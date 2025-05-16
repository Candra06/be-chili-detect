<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Riwayat Diagnosa</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Penyakit</th>
                <th>Nama Penyakit</th>
                <th>Gejala</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                @php
                    $totalGejala = 0;
                    $gejala = collect($item->detailGejala)->map(function ($detail) {
                                    return "<li>" ."(".$detail->gejala->kode_gejala.") " .$detail->gejala->gejala."</li>";
                                })->implode("");
                @endphp
                @foreach ($item->detailGejala as $dg)

                @endforeach

                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->optResult->kode_penyakit}}</td>
                    <td>{{$item->optResult->penyakit}}</td>
                    <td>{!! $gejala !!}</td>
                    <td>{{$item->keterangan == '-'?'':'-'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
