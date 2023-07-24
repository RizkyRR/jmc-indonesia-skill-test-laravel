@extends('layouts.app')

@push('after-style')
@endpush

@section('title')
  Detail Province Data
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ url('/admin/home') }}">Dashboard</a>
      </li>

      <li class="breadcrumb-item">
        <a href="{{ route('province.index') }}">Province Data</a>
      </li>

      <li class="breadcrumb-item active">Detail Province Data</li>
    </ol>
  </nav>

  <!-- Collapse -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Detail Province Data</h5>

          <a href="{{ route('province.index') }}">
            <button type="button" class="btn btn-secondary btn-icon-text">
              <i class="fas fa-arrow-left btn-icon-prepend"></i>
              Kembali
            </button>
          </a>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="name_province">Nama Provinsi</label>
            <div class="col-sm-10">
              <label class="col-form-label">: {{ $item->name_province }}</label>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="total_region">Jumlah Region</label>
            <div class="col-sm-10">
              <label class="col-form-label">: {{ count($item->region) }}</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('after-script')
@endpush
