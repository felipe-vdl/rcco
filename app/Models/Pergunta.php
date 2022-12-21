<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $table = "perguntas";

    protected $fillable = [
        "index",
        "nome",
        "formato",
        "tipo",
        "is_required",
        "is_enabled",
        "topico_id",
        "user_id"
    ];

    public function criador() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function topico() {
        return $this->belongsTo('App\Models\Topico');
    }
    
    public function unidades() {
        return $this->belongsToMany('App\Models\Unidade');
    }

    public function label_options() {
        return $this->hasMany('App\Models\LabelOption');
    }

    public function arquivos() {
        return $this->hasMany('App\Models\Arquivo');
    }
}
