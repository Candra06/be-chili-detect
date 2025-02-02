<?php

namespace App\Http\Controllers;

use App\Models\Rules;
use App\Models\RuleDetail;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Http\Request;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RulesController extends Controller
{
    public $dataPage = [
        "route" => [
            'index' => 'rules.index',
            'add' => 'rules.create',
            'show' => 'rules.show',
            'update' => 'rules.update',
            'edit' => 'rules.edit',
            'store' => 'rules.store',
            'detail' => 'rules.detail',
            'delete' => 'rules.destroy',
        ],
        "tableHead" => ["No", "kode","Penyakit", "Aksi"],
        "tableColumns" => ["DT_RowIndex", "kode","penyakit", "action"],
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataPage = $this->dataPage;
        $list= Rules::with('opt')->get();
        $data = (object)[
            'title' => 'Data Aturan',
            "createBtn" => true,
            'tableHead' => $dataPage['tableHead'],
            'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),
            "routeAdd" => route($dataPage['route']['add']),
            "routeData" => route($dataPage['route']['index']),
            'data' => $list,
        ];
        // return $list;
        if (request()->ajax()) {
            return $this->ajax($list);
        }
        return view('pages.rules.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penyakit = Penyakit::where('status', 'Show')->get();
        $gejala = Gejala::where('status', 'Show')->get();
        $data = (object) [
            'title' => 'Tambah Data Aturan',
            'subtitle' => 'Data Aturan',
            'base_title' => 'Tambah Data',
            'type' => 'add',
            'action' => route($this->dataPage['route']['store']),
            'data' =>(object) [
                'penyakit' => $penyakit,
                'gejala' => $gejala,
            ],
        ];
        // return $data;
        return view('pages.rules.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $opt = Rules::create(['penyakit_id'=>$request->penyakit_id]);
            $gejalaId = [];
            foreach ($request->request as $key => $value) {
                if (str_starts_with($key, 'gejala')) {
                    array_push($gejalaId,str_replace('gejala-','',$key));
                }
            }
            foreach ($gejalaId as $g) {
                RuleDetail::create([
                    'rule_id' => $opt->id,
                    'gejala_id'=>$g,
                ]);
            }
            DB::commit();
            return redirect(route($this->dataPage['route']['index']))->with('success', 'Berhasil menambah data');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Rules $rule)
    {
        $ruleDetail = RuleDetail::with('gejala')->where('rule_id', $rule->id)->get();
        $detail=$rule->opt;
        // return $detail['0'];
        $gejala = [];
        foreach ($ruleDetail as $key => $item) {
            $gejala[] = $item->gejala;
        }
        $detail['gejala']=$gejala;

        return response()->json(['data'=>$detail]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rules $rule)
    {
        return $rule->opt->penyakit;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rules $rule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rules $rules)
    {
        //
    }

    function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn('kode', function ($row) {
                return $row->opt->kode_penyakit;
            })
            ->addColumn('penyakit', function ($row) {
                return $row->opt->penyakit;
            })
            ->addColumn("action", function ($row) {

                $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus rule ' . $row->opt->penyakit . ' ?';
                $actionBtn='<button class="btn-sm modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size: 24px;" onclick="viewDetail(\'' . $detailRoute . '\')" href="#modal-detail"><span class="fe fe-eye"><span></button>';
                $actionBtn .= '<a href="'. $detailRoute .'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                $actionBtn  .= '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>' ;

                return $actionBtn;
            })
            ->rawColumns(["action"])
            ->make(true);
    }
}
