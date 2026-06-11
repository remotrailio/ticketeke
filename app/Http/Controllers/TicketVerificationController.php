<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketVerificationController extends Controller
{
    public function __invoke(Request $request, string $ticket_code): JsonResponse|View
    {
        $ticket = Ticket::with(['order.event'])
            ->where('ticket_code', $ticket_code)
            ->first();

        if (!$ticket) {
            return $this->respond(false, 'Ticket not found.', $request);
        }

        if ($ticket->order->payment_status !== PaymentStatus::PAID) {
            return $this->respond(false, 'Ticket does not belong to a paid order.', $request);
        }

        if ($ticket->checked_in_at !== null) {
            return $this->respond(false, 'Ticket has already been used.', $request, [
                'checked_in_at' => $ticket->checked_in_at->toDateTimeString(),
            ]);
        }

        $ticket->checkIn();

        return $this->respond(true, 'Access granted.', $request, [
            'ticket' => [
                'ticket_code'   => $ticket->ticket_code,
                'attendee_name' => $ticket->attendee_name,
                'event'         => $ticket->order->event->title,
            ],
        ]);
    }

    private function respond(bool $valid, string $message, Request $request, array $extra = []): JsonResponse|View
    {
        $payload = array_merge(compact('valid', 'message'), $extra);

        if ($request->wantsJson()) {
            return response()->json($payload, $valid ? 200 : 422);
        }

        return view('checkin.result', $payload);
    }
}
