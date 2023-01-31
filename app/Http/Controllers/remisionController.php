<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


// Requests rules
use App\Http\Requests\remisionRequest;
use App\Http\Requests\remision1Request;

//Models
use App\regBitacoraModel;
use App\regTemaModel;
use App\regUAdmonModel;
use App\regPeriodosModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regPersonalModel;
use App\regRemisionModel;


class remisionController extends Controller
{


        public function actionBuscarSalida(Request $request){
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
           
            $todo      = $request->get('todo');  
            $periodo   = $request->get('periodo');
            $arbol      =$request->get('arbol'); 
 
            if(session()->get('rango') !== '0'){    
                $regremision = regRemisionModel::join('OFIPA_PERSONAL','OFIPA_PERSONAL.FOLIO','=',
                                                                       'OFIPA_SALIDAS.CVE_SP')
                                ->select( 'OFIPA_PERSONAL.NOMBRE_COMPLETO','OFIPA_SALIDAS.*')
                                ->iddSal($periodo,$arbol)
                                ->idTodo($todo)
                                ->orderBy('OFIPA_SALIDAS.PERIODO_ID','DESC')
                                ->orderBy('OFIPA_SALIDAS.FOLIO'     ,'DESC')
                                        
                                //->fpersal($todo)      
                                ->paginate(40); 
            }else{
                $regremision = regRemisionModel::join('OFIPA_PERSONAL','OFIPA_PERSONAL.FOLIO','=',
                                                                       'OFIPA_SALIDAS.CVE_SP')
                                ->select('OFIPA_PERSONAL.NOMBRE_COMPLETO','OFIPA_SALIDAS.*')
                                //->where(  'OFIPA_SALIDAS.UADMON_ID' ,$depen_id)
                                ->where(  'OFIPA_SALIDAS.CVE_SP'    ,$arbol_id)
                                ->iddSal($periodo,$arbol)
                                ->idTodo($todo)
                                ->orderBy('OFIPA_SALIDAS.PERIODO_ID','DESC')   
                                ->orderBy('OFIPA_SALIDAS.FOLIO'     ,'DESC')                          
                                       
                                //->fpersal($todo)  
                                ->paginate(40);   
            }                                                                          
            if($regremision->count() <= 0){
                toastr()->error('No existen documentos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            }            
            return view('sicinar.remision_documentos.verSalida', compact('nombre','usuario','regperiodos','regmeses','regdias','regremision','regpersonal','regtema', 'arbol_id'));
        }
       


        public function actionVerRemision($ANIO){

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
                         
            $regperiodos    = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                            ->get();
            $regmeses       = regMesesModel::select('MES_ID','MES_DESC')
                            ->get();      
            $regdias        = regDiasModel::select('DIA_ID','DIA_DESC')
                            ->get(); 

            
           

                if(session()->get('rango') !== '0'){  
                    $regremision = regRemisionModel::join('OFIPA_PERSONAL','OFIPA_PERSONAL.FOLIO','=',
                                                                           'OFIPA_SALIDAS.CVE_SP')
                                   ->select('OFIPA_PERSONAL.NOMBRE_COMPLETO','OFIPA_SALIDAS.*')
                                   ->where('OFIPA_SALIDAS.PERIODO_ID', $ANIO)
                                   ->orderBy('OFIPA_SALIDAS.PERIODO_ID','DESC')
                                   ->orderBy('OFIPA_SALIDAS.FOLIO'     ,'DESC')
                                   ->paginate(50);
                }else{                  
                    $regremision = regRemisionModel::join('OFIPA_PERSONAL','OFIPA_PERSONAL.FOLIO','=',
                                                                           'OFIPA_SALIDAS.CVE_SP')
                                   ->select('OFIPA_PERSONAL.NOMBRE_COMPLETO','OFIPA_SALIDAS.*')
                                   //->where(  'DEPEN_ID1' ,$depen_id)
                                   ->where('OFIPA_SALIDAS.CVE_SP', '=', $arbol_id)
                                   ->where('OFIPA_SALIDAS.PERIODO_ID','=',$ANIO)
                                   ->orderBy('OFIPA_SALIDAS.PERIODO_ID','DESC')
                                   ->orderBy('OFIPA_SALIDAS.FOLIO'     ,'DESC')  
                                   ->paginate(50);          
                }                        
                if($regremision->count() <= 0){
                    toastr()->error('No existe documento.','Lo siento!',['positionClass' => 'toast-bottom-right']);
                }
                        
                return view('sicinar.remision_documentos.verSalida',compact('nombre','usuario','regperiodos','regremision','regtema','ANIO','arbol_id')); 
        }
        
        public function actionNuevaRemision(Request $request){
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
                            ->orderBy('TEMA_DESC','asc')
                            ->get(); 
            //$regpersonal    = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //                ->orderBy('FOLIO','asc')
            //                ->get();                         
           
            $testing = $request -> testing;

            $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                            ->get();      
            $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                            ->get(); 
                            

            if(session()->get('rango') !== '0'){         
                $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                               ->orderBy('NOMBRE_COMPLETO','ASC')
                               ->get();                                                        
            }else{
                $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                               ->where('UADMON_ID',$depen_id)
                               ->get();                                  
            } 
                   
            $regremision = regRemisionModel::select('*')
                            ->orderBy('PERIODO_ID','asc')
                            ->orderBy('FOLIO'     ,'asc')
                            ->get();
                            //regrespuesta
            return view('sicinar.remision_documentos.nuevaSalida',compact('nombre','usuario','regperiodos','regmeses','regdias','regremision','regtema','regpersonal', 'regrespuesta', 'testing'));
        }
    
        public function actionAltaNuevaSalida(Request $request){
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
    
            /************ Obtenemos la IP ***************************/                
            if (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_X_FORWARDED')) {
                $ip = getenv('HTTP_X_FORWARDED');
            } elseif (getenv('HTTP_FORWARDED_FOR')) {
                $ip = getenv('HTTP_FORWARDED_FOR');
            } elseif (getenv('HTTP_FORWARDED')) {
                $ip = getenv('HTTP_FORWARDED');
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }        
                
               

                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
                $mes2 = regMesesModel::ObtMes($request->mes_id2);
                $dia2 = regDiasModel::ObtDia($request->dia_id2);             
                $sp   = regPersonalModel::Obtsp($request->cve_sp); 
                      


                $periodo_id = date('Y');
                $folio = regRemisionModel::max('FOLIO');
                $folio = $folio + 1;
     
                $file1 =null;
                if(isset($request->sal_arc1)){
                    if(!empty($request->sal_arc1)){
                        //Comprobar  si el campo act_const tiene un archivo asignado:
                        if($request->hasFile('sal_arc1')){
                            $file1=$request->periodo_id.'_'.$folio.'_'.$request->file('sal_arc1')->getClientOriginalName();
                            //sube el archivo a la carpeta del servidor public/images/
                            $request->file('sal_arc1')->move(public_path().'/storage/', $file1);
                        }
                    }
                }

                $nuevoremision = new regRemisionModel();
                $nuevoremision->PERIODO_ID    = $periodo_id;             
                $nuevoremision->FOLIO         = $folio;
                $nuevoremision->SAL_FOLIO     = $folio;            
                $nuevoremision->SAL_NOFICIO   = substr(trim(strtoupper($request->sal_noficio)),0,19);
                $nuevoremision->CVE_SP        = $request->cve_sp;
                $nuevoremision->CVE_SPSEG     = $request->cve_spseg;            
                $nuevoremision->UADMON_ID     = $sp[0]->uadmon_id; 
                $nuevoremision->UADMON_IDSEG  = $sp[0]->uadmon_id2;           
                $nuevoremision->SAL_FEC_OFIC  = date('Y/m/d', strtotime(trim($request->periodo_id1.'/'.$request->mes_id1.'/'.$request->dia_id1) ));
                $nuevoremision->SAL_FEC_OFIC2 = trim($request->dia_id1.'/'.$request->mes_id1.'/'.$request->periodo_id1);
                $nuevoremision->SAL_FEC_OFIC3 = date('Y/m/d', strtotime(trim($request->periodo_id1.'/'.$request->mes_id1.'/'.$request->dia_id1) ));            
                $nuevoremision->PERIODO_ID1   = $request->periodo_id1;                
                $nuevoremision->MES_ID1       = $request->mes_id1;                
                $nuevoremision->DIA_ID1       = $request->dia_id1;      
    
                $nuevoremision->SAL_FEC_RECIB = date('Y/m/d', strtotime(trim($request->periodo_id2.'/'.$request->mes_id2.'/'.$request->dia_id2) ));
                $nuevoremision->SAL_FEC_RECIB2= trim($request->dia_id2.'/'.$request->mes_id2.'/'.$request->periodo_id2);
                $nuevoremision->SAL_FEC_RECIB3= date('Y/m/d', strtotime(trim($request->periodo_id2.'/'.$request->mes_id2.'/'.$request->dia_id2) ));            
                $nuevoremision->PERIODO_ID2   = $request->periodo_id2;                
                $nuevoremision->MES_ID2       = $request->mes_id2;                   
                $nuevoremision->DIA_ID2       = $request->dia_id2;
                $nuevoremision->TEMA_ID       = $request->tema_id;
    
                $nuevoremision->SAL_DESTIN    = substr(trim(strtoupper($request->sal_destin)) ,0,  99);
                $nuevoremision->SAL_REMITEN   = substr(trim(strtoupper($request->sal_remiten)),0,  99);
                $nuevoremision->SAL_ASUNTO    = substr(trim(strtoupper($request->sal_asunto)) ,0,3999);
                $nuevoremision->SAL_COMMENT   = substr(trim(strtoupper($request->sal_comment)),0, 999);
                $nuevoremision->SAL_UADMON    = substr(trim(strtoupper($request->sal_uadmon)) ,0,  99);            
                $nuevoremision->SAL_ARC1      = $file1;
                $nuevoremision->SAL_DOCHIS    = $file1;
            
                $nuevoremision->IP            = $ip;
                $nuevoremision->LOGIN         = $nombre;         // Usuario ;
                $nuevoremision->save();
                if($nuevoremision->save() == true){

                                       /************ Bitacora inicia *************************************/ 
                                       setlocale(LC_TIME, "spanish");        
                                       $xip          = session()->get('ip');
                                       $xperiodo_id  = (int)date('Y');
                                       $xprograma_id = 1;
                                       $xmes_id      = (int)date('m');
                                       $xproceso_id  =         3;
                                       $xfuncion_id  =      3011;
                                       $xtrx_id      =        16;    //Alta
                                       $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                                               'FECHA_M','IP_M','LOGIN_M')
                                                      ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                                               'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $folio])
                                                      ->get();
                   
                    if($regbitacora->count() <= 0){              // Alta
                        $nuevoregBitacora = new regBitacoraModel();              
                        $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                        $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                        $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                        $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                        $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                        $nuevoregBitacora->FOLIO      = $folio;          // Folio    
                        $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                        $nuevoregBitacora->IP         = $ip;             // IP
                        $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                        $nuevoregBitacora->save();
                        if($nuevoregBitacora->save() == true)
                            toastr()->success('Trx de documento dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        else
                            toastr()->error('Error trx. de documento. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                    }else{                   
                        //*********** Obtine el no. de veces *****************************
                        $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                              'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                              'TRX_ID'     => $xtrx_id,    'FOLIO'      => $folio])
                                     ->max('NO_VECES');
                        $xno_veces = $xno_veces+1;                        
                        //*********** Termina de obtener el no de veces *****************************         
                        $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                       ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                                'TRX_ID'     => $xtrx_id,    'FOLIO'      => $folio])
                                       ->update([
                                                 'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                                 'IP_M'    => $regbitacora->IP       = $ip,
                                                 'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                                 'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                                ]);
                        toastr()->success('Trx de documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }   /************ Bitacora termina *************************************/ 
                }else{
                    toastr()->error('Error en Trx documento Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }   //**************** Termina la alta *******************/
            ////}  
                // ******************* Termina el duplicado **********/
           return redirect()->route('versalida', [$request -> periodo_id2]);
            }
    
        public function actionEditarSalida($id){
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

           
            if(session()->get('rango') !== '0'){                          
                $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                               ->orderBy('NOMBRE_COMPLETO','ASC')
                               ->get();                                                        
            }else{
                $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                               ->where('UADMON_ID',$depen_id)
                               ->get();                                  
            }     
                $regremision = regRemisionModel::select('PERIODO_ID','FOLIO','SAL_FOLIO',
                'SAL_NOFICIO','SAL_DESTIN','SAL_REMITEN','SAL_ASUNTO','SAL_UADMON','SAL_QUIENENV',
                'SAL_QUIENENV2OP', 'CVE_SP','CVE_SPSEG','UADMON_ID','UADMON_IDSEG','SAL_RESP','SAL_FEC_OFIC','SAL_FEC_OFIC2','SAL_FEC_OFIC3',
                'PERIODO_ID1','MES_ID1','DIA_ID1','SAL_FEC_RECIB','SAL_FEC_RECIB2','SAL_FEC_RECIB3',
                'PERIODO_ID2','MES_ID2','DIA_ID2','TEMA_ID','SAL_ARC1','SAL_ARC2','SAL_ARC3',
                'SAL_OBS1','SAL_OBS2','SAL_STATUS1','SAL_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                'FECHA_M','FECHA_M2','IP_M','LOGIN_M', 'SAL_DOCHIS', 'SAL_COMMENT')
                            ->where('FOLIO',$id)
                            ->first();
            if($regremision->count() <= 0){
                toastr()->error('No existen registros de documento.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            }
            return view('sicinar.remision_documentos.editarSalida',compact('nombre','usuario','regperiodos','regmeses','regdias','regremision','regpersonal','regtema','regrespuesta'));
        }
    
        public function actionActualizarSalida(remisionRequest $request, $id){
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
            $regremision = regRemisionModel::where('FOLIO',$id);
            if($regremision->count() <= 0)
                toastr()->error('No existe documento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{        
                //********************** Actualizar ********************************/
                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
                $mes2 = regMesesModel::ObtMes($request->mes_id2);
                $dia2 = regDiasModel::ObtDia($request->dia_id2);             
                $sp   = regPersonalModel::Obtsp($request->cve_sp); 
                
               
    
                $regremision = regRemisionModel::where('FOLIO',$id)        
                                ->update([      
                    'SAL_NOFICIO'   => substr(trim(strtoupper($request->sal_noficio)),0,19),
                    'CVE_SP'        => $request->cve_sp,
                    'CVE_SPSEG'     => $request->cve_spseg,
                    'UADMON_ID'     => $sp[0]->uadmon_id,
                    'UADMON_IDSEG'  => $sp[0]->uadmon_id2,
    
                    'SAL_FEC_OFIC'  => date('Y/m/d', strtotime($request->periodo_id1.'/'.$mes1[0]->mes_mes.'/'.trim($dia1[0]->dia_desc) )),
                    'SAL_FEC_OFIC2' => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1), 
                    'SAL_FEC_OFIC3' => date('Y/m/d', strtotime($request->periodo_id1.'/'.$mes1[0]->mes_mes.'/'.trim($dia1[0]->dia_desc) )),
                    'PERIODO_ID1'   => $request->periodo_id1,
                    'MES_ID1'       => $request->mes_id1,
                    'DIA_ID1'       => $request->dia_id1,
                    'SAL_FEC_RECIB' => date('Y/m/d', strtotime($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.trim($dia2[0]->dia_desc) )),
                    'SAL_FEC_RECIB2'=> trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2), 
                    'SAL_FEC_RECIB3'=> date('Y/m/d', strtotime($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.trim($dia2[0]->dia_desc) )),                
                    'PERIODO_ID2'   => $request->periodo_id2,                
                    'MES_ID2'       => $request->mes_id2,
                    'DIA_ID2'       => $request->dia_id2,
                    'TEMA_ID'       => $request->tema_id,

                    // Columnas //1- No. de respuesta al oficio, 2.- Expediente archivo
                    'SAL_OBS2'      => $request->sal_obs2,
                    'SAL_OBS1'      => $request->sal_obs1,                                 
    
                    'SAL_DESTIN'    => substr(trim(strtoupper($request->sal_destin)) ,0,  99),
                    'SAL_REMITEN'   => substr(trim(strtoupper($request->sal_remiten)),0,  99),
                    'SAL_ASUNTO'    => substr(trim(strtoupper($request->sal_asunto)) ,0,3999),
                    'SAL_COMMENT'   => substr(trim(strtoupper($request->sal_comment)),0, 999),
                    'SAL_UADMON'    => substr(trim(strtoupper($request->sal_uadmon)) ,0,  99),        
                    //'STATUS_1'    => $request->status_1,
    
                    'IP_M'          => $ip,
                    'LOGIN_M'       => $nombre,
                    'FECHA_M2'      => date('Y/m/d'),    //date('d/m/Y')                                
                    'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                toastr()->success('documento actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        17;    //Actualizar 
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
            return redirect()->route('versalida', [$request->periodo_id2]);
        }


        public function actionEditarSalidaFormato($id){

            $nombre        = session()->get('userlog');
            $pass          = session()->get('passlog');
            if($nombre == NULL AND $pass == NULL){
                return view('sicinar.login.expirada');
            }
            $usuario       = session()->get('usuario');
            $rango         = session()->get('rango');
            $arbol_id      = session()->get('arbol_id');     
            $depen_id     = session()->get('depen_id');   
    
            $getCurrentYear = date('Y');


            $regtema      = regTemaModel::select('TEMA_ID','TEMA_DESC')
                            ->orderBy('TEMA_DESC','asc')
                            ->get(); 

            $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                            ->get();  

            $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                            ->get();   

            $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                            ->get();    

           
            if(session()->get('rango') !== '0'){                          
                $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                               ->orderBy('NOMBRE_COMPLETO','ASC')
                               ->get();                                                        
            }else{
                $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                               ->where('UADMON_ID',$depen_id)
                               ->get();                                  
            }     
                $regremision = regRemisionModel::select('PERIODO_ID','FOLIO','SAL_FOLIO',
                'SAL_NOFICIO','SAL_DESTIN','SAL_REMITEN','SAL_ASUNTO','SAL_UADMON','SAL_QUIENENV',
                'SAL_QUIENENV2OP', 'CVE_SP','CVE_SPSEG','UADMON_ID','UADMON_IDSEG','SAL_RESP','SAL_FEC_OFIC','SAL_FEC_OFIC2','SAL_FEC_OFIC3',
                'PERIODO_ID1','MES_ID1','DIA_ID1','SAL_FEC_RECIB','SAL_FEC_RECIB2','SAL_FEC_RECIB3',
                'PERIODO_ID2','MES_ID2','DIA_ID2','TEMA_ID','SAL_ARC1','SAL_ARC2','SAL_ARC3',
                'SAL_OBS1','SAL_OBS2','SAL_STATUS1','SAL_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                'FECHA_M','FECHA_M2','IP_M','LOGIN_M', 'SAL_DOCHIS', 'SAL_COMMENT')
                            ->where('FOLIO',$id)
                            ->first();
            if($regremision->count() <= 0){
                toastr()->error('No existen registros de documento.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            }
            return view('sicinar.remision_documentos.editarSalidaformato',compact('nombre','usuario','regperiodos','regmeses','regdias','regremision','regpersonal','regtema','regrespuesta','getCurrentYear'));
        }

        public function actionActualizarPDF(Request $response, remision1Request $request, $id){
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
    
           
            $regremision = regRemisionModel::where('FOLIO',$id);
            if($regremision->count() <= 0)
                toastr()->error('No existe documento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{        
              
                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
                $mes2 = regMesesModel::ObtMes($request->mes_id2);
                $dia2 = regDiasModel::ObtDia($request->dia_id2);             
                $sp   = regPersonalModel::Obtsp($request->cve_sp);    
                
                $currentYear = date('Y');

                $fileName =null;
                if(isset($request->sal_arc1)){
                    if(!empty($request->sal_arc1)){
                        //Comprobar  si el campo act_const tiene un archivo asignado:
                        if($request->hasFile('sal_arc1')){
                            $fileName=$request->periodo_id.'_'.$id.'_'.$request->file('sal_arc1')->getClientOriginalName();
                            //sube el archivo a la carpeta del servidor public/images/
                            $request->file('sal_arc1')->move(public_path().'/storage/', $fileName);
                        }
                    }
                }

                if((!empty($request->sal_arc1))){
                    $regremision = regRemisionModel::where('FOLIO',$id)        
                        ->update([      
                             'CVE_SP'        => $request->cve_sp,
                             'CVE_SPSEG'     => $request->cve_spseg,
                             'UADMON_ID'     => $sp[0]->uadmon_id,
                             'UADMON_IDSEG'  => $sp[0]->uadmon_id2,

        
                            'TEMA_ID'       => $request->tema_id,                                

                            'IP_M'          => $ip,
                            'LOGIN_M'       => $nombre,
                            'FECHA_M2'      => date('Y/m/d'),    //date('d/m/Y')                                
                            'FECHA_M'       => date('Y/m/d'),    //date('d/m/Y')
                            'SAL_ARC1'      =>$fileName,
                            'SAL_DOCHIS'    =>$fileName
                            
                        ]);
                        toastr()->success('documento actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        }else{
                            $regremision = regRemisionModel::where('FOLIO',$id)        
                            ->update([      
                                 'CVE_SP'        => $request->cve_sp,
                                'CVE_SPSEG'     => $request->cve_spseg,
                                'UADMON_ID'     => $sp[0]->uadmon_id,
                                'UADMON_IDSEG'  => $sp[0]->uadmon_id2,

                
                                'TEMA_ID'       => $request->tema_id,                                

                                'IP_M'          => $ip,
                                'LOGIN_M'       => $nombre,
                                'FECHA_M2'      => date('Y/m/d'),    //date('d/m/Y')                                
                                'FECHA_M'       => date('Y/m/d'),    //date('d/m/Y')   
            
                                   ]);
                                   toastr()->success('documento actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        }

              
               
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        17;    //Actualizar 
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
            return redirect()->route('versalida', [$response->periodo_id]);
        }
    
        public function actionBorrarSalida(Request $request, $id){
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
    
            //************ Eliminar ********************************//
            $regremision = regRemisionModel::where('FOLIO',$id);
            $curretn = date('Y');
            if($regremision->count() <= 0)
                toastr()->error('No existe documento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{        
                $regremision->delete();
                toastr()->success('documento eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
    
                //echo 'Ya entre a borrar registro..........';
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        18;     // Baja 
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
                    toastr()->success('Trx de eliminar de documento registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   //************ Bitacora termina *************************************//                
            }       //************* Termina de eliminar documento ***********************//
            return redirect()->route('versalida', [$curretn]);
        }    
    
    
        // Gráfica demanda de transacciones (Bitacora)
        public function Bitacora(){
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
    
            // http://www.chartjs.org/docs/#bar-chart
            $regbitatxmes=regBitacoraModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                       ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                       ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                                       ->join('JP_CAT_MESES'    ,'JP_CAT_MESES.MES_ID'        ,'=','JP_BITACORA.MES_ID')
                             ->select('JP_BITACORA.MES_ID','JP_CAT_MESES.MES_DESC')
                             ->selectRaw('COUNT(*) AS TOTALGENERAL')
                             ->groupBy('JP_BITACORA.MES_ID','JP_CAT_MESES.MES_DESC')
                             ->orderBy('JP_BITACORA.MES_ID','asc')
                             ->get();        
            $regbitatot=regBitacoraModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                       ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                       ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 1 THEN 1 END) AS M01')  
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 2 THEN 1 END) AS M02')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 3 THEN 1 END) AS M03')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 4 THEN 1 END) AS M04')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 5 THEN 1 END) AS M05')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 6 THEN 1 END) AS M06')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 7 THEN 1 END) AS M07')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 8 THEN 1 END) AS M08')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 9 THEN 1 END) AS M09')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =10 THEN 1 END) AS M10')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =11 THEN 1 END) AS M11')
                             ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =12 THEN 1 END) AS M12')
                             ->selectRaw('COUNT(*) AS TOTALGENERAL')
                             ->get();
    
            $regbitacora=regBitacoraModel::join('JP_CAT_PROCESOS' ,'JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                         ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                         ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                        ->select('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID', 'JP_BITACORA.PROCESO_ID', 
                                    'JP_CAT_PROCESOS.PROCESO_DESC', 'JP_BITACORA.FUNCION_ID', 'JP_CAT_FUNCIONES.FUNCION_DESC', 
                                    'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 1 THEN 1 END) AS ENE')  
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 2 THEN 1 END) AS FEB')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 3 THEN 1 END) AS MAR')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 4 THEN 1 END) AS ABR')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 5 THEN 1 END) AS MAY')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 6 THEN 1 END) AS JUN')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 7 THEN 1 END) AS JUL')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 8 THEN 1 END) AS AGO')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 9 THEN 1 END) AS SEP')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =10 THEN 1 END) AS OCT')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =11 THEN 1 END) AS NOV')
                        ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =12 THEN 1 END) AS DIC')                   
                        ->selectRaw('COUNT(*) AS SUMATOTAL')
                        ->groupBy('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID','JP_BITACORA.PROCESO_ID', 
                                  'JP_CAT_PROCESOS.PROCESO_DESC','JP_BITACORA.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC', 
                                  'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC')
                        ->orderBy('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID','JP_BITACORA.PROCESO_ID', 
                                  'JP_CAT_PROCESOS.PROCESO_DESC','JP_BITACORA.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC',
                                  'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC','asc')
                        ->get();
            //dd($procesos);
            return view('sicinar.numeralia.bitacora',compact('regbitatxmes','regbitacora','regbitatot','nombre','usuario','rango'));
        }
    
      
    
            


        public function buscar(){}
        public function nueva(){}
        public function alta(){}

    }