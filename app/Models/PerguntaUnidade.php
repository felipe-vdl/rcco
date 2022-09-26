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
        $this->belongsTo('App\Models\Pergunta');
    }
    
    public function unidade()
    {
        $this->belongsTo('App\Models\Unidade');
    }
}
