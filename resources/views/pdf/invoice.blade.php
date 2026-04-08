<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header-table {
            width: 100%;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #2f5fa7;
            text-align: right;
        }

        .line {
            border-bottom: 2px solid #2f5fa7;
            margin: 10px 0;
        }

        .info-table {
            width: 100%;
            margin-top: 20px;
        }

        .info-table td {
            vertical-align: top;
        }

        .invoice-details {
            text-align: right;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background: #2f5fa7;
            color: #fff;
            padding: 8px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .table tr:nth-child(even) {
            background: #f4f7fb;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .total-table {
            width: 100%;
            margin-top: 15px;
        }

        .total-table td {
            padding: 6px;
            font-size: 13px;
        }

        .grand-total {
            background: #2f5fa7;
            color: #fff;
            padding: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
        }

        .signature {
            text-align: right;
            margin-top: 40px;
        }
    </style>
</head>

<body>

@php
    $invoiceNumber = $invoice->invoice_number ?? $invoice->id;
    $invoiceDate = optional($invoice->created_at)->format('d M Y');
    $customerName = $user->name ?? 'Customer';
    $customerEmail = $user->email ?? '';
    $paymentMethod = $payment->payment_method ?? 'N/A';

    $subTotal = collect($items)->sum('total');
    $taxAmount = 0;
    $grandTotal = $invoice->amount ?? $subTotal;
@endphp

<!-- Header -->
<table class="header-table">
    <tr>
        <td>
            <strong style="font-size:18px;">Your Company</strong><br>
            <small>your@email.com</small>
        </td>
        <td class="title">INVOICE</td>
    </tr>
</table>

<div class="line"></div>

<!-- Info -->
<table class="info-table">
    <tr>
        <td>
            <strong>Invoice To:</strong><br><br>
            <strong>{{ $customerName }}</strong><br>
            {{ $customerEmail }}
        </td>

        <td class="invoice-details">
            <strong>Invoice #:</strong> {{ $invoiceNumber }}<br>
            <strong>Date:</strong> {{ $invoiceDate }}<br>
            <strong>Payment:</strong> {{ ucfirst($paymentMethod) }}
        </td>
    </tr>
</table>

<!-- Items Table -->
<table class="table">
    <thead>
        <tr>
            <th>NO</th>
            <th>DESCRIPTION</th>
            <th>QTY</th>
            <th>PRICE</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $item)
            <tr>
                <td class="text-center">{{ $item['no'] }}</td>
                <td>
                    <strong>{{ $item['name'] }}</strong>
                </td>
                <td class="text-center">{{ $item['quantity'] }}</td>
                <td class="text-right">${{ number_format($item['price'], 2) }}</td>
                <td class="text-right">${{ number_format($item['total'], 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No items found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Totals -->
<table class="total-table">
    <tr>
        <td class="text-right">Sub Total:</td>
        <td class="text-right">${{ number_format($subTotal, 2) }}</td>
    </tr>

    <tr>
        <td class="text-right">Tax:</td>
        <td class="text-right">${{ number_format($taxAmount, 2) }}</td>
    </tr>

    <tr>
        <td class="grand-total">Grand Total:</td>
        <td class="grand-total text-right">${{ number_format($grandTotal, 2) }}</td>
    </tr>
</table>

<!-- Footer -->
<div class="footer">
    <strong>Note:</strong><br>
    Thank you for your purchase. This is a system-generated invoice.

    <br><br>

    <strong>Terms:</strong><br>
    Payment is non-refundable after successful delivery.
</div>

<div class="signature">
    <br><br>
    ___________________________<br>
    Authorized Signature
</div>

</body>
</html>