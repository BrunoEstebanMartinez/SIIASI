<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regNotaredsocialModel extends Model
{ 
    protected $table      = "IA_REDES_SOCIALES";
    protected $primaryKey = ['PERIODO_ID','RS_FOLIO'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
    'PERIODO_ID',
    'RS_FOLIO',
    'RS_TITULO',
    'RS_NOTA',
    'RS_NOTA2',
    'RS_IA',
    'RS_IA2',
    'RS_LINK',
    'RS_ID',
    'RS_DESC',
    'RS_AUTOR',
    'RS_LIKES',
    'RS_REPOSTEOS',
    'RS_COMEN',
    'RS_ALCANCE',
    'RS_CALIF',  
    'RS_FEC_NOTA',
    'RS_FEC_NOTA2',
    'RS_FEC_NOTA3',
    'PERIODO_ID1',
    'MES_ID1',
    'DIA_ID1',
    'TEMA_ID',
    'TEMA_DESC',
    'RS_FOTO1',
    'RS_FOTO2',
    'RS_FOTO3',
    'RS_FOTO4',
    'RS_OBS1',
    'RS_OBS2',
    'RS_STATUS1',
    'RS_STATUS2',
    'FECHA_REG',
    'FECHA_REG2',
    'IP',
    'LOGIN',
    'FECHA_M',
    'FECHA_M2',
    'IP_M',
    'LOGIN_M'
    ];

 

    public function scopeIddSal($query, $periodo)
    {
        $periodo = Trim($periodo);
             
        if($periodo)
            return $query->where('PERIODO_ID', '=', "$periodo");             
    }     

    public function scopeIdTodo($query, $todo){
        $todo = Trim($todo);          
        if($todo)
            return $query->where('RS_TITULO','LIKE',"%$todo%");
    }


}
