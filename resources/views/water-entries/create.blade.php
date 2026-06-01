@extends('layouts.app')
@section('title', 'নতুন পানি সরবরাহ এন্ট্রি')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">নতুন পানি সরবরাহ এন্ট্রি</h4>
    <a href="{{ route('water-entries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>ফিরে যান</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
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
                <form action="{{ route('water-entries.store') }}" method="POST" id="entryForm">
                    @csrf
                    @include('water-entries._form')
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>সংরক্ষণ করুন</button>
                        <a href="{{ route('water-entries.index') }}" class="btn btn-outline-secondary">বাতিল</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
