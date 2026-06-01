@extends('layouts.app')
@section('title', 'পেমেন্ট হিস্ট্রি')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">পেমেন্ট হিস্ট্রি</h5>
    <a href="{{ route('payments.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-lg me-1"></i>নতুন পেমেন্ট
    </a>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form class="row g-2" method="GET">
            <div class="col-12 col-md-3">
                <select name="farmer_id" class="form-select form-select-sm">
                    <option value="">সব কৃষক</option>
                    @foreach($farmers as $f)
                    <option value="{{ $f->id }}" {{ request('farmer_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-5 col-md-2">
                <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
            </div>
            <div class="col-5 col-md-2">
                <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
            </div>
            <div class="col-2 col-md-auto d-flex gap-1">
                <button class="btn btn-outline-primary btn-sm" type="submit"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm">✕</a>
            </div>
        </form>
    </div>
</div>

@if(request()->hasAny(['farmer_id', 'from', 'to']))
<div class="alert alert-info py-2 small">
    মোট কালেকশন: <strong>৳{{ number_format($totalAmount, 2) }}</strong>
</div>
@endif

{{-- Desktop Table --}}
<div class="card shadow-sm d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>তারিখ</th><th>কৃষক</th><th>পরিমাণ</th><th>মাধ্যম</th><th>এন্ট্রি তারিখ</th><th>রেফারেন্স</th><th>কাজ</th></tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                    <td><a href="{{ route('farmers.show', $payment->farmer) }}" class="text-decoration-none fw-semibold">{{ $payment->farmer->name }}</a></td>
                    <td class="fw-bold text-success">৳{{ number_format($payment->amount, 0) }}</td>
                    <td>{{ $payment->method_label }}</td>
                    <td>{{ $payment->waterEntry ? $payment->waterEntry->supply_date->format('d/m/Y') : 'সামগ্রিক' }}</td>
                    <td>{{ $payment->reference ?? '—' }}</td>
                    <td>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('মুছে ফেলবেন?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-action"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">কোনো পেমেন্ট পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
            @if($payments->count())
            <tfoot class="table-light">
                <tr>
                    <td colspan="2" class="fw-bold">মোট (এই পেজে)</td>
                    <td class="fw-bold text-success">৳{{ number_format($payments->sum('amount'), 0) }}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-footer">{{ $payments->links() }}</div>
    @endif
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($payments as $payment)
    <div class="card shadow-sm mb-2">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold">{{ $payment->farmer->name }}</div>
                    <div class="text-muted small">{{ $payment->payment_date->format('d/m/Y') }} · {{ $payment->method_label }}</div>
                    @if($payment->reference)
                    <div class="text-muted small">Ref: {{ $payment->reference }}</div>
                    @endif
                </div>
                <div class="text-end d-flex flex-column align-items-end gap-1">
                    <span class="fw-bold text-success fs-6">৳{{ number_format($payment->amount, 0) }}</span>
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('মুছে ফেলবেন?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm btn-action"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-5">কোনো পেমেন্ট পাওয়া যায়নি।</div>
    @endforelse

    @if($payments->hasPages())
    <div class="mt-2">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
