<?php

namespace App\Http\Controllers;

// Libreria para enviar emails
//use Illuminate\Support\Facades\Mail;
use Mail;

//use App\resource\views\sicinar\mails\send_email;

use App\Http\Requests\usuarioRequest;
use App\Http\Requests\altaUsuarioRequest;
use App\Http\Requests\autoregaltaUsuRequest;

use Illuminate\Http\Request;

use App\regusuariosModel;
use App\regUAdmonModel;
use App\regPersonalModel;
//use App\regdepenModel;
use App\regBitacoraModel;

//use App\claseMailModel;

class usuariosController extends Controller
{

    /**************************** AUTO REGISTRO DEL USUARIO ***********************/
    public function actionAutoregUsu(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        //if($nombre == NULL AND $pass == NULL){
        //    return view('sicinar.login.expirada');
        //}
        $usuario      = session()->get('usuario');
        $llave        = session()->get('llave');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');

        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS','IP','LOGIN')
                        ->get();
        $regusuarios  = regusuariosModel::select('PERIODO_ID','FOLIO','NOMBRE_COMPLETO',
                                                 'LOGIN','PASSWORD','TELEFONO','OSC_DESC')                        
                        ->get();
        return view('sicinar.BackOffice.autoregUsu',compact('nombre','usuario','rango','llave','regosc','regusuarios'));
    }

