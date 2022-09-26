<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $table = "perguntas";

    protected $fillable = [
        "nome",
        "formato",
        "tipo",
        "is_required",
        "is_enabled",
        "topico_id",
        "user_id"
    ];

    public function criador() {
        $this->belongsTo('App\Models\User', 'user_id');
    }

    public function topico() {
        $this->belongsTo('App\Models\Topico');
    }
    
    public function unidades() {
        $this->belongsToMany('App\Models\Unidade');
    }

    public function label_options() {
        $this->hasMany('App\Models\LabelOption');
    }
}
