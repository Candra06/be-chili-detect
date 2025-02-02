<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use Illuminate\Http\Request;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class GejalaController extends Controller
{
    public $dataPage = [
        "forms" => [
            [
                'name' => 'kode_gejala',
                'title' => 'Kode Gejala',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Kode Gejala',
            ],

            [
                'name' => 'gejala',
                'title' => 'Nama Gejala',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Nama Gejala',
            ],
            [
                'name' => 'densitas',
                'title' => 'Nilai Densitas',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Masukkan nilai gejala',
            ],
            [
                'name' => 'bagian',
                'title' => 'Bagian yang di serang',
                'type' => 'select',
                'required' => false,
                'class' => 'select2-no-search',
                'data' => [
                    [
                        'id'=>'Daun',
                        'val'=>'Daun',
                    ],
                    [
                        'id'=>'Batang',
                        'val'=>'Batang',
                    ],
                    [
                        'id'=>'Buah',
                        'val'=>'Buah',
                    ],
                    [
                        'id'=>'Bunga',
                        'val'=>'Bunga',
                    ],
                    [
                        'id'=>'Akar',
                        'val'=>'Akar',
                    ],
                    [
                        'id'=>'Buah',
                        'val'=>'Buah',
                    ],
                ],
                'placeholder' => 'Pilih bagian yang diserang',
            ],
        ],
        "route" => [
            'index' => 'gejala.index',
            'add' => 'gejala.create',
            'show' => 'gejala.show',
            'update' => 'gejala.update',
            'edit' => 'gejala.edit',
            'store' => 'gejala.store',
            'detail' => 'gejala.detail',
            'delete' => 'gejala.destroy',
        ],
        "tableHead" => ["No", "kode",'gejala','densitas', "Aksi"],
        "tableColumns" => ["DT_RowIndex", "kode_gejala",'gejala','densitas', "action"],
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataPage = $this->dataPage;
        $list= Gejala::where('status', 'Show')->orderBy('id', 'ASC')->get();
        $data = (object)[
            'title' => 'Data Gejala',
            "createBtn" => true,
            'tableHead' => $dataPage['tableHead'],
            'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
            "routeAdd" => route($dataPage['route']['add']),
            "routeData" => route($dataPage['route']['index']),
            'data' => $list,
        ];
        if (request()->ajax()) {
            return $this->ajax($list);
        }
        return view('pages.gejala.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $forms = $this->dataPage['forms'];
        $data = (object) [
            'title' => 'Tambah Data Gejala',
            'subtitle' => 'Data Gejala',
            'base_title' => 'Tambah Data',
            'type' => 'add',

            'action' => route($this->dataPage['route']['store']),
            'data' => [],
            'forms' => $forms,
            // 'menus' => $this->getMenus(),
            // 'useEditor' => TRUE,
        ];
        return view('template.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->toArray();
            $input['densitas'] = str_replace(',','.',$request->densitas);
            Gejala::create($input);
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Gejala $gejala)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gejala $gejala)
    {
        try {
            $page = $this->dataPage;
            $data = (object) [
                'title' => 'Data Gejala',
                'subtitle' => 'Edit Data',
                'type' => 'edit',
                'action' => route($page['route']['update'], ['gejala' => $gejala]),
                'data' => $gejala->toArray(),
                'forms' => $page['forms'],
            ];
            // return $data;
            return view('template.form', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gejala $gejala)
    {
        try {
            DB::beginTransaction();
            $input = $request->toArray();
            unset($input['_method']);
            unset($input['_token']);

            $input['densitas'] = str_replace(',','.',$request->densitas);
            Gejala::where('id', $gejala->id)->update($input);
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil mengubah data');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gejala $gejala)
    {
        try {
            DB::beginTransaction();
            Gejala::where('id',$gejala->id)->update(['status'=> 'Deleted']);
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menghapus data ');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data'.$th->getMessage());
        }
    }

    function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn("action", function ($row) {

                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus gejala ' . $row->name . ' ?';

                $actionBtn = '<a href="'. $editRoute .'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn  .= '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>' ;

                return $actionBtn;
            })
            ->rawColumns(["action"])
            ->make(true);
    }

    public function listGejala() {
        try {
            $list = Gejala::where('status', 'Show')->get();
            $data = ['code'=>"200",'status'=>true,'data'=>$list];
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json(['code'=>"500","error" => $th->getMessage()], 500);
        }
    }
}
