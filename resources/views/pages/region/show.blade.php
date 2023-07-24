@extends('layouts.app')

@push('after-style')
@endpush

@section('title')
  Detail Region Data
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ url('/admin/home') }}">Dashboard</a>
      </li>

      <li class="breadcrumb-item">
        <a href="{{ route('region.index') }}">Region Data</a>
      </li>

      <li class="breadcrumb-item active">Detail Region Data</li>
    </ol>
  </nav>

  <!-- Collapse -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Detail Region Data</h5>

          <a href="{{ route('region.index') }}">
            <button type="button" class="btn btn-secondary btn-icon-text">
              <i class="fas fa-arrow-left btn-icon-prepend"></i>
              Kembali
            </button>
          </a>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="">Nama Provinsi</label>
            <div class="col-sm-10">
              <label class="col-form-label">:
                {{ $item->province->id != null ? $item->province->name_province : '' }}</label>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="">Nama Region</label>
            <div class="col-sm-10">
              <label class="col-form-label">: {{ $item->name_region }}</label>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="">Total Populasi</label>
            <div class="col-sm-10">
              <label class="col-form-label">: {{ $item->total_population_region }}</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('after-script')
@endpush
