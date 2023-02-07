<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTemaModel extends Model
{
    protected $table      = "IA_CAT_TEMAS";
    protected $primaryKey = 'TEMA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'TEMA_NO',
        'TEMA_ID',        
        'TEMA_DESC',
        'TEMA_DESC_CORTA',
        'SECCION_ID',
        'TEMA_STATUS', //S ACTIVO      N INACTIVO
        'TEMA_FECREG'
    ];
}
