<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    use HasFactory;
    protected $table = 'gejala';
    protected $fillable = ['kode_gejala','gejala','densitas','status'];

    public function gejala(): HasMany
    {
        return $this->hasMany(RuleDetail::class, 'gejala_id', 'id');
    }

    public function gejalaHasil(): HasMany
    {
        return $this->hasMany(HasilDetail::class, 'gejala_id', 'id');
    }
}
