<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilDetail extends Model
{
    use HasFactory;
    protected $table='hasil_detail';
    protected $fillable =['hasil_id','gejala_id','densitas'];

    public function detailGejala() {
        return $this->belongsTo(Hasil::class, 'hasil_id', 'id');
        // return $this->belongsTo(Rules::class, 'hasil_id', 'id');
    }

    public function gejala() {
        return $this->belongsTo(Gejala::class, 'gejala_id', 'id');
    }
}
