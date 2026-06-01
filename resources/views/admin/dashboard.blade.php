@extends('admin.layout')
@section('title', 'অ্যাডমিন ড্যাশবোর্ড')
@section('breadcrumb')
    <li class="breadcrumb-item active">ড্যাশবোর্ড</li>
@endsection

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#ede9fe;">
                    <i class="bi bi-people-fill" style="color:#7c3aed;"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark">{{ $totalOwners }}</div>
                    <div class="text-muted" style="font-size:.78rem;">মোট মালিক</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#dcfce7;">
                    <i class="bi bi-person-check-fill" style="color:#16a34a;"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark">{{ $activeOwners }}</div>
                    <div class="text-muted" style="font-size:.78rem;">সক্রিয় মালিক</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fee2e2;">
                    <i class="bi bi-person-x-fill" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark">{{ $expiredOwners }}</div>
                    <div class="text-muted" style="font-size:.78rem;">মেয়াদ শেষ</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fef3c7;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#d97706;"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark">{{ $expiringSoon }}</div>
                    <div class="text-muted" style="font-size:.78rem;">শীঘ্রই শেষ (৭ দিন)</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Second row --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e0f2fe;">
                    <i class="bi bi-person-badge-fill" style="color:#0284c7;"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($totalFarmers) }}</div>
                    <div class="text-muted" style="font-size:.78rem;">মোট কৃষক</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e0f2fe;">
                    <i class="bi bi-droplet-half" style="color:#0284c7;"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($totalWaterEntries) }}</div>
                    <div class="text-muted" style="font-size:.78rem;">পানি এন্ট্রি</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#dcfce7;">
                    <i class="bi bi-cash-stack" style="color:#16a34a;"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-dark">৳{{ number_format($totalPayments, 0) }}</div>
                    <div class="text-muted" style="font-size:.78rem;">মোট পেমেন্ট</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Users --}}
    <div class="col-md-7">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="mb-0 fw-600"><i class="bi bi-clock-history me-2 text-primary"></i>সাম্প্রতিক মালিক</h6>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">সব দেখুন</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>নাম</th>
                            <th>Email</th>
                            <th>অবস্থা</th>
                            <th>মেয়াদ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $u)
                        <tr>
                            <td class="fw-500">{{ $u->name }}</td>
                            <td class="text-muted">{{ $u->email }}</td>
                            <td>
                                @if($u->isActive())
                                    <span class="badge badge-active rounded-pill px-2">সক্রিয়</span>
                                @else
                                    <span class="badge badge-expired rounded-pill px-2">মেয়াদ শেষ</span>
                                @endif
                            </td>
                            <td style="font-size:.8rem;">
                                {{ $u->expires_at?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-secondary py-0 px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">কোনো মালিক নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Expiring Soon --}}
    <div class="col-md-5">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="mb-0 fw-600 text-warning"><i class="bi bi-exclamation-triangle me-2"></i>শীঘ্রই মেয়াদ শেষ</h6>
            </div>
            @if($expiringUsers->isEmpty())
            <div class="page-card-body text-center text-muted py-4">
                <i class="bi bi-check-circle-fill fs-3 text-success d-block mb-2"></i>
                কোনো মালিকের মেয়াদ আসন্ন নয়
            </div>
            @else
            <div class="list-group list-group-flush">
                @foreach($expiringUsers as $u)
                <div class="list-group-item d-flex align-items-center gap-3 py-2">
                    <div class="flex-shrink-0 text-center" style="width:40px;">
                        <div class="fw-bold text-danger" style="font-size:1.1rem;">{{ $u->daysRemaining() }}</div>
                        <div style="font-size:.6rem;color:#64748b;">দিন</div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-500" style="font-size:.85rem;">{{ $u->name }}</div>
                        <div class="text-muted" style="font-size:.75rem;">{{ $u->email }}</div>
                    </div>
                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-warning py-0 px-2" style="font-size:.75rem;">
                        বাড়ান
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Quick Add --}}
        <div class="page-card mt-3">
            <div class="page-card-header">
                <h6 class="mb-0 fw-600"><i class="bi bi-lightning-fill me-2 text-primary"></i>দ্রুত অ্যাকশন</h6>
            </div>
            <div class="page-card-body d-grid gap-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-2"></i>নতুন মালিক যোগ করুন
                </a>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-people me-2"></i>সব মালিক দেখুন
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
