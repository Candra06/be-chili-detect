<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\HasilDetail;
use App\Models\Penyakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helper\Helpers;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\HistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class HasilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $dataPage = [

        "route" => [
            'index' => 'history.index',
            'add' => 'history.create',
            'show' => 'history.show',
            'detail' => 'history.detail',
        ],
        "tableHead" => ["No", "Kode","Penyakit", "Gejala"],
        "tableColumns" => ["DT_RowIndex", "kode","penyakit", "gejala"],
    ];
    public function index()
    {
        $dataPage = $this->dataPage;
        $list= Hasil::with('optResult','detailGejala','detailGejala.gejala')->get();
        $data = (object)[
            'title' => 'Data Riwayat Diagnosa',
            "createBtn" => false,
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
        return view('pages.history.index', compact('data'));
    }

    function ajax($list)
    {
        return DataTables::of($list)
            ->addIndexColumn()
            ->smart(false)
            // ->addColumn("action", function ($row) {

            //     $editRoute = route($this->dataPage['route']['edit'], $row->id);
            //     $detailRoute = route($this->dataPage['route']['show'], $row->id);
            //     $deleteRoute = route($this->dataPage['route']['delete'], $row->id);
            //     $message = 'Apakah Anda yakin untuk menghapus penyakit ' . $row->name . ' ?';

            //     $actionBtn = '<a href="'. $editRoute .'"><button class="btn-sm me-2 btn" style="font-size:24px;"><span class="fe fe-edit"></span></button></a>';
            //     $actionBtn  .= '<button class="btn-sm mr-2 modal-effect btn" data-bs-effect="effect-scale" data-bs-toggle="modal" style="font-size:24px;" onclick="deleteData(\'' . $deleteRoute . '\', \'' . $message . '\')" href="#modal-delete"><span class="fe fe-trash"></span></button>' ;

            //     return $actionBtn;
            // })
            ->addColumn("gejala", function ($row) {

                $details = collect($row->detailGejala)->map(function ($detail) {
                    return "- " ."(".$detail->gejala->kode_gejala.") " .$detail->gejala->gejala;
                })->implode("<br>");

                return $details;
            })
            ->addColumn("kode", function ($row) {
                return $row->optResult->kode_penyakit;
            })
            ->addColumn("penyakit", function ($row) {
                return $row->optResult->penyakit;
            })
            // ->addColumn("keterangan", function ($row) {
            //     return $row->keterangan;
            // })
            ->rawColumns(["gejala","kode","penyakit","keterangan"])
            ->make(true);
    }

    public function printPdf(){
        try {
            $data= Hasil::with('optResult','gejala')->get();
            // $data= Hasil::with('optResult','detailGejala','detailGejala.gejala')->get();
            // $pdf = Pdf::loadView('template.history', $data);
            // return $pdf->download('DataSet.pdf');
            $items = [];
            foreach ($data as $item) {
                $g = [];
                foreach ($item->gejala as $gj) {
                    array_push($g,$gj->densitas);
                }
                array_push($g,$item->optResult->kode_penyakit);

                array_push($items,$g);
            }
            foreach ($items as $val) {
                # code...
                Helpers::appendToCsv($val);
            }
            return $items;
            return Excel::download(new HistoryExport($data), 'hasil-diagnosa.xlsx');
            return view('template.history', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function historyByUser($deviceId) {
        try {
            $data= Hasil::with('optResult','detailGejala','detailGejala.gejala')
            ->where('created_by', $deviceId)
            ->orderBy('id', 'DESC')
            ->get();
            return response()->json(['success'=>true,'data'=>$data,'code'=>'200'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false,'message'=>'Gagal mendapatkan history : '.$th->getMessage(),'code'=>'500'], 500);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Hasil $hasil)
    {
        //
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
}
