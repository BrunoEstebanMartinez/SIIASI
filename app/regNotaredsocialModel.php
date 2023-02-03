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
    'RS_CALIF_IA',
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

    public function scopefPerSal($query, $fpersal)
    {
        if($fpersal)
            return $query->orwhere('PERIODO_ID', 'LIKE', "%$fpersal%");
    }
    // Busca por numero de oficio
    public function scopeIddSal($query, $periodo, $arbol)
    {
        $periodo = strtoupper(Trim($periodo));
        $arbol = strtoupper(Trim($arbol));          
        if($periodo and $arbol)
            return $query->where('PERIODO_ID', '=', "$periodo",'AND','CVE_SP', '=', "$arbol");             
    }     
 
    public function scopeIdTodo($query, $todo){
        $todo = strtoupper(Trim($todo));          
        if($todo)
            return $query->where('SAL_NOFICIO', '=', "$todo");   
    }

    // Busca por destinatario
    public function scopeDestiSal($query, $destisal)
    {
        $destisal = strtoupper(Trim($destisal));          
        if($destisal) 
            return $query->orwhere('SAL_DESTIN', 'LIKE', "%$destisal%");
    } 
    // Busca por remitente
    public function scopeRemiSal($query, $remisal)
    {
        $remisal = strtoupper(Trim($remisal));          
        if($remisal) 
            return $query->orwhere('SAL_REMITEN', 'LIKE', "%$remisal%");
    }     
    // Busca por asunto
    public function scopeAsunSal($query, $asunsal)
    {
        $asunsal = strtoupper(Trim($asunsal));          
        if($asunsal) 
            return $query->orwhere('SAL_ASUNTO', 'LIKE', "%$asunsal%");
    }     

    public function scopeEscanSal($query, $escansal){
        $escansal = strtoupper(trim($escansal));
        if($escansal){
            return $query->orwhere('SAL_DOCHIS', 'LIKE', "%$escansal");
        }
    }

    public function scopeExpArchSal($query, $exparchsal){
        $exparchsal = strtoupper(trim($exparchsal));
        if($exparchsal){
            return $query->orwhere('SAL_OBS1', 'LIKE', "%$exparchsal");
        }
    }

    public function scopeRespNoSal($query, $respnosal){
        $respnosal = strtoupper(trim($respnosal));
        if($respnosal){
            return $query->orwhere('SAL_OBS2', 'LIKE', "%$respnosal");
        }
    }
}
