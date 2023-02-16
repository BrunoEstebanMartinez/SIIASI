<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regNotamediosModel extends Model
{
    protected $table      = "IA_NOTAS_MEDIOS";
    protected $primaryKey = ['PERIODO_ID','NM_FOLIO'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
    'PERIODO_ID',
    'NM_FOLIO',
    'NM_TITULO',
    'NM_NOTA',
    'NM_NOTA2',
    'NM_IA',
    'NM_IA2',
    'NM_LINK',
    'MEDIO_ID',
    'MEDIO_DESC',
    'TIPON_ID',
    'TIPON_DESC',
    'NM_AUTOR',
    'NM_CALIF',
    'NM_CALIF_IA',
    'NM_FEC_NOTA',
    'NM_FEC_NOTA2',
    'NM_FEC_NOTA3',
    'PERIODO_ID1',
    'MES_ID1',
    'DIA_ID1',
    'TEMA_ID',
    'TEMA_DESC',
    'NM_FOTO1',
    'NM_FOTO2',
    'NM_FOTO3',
    'NM_FOTO4',
    'NM_OBS1',
    'NM_OBS2',
    'NM_STATUS1',
    'NM_STATUS2',    
    'FECHA_REG',
    'FECHA_REG2',
    'IP',
    'LOGIN',
    'FECHA_M',
    'FECHA_M2',
    'IP_M',
    'LOGIN_M'     
    ];
 

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************// 
    public function scopeIddSal($query, $periodo)
    {
        $periodo = Trim($periodo);
             
        if($periodo)
            return $query->where('PERIODO_ID', '=', "$periodo");             
    } 

    public function scopeIdMedio($query, $medio){

        $medio = Trim($medio);

        if($medio)
            return $query->where('MEDIO_DESC', $medio);
    }


    public function scopeIdTipo($query, $tipo){

        $tipo = Trim($tipo);

        if($tipo)
            return $query->where('TIPON_DESC', $tipo);
        

    }

    public function scopeIdTodo($query, $todo){
        $todo = Trim($todo);          
        if($todo)
            return $query->where('NM_TITULO','LIKE',"%$todo%")
                            ->orwhere('MEDIO_DESC', 'LIKE', "%$todo%");
    }


}
