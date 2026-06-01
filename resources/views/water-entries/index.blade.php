@extends('layouts.app')
@section('title', 'পানি সরবরাহ এন্ট্রি')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">পানি সরবরাহ এন্ট্রি</h5>
    <a href="{{ route('water-entries.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>নতুন
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
            <div class="col-6 col-md-2">
                <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
            </div>
            <div class="col-6 col-md-2">
                <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
            </div>
            <div class="col-8 col-md-2">
                <select name="season" class="form-select form-select-sm">
                    <option value="">সব মৌসুম</option>
                    <option value="রবি" {{ request('season') === 'রবি' ? 'selected' : '' }}>রবি</option>
                    <option value="খরিপ-১" {{ request('season') === 'খরিপ-১' ? 'selected' : '' }}>খরিপ-১</option>
                    <option value="খরিপ-২" {{ request('season') === 'খরিপ-২' ? 'selected' : '' }}>খরিপ-২</option>
                </select>
            </div>
            <div class="col-4 col-md-auto d-flex gap-1">
                <button class="btn btn-outline-primary btn-sm flex-fill" type="submit"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('water-entries.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">✕</a>
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
                    <th>তারিখ</th><th>কৃষক</th><th>ঘণ্টা</th><th>রেট</th>
                    <th>মোট বিল</th><th>পরিশোধ</th><th>বাকি</th><th>মৌসুম</th><th>স্ট্যাটাস</th><th>কাজ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                <tr>
                    <td>{{ $entry->supply_date->format('d/m/Y') }}</td>
                    <td><a href="{{ route('farmers.show', $entry->farmer) }}" class="text-decoration-none fw-semibold">{{ $entry->farmer->name }}</a></td>
                    <td>{{ $entry->hours }}</td>
                    <td>৳{{ $entry->rate_per_hour }}</td>
                    <td class="fw-semibold">৳{{ number_format($entry->total_amount, 0) }}</td>
                    <td class="text-success">৳{{ number_format($entry->paid_amount, 0) }}</td>
                    <td class="{{ $entry->due_amount > 0 ? 'text-danger fw-bold' : 'text-muted' }}">৳{{ number_format($entry->due_amount, 0) }}</td>
                    <td>{{ $entry->season ?? '—' }}</td>
                    <td>{!! $entry->status_badge !!}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('invoices.show', $entry) }}" class="btn btn-outline-info btn-action"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('water-entries.edit', $entry) }}" class="btn btn-outline-warning btn-action"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('invoices.pdf', $entry) }}" class="btn btn-outline-danger btn-action"><i class="bi bi-file-pdf"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-5 text-muted">কোনো এন্ট্রি পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($entries->hasPages())
    <div class="card-footer">{{ $entries->links() }}</div>
    @endif
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($entries as $entry)
    <div class="card shadow-sm mb-2">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-1">
                <div>
                    <span class="fw-bold">{{ $entry->farmer->name }}</span>
                    <div class="text-muted small">{{ $entry->supply_date->format('d/m/Y') }}{{ $entry->season ? ' · ' . $entry->season : '' }}</div>
                </div>
                {!! $entry->status_badge !!}
            </div>
            <div class="row g-1 text-center my-2" style="font-size:.8rem;">
                <div class="col-4">
                    <div class="bg-light rounded p-1">
                        <div class="text-muted" style="font-size:.65rem;">{{ $entry->hours }} ঘণ্টা × ৳{{ $entry->rate_per_hour }}</div>
                        <div class="fw-semibold">৳{{ number_format($entry->total_amount, 0) }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-success bg-opacity-10 rounded p-1">
                        <div class="text-muted" style="font-size:.65rem;">পরিশোধ</div>
                        <div class="fw-semibold text-success">৳{{ number_format($entry->paid_amount, 0) }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="{{ $entry->due_amount > 0 ? 'bg-danger bg-opacity-10' : 'bg-success bg-opacity-10' }} rounded p-1">
                        <div class="text-muted" style="font-size:.65rem;">বাকি</div>
                        <div class="fw-semibold {{ $entry->due_amount > 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($entry->due_amount, 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('invoices.show', $entry) }}" class="btn btn-outline-info btn-sm flex-fill">ইনভয়েস</a>
                <a href="{{ route('water-entries.edit', $entry) }}" class="btn btn-outline-warning btn-sm flex-fill">এডিট</a>
                <a href="{{ route('invoices.pdf', $entry) }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-pdf"></i></a>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-5">কোনো এন্ট্রি পাওয়া যায়নি।</div>
    @endforelse

    @if($entries->hasPages())
    <div class="mt-2">{{ $entries->links() }}</div>
    @endif
</div>
@endsection
