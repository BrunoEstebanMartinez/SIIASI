<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regUAdmonModel extends Model
{
    protected $table      = "OFIPA_CAT_UADMON";
    protected $primaryKey = 'UADMON_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'UADMON_ID',        
        'UADMON_DESC',
        'UADMON_STATUS', //S ACTIVO      N INACTIVO
        'UADMON_FECREG'
    ];

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