    public function actionRegaltaUsu(autoregaltaUsuRequest $request){
        //dd($request->all());
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        $llave      = session()->get('llave');
        //if($nombre == NULL AND $pass == NULL){
        //    return view('sicinar.login.expirada');
        //}
        $usuario    = session()->get('usuario');
        $rango      = session()->get('rango');
        $ip         = session()->get('ip');
        $arbol_id   = session()->get('arbol_id');

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

        // **************** Validar usuario ***********************//
        $existe = regusuariosModel::where('LOGIN'   ,strtolower($request->usuario))
                                  ->where('PASSWORD',$request->password);
        //dd($existe, 'Folio:'.$request->folio,'login:'.$request->usuario,'password:'.$request->password);
        if($existe->count()>=1){
            toastr()->error('Ya existe usuario:'.trim(strtolower($request->usuario)).' ','Por favor consultar con el administrador del sistema. Tel.: 722 226 01 82, 84 y 85 ext. 5916',['positionClass' => 'toast-bottom-right'],['timeOut' => '7000']);
            toastr()->error('Ya existe usuario:'.trim(strtolower($request->usuario)).' ','Por favor consultar con el administrador del sistema. Tel.: 722 226 01 82, 84 y 85 ext. 5916',['positionClass' => 'toast-bottom-right'],['progressBar' => 'true']);

            session()->forget('userlog');
            session()->forget('passlog');
            session()->forget('usuario','ip','rango','llave');
            return view('sicinar.login.loginInicio');        
        } else {  
            // **************** Validar si ya esta registrada OSC ***********************//
            $regosc   = regOscModel::where('OSC_DESC',trim(strtoupper($request->osc_desc)) );
            if($regosc->count() > 0) {
                toastr()->error('Ya existe OSC: '.trim(strtoupper($request->osc_desc)).' ','Por favor consultar con el administrador del sistema. Tel.: 722 226 01 82, 84 y 85 ext. 5916',
                               ['positionClass'  => 'toast-bottom-right'],
                               ['showDuration'   =>'400' ],
                               ['hideDuration'   =>'1000'],
                               ['timeOut'        =>'7000'],
                               ['extendedTimeOut'=>'1000']);
                session()->forget('userlog');
                session()->forget('passlog');
                session()->forget('usuario','ip','rango','llave');
                //return view('sicinar.login.terminada');
                return view('sicinar.login.loginInicio');        
            } else {                

            // ********* Se autoregistra razon social si no existe *************
            //            if($request->perfil == '1')
            //                $tp = 'CA';
            //            else
            $tp = 'PG';
            $replegal = strtoupper(Trim($request->nombre).' '.Trim($request->paterno).' '.Trim($request->materno));
            $loginn   = strtolower($request->usuario);
            $oscc     = substr(Trim(strtoupper($request->osc_desc)),0,99);
            $tel      = Substr(Trim($request->telefono),0,59);
            $psw      = $request->password;            

            $osc_id   = regOscModel::max('OSC_ID');
            $osc_id   = $osc_id+1;

            $nuevaosc = new regOscModel();
            $nuevaosc->OSC_ID    = $osc_id;
            $nuevaosc->OSC_DESC  = substr(trim(strtoupper($request->osc_desc)),0,99);
        
            $nuevaosc->IP        = $ip;
            $nuevaosc->LOGIN     = strtolower($request->usuario);  // Usuario ;
        
            $nuevaosc->save();
            if($nuevaosc->save() == true){
                //toastr()->success('OSC autoregistrada.','ok!',['positionClass' => 'toast-bottom-right']);

                //dd($request->all());
                $folio        = regusuariosModel::max('FOLIO');
                $folio        = $folio+1;

                $nuevoUsuario = new regusuariosModel();
                $nuevoUsuario->PERIODO_ID     = date('Y');
                $nuevoUsuario->FOLIO         = $folio; 

                $nuevoUsuario->CVE_ARBOL     = $osc_id;
                $nuevoUsuario->LOGIN         = strtolower($request->usuario);
                $nuevoUsuario->PASSWORD      = $request->password;
                $nuevoUsuario->AP_PATERNO    = substr(Trim(strtoupper($request->paterno)),0,79);
                $nuevoUsuario->AP_MATERNO    = substr(Trim(strtoupper($request->materno)),0,79);
                $nuevoUsuario->NOMBRES       = substr(Trim(strtoupper($request->nombre)) ,0,79);
                $nuevoUsuario->NOMBRE_COMPLETO = $replegal;
                $nuevoUsuario->TIPO_USUARIO  = $tp;
                $nuevoUsuario->OSC_DESC      = substr(Trim(strtoupper($request->osc_desc)),0,99);
                $nuevoUsuario->TELEFONO      = Substr(Trim($request->telefono)            ,0,59);
                $nuevoUsuario->STATUS_1      = '0';       // 0 = OSC
                $nuevoUsuario->STATUS_2      = '0';
        
                $nuevoUsuario->IP            = $ip;
                $nuevoUsuario->FECHA_REGISTRO= date('Y/m/d');
                if($nuevoUsuario->save() == true){
                    //toastr()->success('Usuario creado.','Ok!',['positionClass' => 'toast-bottom-right']);

                    /************ Bitacora inicia *************************************/ 
                    setlocale(LC_TIME, "spanish");        
                    $xip          = session()->get('ip');
                    $xperiodo_id  = (int)date('Y');
                    $xprograma_id = 1;
                    $xmes_id      = (int)date('m');
                    $xproceso_id  =         6;
                    $xfuncion_id  =      6004;
                    $xtrx_id      =        21;    //Autenticación

                    $regbitacora = regBitacoraModel::select('PERIODO_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $folio])
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
                    }else{                   
                        //*********** Obtine el no. de veces *****************************
                        $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'MES_ID'     => $xmes_id, 
                                                              'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                                              'TRX_ID' => $xtrx_id, 'FOLIO' => $folio])
                                     ->max('NO_VECES');
                        $xno_veces = $xno_veces+1;                        
                        //*********** Termina de obtener el no de veces *****************************         
                        $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID'     => $xmes_id, 
                                            'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,
                                            'TRX_ID'     => $xtrx_id,     'FOLIO'      => $folio])
                                   ->update([
                                             'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'     => $regbitacora->IP       = $ip,
                                             'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                        //toastr()->success('Autoregistro Usr registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }   /************ Bitacora termina *************************************/ 
                }else{
                    toastr()->error('Auto registro de usr no creado.','Error inesperado!',['positionClass' => 'toast-bottom-right']);
                }
            }else{
                toastr()->error('Auto registro de OSC no creado.','Error inesperado!',['positionClass' => 'toast-bottom-right']);
            } 
            
            $strVal = (string)$folio;
            toastr()->success('Folio asigando por el sistema ',$strVal,['positionClass' => 'toast-bottom-right'],['timeOut' => '9000']);
            //toastr()->info(   'Folio asignado por el sistema:',$folio ,['positionClass' => 'toast-bottom-right'],['timeOut' => '9000']);
            //return redirect()->route('emailregistro',array($folio,strtolower($request->usuario)) );

            //return view('sicinar.mails.verBienve', compact('nombre','usuario','rango','folio','replegal','loginn','psw','oscc','tel'));      
            //return view('sicinar.mails.verBienve', array($folio,$replegal,$loginn,$psw,$oscc,$tel) );      
            //return redirect()->route('emailbienve',array('folio'   => $folio,
            //                                             'replegal'=> $nombre_completo,
            //                                             'login'   => strtolower($request->usuario), 
            //                                             'ocs'     => substr(Trim(strtoupper($request->osc_desc)),0,99),
            //                                             'telefono'=> Substr(Trim($request->telefono),0,59)
            //                                            )
            //                        );
            return redirect()->route('editarbienve',array($folio) );
            //dd('ya mostre......');
            //return view('sicinar.login.loginInicio');
            } /************ Termina de validar si ya existe razon social ***********/        
        }     /************ Termina de validar si ya existe el usuario y psw *******/        
    }         /************ TERMINA AUTO REGISTRO DEL USUARIO **********************/
 
