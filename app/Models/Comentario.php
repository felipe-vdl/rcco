<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = "comentarios";

    protected $fillable = [
        "data",
        "content",
        "relator_id",
        "unidade_id",
        "user_id"
    ];

    public function criador() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function relator() {
        return $this->belongsTo('App\Models\User', 'relator_id');
    }

    public function unidade () {
        return $this->belongsTo('App\Models\Unidade');
    }
}
