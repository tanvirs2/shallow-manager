@extends('layouts.app')
@section('title', 'ইনভয়েস #' . $waterEntry->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">ইনভয়েস #{{ $waterEntry->id }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('invoices.pdf', $waterEntry) }}" class="btn btn-danger">
            <i class="bi bi-file-pdf me-1"></i>PDF ডাউনলোড
        </a>
        <a href="{{ route('water-entries.show', $waterEntry) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>ফিরে যান
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow" id="invoice">
            <div class="card-body p-4">
                {{-- Header --}}
                <div class="text-center border-bottom pb-3 mb-3">
                    <h5 class="fw-bold mb-1">{{ optional($owner)->pump_name ?? 'শ্যালো ম্যানেজার' }}</h5>
                    @if($owner)
                    <div class="text-muted small">{{ $owner->village }}, {{ $owner->address }}</div>
                    <div class="small">মোবাইল: {{ $owner->mobile }}</div>
                    @endif
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <div class="text-muted small">ইনভয়েস নম্বর</div>
                        <div class="fw-semibold">#INV-{{ str_pad($waterEntry->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">তারিখ</div>
                        <div class="fw-semibold">{{ $waterEntry->supply_date->format('d/m/Y') }}</div>
                    </div>
                </div>

                <div class="bg-light rounded p-3 mb-3">
                    <div class="text-muted small mb-1">বিলের প্রাপক</div>
                    <div class="fw-bold fs-5">{{ $waterEntry->farmer->name }}</div>
                    <div class="text-muted">{{ $waterEntry->farmer->mobile }}</div>
                    <div class="text-muted small">{{ $waterEntry->farmer->village }}</div>
                </div>

                <table class="table table-bordered mb-3">
                    <thead class="table-primary">
                        <tr><th>বিবরণ</th><th class="text-center">পরিমাণ</th><th class="text-center">রেট</th><th class="text-end">মোট</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>পানি সরবরাহ (সেচ)
                                @if($waterEntry->season)<br><small class="text-muted">মৌসুম: {{ $waterEntry->season }}</small>@endif
                            </td>
                            <td class="text-center">{{ $waterEntry->hours }} ঘণ্টা</td>
                            <td class="text-center">৳{{ $waterEntry->rate_per_hour }}/ঘণ্টা</td>
                            <td class="text-end fw-semibold">৳{{ number_format($waterEntry->total_amount, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" class="text-end fw-bold">মোট বিল</td><td class="text-end fw-bold">৳{{ number_format($waterEntry->total_amount, 2) }}</td></tr>
                        <tr class="table-success"><td colspan="3" class="text-end fw-bold">মোট পরিশোধ</td><td class="text-end fw-bold">৳{{ number_format($waterEntry->paid_amount, 2) }}</td></tr>
                        <tr class="{{ $waterEntry->due_amount > 0 ? 'table-danger' : 'table-success' }}">
                            <td colspan="3" class="text-end fw-bold fs-5">বাকি</td>
                            <td class="text-end fw-bold fs-5">৳{{ number_format($waterEntry->due_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                @if($waterEntry->payments->count())
                <div class="mb-3">
                    <div class="fw-semibold small text-muted mb-2">পেমেন্ট রেকর্ড:</div>
                    @foreach($waterEntry->payments as $p)
                    <div class="d-flex justify-content-between small">
                        <span>{{ $p->payment_date->format('d/m/Y') }} — {{ $p->method_label }}</span>
                        <span class="text-success fw-semibold">৳{{ number_format($p->amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="text-center text-muted small border-top pt-3">
                    ধন্যবাদ আপনার সহযোগিতার জন্য।
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
