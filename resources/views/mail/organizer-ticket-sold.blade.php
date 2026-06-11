<x-mail::message>
# New ticket sale

Hi {{ $order->event->organizer->user->name }},

A new order has been placed for **{{ $order->event->title }}**.

<x-mail::panel>
**Order:** #{{ $order->order_number }}
**Buyer:** {{ $order->user->name }} ({{ $order->user->email }})
**Date:** {{ $order->created_at->format('d M Y, H:i') }}
**M-Pesa receipt:** {{ $order->mpesa_receipt_number ?? '—' }}
</x-mail::panel>

**Tickets purchased:**

@foreach($order->items as $item)
- {{ $item->ticketType?->name }} × {{ $item->quantity }} — {{ strtoupper($order->currency) }} {{ number_format($item->subtotal, 2) }}
@endforeach

<x-mail::table>
| | |
|:--|--:|
| Subtotal | {{ strtoupper($order->currency) }} {{ number_format($order->subtotal, 2) }} |
@if($order->fees > 0)
| Platform fee | {{ strtoupper($order->currency) }} {{ number_format($order->fees, 2) }} |
@endif
| **Total** | **{{ strtoupper($order->currency) }} {{ number_format($order->total, 2) }}** |
</x-mail::table>

<x-mail::button url="{{ url('/organizer/orders') }}" color="primary">
View in dashboard
</x-mail::button>

Thanks,
{{ app_settings()->app_name }}
</x-mail::message>
