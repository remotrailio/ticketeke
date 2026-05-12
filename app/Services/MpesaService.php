<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MpesaService
{
    public function getAccessToken(): string
    {
        return cache()->remember('mpesa_access_token', 3500, function () {
            $response = Http::withBasicAuth(
                config('mpesa.consumer_key'),
                config('mpesa.consumer_secret')
            )->get(config('mpesa.base_url') . '/oauth/v1/generate', [
                'grant_type' => 'client_credentials',
            ]);

            if (! $response->successful() || ! $response->json('access_token')) {
                Log::error('M-Pesa token fetch failed', ['body' => $response->body()]);
                throw new RuntimeException('Could not obtain M-Pesa access token.');
            }

            return $response->json('access_token');
        });
    }

    public function initiateStkPush(Order $order, string $phone): array
    {
        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode(
            config('mpesa.shortcode') . config('mpesa.passkey') . $timestamp
        );

        $payload = [
            'BusinessShortCode' => config('mpesa.shortcode'),
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => (int) ceil((float) $order->total),
            'PartyA'            => $phone,
            'PartyB'            => config('mpesa.shortcode'),
            'PhoneNumber'       => $phone,
            'CallBackURL'       => config('mpesa.callback_url'),
            'AccountReference'  => $order->order_number,
            'TransactionDesc'   => 'Ticket Payment – ' . $order->event->title,
        ];

        Log::info('M-Pesa STK push request', [
            'order'  => $order->order_number,
            'phone'  => $phone,
            'amount' => $payload['Amount'],
        ]);

        try {
            $response = Http::withToken($token)
                ->asJson()
                ->acceptJson()
                ->timeout(30)
                ->post(config('mpesa.base_url') . '/mpesa/stkpush/v1/processrequest', $payload);
        } catch (ConnectionException $e) {
            Log::error('M-Pesa STK push connection error', ['error' => $e->getMessage()]);
            throw new RuntimeException('M-Pesa API unreachable. Please try again.');
        }

        $data = $response->json();

        Log::info('M-Pesa STK push response', ['order' => $order->order_number, 'response' => $data]);

        if (isset($data['CheckoutRequestID'])) {
            $order->update([
                'mpesa_checkout_request_id' => $data['CheckoutRequestID'],
                'mpesa_response'            => $data,
                'payment_provider'          => 'mpesa',
                'payment_method'            => 'stk_push',
                'payment_status'            => PaymentStatus::PROCESSING,
            ]);
        } else {
            Log::error('M-Pesa STK push did not return CheckoutRequestID', [
                'order'    => $order->order_number,
                'response' => $data,
            ]);
        }

        return $data;
    }

    public function queryStatus(Order $order): array
    {
        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode(
            config('mpesa.shortcode') . config('mpesa.passkey') . $timestamp
        );

        $response = Http::withToken($token)
            ->asJson()
            ->acceptJson()
            ->timeout(30)
            ->post(config('mpesa.base_url') . '/mpesa/stkpushquery/v1/query', [
                'BusinessShortCode' => config('mpesa.shortcode'),
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'CheckoutRequestID' => $order->mpesa_checkout_request_id,
            ]);

        $data = $response->json();

        Log::info('M-Pesa status query', [
            'order'    => $order->order_number,
            'response' => $data,
        ]);

        return $data;
    }

    public static function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '254' . substr($phone, 1);
        }

        if (str_starts_with($phone, '+')) {
            return ltrim($phone, '+');
        }

        if (! str_starts_with($phone, '254')) {
            return '254' . $phone;
        }

        return $phone;
    }
}
