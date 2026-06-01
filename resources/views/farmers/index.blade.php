@extends('layouts.app')
@section('title', 'কৃষক তালিকা')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">কৃষক তালিকা</h5>
    <a href="{{ route('farmers.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>নতুন কৃষক
    </a>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form class="row g-2" method="GET">
            <div class="col-12 col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="নাম, মোবাইল বা গ্রাম..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-8 col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">সব কৃষক</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>সক্রিয়</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                </select>
            </div>
            <div class="col-4 col-md-auto d-flex gap-1">
                <button class="btn btn-outline-primary btn-sm flex-fill" type="submit"><i class="bi bi-search"></i></button>
                <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">✕</a>
            </div>
        </form>
    </div>
</div>

{{-- Desktop Table --}}
<div class="card shadow-sm d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>নাম</th><th>মোবাইল</th><th>গ্রাম</th><th>জমি</th>
                    <th>মোট বিল</th><th>মোট দেওয়া</th><th>বাকি</th><th>অবস্থা</th><th>কাজ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($farmers as $farmer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="{{ route('farmers.show', $farmer) }}" class="fw-semibold text-decoration-none">{{ $farmer->name }}</a></td>
                    <td>{{ $farmer->mobile }}</td>
                    <td>{{ $farmer->village ?? '—' }}</td>
                    <td>{{ $farmer->land_area }} {{ $farmer->land_unit }}</td>
                    <td>৳{{ number_format($farmer->total_billed, 0) }}</td>
                    <td>৳{{ number_format($farmer->total_paid, 0) }}</td>
                    <td class="{{ $farmer->total_due > 0 ? 'text-danger fw-bold' : 'text-success' }}">৳{{ number_format($farmer->total_due, 0) }}</td>
                    <td>
                        @if($farmer->is_active)
                            <span class="badge bg-success-subtle text-success">সক্রিয়</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">নিষ্ক্রিয়</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('farmers.edit', $farmer) }}" class="btn btn-outline-warning btn-action"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('payments.create', ['farmer_id' => $farmer->id]) }}" class="btn btn-outline-success btn-action"><i class="bi bi-cash"></i></a>
                            <a href="{{ route('invoices.farmer-bill', $farmer) }}" class="btn btn-outline-secondary btn-action"><i class="bi bi-file-pdf"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-5 text-muted">কোনো কৃষক পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($farmers->hasPages())
    <div class="card-footer">{{ $farmers->links() }}</div>
    @endif
</div>

{{-- Mobile Card List --}}
<div class="d-md-none">
    @forelse($farmers as $farmer)
    <div class="card shadow-sm mb-2">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <a href="{{ route('farmers.show', $farmer) }}" class="fw-bold text-decoration-none">{{ $farmer->name }}</a>
                    <div class="text-muted small">{{ $farmer->mobile }} · {{ $farmer->village ?? '—' }}</div>
                    <div class="text-muted small">জমি: {{ $farmer->land_area }} {{ $farmer->land_unit }}</div>
                </div>
                @if($farmer->is_active)
                    <span class="badge bg-success-subtle text-success">সক্রিয়</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary">নিষ্ক্রিয়</span>
                @endif
            </div>
            <div class="row g-1 text-center mb-2" style="font-size:.8rem;">
                <div class="col-4">
                    <div class="bg-light rounded p-1">
                        <div class="text-muted" style="font-size:.65rem;">বিল</div>
                        <div class="fw-semibold">৳{{ number_format($farmer->total_billed, 0) }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light rounded p-1">
                        <div class="text-muted" style="font-size:.65rem;">দেওয়া</div>
                        <div class="fw-semibold text-success">৳{{ number_format($farmer->total_paid, 0) }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="{{ $farmer->total_due > 0 ? 'bg-danger bg-opacity-10' : 'bg-success bg-opacity-10' }} rounded p-1">
                        <div class="text-muted" style="font-size:.65rem;">বাকি</div>
                        <div class="fw-semibold {{ $farmer->total_due > 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($farmer->total_due, 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-outline-primary btn-sm flex-fill">বিস্তারিত</a>
                <a href="{{ route('payments.create', ['farmer_id' => $farmer->id]) }}" class="btn btn-success btn-sm flex-fill">পেমেন্ট</a>
                <a href="{{ route('farmers.edit', $farmer) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-5">কোনো কৃষক পাওয়া যায়নি।</div>
    @endforelse

    @if($farmers->hasPages())
    <div class="mt-2">{{ $farmers->links() }}</div>
    @endif
</div>
@endsection
