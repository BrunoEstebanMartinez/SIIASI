<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regAtenderrecepModel extends Model
{
    protected $table      = "OFIPA_ENTRADAS_RESPUESTAS";
    protected $primaryKey = ['PERIODO_ID','FOLIO'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
    'PERIODO_ID',
    'FOLIO',
    'ENT_FOLIO',
    'ENT_NOFICIO',
    'ENT_DESTIN',
    'ENT_REMITEN',
    'ENT_ASUNTO',
    'ENT_UADMON',
    'ENT_TURNADO_A',
    'CVE_SP',
    'UADMON_ID',
    'CVE_SP2',
    'UADMON_ID2',
    'ENT_RESP',
    'ENT_FEC_OFIC', 
    'ENT_FEC_OFIC2', 
    'ENT_FEC_OFIC3', 
    'PERIODO_ID1', 
    'MES_ID1', 
    'DIA_ID1', 
    'ENT_FEC_RECIB', 
    'ENT_FEC_RECIB2', 
    'ENT_FEC_RECIB3',
    'PERIODO_ID2', 
    'MES_ID2', 
    'DIA_ID2',
    'ENT_FEC_RESP',
    'ENT_FEC_RESP2',
    'ENT_FEC_RESP3',
    'PERIODO_ID3',
    'MES_ID3',
    'DIA_ID3',
    'TEMA_ID',
    'ENT_ARC1',
    'ENT_ARC2',
    'ENT_ARC3',
    'ENT_OBS1',
    'ENT_OBS2',
    'ENT_STATUS1',
    'ENT_STATUS2',
    'ENT_STATUS3',    
    'FECHA_REG',
    'FECHA_REG2',
    'IP',
    'LOGIN',
    'FECHA_M',
    'FECHA_M2',
    'IP_M',
    'LOGIN_M'     
    ];
}
