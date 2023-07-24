@extends('layouts.app')

@push('after-style')
@endpush

@section('title')
  Edit Province
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Dashboard</a>
      </li>

      <li class="breadcrumb-item">
        <a href="{{ route('province.index') }}">Province Data</a>
      </li>

      <li class="breadcrumb-item active">Edit Province</li>
    </ol>
  </nav>

  <!-- Collapse -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Edit Province</h5>

          <a href="{{ route('province.index') }}" class="btn btn-secondary btn-icon-text">
            <i class="fas fa-arrow-left btn-icon-prepend"></i>
            Kembali
          </a>
        </div>

        <div class="card-body">
          <form action="{{ route('province.update', $item->id) }}" method="POST" id="form-province"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label" for="name_province">Nama Provinsi<span style="color: red">*</span></label>
              <input type="text" class="form-control" id="name_province" name="name_province"
                placeholder="Masukkan nama provinsi" value="{{ $item->name_province ?? old('name_province') }}" />
            </div>

            <a href="javascript:void(0)" class="btn btn-primary btn-simpan">Simpan</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('after-script')
  <script>
    $(function() {
      $(document).on("click", ".btn-simpan", function(event) {
        $('.btn-simpan').text('Menyimpan...'); //change button text
        $('.btn-simpan').attr('disabled', true); //set button disable

        var url = $('#form-province').attr('action');
        var type = $('#form-province').attr('method');

        var form = $('#form-province')[0];
        var formData = new FormData(form);
        event.preventDefault();

        // ajax adding data to database
        $.ajax({
          url: url,
          type: type,
          processData: false,
          contentType: false,
          data: formData,
          dataType: "JSON",
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          success: function(response) {
            $('.btn-simpan').text('Simpan'); //change button text
            $('.btn-simpan').attr('disabled', false); //set button enable

            if (response.status == true) //if success close modal and reload ajax table
            {
              var span = document.createElement("span");
              span.innerHTML = "" + response.pesan + "";

              swal({
                  html: true,
                  title: "Success!",
                  content: span,
                  icon: "success"
                })
                .then(function() {
                  document.location = "{{ route('province.index') }}";
                });
            } else {
              var pesan = "";
              var data_pesan = response.pesan;
              const wrapper = document.createElement('div');
              if (typeof(data_pesan) == 'object') {
                jQuery.each(data_pesan, function(key, value) {
                  console.log(value);
                  pesan += value + '<br>';
                  wrapper.innerHTML = pesan;
                });
                swal({
                  title: "Error!",
                  content: wrapper,
                  icon: "warning"
                });
              } else {
                swal({
                  title: "Error!",
                  text: response.pesan,
                  icon: "warning"
                });
              }
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $('.btn-simpan').text('Simpan'); //change button text
            $('.btn-simpan').attr('disabled', false); //set button enable

            var err = eval("(" + jqXHR.responseText + ")");

            swal("Error!", err.Message, "error");
          }
        });
      });
    })
  </script>
@endpush
