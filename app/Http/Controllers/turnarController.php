<?php
//**************************************************************/
//* File:       turnarController.php
//* Función:    Turnar documentos 
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: noviembre 2022
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\turnarRequest;

use App\regBitacoraModel;
use App\regTemaModel;
use App\regUAdmonModel;
use App\regPeriodosModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regPersonalModel;
use App\regRecepcionModel;
use App\regAtenderrecepModel;

// Exportar a excel 
//use App\Exports\ExportRecepcionExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class turnarController extends Controller
{

    public function actionBuscarTurnar(Request $request)
    {
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

        $regtema      = regTemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get(); 
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                  
        $regpersonal  = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->get();                                                                 
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        //$folio    = $request->get('folio');   
        $idd      = $request->get('idd');     //No. de oficio   
        $desti    = $request->get('desti');   //Destinatario
        $remi     = $request->get('remi');    //Remitente
        $asun     = $request->get('asun');    //Asunto
        $fper     = $request->get('fper');    //Periodo
        if(session()->get('rango') !== '0'){    
            $regrecepcion = regRecepcionModel::join('OFIPA_PERSONAL','OFIPA_PERSONAL.FOLIO','=',
                                                                     'OFIPA_ENTRADAS.CVE_SP')
                            ->select( 'OFIPA_PERSONAL.NOMBRE_COMPLETO','OFIPA_ENTRADAS.*')
                            ->orderBy('OFIPA_ENTRADAS.PERIODO_ID','DESC')
                            ->orderBy('OFIPA_ENTRADAS.FOLIO'     ,'DESC')
                            ->desti($desti)     //Metodos equvalente a ->where('DEPEN_DESC', 'LIKE', "%$name%");
                            ->remi($remi)       //Metodos personalizados
                            ->idd($idd)         //Metodos personalizados
                            ->asun($asun)       //Metodos personalizados     
                            ->fper($fper)       //Metodos personalizados  
                            ->paginate(40); 
        }else{
            $regrecepcion = regRecepcionModel::join('OFIPA_PERSONAL','OFIPA_PERSONAL.FOLIO','=',
                                                                     'OFIPA_ENTRADAS.CVE_SP')
                            ->select( 'OFIPA_PERSONAL.NOMBRE_COMPLETO','OFIPA_ENTRADAS.*')
                            ->where(  'OFIPA_ENTRADAS.CVE_SP'    ,$arbol_id)
                            ->orderBy('OFIPA_ENTRADAS.PERIODO_ID','DESC')   
                            ->orderBy('OFIPA_ENTRADAS.FOLIO'     ,'DESC')                          
                            ->desti($desti)     //Metodos equvalente a ->where('DEPEN_DESC', 'LIKE', "%$name%");
                            ->remi($remi)       //Metodos personalizados
                            ->idd($idd)         //Metodos personalizados
                            ->asun($asun)       //Metodos personalizados     
                            ->fper($fper)       //Metodos personalizados  
                            ->paginate(40);   
        }                                                                          
        if($regrecepcion->count() <= 0){
            toastr()->error('No existen documentos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.turnar.verTurnar', compact('nombre','usuario','regperiodos','regmeses','regdias','regrecepcion','regpersonal','regtema'));
    }
 
    public function actionVerTurnar(){
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

        $regtema      = regTemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get(); 
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();     
        $regpersonal  = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->get();                                             
        //********* Validar rol de usuario **********************/
        $band ="0";
        if(session()->get('rango') !== '0'){  
            $regrecepcion= regRecepcionModel::select('PERIODO_ID','FOLIO','ENT_FOLIO',
                           'ENT_NOFICIO','ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A',
                           'CVE_SP','UADMON_ID','ENT_RESP','ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3',
                           'PERIODO_ID2','MES_ID2','DIA_ID2','TEMA_ID','ENT_ARC1','ENT_ARC2',
                           'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','ENT_STATUS3','FECHA_REG','FECHA_REG2')
                           ->where(  'ENT_STATUS3','0')
                           ->orderBy('PERIODO_ID' ,'DESC')
                           ->orderBy('FOLIO'      ,'DESC')
                           ->paginate(40);
        }else{                  
            $regrecepcion= regRecepcionModel::join('OFIPA_PERSONAL','OFIPA_PERSONAL.FOLIO','=',
                                                                    'OFIPA_ENTRADAS.CVE_SP')
                           ->select( 'OFIPA_PERSONAL.FOLIO AS CLAVE_SP',
                                     'OFIPA_PERSONAL.NOMBRE_COMPLETO',
                                     'OFIPA_ENTRADAS.*')
                           ->where(  'OFIPA_ENTRADAS.CVE_SP'     ,$arbol_id)
                           ->where(  'OFIPA_ENTRADAS.ENT_STATUS3','0')
                           ->orderBy('OFIPA_ENTRADAS.PERIODO_ID' ,'DESC')
                           ->orderBy('OFIPA_ENTRADAS.FOLIO'      ,'DESC')
                           ->paginate(40);    
        }                        
        if($regrecepcion->count() <= 0){
            toastr()->error('No existe documento.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        } 
        return view('sicinar.turnar.verTurnar',compact('nombre','usuario','regperiodos','regrecepcion','regpersonal','regtema')); 
    }


    public function actionEditarTurnar($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');     
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
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                           ->orderBy('NOMBRE_COMPLETO','ASC')
                           ->get();  
            $regrecepcion =regRecepcionModel::select('PERIODO_ID','FOLIO','ENT_FOLIO',
                           'TEMA_ID','UADMON_ID',
                           'ENT_RESP', 'ENT_STATUS2','ENT_OBS2','ENT_ARC1','ENT_ARC2','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where('FOLIO',$id)
                           ->get();                                                                                 
            $regrespuesta =regAtenderrecepModel::select('PERIODO_ID','FOLIO','ENT_FOLIO','ENT_NOFICIO',
                           'ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A','CVE_SP','UADMON_ID',
                           'CVE_SP2','UADMON_ID2','ENT_RESP',
                           'ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3','PERIODO_ID1','MES_ID1','DIA_ID1',
                           'ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3','PERIODO_ID2','MES_ID2','DIA_ID2',
                           'ENT_FEC_RESP','ENT_FEC_RESP2','ENT_FEC_RESP3',
                           'PERIODO_ID3','MES_ID3','DIA_ID3','TEMA_ID','ENT_ARC1','ENT_ARC2',
                           'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','ENT_STATUS3','FECHA_REG','FECHA_REG2','IP','LOGIN',
                           'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where('FOLIO',$id)        
                           ->first();                           
        }else{ 
            $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                           ->where('FOLIO',$arbol_id)
                           ->get();      
            $regrecepcion =regRecepcionModel::select('PERIODO_ID','FOLIO','ENT_FOLIO',
                           'TEMA_ID','UADMON_ID',
                           'ENT_RESP', 'ENT_STATUS2','ENT_OBS2','ENT_ARC1','ENT_ARC2','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where('FOLIO',$id)
                           ->get();                                                          
            $regrespuesta =regAtenderrecepModel::select('PERIODO_ID','FOLIO','ENT_FOLIO','ENT_NOFICIO',
                           'ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A','CVE_SP','UADMON_ID',
                           'CVE_SP2','UADMON_ID2','ENT_RESP',
                           'ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3','PERIODO_ID1','MES_ID1','DIA_ID1',
                           'ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3','PERIODO_ID2','MES_ID2','DIA_ID2',
                           'ENT_FEC_RESP','ENT_FEC_RESP2','ENT_FEC_RESP3',
                           'PERIODO_ID3','MES_ID3','DIA_ID3','TEMA_ID','ENT_ARC1','ENT_ARC2',
                           'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','ENT_STATUS3','FECHA_REG','FECHA_REG2','IP','LOGIN',
                           'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where('FOLIO',$id)        
                           ->first();                               
        } 
        if($regrespuesta->count() <= 0){
            toastr()->error('No existen documentos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.turnar.editarTurnar',compact('nombre','usuario','regperiodos','regmeses','regdias','regpersonal','regtema','regrespuesta','regrecepcion'));
    }

    public function actionActualizarTurnar(turnarRequest $request, $id){
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
 
        // **************** actualizar ******************************
        $regrespuesta = regAtenderrecepModel::where('FOLIO',$id);
        if($regrespuesta->count() <= 0)
            toastr()->error('No existe documento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $regrespuesta = regAtenderrecepModel::where('FOLIO',$id)        
                            ->update([      
                                      'TEMA_ID'      => $request->tema_id,
                                      'CVE_SP'       => $request->cve_sp,
                                      'ENT_STATUS3'   => $request->ent_status3,

                                      'IP_M'          => $ip,
                                      'LOGIN_M'       => $nombre,
                                      'FECHA_M2'      => date('Y/m/d'),    //date('d/m/Y')                                
                                      'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
            toastr()->success('documento actualizado y turnado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //*********************** Respuestas ***************************************//
            $regrecepcion = regRecepcionModel::where('FOLIO',$id)  
                            ->update([      
                                      'TEMA_ID'     => $request->tema_id,
                                      'CVE_SP'      => $request->cve_sp,       
                                      'ENT_STATUS3' => $request->ent_status3,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M2'    => date('Y/m/d'),   //date('d/m/Y')  
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')        
                                   ]);
            toastr()->success('documento de respuesta actualizado y turnado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =         7;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
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
                    toastr()->success('Trx turnar documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de turnar documento. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtiene el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                      'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                      'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Trx de turnar documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verturnar');
    }


}
