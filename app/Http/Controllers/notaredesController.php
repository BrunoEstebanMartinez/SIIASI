<?php
//**************************************************************/
//* File:       notaredesController.php
//* Función:    Notas periodisticas de redes sociales
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: febrero 2023
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\notaredRequest;
use App\Http\Requests\notared1Request;

use App\regBitacoraModel;
use App\regTemaModel;
use App\regDiasModel;
use App\regMesesModel;
use App\regRedSocialModel;
use App\regTiponotaModel;
use App\regPeriodosModel;
use App\regNotaredsocialModel;

// Exportar a excel 
use App\Exports\ExporNotasredExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class notaredesController extends Controller
{

    public function actionBuscarNotared(Request $request)
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

        $regtemas     = regTemaModel::select('TEMA_ID','TEMA_DESC')
                        ->get(); 
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                  
        $regtiponota  = regTiponotaModel::select('TIPON_ID','TIPON_DESC')
                        ->get();    
        $regredes    = regRedSocialModel::select('RS_ID','RS_DESC')
                        ->get();  
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//       
        $todo      = $request->get('todo');  
        $periodo   = $request->get('periodo');
        $arbol      =$request->get('arbol'); 

        if(session()->get('rango') !== '0'){    
            $regnotaredes = regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                            ->orderBy('PERIODO_ID','DESC')
                            ->orderBy('RS_FOLIO'  ,'DESC')
                            ->iddSal($periodo)
                            ->idTodo($todo)  
                            ->paginate(40); 
        }else{
            $regnotaredes = regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                            //->where(  'UADMON_ID' ,$depen_id)
                            ->orderBy('PERIODO_ID','DESC')   
                            ->orderBy('RS_FOLIO'  ,'DESC')                          
                            ->iddSal($periodo)
                            ->idTodo($todo) 
                            ->paginate(40);   
        }                                                                          
        if($regnotaredes->count() <= 0){
            toastr()->error('No existen nota de red social.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.notas_redes_sociales.verNotasred', compact('nombre','usuario','regperiodos','regmeses','regdias','regnotaredes','regtiponota','regtemas','regredes'));
    }

    public function actionVerNotasred(){
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
        $regredes    = regRedSocialModel::select('RS_ID','RS_DESC')
                        ->get();                          
        $histPeriodos = regNotaredsocialModel::select('PERIODO_ID')
                        ->DISTINCT()
                        ->GET();                   
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            $regnotaredes= regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('RS_FOLIO'     ,'DESC')
                           ->paginate(40);
        }else{                  
            $regnotaredes= regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           //->where(  'UADMON_ID' ,$depen_id)            
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('RS_FOLIO'  ,'DESC')  
                           ->paginate(40);          
        }                        
        if($regnotaredes->count() <= 0){
            toastr()->error('No existen notas de red social.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_redes_sociales.verNotasred',compact('nombre','usuario','regperiodos','regtiponota','regtemas','histPeriodos','regredes','regnotaredes')); 
    }

    public function isWithYearAction($ANIO){
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
        $histPeriodos = regNotaredsocialModel::select('PERIODO_ID')
                        ->DISTINCT()
                        ->GET();              
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            //$regpersonal =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //              ->get(); 
            $regnnotamedio= regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(  'PERIODO_ID'  ,$ANIO) 
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('RS_FOLIO'  ,'DESC')
                           ->paginate(40);
           
        }else{                  
            //$regpersonal = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //               ->where('UADMON_ID',$depen_id)
            //               ->get();  
            $regnnotamedio= regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(  'PERIODO_ID'  ,$ANIO) 
                           //->where('UADMON_ID' ,$depen_id)            
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('RS_FOLIO'  ,'DESC')  
                           ->paginate(40);          
        }                        
        if($regnnotamedio->count() <= 0){
            toastr()->error('No existen notas de red social.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_redes_sociales.verNotasred',compact('nombre','usuario','regperiodos','regnnotamedio','regpersonal','regtema','histPeriodos','ANIO')); 
    }

    public function actionNuevaNotared(){
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
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();    
        $regtiponota  = regTiponotaModel::select('TIPON_ID','TIPON_DESC')
                        ->where('TIPON_ID','<>',0)        
                        ->get();    
        $regredes    = regRedSocialModel::select('RS_ID','RS_DESC')
                        ->where('RS_ID','<>',0)        
                        ->get();                          
        $histPeriodos = regNotaredsocialModel::select('PERIODO_ID')
                        ->DISTINCT()
                        ->GET(); 
        //if(session()->get('rango') !== '0'){         
        //    $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
        //                   ->orderBy('NOMBRE_COMPLETO','ASC')
        //                   ->get();                                                        
        //}else{
        //    $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
        //                   ->orderBy('NOMBRE_COMPLETO','ASC')
        //                   ->where('UADMON_ID',$depen_id)
        //                   ->get();                                  
        //}     
        $regnotaredes = regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                         ->get();
        return view('sicinar.notas_redes_sociales.nuevaNotared',compact('nombre','usuario','regperiodos','regmeses','regdias','regtema','regtiponota','regredes','histPeriodos','regnotaredes'));
    }

    public function actionAltanuevaNotared(Request $request){
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

        // *************** Validar duplicidad ***********************************/
        $duplicado = regNotaredsocialModel::where(['RS_TITULO' => TRIM($request->rs_titulo),'RS_NOTA' => TRIM($request->rs_nota)])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['RS_NOTA' => 'NOTA '.$request->rs_nota.' Ya existe la nota de red social. Por favor verificar.']);
        else{  
            /************ ALTA  *****************************/ 
            //if(!empty($request->mes_d1) and !empty($request->dia_d1) ){
                ////toastr()->error('muy bien 1.....','¡ok!',['positionClass' => 'toast-bottom-right']);
                //$mes1 = regMesesModel::ObtMes($request->mes_id1);
                //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //}   //endif

            $mes1  = regMesesModel::ObtMes($request->mes_id1);
            $dia1  = regDiasModel::ObtDia($request->dia_id1);                
            $red   = regRedSocialModel::ObtRed($request->rs_id);             
            $tipon = regTiponotaModel::ObtTipon($request->tipon_id);             

            $folio = regNotaredsocialModel::max('RS_FOLIO');
            $folio = $folio + 1;
 
            $file1 =null;
            //if(isset($request->ent_arc1)){
            //    if(!empty($request->ent_arc1)){
            //        //Comprobar  si el campo act_const tiene un archivo asignado:
            //        if($request->hasFile('ent_arc1')){
            //            $file1=$request->periodo_id.'_'.$folio.'_'.$request->file('ent_arc1')->getClientOriginalName();
            //            //sube el archivo a la carpeta del servidor public/images/
            //            $request->file('ent_arc1')->move(public_path().'/storage/', $file1);
            //        }
            //    }
            //}     
            $nuevorecepcion = new regNotaredsocialModel();
            $nuevorecepcion->PERIODO_ID    = $request->periodo_id;             
            $nuevorecepcion->RS_FOLIO      = $folio;
            $nuevorecepcion->RS_TITULO     = substr(trim($request->rs_titulo)  ,0,3499);
            $nuevorecepcion->RS_NOTA       = substr(trim($request->rs_nota)    ,0,3999);
            $nuevorecepcion->RS_NOTA2      = substr(trim($request->rs_nota2)   ,0,3999);
            $nuevorecepcion->RS_IA         = substr(trim($request->rs_ia)      ,0,3999);            
            $nuevorecepcion->RS_LINK       = substr(trim($request->rs_link)    ,0,1999);
            $nuevorecepcion->RS_AUTOR      = substr(trim($request->rs_autor)   ,0,  79);
            $nuevorecepcion->RS_ID         = $request->rs_id;            
            $nuevorecepcion->RS_DESC       = substr(trim($red[0]->rs_desc),0,  79); 
            //$nuevorecepcion->TIPON_ID    = $request->tipon_id;            
            //$nuevorecepcion->TIPON_DESC  = substr(trim($tipon[0]->tipon_desc),0,  79);             

            $nuevorecepcion->RS_FEC_NOTA   = date('Y/m/d', strtotime(trim($request->datepickerOf)));
            $nuevorecepcion->RS_FEC_NOTA2  = date('d/m/Y', strtotime(trim($request->datepickerOf)));
            $nuevorecepcion->RS_FEC_NOTA3  = date('Y/m/d H:i:s', strtotime(trim($request->datepickerOf) ));

            $nuevorecepcion->PERIODO_ID1   = $request->periodo_id1;                
            $nuevorecepcion->MES_ID1       = $request->mes_id1;                
            $nuevorecepcion->DIA_ID1       = $request->dia_id1;      
            $nuevorecepcion->RS_LIKES      = $request->rs_likes;
            $nuevorecepcion->RS_REPOSTEOS  = $request->rs_reposteos;
            $nuevorecepcion->RS_COMEN      = $request->rs_comen;
            $nuevorecepcion->RS_ALCANCE    = $request->rs_alcance;
            $nuevorecepcion->RS_CALIF      = $request->rs_calif;                                                

            //$nuevorecepcion->ENT_ARC1    = $file1;
        
            $nuevorecepcion->IP            = $ip;
            $nuevorecepcion->LOGIN         = $nombre;         // Usuario ;
            $nuevorecepcion->save();
            if($nuevorecepcion->save() == true){
                toastr()->success('nota de red social dada de alta.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3001;
                $xtrx_id      =         1;    //Alta
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
                        toastr()->success('Trx de nota de red social dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx. de nota de red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                    toastr()->success('Trx de nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
            }else{
                toastr()->error('Error en Trx nota de red social Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }       //**************** Termina la alta       *****************/
        }           //**************** Termina de validar duplicado **********/
        return redirect()->route('vernotasred');
    } 

    public function actionEditarNotared($id){
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
        $regredes    = regRedSocialModel::select('RS_ID','RS_DESC')
                        ->where('RS_ID','<>',0)
                        ->get();                          
        $histPeriodos = regNotaredsocialModel::select('PERIODO_ID')
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
        $regnotaredes = regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where('RS_FOLIO',$id)
                        ->first();
        if($regnotaredes->count() <= 0){
            toastr()->error('No existe nota de red social.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_redes_sociales.editarNotared',compact('nombre','usuario','regperiodos','regmeses','regdias','regtema','regtiponota','regredes','histPeriodos','regnotaredes'));
    }

    public function actionActualizarNotared(notaredRequest $request, $id){
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
        $regnotaredes = regNotaredsocialModel::where('RS_FOLIO',$id);
        if($regnotaredes->count() <= 0)
            toastr()->error('No existe nota de red social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $mes1  = regMesesModel::ObtMes($request->mes_id1);
            $dia1  = regDiasModel::ObtDia($request->dia_id1); 
            $red   = regRedSocialModel::ObtRed($request->rs_id);             
            $tipon = regTiponotaModel::ObtTipon($request->tipon_id);             

            $regnotaredes = regNotaredsocialModel::where('RS_FOLIO',$id)        
                            ->update([      
                                      'RS_TITULO'    => substr(trim($request->rs_titulo)  ,0,3499),
                                      'RS_NOTA'      => substr(trim($request->rs_nota)    ,0,3999),
                                      'RS_NOTA2'     => substr(trim($request->rs_nota2)   ,0,3999),
                                      'RS_IA'        => substr(trim($request->rs_ia)      ,0,3999),            
                                      'RS_LINK'      => substr(trim($request->rs_link)    ,0,1999),
                                      'RS_AUTOR'     => substr(trim($request->rs_autor)   ,0,  79),
                                      'RS_ID'        => $request->rs_id,
                                      'RS_DESC'      => substr(trim($red[0]->rs_desc),0,  79),
                                      //'TIPON_ID'   => $request->tipon_id,
                                      //'TIPON_DESC' => substr(trim($tipon[0]->tipon_desc),0,  79),

                                      'RS_FEC_NOTA'  => date('Y/m/d', strtotime(trim($request->datepickerOf))),
                                      'RS_FEC_NOTA2' => trim($request->dia_id1.'/'.$request->mes_id1.'/'.$request->periodo_id1), 
                                      'RS_FEC_NOTA3' => date('Y/m/d', strtotime(trim($request->datepickerOf))),

                                      'PERIODO_ID1'  => $request->periodo_id1,
                                      'MES_ID1'      => $request->mes_id1,            
                                      'DIA_ID1'      => $request->dia_id1,      

                                      'RS_LIKES'     => $request->rs_likes, 
                                      'RS_REPOSTEOS' => $request->rs_reposteos, 
                                      'RS_COMEN'     => $request->rs_comen, 
                                      'RS_ALCANCE'   => $request->rs_alcance, 
                                      'RS_CALIF'     => $request->rs_calif,                                  

                                      'IP_M'         => $ip,
                                      'LOGIN_M'      => $nombre,
                                      'FECHA_M2'     => date('Y/m/d'),    //date('d/m/Y')                                
                                      'FECHA_M'      => date('Y/m/d')     //date('d/m/Y')                                
                                      ]);
            toastr()->success('nota de red social actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =         2;    //Actualizar 
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
                    toastr()->success('Trx actualización de nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de actualización de nota de red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de actualización de nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('vernotasred');
    }

    public function actionEditarNotared1($id){
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
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
        }else{
        }     
        $regnotaredes = regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where('RS_FOLIO',$id)
                        ->first();
        if($regnotaredes->count() <= 0){
            toastr()->error('No existe nota de red social.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_redes_sociales.editarNotared1',compact('nombre','usuario','regperiodos','regnotaredes','regpersonal','regtema'));
    }

    public function actionActualizarNotared1(notared1Request $request, $id){
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
        $regnotaredes = regNotaredsocialModell::where('RS_FOLIO',$id);
        if($regnotaredes->count() <= 0)
            toastr()->error('No existe nota de red social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //********************** Actualizar nota de red social ***************************/
            $name01 =null;
            if($request->hasFile('rs_foto1')){
                $name01 = $id.'_'.$request->file('rs_foto1')->getClientOriginalName(); 
                $request->file('rs_foto1')->move(public_path().'/storage/', $name01);

                $regnotaredes = regNotaredsocialModel::where('RS_FOLIO',$id)        
                                ->update([                
                                          'RS_FOTO1' => $name01,

                                          'IP_M'     => $ip,
                                          'LOGIN_M'  => $nombre,
                                          'FECHA_M2' => date('Y/m/d'),    //date('d/m/Y')
                                          'FECHA_M'  => date('Y/m/d')    //date('d/m/Y')                                
                                          ]);
                toastr()->success('Archivo digital actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);


                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3001;
                $xtrx_id      =         2;    //Actualizar 
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
                        toastr()->success('Trx actualización de nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error Trx de actualización de nota de red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                    toastr()->success('Trx de actualización de nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/                     
            }   //*** Termina actualizar arc. digital ************************//                
        }       /************ Actualizar *******************************************/
        return redirect()->route('vernotasred');
    }


    public function actionBorrarNotared($id){
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

        //************ Eliminar nota de red social ********************************//
        $regnotaredes = regNotaredsocialModel::where('RS_FOLIO',$id);
        if($regnotaredes->count() <= 0)
            toastr()->error('No existe nota de red social.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regnotaredes->delete();
            toastr()->success('nota de red social eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =         3;     // Baja 
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
                    toastr()->success('Trx de elimiar nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de Trx al eliminar nota de red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de elimiar nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   //************ Bitacora termina *************************************//                
        }       //************* Termina de eliminar nota de red social ***********************//
        return redirect()->route('vernotasper');
    }    

    // exportar a formato excel
    public function actionExporNotasredesExcel($id){
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
        $xfuncion_id  =      3001;
        $xtrx_id      =         4;            // Exportar a formato Excel
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
               toastr()->success('Trx de exportar a excel nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error Trx de exportar a excel nota de red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
            toastr()->success('Trx de exportar a excel nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /********************** Bitacora termina *************************************/  
        return Excel::download(new ExporNotasredExcel, 'recepcion_nota de red socials_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportNotasredesPDF($id,$id2){
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
        $xfuncion_id  =      3001;
        $xtrx_id      =         5;       //Exportar a formato PDF
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
               toastr()->success('Trx de exportar a PDF nota de red social registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de exportar a excel nota de red social. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
            toastr()->success('Trx de exportar a excel nota de red social actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regtema      = regTemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get(); 
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                     
        $regnotaredes = regNotaredsocialModel::select('PERIODO_ID','RS_FOLIO','RS_TITULO','RS_NOTA','RS_NOTA2','RS_IA','RS_IA2',
                            'RS_LINK','RS_ID','RS_DESC','RS_AUTOR','RS_LIKES','RS_REPOSTEOS','RS_COMEN','RS_ALCANCE','RS_CALIF',
                            'RS_FEC_NOTA','RS_FEC_NOTA2','RS_FEC_NOTA3','PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC',
                            'RS_FOTO1','RS_FOTO2','RS_FOTO3','RS_FOTO4','RS_OBS1','RS_OBS2','RS_STATUS1','RS_STATUS2',
                            'FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->get();                                                                          
        $regnotaredes = regNotaredsocialModel::join('OFIPA_CAT_UADMON' ,'OFIPA_CAT_UADMON.UADMON_ID','=',
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
        if($regnnotamedio->count() <= 0){ 
            toastr()->error('No existe nota de red social.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }else{
            $pdf = PDF::loadView('sicinar.pdf.NotasperPdf',compact('nombre','usuario','regtema','regnnotamedio','regpersonal','regmeses','regttema'));
            //$options = new Options();
            //$options->set('defaultFont', 'Courier');
            //$pdf->set_option('defaultFont', 'Courier');
            $pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');          
            //$pdf->setPaper('A4','portrait');

            // Output the generated PDF to Browser
            return $pdf->stream('recepcion_nota de red socials-'.$id2);
        }
    }


    // Gráfica por estado
    public function IapxEdo(){
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

        $regtotxedo=regdepenModel::join('JP_CAT_ENTIDADES_FED',[['JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','OFIPA_PERSONAL.ENTIDADFEDERATIVA_ID'],['OFIPA_PERSONAL.DEPEN_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXEDO')
                               ->get();

        $regdepen=regdepenModel::join('JP_CAT_ENTIDADES_FED',[['JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','OFIPA_PERSONAL.ENTIDADFEDERATIVA_ID'],['OFIPA_PERSONAL.DEPEN_ID','<>',0]])
                      ->selectRaw('OFIPA_PERSONAL.ENTIDADFEDERATIVA_ID, JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                        ->groupBy('OFIPA_PERSONAL.ENTIDADFEDERATIVA_ID', 'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                        ->orderBy('OFIPA_PERSONAL.ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxedo',compact('regdepen','regtotxedo','nombre','usuario','rango'));
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

    // Gráfica de IAP por Rubro social
    public function IapxRubro2(){
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
        return view('sicinar.numeralia.graficadeprueba',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
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

    // Mapas
    public function Mapas2(){
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
        return view('sicinar.numeralia.mapasdeprueba2',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }

    // Mapas
    public function Mapas3(){
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
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba3',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }





}
