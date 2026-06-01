@extends('admin.layout')
@section('title', 'নতুন শ্যালো মালিক')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none">শ্যালো মালিক</a></li>
    <li class="breadcrumb-item active">নতুন মালিক</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2 text-primary"></i>নতুন শ্যালো মালিক তৈরি</h6>
            </div>
            <div class="page-card-body">

                @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <ul class="mb-0 ps-3" style="font-size:.85rem;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-500">নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="শ্যালো মালিকের নাম" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-500">ইমেইল <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="login@email.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-500">পাসওয়ার্ড <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="pass"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="কমপক্ষে ৬ অক্ষর" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('pass','eyePass')">
                                <i class="bi bi-eye" id="eyePass"></i>
                            </button>
                        </div>
                        @error('password')<div class="text-danger mt-1" style="font-size:.8rem;">{{ $message }}</div>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-500">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="pass2"
                                   class="form-control" placeholder="পুনরায় পাসওয়ার্ড" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('pass2','eyePass2')">
                                <i class="bi bi-eye" id="eyePass2"></i>
                            </button>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Subscription Duration --}}
                    <div class="mb-1">
                        <label class="form-label fw-500"><i class="bi bi-clock me-1 text-warning"></i>সাবস্ক্রিপশন মেয়াদ <span class="text-danger">*</span></label>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-5">
                            <input type="number" name="duration_value"
                                   class="form-control @error('duration_value') is-invalid @enderror"
                                   value="{{ old('duration_value', 30) }}" min="1" max="999" required>
                            @error('duration_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-7">
                            <select name="duration_type" class="form-select @error('duration_type') is-invalid @enderror">
                                <option value="days"   {{ old('duration_type','days') === 'days'   ? 'selected' : '' }}>দিন (Days)</option>
                                <option value="months" {{ old('duration_type') === 'months' ? 'selected' : '' }}>মাস (Months)</option>
                                <option value="years"  {{ old('duration_type') === 'years'  ? 'selected' : '' }}>বছর (Years)</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info py-2" style="font-size:.8rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        মেয়াদ শেষ হলে মালিক লগইন করতে পারবেন না। আপনি যেকোনো সময় মেয়াদ বাড়াতে বা বাতিল করতে পারবেন।
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-500">
                            <i class="bi bi-check-lg me-1"></i>মালিক তৈরি করুন
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">বাতিল</a>
                    </div>
                </form>
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
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'bi bi-eye-slash';
    } else {
        inp.type = 'password';
        ico.className = 'bi bi-eye';
    }
}
</script>
@endpush
