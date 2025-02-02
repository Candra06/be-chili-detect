<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    use HasFactory;
    protected $table = 'penyakit';
    protected $fillable = ['kode_penyakit','penyakit','densitas','status'];

    public function opt(): HasMany
    {
        return $this->hasMany(Rules::class, 'penyakit_id', 'id');
    }
}
