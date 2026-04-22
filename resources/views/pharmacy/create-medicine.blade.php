@extends('layouts.app')
@section('title','Add Medicine')
@section('page-title','Add Medicine')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-capsule-pill text-primary me-2"></i>Add New Medicine</div>
      <div class="card-body">
        <form method="POST" action="{{ route('pharmacy.medicines.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Medicine Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Progesterone"></div>
            <div class="col-md-6"><label class="form-label">Generic Name</label><input type="text" name="generic_name" class="form-control" value="{{ old('generic_name') }}"></div>
            <div class="col-md-6"><label class="form-label">Brand</label><input type="text" name="brand" class="form-control" value="{{ old('brand') }}"></div>
            <div class="col-md-6"><label class="form-label">Category</label>
              <select name="category" class="form-select">
                <option value="">— Select —</option>
                @foreach(['Hormone','Antibiotic','Analgesic','Vitamin','Fertility','Injection','Tablet','Capsule','Syrup','Other'] as $cat)
                <option value="{{ $cat }}" {{ old('category')==$cat?'selected':'' }}>{{ $cat }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4"><label class="form-label">Unit *</label>
              <select name="unit" class="form-select" required>
                @foreach(['pcs','tab','cap','vial','amp','bottle','strip','box','ml','mg'] as $u)
                <option value="{{ $u }}" {{ old('unit','pcs')==$u?'selected':'' }}>{{ $u }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4"><label class="form-label">Reorder Level *</label><input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level',10) }}" min="0" required></div>
            <div class="col-md-4"><label class="form-label">Status</label>
              <select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select>
            </div>
            <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-check-lg me-2"></i>Add Medicine</button>
            <a href="{{ route('pharmacy.medicines') }}" class="btn btn-light">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
