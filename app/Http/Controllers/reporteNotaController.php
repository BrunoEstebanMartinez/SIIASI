<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\regBitacoraModel;
use App\regTemaModel;
use App\regDiasModel;
use App\regMesesModel;
use App\regmediosModel;
use App\regTiponotaModel;
use App\regPeriodosModel;
use App\regNotamediosModel;

use PDF;
use Illuminate\Support\Facades\DB;

class reporteNotaController extends Controller
{

    public function viewMenuCriterio(){

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

        $regtemas     = regTemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get(); 
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();  
        $regtiponota  = regTiponotaModel::select('TIPON_ID','TIPON_DESC')
                        ->get();    
        $regmedios    = regmediosModel::select('MEDIO_ID','MEDIO_DESC')
                        ->get();                          
        $histPeriodos = regNotamediosModel::select('PERIODO_ID')
                        ->DISTINCT()
                        ->GET();
                        
        $regmedioFilter = regNotamediosModel::select('MEDIO_DESC') 
                        ->DISTINCT()
                        ->GET();  

       

        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            //$regpersonal =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //              ->get(); 
            $regnotamedio= regNotamediosModel::select('*')
                        ->orderBy('PERIODO_ID','DESC')
                        ->orderBy('NM_FOLIO'     ,'DESC')
                        ->paginate(40);
        }else{                  
            //$regpersonal = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //               ->where('UADMON_ID',$depen_id)
            //               ->get();                            
            $regnotamedio= regNotamediosModel::select('*')
                        //->where(  'UADMON_ID' ,$depen_id)            
                        ->orderBy('PERIODO_ID','DESC')
                        ->orderBy('NM_FOLIO'  ,'DESC')  
                        ->paginate(40);          
        }                        
        if($regnotamedio->count() <= 0){
            toastr()->error('No existen notas periodisticas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_periodisticas.menuReportes', compact('nombre','usuario','regperiodos','regtiponota','regtemas','histPeriodos','regmedios','regnotamedio', 'regmedioFilter', 'regdias', 'regmeses', 'regperiodos'));

}

    public function loadADownPDF(Request $request){

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
        $regtiponota  = regTiponotaModel::select('TIPON_ID','TIPON_DESC')
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
                

                    $filterR = regNotamediosModel::SELECT('NM_FEC_NOTA', 'MEDIO_DESC', 'TIPON_DESC', 'NM_TITULO', 'NM_NOTA', 'NM_LINK', 'NM_CALIF')
                    ->selectRaw('CASE WHEN NM_CALIF = 1 THEN 1 ELSE 0 END AS POSITIVO')
                    ->selectRaw('CASE WHEN NM_CALIF = 2 THEN 1 ELSE 0 END AS NEUTRO')
                    ->selectRaw('CASE WHEN NM_CALIF = 3 THEN 1 ELSE 0 END AS NEGATIVO')
                    ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                    ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                    ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ]) 
                    ->orderBy('NM_FEC_NOTA')
                    ->GET();  
                    
                    if($filterR->count() <= 0){
                        toastr()->error('No existen notas periodisticas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
                    }
            
                   
                        $pdfTesting = PDF::loadView('sicinar.pdf.perFilter', compact('nombre', 'usuario', 'filterR', 
                        'regdias', 'regmeses', 'regperiodos', 'id_dia1', 'id_mes1', 'id_periodo1', 'id_dia2', 'id_mes2', 'id_periodo2'));
                        
                        $pdfTesting->setPaper('letter', 'landscape');
            
                        return $pdfTesting->download('ReporteNota.pdf');
            }
            
            


            /*
            
            $filterR = DB::TABLE(DB::raw('NM_FEC_NOTA, MEDIO_DESC, TIPON_DESC, NM_TITULO, NM_NOTA, NM_LINK, SUM(Pos) POSITIVO, SUM(Neu) NEUTRO, SUM(Neg) NEGATIVO'))
                        ->selectRaw('IA_NOTAS_MEDIOS')
                        ->selectRaw(
                        DB::raw('DECODE(NM_CALIF, 1, 1, 0) Pos,')
                        DB::raw('DECODE(NM_CALIF, 2, 1, 0) Neu,')
                        DB::raw('DECODE(NM_CALIF, 3, 1, 0) Neg,'))
                        ->WHERE(DB::raw("NM_FEC_NOTA BETWEEN "."'$isConvFInit'"." AND "."'$isConvFFinal'"));

        }else{                  
                                   
            $filterR = DB::TABLE(DB::raw('NM_FEC_NOTA, MEDIO_DESC, TIPON_DESC, NM_TITULO, NM_NOTA, NM_LINK, SUM(Pos) POSITIVO, SUM(Neu) NEUTRO, SUM(Neg) NEGATIVO'))
            ->selectRaw('IA_NOTAS_MEDIOS')
            ->selectRaw(DB::raw('
            DECODE(NM_CALIF, 1, 1, 0) Pos,
            DECODE(NM_CALIF, 2, 1, 0) Neu,
            DECODE(NM_CALIF, 3, 1, 0) Neg'))
            ->WHERE(DB::raw("NM_FEC_NOTA BETWEEN "."'$isConvFInit'"." AND "."'$isConvFFinal'"));

            */
            
        }                        
       


    }
}
