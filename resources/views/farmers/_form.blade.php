<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $farmer->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">মোবাইল নম্বর <span class="text-danger">*</span></label>
        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
               value="{{ old('mobile', $farmer->mobile ?? '') }}" placeholder="01XXXXXXXXX" required>
        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">গ্রাম</label>
        <input type="text" name="village" class="form-control" value="{{ old('village', $farmer->village ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">ইউনিয়ন</label>
        <input type="text" name="union" class="form-control" value="{{ old('union', $farmer->union ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">উপজেলা</label>
        <input type="text" name="upazila" class="form-control" value="{{ old('upazila', $farmer->upazila ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">জমির পরিমাণ <span class="text-danger">*</span></label>
        <input type="number" name="land_area" step="0.001" min="0"
               class="form-control @error('land_area') is-invalid @enderror"
               value="{{ old('land_area', $farmer->land_area ?? '') }}" required>
        @error('land_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">একক <span class="text-danger">*</span></label>
        <select name="land_unit" class="form-select" required>
            <option value="shotok" {{ old('land_unit', $farmer->land_unit ?? 'shotok') === 'shotok' ? 'selected' : '' }}>শতক</option>
            <option value="acre" {{ old('land_unit', $farmer->land_unit ?? '') === 'acre' ? 'selected' : '' }}>একর</option>
            <option value="bigha" {{ old('land_unit', $farmer->land_unit ?? '') === 'bigha' ? 'selected' : '' }}>বিঘা</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">NID নম্বর</label>
        <input type="text" name="nid" class="form-control" value="{{ old('nid', $farmer->nid ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">জমির বিবরণ</label>
        <textarea name="land_description" class="form-control" rows="2" placeholder="জমির অবস্থান, ধরন ইত্যাদি">{{ old('land_description', $farmer->land_description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">মন্তব্য</label>
        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $farmer->notes ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                   {{ old('is_active', $farmer->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">সক্রিয় কৃষক</label>
        </div>
    </div>
</div>
