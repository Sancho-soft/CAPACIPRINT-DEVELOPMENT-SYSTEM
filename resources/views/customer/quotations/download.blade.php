<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quotation #{{ $quotation->quotation_number }} — CAPACIPRINT</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2c3a; margin: 40px; }
        h1 { font-size: 22px; } table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px 14px; text-align: left; font-size: 13px; }
        th { background: #f8fafc; font-weight: 700; }
        .total td { font-weight: 900; background: #e8f7fd; font-size: 16px; }
    </style>
</head>
<body>
    <h1>CAPACIPRINT — Quotation</h1>
    <p><strong>#{{ $quotation->quotation_number }}</strong> &nbsp;|&nbsp; Date: {{ $quotation->created_at->format('F d, Y') }}</p>
    <table>
        <tr><th>Service</th><td>{{ $quotation->printRequest->service ?? '—' }}</td></tr>
        <tr><th>Quantity</th><td>{{ $quotation->printRequest->quantity ?? '—' }} copies</td></tr>
        <tr><th>Size</th><td>{{ $quotation->printRequest->size ?? '—' }}</td></tr>
        <tr><th>Material</th><td>{{ $quotation->printRequest->material ?? '—' }}</td></tr>
        <tr><th>Finishing</th><td>{{ $quotation->printRequest->finishing ?? '—' }}</td></tr>
        <tr><th>Base Cost</th><td>₱{{ number_format($quotation->base_cost, 2) }}</td></tr>
        <tr><th>Material Cost</th><td>₱{{ number_format($quotation->material_cost, 2) }}</td></tr>
        <tr><th>Finishing Cost</th><td>₱{{ number_format($quotation->finishing_cost, 2) }}</td></tr>
        <tr class="total"><th>Total (VAT Inc.)</th><td>₱{{ number_format($quotation->total_price, 2) }}</td></tr>
        <tr><th>Valid Until</th><td>{{ $quotation->valid_until?->format('F d, Y') ?? 'N/A' }}</td></tr>
    </table>
    <p style="margin-top:30px; font-size:12px; color:#64748b;">CAPACIPRINT – Intelligent Print Routing System</p>
    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
