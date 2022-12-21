<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    protected $table = "arquivos";

    protected $fillable = [
        "nome_origem",
        "filename",
        "extension",
        "pergunta_id",
        "resposta_id"
    ];

    public function pergunta()
    {
        return $this->belongsTo('App\Models\Pergunta');
    }

    public function resposta()
    {
        return $this->belongsTo('App\Models\Resposta');
    }
}
