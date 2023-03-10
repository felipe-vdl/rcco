<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatorioExterno extends Model
{
    protected $table = "relatorio_externos";

    protected $fillable = [
        'unidade_id',
        'user_id',
        'nome',
        'data',
        'filename',
        'extensao',
    ];

    public function criador() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function unidade() {
        return $this->belongsTo('App\Models\Unidade');
    }
}
