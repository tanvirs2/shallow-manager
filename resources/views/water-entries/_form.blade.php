<div class="row g-3">
    <div class="col-12">
        <label class="form-label fw-semibold">কৃষক <span class="text-danger">*</span></label>
        <select name="farmer_id" id="farmer_id" class="form-select @error('farmer_id') is-invalid @enderror" required>
            <option value="">— কৃষক বেছে নিন —</option>
            @foreach($farmers as $f)
            <option value="{{ $f->id }}" {{ old('farmer_id', $waterEntry->farmer_id ?? request('farmer_id')) == $f->id ? 'selected' : '' }}>
                {{ $f->name }} ({{ $f->mobile }}) — {{ $f->village }}
            </option>
            @endforeach
        </select>
        @error('farmer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">তারিখ <span class="text-danger">*</span></label>
        <input type="date" name="supply_date" class="form-control @error('supply_date') is-invalid @enderror"
               value="{{ old('supply_date', isset($waterEntry) ? $waterEntry->supply_date->format('Y-m-d') : date('Y-m-d')) }}" required>
        @error('supply_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">মৌসুম</label>
        <select name="season" class="form-select">
            <option value="">— বেছে নিন —</option>
            <option value="রবি" {{ old('season', $waterEntry->season ?? '') === 'রবি' ? 'selected' : '' }}>রবি</option>
            <option value="খরিপ-১" {{ old('season', $waterEntry->season ?? '') === 'খরিপ-১' ? 'selected' : '' }}>খরিপ-১</option>
            <option value="খরিপ-২" {{ old('season', $waterEntry->season ?? '') === 'খরিপ-২' ? 'selected' : '' }}>খরিপ-২</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">ঘণ্টা <span class="text-danger">*</span></label>
        <input type="number" name="hours" id="hours" step="0.5" min="0.5"
               class="form-control @error('hours') is-invalid @enderror"
               value="{{ old('hours', $waterEntry->hours ?? '') }}" required>
        @error('hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">রেট (৳/ঘণ্টা) <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">৳</span>
            <input type="number" name="rate_per_hour" id="rate" step="0.01" min="0"
                   class="form-control @error('rate_per_hour') is-invalid @enderror"
                   value="{{ old('rate_per_hour', $waterEntry->rate_per_hour ?? $defaultRate) }}" required>
        </div>
        @error('rate_per_hour')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">মোট টাকা</label>
        <div class="input-group">
            <span class="input-group-text">৳</span>
            <input type="text" id="total" class="form-control bg-light fw-bold" readonly placeholder="স্বয়ংক্রিয়">
        </div>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">মন্তব্য</label>
        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $waterEntry->notes ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
<script>
function calcTotal() {
    const h = parseFloat(document.getElementById('hours').value) || 0;
    const r = parseFloat(document.getElementById('rate').value) || 0;
    document.getElementById('total').value = '৳' + (h * r).toFixed(2);
}
document.getElementById('hours').addEventListener('input', calcTotal);
document.getElementById('rate').addEventListener('input', calcTotal);
calcTotal();
</script>
@endpush
