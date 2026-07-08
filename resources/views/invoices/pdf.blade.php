<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 30px 36px; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1a1a1a; }
        .confidential { text-align: right; font-size: 10px; color: #888; margin-bottom: 4px; }
        h1.title { text-align: center; font-size: 20px; letter-spacing: 4px; margin: 0 0 20px 0; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .header-table td { vertical-align: top; padding: 4px 0; }
        .label { color: #888; font-size: 10px; text-transform: uppercase; }
        .value { font-size: 13px; font-weight: bold; }

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .meta-table th {
            background: #2d2d2d; color: #fff; font-size: 10px; text-transform: uppercase;
            padding: 6px 8px; text-align: left;
        }
        .meta-table td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 12px; }

        table.items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.items th {
            background: #2d2d2d; color: #fff; font-size: 10px; text-transform: uppercase;
            padding: 8px; text-align: left;
        }
        table.items td { padding: 8px; border-bottom: 1px solid #eee; font-size: 12px; }
        table.items td.num, table.items th.num { text-align: right; }

        .totals { width: 100%; margin-top: 12px; }
        .totals table { width: 45%; margin-left: 55%; border-collapse: collapse; }
        .totals td { padding: 6px 8px; font-size: 12px; }
        .totals tr.grand td { font-weight: bold; font-size: 14px; border-top: 2px solid #2d2d2d; }
        .totals tr td:last-child { text-align: right; }
    </style>
</head>
<body>
    <div class="confidential">Confidential</div>
    <h1 class="title">INVOICE</h1>

    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="label">Company name and contact details</div>
                <div class="value">{{ $invoice->company_name }}</div>
                @if ($invoice->company_address)
                    <div style="white-space: pre-line;">{{ $invoice->company_address }}</div>
                @endif
            </td>
            <td style="width: 40%;">
                <div class="label">Date</div>
                <div class="value">{{ $invoice->invoice_date->format('n/j/Y') }}</div>
                <div class="label" style="margin-top:8px;">Invoice #</div>
                <div class="value">{{ $invoice->invoice_number }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 10px;">
                <div class="label">To</div>
                <div class="value">{{ $invoice->client_name }}</div>
                @if ($invoice->client_address)
                    <div style="white-space: pre-line;">{{ $invoice->client_address }}</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <th>Driver name</th>
            <th>Payment terms</th>
            <th>Due date</th>
        </tr>
        <tr>
            <td>{{ $invoice->driver_name ?: '—' }}</td>
            <td>{{ $invoice->payment_terms ?: '—' }}</td>
            <td>{{ $invoice->due_date ? $invoice->due_date->format('n/j/Y') : '—' }}</td>
        </tr>
    </table>

    <table class="items">
        <tr>
            <th style="width: 10%;">Qty</th>
            <th style="width: 50%;">Description</th>
            <th class="num" style="width: 20%;">Day / Rate or cost</th>
            <th class="num" style="width: 20%;">Total</th>
        </tr>
        @foreach ($invoice->items as $item)
            <tr>
                <td>{{ rtrim(rtrim(number_format($item->qty, 2), '0'), '.') }}</td>
                <td>{{ $item->description }}</td>
                <td class="num">{{ number_format($item->rate, 2) }}</td>
                <td class="num">{{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td>{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Tax &amp; bank charges</td>
                <td>{{ number_format($invoice->tax_bank_charges, 2) }}</td>
            </tr>
            <tr class="grand">
                <td>Total</td>
                <td>{{ number_format($invoice->total, 2) }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
