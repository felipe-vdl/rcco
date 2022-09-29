<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topico extends Model
{
    protected $table = "topicos";

    protected $fillable = [
        "nome",
        "setor_id",
        "user_id"
    ];

    public function criador()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function setor()
    {
        return $this->belongsTo('App\Models\Setor');
    }

    public function perguntas()
    {
        return $this->hasMany('App\Models\Pergunta');
    }

    public function respostas()
    {
        return $this->hasMany('App\Models\Resposta');
    }
}