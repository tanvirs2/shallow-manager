@extends('layouts.app')
@section('title', 'এক্সেল ইমপোর্ট')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">এক্সেল / CSV ইমপোর্ট</h4>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-people me-2 text-primary"></i>কৃষক ইমপোর্ট
            </div>
            <div class="card-body">
                <p class="text-muted small">Excel/CSV ফাইল থেকে কৃষকদের তথ্য ইমপোর্ট করুন।</p>
                <a href="{{ route('import.template', 'farmers') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-download me-1"></i>টেমপ্লেট ডাউনলোড করুন
                </a>
                <form action="{{ route('import.farmers') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ফাইল বেছে নিন (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>ইমপোর্ট করুন
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-droplet-half me-2 text-info"></i>পানি সরবরাহ ইমপোর্ট
            </div>
            <div class="card-body">
                <p class="text-muted small">Excel/CSV ফাইল থেকে পানি সরবরাহ এন্ট্রি ইমপোর্ট করুন।</p>
                <a href="{{ route('import.template', 'water-entries') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-download me-1"></i>টেমপ্লেট ডাউনলোড করুন
                </a>
                <form action="{{ route('import.water-entries') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ফাইল বেছে নিন (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>ইমপোর্ট করুন
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-warning">
            <div class="card-body">
                <h6 class="fw-bold"><i class="bi bi-info-circle me-2 text-warning"></i>গুরুত্বপূর্ণ নির্দেশনা</h6>
                <ul class="mb-0 small text-muted">
                    <li>কৃষক ইমপোর্টের সময় মোবাইল নম্বর ইউনিক হতে হবে।</li>
                    <li>পানি সরবরাহ ইমপোর্টের সময় কৃষকের মোবাইল নম্বর দিয়ে মিলানো হবে।</li>
                    <li>তারিখ ফরম্যাট: YYYY-MM-DD (যেমন: 2024-06-15)</li>
                    <li>একক: acre, shotok, অথবা bigha</li>
                    <li>প্রথম রো হেডার হিসেবে ব্যবহার হবে, ডেটা ২য় রো থেকে শুরু হবে।</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
