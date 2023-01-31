<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\progdilRequest;
use App\Http\Requests\filtro01Request;

use App\regBitacoraModel;
use App\regdepenModel;
use App\regUmedidaModel;
use App\regPeriodosModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regAniosModel;
use App\regEPprogramaModel;
use App\regEPproyectoModel;
use App\regProgeAnualModel;
use App\regProgdAnualModel;

// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelExportAvancespa01;
use App\Exports\BladeExport;
// Exportar a pdf
use PDF;
//use Options;

class reportespaController extends Controller
{

    public function actionReporteProgvisitas(){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->get();       
        $regprogdil   = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','OSC_ID',
                                             'MUNICIPIO_ID','ENTIDAD_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_AUDITOR2','VISITA_AUDITOR4',
                                             'VISITA_TIPO1','VISITA_FECREGP','VISITA_FECREGP2','VISITA_EDO','VISITA_OBS2','VISITA_OBS3',
                                             'FECREG','FECREG2','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('MES_ID','ASC')
                        ->orderBy('DIA_ID','ASC')
                        ->orderBY('HORA_ID','ASC') 
                        ->first(); 
        if($regprogdil->count() <= 0){
            toastr()->error('No existen registros de visitas programadas en la agenda.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoProgdil');
        }
        return view('sicinar.agenda.reporteProgramavisitas',compact('nombre','usuario','regmeses','reghoras','regdias','regosc','regperiodos','regprogdil','regentidades','regmunicipio'));

    }

