<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetorUser extends Model
{
    protected $table = "setor_user";

    protected $fillable = [
        'setor_id',
        'user_id'
    ];
    
    public function setor()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Setor');
    }
}