    public function actionEditarBienve($id){
        $nombre    = session()->get('userlog');
        $pass      = session()->get('passlog');
        $llave     = session()->get('llave');        
        //if($nombre == NULL AND $pass == NULL){
        //    return view('sicinar.login.expirada');
        //}
        $usuario   = session()->get('usuario');
        //$id_estructura = rtrim($id_estruc," ");
        $rango     = session()->get('rango');
        $arbol_id  = session()->get('arbol_id');

        $reguser   = regusuariosModel::select('PERIODO_ID','FOLIO','NOMBRE_COMPLETO','LOGIN','PASSWORD',
                     'DEPEN_ID','TELEFONO','OSC_DESC')
                     ->where('FOLIO',$id)
                     ->first();        
        return view('sicinar.mails.editarBienve',compact('nombre','usuario','llave','rango','reguser'));
    }
 
    public function actionLogin(usuarioRequest $request){
    	//dd($request->all()); 
        $existe = regusuariosModel::select('FOLIO','LOGIN','PASSWORD','TIPO_USUARIO','DEPEN_ID','CVE_ARBOL','STATUS_1')
            //->where('FOLIO'   ,'='   ,    $request->folio)
            ->where('LOGIN'   ,'like','%'.$request->usuario.'%')
            ->where('PASSWORD','like','%'.$request->password.'%')
            ->get();
    	//dd($existe, 'Folio:'.$request->folio,'login:'.$request->usuario,'password:'.$request->password);
        if($existe->count()>=1){
            //dd('Entra if.');
    	    if(strcmp($existe[0]->login,$request->usuario) == 0){
    	        if(strcmp($existe[0]->password,$request->password) == 0){
                    //dd('Entro.');
                }else{
                    return back()->withInput()->withErrors(['PASSWORD' => 'Contraseña incorrecta.']);
                }
            }else{
                return back()->withInput()->withErrors(['LOGIN' => 'Usuario -'.$request->usuario.'- incorrecto.']);
            }
        }
        //dd($existe, 'Folio:'.$request->folio,'login:'.$request->usuario,'password:'.$request->password);
        if($existe->count()>=1){
            //****************** Obtener la OSC ****************
            $arbol_id =0;
            //***************************************************
    		if($existe[0]->status_1 == '4'){  //Super administrador
    			$usuario           = "SuperAdministrador";
    		}else{
                if($existe[0]->status_1 == '3'){ //Administrador
                    $usuario           = "Administrador";
                }else{
                    if($existe[0]->status_1 == '2'){ //Particular
                        $usuario            = "Particular";
                    }else{
                        if($existe[0]->status_1 == '1'){ //operativo UNIDADES ADMINISTRATIVAS
                            $usuario       = "Operativo";
                        }else{
                            if($existe[0]->status_1 == '0'){ //IAP
                                $usuario       = "General";
                            }else{                            
                                return back()->withInput()->withErrors(['LOGIN' => 'Usuario o password incorrecto.']);
                            }
                        }
                    }
                }
    		}
            //$id_estructura = $existe[0]->estrucgob_id;
            $depen_id        = $existe[0]->depen_id;                        
            $llave           = $request->folio;
            $nombre          = $request->usuario;
            $trami           = $request->tramite;
            $rango           = $existe[0]->status_1;
            $depen_id        = $existe[0]->depen_id;  
            $arbol_id        = $existe[0]->cve_arbol; 
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
    		session(['userlog' => $request->usuario,'passlog' => $request->password,'usuario' => $usuario, 'ip' => $ip, 'rango' => $rango, 'arbol_id'=>$arbol_id, 'llave'=>$request->folio,'depen_id'=>$depen_id]);
            //dd('Usuario: '.$usuario.' - Rango: '.$rango.' - Estructura: '.$estructura.'- Dependencia: '.$dependencia.' - Nombre dependencia: '.$nombre_dependencia);
    		toastr()->info($nombre,'Bienvenido ');
            //dd($existe, 'Hola Folio:'.$request->folio,'login:'.$request->usuario,'password:'.$request->password);
    		return view('sicinar.menu.menuInicio',compact('usuario','nombre','rango','llave','depen_id','arbol_id'));
    	}else{
    		return back()->withInput()->withErrors(['LOGIN' => 'Usuario no esta dado de alta.']);
    	}
    }

