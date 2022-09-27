<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    protected $table = "respostas";

    protected $fillable = [
        'data',
        'valor',
        'pergunta_id',
        'status',
        'data_envio',
        'unidade_id',
        'user_id'
    ];

    public function criador() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function unidade() {
        return $this->belongsTo('App\Models\Unidade');
    }

    public function pergunta() {
        return $this->belongsTo('App\Models\Pergunta');
    }

    public function label_valors() {
        return $this->hasMany('App\Models\LabelValor');
    }
}
