@extends('admin.layout')
@section('title', $user->name . ' — এডিট')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none">শ্যালো মালিক</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')

<div class="row g-3">

    {{-- Left: User Info Edit --}}
    <div class="col-md-5">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-fill me-2 text-primary"></i>মালিকের তথ্য</h6>
            </div>
            <div class="page-card-body">

                {{-- Status Banner --}}
                <div class="text-center mb-3 p-3 rounded" style="background:{{ $user->isActive() ? '#f0fdf4' : '#fff1f2' }};border:1px solid {{ $user->isActive() ? '#bbf7d0' : '#fecdd3' }}">
                    @if($user->isActive())
                        @php $days = $user->daysRemaining(); @endphp
                        <div class="fw-bold" style="color:#16a34a;font-size:1.1rem;">✅ সক্রিয়</div>
                        <div style="font-size:.85rem;color:#166534;">
                            @if($days <= 7)
                                <span class="text-danger fw-bold">⚠️ মাত্র {{ $days }} দিন বাকি!</span>
                            @else
                                {{ $days }} দিন বাকি
                            @endif
                            · মেয়াদ: {{ $user->expires_at->format('d/m/Y') }}
                        </div>
                    @else
                        <div class="fw-bold" style="color:#dc2626;font-size:1.1rem;">❌ মেয়াদ শেষ</div>
                        <div style="font-size:.82rem;color:#991b1b;">
                            @if($user->expires_at)
                                {{ $user->expires_at->format('d/m/Y') }} তে শেষ হয়েছে
                            @else
                                কোনো মেয়াদ সেট করা হয়নি
                            @endif
                        </div>
                    @endif
                </div>

                @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <ul class="mb-0 ps-3" style="font-size:.83rem;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-500">নাম</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">ইমেইল</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-3">
                    <p class="text-muted mb-2" style="font-size:.8rem;"><i class="bi bi-lock me-1"></i>পাসওয়ার্ড পরিবর্তন (ঐচ্ছিক)</p>

                    <div class="mb-3">
                        <label class="form-label fw-500">নতুন পাসওয়ার্ড</label>
                        <div class="input-group">
                            <input type="password" name="password" id="pass"
                                   class="form-control" placeholder="খালি রাখলে পরিবর্তন হবে না">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('pass','ep1')">
                                <i class="bi bi-eye" id="ep1"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">পাসওয়ার্ড নিশ্চিত করুন</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="pass2"
                                   class="form-control" placeholder="পুনরায় পাসওয়ার্ড">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('pass2','ep2')">
                                <i class="bi bi-eye" id="ep2"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i>তথ্য সেভ করুন
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats --}}
        <div class="page-card mt-3">
            <div class="page-card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2 text-info"></i>পরিসংখ্যান</h6>
            </div>
            <div class="page-card-body">
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fw-bold fs-5">{{ $user->farmers_count ?? $user->farmers()->count() }}</div>
                            <div class="text-muted" style="font-size:.75rem;">কৃষক</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded">
                            <div class="fw-bold fs-5">{{ $user->created_at->diffForHumans() }}</div>
                            <div class="text-muted" style="font-size:.75rem;">যোগদান</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Subscription Management --}}
    <div class="col-md-7">

        {{-- Grant / Extend Access --}}
        <div class="page-card mb-3">
            <div class="page-card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2 text-success"></i>মেয়াদ দিন / বাড়ান</h6>
            </div>
            <div class="page-card-body">
                <form action="{{ route('admin.users.grant', $user) }}" method="POST">
                    @csrf

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label fw-500 mb-1" style="font-size:.83rem;">পরিমাণ</label>
                            <input type="number" name="duration_value" class="form-control" value="1" min="1" max="999" required>
                        </div>
                        <div class="col-8">
                            <label class="form-label fw-500 mb-1" style="font-size:.83rem;">একক</label>
                            <select name="duration_type" class="form-select">
                                <option value="days">দিন (Days)</option>
                                <option value="months" selected>মাস (Months)</option>
                                <option value="years">বছর (Years)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500" style="font-size:.83rem;">হিসাব করার পদ্ধতি</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="extend" id="extendNo" value="0" checked>
                                <label class="form-check-label" for="extendNo" style="font-size:.85rem;">
                                    <strong>আজ থেকে</strong> (নতুন মেয়াদ)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="extend" id="extendYes" value="1">
                                <label class="form-check-label" for="extendYes" style="font-size:.85rem;">
                                    <strong>বর্তমান মেয়াদের সাথে যোগ</strong>
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">
                            বর্তমান মেয়াদ:
                            @if($user->expires_at?->isFuture())
                                <strong class="text-success">{{ $user->expires_at->format('d/m/Y') }}</strong>
                                ({{ $user->daysRemaining() }} দিন বাকি)
                            @else
                                <span class="text-danger">মেয়াদ নেই / শেষ</span>
                            @endif
                        </small>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-500">
                        <i class="bi bi-check-circle me-1"></i>মেয়াদ দিন
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick Presets --}}
        <div class="page-card mb-3">
            <div class="page-card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-fill me-2 text-warning"></i>দ্রুত মেয়াদ (আজ থেকে)</h6>
            </div>
            <div class="page-card-body">
                <div class="row g-2">
                    @foreach([
                        ['label' => '১ মাস', 'type' => 'months', 'val' => 1],
                        ['label' => '৩ মাস', 'type' => 'months', 'val' => 3],
                        ['label' => '৬ মাস', 'type' => 'months', 'val' => 6],
                        ['label' => '১ বছর', 'type' => 'years',  'val' => 1],
                        ['label' => '৩০ দিন','type' => 'days',   'val' => 30],
                        ['label' => '৭ দিন', 'type' => 'days',   'val' => 7],
                    ] as $p)
                    <div class="col-4">
                        <form action="{{ route('admin.users.grant', $user) }}" method="POST">
                            @csrf
                            <input type="hidden" name="duration_type"  value="{{ $p['type'] }}">
                            <input type="hidden" name="duration_value" value="{{ $p['val'] }}">
                            <input type="hidden" name="extend" value="0">
                            <button class="btn btn-outline-primary btn-sm w-100">{{ $p['label'] }}</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Revoke + Delete --}}
        <div class="page-card border-danger" style="border-color:#fca5a5 !important;">
            <div class="page-card-header" style="background:#fff5f5;">
                <h6 class="mb-0 fw-bold text-danger"><i class="bi bi-shield-x me-2"></i>বিপজ্জনক অ্যাকশন</h6>
            </div>
            <div class="page-card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <form action="{{ route('admin.users.revoke', $user) }}" method="POST"
                              onsubmit="return confirm('{{ $user->name }} এর অ্যাক্সেস বাতিল করবেন?')">
                            @csrf
                            <button class="btn btn-warning w-100 fw-500">
                                <i class="bi bi-ban me-1"></i>অ্যাক্সেস বাতিল
                            </button>
                        </form>
                        <small class="text-muted d-block mt-1 text-center" style="font-size:.72rem;">মালিক লগইন করতে পারবেন না</small>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST"
                              onsubmit="return confirm('⚠️ সতর্কতা!\n\n{{ $user->name }} এর সব ডেটা মুছে যাবে!\nকৃষক, পানি এন্ট্রি, পেমেন্ট সব!\n\nনিশ্চিত?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger w-100 fw-500">
                                <i class="bi bi-trash3-fill me-1"></i>অ্যাকাউন্ট মুছুন
                            </button>
                        </form>
                        <small class="text-muted d-block mt-1 text-center" style="font-size:.72rem;">সব ডেটা স্থায়ীভাবে মুছবে</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function togglePass(id, iconId) {
    const inp = document.getElementById(id);
    const ico = document.getElementById(iconId);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endpush
