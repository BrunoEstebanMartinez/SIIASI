<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regPersonalModel extends Model
{
    protected $table      = "OFIPA_PERSONAL";
    protected $primaryKey = ['FOLIO','UADMON_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'FOLIO',
        'UADMON_ID',
        'PRIMER_APELLIDO',
        'SEGUNDO_APELLIDO',
        'NOMBRES',
        'NOMBRE_COMPLETO',
        'CURP',
        'FECHA_INGRESO',
        'FECHA_INGRESO2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'FECHA_NACIMIENTO',
        'FECHA_NACIMIENTO2',
        'PERIODO_ID2',
        'MES_ID2',
        'DIA_ID2',
        'SEXO',
        'RFC',
        'ID_OFICIAL',
        'DOMICILIO',
        'COLONIA',
        'CP',
        'ENTRE_CALLE',
        'Y_CALLE',
        'OTRA_REFERENCIA',
        'TELEFONO',
        'CELULAR',
        'E_MAIL',
        'ENTIDAD_NAC_ID',
        'ENTIDAD_FED_ID',
        'MUNICIPIO_ID',
        'LOCALIDAD_ID',
        'LOCALIDAD',
        'EDO_CIVIL_ID',
        'GRADO_ESTUDIOS_ID',
        'PUESTO',
        'TIPOEMP_ID',
        'CLASEEMP_ID',
        'SUELDO_MENSUAL',
        'OBS_1',
        'OBS_2',
        'STATUS_1',
        'STATUS_2',
        'FECHA_REG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    public static function Obtsp($id){
        return (regPersonalModel::select('UADMON_ID','NOMBRE_COMPLETO')->where('FOLIO','=',$id)
                                  ->get());
    }

    public static function obtMunicipios($id){
        return regPadronModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID','MUNICIPIONOMBRE')
                               ->where('ENTIDADFEDERATIVAID','=', $id)
                               ->orderBy('MUNICIPIOID','asc')
                               ->get();
    }

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('NOMBRE_COMPLETO', 'LIKE', "%$name%");
    }

    public function scopeEmail($query, $email)
    {
        if($email)
            return $query->where('IAP_EMAIL', 'LIKE', "%$email%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('IAP_OBJSOC', 'LIKE', "%$bio%");
    } 
    public function scopeNameUAdmon($query, $nameuadmon)
    {
        if($nameuadmon) 
            return $query->where('UADMON_DESC', 'LIKE', "%$nameuadmon%");
    }      


}
