<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    use HasFactory;
    protected $table='rules';
    protected $fillable = ['penyakit_id'];

    public function opt() {
        return $this->belongsTo(Penyakit::class, 'penyakit_id', 'id');
    }

    public function detail() {
        return $this->hasMany(RuleDetail::class, 'id', 'rule_id');
    }
}
