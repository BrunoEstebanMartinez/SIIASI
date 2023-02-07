<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regusuariosModel extends Model
{
    protected $table = 'OFIPA_CTRL_ACCESO';
    protected  $primaryKey = ['LOGIN','PASSWORD'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
	    'PERIODO_ID',
        'CVE_PROGRAMA',
        'FOLIO',
        'DEPEN_ID',
        'LOGIN',
        'PASSWORD',
        'AP_PATERNO',
        'AP_MATERNO',
        'NOMBRES',
        'NOMBRE_COMPLETO',
        'FECHA_NACIMIENTO',
        'TELEFONO',
        'CVE_NIVEL',
        'CVE_MUNICIPIO',
        'CVE_ENTIDAD_FEDERATIVA',
        'CVE_ARBOL',
        'TIPO_USUARIO',
        'STATUS_1',
        'STATUS_2',
        'IP',
        'FECHA_REGISTRO',
        'UADMON_DESC'
    ];
 
    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('NOMBRE_COMPLETO', 'LIKE', "%$name%");
    }

    public function scopeIdd($query, $idd)
    {
        if($idd)
            return $query->where('CVE_ARBOL', '=', $idd);
    }
    public function scopeLogin($query, $login) 
    {
        if($login)
            return $query->where('LOGIN', 'LIKE', "%$login%");
    }

    public function scopeNameUAdmon($query, $nameuadmon)
    {
        if($nameuadmon) 
            return $query->where('UADMON_DESC', 'LIKE', "%$nameuadmon%");
    } 

}
 