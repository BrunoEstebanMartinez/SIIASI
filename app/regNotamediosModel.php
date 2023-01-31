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
        $periodo = strtoupper(Trim($periodo));
             
        if($periodo)
            return $query->where('PERIODO_ID', '=', "$periodo");             
    }     

    public function scopeIdTodo($query, $todo){
        $todo = strtoupper(Trim($todo));          
        if($todo)
            return $query->where('ENT_NOFICIO', '=', "$todo")
                        ->orwhere('ENT_DESTIN', 'LIKE', "%$todo%")
                        ->orwhere('ENT_REMITEN','LIKE', "%$todo%")
                        ->orwhere('ENT_ASUNTO', 'LIKE', "%$todo%");   
    }

    public function scopefPer($query, $fper)
    {
        if($fper)
            return $query->orwhere('PERIODO_ID', '=', "$fper");
    }
    // Busca por numero de oficio
    public function scopeIdd($query, $idd)
    {
        $idd = strtoupper(Trim($idd));          
        if($idd)
            return $query->orwhere('ENT_NOFICIO', 'LIKE', "%$idd%");
    }    
    // Busca por destinatario
    public function scopeDesti($query, $desti)
    {
        $desti = strtoupper(Trim($desti));          
        if($desti) 
            return $query->orwhere('ENT_DESTIN', 'LIKE', "%$desti%");
    } 
    // Busca por remitente
    public function scopeRemi($query, $remi)
    {
        $remi = strtoupper(Trim($remi));          
        if($remi) 
            return $query->orwhere('ENT_REMITEN', 'LIKE', "%$remi%");
    }     
    // Busca por asunto
    public function scopeAsun($query, $asun)
    {
        $asun = strtoupper(Trim($asun));          
        if($asun) 
            return $query->orwhere('ENT_ASUNTO', 'LIKE', "%$asun%");
    }     
    
}
