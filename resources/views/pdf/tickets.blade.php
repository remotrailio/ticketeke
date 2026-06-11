<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets — {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            font-size: 13px;
        }

        /* ── Header ─────────────────────────────────────────────────────── */
        .header {
            background: #4f46e5;
            color: #ffffff;
            padding: 28px 36px;
            margin-bottom: 24px;
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-meta {
            text-align: right;
            font-size: 11px;
            opacity: 0.85;
            line-height: 1.6;
        }

        /* ── Ticket card ─────────────────────────────────────────────────── */
        .ticket-wrap {
            padding: 0 36px;
            margin-bottom: 28px;
            page-break-inside: avoid;
        }

        .ticket {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .ticket-top {
            display: flex;
            background: #ffffff;
            padding: 20px 24px;
            border-bottom: 2px dashed #e2e8f0;
        }

        .ticket-info {
            flex: 1;
        }

        .event-name {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .ticket-type {
            display: inline-block;
            background: #ede9fe;
            color: #4f46e5;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        .meta-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 5px;
            font-size: 12px;
            color: #475569;
            line-height: 1.4;
        }

        .meta-label {
            width: 80px;
            font-weight: 600;
            color: #94a3b8;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-top: 1px;
        }

        .qr-section {
            width: 150px;
            text-align: center;
            padding-left: 16px;
        }

        .qr-section img {
            width: 140px;
            height: 140px;
        }

        .qr-label {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Ticket bottom bar ───────────────────────────────────────────── */
        .ticket-bottom {
            background: #f8fafc;
            padding: 10px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-code {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #4f46e5;
        }

        .ticket-num {
            font-size: 10px;
            color: #94a3b8;
        }

        /* ── Order summary ───────────────────────────────────────────────── */
        .summary {
            margin: 0 36px 28px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .summary-header {
            background: #0f172a;
            color: #ffffff;
            padding: 10px 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 20px;
            font-size: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-row:last-child { border-bottom: none; }

        .summary-total {
            font-weight: 700;
            color: #4f46e5;
            font-size: 14px;
        }

        /* ── Footer ──────────────────────────────────────────────────────── */
        .footer {
            text-align: center;
            padding: 16px 36px 24px;
            font-size: 10px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer strong { color: #4f46e5; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-inner">
            <div class="brand">{{ app_settings()->app_name }}</div>
            <div class="header-meta">
                Order #{{ $order->order_number }}<br>
                {{ $order->paid_at?->format('d M Y, H:i') }}<br>
                Receipt: {{ $order->mpesa_receipt_number ?? '—' }}
            </div>
        </div>
    </div>

    {{-- Tickets --}}
    @foreach($order->tickets as $i => $ticket)
    @php
        $event    = $order->event;
        $typeName = $ticket->orderItem?->ticketType?->name ?? 'General Admission';

        // QR encodes a signed URL — tamper-proof, expires when the event ends (+ 1 day grace).
        // Never encode raw IDs; ticket_code is the only external identifier.
        $qrExpiry   = $event->end_at?->addDay() ?? now()->addDays(30);
        $qrUrl      = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'tickets.verify',
            $qrExpiry,
            ['ticket_code' => $ticket->ticket_code]
        );
        $qr = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(400)->margin(3)->errorCorrection('M')->generate($qrUrl));
    @endphp

    <div class="ticket-wrap">
        <div class="ticket">
            <div class="ticket-top">
                <div class="ticket-info">
                    <div class="event-name">{{ $event->title }}</div>
                    <div class="ticket-type">{{ $typeName }}</div>

                    <div class="meta-row">
                        <div class="meta-label">Date</div>
                        <div>{{ $event->start_at?->format('D, d M Y · H:i') ?? '—' }}</div>
                    </div>
                    @if($event->venue_name)
                    <div class="meta-row">
                        <div class="meta-label">Venue</div>
                        <div>{{ $event->venue_name }}@if($event->city), {{ $event->city }}@endif</div>
                    </div>
                    @endif
                    <div class="meta-row">
                        <div class="meta-label">Attendee</div>
                        <div>{{ $ticket->attendee_name ?? $order->user?->name ?? '—' }}</div>
                    </div>
                </div>

                <div class="qr-section">
                    <img src="data:image/png;base64,{{ $qr }}" alt="QR Code">
                    <div class="qr-label">Scan at entry</div>
                </div>
            </div>

            <div class="ticket-bottom">
                <div class="ticket-code">{{ $ticket->ticket_code }}</div>
                <div class="ticket-num">Ticket {{ $i + 1 }} of {{ $order->tickets->count() }}</div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Order summary --}}
    <div class="summary">
        <div class="summary-header">Order Summary</div>
        @foreach($order->items as $item)
        <div class="summary-row">
            <span>{{ $item->ticketType?->name }} × {{ $item->quantity }}</span>
            <span>{{ strtoupper($order->currency) }} {{ number_format($item->subtotal, 2) }}</span>
        </div>
        @endforeach
        @if($order->fees > 0)
        <div class="summary-row">
            <span>Platform fee</span>
            <span>{{ strtoupper($order->currency) }} {{ number_format($order->fees, 2) }}</span>
        </div>
        @endif
        <div class="summary-row">
            <span class="summary-total">Total paid</span>
            <span class="summary-total">{{ strtoupper($order->currency) }} {{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Present this ticket (printed or digital) at the event entrance.<br>
        Each ticket code is unique and valid for one entry only.<br><br>
        <strong>{{ app_settings()->app_name }}</strong> · Questions? Contact the event organiser.
    </div>

</body>
</html>
