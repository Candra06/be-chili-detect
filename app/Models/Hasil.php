<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    use HasFactory;
    protected $table='hasil';
    protected $fillable =['is_valid','penyakit_id_result','penyakit_id_recommended','created_by','keterangan'];

    public function optResult() {
        return $this->belongsTo(Penyakit::class, 'penyakit_id_result', 'id');
    }

    public function optRecommend() {
        return $this->belongsTo(Penyakit::class, 'penyakit_id_recommended', 'id');
    }

    public function detail() {
        return $this->hasMany(HasilDetail::class, 'id', 'hasil_id');
    }
}
