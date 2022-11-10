<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelOption extends Model
{
    protected $table = "label_options";

    protected $fillable = [
        "nome",
        "pergunta_id",
        'is_enabled',
    ];

    public function pergunta()
    {
        return $this->belongsTo('App\Models\Pergunta');
    }

    // public function label_valors()
    // {
    //     $this->hasMany('App\Models\LabelValor');
    // }
}
