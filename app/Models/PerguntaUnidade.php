<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerguntaUnidade extends Model
{
    protected $table = "pergunta_unidade";

    protected $fillable = [
        "pergunta_id",
        "unidade_id"
    ];

    public function pergunta()
    {
        return $this->belongsTo('App\Models\Pergunta');
    }
    
    public function unidade()
    {
        return $this->belongsTo('App\Models\Unidade');
    }
}