    public function actionCerrarSesion(){
        session()->forget('userlog');
        session()->forget('passlog');
        session()->forget('usuario','ip','rango','llave','depen_id');
        //REGRESA AL LOGIN PRINCIPAL
        //return view('sicinar.login.terminada');
        return view('sicinar.login.loginInicio');
    }

    public function actionExpirada(){
        session()->forget('userlog');
        session()->forget('passlog');
        session()->forget('usuario','ip','rango','llave','depen_id');        
    	return view('sicinar.login.expirada');
    }

    public function actionNuevoUsuario(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc  = session()->get('id_estructura');
        $llave        = session()->get('llave');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');
        $depen_id     = session()->get('depen_id');

        $reguadmon    = regUAdmonModel::select('UADMON_ID','UADMON_DESC')
                        ->orderBy('UADMON_DESC','ASC')
                        ->get();      
        $regpersonal  = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->orderBy('NOMBRE_COMPLETO','ASC')
                        ->get();                             
        $reguser      = regusuariosModel::select('*')
                        ->get();                                          
        return view('sicinar.BackOffice.nuevoUsuario',compact('nombre','usuario','rango','llave','depen_id','reguadmon','reguser','regpersonal'));
    }

    public function actionAltaUsuario(altaUsuarioRequest $request){
        //dd($request->all());
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        //$llave      = session()->get('llave');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $rango      = session()->get('rango');
        $ip         = session()->get('ip');
        $arbol_id   = session()->get('arbol_id');
        $depen_id   = session()->get('depen_id');

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

        if($request->perfil == '4')
            $tp = 'SA';
        else
            if($request->perfil == '3')
                $tp = 'AD';
            else
                if($request->perfil == '2')
                    $tp = 'EJ';
                else
                    if($request->perfil == '1')
                        $tp = 'CA';
                    else
                        $tp = 'PG';

        //if($request->perfil == '1' AND $request->unidad == '0'){
        //    return back()->withErrors(['unidad' => 'No puedes elegir la Unidad Administrativa: ADMINISTRADOR si tiene rol OPERATIVO.']);
        //} 
        //dd($request->all());
        $folio        = regusuariosModel::max('FOLIO');
        $folio        = $folio+1;
        $nuevoUsuario = new regusuariosModel();
        $nuevoUsuario->PERIODO_ID    = date('Y');
        $nuevoUsuario->FOLIO         = $folio; 
        $nuevoUsuario->DEPEN_ID      = $request->depen_id;
        $nuevoUsuario->CVE_ARBOL     = $request->cve_arbol;

        $nuevoUsuario->LOGIN         = strtolower($request->usuario);
        $nuevoUsuario->PASSWORD      = $request->password;
        $nuevoUsuario->AP_PATERNO    = strtoupper($request->paterno);
        $nuevoUsuario->AP_MATERNO    = strtoupper($request->materno);
        $nuevoUsuario->NOMBRES       = strtoupper($request->nombre);
        $nuevoUsuario->NOMBRE_COMPLETO = strtoupper(TRIM($request->nombre).' '.Trim($request->paterno).' '.Trim($request->materno));
        $nuevoUsuario->TIPO_USUARIO  = $tp;
        $nuevoUsuario->STATUS_1      = $request->perfil;
        $nuevoUsuario->STATUS_2      = 1;
        //$nuevoUsuario->EMAIL       = strtolower($request->usuario);
        $nuevoUsuario->IP            = $ip;
        $nuevoUsuario->FECHA_REGISTRO= date('Y/m/d');
        if($nuevoUsuario->save() == true){
            toastr()->success('El Usuario ha sido creado correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verUsuarios');

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         6;
            $xfuncion_id  =      6004;
            $xtrx_id      =        22;    //Alta

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $folio])
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
                    toastr()->success('Trx de Usr dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado al dar de alta trx usr en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $folio])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID'     => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,
                                        'TRX_ID'     => $xtrx_id,     'FOLIO'      => $folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Trx de Usr actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/ 
        }else{
            toastr()->error('El Usuario no ha sido creado.','Ha ocurrido algo inesperado!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verUsuarios');
    }
 