    // exportar a formato PDF
    public function actionProgramavisitasPdf(filtro01Request $request){
        //dd('tipo 2:',$request->visita_tipo2);
        //if($request->visita_tipo2 == 'E'){
            //dd('hola ya entre......');  
            //return redirect()->route('programavisitasExcel',[$request->periodo_id, $request->mes_id, $request->visita_tipo1]);
            //return view('programavisitasExcel',[$request->periodo_id, $request->mes_id, $request->visita_tipo1]);
        //}else{
 
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                        ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_REGCONS','OSC_RFC','OSC_STATUS')->get();       
        $regprogdil   = regAgendaModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_AGENDA.OSC_ID')
                        ->select('PE_OSC.OSC_DESC','PE_OSC.OSC_REGCONS',
                        'PE_AGENDA.VISITA_FOLIO',
                        'PE_AGENDA.PERIODO_ID','PE_AGENDA.MES_ID','PE_AGENDA.DIA_ID','PE_AGENDA.HORA_ID',
                        'PE_AGENDA.OSC_ID','PE_AGENDA.MUNICIPIO_ID','PE_AGENDA.VISITA_CONTACTO',
                        'PE_AGENDA.VISITA_TEL','PE_AGENDA.VISITA_EMAIL','PE_AGENDA.VISITA_DOM',
                        'PE_AGENDA.VISITA_OBJ','PE_AGENDA.VISITA_SPUB','PE_AGENDA.VISITA_SPUB2',
                        'PE_AGENDA.VISITA_AUDITOR2','PE_AGENDA.VISITA_AUDITOR4','PE_AGENDA.VISITA_TIPO1',
                        'PE_AGENDA.VISITA_FECREGP','PE_AGENDA.VISITA_FECREGP2','PE_AGENDA.VISITA_EDO',
                        'PE_AGENDA.VISITA_OBS2','PE_AGENDA.VISITA_OBS3')
                        ->where( ['PE_AGENDA.PERIODO_ID'   => $request->periodo_id, 
                                  'PE_AGENDA.MES_ID'       => $request->mes_id
                                 ])
                        ->orderBy('PE_AGENDA.PERIODO_ID'  ,'ASC')
                        ->orderBy('PE_AGENDA.VISITA_FOLIO','ASC')
                        ->get();
        if($regprogdil->count() <= 0){
            toastr()->error('No existe visitas proramadas en la agenda.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }else{
            /************ Bitacora inicia *************************************/ 
            $id           =        1;
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3006;
            $xtrx_id      =       184;     // pdf
            $id           =       $id;
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                                                    'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 
                                                    'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id;             // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                        'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                        'IP_M'    => $regbitacora->IP       = $ip,
                                        'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                        'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/ 

            /**************** Genera reporte pdf **********************/            
            $pdf = PDF::loadView('sicinar.pdf.programavisitasPdf', compact('nombre','usuario','regmeses','reghoras','regdias','regperiodos','regosc','regprogdil','regentidades','regmunicipio'));
            //$options = new Options();
            //$options->set('defaultFont', 'Courier');
            //$pdf->set_option('defaultFont', 'Courier');
            //******** Horizontal ***************
            $pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');
            //$pdf->set_options('isPhpEnabled', true);
            //$pdf->setOptions(['isPhpEnabled' => true]);
            //******** vertical ***************          
            //$pdf->setPaper('A4','portrait');

            // Output the generated PDF to Browser
            return $pdf->stream('ProgramaDeVisitas');
        }   //*************** Genera reporte en formato PDF **************************//

        //}   //******** Termina la selección del tipo de reporte *********************//
    }

    //************************* Reporte excel **********************//
    public function actionRepExcelq01(){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();   
        //$regdias    = regDiasModel::select('DIA_ID','DIA_DESC')
        //              ->get();   
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','ASC')
                        ->get();        
        $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where(  'DEPEN_STATUS','1')
                        ->orderBy('DEPEN_DESC'  ,'ASC')
                        ->get();                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();                                    
        $regprogdanual=regProgdAnualModel::join('SIEVAD_CAT_UMEDIDA' ,'SIEVAD_CAT_UMEDIDA.UMED_ID','=',
                                                                      'SIEVAD_DPA.UMED_ID')
                                         ->join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                          'SIEVAD_DPA.DEPEN_ID2')
                       ->select(    'SIEVAD_DPA.FOLIO', 
                                    'SIEVAD_DPA.PARTIDA', 
                                    'SIEVAD_DPA.CIPREP_ID',
                                    'SIEVAD_DPA.LGOB_COD',
                                    'SIEVAD_DPA.DEPEN_ID1',
                                    'SIEVAD_DPA.DEPEN_ID2',
                                    'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC', 
                                    'SIEVAD_DPA.MES_ID2', 
                                    'SIEVAD_DPA.FECHA_ENTREGA', 
                                    'SIEVAD_DPA.FECHA_ENTREGA2', 
                                    'SIEVAD_DPA.FECHA_ENTREGA3', 
                                    'SIEVAD_DPA.ACTIVIDAD_DESC', 
                                    'SIEVAD_DPA.OBJETIVO_DESC', 
                                    'SIEVAD_DPA.OPERACIONAL_DESC', 
                                    'SIEVAD_DPA.SOPORTE_01',
                                    'SIEVAD_DPA.OBS_01',
                                    
                                    'SIEVAD_DPA.UMED_ID', 
                                    'SIEVAD_CAT_UMEDIDA.UMED_DESC', 
                                    'SIEVAD_DPA.MESP_01', 'SIEVAD_DPA.MESP_02', 'SIEVAD_DPA.MESP_03', 
                                    'SIEVAD_DPA.MESP_04', 'SIEVAD_DPA.MESP_05', 'SIEVAD_DPA.MESP_06', 
                                    'SIEVAD_DPA.MESP_07', 'SIEVAD_DPA.MESP_08', 'SIEVAD_DPA.MESP_09', 
                                    'SIEVAD_DPA.MESP_10', 'SIEVAD_DPA.MESP_11', 'SIEVAD_DPA.MESP_12',
                                    'SIEVAD_DPA.TRIMP_01','SIEVAD_DPA.TRIMP_02','SIEVAD_DPA.TRIMP_03',
                                    'SIEVAD_DPA.TRIMP_04',
                                    'SIEVAD_DPA.TRIMC_01','SIEVAD_DPA.TRIMC_02','SIEVAD_DPA.TRIMC_03',
                                    'SIEVAD_DPA.TRIMC_04',
                                    'SIEVAD_DPA.TSEMP_01','SIEVAD_DPA.TSEMP_02','SIEVAD_DPA.TSEMC_01',
                                    'SIEVAD_DPA.TSEMC_02',
                                    'SIEVAD_DPA.TOTP_01' ,'SIEVAD_DPA.TOTP_02' ,'SIEVAD_DPA.TOTC_01' ,
                                    'SIEVAD_DPA.TOTC_02',
                                    'SIEVAD_DPA.MES_P01','SIEVAD_DPA.MES_P02','SIEVAD_DPA.MES_P03','SIEVAD_DPA.MES_P04','SIEVAD_DPA.MES_P05','SIEVAD_DPA.MES_P06',
                                    'SIEVAD_DPA.MES_P07','SIEVAD_DPA.MES_P08','SIEVAD_DPA.MES_P09','SIEVAD_DPA.MES_P10','SIEVAD_DPA.MES_P11','SIEVAD_DPA.MES_P12',
                                    'SIEVAD_DPA.TRIM_P01','SIEVAD_DPA.TRIM_P02','SIEVAD_DPA.TRIM_P03','SIEVAD_DPA.TRIM_P04','SIEVAD_DPA.SEM_P01','SIEVAD_DPA.SEM_P02','SIEVAD_DPA.TOT_P01',
                                    'SIEVAD_DPA.SEMAF_01','SIEVAD_DPA.SEMAF_02','SIEVAD_DPA.SEMAF_03','SIEVAD_DPA.SEMAF_04','SIEVAD_DPA.SEMAF_05','SIEVAD_DPA.SEMAF_06',
                                    'SIEVAD_DPA.SEMAF_07','SIEVAD_DPA.SEMAF_08','SIEVAD_DPA.SEMAF_09','SIEVAD_DPA.SEMAF_10','SIEVAD_DPA.SEMAF_11','SIEVAD_DPA.SEMAF_12',
                                    'SIEVAD_DPA.SEMAFT_01','SIEVAD_DPA.SEMAFT_02','SIEVAD_DPA.SEMAFT_03','SIEVAD_DPA.SEMAFT_04','SIEVAD_DPA.SEMAFS_01','SIEVAD_DPA.SEMAFS_02','SIEVAD_DPA.SEMAFA_01'
                               )
                       ->selectRaw('(SIEVAD_DPA.MESP_01+SIEVAD_DPA.MESP_02+SIEVAD_DPA.MESP_03+
                                     SIEVAD_DPA.MESP_04+SIEVAD_DPA.MESP_05+SIEVAD_DPA.MESP_06+
                                     SIEVAD_DPA.MESP_07+SIEVAD_DPA.MESP_08+SIEVAD_DPA.MESP_09+
                                     SIEVAD_DPA.MESP_10+SIEVAD_DPA.MESP_11+SIEVAD_DPA.MESP_12)
                                     META_PROGRAMADA')
                       ->selectRaw('(SIEVAD_DPA.MESC_01+SIEVAD_DPA.MESC_02+SIEVAD_DPA.MESC_03+
                                     SIEVAD_DPA.MESC_04+SIEVAD_DPA.MESC_05+SIEVAD_DPA.MESC_06+
                                     SIEVAD_DPA.MESC_07+SIEVAD_DPA.MESC_08+SIEVAD_DPA.MESC_09+
                                     SIEVAD_DPA.MESC_10+SIEVAD_DPA.MESC_11+SIEVAD_DPA.MESC_12)
                                     META_REALIZADA')                       
                       ->orderBy(   'SIEVAD_DPA.PERIODO_ID','ASC')                   
                       ->orderBy(   'SIEVAD_DPA.FOLIO'     ,'ASC')
                       ->orderBy(   'SIEVAD_DPA.PARTIDA'   ,'ASC')
                       ->get(); 
                       //->toArray();
                       //->first();
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros del programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.reportes.reporteExcel01',compact('nombre','usuario','regmeses','regdepen','regperiodos','regprogdanual'));
    }

