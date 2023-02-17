<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\regBitacoraModel;
use App\regTemaModel;
use App\regDiasModel;
use App\regMesesModel;
use App\regNotaredsocialModel;
use App\regPeriodosModel;
use App\regRedsocialModel;

use PDF;
use Illuminate\Support\Facades\DB;

class reporteRedController extends Controller
{
    public function viewMenuCriterioRedes(){

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

      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();  
        $regtipores  = regRedsocialModel::select('RS_ID','RS_DESC')
                        ->get();    
                               
                        
        $regmedioFilter = regNotaredsocialModel::select('RS_DESC') 
                        ->DISTINCT()
                        ->GET();  

       

        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            //$regpersonal =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //              ->get(); 
            $regnotamedio= regNotaredsocialModel::select('*')
            //->where(  'UADMON_ID' ,$depen_id)            
            ->orderBy('PERIODO_ID','DESC')
            ->orderBy('RS_FOLIO'  ,'DESC')  
            ->paginate(40); 
        }else{                  
            //$regpersonal = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //               ->where('UADMON_ID',$depen_id)
            //               ->get();                            
            $regnotamedio= regNotaredsocialModel::select('*')
                        //->where(  'UADMON_ID' ,$depen_id)            
                        ->orderBy('PERIODO_ID','DESC')
                        ->orderBy('RS_FOLIO'  ,'DESC')  
                        ->paginate(40);          
        }                        
        if($regnotamedio->count() <= 0){
            toastr()->error('No existen notas en redes sociales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_redes_sociales.menuReportesR', compact('nombre','usuario','regperiodos','regtipores',
        'regtemas','histPeriodos','regmedios','regnotamedio', 'regmedioFilter', 'regdias', 'regmeses', 'regperiodos'));

}

    public function loadADownPDFRedes(Request $request){

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
        
        
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                ->get();  
         
   


        if(session()->get('rango') !== '0'){  


            /*
            $filterR = regNotamediosModel::SELECT('NM_FEC_NOTA', 'MEDIO_DESC', 'NM_TITULO', 'NM_NOTA', 'NM_LINK')
            ->WHERE('NM_CALIF', '1')
            ->GET();

            */

            $option = $request -> get('tipoR');


            if($option == '1'){

                    $id_dia1 = $request -> get('dia_id1');
                    $id_mes1 = $request -> get('mes_id1');
                    $id_periodo1 = $request -> get('periodo_id1');

                    $id_dia2 = $request -> get('dia_id2');
                    $id_mes2 = $request -> get('mes_id2');
                    $id_periodo2 = $request -> get('periodo_id2');
                    
                    $isConvFInit = date('d/m/y',  strtotime(trim($id_dia1."/".$id_mes1."/".$id_periodo2)));
                    $isConvFFinal = date('d/m/y', strtotime(trim($id_dia2."/".$id_mes2."/".$id_periodo2)));

                
                    $isConvInit = date('d/m/y', strtotime(trim($request->datepickerInit)));
                    $isConvFinal = date('d/m/y', strtotime(trim($request->datepickerFin)));
                

                    $filterR = regNotaredsocialModel::SELECT('RS_FEC_NOTA', 'RS_DESC', 'RS_NOTA', 'RS_LIKES', 'RS_REPOSTEOS', 'RS_COMEN', 'RS_ALCANCE', 'RS_CALIF')
                    ->selectRaw('CASE WHEN RS_CALIF = 1 THEN 1 ELSE 0 END AS POSITIVO')
                    ->selectRaw('CASE WHEN RS_CALIF = 2 THEN 1 ELSE 0 END AS NEUTRO')
                    ->selectRaw('CASE WHEN RS_CALIF = 3 THEN 1 ELSE 0 END AS NEGATIVO')
                    ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                    ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                    ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ]) 
                    ->orderBy('RS_FEC_NOTA')
                    ->GET();  
                    
                    if($filterR->count() <= 0){
                        toastr()->error('No existen notas periodisticas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
                    }
            
                   
                        $pdfTesting = PDF::loadView('sicinar.pdf.perFilterRed', compact('nombre', 'usuario', 'filterR', 
                        'regdias', 'regmeses', 'regperiodos', 'id_dia1', 'id_mes1', 'id_periodo1', 'id_dia2', 'id_mes2', 'id_periodo2'));
                        
                        $pdfTesting->setPaper('letter', 'landscape');
            
                        return $pdfTesting->download('ReporteRedSocial.pdf');
            }
            
            


        }   
    }
}
    
