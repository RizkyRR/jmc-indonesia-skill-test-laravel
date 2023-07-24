<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <title>{{ $title }}</title>

  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .header {
      text-align: center;
    }

    .report-title {
      font-size: 24px;
      font-weight: bold;
    }

    .separator {
      margin: 20px 0;
      border-bottom: 2px solid black;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      border: 1px solid black;
      padding: 8px;
    }

    .table th {
      background-color: #f2f2f2;
    }

    body {
      font-family: Arial, Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
      text-justify: inter-word;
    }

    .page-break {
      page-break-after: always;
    }
  </style>
</head>

<body>
  <div class="header">
    <div class="report-title">{{ $title }}</div>
  </div>

  <div class="separator"></div>

  <table class="table">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Provinsi</th>
        <th>Nama Region</th>
        <th>Jumlah Penduduk</th>
      </tr>
    </thead>

    <tbody>

      @if ($datas != null)
        @php
          $row_number = 0;
        @endphp

        @foreach ($datas as $item)
          <tr>
            <td>{{ ++$row_number }}</td>

            <td>
              {{ $item->province->name_province }}
            </td>

            <td>
              {{ $item->name_region }}
            </td>

            <td>
              {{ $item->total_population_region }}
            </td>
          </tr>
        @endforeach

        <tr>
          <td colspan="3" style="text-align: center; font-weight: bold">Total Keseluruhan</td>
          <td>{{ $total_populasi_keseluruhan }}</td>
        </tr>
      @else
        <tr>
          <td colspan="4">Data Tidak Tersedia</td>
        </tr>
      @endif

    </tbody>
  </table>
</body>

</html>
