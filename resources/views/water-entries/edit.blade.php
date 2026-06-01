@extends('layouts.app')
@section('title', 'এন্ট্রি সম্পাদনা')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">এন্ট্রি সম্পাদনা</h4>
    <a href="{{ route('water-entries.show', $waterEntry) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>ফিরে যান</a>
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
                <form action="{{ route('water-entries.update', $waterEntry) }}" method="POST">
                    @csrf @method('PUT')
                    @php $defaultRate = $waterEntry->rate_per_hour; @endphp
                    @include('water-entries._form')
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>আপডেট করুন</button>
                        <a href="{{ route('water-entries.show', $waterEntry) }}" class="btn btn-outline-secondary">বাতিল</a>
                    </div>
                </form>

                {{-- Delete form OUTSIDE update form --}}
                <form action="{{ route('water-entries.destroy', $waterEntry) }}" method="POST" class="mt-2"
                      onsubmit="return confirm('এন্ট্রিটি মুছে ফেলবেন? এই কাজ পূর্বাবস্থায় ফেরানো যাবে না।')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash me-1"></i>এন্ট্রি মুছুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
