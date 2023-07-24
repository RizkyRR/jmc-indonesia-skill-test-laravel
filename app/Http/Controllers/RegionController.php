<?php

namespace App\Http\Controllers;


use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if (request()->ajax()) {
      $datas = Region::with('province');

      if (!empty($request->get('province_id'))) {
        $datas = $datas->where(function ($query) use ($request) {
          $query->where('province_id', $request->get('province_id'));
        });
      }

      $datas = $datas->get()->sortBy('region.name_province');

      return DataTables::of($datas)
        ->filter(function ($instance) use ($request) {
          if (!empty($request->get('search'))) {
            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
              if (Str::contains(Str::lower($row['name_province']), Str::lower($request->get('search')))) {
                return true;
              } elseif (Str::contains(Str::lower($row['name_region']), Str::lower($request->get('search')))) {
                return true;
              }

              return false;
            });
          }
        })
        ->addColumn('action', function ($data) {
          //detail
          $btn_detail = '<a class="dropdown-item" href="' . route('region.show', $data->id) . '"><i class="fas fa-eye me-1"></i> Detail</a>';

          //edit
          $btn_edit = '<a class="dropdown-item" href="' . route('region.edit', $data->id) . '"><i class="fas fa-pencil-alt me-1"></i> Edit</a>';

          //delete
          $btn_hapus = '<a class="dropdown-item btn-hapus text-danger" href="javascript:void(0)" data-id="' . $data->id  . '" data-nama="' . $data->name_region . '"><i class="fas fa-trash-alt me-1"></i> Hapus</a>';



          return '<div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu menu-action-datatable">
                  ' . $btn_detail . '
                  ' . $btn_edit . '
                  ' . $btn_hapus . '
                  </div>
                </div>';
        })
        ->addColumn('name_province', function ($data) {
          return $data->province->name_province;
        })
        ->rawColumns([
          'action',
          'name_province'
        ])
        ->addIndexColumn() //increment
        ->make(true);
    };

    return view('pages.region.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('pages.region.create');
  }

  public function rules($request)
  {
    $rule = [
      'name_region' => 'required|string|max:200',
      'province_id' => 'required',
      'total_population_region' => 'required|numeric',
    ];

    $pesan = [
      'name_region.required' => 'Nama region wajib diisi!',
      'name_region.max' => 'Nama region tidak boleh lebih dari 200 karakter!',
      'province_id.required' => 'Data provinsi wajib diisi!',
      'total_population_region.required' => 'Total populasi wajib diisi!',
      'total_population_region.numeric' => 'Total populasi wajib diisi dengan angka!',
    ];

    return Validator::make($request, $rule, $pesan);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validator = $this->rules($request->all());

    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'pesan' => $validator->errors()
      ]);
    } else {
      DB::beginTransaction();

      try {
        $item_check_region = Region::where('province_id', $request->province_id)
          ->where('name_region', 'like', '%' . $request->name_region . '%')
          ->first();

        if ($item_check_region) {
          return response()->json([
            'status' => false,
            'pesan' => "Region sudah tersedia silahkan inputkan nama region yang berbeda!",
          ], 200);
        } else {
          $post = new Region();
          $post->province_id = $request->province_id;
          $post->name_region = $request->name_region;
          $post->total_population_region = $request->total_population_region;

          $simpan = $post->save();

          DB::commit();

          if ($simpan == true) {
            return response()->json([
              'status' => true,
              'pesan' => "Data region berhasil dibuat!"
            ], 200);
          } else {
            return response()->json([
              'status' => false,
              'pesan' => "Data region tidak dapat dibuat!"
            ], 200);
          }
        }
      } catch (\Exception $e) {
        DB::rollback();

        return response()->json([
          'status' => false,
          'pesan' => $e->getMessage()
        ], 200);
      }
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Region  $region
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $item = Region::findOrFail($id);

    return view('pages.region.show', compact('item'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Region  $region
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $item = Region::findOrFail($id);

    return view('pages.region.edit', compact('item'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Region  $region
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $validator = $this->rules($request->all());

    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'pesan' => $validator->errors()
      ]);
    } else {
      DB::beginTransaction();

      try {
        // pastikan id region ada dan sesuai dengan yang akan diupdate
        $check_exist_region = Region::findOrFail($id);

        // logic ketika user mengubah data region berbeda dengan isian aslinya untuk dicek dengan data lainnya
        if ($check_exist_region->name_region != $request->name_region) {
          // mencari kesamaan dengan where like agar meminimalisisr kesamaan
          $is_relate = Region::where('province_id', $request->province_id)
            ->where('name_region', 'like', '%' . $request->name_region . '%')
            ->first();

          if ($is_relate) {
            return response()->json([
              'status' => false,
              'pesan' => "Region sudah tersedia silahkan inputkan nama region yang berbeda!",
            ], 200);
          } else {
            $name_region = $request->name_region;
          }
        } else {
          $name_region = $request->name_region;
        }

        $post = Region::findOrFail($id);
        $post->name_region = $name_region;
        $post->total_population_region = $request->total_population_region;

        $simpan = $post->save();

        DB::commit();

        if ($simpan == true) {
          return response()->json([
            'status' => true,
            'pesan' => "Data region berhasil diubah!"
          ], 200);
        } else {
          return response()->json([
            'status' => false,
            'pesan' => "Data region tidak dapat diubah!"
          ], 200);
        }
      } catch (\Exception $e) {
        DB::rollback();

        return response()->json([
          'status' => false,
          'pesan' => $e->getMessage()
        ], 200);
      }
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Region  $region
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $hapus = Region::where('id', $id)->delete();

    if ($hapus == true) {
      return response()->json([
        'status' => true,
        'pesan' => "Data region berhasil dihapus!"
      ], 200);
    } else {
      return response()->json([
        'status' => false,
        'pesan' => "Data region tidak berhasil dihapus!"
      ], 200);
    }
  }

  public function exportPdf(Request $request)
  {
  }
}
