<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelValor extends Model
{
    protected $table = "label_valors";

    protected $fillable = [
        'valor',
        'pergunta_id',
        'resposta_id',
        'label_option_id'
    ];

    public function resposta() {
        return $this->belongsTo('App\Models\Resposta');
    }

    public function pergunta() {
        return $this->belongsTo('App\Models\Pergunta');
    }

    public function label_option() {
        return $this->belongsTo('App\Models\LabelOption');
    }
}