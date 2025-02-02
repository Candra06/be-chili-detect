<?php
namespace App\Helper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Helpers {

    public static function dateFormatted($tanggal, $format = 'Y-m-d')
    {
        $carbonDate = Carbon::parse($tanggal);
        $tanggalFormat = $carbonDate->translatedFormat($format);
        return $tanggalFormat;
    }

    public static function tableColumns($data = [], $order = [], $search = [])
    {
        $format = [];
        foreach ($data as $value) {
            if ($value == "action") {
                $format[] = ["data" => $value, "name" => $value, "searchable" => false, "orderable" => false, "width" => '10%'];
            } else {
                if ($value == "DT_RowIndex" || $value == "select") {
                    $format[] = ["data" => $value, "name" => $value, "width" => '7%'];
                } else {
                    $format[] = ["data" => $value, "name" => $value,];
                }
            }
        }
        foreach ($order as $key => $val) {
            $format[$key]['orderable'] = $val;
        }
        foreach ($search as $val) {
            $format[$key]['searchable'] =  $val;
        }
        return $format;
    }

    public static function getNumberOnly($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    public static function appendToCsv($newData)
    {
        try {
            $filePath = storage_path('app/master-cabai.csv');

            $file = fopen($filePath, "a"); // Open in append mode
            fputcsv($file, $newData); // Append new row
            fclose($file);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }



}
?>
