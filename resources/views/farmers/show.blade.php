@extends('layouts.app')
@section('title', $farmer->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ $farmer->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('farmers.edit', $farmer) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil me-1"></i>এডিট</a>
        <a href="{{ route('payments.create', ['farmer_id' => $farmer->id]) }}" class="btn btn-success btn-sm"><i class="bi bi-cash me-1"></i>পেমেন্ট নিন</a>
        <a href="{{ route('invoices.farmer-bill', $farmer) }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-pdf me-1"></i>বিল PDF</a>
        <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted small text-uppercase mb-3">ব্যক্তিগত তথ্য</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">মোবাইল</td><td class="fw-semibold">{{ $farmer->mobile }}</td></tr>
                    <tr><td class="text-muted">গ্রাম</td><td>{{ $farmer->village ?? '—' }}</td></tr>
                    <tr><td class="text-muted">ইউনিয়ন</td><td>{{ $farmer->union ?? '—' }}</td></tr>
                    <tr><td class="text-muted">উপজেলা</td><td>{{ $farmer->upazila ?? '—' }}</td></tr>
                    <tr><td class="text-muted">জমি</td><td>{{ $farmer->land_area }} {{ $farmer->land_unit }}</td></tr>
                    <tr><td class="text-muted">NID</td><td>{{ $farmer->nid ?? '—' }}</td></tr>
                    <tr><td class="text-muted">অবস্থা</td>
                        <td>@if($farmer->is_active)<span class="badge bg-success">সক্রিয়</span>@else<span class="badge bg-secondary">নিষ্ক্রিয়</span>@endif</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="row g-3">
            <div class="col-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-4 fw-bold text-primary">৳{{ number_format($farmer->total_billed, 0) }}</div>
                        <div class="text-muted small">মোট বিল</div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-4 fw-bold text-success">৳{{ number_format($farmer->total_paid, 0) }}</div>
                        <div class="text-muted small">মোট পেমেন্ট</div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-4 fw-bold {{ $farmer->total_due > 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($farmer->total_due, 0) }}</div>
                        <div class="text-muted small">বাকি</div>
                    </div>
                </div>
            </div>
        </div>

        @if($farmer->land_description)
        <div class="card shadow-sm mt-3">
            <div class="card-body py-2">
                <div class="text-muted small mb-1">জমির বিবরণ</div>
                <p class="mb-0">{{ $farmer->land_description }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Water Entries --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between">
        <span class="fw-semibold"><i class="bi bi-droplet-half me-2 text-primary"></i>পানি সরবরাহ ইতিহাস</span>
        <a href="{{ route('water-entries.create') }}?farmer_id={{ $farmer->id }}" class="btn btn-primary btn-sm">+ নতুন এন্ট্রি</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>তারিখ</th><th>ঘণ্টা</th><th>রেট</th><th>মোট</th><th>পরিশোধ</th><th>বাকি</th><th>স্ট্যাটাস</th><th>কাজ</th></tr>
            </thead>
            <tbody>
                @forelse($farmer->waterEntries as $entry)
                <tr>
                    <td>{{ $entry->supply_date->format('d/m/Y') }}</td>
                    <td>{{ $entry->hours }}</td>
                    <td>৳{{ $entry->rate_per_hour }}</td>
                    <td class="fw-semibold">৳{{ number_format($entry->total_amount, 0) }}</td>
                    <td class="text-success">৳{{ number_format($entry->paid_amount, 0) }}</td>
                    <td class="{{ $entry->due_amount > 0 ? 'text-danger' : '' }}">৳{{ number_format($entry->due_amount, 0) }}</td>
                    <td>{!! $entry->status_badge !!}</td>
                    <td>
                        <a href="{{ route('invoices.show', $entry) }}" class="btn btn-outline-info btn-action"><i class="bi bi-receipt"></i></a>
                        <a href="{{ route('invoices.pdf', $entry) }}" class="btn btn-outline-danger btn-action"><i class="bi bi-file-pdf"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">কোনো এন্ট্রি নেই</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Payments --}}
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between">
        <span class="fw-semibold"><i class="bi bi-cash me-2 text-success"></i>পেমেন্ট হিস্ট্রি</span>
        <a href="{{ route('payments.create', ['farmer_id' => $farmer->id]) }}" class="btn btn-success btn-sm">+ পেমেন্ট</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>তারিখ</th><th>পরিমাণ</th><th>মাধ্যম</th><th>রেফারেন্স</th><th>মন্তব্য</th><th>কাজ</th></tr>
            </thead>
            <tbody>
                @forelse($farmer->payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                    <td class="fw-semibold text-success">৳{{ number_format($payment->amount, 0) }}</td>
                    <td>{{ $payment->method_label }}</td>
                    <td>{{ $payment->reference ?? '—' }}</td>
                    <td>{{ $payment->notes ?? '—' }}</td>
                    <td>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('মুছে ফেলবেন?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-action"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">কোনো পেমেন্ট নেই</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
