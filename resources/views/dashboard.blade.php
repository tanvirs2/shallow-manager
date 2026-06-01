@extends('layouts.app')
@section('title', 'ড্যাশবোর্ড')

@section('content')

{{-- Stats --}}
<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-day"></i></div>
                    <div class="overflow-hidden">
                        <div class="text-muted" style="font-size:.75rem;">আজকের কালেকশন</div>
                        <div class="fw-bold fs-6">৳{{ number_format($todayCollection, 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-calendar-month"></i></div>
                    <div class="overflow-hidden">
                        <div class="text-muted" style="font-size:.75rem;">মাসিক কালেকশন</div>
                        <div class="fw-bold fs-6">৳{{ number_format($monthCollection, 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-receipt"></i></div>
                    <div class="overflow-hidden">
                        <div class="text-muted" style="font-size:.75rem;">মোট বিল</div>
                        <div class="fw-bold fs-6">৳{{ number_format($totalBilled, 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="overflow-hidden">
                        <div class="text-muted" style="font-size:.75rem;">মোট বাকি</div>
                        <div class="fw-bold fs-6 text-danger">৳{{ number_format($totalDue, 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-2 mb-3 d-none d-md-flex">
    <div class="col-md-3">
        <a href="{{ route('water-entries.create') }}" class="btn btn-primary w-100">
            <i class="bi bi-plus-circle me-1"></i>নতুন এন্ট্রি
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('payments.create') }}" class="btn btn-success w-100">
            <i class="bi bi-cash-stack me-1"></i>পেমেন্ট নিন
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('farmers.create') }}" class="btn btn-outline-primary w-100">
            <i class="bi bi-person-plus me-1"></i>নতুন কৃষক
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary w-100">
            <i class="bi bi-bar-chart me-1"></i>রিপোর্ট
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Today's Entries --}}
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                <span class="fw-semibold small"><i class="bi bi-droplet-half me-1 text-primary"></i>আজকের এন্ট্রি</span>
                <a href="{{ route('water-entries.create') }}" class="btn btn-primary btn-sm">+ নতুন</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($todayEntries as $entry)
                <a href="{{ route('water-entries.show', $entry) }}" class="list-group-item list-group-item-action px-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">{{ $entry->farmer->name }}</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ $entry->hours }} ঘণ্টা × ৳{{ $entry->rate_per_hour }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold small">৳{{ number_format($entry->total_amount, 0) }}</div>
                            {!! $entry->status_badge !!}
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center text-muted py-4 small"><i class="bi bi-inbox fs-3 d-block mb-1"></i>আজ কোনো এন্ট্রি নেই</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top Due --}}
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2">
                <span class="fw-semibold small"><i class="bi bi-exclamation-circle me-1 text-danger"></i>বাকি আছে</span>
            </div>
            <div class="list-group list-group-flush">
                @forelse($topDue as $farmer)
                <div class="list-group-item px-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">{{ $farmer->name }}</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ $farmer->mobile }}</div>
                        </div>
                        <div class="text-end d-flex align-items-center gap-2">
                            <span class="fw-bold text-danger small">৳{{ number_format($farmer->total_due, 0) }}</span>
                            <a href="{{ route('payments.create', ['farmer_id' => $farmer->id]) }}" class="btn btn-outline-success btn-sm btn-action">পেমেন্ট</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4 small"><i class="bi bi-check-circle fs-3 d-block mb-1 text-success"></i>সবার পেমেন্ট ক্লিয়ার!</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                <span class="fw-semibold small"><i class="bi bi-cash me-1 text-success"></i>সাম্প্রতিক পেমেন্ট</span>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm">সব</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentPayments as $payment)
                <div class="list-group-item px-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">{{ $payment->farmer->name }}</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ $payment->payment_date->format('d/m/Y') }} · {{ $payment->method_label }}</div>
                        </div>
                        <div class="fw-bold text-success small">৳{{ number_format($payment->amount, 0) }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4 small">কোনো পেমেন্ট নেই</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
