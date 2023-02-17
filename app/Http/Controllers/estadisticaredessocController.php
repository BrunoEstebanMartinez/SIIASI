<?php
//**************************************************************/
//* File:       estadisticaredessocController.php
//* Función:    redes sociales
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: enero 2023
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\filtroredes1Request;

use App\regBitacoraModel;
use App\regTemaModel;
use App\regDiasModel;
use App\regMesesModel;
use App\regRedSocialModel;
use App\regTiponotaModel;
use App\regPeriodosModel;
use App\regNotaredsocialModel;

// Exportar a excel 
use App\Exports\ExportNotasperExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use GuzzleHttp\Client;
use Http;
//use Options;

class estadisticaredessocController extends Controller
{


    // Filtro de estadistica de redes
    public function actionRedesfiltro1(){
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

        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();   
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();   
        $regperiodos  = regPeriodosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->orderBy('PERIODO_ID','asc') 
                        ->get();        
        
        if($regperiodos->count() <= 0){
            toastr()->error('No existen periodos fiscales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_redes_sociales.filtroRedes1',compact('nombre','usuario','regdias','regmeses','regperiodos'));
    }

    // Gráfica de Frecuencia de redes sociales
    public function actionEstadisticaredes(Request $request){
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
                        ->where('TIPON_ID','<>',0)
                        ->get();    
        $regredes     = regRedSocialModel::select('RS_ID','RS_DESC')
                        ->get();  

        $periodo = $request->periodo_id1;
        $mes     = $request->mes_id1;
        $dia1    = $request->dia_id1;
        $dia2     = $request->dia_id2;

        //switch ($request->semaforo) {
        //case 1:   //Medios informativos
        //case 2:   //Tipo de nota
        //dd('dia:'.$dia);
        //case 3:   //Calificación +, - y neutra
        if($request->tipo == 1){   // 1 = Medios informativos                                          
                 //$regnotamedio = regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                 //        'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                 //        'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                 //        'NM_STATUS1','NM_STATUS2','FECHA_M','FECHA_M2','IP_M','LOGIN_M')            
                 $regnotaredest= regNotaredsocialModel::selectRaw('COUNT(*) AS TOTAL')
                                 ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                                 ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                                 ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ])     
                                 ->get();                            
                 $regnotaredesd= regNotaredsocialModel::select('RS_ID','RS_DESC')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 1 THEN 1 END) AS M01')  
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 2 THEN 1 END) AS M02')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 3 THEN 1 END) AS M03')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 4 THEN 1 END) AS M04')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 5 THEN 1 END) AS M05')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 6 THEN 1 END) AS M06')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 7 THEN 1 END) AS M07')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 8 THEN 1 END) AS M08')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 9 THEN 1 END) AS M09')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 =10 THEN 1 END) AS M10')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 =11 THEN 1 END) AS M11')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 =12 THEN 1 END) AS M12')                    
                                 ->selectRaw('COUNT(*) AS TOTAL')
                                 ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                                 ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                                 ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ])     
                                 ->groupBy('RS_ID'  ,'RS_DESC')
                                 ->orderBy('RS_DESC','asc')
                                 ->get();                                  
                return view('sicinar.numeralia.EstadisticaTipored',compact('nombre','usuario','rango','regnotaredest','regnotaredesd','regmeses','periodo','mes','dia1','dia2'));
        }else{
            if($request->tipo == 2){       // 2 = Calificación + -, neutra    
                 $regnotaredest= regNotaredsocialModel::selectRaw('COUNT(*) AS TOTAL')
                                 ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                                 ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                                 ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ])     
                                 ->get();                            
                 $regnotaredesd= regNotaredsocialModel::select('RS_CALIF')
                                 ->selectRaw('SUM(CASE WHEN RS_CALIF = 1 THEN 1 END) AS CPOS')  
                                 ->selectRaw('SUM(CASE WHEN RS_CALIF = 2 THEN 1 END) AS CNEU')
                                 ->selectRaw('SUM(CASE WHEN RS_CALIF = 3 THEN 1 END) AS CNEG')                 
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 1 THEN 1 END) AS M01')  
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 2 THEN 1 END) AS M02')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 3 THEN 1 END) AS M03')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 4 THEN 1 END) AS M04')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 5 THEN 1 END) AS M05')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 6 THEN 1 END) AS M06')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 7 THEN 1 END) AS M07')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 8 THEN 1 END) AS M08')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 = 9 THEN 1 END) AS M09')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 =10 THEN 1 END) AS M10')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 =11 THEN 1 END) AS M11')
                                 ->selectRaw('SUM(CASE WHEN MES_ID1 =12 THEN 1 END) AS M12')  
                                 //->selectRaw('SUM(CASE WHEN NM_CALIF = 1 THEN "POSITIVO" ELSE NM_CALIF = 2 "NEUTRO" ELSE NM_CALIF = 3 "NEGATIVO" END) AS CALIF_DESC')                  
                                 //->selectRaw('CASE NM_CALIF WHEN 1 "POSITIVO" WHEN 2 "NEUTRO" WHEN 3 "NEGATIVO" END AS CALIF_DESC')                  
                                 ->selectRaw('COUNT(*) AS TOTAL')
                                 ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                                 ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                                 ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ])                             
                                 ->groupBy('RS_CALIF')
                                 ->orderBy('RS_CALIF','asc')
                                 ->get();       
                return view('sicinar.numeralia.EstadisticaRedcalif',compact('nombre','usuario','rango','regnotaredest','regnotaredesd','regmeses','periodo','mes','dia1','dia2'));                
            }  // if ($request->tipo) --- Termina tipo 2
        }      // if ($request->tipo) --- Termina tipo 1          
    }


}
