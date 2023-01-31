<?php
//**************************************************************/
//* File:       atenderrecepController.php
//* Función:    Atender y dar seguimiento de oficios de entrada (recepcion)
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: noviembre 2022
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\atenrecepRequest;
use App\Http\Requests\atenrecep2Request;

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

class atenderrecepController extends Controller
{

    public function actionBuscarAtenrecep(Request $request)
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
                            //->where(  'OFIPA_ENTRADAS.UADMON_ID' ,$depen_id)
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
        return view('sicinar.recepcion_documentos.verAtenrecep', compact('nombre','usuario','regperiodos','regmeses','regdias','regrecepcion','regpersonal','regtema'));
    }
 
    public function actionVerAtenrecep(){
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
        if(session()->get('rango') !== '0'){  
            $regrecepcion= regRecepcionModel::select('PERIODO_ID','FOLIO','ENT_FOLIO',
                           'ENT_NOFICIO','ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A',
                           'CVE_SP','UADMON_ID','ENT_RESP','ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3',
                           'PERIODO_ID2','MES_ID2','DIA_ID2','TEMA_ID','ENT_ARC1','ENT_ARC2',
                           'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','ENT_STATUS3','FECHA_REG','FECHA_REG2')
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('FOLIO'     ,'DESC')
                           ->paginate(40);
        }else{                  
            $regrecepcion= regRecepcionModel::join('OFIPA_PERSONAL','OFIPA_PERSONAL.FOLIO','=',
                                                                    'OFIPA_ENTRADAS.CVE_SP')
                           ->select( 'OFIPA_PERSONAL.FOLIO AS CLAVE_SP',
                                     'OFIPA_PERSONAL.NOMBRE_COMPLETO',
                                     'OFIPA_ENTRADAS.*')
                           ->where(  'OFIPA_ENTRADAS.CVE_SP'    ,$arbol_id)
                           ->orderBy('OFIPA_ENTRADAS.PERIODO_ID','DESC')
                           ->orderBy('OFIPA_ENTRADAS.FOLIO'     ,'DESC')
                           ->paginate(40);    
        }                         
        if($regrecepcion->count() <= 0){
            toastr()->error('No existe documento.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        } 
        return view('sicinar.recepcion_documentos.verAtenrecep',compact('nombre','usuario','regperiodos','regrecepcion','regpersonal','regtema')); 
    }

    public function actionEditarAtenrecep($id){
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
                           'ENT_RESP', 'ENT_STATUS2','ENT_STATUS3','ENT_OBS2','ENT_ARC1','ENT_ARC2','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
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
                           'ENT_RESP', 'ENT_STATUS2','ENT_STATUS3','ENT_OBS2','ENT_ARC1','ENT_ARC2','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where('FOLIO'    ,$id)
                           //->where('UADMON_ID',$depen_id)
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
                           ->where('FOLIO'    ,$id)        
                           //->where('UADMON_ID',$depen_id)
                           ->first();                               
        } 
        if($regrespuesta->count() <= 0){
            toastr()->error('No existen documentos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.recepcion_documentos.editarAtenrecep',compact('nombre','usuario','regperiodos','regmeses','regdias','regpersonal','regtema','regrespuesta','regrecepcion'));
    }

    public function actionActualizarAtenrecep(atenrecepRequest $request, $id){
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
        //$regrecepcion = regRecepcionModel::where('FOLIO',$id);
        $regrespuesta = regAtenderrecepModel::where('FOLIO',$id);
        if($regrespuesta->count() <= 0)
            toastr()->error('No existe documento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $mes3 = regMesesModel::ObtMes($request->mes_id3);
            $dia3 = regDiasModel::ObtDia($request->dia_id3);                
            //$sp = regPersonalModel::Obtsp($request->cve_sp);    

            //********************** Actualizar ********************************/
            //dd('archivo...:'.$request->hasFile('ent_arc2'),$request->file('ent_arc2'),$request->ent_arc2);
            $name01 =null;
            if($request->hasFile('ent_arc1')){
                $name01 = Trim($request->ent_arc1);
            }            
            $name02 =null;
            if($request->hasFile('ent_arc2')){
                
                $name02 = $request->periodo_id.'_'.$id.'_'.$request->file('ent_arc2')->getClientOriginalName(); 
                $request->file('ent_arc2')->move(public_path().'/storage/', $name02);
                //dd('Entre 1......'.$name02);
                $regrespuesta = regAtenderrecepModel::where('FOLIO',$id)        
                                ->update([      
                    'ENT_FEC_RESP'  => date('Y/m/d', strtotime($request->periodo_id3.'/'.$mes3[0]->mes_mes.'/'.trim($dia3[0]->dia_desc) )),
                    'ENT_FEC_RESP2' => trim($dia3[0]->dia_desc.'/'.$mes3[0]->mes_mes.'/'.$request->periodo_id3), 
                    'ENT_FEC_RESP3' => date('Y/m/d', strtotime($request->periodo_id3.'/'.$mes3[0]->mes_mes.'/'.trim($dia3[0]->dia_desc) )),
                    'PERIODO_ID3'   => $request->periodo_id3,
                    'MES_ID3'       => $request->mes_id3,
                    'DIA_ID3'       => $request->dia_id3,
                    'TEMA_ID'       => $request->tema_id,

                    'ENT_RESP'      => substr(trim(strtoupper($request->ent_resp)),0,3999),
                    'ENT_STATUS2'   => $request->ent_status2,
                    'ENT_OBS2'      => substr(trim(strtoupper($request->ent_obs2)),0,3999), 
                    //'ENT_ARC1'    => $name01,
                    'ENT_ARC2'      => $name02,

                    'IP_M'          => $ip,
                    'LOGIN_M'       => $nombre,
                    'FECHA_M2'      => date('Y/m/d'),    //date('d/m/Y')                                
                    'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                toastr()->success('documento actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                //*********************** Respuestas ***************************************//
                $regrecepcion = regRecepcionModel::where('FOLIO',$id)  
                                ->update([      
                                          'TEMA_ID'     => $request->tema_id,
                                          'ENT_RESP'    => substr(trim(strtoupper($request->ent_resp)),0,3999),       
                                          'ENT_STATUS2' => $request->ent_status2,
                                          'ENT_OBS2'    => substr(trim(strtoupper($request->ent_obs2)),0,3999),        
                                          'ENT_ARC2'    => $name02,                    
  
                                          'IP_M'        => $ip,
                                          'LOGIN_M'     => $nombre,
                                          'FECHA_M2'    => date('Y/m/d'),   //date('d/m/Y')  
                                          'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')        
                                       ]);
                toastr()->success('documento de respuesta actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            }else{ 
                //dd('Entre 2 ......');
                $regrespuesta = regAtenderrecepModel::where('FOLIO',$id)        
                                ->update([      
                    'ENT_FEC_RESP'  => date('Y/m/d', strtotime($request->periodo_id3.'/'.$mes3[0]->mes_mes.'/'.trim($dia3[0]->dia_desc) )),
                    'ENT_FEC_RESP2' => trim($dia3[0]->dia_desc.'/'.$mes3[0]->mes_mes.'/'.$request->periodo_id3), 
                    'ENT_FEC_RESP3' => date('Y/m/d', strtotime($request->periodo_id3.'/'.$mes3[0]->mes_mes.'/'.trim($dia3[0]->dia_desc) )),
                    'PERIODO_ID3'   => $request->periodo_id3,
                    'MES_ID3'       => $request->mes_id3,
                    'DIA_ID3'       => $request->dia_id3,
                    'TEMA_ID'       => $request->tema_id,

                    'ENT_RESP'      => substr(trim(strtoupper($request->ent_resp)),0,3999),
                    'ENT_STATUS2'   => $request->ent_status2,
                    'ENT_OBS2'      => substr(trim(strtoupper($request->ent_obs2)),0,3999),  
                    //'ENT_ARC1'    => $name01,      
                    //'ENT_ARC2'    => $name02,

                    'IP_M'          => $ip,
                    'LOGIN_M'       => $nombre,
                    'FECHA_M2'      => date('Y/m/d'),    //date('d/m/Y')                                
                    'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                toastr()->success('documento actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                //*********************** Respuestas ***************************************//
                $regrecepcion = regRecepcionModel::where('FOLIO',$id)        
                //$regrespuesta = regRecepcionRespModel::where('FOLIO',$id)        
                                ->update([      
                                          'TEMA_ID'       => $request->tema_id,
                                          'ENT_RESP'      => substr(trim(strtoupper($request->ent_resp)) ,0,3999),       
                                          'ENT_STATUS2'   => $request->ent_status2,
                                          'ENT_OBS2'      => substr(trim(strtoupper($request->ent_obs2)),0,3999), 
                                          //'ENT_ARC2'    => $name02,                    

                                          'IP_M'         => $ip,
                                          'LOGIN_M'      => $nombre,
                                          'FECHA_M2'     => date('Y/m/d'),    //date('d/m/Y')       
                                          'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')   
                                       ]);
                toastr()->success('documento de respuesta actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m'); 
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =        12;    //Actualizar 
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
                    toastr()->success('Trx actualización de documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de actualización de documento. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
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
                toastr()->success('Trx de actualización de documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('veratenrecep');
    }

    public function actionEditarAtenrecep2($id){
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
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                           ->orderBy('NOMBRE_COMPLETO','ASC')
                           ->get();                                                        
            $regrespuesta =regAtenderrecepModel::select('PERIODO_ID','FOLIO','ENT_FOLIO','ENT_NOFICIO',
                           'ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A','CVE_SP','UADMON_ID',
                           'CVE_SP2','UADMON_ID2','ENT_RESP',
                           'ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3','PERIODO_ID1','MES_ID1','DIA_ID1',
                           'ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3','PERIODO_ID2','MES_ID2','DIA_ID2',
                           'ENT_FEC_RESP','ENT_FEC_RESP2','ENT_FEC_RESP3',
                           'PERIODO_ID3','MES_ID3','DIA_ID3','TEMA_ID','ENT_ARC1','ENT_ARC2',
                           'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                           'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where('FOLIO',$id)        
                           ->first();                           
        }else{
            $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                           ->where('FOLIO',$arbol_id)
                           ->get();                                  
            $regrespuesta =regAtenderrecepModel::select('PERIODO_ID','FOLIO','ENT_FOLIO','ENT_NOFICIO',
                           'ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A','CVE_SP','UADMON_ID',
                           'CVE_SP2','UADMON_ID2','ENT_RESP',
                           'ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3','PERIODO_ID1','MES_ID1','DIA_ID1',
                           'ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3','PERIODO_ID2','MES_ID2','DIA_ID2',
                           'ENT_FEC_RESP','ENT_FEC_RESP2','ENT_FEC_RESP3',
                           'PERIODO_ID3','MES_ID3','DIA_ID3','TEMA_ID','ENT_ARC1','ENT_ARC2',
                           'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                           'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where('FOLIO'    ,$id)        
                           //->where('UADMON_ID',$depen_id)
                           ->first();                                    
        } 
        if($regrespuesta->count() <= 0){
            toastr()->error('No existen documentos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.recepcion_documentos.editarAtenrecep2',compact('nombre','usuario','regperiodos','regmeses','regdias','regpersonal','regtema','regrespuesta'));
    }

    public function actionActualizarAtenrecep2(atenrecep2Request $request, $id){
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

            //********************** Actualizar documento ***************************/
            $name02 =null;
            if($request->hasFile('ent_arc2')){
                $name02 = $request->periodo_id.'_'.$id.'_'.$request->file('ent_arc2')->getClientOriginalName(); 
                $request->file('ent_arc2')->move(public_path().'/storage/', $name02);
                //*********************** Respuestas ***************************************//
                $regrespuesta=regAtenderrecepModel::where('FOLIO',$id)        
                              ->update([      
                                        'ENT_ARC2'  => $name02,

                                        'IP_M'      => $ip,
                                        'LOGIN_M'   => $nombre,
                                        'FECHA_M2'  => date('Y/m/d'),    //date('d/m/Y')                                
                                        'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                toastr()->success('documento de respuesta actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                $regrecepcion=regRecepcionModel::where('FOLIO',$id)        
                              ->update([                
                                        'ENT_ARC2' => $name02,

                                        'IP_M'     => $ip,
                                        'LOGIN_M'  => $nombre,
                                        'FECHA_M2' => date('Y/m/d'),    //date('d/m/Y')
                                        'FECHA_M'  => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                toastr()->success('Archivo digital actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   //*** Termina actualización ************************//

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =        12;    //Actualizar 
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
                    toastr()->success('Trx actualización de documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de actualización de documento. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
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
                toastr()->success('Trx de actualización de documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('veratenrecep');
    }


    public function actionBorrarRecepcion($id){
        //dd($request->all());
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

        //************ Eliminar documento de entrada ********************************//
        $regrecepcion = regRecepcionModel::where('FOLIO',$id);
        if($regrecepcion->count() <= 0)
            toastr()->error('No existe documento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regrecepcion->delete();
            toastr()->success('documento eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //************ Eliminar documento de respuesta ********************************//
            $regrespuesta = regRecepcionRespModel::where('FOLIO',$id);
            if($regrespuesta->count() <= 0)
                 toastr()->error('No existe documento de respuesta.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{        
               $regrespuesta->delete();
               toastr()->success('documento de respuesta eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =        13;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID','FUNCION_ID', 
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
                    toastr()->success('Trx de elimiar de documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de Trx de elimiar de documento al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de elimiar de documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   //************ Bitacora termina *************************************//                
        }       //************* Termina de eliminar documento ***********************//
        return redirect()->route('verrecepcion');
    }    

    // exportar a formato excel
    public function actionExportRecepcionExcel($id){
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
        
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3003;
        $xtrx_id      =        14;            // Exportar a formato Excel
        $id           =         0;
        $regbitacora  = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
               toastr()->success('Trx de exportar a excel documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error Trx de exportar a excel documento. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id,
                                                  'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                  'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                         ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************                
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx de exportar a excel documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /********************** Bitacora termina *************************************/  
        return Excel::download(new ExportRecepcionExcel, 'recepcion_documentos_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportRecepcionPDF($id,$id2){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');   
        $depen_id     = session()->get('depen_id');             

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3003;
        $xtrx_id      =        15;       //Exportar a formato PDF
        $id           =         0;
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
               toastr()->success('Trx de exportar a PDF documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de exportar a excel documento. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                  'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
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
            toastr()->success('Trx de exportar a excel documento actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regtema      = regTemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get(); 
        //$regpersonal    = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
        //                ->orderBy('FOLIO','asc')
        //                ->get();                         
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                     
        $regpersonal  = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->orderBy('NOMBRE_COMPLETO','ASC')
                        ->get();                                                        
        $regrespuesta = regRecepcionRespModel::select('PERIODO_ID','FOLIO','ENT_FOLIO','ENT_NOFICIO',
                        'ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A','CVE_SP','UADMON_ID',
                        'CVE_SP2','UADMON_ID2','ENT_RESP','ENT_FEC_RESP','ENT_FEC_RESP2','ENT_FEC_RESP3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','ENT_ARC1','ENT_ARC2','ENT_ARC3',
                        'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                        'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->get();                
        $regrecepcion = regRecepcionModel::select('PERIODO_ID','FOLIO','ENT_FOLIO',
                        'ENT_NOFICIO','ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A',
                        'CVE_SP','UADMON_ID','ENT_RESP','ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3',
                        'PERIODO_ID2','MES_ID2','DIA_ID2','TEMA_ID','ENT_ARC1','ENT_ARC2','ENT_ARC3',
                        'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                        'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->get();                                                                          
        $regrecepcion = regRecepcionModel::join('OFIPA_CAT_UADMON' ,'OFIPA_CAT_UADMON.UADMON_ID','=',
                                                                    'OFIPA_PERSONAL.UADMON_ID')
                                         ->join('OFIPA_PERSONAL'   ,'OFIPA_PERSONAL.FOLIO','=',
                                                                    'OFIPA_ENTRADAS.CVE_SP')
                       ->select(    'OFIPA_ENTRADAS.PERIODO_ID', 
                                    'OFIPA_ENTRADAS.FOLIO', 
                                    'OFIPA_ENTRADAS.ENT_NOFICIO', 
                                    'OFIPA_ENTRADAS.ENT_DESTIN',
                                    'OFIPA_ENTRADAS.ENT_REMITEN',
                                    'OFIPA_ENTRADAS.ENT_ASUNTO',
                                    'OFIPA_ENTRADAS.ENT_UADMON',
                                    'OFIPA_PERSONAL.NOMBRE_COMPLETO', 
                                    'OFIPA_ENTRADAS.ENT_TURNADO_A', 
                                    'OFIPA_ENTRADAS.CVE_SP', 
                                    'OFIPA_ENTRADAS.UADMON_ID', 
                                    'OFIPA_ENTRADAS.ENT_RESP', 
                                    'OFIPA_ENTRADAS.ENT_FEC_OFIC', 
                                    'OFIPA_ENTRADAS.ENT_FEC_OFIC2', 
                                    'OFIPA_ENTRADAS.ENT_FEC_OFIC3', 
                                    'OFIPA_ENTRADAS.PERIODO_ID1', 
                                    'OFIPA_ENTRADAS.MES_ID1', 
                                    'OFIPA_ENTRADAS.DIA_ID1', 
                                    'OFIPA_ENTRADAS.ENT_FEC_RECIB', 
                                    'OFIPA_ENTRADAS.ENT_FEC_RECIB2', 
                                    'OFIPA_ENTRADAS.ENT_FEC_RECIB3', 
                                    'OFIPA_ENTRADAS.PERIODO_ID2', 
                                    'OFIPA_ENTRADAS.MES_ID2', 
                                    'OFIPA_ENTRADAS.DIA_ID2',                                    
                                    'OFIPA_ENTRADAS.TEMA_ID', 
                                    'OFIPA_ENTRADAS.ENT_ARC1', 
                                    'OFIPA_ENTRADAS.ENT_ARC2'
                               )
                       ->where(     'OFIPA_ENTRADAS.FOLIO'     ,$id2)
                       ->orderBy(   'OFIPA_ENTRADAS.PERIODO_ID','ASC')                   
                       ->orderBy(   'OFIPA_ENTRADAS.FOLIO'     ,'ASC')
                ->get();    
        //dd('Llave:',$id,' llave2:',$id2);       
        if($regrecepcion->count() <= 0){ 
            toastr()->error('No existe documento.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }else{
            $pdf = PDF::loadView('sicinar.pdf.RecepcionPdf',compact('nombre','usuario','regtema','regrecepcion','regpersonal','regmeses','regttema'));
            //$options = new Options();
            //$options->set('defaultFont', 'Courier');
            //$pdf->set_option('defaultFont', 'Courier');
            $pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');          
            //$pdf->setPaper('A4','portrait');

            // Output the generated PDF to Browser
            return $pdf->stream('recepcion_documentos-'.$id2);
        }
    }

}
