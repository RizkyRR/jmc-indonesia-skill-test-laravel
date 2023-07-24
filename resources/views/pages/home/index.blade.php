@extends('layouts.app')

@push('after-style')
@endpush

@section('title')
  Home
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-6 mb-4 order-0">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-primary">Data Provinsi! </h5>
          <p class="mb-4">
            Anda memiliki <span class="fw-bold">{{ $count_province }}</span> data provinsi.
          </p>

          <a href="{{ route('province.index') }}" class="btn btn-sm btn-outline-primary">View Provinces</a>
        </div>
      </div>
    </div>


    <div class="col-lg-6 mb-4 order-1">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-primary">Data Region! </h5>
          <p class="mb-4">
            Anda memiliki <span class="fw-bold">{{ $count_region }}</span> data region.
          </p>

          <a href="{{ route('region.index') }}" class="btn btn-sm btn-outline-primary">View Regions</a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('after-script')
@endpush
