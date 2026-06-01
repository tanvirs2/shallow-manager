@extends('layouts.app')
@section('title', 'রিপোর্ট')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">রিপোর্ট</h4>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-2">
                <label class="form-label small fw-semibold">রিপোর্টের ধরন</label>
                <select name="type" id="reportType" class="form-select">
                    <option value="daily" {{ $type === 'daily' ? 'selected' : '' }}>দৈনিক</option>
                    <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>মাসিক</option>
                    <option value="seasonal" {{ $type === 'seasonal' ? 'selected' : '' }}>মৌসুমওয়াইজ</option>
                    <option value="custom" {{ $type === 'custom' ? 'selected' : '' }}>কাস্টম</option>
                </select>
            </div>
            <div class="col-md-2 type-daily" style="{{ $type !== 'daily' ? 'display:none' : '' }}">
                <label class="form-label small fw-semibold">তারিখ</label>
                <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2 type-monthly" style="{{ $type !== 'monthly' ? 'display:none' : '' }}">
                <label class="form-label small fw-semibold">মাস</label>
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 type-monthly type-custom" style="{{ !in_array($type, ['monthly','custom']) ? 'display:none' : '' }}">
                <label class="form-label small fw-semibold">বছর</label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y')-5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 type-seasonal" style="{{ $type !== 'seasonal' ? 'display:none' : '' }}">
                <label class="form-label small fw-semibold">মৌসুম</label>
                <select name="season" class="form-select">
                    <option value="রবি" {{ $season === 'রবি' ? 'selected' : '' }}>রবি</option>
                    <option value="খরিপ-১" {{ $season === 'খরিপ-১' ? 'selected' : '' }}>খরিপ-১</option>
                    <option value="খরিপ-২" {{ $season === 'খরিপ-২' ? 'selected' : '' }}>খরিপ-২</option>
                </select>
            </div>
            <div class="col-md-2 type-custom" style="{{ $type !== 'custom' ? 'display:none' : '' }}">
                <label class="form-label small fw-semibold">শুরু তারিখ</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-2 type-custom" style="{{ $type !== 'custom' ? 'display:none' : '' }}">
                <label class="form-label small fw-semibold">শেষ তারিখ</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">কৃষক</label>
                <select name="farmer_id" class="form-select">
                    <option value="">সব কৃষক</option>
                    @foreach($farmers as $f)
                    <option value="{{ $f->id }}" {{ $farmerId == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i>দেখুন</button>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-primary">
    <strong>{{ $label }}</strong>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center border-0 bg-primary bg-opacity-10">
            <div class="card-body">
                <div class="text-muted small">মোট সেচ ঘণ্টা</div>
                <div class="fs-3 fw-bold text-primary">{{ number_format($totalHours, 1) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center border-0 bg-success bg-opacity-10">
            <div class="card-body">
                <div class="text-muted small">মোট বিল</div>
                <div class="fs-3 fw-bold text-success">৳{{ number_format($totalBilled, 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center border-0 {{ $totalDue > 0 ? 'bg-danger' : 'bg-success' }} bg-opacity-10">
            <div class="card-body">
                <div class="text-muted small">মোট বাকি</div>
                <div class="fs-3 fw-bold {{ $totalDue > 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($totalDue, 0) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-droplet-half me-2 text-primary"></i>পানি সরবরাহ এন্ট্রি ({{ $entries->count() }}টি)</div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light"><tr><th>তারিখ</th><th>কৃষক</th><th>ঘণ্টা</th><th>মোট</th><th>স্ট্যাটাস</th></tr></thead>
                    <tbody>
                        @forelse($entries as $e)
                        <tr>
                            <td>{{ $e->supply_date->format('d/m/Y') }}</td>
                            <td>{{ $e->farmer->name }}</td>
                            <td>{{ $e->hours }}</td>
                            <td>৳{{ number_format($e->total_amount, 0) }}</td>
                            <td>{!! $e->status_badge !!}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">কোনো এন্ট্রি নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-cash me-2 text-success"></i>পেমেন্ট ({{ $payments->count() }}টি)</div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light"><tr><th>তারিখ</th><th>কৃষক</th><th>পরিমাণ</th><th>মাধ্যম</th></tr></thead>
                    <tbody>
                        @forelse($payments as $p)
                        <tr>
                            <td>{{ $p->payment_date->format('d/m/Y') }}</td>
                            <td>{{ $p->farmer->name }}</td>
                            <td class="text-success fw-semibold">৳{{ number_format($p->amount, 0) }}</td>
                            <td>{{ $p->method_label }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">কোনো পেমেন্ট নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const typeSelect = document.getElementById('reportType');
function toggleFields() {
    const t = typeSelect.value;
    document.querySelectorAll('.type-daily, .type-monthly, .type-seasonal, .type-custom').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.type-' + t).forEach(el => el.style.display = '');
    if (t === 'monthly') document.querySelectorAll('.type-monthly').forEach(el => el.style.display = '');
}
typeSelect.addEventListener('change', toggleFields);
</script>
@endpush
