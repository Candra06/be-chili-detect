<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleDetail extends Model
{
    use HasFactory;
    protected $table='rules_detail';
    protected $fillable = ['rule_id','gejala_id'];

    public function detail() {
        return $this->belongsTo(Rules::class, 'rule_id', 'id');
    }

    public function gejala() {
        return $this->belongsTo(Gejala::class, 'gejala_id', 'id');
    }
}
