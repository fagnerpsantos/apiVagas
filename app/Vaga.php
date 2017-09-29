<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    protected $fillable = [
        "titulo", "descricao", "salario", "qtdHoras", "qtdVagas", "empresa_id"
    ];

    public function empresa(){
        return $this->belongsTo('App\Empresa', 'empresa_id');
    }
}
