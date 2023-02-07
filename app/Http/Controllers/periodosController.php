<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Requested;
use App\RegRemisionModel;


class periodosController extends Controller
{
    
    public function showModelFilter(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');   
        $depen_id     = session()->get('depen_id');  

 

        if($rango !== '0'){
            $periodos = regRemisionModel::select('OFIPA_SALIDAS.PERIODO_ID')
            ->JOIN('OFIPA_PERSONAL', 'OFIPA_PERSONAL.FOLIO','=','OFIPA_SALIDAS.CVE_SP')
            ->DISTINCT()
            ->GET();
        }else{
            $periodos = regRemisionModel::select('OFIPA_SALIDAS.PERIODO_ID')
            ->JOIN('OFIPA_PERSONAL', 'OFIPA_PERSONAL.FOLIO','=','OFIPA_SALIDAS.CVE_SP')
            ->WHERE('OFIPA_SALIDAS.CVE_SP', $arbol_id)
            ->DISTINCT()
            ->GET();
        }

        return view('sicinar.remision_documentos.selectPeriodo', compact('nombre','usuario','periodos'));
        
    
    }

}
