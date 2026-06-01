@extends('layouts.app')
@section('title', 'নতুন পেমেন্ট')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">পেমেন্ট নেওয়া</h4>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>ফিরে যান</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">কৃষক <span class="text-danger">*</span></label>
                            <select name="farmer_id" id="farmer_id" class="form-select @error('farmer_id') is-invalid @enderror" required>
                                <option value="">— কৃষক বেছে নিন —</option>
                                @foreach($farmers as $f)
                                <option value="{{ $f->id }}" {{ old('farmer_id', $selectedFarmer?->id) == $f->id ? 'selected' : '' }}>
                                    {{ $f->name }} ({{ $f->mobile }})
                                </option>
                                @endforeach
                            </select>
                            @error('farmer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12" id="dueInfo" style="display: none;">
                            <div class="alert alert-warning py-2">
                                মোট বাকি: <strong id="totalDue">৳0</strong>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">নির্দিষ্ট এন্ট্রির জন্য (ঐচ্ছিক)</label>
                            <select name="water_entry_id" id="water_entry_id" class="form-select">
                                <option value="">— সামগ্রিক পেমেন্ট —</option>
                                @foreach($dueEntries as $entry)
                                <option value="{{ $entry->id }}" {{ old('water_entry_id', request('water_entry_id')) == $entry->id ? 'selected' : '' }}
                                        data-due="{{ $entry->due_amount }}">
                                    {{ $entry->supply_date->format('d/m/Y') }} — {{ $entry->hours }} ঘণ্টা (বাকি: ৳{{ number_format($entry->due_amount, 0) }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">পরিমাণ (৳) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="amount" id="amount" step="0.01" min="1"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror"
                                   value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">পেমেন্ট মাধ্যম <span class="text-danger">*</span></label>
                            <select name="method" class="form-select" required>
                                <option value="cash" {{ old('method') === 'cash' ? 'selected' : '' }}>নগদ টাকা</option>
                                <option value="bkash" {{ old('method') === 'bkash' ? 'selected' : '' }}>বিকাশ</option>
                                <option value="nagad" {{ old('method') === 'nagad' ? 'selected' : '' }}>নগদ (Nagad)</option>
                                <option value="rocket" {{ old('method') === 'rocket' ? 'selected' : '' }}>রকেট</option>
                                <option value="bank" {{ old('method') === 'bank' ? 'selected' : '' }}>ব্যাংক</option>
                                <option value="other" {{ old('method') === 'other' ? 'selected' : '' }}>অন্যান্য</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">রেফারেন্স নম্বর</label>
                            <input type="text" name="reference" class="form-control"
                                   value="{{ old('reference') }}" placeholder="ট্রানজেকশন ID ইত্যাদি">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">মন্তব্য</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>পেমেন্ট সংরক্ষণ করুন</button>
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">বাতিল</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const farmerSelect = document.getElementById('farmer_id');
const entrySelect = document.getElementById('water_entry_id');
const amountInput = document.getElementById('amount');
const dueInfo = document.getElementById('dueInfo');
const totalDueEl = document.getElementById('totalDue');

farmerSelect.addEventListener('change', function() {
    if (!this.value) { dueInfo.style.display = 'none'; return; }

    fetch('/payments/farmer-due?farmer_id=' + this.value)
        .then(r => r.json())
        .then(data => {
            entrySelect.innerHTML = '<option value="">— সামগ্রিক পেমেন্ট —</option>';
            data.due_entries.forEach(e => {
                entrySelect.innerHTML += `<option value="${e.id}" data-due="${e.due_amount}">${e.label}</option>`;
            });
            totalDueEl.textContent = '৳' + parseFloat(data.total_due).toLocaleString('en-BD', {minimumFractionDigits: 2});
            dueInfo.style.display = data.total_due > 0 ? 'block' : 'none';
        });
});

entrySelect.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const due = opt.dataset.due;
    if (due) amountInput.value = parseFloat(due).toFixed(2);
});
</script>
@endpush
