<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<style>
    @font-face {
        font-family: 'Hind Siliguri';
        src: local('Arial');
    }
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
    .header { text-align: center; border-bottom: 2px solid #1a3a5c; padding-bottom: 10px; margin-bottom: 15px; }
    .header h2 { margin: 0; color: #1a3a5c; font-size: 18px; }
    .header p { margin: 2px 0; color: #666; font-size: 11px; }
    .invoice-meta { display: flex; justify-content: space-between; margin-bottom: 15px; }
    .farmer-box { background: #f0f4f8; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    th { background: #1a3a5c; color: white; padding: 6px 8px; text-align: left; font-size: 11px; }
    td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .total-row { background: #f0f4f8; font-weight: bold; }
    .due-row { background: #fee2e2; font-weight: bold; font-size: 13px; }
    .paid-row { background: #d1fae5; font-weight: bold; }
    .footer { text-align: center; border-top: 1px solid #e2e8f0; padding-top: 10px; color: #999; font-size: 10px; margin-top: 20px; }
</style>
</head>
<body>
<div class="header">
    <h2>{{ optional($owner)->pump_name ?? 'সেলো সেচ ব্যবস্থাপনা' }}</h2>
    @if($owner)
    <p>{{ $owner->village }}, {{ $owner->address }}</p>
    <p>মোবাইল: {{ $owner->mobile }}</p>
    @endif
</div>

<div class="invoice-meta">
    <div><strong>ইনভয়েস নং:</strong> #INV-{{ str_pad($waterEntry->id, 5, '0', STR_PAD_LEFT) }}</div>
    <div><strong>তারিখ:</strong> {{ $waterEntry->supply_date->format('d/m/Y') }}</div>
</div>

<div class="farmer-box">
    <strong>{{ $waterEntry->farmer->name }}</strong><br>
    মোবাইল: {{ $waterEntry->farmer->mobile }}<br>
    গ্রাম: {{ $waterEntry->farmer->village ?? '—' }}
</div>

<table>
    <tr>
        <th>বিবরণ</th>
        <th class="text-center">ঘণ্টা</th>
        <th class="text-right">রেট (৳/ঘণ্টা)</th>
        <th class="text-right">মোট (৳)</th>
    </tr>
    <tr>
        <td>পানি সরবরাহ (সেচ){{ $waterEntry->season ? ' — ' . $waterEntry->season : '' }}</td>
        <td class="text-center">{{ $waterEntry->hours }}</td>
        <td class="text-right">{{ number_format($waterEntry->rate_per_hour, 2) }}</td>
        <td class="text-right">{{ number_format($waterEntry->total_amount, 2) }}</td>
    </tr>
    <tr class="total-row">
        <td colspan="3" class="text-right">মোট বিল</td>
        <td class="text-right">৳{{ number_format($waterEntry->total_amount, 2) }}</td>
    </tr>
    <tr class="paid-row">
        <td colspan="3" class="text-right">মোট পরিশোধ</td>
        <td class="text-right">৳{{ number_format($waterEntry->paid_amount, 2) }}</td>
    </tr>
    <tr class="{{ $waterEntry->due_amount > 0 ? 'due-row' : 'paid-row' }}">
        <td colspan="3" class="text-right">বাকি</td>
        <td class="text-right">৳{{ number_format($waterEntry->due_amount, 2) }}</td>
    </tr>
</table>

@if($waterEntry->payments->count())
<p><strong>পেমেন্ট রেকর্ড:</strong></p>
<table>
    <tr><th>তারিখ</th><th>মাধ্যম</th><th class="text-right">পরিমাণ</th></tr>
    @foreach($waterEntry->payments as $p)
    <tr>
        <td>{{ $p->payment_date->format('d/m/Y') }}</td>
        <td>{{ $p->method_label }}</td>
        <td class="text-right">৳{{ number_format($p->amount, 2) }}</td>
    </tr>
    @endforeach
</table>
@endif

<div class="footer">ধন্যবাদ আপনার সহযোগিতার জন্য | তৈরি: {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
