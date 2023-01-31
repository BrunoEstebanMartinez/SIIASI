<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTiponotaModel extends Model
{
    protected $table      = "IA_CAT_TIPOS_NOTAS";
    protected $primaryKey = 'TIPON_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'TIPON_ID',
        'TIPON_DESC',
        'TIPON_STATUS',
        'TIPON_FECREG'
    ];

    public static function ObtSeccion($id){
        return (regPerModel::select('SECCION_DESC')
                            ->where('SECCION_ID','=',$id)
                            ->get());
    }
}