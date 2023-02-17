<?php
//**************************************************************/
//* File:       estadisticanotainfController.php
//* Función:    Notas periodisticas de medios
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: enero 2023
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\filtronota1Request;

use App\regBitacoraModel;
use App\regTemaModel;
use App\regDiasModel;
use App\regMesesModel;
use App\regmediosModel;
use App\regTiponotaModel;
use App\regPeriodosModel;
use App\regNotamediosModel;

// Exportar a excel 
use App\Exports\ExportNotasperExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use GuzzleHttp\Client;
use Http;
//use Options;

class estadisticanotainfController extends Controller
{

  

    public function actionEditarNotaper($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');     
        $depen_id     = session()->get('depen_id');   

        $regtema      = regTemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_DESC','asc')
                        ->get(); 
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();    
        $regtiponota  = regTiponotaModel::select('TIPON_ID','TIPON_DESC')
                        ->where('TIPON_ID','<>',0)
                        ->get();    
        $regmedios    = regmediosModel::select('MEDIO_ID','MEDIO_DESC')
                        ->where('MEDIO_ID','<>',0)
                        ->get();                          
        $histPeriodos = regNotamediosModel::select('PERIODO_ID')
                        ->DISTINCT()
                        ->GET(); 
        //********* Validar rol de usuario **********************/
        //if(session()->get('rango') !== '0'){                          
        //    $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
        //                   ->orderBy('NOMBRE_COMPLETO','ASC')
        //                   ->get();                                                        
        //}else{
        //    $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
        //                   ->where('UADMON_ID',$depen_id)
        //                   ->get();                                  
        //}     
        $regnotamedio = regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                            'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                            'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                            'NM_STATUS1','NM_STATUS2','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where('NM_FOLIO',$id)
                        ->first();
        if($regnotamedio->count() <= 0){
            toastr()->error('No existe nota periodistica.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_periodisticas.editarNotaper',compact('nombre','usuario','regperiodos','regmeses','regdias','regtema','regtiponota','regmedios','histPeriodos','regnotamedio'));
    }
    
    // Gráfica de IAP por municipio
    public function IapxMpio(){
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

        $regtotxmpio=regdepenModel::join('JP_CAT_MUNICIPIOS_SEDESEM',[['JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','OFIPA_PERSONAL.MUNICIPIO_ID'],['OFIPA_PERSONAL.DEPEN_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regdepen=regdepenModel::join('JP_CAT_MUNICIPIOS_SEDESEM',[['JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','OFIPA_PERSONAL.MUNICIPIO_ID'],['OFIPA_PERSONAL.DEPEN_ID','<>',0]])
                      ->selectRaw('OFIPA_PERSONAL.MUNICIPIO_ID, JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('OFIPA_PERSONAL.MUNICIPIO_ID', 'JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('OFIPA_PERSONAL.MUNICIPIO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxmpio',compact('regdepen','regtotxmpio','nombre','usuario','rango'));
    }

    // Gráfica de IAP por Rubro social
    public function IapxRubro(){
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

        $regtotxrubro=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','OFIPA_PERSONAL.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regdepen=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','OFIPA_PERSONAL.UMEDIDA_ID')
                      ->selectRaw('OFIPA_PERSONAL.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('OFIPA_PERSONAL.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('OFIPA_PERSONAL.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxrubro',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }


    // Mapas
    public function Mapas(){
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

        $regtotxrubro=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','OFIPA_PERSONAL.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regdepen=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','OFIPA_PERSONAL.UMEDIDA_ID')
                      ->selectRaw('OFIPA_PERSONAL.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('OFIPA_PERSONAL.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('OFIPA_PERSONAL.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }


    // Filtro de estadistica de notas informativas
    public function actionNotafiltro1(){
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
        return view('sicinar.notas_periodisticas.filtroNota1',compact('nombre','usuario','regdias','regmeses','regperiodos'));
    }

    // Gráfica de Frecuencia de notas informativas
    public function actionEstadisticanota(Request $request){
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
        $regmedios    = regmediosModel::select('MEDIO_ID','MEDIO_DESC')
                        ->where('MEDIO_ID','<>',0)
                        ->get();      

        $periodo = $request->periodo_id1;
        $mes     = $request->mes_id1;
        $dia1    = $request->dia_id1;
        $dia2     = $request->dia_id2;

        //switch ($request->semaforo) {
        //case 1:   //Medios informativos
        //case 2:   //Tipo de nota
        //case 3:   //Calificación +, - y neutra
        if($request->tipo == 1){   // 1 = Medios informativos                                          
                 //$regnotamedio = regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                 //        'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                 //        'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                 //        'NM_STATUS1','NM_STATUS2','FECHA_M','FECHA_M2','IP_M','LOGIN_M')            
                 $regnotamediot= regNotamediosModel::selectRaw('COUNT(*) AS TOTAL')
                                 ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                                 ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                                 ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ])     
                                 ->get();                            
                 $regnotamediod= regNotamediosModel::select('MEDIO_ID','MEDIO_DESC')
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
                                 ->groupBy('MEDIO_ID'  ,'MEDIO_DESC')
                                 ->orderBy('MEDIO_DESC','asc')
                                 ->get();                                  
                return view('sicinar.numeralia.EstadisticaTipomedio',compact('nombre','usuario','rango','regnotamediot','regnotamediod','regmeses','periodo','mes','dia1','dia2'));
        }else{
            if($request->tipo == 2){       // 2 = Tipos de notas    
                 $regnotamediot= regNotamediosModel::selectRaw('COUNT(*) AS TOTAL')
                                 ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                                 ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                                 ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ])     
                                 ->get();                            
                 $regnotamediod= regNotamediosModel::select('TIPON_ID','TIPON_DESC')
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
                                 ->groupBy('TIPON_ID','TIPON_DESC')
                                 ->get();       

                return view('sicinar.numeralia.EstadisticaTiponota',compact('nombre','usuario','rango','regnotamediot','regnotamediod','regmeses','periodo','mes','dia1','dia2'));
            }else{
                if($request->tipo == 3){       // 3 = Calificación + -, neutra    
                    $regnotamediot= regNotamediosModel::selectRaw('COUNT(*) AS TOTAL')
                                    ->where([['PERIODO_ID1','>=',$request->periodo_id1],['PERIODO_ID1','<=',$request->periodo_id2] ])
                                    ->where([['MES_ID1'    ,'>=',$request->mes_id1]    ,['MES_ID1'    ,'<=',$request->mes_id2] ])
                                    ->where([['DIA_ID1'    ,'>=',$request->dia_id1]    ,['DIA_ID1'    ,'<=',$request->dia_id2] ])     
                                    ->get();                            
                    $regnotamediod= regNotamediosModel::select('NM_CALIF')
                                    ->selectRaw('SUM(CASE WHEN NM_CALIF = 1 THEN 1 END) AS CPOS')  
                                    ->selectRaw('SUM(CASE WHEN NM_CALIF = 2 THEN 1 END) AS CNEU')
                                    ->selectRaw('SUM(CASE WHEN NM_CALIF = 3 THEN 1 END) AS CNEG')                 
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
                                    ->groupBy('NM_CALIF')
                                    ->orderBy('NM_CALIF','asc')
                                    ->get();       
                    return view('sicinar.numeralia.EstadisticaNotacalif',compact('nombre','usuario','rango','regnotamediot','regnotamediod','regmeses','periodo','mes','dia1','dia2'));
                }  // if ($request->tipo) --- Termina Calif 3
            }  // if ($request->tipo) --- Termina tipo 2
        }      // if ($request->tipo) --- Termina tipo 1          
    }


}
