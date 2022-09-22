<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $table = "unidades";

    protected $fillable = [
        "nome",
        "user_id",
        "setor_id"
    ];

    public function criador()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function setor()
    {
        return $this->belongsTo('App\Models\Setor');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    // belongsToMany Pergunta
    
    // hasMany Resposta
}