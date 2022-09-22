<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadeUser extends Model
{
    protected $table = "unidade_user";

    protected $fillable = [
        'unidade_id',
        'user_id'
    ];

    public function unidade()
    {
        return $this->belongsTo('App\Models\Unidade');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}