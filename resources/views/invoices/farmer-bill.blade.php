<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
    .header { text-align: center; border-bottom: 2px solid #1a3a5c; padding-bottom: 10px; margin-bottom: 15px; }
    .header h2 { margin: 0; color: #1a3a5c; font-size: 18px; }
    .header p { margin: 2px 0; color: #666; font-size: 11px; }
    .farmer-box { background: #f0f4f8; padding: 10px; border-radius: 4px; margin-bottom: 15px; display: flex; justify-content: space-between; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    th { background: #1a3a5c; color: white; padding: 6px 8px; text-align: left; font-size: 11px; }
    td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .total-row { background: #f0f4f8; font-weight: bold; }
    .due-row { background: #fee2e2; font-weight: bold; font-size: 13px; }
    .paid-row { background: #d1fae5; font-weight: bold; }
    .footer { text-align: center; border-top: 1px solid #e2e8f0; padding-top: 10px; color: #999; font-size: 10px; margin-top: 20px; }
    .badge-paid { background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 3px; }
    .badge-partial { background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 3px; }
    .badge-due { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 3px; }
</style>
</head>
<body>
<div class="header">
    <h2>{{ optional($owner)->pump_name ?? 'শ্যালো সেচ ব্যবস্থাপনা' }}</h2>
    @if($owner)
    <p>{{ $owner->village }}, {{ $owner->address }} | মোবাইল: {{ $owner->mobile }}</p>
    @endif
    <h4 style="margin:8px 0 0;">মাসিক বিল — {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</h4>
</div>

<div class="farmer-box">
    <div>
        <strong>{{ $farmer->name }}</strong><br>
        মোবাইল: {{ $farmer->mobile }}<br>
        গ্রাম: {{ $farmer->village ?? '—' }}<br>
        জমি: {{ $farmer->land_area }} {{ $farmer->land_unit }}
    </div>
    <div style="text-align:right;">
        <div>মোট বিল: <strong>৳{{ number_format($totalBilled, 2) }}</strong></div>
        <div>মোট দেওয়া: <strong>৳{{ number_format($totalPaid, 2) }}</strong></div>
        <div style="color:{{ $totalDue > 0 ? '#dc2626' : '#16a34a' }}; font-size:14px; font-weight:bold;">
            বাকি: ৳{{ number_format($totalDue, 2) }}
        </div>
    </div>
</div>

<table>
    <tr>
        <th>তারিখ</th>
        <th class="text-center">ঘণ্টা</th>
        <th class="text-right">রেট</th>
        <th class="text-right">মোট বিল</th>
        <th class="text-right">পরিশোধ</th>
        <th class="text-right">বাকি</th>
        <th class="text-center">অবস্থা</th>
    </tr>
    @foreach($entries as $entry)
    <tr>
        <td>{{ $entry->supply_date->format('d/m/Y') }}</td>
        <td class="text-center">{{ $entry->hours }}</td>
        <td class="text-right">৳{{ $entry->rate_per_hour }}</td>
        <td class="text-right">৳{{ number_format($entry->total_amount, 2) }}</td>
        <td class="text-right">৳{{ number_format($entry->paid_amount, 2) }}</td>
        <td class="text-right">৳{{ number_format($entry->due_amount, 2) }}</td>
        <td class="text-center">
            @php $s = $entry->payment_status; @endphp
            <span class="badge-{{ $s }}">{{ $s === 'paid' ? 'পরিশোধ' : ($s === 'partial' ? 'আংশিক' : 'বাকি') }}</span>
        </td>
    </tr>
    @endforeach
    <tr class="total-row">
        <td colspan="3" class="text-right">মোট</td>
        <td class="text-right">৳{{ number_format($totalBilled, 2) }}</td>
        <td class="text-right">৳{{ number_format($totalPaid, 2) }}</td>
        <td class="text-right">৳{{ number_format($totalDue, 2) }}</td>
        <td></td>
    </tr>
</table>

<div class="footer">ধন্যবাদ আপনার সহযোগিতার জন্য | তৈরি: {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
