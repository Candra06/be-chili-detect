<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\HasilDetail;
use App\Models\Penyakit;
use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class HasilController extends Controller
{
    public $dataPage = [
        "route" => [
            'index' => 'list',
        //     'add' => 'rules.create',
            'show' => 'list-detail',
        //     'update' => 'rules.update',
        //     'edit' => 'rules.edit',
        //     'store' => 'rules.store',
            'detail' => 'rules.detail',
        //     'delete' => 'rules.destroy',
        ],
        "tableHead" => ["No", "kode","Penyakit", "Aksi"],
        "tableColumns" => ["DT_RowIndex", "kode","penyakit", "action"],
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyakit = Penyakit::where('status', 'Show')->get();
        $gejala = Gejala::where('status', 'Show')->get();
        $data = (object) [
            'title' => 'Tambah Data Referensi',
            'subtitle' => 'Data Referensi',
            'base_title' => 'Tambah Data',
            'type' => 'add',
            'action' => url('save-diagnosa'),
            'data' =>(object) [
                'penyakit' => $penyakit,
                'gejala' => $gejala,
            ],
        ];
        // return $data;
        return view('pages.diagnosa.form', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        $dataPage = $this->dataPage;
        $list= Hasil::with('optResult')->where('created_by','Pakar')->get();
        // return $list;
        $data = (object)[
            'title' => 'Data Referensi',
            "createBtn" => true,
            'tableHead' => $dataPage['tableHead'],
            'tableColumns' => Helpers::tableColumns($dataPage['tableColumns']),

            "routeData" => route($dataPage['route']['index']),
            'data' => $list,
        ];
        // return $list;
        if (request()->ajax()) {
            return $this->ajax($list);
        }
        return view('pages.diagnosa.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penyakit_id' => 'required',
        ]);
        $gejalaId = [];
        foreach ($request->request as $key => $value) {
            if (str_starts_with($key, 'gejala')) {
                array_push($gejalaId,str_replace('gejala-','',$key));
            }
        }
        // return $request;
        try {
            if (count($gejalaId) < 1) {
                return back()->with('warning', 'Harap pilih minimal 1 gejala!');
            }
            DB::beginTransaction();
            $inputResult = [
                'is_valid' => 'Y',
                'created_by' => 'Pakar',
                'keterangan' => '-',
                'penyakit_id_result' => $request->penyakit_id,
                'penyakit_id_recommended' => $request->penyakit_id,
            ];

            $hasil = Hasil::create($inputResult);


            $dens = [];
            $prepareCsv = [];
            $gejala = Gejala::where('status', 'Show')->get();
            foreach ($gejala as $value) {
                if (in_array($value->id, $gejalaId)) {
                    array_push($dens,[
                        'hasil_id' => $hasil->id,
                        'gejala_id' => $value->id,
                        'densitas' => $value->densitas,
                    ]);
                    array_push($prepareCsv, $value->densitas);
                } else {
                    array_push($dens,[
                        'hasil_id' => $hasil->id,
                        'gejala_id' => $value->id,
                        'densitas' => 0,
                    ]);
                    array_push($prepareCsv, 0);
                }
            }
            // return $hasil->optResult->kode_penyakit;
            array_push($prepareCsv, $hasil->optResult->kode_penyakit);
            array_push($prepareCsv, 'Pakar');
            foreach ($dens as $g) {
                HasilDetail::create($g);
            }
            $addDataSet = Helpers::appendToCsv($prepareCsv);
            if (!$addDataSet) {
                return back()->with('error', 'Gagal menambah data csv');
            }

            DB::commit();
            return redirect('/')->with('success', 'Berhasil menambah data');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', $th->getError());
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Hasil $hasil)
    {
        $hasilDetail = HasilDetail::with('gejala')->where('hasil_id', $hasil->id)->get();
        // return $hasilDetail;
        $detail=$hasil->opt;
        // return $detail['0'];
        $gejala = [];
        foreach ($hasilDetail as $key => $item) {
            $gejala[] = $item->gejala;
        }
        $detail['detail']=$hasilDetail;
        $detail['gejala']=$gejala;

        return response()->json(['data'=>$detail]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hasil $hasil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hasil $hasil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hasil $hasil)
    {
        //
    }

    public function executeModel(Request $request) {
        $prepareInput = [];
        // return $prepareInput;
        $inputData = $request->input('numbers');
        // $inputFromRequest = json_encode(["numbers" => $request->input('numbers')]);

        // Encode input ke JSON
        $jsonInput = json_encode($inputData);

        $command = "python3 " . base_path('main.py');

        $descriptorSpec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];

        $process = proc_open($command, $descriptorSpec, $pipes);
        if (is_resource($process)) {
            fwrite($pipes[0], $jsonInput);
            fclose($pipes[0]);
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            proc_close($process);
            if ($error) {
                return response()->json(["error" => $error], 500);
            }
            $ress = json_decode($output, true);
            $inputCsv = implode(",",$inputData).','.$ress['result'];
            array_push($inputData, $ress['result']);
            $getDesease = Penyakit::where('kode_penyakit', $ress['result'])->first();

            $inputResult = [
                'is_valid' => $request->is_valid,
                'created_by' => $request->created_by,
                'keterangan' => $request->keterangan,
                'penyakit_id_result' => $getDesease->id,
                'penyakit_id_recommended' => $getDesease->id,
            ];
            $hasil = Hasil::create($inputResult);

            $prepareInput = [];
            foreach ($request->input as $val) {
                $tmp = [
                    'hasil_id'=>$hasil->id,
                    'gejala_id'=>$val['id'],
                    'densitas'=>$val['densitas']
                ];
                HasilDetail::create($tmp);
                array_push($prepareInput, $val['densitas']);
            }
            $result = [];
            foreach ($getDesease as $key => $value) {
                $result[$key] = $value;
            }

            $result['data']['accuracy']= $ress['accuracy'];
            $result['data']['data']= $hasil->optResult;
            $result['data']['data']['result_id']= $hasil->id;
            $result['code']= '200';
            array_push($inputData, $hasil->id);
            $addDataSet = Helpers::appendToCsv($inputData);
            if ($addDataSet) {
                return response()->json($result, 200);

            }else{
                return response()->json(["error" => "Failed to execute Python script"], 500);
            }

        }
        return response()->json(["error" => "Failed to execute Python script"], 500);
    }

    public function validateDiagnose(Request $request) {

        try {
            $inputResult = [
                'is_valid' => $request->is_valid,
                'created_by' => $request->created_by,
                'keterangan' => $request->keterangan??'-',
                'penyakit_id_recommended' => $request->penyakit_id,
            ];
            Hasil::where('id', $request->id)->update($inputResult);
            return response()->json(['success'=>true,'message'=>'Success validate','code'=>'200'], 200);
        } catch (\Throwable $th) {
            return response()->json(["error" => $th->getMessage(),'code'=>500], 500);
        }
    }


    function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            ->addColumn('kode', function ($row) {
                return $row->optResult->kode_penyakit;
            })
            ->addColumn('penyakit', function ($row) {
                return $row->optResult->penyakit;
            })
            ->addColumn("action", function ($row) {

                // $editRoute = route($this->dataPage['route']['edit'], $row->id);
                $detailRoute = route($this->dataPage['route']['show'], $row->id);
                // $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
                $message = 'Apakah Anda yakin untuk menghapus rule ' . $row->optResult->penyakit . ' ?';
                $actionBtn='<button class="btn-sm modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size: 24px;" onclick="viewDetail(\'' . $detailRoute . '\')" href="#modal-detail"><span class="fe fe-eye"><span></button>';
                // $actionBtn .= '<a href="'. $detailRoute .'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
                // $actionBtn  .= '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>' ;

                return $actionBtn;
            })
            ->rawColumns(["action"])
            ->make(true);
    }
}
