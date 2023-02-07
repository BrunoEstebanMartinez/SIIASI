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
<<<<<<< HEAD
		'MEDIO_DESC',
		'MEDIO_LINK',
		'MEDIO_FOTO1',
		'MEDIO_FOTO2',
		'MEDIO_OBS1',
		'MEDIO_OBS2',
		'MEDIO_STATUS',
		'FECREG'
=======
    'MEDIO_DESC',
    'MEDIO_LINK',
    'MEDIO_FOTO1',
    'MEDIO_FOTO2',
    'MEDIO_OBS1',
    'MEDIO_OBS2',
    'MEDIO_STATUS',
    'FECREG'
>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
    ];

    public static function ObtMedio($id){
        return (regmediosModel::select('MEDIO_DESC')
                                  ->where('MEDIO_ID','=',$id)
                                  ->get());
    }


}
