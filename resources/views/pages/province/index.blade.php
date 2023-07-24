@extends('layouts.app')

@push('after-style')
@endpush

@section('title')
  Province Data
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Home</a>
      </li>

      <li class="breadcrumb-item active">Province Data</li>
    </ol>
  </nav>

  <!-- Collapse -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header">
          <div class="row ms-2 me-3">
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
              <h5 class="mb-0">Province Data</h5>
            </div>

            <div
              class="col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-3 gap-md-2">
              <div class="ms-auto">
                <a id="pdf-link" href="{{ route('province.export-pdf') }}"
                  class="btn btn-secondary btn-icon-text order-0" target="__blank">
                  <i class="fas fa-file-pdf btn-icon-prepend"></i>
                  Export Pdf
                </a>

                <a href="{{ route('province.create') }}" class="btn btn-primary btn-icon-text">
                  <i class="fa fa-plus btn-icon-prepend"></i>
                  Tambah
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table" id="table-list-province" style="width: 100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name Province</th>
                  <th>Jumlah Region</th>
                  <th>Actions</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('after-script')
  <script>
    $(function() {
      var table = $('#table-list-province').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        responsive: true,
        ajax: {
          url: "{{ url()->current() }}",
          type: "GET",
          data: function(d) {
            d.search = $('input[type="search"]').val();
          }
        },
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'name_province',
            name: 'name_province'
          },
          {
            data: 'total_region',
            name: 'total_region'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ]
      });

      // datatables responsive
      new $.fn.dataTable.FixedHeader(table);

      //delete
      $('#table-list-province').on('click', '.btn-hapus', function() {
        var kode = $(this).data('id');
        var nama = $(this).data('nama');

        swal({
            title: "Apakah anda yakin?",
            text: "Untuk menghapus data : " + nama,
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                type: 'ajax',
                method: 'get',
                url: '/province/delete/' + kode,
                async: true,
                dataType: 'json',
                success: function(response) {
                  if (response.status == true) {
                    var span = document.createElement("span");
                    span.innerHTML = "" + response.pesan + "";

                    swal({
                        html: true,
                        title: "Success!",
                        content: span,
                        icon: "success"
                      })
                      .then(function() {
                        table.ajax.reload();
                      });
                  } else {
                    swal("Hapus Data Gagal!", {
                      icon: "warning",
                      title: "Failed!",
                      text: response.pesan,
                    });
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  var err = eval("(" + jqXHR.responseText + ")");
                  swal("Error!", err.Message, "error");
                }
              });
            } else {
              swal("Cancelled", "Hapus Data Dibatalkan.", "error");
            }
          });
      });
    });
  </script>
@endpush
