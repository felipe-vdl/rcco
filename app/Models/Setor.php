<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    protected $table= "setors";

    protected $fillable = [
        "nome",
        "user_id",
    ];

    public function criador()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function unidades()
    {
        return $this->hasMany('App\Models\Unidade');
    }
    
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}