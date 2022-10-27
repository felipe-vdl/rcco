<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marcador extends Model
{
    protected $table = "marcadors";

    protected $fillable = [
        "nome",
        "is_enabled",
        "setor_id",
        "user_id"
    ];

    public function criador() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function setor() {
        return $this->belongsTo('App\Models\Setor');
    }

    public function respostas() {
        return $this->hasMany('App\Models\Resposta');
    }
}