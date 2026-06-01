@extends('layouts.app')
@section('title', 'শ্যালো মালিকের প্রোফাইল')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">শ্যালো মালিকের প্রোফাইল</h4>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-person-gear me-2"></i>প্রোফাইল তথ্য
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('pump-owner.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $owner->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">মোবাইল <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                   value="{{ old('mobile', $owner->mobile) }}" required>
                            @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">শ্যালোর নাম / পরিচিতি</label>
                            <input type="text" name="pump_name" class="form-control"
                                   value="{{ old('pump_name', $owner->pump_name) }}" placeholder="যেমন: মিজান শ্যালো">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">গ্রাম</label>
                            <input type="text" name="village" class="form-control"
                                   value="{{ old('village', $owner->village) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">ঠিকানা</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $owner->address) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">প্রতি ঘণ্টা রেট (৳) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="rate_per_hour" step="0.01" min="0"
                                       class="form-control @error('rate_per_hour') is-invalid @enderror"
                                       value="{{ old('rate_per_hour', $owner->rate_per_hour) }}" required>
                            </div>
                            @error('rate_per_hour')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NID নম্বর</label>
                            <input type="text" name="nid" class="form-control"
                                   value="{{ old('nid', $owner->nid) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">মন্তব্য</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $owner->notes) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
