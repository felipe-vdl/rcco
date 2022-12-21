<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    protected $table = "respostas";

    protected $fillable = [
        'data',
        'unidade_id',
        'pergunta_id',
        'topico_id',
        'marcador_id',
        'valor',
        'user_id',
        'status',
        'data_envio',
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

    public function marcador() {
        return $this->belongsTo('App\Models\Marcador');
    }

    public function topico() {
        return $this->belongsTo('App\Models\Topico');
    }

    public function label_valors() {
        return $this->hasMany('App\Models\LabelValor');
    }

    public function arquivos() {
        return $this->hasMany('App\Models\Arquivo');
    }
}