     //Exportar a formato excel
    public function actionRepExcel01(filtro01Request $request){
        //dd('id..............',$id,'-id1:',$id1,'-id2:',$id2);
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();   
        //$regdias    = regDiasModel::select('DIA_ID','DIA_DESC')
        //              ->get();   
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->orderBy('PERIODO_ID','ASC')
                        ->get();        
        $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where(  'DEPEN_STATUS','1')
                        ->orderBy('DEPEN_DESC'  ,'ASC')
                        ->get();                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();                                    
        $regprogdanual=regProgdAnualModel::join('SIEVAD_CAT_UMEDIDA' ,'SIEVAD_CAT_UMEDIDA.UMED_ID','=',
                                                                      'SIEVAD_DPA.UMED_ID')
                                         ->join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                          'SIEVAD_DPA.DEPEN_ID2')
                       ->select(    'SIEVAD_DPA.FOLIO', 
                                    'SIEVAD_DPA.PARTIDA', 
                                    'SIEVAD_DPA.CIPREP_ID',
                                    'SIEVAD_DPA.LGOB_COD',
                                    'SIEVAD_DPA.DEPEN_ID1',
                                    'SIEVAD_DPA.DEPEN_ID2',
                                    'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC', 
                                    'SIEVAD_DPA.MES_ID2', 
                                    'SIEVAD_DPA.FECHA_ENTREGA', 
                                    'SIEVAD_DPA.FECHA_ENTREGA2', 
                                    'SIEVAD_DPA.FECHA_ENTREGA3', 
                                    'SIEVAD_DPA.ACTIVIDAD_DESC', 
                                    'SIEVAD_DPA.OBJETIVO_DESC', 
                                    'SIEVAD_DPA.OPERACIONAL_DESC', 
                                    'SIEVAD_DPA.SOPORTE_01',
                                    'SIEVAD_DPA.OBS_01',
                                    
                                    'SIEVAD_DPA.UMED_ID', 
                                    'SIEVAD_CAT_UMEDIDA.UMED_DESC', 
                                    'SIEVAD_DPA.MESP_01', 'SIEVAD_DPA.MESP_02', 'SIEVAD_DPA.MESP_03', 
                                    'SIEVAD_DPA.MESP_04', 'SIEVAD_DPA.MESP_05', 'SIEVAD_DPA.MESP_06', 
                                    'SIEVAD_DPA.MESP_07', 'SIEVAD_DPA.MESP_08', 'SIEVAD_DPA.MESP_09', 
                                    'SIEVAD_DPA.MESP_10', 'SIEVAD_DPA.MESP_11', 'SIEVAD_DPA.MESP_12',
                                    'SIEVAD_DPA.TRIMP_01','SIEVAD_DPA.TRIMP_02','SIEVAD_DPA.TRIMP_03',
                                    'SIEVAD_DPA.TRIMP_04',
                                    'SIEVAD_DPA.TRIMC_01','SIEVAD_DPA.TRIMC_02','SIEVAD_DPA.TRIMC_03',
                                    'SIEVAD_DPA.TRIMC_04',
                                    'SIEVAD_DPA.TSEMP_01','SIEVAD_DPA.TSEMP_02','SIEVAD_DPA.TSEMC_01',
                                    'SIEVAD_DPA.TSEMC_02',
                                    'SIEVAD_DPA.TOTP_01' ,'SIEVAD_DPA.TOTP_02' ,'SIEVAD_DPA.TOTC_01' ,
                                    'SIEVAD_DPA.TOTC_02',
                                    'SIEVAD_DPA.MES_P01','SIEVAD_DPA.MES_P02','SIEVAD_DPA.MES_P03','SIEVAD_DPA.MES_P04','SIEVAD_DPA.MES_P05','SIEVAD_DPA.MES_P06',
                                    'SIEVAD_DPA.MES_P07','SIEVAD_DPA.MES_P08','SIEVAD_DPA.MES_P09','SIEVAD_DPA.MES_P10','SIEVAD_DPA.MES_P11','SIEVAD_DPA.MES_P12',
                                    'SIEVAD_DPA.TRIM_P01','SIEVAD_DPA.TRIM_P02','SIEVAD_DPA.TRIM_P03','SIEVAD_DPA.TRIM_P04','SIEVAD_DPA.SEM_P01','SIEVAD_DPA.SEM_P02','SIEVAD_DPA.TOT_P01',
                                    'SIEVAD_DPA.SEMAF_01','SIEVAD_DPA.SEMAF_02','SIEVAD_DPA.SEMAF_03','SIEVAD_DPA.SEMAF_04','SIEVAD_DPA.SEMAF_05','SIEVAD_DPA.SEMAF_06',
                                    'SIEVAD_DPA.SEMAF_07','SIEVAD_DPA.SEMAF_08','SIEVAD_DPA.SEMAF_09','SIEVAD_DPA.SEMAF_10','SIEVAD_DPA.SEMAF_11','SIEVAD_DPA.SEMAF_12',
                                    'SIEVAD_DPA.SEMAFT_01','SIEVAD_DPA.SEMAFT_02','SIEVAD_DPA.SEMAFT_03','SIEVAD_DPA.SEMAFT_04','SIEVAD_DPA.SEMAFS_01','SIEVAD_DPA.SEMAFS_02','SIEVAD_DPA.SEMAFA_01'
                               )
                       ->selectRaw('(SIEVAD_DPA.MESP_01+SIEVAD_DPA.MESP_02+SIEVAD_DPA.MESP_03+
                                     SIEVAD_DPA.MESP_04+SIEVAD_DPA.MESP_05+SIEVAD_DPA.MESP_06+
                                     SIEVAD_DPA.MESP_07+SIEVAD_DPA.MESP_08+SIEVAD_DPA.MESP_09+
                                     SIEVAD_DPA.MESP_10+SIEVAD_DPA.MESP_11+SIEVAD_DPA.MESP_12)
                                     META_PROGRAMADA')
                       ->selectRaw('(SIEVAD_DPA.MESC_01+SIEVAD_DPA.MESC_02+SIEVAD_DPA.MESC_03+
                                     SIEVAD_DPA.MESC_04+SIEVAD_DPA.MESC_05+SIEVAD_DPA.MESC_06+
                                     SIEVAD_DPA.MESC_07+SIEVAD_DPA.MESC_08+SIEVAD_DPA.MESC_09+
                                     SIEVAD_DPA.MESC_10+SIEVAD_DPA.MESC_11+SIEVAD_DPA.MESC_12)
                                     META_REALIZADA')                       
                       ->orderBy(   'SIEVAD_DPA.PERIODO_ID','ASC')                   
                       ->orderBy(   'SIEVAD_DPA.FOLIO'     ,'ASC')
                       ->orderBy(   'SIEVAD_DPA.PARTIDA'   ,'ASC')
                       ->get(); 
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $periodo_id = $request->get('periodo_id');   
        $mes_id     = $request->get('mes_id');  
        $tipo       = $request->get('tipo');      //  tipo P-Pantalla, E-Excel, D-PDF
        $depen_id   = $request->get('depen_id');     
        $clase      = $request->get('clase');     // clase de reporte C-concentrado, D-Detallado

