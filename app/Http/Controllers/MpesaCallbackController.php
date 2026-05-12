<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{
    public function __invoke(Request $request, TicketService $tickets): Response
    {
        $payload = $request->json()->all();

        Log::info('M-Pesa callback received', ['payload' => $payload]);

        $body = $payload['Body']['stkCallback'] ?? null;

        if (! $body) {
            Log::warning('M-Pesa callback missing Body.stkCallback');
            return response('', 200);
        }

        $checkoutRequestId = $body['CheckoutRequestID'] ?? null;
        $resultCode        = $body['ResultCode'] ?? null;

        if (! $checkoutRequestId) {
            Log::warning('M-Pesa callback missing CheckoutRequestID');
            return response('', 200);
        }

        $order = Order::where('mpesa_checkout_request_id', $checkoutRequestId)->first();

        if (! $order) {
            Log::error('M-Pesa callback: order not found', ['checkout_request_id' => $checkoutRequestId]);
            return response('', 200);
        }

        if ($order->isPaid()) {
            Log::info('M-Pesa callback: order already paid', ['order' => $order->order_number]);
            return response('', 200);
        }

        if ($resultCode !== 0) {
            Log::warning('M-Pesa callback: payment failed', [
                'order'       => $order->order_number,
                'result_code' => $resultCode,
                'result_desc' => $body['ResultDesc'] ?? null,
            ]);

            $order->update([
                'payment_status' => \App\Enums\PaymentStatus::FAILED,
                'mpesa_response' => $payload,
            ]);

            return response('', 200);
        }

        $callbackMetadata = collect($body['CallbackMetadata']['Item'] ?? [])
            ->keyBy('Name')
            ->map(fn ($item) => $item['Value'] ?? null);

        $mpesaReceiptNumber = $callbackMetadata->get('MpesaReceiptNumber');
        $amount             = (float) ($callbackMetadata->get('Amount') ?? 0);
        $expectedAmount     = (float) ceil((float) $order->total);

        if ($amount < $expectedAmount) {
            Log::error('M-Pesa callback: amount mismatch', [
                'order'    => $order->order_number,
                'expected' => $expectedAmount,
                'received' => $amount,
            ]);

            $order->update([
                'payment_status' => \App\Enums\PaymentStatus::FAILED,
                'mpesa_response' => $payload,
            ]);

            return response('', 200);
        }

        $order->markPaid(
            paymentReference: $mpesaReceiptNumber,
            mpesaReceipt: $mpesaReceiptNumber,
            callbackPayload: $payload,
        );

        $tickets->generateForOrder($order);

        Log::info('M-Pesa payment complete — tickets generated', [
            'order'   => $order->order_number,
            'receipt' => $mpesaReceiptNumber,
        ]);

        return response('', 200);
    }
}