    public function actionBuscarUsuario(Request $request)
    {
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $rango      = session()->get('rango');
        $ip         = session()->get('ip');
        $arbol_id   = session()->get('arbol_id');
        $depen_id   = session()->get('depen_id');      

        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        //********* Validar rol de usuario **********************/
        $name    = $request->get('name');   
        $nameosc = $request->get('nameosc');           
        $login   = $request->get('login');  
        $idd     = $request->get('idd');  
        //$bio   = $request->get('bio');             
        if(session()->get('rango') !== '0'){    
            $reguadmon= regUAdmonModel::select('UADMON_ID','UADMON_DESC')
                        ->orderBy('UADMON_ID','ASC')
                        ->get();   
            $usuarios = regusuariosModel::select('OFIPA_CTRL_ACCESO.*')
                        ->orderBy('FOLIO','ASC')
                        ->name($name)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                        ->nameosc($nameosc)     //Metodos personalizados
                        ->login($login)         //Metodos personalizados
                        ->idd($idd)
                        ->paginate(30);                 
        }else{                
            $reguadmon= regUAdmonModel::select('UADMON_ID','UADMON_DESC')
                        ->where(  'UADMON_ID',$depen_id)
                        ->orderBy('UADMON_ID','ASC')
                        ->get();                        
            $usuarios = regusuariosModel::select('OFIPA_CTRL_ACCESO.*')
                        ->where(  'DEPEN_ID',$depen_id)
                        ->orderBy('FOLIO'   ,'ASC')
                        ->name($name)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                        ->nameosc($nameosc)     //Metodos personalizados
                        ->login($login)         //Metodos personalizados
                        ->idd($idd)
                        ->paginate(30);                         
        }
        if($usuarios->count() <= 0){
            toastr()->error('No existen usuarios del sistema.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.BackOffice.verUsuarios', compact('nombre','usuario','usuarios','depen_id','reguadmon','arbol_id'));
    }

    public function actionVerUsuario(){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        $llave      = session()->get('llave');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $rango      = session()->get('rango');
        $arbol_id   = session()->get('arbol_id');
        $depen_id   = session()->get('depen_id');      

        $regpersonal  = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->orderBy('NOMBRE_COMPLETO','ASC')
                        ->get();   
        if(session()->get('rango') !== '0'){    
            $reguadmon=regUAdmonModel::select('UADMON_ID','UADMON_DESC')
                       ->orderBy('UADMON_ID','ASC')
                       ->get();   
            $usuarios= regusuariosModel::select('FOLIO','NOMBRE_COMPLETO','DEPEN_ID','LOGIN','PASSWORD','CVE_ARBOL','STATUS_1','STATUS_2')
                       ->orderBy('FOLIO','ASC')
                       ->paginate(50);        
        }else{            
            $reguadmon=regUAdmonModel::select('UADMON_ID','UADMON_DESC')
                       ->where(  'UADMIN_ID',$depen_id)
                       ->orderBy('UADMON_ID','ASC')
                       ->get();                                  
            $usuarios= regusuariosModel::select('FOLIO','NOMBRE_COMPLETO','DEPEN_ID','LOGIN','PASSWORD','CVE_ARBOL','STATUS_1','STATUS_2')
                       ->where(  'DEPEN_ID',$depen_id)
                       ->orderBy('FOLIO'   ,'ASC')
                       ->paginate(50);            
        }
        if($usuarios->count() <= 0){
            toastr()->error('No existen usuarios del sistema.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.BackOffice.verUsuarios',compact('nombre','usuario','rango','llave','depen_id','usuarios','reguadmon','regpersonal'));
    }

    public function actionEditarUsuario($id){
        $nombre    = session()->get('userlog');
        $pass      = session()->get('passlog');
        $llave     = session()->get('llave');        
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario   = session()->get('usuario');
        //$id_estructura = rtrim($id_estruc," ");
        $rango     = session()->get('rango');
        $arbol_id  = session()->get('arbol_id');
        $depen_id  = session()->get('depen_id');              

        $reguadmon  = regUAdmonModel::select('UADMON_ID','UADMON_DESC')
                      ->orderBy('UADMON_ID','ASC')
                      ->get();   
        $regpersonal= regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                      ->orderBy('NOMBRE_COMPLETO','ASC')
                      ->get();                        
        $user       = regusuariosModel::select('FOLIO','NOMBRES','AP_PATERNO','AP_MATERNO','LOGIN','PASSWORD','STATUS_1','STATUS_2','DEPEN_ID')
                      ->where('FOLIO',$id)
                      ->first();        
        return view('sicinar.BackOffice.editarUsuario',compact('nombre','usuario','llave','rango','depen_id','user','reguadmon','regpersonal'));
    }

    public function actionActualizarUsuario(altaUsuarioRequest $request, $id){
        //dd($request->all());
        $nombre    = session()->get('userlog');
        $pass      = session()->get('passlog');
        $llave     = session()->get('llave');                
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
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
        $arbol_id  = session()->get('arbol_id');
        $depen_id  = session()->get('depen_id');  

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6004;
        $xtrx_id      =        23;    //Actualizar 

        $regbitacora  = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID',
                                                 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 
                                                 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
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
               toastr()->success('Trx de usr actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error en trx de usr al actulizar en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                  'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
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
            toastr()->success('Trx de usr actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        if($request->perfil == '4')
            $tp = 'SA';
        else
            if($request->perfil == '3')
                $tp = 'AD';
            else
                if($request->perfil == '2')
                    $tp = 'EJ';
                else
                    if($request->perfil == '1')
                        $tp = 'CA';
                    else
                        $tp = 'PG';
        $actUser = regusuariosModel::where('FOLIO',$id)
                   ->update([
                             'DEPEN_ID'        => $request->depen_id,
                             'CVE_ARBOL'       => $request->cve_arbol,

                             'LOGIN'           => strtolower($request->usuario),
                             'PASSWORD'        => $request->password,
                             'AP_PATERNO'      => strtoupper($request->paterno),
                             'AP_MATERNO'      => strtoupper($request->materno),
                             'NOMBRES'         => strtoupper($request->nombre),
                             'NOMBRE_COMPLETO' => strtoupper(Trim($request->nombre).' '.Trim($request->paterno).' '.Trim($request->materno)),
                             'TIPO_USUARIO'    => $tp,
                             'STATUS_1'        => $request->perfil,
                             'STATUS_2'        => $request->status_2
                            ]);
        toastr()->success('Trx de usuario actualizada.','Ok!',['positionClass' => 'toast-bottom-right']);
        return redirect()->route('verUsuarios');
    }

    public function actionBorrarUsuario($id){
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
        $trami        = session()->get('trami');        

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
            
        /************ Elimina el usuario **************************************/
        $usuarios = regusuariosModel::where('FOLIO',$id);
        if($usuarios->count() <= 0)
            toastr()->error('No existe el usuario.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $usuarios->delete();
            toastr()->success('Usuario eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         6;
            $xfuncion_id  =      6004;
            $xtrx_id      =        24;     // Baja 

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
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
                    toastr()->success('Trx de eliminar usr registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error en trx de eliminar usr en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                               ->update([
                                          'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                          'IP_M'    => $regbitacora->IP           = $ip,
                                          'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                          'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Trx de eliminar usr registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }  /************ Bitacora termina *************************************/     
        }      /************* Termina de eliminar  el usuario **********************************/
        return redirect()->route('verUsuarios');
    }    

    // enviar email de bienvenida
    public function actionEmailRegistro($id, $id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        //dd('entra al email....'.$nombre,' psw:'.$pass, ' Folio:'.$id);
        //if($nombre == NULL AND $pass == NULL){
        //    return view('sicinar.login.expirada');
        //}
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');    
        $trami        = session()->get('trami');            
        
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6004;
        $xtrx_id      =        27;            // Enviar email de bienvendia
        
        $regbitacora  = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
               toastr()->success('Trx de email de bienvenida registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error Trx de envio de emial de bienvendia en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx de email de bienvenida registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /********************** Bitacora termina *************************************/  

        $user = regusuariosModel::select('FOLIO','NOMBRE_COMPLETO','LOGIN','PASSWORD','OSC_DESC','TELEFONO',
                                         'CVE_DEPENDENCIA','CVE_ARBOL')
                ->where('FOLIO',$id)        
                ->get();    
        //dd('Reg.:'.$user,' Folio:'.$id);       
        if($user->count() <= 0){
            toastr()->error('No existe usuario.','Uppss!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verProgtrab');
        }else{
            //return redirect()->route('verUsuarios');
            
            //return view('sicinar.mails.send_email',compact('nombre','usuario','llave','rango','user'));
            //Mail::to($request->user())->send(new OrderShipped($order));

            //$email = $request->input('email');
            //$email = $request->input($id2);
            //Mail::to($id2)->send(new claseMailModel($user) );
            //return back()->with('success', 'email enviado exitosamente!');
            //Mail::send('sicinar.mails.send_email', $user);
            //{
            //    $message->subject('Trámite de registro en Padrón Estatal');
            //    $message->to($id2);
                //return back()->with('success', 'email enviado exitosamente!');
            //});            

            // Send confirmation code
            //Mail::send('sicinar.mails.send_email', $user, function($message) use ($user) {
            //     $message->to($user['login'], $user['nombre_completo'])->subject('Por favor confirma tu correo');
            //});

            //Mail::to($request->user())
            //      ->cc($moreUsers)
            //      ->bcc($evenMoreUsers)
            //      ->send(new OrderShipped($order));

            $name = 'El primer correo electrónico que envié'; 
            // 1. Enviar plantilla de correo electrónico
            Mail::send('sicinar.mails.send_email',['name'=>$name,$id],function($message){ 
                $to = 'sbbz650620@hotmail.com';
                $message->to($to)->subject('Prueba de correo'); 
            }); 
            Session::flash('message','Mensaje enviado correctamente');
            // https://www.youtube.com/watch?v=T1AqxgKM4OM
            // Se devuelve una matriz de error, utilícela para determinar si la transmisión es exitosa
            if(count(Mail::failures()) < 1){
                echo 'Envíe el correo electrónico con éxito, verifique! '; 
            }else{
                echo 'No se pudo enviar el correo. Vuelve a intentarlo. ';
            }         

            /**
            * Enviar correo electrónico de texto sin formato
            */
            //$data = array('name'=>"Our Code World");
            //** Ruta o nombre de la plantilla de hoja que se va a representar
            //$template_path = 'sicinar.mails.send_email';
            //Mail::send(['text'=> $template_path ], $data, function($message) {
                //** Configure el destinatario y el asunto del correo.
                //**$message->to('sbbz650620@hotmail.com', 'silverio baltazar')->subject('Laravel First Mail');
                //$message->to('sbbz650620@hotmail.com')->subject('Laravel First Mail');
                //** Establecer el remitente
                //**$message->from('sbbz650620@gmail.com','Our Code World');
            //});
            //return "Correo electrónico básico enviado, revise su bandeja de entrada.";
            // error Expected response code 250 but got code "530", with message "530 5.7.1 Authentication required "

        }   
        return view('sicinar.login.loginInicio');     
        //return Excel::download(new ExportProgtrabExcel, 'Programa_Trabajo_'.date('d-m-Y').'.xlsx');
    }


    public function actionActivarUsuario($id){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $activar = regusuariosModel::where('FOLIO',$id)
            ->update([
                'STATUS_2' => '1'
            ]);
        return redirect()->route('verUsuarios');
    }

    public function actionDesactivarUsuario($id){
        $nombre = session()->get('userlog');
        $pass   = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $activar = regusuariosModel::where('FOLIO',$id)
            ->update([
                'STATUS_2' => '0'
            ]);
        return redirect()->route('verUsuarios');
    }
}