        //*************************************************************************//
        // Generar reportes concentrados C-Concentrado, D-Detallado          ******//
        //*************************************************************************//        
        if($request->clase == 'C'){
            //*************************************************************************//
            // Enviar reporte a pantalla, excel o PDF                            ******//
            //*************************************************************************//             
            switch ($request->tipo) {               
            case 'P':    //Por pantalla concentrado
            break;
            case 'E':    //Formato excel concentrado  
            break;
            }            //Switch ($request->tipo) - Fin tipo de reporte pantalla, excel o PDF   
        }else{           // Generar reportes D-detallados                            ****//
            //*************************************************************************//
            // Enviar reporte a pantalla, excel o PDF                            ******//
            //*************************************************************************//                 
            switch ($request->tipo) {
            case 'P':    //Por pantalla detallado
                 break;
            case 'E':    //Formato excel detallado
                //dd('periodo:'.$request->periodo_id,' Mes:'.$request->mes_id,' Tipo:'.$request->clase,' reporte a:'.$request->tipo,' depen:'.$request->depen_id);
                if( !empty($request->depen_id) ){
                //if( is_null($request->depen_id) ){   
                    //if($request->semaforo == 3 ){                                                              
                    $totregs=regProgdAnualModel::join('SIEVAD_CAT_UMEDIDA'     ,'SIEVAD_CAT_UMEDIDA.UMED_ID','=',
                                                                                'SIEVAD_DPA.UMED_ID')
                                               ->join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                                'SIEVAD_DPA.DEPEN_ID2')                   
                          ->selectRaw('COUNT(*) AS REGISTROS')
                          ->Where(  'SIEVAD_DPA.PERIODO_ID'    ,'=',$request->periodo_id) 
                          //->Where('SIEVAD_DPA.MES_ID'        ,'=',$request->mess)
                          ->Where(  'SIEVAD_DPA.DEPEN_ID2'     ,'=',$request->depen_id)
                          //->Where(  'SIEVAD_DPA.RECIBO_STATUS2','=',$request->semaforo)    
                          ->orderBy('SIEVAD_DPA.PERIODO_ID','ASC')                   
                          ->orderBy('SIEVAD_DPA.FOLIO'     ,'ASC')
                          ->orderBy('SIEVAD_DPA.PARTIDA'   ,'ASC')                          
                          ->get();    
                    //}
                    //dd('1........'.$totregs->count(), $totregs);
                }else{ 
                    //if($request->semaforo == 3 ){                                                              
                    $totregs=regProgdAnualModel::join('SIEVAD_CAT_UMEDIDA'     ,'SIEVAD_CAT_UMEDIDA.UMED_ID','=',
                                                                                'SIEVAD_DPA.UMED_ID')
                                               ->join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                                'SIEVAD_DPA.DEPEN_ID2')                   
                          ->selectRaw('COUNT(*) AS REGISTROS')
                          ->Where(  'SIEVAD_DPA.PERIODO_ID'    ,'=',$request->periodo_id) 
                          //->Where('SIEVAD_DPA.MES_ID'        ,'=',$request->mess)
                          //->Where(  'SIEVAD_DPA.DEPEN_ID2'   ,'=',$request->depen_id)
                          //->Where(  'SIEVAD_DPA.RECIBO_STATUS2','=',$request->semaforo)    
                          ->orderBy('SIEVAD_DPA.PERIODO_ID','ASC')                   
                          ->orderBy('SIEVAD_DPA.FOLIO'     ,'ASC')
                          ->orderBy('SIEVAD_DPA.PARTIDA'   ,'ASC')                          
                          ->get();    
                    //}                           
                    //dd('2........'.$totregs->count(), $totregs);
                }   // Depen_id
                //dd($totregs->count(), $totregs);
                //$registros =  $totregs[0]->registros;
                //if($registros <=0)
                if ($totregs->count() <=0)
                     return back()->withErrors(['FOLIO' => 'No existen registros de programa anual.']);
                    //toastr()->error('No existen registros de recibos de bitacora. ','Lo siento!',['positionClass' => 'toast-bottom-right']);  
                else{             
                    //if((!is_null($request->diaa))&&(is_null($request->cliee)) ){
                    if( !empty($request->depen_id) ){ 
                        //if($request->semaforo == 3 ){                  
                        $regprogdanual= regProgdAnualModel::join('SIEVAD_CAT_UMEDIDA','SIEVAD_CAT_UMEDIDA.UMED_ID','=',
                                                                 'SIEVAD_DPA.UMED_ID')
                                  ->join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                   'SIEVAD_DPA.DEPEN_ID2')
                        ->select(    
                                    'SIEVAD_DPA.DEPEN_ID1',
                                    'SIEVAD_DPA.DEPEN_ID2',
                                    'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC', 
                                    'SIEVAD_DPA.FOLIO', 
                                    'SIEVAD_DPA.PARTIDA', 
                                    'SIEVAD_DPA.CIPREP_ID',
                                    'SIEVAD_DPA.LGOB_COD',
                                    //'SIEVAD_DPA.MES_ID2', 
                                    //'SIEVAD_DPA.FECHA_ENTREGA', 
                                    'SIEVAD_DPA.FECHA_ENTREGA2', 
                                    //'SIEVAD_DPA.FECHA_ENTREGA3', 
                                    'SIEVAD_DPA.ACTIVIDAD_DESC', 
                                    
                                    'SIEVAD_DPA.UMED_ID', 
                                    'SIEVAD_CAT_UMEDIDA.UMED_DESC', 
                                    'SIEVAD_DPA.TOTP_01' ,'SIEVAD_DPA.TOTC_01' ,
                                    'SIEVAD_DPA.TOT_P01',
                                    'SIEVAD_DPA.SEMAFA_01',

                                    'SIEVAD_DPA.MESP_01', 'SIEVAD_DPA.MESC_01',
                                    'SIEVAD_DPA.MES_P01',
                                    'SIEVAD_DPA.SEMAF_01',
                                    'SIEVAD_DPA.MESP_02', 'SIEVAD_DPA.MESC_02',  
                                    'SIEVAD_DPA.MES_P02',
                                    'SIEVAD_DPA.SEMAF_02',
                                    'SIEVAD_DPA.MESP_03', 'SIEVAD_DPA.MESC_03',
                                    'SIEVAD_DPA.MES_P03',
                                    'SIEVAD_DPA.SEMAF_03',
                                    'SIEVAD_DPA.TRIMP_01', 'SIEVAD_DPA.TRIMC_01',
                                    'SIEVAD_DPA.TRIM_P01',
                                    'SIEVAD_DPA.SEMAFT_01',

                                    'SIEVAD_DPA.MESP_04', 'SIEVAD_DPA.MESC_04',
                                    'SIEVAD_DPA.MES_P04',
                                    'SIEVAD_DPA.SEMAF_04',
                                    'SIEVAD_DPA.MESP_05', 'SIEVAD_DPA.MESC_05',  
                                    'SIEVAD_DPA.MES_P05',
                                    'SIEVAD_DPA.SEMAF_05',
                                    'SIEVAD_DPA.MESP_06', 'SIEVAD_DPA.MESC_06',
                                    'SIEVAD_DPA.MES_P06',
                                    'SIEVAD_DPA.SEMAF_06',
                                    'SIEVAD_DPA.TRIMP_02', 'SIEVAD_DPA.TRIMC_02',
                                    'SIEVAD_DPA.TRIM_P02',
                                    'SIEVAD_DPA.SEMAFT_02',
                                    'SIEVAD_DPA.TSEMP_01', 'SIEVAD_DPA.TSEMC_01',
                                    'SIEVAD_DPA.SEM_P01',
                                    'SIEVAD_DPA.SEMAFS_01',

                                    'SIEVAD_DPA.MESP_07', 'SIEVAD_DPA.MESC_07',   
                                    'SIEVAD_DPA.MES_P07',
                                    'SIEVAD_DPA.SEMAF_07',
                                    'SIEVAD_DPA.MESP_08', 'SIEVAD_DPA.MESC_08',
                                    'SIEVAD_DPA.MES_P08',
                                    'SIEVAD_DPA.SEMAF_08',
                                    'SIEVAD_DPA.MESP_09', 'SIEVAD_DPA.MESC_09',
                                    'SIEVAD_DPA.MES_P09',
                                    'SIEVAD_DPA.SEMAF_09',
                                    'SIEVAD_DPA.TRIMP_03', 'SIEVAD_DPA.TRIMC_03',
                                    'SIEVAD_DPA.TRIM_P03',
                                    'SIEVAD_DPA.SEMAFT_03',

                                    'SIEVAD_DPA.MESP_10', 'SIEVAD_DPA.MESC_10', 
                                    'SIEVAD_DPA.MES_P10',
                                    'SIEVAD_DPA.SEMAF_10',
                                    'SIEVAD_DPA.MESP_11', 'SIEVAD_DPA.MESC_11', 
                                    'SIEVAD_DPA.MES_P11',
                                    'SIEVAD_DPA.SEMAF_11',
                                    'SIEVAD_DPA.MESP_12', 'SIEVAD_DPA.MESC_12', 
                                    'SIEVAD_DPA.MES_P12',
                                    'SIEVAD_DPA.SEMAF_12',
                                    'SIEVAD_DPA.TRIMP_04','SIEVAD_DPA.TRIMC_04',
                                    'SIEVAD_DPA.TRIM_P04',
                                    'SIEVAD_DPA.SEMAFT_04',
                                    'SIEVAD_DPA.TSEMP_02','SIEVAD_DPA.TSEMC_02',
                                    'SIEVAD_DPA.SEM_P02',
                                    'SIEVAD_DPA.SEMAFS_02',

                                    'SIEVAD_DPA.OBJETIVO_DESC', 
                                    'SIEVAD_DPA.OPERACIONAL_DESC', 
                                    'SIEVAD_DPA.SOPORTE_01' 
                               )
                          ->Where(  'SIEVAD_DPA.PERIODO_ID','=',$request->periodo_id) 
                          //->Where('SIEVAD_DPA.MES_ID'    ,'=',$request->mess)
                          ->Where('SIEVAD_DPA.DEPEN_ID2'   ,'=',$request->depen_id)
                          //->Where('SIEVAD_DPA.RECIBO_STATUS2','=',$request->semaforo)    
                          ->orderBy('SIEVAD_DPA.PERIODO_ID','ASC')                   
                          ->orderBy('SIEVAD_DPA.FOLIO'     ,'ASC')
                          ->orderBy('SIEVAD_DPA.PARTIDA'   ,'ASC')                          
                          ->get();                          
                        //}
                    }else{ 
                        $regprogdanual= regProgdAnualModel::join('SIEVAD_CAT_UMEDIDA'     ,'SIEVAD_CAT_UMEDIDA.UMED_ID','=',
                                                                 'SIEVAD_DPA.UMED_ID')
                                  ->join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                   'SIEVAD_DPA.DEPEN_ID2')
                        ->select(    
                                    'SIEVAD_DPA.DEPEN_ID1',
                                    'SIEVAD_DPA.DEPEN_ID2',
                                    'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC', 
                                    'SIEVAD_DPA.FOLIO', 
                                    'SIEVAD_DPA.PARTIDA', 
                                    'SIEVAD_DPA.CIPREP_ID',
                                    'SIEVAD_DPA.LGOB_COD',
                                    //'SIEVAD_DPA.MES_ID2', 
                                    //'SIEVAD_DPA.FECHA_ENTREGA', 
                                    'SIEVAD_DPA.FECHA_ENTREGA2', 
                                    //'SIEVAD_DPA.FECHA_ENTREGA3', 
                                    'SIEVAD_DPA.ACTIVIDAD_DESC', 
                                    
                                    'SIEVAD_DPA.UMED_ID', 
                                    'SIEVAD_CAT_UMEDIDA.UMED_DESC', 
                                    'SIEVAD_DPA.TOTP_01' ,'SIEVAD_DPA.TOTC_01' ,
                                    'SIEVAD_DPA.TOT_P01',
                                    'SIEVAD_DPA.SEMAFA_01',

                                    'SIEVAD_DPA.MESP_01', 'SIEVAD_DPA.MESC_01',
                                    'SIEVAD_DPA.MES_P01',
                                    'SIEVAD_DPA.SEMAF_01',
                                    'SIEVAD_DPA.MESP_02', 'SIEVAD_DPA.MESC_02',  
                                    'SIEVAD_DPA.MES_P02',
                                    'SIEVAD_DPA.SEMAF_02',
                                    'SIEVAD_DPA.MESP_03', 'SIEVAD_DPA.MESC_03',
                                    'SIEVAD_DPA.MES_P03',
                                    'SIEVAD_DPA.SEMAF_03',
                                    'SIEVAD_DPA.TRIMP_01', 'SIEVAD_DPA.TRIMC_01',
                                    'SIEVAD_DPA.TRIM_P01',
                                    'SIEVAD_DPA.SEMAFT_01',

                                    'SIEVAD_DPA.MESP_04', 'SIEVAD_DPA.MESC_04',
                                    'SIEVAD_DPA.MES_P04',
                                    'SIEVAD_DPA.SEMAF_04',
                                    'SIEVAD_DPA.MESP_05', 'SIEVAD_DPA.MESC_05',  
                                    'SIEVAD_DPA.MES_P05',
                                    'SIEVAD_DPA.SEMAF_05',
                                    'SIEVAD_DPA.MESP_06', 'SIEVAD_DPA.MESC_06',
                                    'SIEVAD_DPA.MES_P06',
                                    'SIEVAD_DPA.SEMAF_06',
                                    'SIEVAD_DPA.TRIMP_02', 'SIEVAD_DPA.TRIMC_02',
                                    'SIEVAD_DPA.TRIM_P02',
                                    'SIEVAD_DPA.SEMAFT_02',
                                    'SIEVAD_DPA.TSEMP_01', 'SIEVAD_DPA.TSEMC_01',
                                    'SIEVAD_DPA.SEM_P01',
                                    'SIEVAD_DPA.SEMAFS_01',

                                    'SIEVAD_DPA.MESP_07', 'SIEVAD_DPA.MESC_07',   
                                    'SIEVAD_DPA.MES_P07',
                                    'SIEVAD_DPA.SEMAF_07',
                                    'SIEVAD_DPA.MESP_08', 'SIEVAD_DPA.MESC_08',
                                    'SIEVAD_DPA.MES_P08',
                                    'SIEVAD_DPA.SEMAF_08',
                                    'SIEVAD_DPA.MESP_09', 'SIEVAD_DPA.MESC_09',
                                    'SIEVAD_DPA.MES_P09',
                                    'SIEVAD_DPA.SEMAF_09',
                                    'SIEVAD_DPA.TRIMP_03', 'SIEVAD_DPA.TRIMC_03',
                                    'SIEVAD_DPA.TRIM_P03',
                                    'SIEVAD_DPA.SEMAFT_03',

                                    'SIEVAD_DPA.MESP_10', 'SIEVAD_DPA.MESC_10', 
                                    'SIEVAD_DPA.MES_P10',
                                    'SIEVAD_DPA.SEMAF_10',
                                    'SIEVAD_DPA.MESP_11', 'SIEVAD_DPA.MESC_11', 
                                    'SIEVAD_DPA.MES_P11',
                                    'SIEVAD_DPA.SEMAF_11',
                                    'SIEVAD_DPA.MESP_12', 'SIEVAD_DPA.MESC_12', 
                                    'SIEVAD_DPA.MES_P12',
                                    'SIEVAD_DPA.SEMAF_12',
                                    'SIEVAD_DPA.TRIMP_04','SIEVAD_DPA.TRIMC_04',
                                    'SIEVAD_DPA.TRIM_P04',
                                    'SIEVAD_DPA.SEMAFT_04',
                                    'SIEVAD_DPA.TSEMP_02','SIEVAD_DPA.TSEMC_02',
                                    'SIEVAD_DPA.SEM_P02',
                                    'SIEVAD_DPA.SEMAFS_02',

                                    'SIEVAD_DPA.OBJETIVO_DESC', 
                                    'SIEVAD_DPA.OPERACIONAL_DESC', 
                                    'SIEVAD_DPA.SOPORTE_01'
                               )
                          ->Where(  'SIEVAD_DPA.PERIODO_ID'    ,'=',$request->periodo_id) 
                          //->Where('SIEVAD_DPA.MES_ID'        ,'=',$request->mess)
                          //->Where('SIEVAD_DPA.DEPEN_ID2'     ,'=',$request->depen_id)
                          //->Where('SIEVAD_DPA.RECIBO_STATUS2','=',$request->semaforo)    
                          ->orderBy('SIEVAD_DPA.PERIODO_ID','ASC')                   
                          ->orderBy('SIEVAD_DPA.FOLIO'     ,'ASC')
                          ->orderBy('SIEVAD_DPA.PARTIDA'   ,'ASC')                          
                          ->get();                                       
                    }   // depen_id

                    /************ Bitacora inicia *************************************/ 
                    setlocale(LC_TIME, "spanish");        
                    $xip          = session()->get('ip');
                    $xperiodo_id  = (int)date('Y');
                    $xprograma_id = 1;
                    $xmes_id      = (int)date('m');
                    $xproceso_id  =         3;
                    $xfuncion_id  =      3001;
                    $xtrx_id      =         3;    //Reportes        
                    $id           =         0;
                    $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID', 
                                   'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                                   ->get();
                    if($regbitacora->count() <= 0){              // Alta
                        $nuevoregBitacora = new regBitacoraModel();              
                        $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                        $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                        $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                        $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                        $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                        $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                        $nuevoregBitacora->FOLIO      = $id;             // Folio    
                        $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                        $nuevoregBitacora->IP         = $ip;             // IP
                        $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                        $nuevoregBitacora->save();
                        if($nuevoregBitacora->save() == true) 
                            toastr()->success('Trx de exportar a excel registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        else
                            toastr()->error('Error de trx de exportar a excel. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                    }else{                   
                        //*********** Obtine el no. de veces *****************************
                        $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                              'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                              'TRX_ID'     => $xtrx_id,'FOLIO'          => $id])
                                     ->max('NO_VECES');
                        $xno_veces = $xno_veces+1;                        
                        //*********** Termina de obtener el no de veces *****************************         
                        $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                       ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id,
                                                'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                'TRX_ID'     => $xtrx_id,'FOLIO'          => $id])
                                       ->update([
                                                 'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                                 'IP_M'     => $regbitacora->IP       = $ip,
                                                 'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                                 'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                                ]);
                        toastr()->success('Trx de exportar a excel registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }   //************ Bitacora termina ****************************************//         
                    //*************** Genera reporte a excel, las tablas estan en el reporte ***//
                    //return Excel::download(new ExcelExportAvancespa01v2($request->periodo_id,$request->mes_id,$request->depen_id,$request->tipo),'Detallado_AvancePA_'.date('d-m-Y').'.xlsx');
                    //*************** Genera reporte a excel, se define tablas antes ***//
                    return Excel::download(new ExcelExportAvancespa01($regprogdanual,$request->periodo_id,$request->mes_id,$request->depen_id,$request->tipo),'Detallado_AvancePA_'.date('d-m-Y').'.xlsx');
                    //return Excel::download(new ExcelExportReporteConsumoDet($regrecibos,$request->perr, $request->mess, $request->tipog_id),'ReporteDeConsumoDetallado_'.date('d-m-Y').'.xlsx');
                }       // si hay registros
                break;
            }   //Switch ($request->tipo) - Fin tipo de reporte pantalla, excel o PDF   
        }   // fin clase

    }    


}
