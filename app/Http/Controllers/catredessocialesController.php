<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\redRequest;
use App\Http\Requests\red1Request;
use App\regRedsocialModel;
use App\regBitacoraModel;
// Exportar a excel 
use App\Exports\ExcelExportRedes;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;

class catredessocialesController extends Controller
{

    public function actionVerRedes(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');

        $regredes   =regRedsocialModel::select('RS_ID','RS_DESC','RS_LINK','RS_FOTO1','RS_FOTO2',
                                               'RS_OBS1','RS_OBS2','RS_STATUS','FECREG')
                    ->orderBy('RS_ID','ASC')
                    ->paginate(50);
        if($regredes->count() <= 0){
            toastr()->error('No existen registros.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.catalogos.verRedes',compact('nombre','usuario','regredes'));
    }

    public function actionNuevaRed(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');

        $regredes = regRedsocialModel::select('RS_ID','RS_DESC','RS_LINK','RS_FOTO1','RS_FOTO2',
                                              'RS_OBS1','RS_OBS2','RS_STATUS','FECREG')
                   ->orderBy('RS_ID','asc')
                   ->get();
        return view('sicinar.catalogos.nuevaRed',compact('nombre','usuario','regredes'));
    }

    public function actionAltaNuevaRed(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

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
        // **************** duplicados ******************************//
        $regredes = regRedsocialModel::where('RS_ID',$request->rs_id);
        if($regredes->count() > 0)
            toastr()->error('Ya existe red social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            /************ Alta ************************************/   

            $rs_id = regRedsocialModel::max('RS_ID');
            $rs_id = $rs_id+1;

            $file1 =null;
            if(isset($request->rs_foto1)){
                if(!empty($request->rs_foto1)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('rs_foto1')){
                        $file1=$rs_id.'_'.$request->file('rs_foto1')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('rs_foto1')->move(public_path().'/storage/',$file1);
                    }
                }
            }     

            $nuevorubro = new regRedsocialModel();
            $nuevorubro->RS_ID    = $rs_id;
            $nuevorubro->RS_DESC  = substr(trim($request->rs_desc),0,99);
            //$nuevorubro->RS_LINK= substr(trim($request->rs_link),0,99);
            //$nuevorubro->RS_OBS1= substr(trim($request->rs_obs1),0,99);
            $nuevorubro->RS_FOTO1 = $file1;

            $nuevorubro->save();

            if($nuevorubro->save() == true){
                toastr()->success('red social dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip         = session()->get('ip');
                $xperiodo_id = (int)date('Y');
                $xprograma_id= 1;
                $xmes_id     = (int)date('m');
                $xproceso_id =         6;
                $xfuncion_id =      6002;
                $xtrx_id     =        43;
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO'      => $rs_id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $rs_id;       // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $xip;            // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('red social dado de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id, 
                                                          'MES_ID'     => $xmes_id,    'PROCESO_ID'  => $xproceso_id, 
                                                          'FUNCION_ID' => $xfuncion_id,'TRX_ID'      => $xtrx_id,
                                                          'FOLIO'      => $rs_id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                            'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id,
                                            'FOLIO'      => $rs_id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $xip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('red social actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
            }else{
                toastr()->error('Error en red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   /*** Termina alta **********************/
        }       /****** Valida duplicados *********************/
        return redirect()->route('verredes');
    }

    public function actionEditarRed($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regredes   =regRedsocialModel::where('RS_ID',$id)
                     ->first();
        if($regredes->count() <= 0){
            toastr()->error('No existe red social.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.catalogos.editarRed',compact('nombre','usuario','regredes'));
    }

    public function actionActualizarRed(redRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

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

        // **************** actualizar ******************************
        $regredes = regRedsocialModel::where('RS_ID',$id);
        if($regredes->count() <= 0)
            toastr()->error('No existe red social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regredes = regRedsocialModel::where('RS_ID',$id)        
                       ->update([
                                 'RS_DESC'    => substr(trim($request->rs_desc),0,99)
                                 //'RS_LINK'    => substr(trim($request->rs_link),0,99),  
                                 //'RS_OBS1'    => substr(trim($request->rs_obs1),0,99)
                                 //'RS_STATUS'  => $request->rs_status
                                ]);
            toastr()->success('red social actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         6;
            $xfuncion_id  =      6002;
            $xtrx_id      =        44;     //actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                           'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO'      => $id])
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
                $nuevoregBitacora->IP         = $xip;            // IP
                $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 
                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de red social dado de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trx de red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $xip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de alta de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verredes');
    }

    public function actionEditarRed1($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regredes   =regRedsocialModel::where('RS_ID',$id)
                     ->first();
        if($regredes->count() <= 0){
            toastr()->error('No existe red social.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.catalogos.editarRed1',compact('nombre','usuario','regredes'));
    }

    public function actionActualizarRed1(red1Request $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

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

        // **************** actualizar ******************************
        $regredes = regRedsocialModel::where('RS_ID',$id);
        if($regredes->count() <= 0)
            toastr()->error('No existe red social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name01 =null;
            if($request->hasFile('rs_foto1')){
                $name01 = $id.'_'.$request->file('rs_foto1')->getClientOriginalName(); 
                $request->file('rs_foto1')->move(public_path().'/storage/', $name01);

                $regredes = regRedsocialModel::where('RS_ID',$id)        
                           ->update([
                                     'RS_FOTO1'     => $name01
                                    ]);
                toastr()->success('red social actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         6;
                $xfuncion_id  =      6002;
                $xtrx_id      =        44;     //actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO'      => $id])
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
                    $nuevoregBitacora->IP         = $xip;            // IP
                    $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de red social dado de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx de red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                            'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                            'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $xip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Trx de alta de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/                     
            }       //*** Termina validar foto ************************//
        }           /************ Termina de actualizar ********************************/
        return redirect()->route('verredes');
    }

    public function actionBorrarRed($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regredes = regRedsocialModel::where('RS_ID',$id);
        if($regredes->count() <= 0)
            toastr()->error('No existe red social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regredes->delete();
            toastr()->success('red social eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         6;
            $xfuncion_id  =      6002;
            $xtrx_id      =        45;

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO'      => $id])
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
                $nuevoregBitacora->IP         = $xip;            // IP
                $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 
                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de eliminar red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trx de eliminar red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,  'TRX_ID' => $xtrx_id, 
                                        'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $xip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de eliminar red social actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/          
        }
        return redirect()->route('verredes');
    }    

    // exportar a formato excel
    public function actionExportRedesExcel(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6002;
        $xtrx_id      =        46;            // Exportar a formato Excel
        $id           =         0;

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID','TRX_ID', 
                                                'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                 'FOLIO'      => $id])
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
            $nuevoregBitacora->IP         = $xip;            // IP
            $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Trx de exportar Redes sociales a excel registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de trx de exportar Redes sociales a excel. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                                  'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                  'FOLIO'      => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO'      => $id])
            ->update([
                'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                'IP_M'    => $regbitacora->IP       = $xip,
                'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Trx de exportar Redes sociales a excel actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/  
        return Excel::download(new ExcelExportRedes, 'Cat_RedesSociales_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportRedesPdf(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regredes  = regRedsocialModel::select('RS_ID','RS_DESC','RS_LINK','RS_FOTO1','RS_FOTO2',
                                            'RS_OBS1','RS_OBS2','RS_STATUS','FECREG')
                     ->orderBy('RS_ID','ASC')
                     ->get();
        if($regredes->count() <= 0){
            toastr()->error('No existen registros.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }else{

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         6;
            $xfuncion_id  =      6002;
            $xtrx_id      =        47;       //Exportar a formato PDF
            $id           =         0;
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO'      => $id])
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
                $nuevoregBitacora->IP         = $xip;            // IP
                $nuevoregBitacora->LOGIN      = $nombre;        // Usuario 
                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de exportar Redes sociales a PDF registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trx de exportar Redes sociales a PDF. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M' => $regbitacora->IP           = $xip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de exportar Redes sociales a PDF actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/          
            set_time_limit(0);
            ini_set("memory_limit",-1);
            ini_set('max_execution_time', 0);            
            $pdf = PDF::loadView('sicinar.pdf.RedesPDF', compact('nombre','usuario','regredes'));
            //******** Horizontal ***************
            //$pdf->setPaper('A4', 'landscape');      
            //******** vertical *************** 
            //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
            $pdf->setPaper('letter','portrait');         
            return $pdf->stream('RedesSociales');
        }   /*********** Termina el if ***********************/
    }

}
