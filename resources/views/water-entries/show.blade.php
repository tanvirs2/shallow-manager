@extends('layouts.app')
@section('title', 'এন্ট্রি বিস্তারিত')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">এন্ট্রি বিস্তারিত</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('water-entries.edit', $waterEntry) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil me-1"></i>এডিট</a>
        <a href="{{ route('invoices.show', $waterEntry) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-receipt me-1"></i>ইনভয়েস</a>
        <a href="{{ route('invoices.pdf', $waterEntry) }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>PDF</a>
        <a href="{{ route('water-entries.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">এন্ট্রি তথ্য</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted">কৃষক</td><td class="fw-semibold">
                        <a href="{{ route('farmers.show', $waterEntry->farmer) }}">{{ $waterEntry->farmer->name }}</a></td></tr>
                    <tr><td class="text-muted">তারিখ</td><td>{{ $waterEntry->supply_date->format('d F Y') }}</td></tr>
                    <tr><td class="text-muted">ঘণ্টা</td><td>{{ $waterEntry->hours }} ঘণ্টা</td></tr>
                    <tr><td class="text-muted">রেট</td><td>৳{{ $waterEntry->rate_per_hour }}/ঘণ্টা</td></tr>
                    <tr><td class="text-muted">মোট বিল</td><td class="fw-bold fs-5">৳{{ number_format($waterEntry->total_amount, 2) }}</td></tr>
                    <tr><td class="text-muted">পরিশোধ</td><td class="text-success fw-semibold">৳{{ number_format($waterEntry->paid_amount, 2) }}</td></tr>
                    <tr><td class="text-muted">বাকি</td><td class="{{ $waterEntry->due_amount > 0 ? 'text-danger' : 'text-success' }} fw-semibold">৳{{ number_format($waterEntry->due_amount, 2) }}</td></tr>
                    <tr><td class="text-muted">স্ট্যাটাস</td><td>{!! $waterEntry->status_badge !!}</td></tr>
                    @if($waterEntry->season)
                    <tr><td class="text-muted">মৌসুম</td><td>{{ $waterEntry->season }}</td></tr>
                    @endif
                    @if($waterEntry->notes)
                    <tr><td class="text-muted">মন্তব্য</td><td>{{ $waterEntry->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <span class="fw-semibold">পেমেন্ট রেকর্ড</span>
                <a href="{{ route('payments.create', ['farmer_id' => $waterEntry->farmer_id, 'water_entry_id' => $waterEntry->id]) }}" class="btn btn-success btn-sm">+ পেমেন্ট</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>তারিখ</th><th>পরিমাণ</th><th>মাধ্যম</th><th>রেফারেন্স</th></tr></thead>
                    <tbody>
                        @forelse($waterEntry->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-success">৳{{ number_format($payment->amount, 0) }}</td>
                            <td>{{ $payment->method_label }}</td>
                            <td>{{ $payment->reference ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">কোনো পেমেন্ট নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
