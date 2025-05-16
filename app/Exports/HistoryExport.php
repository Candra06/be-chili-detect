<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class HistoryExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($hasilDiagnosa)
    {
        $this->data = $hasilDiagnosa;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            // Flatten gejala to a line-separated string
            $gejala = collect($item['detailGejala'])->map(function ($detail) {
                return "- (" . $detail['gejala']['kode_gejala'] . ") " . $detail['gejala']['gejala'];
            })->implode("<br>");

            return [
                'ID' => $item['id'],
                'Kode Penyakit' => $item['optResult']['kode_penyakit'],
                'Nama Penyakit' => $item['optResult']['penyakit'],
                'Gejala' => $gejala,
                'Keterangan' => $item['keterangan'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Penyakit',
            'Nama Penyakit',
            'Gejala',
            'Keterangan',
        ];
    }
}
