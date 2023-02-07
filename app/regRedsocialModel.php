<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regRedsocialModel extends Model
{
    protected $table      = "IA_CAT_REDES_SOCIALES";
    protected $primaryKey = 'RS_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'RS_ID',
        'RS_DESC',
        'RS_LINK',
        'RS_FOTO1',
        'RS_FOTO2',
        'RS_OBS1',
        'RS_OBS2',
        'RS_STATUS',
        'FECREG'
    ];
}
