<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if (request()->ajax()) {
      $datas = Province::orderBy('created_at', 'desc')->get();

      return DataTables::of($datas)
        ->filter(function ($instance) use ($request) {
          if (!empty($request->get('search'))) {
            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
              if (Str::contains(Str::lower($row['name_province']), Str::lower($request->get('search')))) {
                return true;
              }

              return false;
            });
          }
        })
        ->addColumn('action', function ($data) {
          //detail
          $btn_detail = '<a class="dropdown-item" href="' . route('province.show', $data->id) . '"><i class="fas fa-eye me-1"></i> Detail</a>';

          //edit
          $btn_edit = '<a class="dropdown-item" href="' . route('province.edit', $data->id) . '"><i class="fas fa-pencil-alt me-1"></i> Edit</a>';

          //delete
          $btn_hapus = '<a class="dropdown-item btn-hapus" href="javascript:void(0)" data-id="' . $data->id  . '" data-nama="' . $data->name_province . '"><i class="fas fa-trash-alt me-1"></i> Hapus</a>';



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
        ->addColumn('total_region', function ($data) {
          return count($data->region);
        })
        ->rawColumns([
          'action',
          'total_region'
        ])
        ->addIndexColumn() //increment
        ->make(true);
    };

    return view('pages.province.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('pages.province.create');
  }

  public function rules($request)
  {
    $rule = [
      'name_province' => 'required|string|max:200',
    ];

    $pesan = [
      'name_province.required' => 'Nama provinsi wajib diisi!',
      'name_province.max' => 'Nama provinsi tidak boleh lebih dari 200 karakter!',
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
        $item_check_province = Province::where('name_province', 'like', '%' . $request->name_province . '%')
          ->first();

        if ($item_check_province) {
          return response()->json([
            'status' => false,
            'pesan' => "Provinsi sudah tersedia silahkan inputkan nama provinsi yang berbeda!",
          ], 200);
        } else {
          $post = new Province();
          $post->name_province = $request->name_province;

          $simpan = $post->save();

          DB::commit();

          if ($simpan == true) {
            return response()->json([
              'status' => true,
              'pesan' => "Data provinsi berhasil dibuat!"
            ], 200);
          } else {
            return response()->json([
              'status' => false,
              'pesan' => "Data provinsi tidak dapat dibuat!"
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
   * @param  \App\Models\Province  $province
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $item = Province::findOrFail($id);

    return view('pages.province.show', compact('item'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Province  $province
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $item = Province::findOrFail($id);

    return view('pages.province.edit', compact('item'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Province  $province
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
        $check_exist_province = Province::findOrFail($id);

        if ($check_exist_province->name_province != $request->name_province) {
          $is_relate = Province::where('name_province', 'like', '%' . $request->name_province . '%')
            ->first();

          if ($is_relate) {
            return response()->json([
              'status' => false,
              'pesan' => "Provinsi sudah tersedia silahkan inputkan nama provinsi yang berbeda!",
            ], 200);
          } else {
            $name_province = $request->name_province;
          }
        } else {
          $name_province = $request->name_province;
        }

        $post = Province::findOrFail($id);
        $post->name_province = $name_province;

        $simpan = $post->save();

        DB::commit();

        if ($simpan == true) {
          return response()->json([
            'status' => true,
            'pesan' => "Data provinsi berhasil diubah!"
          ], 200);
        } else {
          return response()->json([
            'status' => false,
            'pesan' => "Data provinsi tidak dapat diubah!"
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
   * @param  \App\Models\Province  $province
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $query_province = Province::where('id', $id);

    $item_province = $query_province->first();

    Region::where('province_id', $item_province->id)->delete();

    $hapus = $query_province->delete();

    if ($hapus == true) {
      return response()->json([
        'status' => true,
        'pesan' => "Data province berhasil dihapus!"
      ], 200);
    } else {
      return response()->json([
        'status' => false,
        'pesan' => "Data province tidak berhasil dihapus!"
      ], 200);
    }
  }

  public function getProvinceBySelect2(Request $request)
  {
    $search = $request->search;

    if ($search == '') {
      $data = Province::limit(20)
        ->get();
    } else {
      $data = Province::where('name_province', 'like', '%' . $search . '%')
        ->limit(20)
        ->get();
    }

    $response = array();
    foreach ($data as $item) {

      $response[] = array(
        "id" => $item->id,
        "text" => $item->name_province
      );
    }

    return response()->json($response);
  }
}
