<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regmediosModel extends Model
{
    protected $table = "IA_CAT_MEDIOS";
    protected  $primaryKey = 'MEDIO_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'MEDIO_ID',
		'MEDIO_DESC,'
		'MEDIO_LINK,'
		'MEDIO_FOTO1,'
		'MEDIO_FOTO2,'
		'MEDIO_OBS1,'
		'MEDIO_OBS2,'
		'MEDIO_STATUS',
		'FECREG'
    ];

    public static function Unidades($id){
        return dependenciasModel::select('DEPEN_ID','DEPEN_DESC')
                                  ->where('DEPEN_ID','like','%211C04%')
        						  ->where('ESTRUCGOB_ID','like','%'.$id.'%')
                                  ->orderBy('DEPEN_ID','asc')
                                  ->get();
    }
}
