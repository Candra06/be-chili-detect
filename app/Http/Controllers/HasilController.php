<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\HasilDetail;
use App\Models\Penyakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helper\Helpers;

class HasilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
