@extends('admin.layout')
@section('title', 'শ্যালো মালিক তালিকা')
@section('breadcrumb')
    <li class="breadcrumb-item active">শ্যালো মালিক</li>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i>শ্যালো মালিক তালিকা</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus-fill me-1"></i>নতুন মালিক
    </a>
</div>

{{-- Filters --}}
<div class="page-card mb-3">
    <div class="page-card-body py-2">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="নাম বা ইমেইল দিয়ে খুঁজুন..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">সব মালিক</option>
                    <option value="active"   {{ request('filter') === 'active'   ? 'selected' : '' }}>✅ সক্রিয়</option>
                    <option value="expired"  {{ request('filter') === 'expired'  ? 'selected' : '' }}>❌ মেয়াদ শেষ</option>
                    <option value="expiring" {{ request('filter') === 'expiring' ? 'selected' : '' }}>⚠️ শীঘ্রই শেষ (৭ দিন)</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>খুঁজুন</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm ms-1">রিসেট</a>
            </div>
        </form>
    </div>
</div>

{{-- Desktop Table --}}
<div class="page-card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>নাম</th>
                    <th>ইমেইল</th>
                    <th>কৃষক</th>
                    <th>মেয়াদ শেষ</th>
                    <th>বাকি</th>
                    <th>অবস্থা</th>
                    <th>তৈরি</th>
                    <th>অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td class="text-muted">{{ $u->id }}</td>
                    <td class="fw-500">{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $u->farmers_count ?? 0 }} জন</span>
                    </td>
                    <td style="font-size:.82rem;">
                        {{ $u->expires_at?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td>
                        @if($u->isActive())
                            @php $days = $u->daysRemaining(); @endphp
                            @if($days <= 7)
                                <span class="badge badge-expiring">{{ $days }} দিন</span>
                            @else
                                <span class="text-success fw-500">{{ $days }} দিন</span>
                            @endif
                        @else
                            <span class="text-danger">—</span>
                        @endif
                    </td>
                    <td>
                        @if($u->isActive())
                            <span class="badge badge-active rounded-pill px-2">সক্রিয়</span>
                        @else
                            <span class="badge badge-expired rounded-pill px-2">মেয়াদ শেষ</span>
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:#64748b;">{{ $u->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                            <i class="bi bi-pencil-fill"></i> এডিট
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                        কোনো মালিক পাওয়া যায়নি
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="d-flex justify-content-center py-3">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($users as $u)
    <div class="page-card mb-2">
        <div class="page-card-body">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <div class="fw-bold">{{ $u->name }}</div>
                    <div class="text-muted" style="font-size:.8rem;">{{ $u->email }}</div>
                </div>
                @if($u->isActive())
                    <span class="badge badge-active rounded-pill px-2">সক্রিয়</span>
                @else
                    <span class="badge badge-expired rounded-pill px-2">মেয়াদ শেষ</span>
                @endif
            </div>
            <div class="d-flex gap-3 mb-2" style="font-size:.78rem;color:#64748b;">
                <span><i class="bi bi-people me-1"></i>{{ $u->farmers_count ?? 0 }} কৃষক</span>
                <span><i class="bi bi-calendar3 me-1"></i>মেয়াদ: {{ $u->expires_at?->format('d/m/Y') ?? '—' }}</span>
                @if($u->isActive())
                <span class="{{ $u->daysRemaining() <= 7 ? 'text-danger fw-bold' : 'text-success' }}">
                    {{ $u->daysRemaining() }} দিন বাকি
                </span>
                @endif
            </div>
            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary w-100">
                <i class="bi bi-pencil-fill me-1"></i>এডিট / সাবস্ক্রিপশন
            </a>
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-5">কোনো মালিক নেই</div>
    @endforelse

    @if($users->hasPages())
    <div class="d-flex justify-content-center py-3">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
