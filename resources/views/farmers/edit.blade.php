@extends('layouts.app')
@section('title', 'কৃষক সম্পাদনা')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">কৃষক সম্পাদনা: {{ $farmer->name }}</h4>
    <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>ফিরে যান</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
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
                <form action="{{ route('farmers.update', $farmer) }}" method="POST">
                    @csrf @method('PUT')
                    @include('farmers._form')
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>আপডেট করুন</button>
                        <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-outline-secondary">বাতিল</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
