<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        "nomeFantasia", "cnpj", "email"
    ];

    public function vagas(){
        return $this->hasMany('App\Vaga', 'empresa_id');
    }
}
