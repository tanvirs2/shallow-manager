@extends('layouts.app')
@section('title', 'নতুন কৃষক যোগ করুন')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">নতুন কৃষক যোগ করুন</h4>
    <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>ফিরে যান</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('farmers.store') }}" method="POST">
                    @csrf
                    @include('farmers._form')
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>সংরক্ষণ করুন</button>
                        <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary">বাতিল</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
