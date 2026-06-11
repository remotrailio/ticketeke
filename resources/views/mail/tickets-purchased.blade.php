<x-mail::message>
# You're going! 🎉

Hi {{ $order->user->name }},

Your payment was confirmed and your tickets for **{{ $order->event->title }}** are ready. Find them attached to this email as a PDF.

<x-mail::panel>
**Order:** #{{ $order->order_number }}
**Event:** {{ $order->event->title }}
**Date:** {{ $order->event->starts_at?->format('D, d M Y · H:i') ?? 'TBA' }}
@if($order->event->venue_name)
**Venue:** {{ $order->event->venue_name }}@if($order->event->city), {{ $order->event->city }}@endif
@endif
**Tickets:** {{ $order->tickets->count() }}
**Total paid:** {{ strtoupper($order->currency) }} {{ number_format($order->total, 2) }}
@if($order->mpesa_receipt_number)
**M-Pesa receipt:** {{ $order->mpesa_receipt_number }}
@endif
</x-mail::panel>

Print or show your ticket PDF at the entrance. Each ticket has a unique QR code — do not share it.

<x-mail::button url="{{ route('orders.confirmation', $order->uuid) }}" color="primary">
View your tickets online
</x-mail::button>

See you at the event!

Thanks,
{{ app_settings()->app_name }}
</x-mail::message>
