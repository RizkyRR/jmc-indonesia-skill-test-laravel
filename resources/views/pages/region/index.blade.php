@extends('layouts.app')

@push('after-style')
  <style>
    .dataTables_wrapper .dataTables_filter input:not(:valid):not(:focus) {
      box-shadow: 0 0 5px #fff !important;
    }

    .dataTables_wrapper .dataTables_filter input::-webkit-search-cancel-button {
      -webkit-appearance: none !important;
    }

    .dataTables_wrapper .dataTables_filter button {
      visibility: hidden;
      outline: none;
    }

    .dataTables_wrapper .dataTables_filter input:valid~button {
      visibility: visible;
    }
  </style>
@endpush

@section('title')
  Region Data
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Home</a>
      </li>

      <li class="breadcrumb-item active">Region Data</li>
    </ol>
  </nav>

  <!-- Collapse -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header">
          <div class="row ms-2 me-3">
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
              <h5 class="mb-0">Region Data</h5>
            </div>

            <div
              class="col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-3 gap-md-2">
              <div class="ms-auto">
                <a id="pdf-link" href="#" class="btn btn-secondary btn-icon-text order-0" target="__blank">
                  <i class="fas fa-file-pdf btn-icon-prepend"></i>
                  Export Pdf
                </a>

                <a href="{{ route('region.create') }}" class="btn btn-primary btn-icon-text">
                  <i class="fa fa-plus btn-icon-prepend"></i>
                  Tambah
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-12 float-left">
              <div class="row">
                <div class="col-md-8 mb-3">
                  <label>Cari berdasarkan provinsi:</label>

                  <select class="form-control" name="province_id" id="province_id" style="width: 100%">
                  </select>
                </div>

                <div class="col-md-4 mb-3 d-flex align-items-end">
                  <a href="javascript:void(0)" class="btn btn-secondary btn-icon-text btn-clear-filter">
                    Clear Filter
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="table-responsive text-nowrap">
            <table class="table" id="table-list-region" style="width: 100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Provinsi</th>
                  <th>Nama Region</th>
                  <th>Jumlah Populasi</th>
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
      // Ambil elemen <a> berdasarkan ID
      var pdfLink = $('#pdf-link');
      var getFullUrl = "{{ route('region.export-pdf') }}";
      var arrUrl = {};

      exportPdfUrl()

      function exportPdfUrl() {
        var checkObject = jQuery.isEmptyObject(arrUrl);

        console.log(checkObject);

        if (checkObject === false) {
          var jsonArrUrl = JSON.stringify(arrUrl); // Konversi objek menjadi JSON string

          // Ubah URL dengan data JSON sebagai query string
          var pdfUrl = "{{ route('region.export-pdf') }}";
          pdfUrl += "?arrUrl=" + encodeURIComponent(jsonArrUrl);

          pdfLink.attr('href', pdfUrl);
        } else {
          pdfLink.attr('href', getFullUrl);
        }
      }

      var table = $('#table-list-region').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        responsive: true,
        ajax: {
          url: "{{ url()->current() }}",
          type: "GET",
          data: function(d) {
            d.search = $('input[type="search"]').val();
            d.province_id = getProvince();
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
            data: 'name_region',
            name: 'name_region'
          },
          {
            data: 'total_population_region',
            name: 'total_population_region'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        initComplete: function(settings) {
          //settings.nTable.id --> Get table ID
          $('#' + settings.nTable.id + '_filter input')
            .wrap(`<div class="d-inline-flex position-relative"></div>`)
            .after(
              `<button type="button" class="close btn position-absolute m-0" aria-label="Close" style="right:5px"><span aria-hidden="true">&times;</span></button>`
            )
            .attr('required', 'required')
            .attr('title', 'Search');

          // Click Event on Clear button
          $(document).on('click', '#' + settings.nTable.id + '_filter button', function() {
            // jika berhasil diclear akan menghpus isian object search
            delete arrUrl.search;
            exportPdfUrl();

            $('#' + settings.nTable.id).DataTable({
                "retrieve": true,
              })
              .search('')
              .draw(); // reDraw table
          });
        }
      });

      // Cek apakah inputan search tidak kosong setelah di-clear
      if (table.search() !== null && table.search() !== '') {
        arrUrl.search = table.search();
        exportPdfUrl();
      } else {
        delete arrUrl.search;
        exportPdfUrl();
      }

      function getProvince() {
        var province_id = $("#province_id").val();

        return province_id;
      }

      $('input[type="search"]').on('keyup paste', function() {
        arrUrl.search = $(this).val();

        exportPdfUrl();
      });

      $("#province_id").select2({
        ajax: {
          url: "{{ route('get-province-by-select2') }}",
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return {
              _token: "{{ csrf_token() }}",
              search: params.term // search term
            };
          },
          processResults: function(response) {
            return {
              results: $.map(response, function(obj) {
                return {
                  id: obj.id,
                  text: obj.text
                };
              })
            };
          },
          cache: true
        },
        placeholder: 'Pilih Provinsi',
        theme: "bootstrap-5"
      });

      $("#province_id").change(function() {
        table.draw();
      });

      $('#province_id').on("select2:selecting", function(e) {
        arrUrl.province_id = e.params.args.data.id;

        exportPdfUrl()

        console.log(e.params.args.data.id);
      });

      $('.btn-clear-filter').click(function() {
        $('#province_id').val('').trigger('change');

        table.ajax.reload();

        arrUrl = {};
        exportPdfUrl();
        getProvince()
      });

      // datatables responsive
      new $.fn.dataTable.FixedHeader(table);

      //delete
      $('#table-list-region').on('click', '.btn-hapus', function() {
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
                url: '/region/delete/' + kode,
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
