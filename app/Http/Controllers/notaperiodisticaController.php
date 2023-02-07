<?php
//**************************************************************/
//* File:       notaperiodisticaController.php
//* Función:    Notas periodisticas de medios
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: enero 2023
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\notaperRequest;
use App\Http\Requests\notaper1Request;

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
use GuzzleHttp\Client;
//use Options;

class notaperiodisticaController extends Controller
{

                private $models = [
                
                    "babbage" => "text-babbage-001",
                    "curies"  => "text-curie-001",
                    "ada"     => "text-ada-001",
                    "davinci" =>  "text-davinci-003"       

            ];

            public $tokenApi =  "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";


            public function openAIAPI(Request $request){


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
                $regmedios    = regmediosModel::select('MEDIO_ID','MEDIO_DESC')
                                ->where('MEDIO_ID','<>',0)        
                                ->get();                          
                $histPeriodos = regNotamediosModel::select('PERIODO_ID')
                                ->DISTINCT()
                                ->GET(); 


                                $regnotamedio = regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                                'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                                'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                                'NM_STATUS1','NM_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                             ->get();

                       
                            $link = $request->nm_link;

                            $testing = "Php es";
                            
                            $maxTokens = 3625;

                        $cliente = new Client();

                //Metodo 1

                      
                        $open_ai_response = $cliente->post('https://api.openai.com/v1/completions', [

                                'headers' => [
                                        'Content-Type' => 'application/json',
                                        'Authorization' => 'Bearer '.$this->tokenApi,
                                ],

                                'json' => [
                                'model' => $this->models['davinci'],   
                                'prompt' => $request->input('nm_link'),
                                'temperature' => 0.7,
                                'max_tokens' => $maxTokens,
                                'top_p'     => 1.0,
                                'frequency_penalty' => 0,
                                'presence_penalty'  => 1
                                ],

                            ]);

                        $responseAI = json_decode($open_ai_response->getBody(), true);

            // Metodo 2

            /*
                        $open_ai_response = Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'Authorization' => "Bearer ".$this->$tokenApi
                            ])->post("https://api.openai.com/v1/engines/$this->models[davinci]/completions", [
                                'prompt' => "Resumen de la nota".$isNote." "."Tl;dr",
                                "temperature" => 0.9,
                                "max_tokens" => $maxTokens,
                                "top_p"     => 1,
                                "frequency_penalty" => 0,
                                "presence_penalty"  => 0
                            ])->json();

                                $open_ai_response['choices'][0]['text'];

                                */



                        return view("sicinar.notas_periodisticas.nuevaNotaper", compact('nombre','usuario','regperiodos','regmeses','regdias','regtema','regtiponota','regmedios','histPeriodos','regnotamedio','responseAI', 'link'));

            }

    public function actionBuscarNotaper(Request $request)
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
        $regmedios    = regmediosModel::select('MEDIO_ID','MEDIO_DESC')
                        ->get();  
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//       
        $todo      = $request->get('todo');  
        $periodo   = $request->get('periodo');
        $arbol      =$request->get('arbol'); 

        if(session()->get('rango') !== '0'){    
            $regnotamedio = regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                            'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                            'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                            'NM_STATUS1','NM_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                            ->orderBy('PERIODO_ID','DESC')
                            ->orderBy('NM_FOLIO'  ,'DESC')
                            ->iddSal($periodo)
                            ->idTodo($todo)  
                            ->paginate(40); 
        }else{
            $regnotamedio = regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                            'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                            'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                            'NM_STATUS1','NM_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                            //->where(  'UADMON_ID' ,$depen_id)
                            ->orderBy('PERIODO_ID','DESC')   
                            ->orderBy('NM_FOLIO'  ,'DESC')                          
                            ->iddSal($periodo)
                            ->idTodo($todo) 
                            ->paginate(40);   
        }                                                                          
        if($regnotamedio->count() <= 0){
            toastr()->error('No existen nota periodisticas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.notas_periodisticas.verNotasper', compact('nombre','usuario','regperiodos','regmeses','regdias','regnotamedio','regtiponota','regtemas','regmedios'));
    }

    public function actionVerNotasper(){
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
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            //$regpersonal =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //              ->get(); 
            $regnotamedio= regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                            'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                            'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                            'NM_STATUS1','NM_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('NM_FOLIO'     ,'DESC')
                           ->paginate(40);
        }else{                  
            //$regpersonal = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //               ->where('UADMON_ID',$depen_id)
            //               ->get();                            
            $regnotamedio= regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                            'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                            'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                            'NM_STATUS1','NM_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           //->where(  'UADMON_ID' ,$depen_id)            
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('NM_FOLIO'  ,'DESC')  
                           ->paginate(40);          
        }                        
        if($regnotamedio->count() <= 0){
            toastr()->error('No existen notas periodisticas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_periodisticas.verNotasper',compact('nombre','usuario','regperiodos','regtiponota','regtemas','histPeriodos','regmedios','regnotamedio')); 
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
        $histPeriodos = regNotamediosModel::select('PERIODO_ID')
                        ->DISTINCT()
                        ->GET(); 

         $regmedios    = regmediosModel::select('MEDIO_ID','MEDIO_DESC')
                        ->get();  

        $regtiponota  = regTiponotaModel::select('TIPON_ID','TIPON_DESC')
                        ->get();                                           
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            //$regpersonal =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //              ->get(); 
            $regnotamedio= regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                            'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                            'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                            'NM_STATUS1','NM_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(  'PERIODO_ID'  ,$ANIO) 
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('NM_FOLIO'  ,'DESC')
                           ->paginate(40);
           
        }else{                  
            //$regpersonal = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
            //               ->where('UADMON_ID',$depen_id)
            //               ->get();  
            $regnotamedio= regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                            'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                            'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                            'NM_STATUS1','NM_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(  'PERIODO_ID'  ,$ANIO) 
                           //->where('UADMON_ID' ,$depen_id)            
                           ->orderBy('PERIODO_ID','DESC')
                           ->orderBy('NM_FOLIO'  ,'DESC')  
                           ->paginate(40);          
        }                        
        if($regnotamedio->count() <= 0){
            toastr()->error('No existen notas periodisticas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_periodisticas.verNotasper',compact('nombre','usuario','regperiodos','regnotamedio','regpersonal','regtema', 'histPeriodos','ANIO', 'regmedios', 'regtiponota')); 
    }

    public function actionNuevaNotaper(){
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
        $regmedios    = regmediosModel::select('MEDIO_ID','MEDIO_DESC')
                        ->where('MEDIO_ID','<>',0)        
                        ->get();                          
        $histPeriodos = regNotamediosModel::select('PERIODO_ID')
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
        $regnotamedio = regNotamediosModel::select('PERIODO_ID','NM_FOLIO','NM_TITULO','NM_NOTA','NM_NOTA2','NM_IA','NM_IA2','NM_LINK',
                            'MEDIO_ID','MEDIO_DESC','TIPON_ID','TIPON_DESC','NM_AUTOR','NM_CALIF','NM_CALIF_IA','NM_FEC_NOTA','NM_FEC_NOTA2','NM_FEC_NOTA3',
                            'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','TEMA_DESC','NM_FOTO1','NM_FOTO2','NM_FOTO3','NM_FOTO4','NM_OBS1','NM_OBS2',
                            'NM_STATUS1','NM_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                         ->get();
        return view('sicinar.notas_periodisticas.nuevaNotaper',compact('nombre','usuario','regperiodos','regmeses','regdias','regtema','regtiponota','regmedios','histPeriodos','regnotamedio'));
    }

    public function actionAltanuevaNotaper(Request $request){
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
        $duplicado = regNotamediosModel::where(['NM_TITULO' => TRIM($request->nm_titulo),'NM_NOTA' => TRIM($request->nm_nota)])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['NM_NOTA' => 'NOTA '.$request->NM_NOTA.' Ya existe la nota periodísticas. Por favor verificar.']);
        else{  
            /************ ALTA  *****************************/ 
            //if(!empty($request->mes_d1) and !empty($request->dia_d1) ){
                ////toastr()->error('muy bien 1.....','¡ok!',['positionClass' => 'toast-bottom-right']);
                //$mes1 = regMesesModel::ObtMes($request->mes_id1);
                //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //}   //endif

            $mes1  = regMesesModel::ObtMes($request->mes_id1);
            $dia1  = regDiasModel::ObtDia($request->dia_id1);                
            $medio = regmediosModel::ObtMedio($request->medio_id);             
            $tipon = regTiponotaModel::ObtTipon($request->tipon_id);             

            $folio = regNotamediosModel::max('NM_FOLIO');
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
            $nuevorecepcion = new regNotamediosModel();
            $nuevorecepcion->PERIODO_ID    = $request->periodo_id;             
            $nuevorecepcion->NM_FOLIO      = $folio;
            $nuevorecepcion->NM_TITULO     = substr(trim($request->nm_titulo)  ,0,3499);
            $nuevorecepcion->NM_NOTA       = substr(trim($request->nm_nota)    ,0,3999);
            $nuevorecepcion->NM_NOTA2      = substr(trim($request->nm_nota2)   ,0,3999);
            $nuevorecepcion->NM_IA         = substr(trim($request->nm_nota_ia) ,0,3999);            
            $nuevorecepcion->NM_LINK       = substr(trim($request->nm_link)    ,0,1999);
            $nuevorecepcion->NM_AUTOR      = substr(trim($request->nm_autor)   ,0,  79);
            $nuevorecepcion->MEDIO_ID      = $request->medio_id;            
            $nuevorecepcion->MEDIO_DESC    = substr(trim($medio[0]->medio_desc),0,  79); 
            $nuevorecepcion->TIPON_ID      = $request->tipon_id;            
            $nuevorecepcion->TIPON_DESC    = substr(trim($tipon[0]->tipon_desc),0,  79);             

            $nuevorecepcion->NM_FEC_NOTA   = date('Y/m/d', strtotime(trim($request->datepickerOf)));
            $nuevorecepcion->NM_FEC_NOTA2  = date('d/m/Y', strtotime(trim($request->datepickerOf)));
            $nuevorecepcion->NM_FEC_NOTA3  = date('Y/m/d H:i:s', strtotime(trim($request->datepickerOf) ));

            $nuevorecepcion->PERIODO_ID1   = $request->periodo_id1;                
            $nuevorecepcion->MES_ID1       = $request->mes_id1;                
            $nuevorecepcion->DIA_ID1       = $request->dia_id1;      
            $nuevorecepcion->NM_CALIF      = $request->nm_calif;                  

            //$nuevorecepcion->ENT_ARC1    = $file1;
        
            $nuevorecepcion->IP            = $ip;
            $nuevorecepcion->LOGIN         = $nombre;         // Usuario ;
            $nuevorecepcion->save();
            if($nuevorecepcion->save() == true){
                toastr()->success('nota periodistica dada de alta.','ok!',['positionClass' => 'toast-bottom-right']);

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
                        toastr()->success('Trx de nota periodistica dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx. de nota periodistica. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                    toastr()->success('Trx de nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
            }else{
                toastr()->error('Error en Trx nota periodistica Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }       //**************** Termina la alta       *****************/
        }           //**************** Termina de validar duplicado **********/
        return redirect()->route('vernotasper');
    } 

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

    public function actionActualizarNotaper(notaperRequest $request, $id){
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
        $regnotamedio = regNotamediosModel::where('NM_FOLIO',$id);
        if($regnotamedio->count() <= 0)
            toastr()->error('No existe nota periodistica.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $mes1  = regMesesModel::ObtMes($request->mes_id1);
            $dia1  = regDiasModel::ObtDia($request->dia_id1); 
            $medio = regmediosModel::ObtMedio($request->medio_id);             
            $tipon = regTiponotaModel::ObtTipon($request->tipon_id);             

            $regnotamedio = regNotamediosModel::where('NM_FOLIO',$id)        
                            ->update([      
                                      'NM_TITULO'    => substr(trim($request->nm_titulo)  ,0,3499),
                                      'NM_NOTA'      => substr(trim($request->nm_nota)    ,0,3999),
                                      'NM_NOTA2'     => substr(trim($request->nm_nota2)   ,0,3999),
                                      'NM_IA'        => substr(trim($request->nm_nota_ia) ,0,3999),            
                                      'NM_LINK'      => substr(trim($request->nm_link)    ,0,1999),
                                      'NM_AUTOR'     => substr(trim($request->nm_autor)   ,0,  79),
                                      'MEDIO_ID'     => $request->medio_id,
                                      'MEDIO_DESC'   => substr(trim($medio[0]->medio_desc),0,  79),
                                      'TIPON_ID'     => $request->tipon_id,
                                      'TIPON_DESC'   => substr(trim($tipon[0]->tipon_desc),0,  79),

                                      'NM_FEC_NOTA'  => date('Y/m/d', strtotime(trim($request->datepickerOf))),
                                      'NM_FEC_NOTA2' => trim($request->dia_id1.'/'.$request->mes_id1.'/'.$request->periodo_id1), 
                                      'NM_FEC_NOTA3' => date('Y/m/d', strtotime(trim($request->datepickerOf))),

                                      'PERIODO_ID1'  => $request->periodo_id1,
                                      'MES_ID1'      => $request->mes_id1,            
                                      'DIA_ID1'      => $request->dia_id1,      
                                      'NM_CALIF'     => $request->nm_calif,                                  

                                      'IP_M'         => $ip,
                                      'LOGIN_M'      => $nombre,
                                      'FECHA_M2'     => date('Y/m/d'),    //date('d/m/Y')                                
                                      'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
            toastr()->success('nota periodística actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

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
                    toastr()->success('Trx actualización de nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de actualización de nota periodistica. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de actualización de nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('vernotasper');
    }

    public function actionEditarRecepcion1($id){
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
        }else{
            $regpersonal  =regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                           ->where('DEPEN_ID',$depen_id)
                           ->get();                                  
        }     
        $regrespuesta = regAtenderrecepModel::select('PERIODO_ID','FOLIO','ENT_FOLIO','ENT_NOFICIO',
                        'ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A','CVE_SP','UADMON_ID',
                        'CVE_SP2','UADMON_ID2','ENT_RESP','ENT_FEC_RESP','ENT_FEC_RESP2','ENT_FEC_RESP3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','TEMA_ID','ENT_ARC1',
                        'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                        'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where('FOLIO',$id)        
                        ->get();                
        $regnnotamedio = regNotamediosModel::select('PERIODO_ID','FOLIO','ENT_FOLIO',
                        'ENT_NOFICIO','ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A',
                        'CVE_SP','UADMON_ID','ENT_RESP','ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3',
                        'PERIODO_ID2','MES_ID2','DIA_ID2','TEMA_ID','ENT_ARC1',
                        'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                        'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where('FOLIO',$id)
                        ->first();
        if($regnotamedio->count() <= 0){
            toastr()->error('No existen registros de nota periodistica.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_periodisticas.editarRecepcion1',compact('nombre','usuario','regperiodos','regmeses','regdias','regnotamedio','regpersonal','regtema','regrespuesta'));
    }

    public function actionActualizarRecepcion1(notaper1Request $request, $id){
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
        $regnnotamedio = regNotamediosModel::where('FOLIO',$id);
        if($regnnotamedio->count() <= 0)
            toastr()->error('No existe nota periodistica.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //********************** Actualizar nota periodistica ***************************/
            $name01 =null;
            if($request->hasFile('ent_arc1')){
                $name01 = $request->periodo_id.'_'.$id.'_'.$request->file('ent_arc1')->getClientOriginalName(); 
                $request->file('ent_arc1')->move(public_path().'/storage/', $name01);

                $regnnotamedio = regNotamediosModel::where('FOLIO',$id)        
                                ->update([                
                                          'ENT_ARC1' => $name01,

                                          'IP_M'     => $ip,
                                          'LOGIN_M'  => $nombre,
                                          'FECHA_M2' => date('Y/m/d'),    //date('d/m/Y')
                                          'FECHA_M'  => date('Y/m/d')    //date('d/m/Y')                                
                                          ]);
                toastr()->success('Archivo digital actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                //*********************** Respuestas ***************************************//
                $regrespuesta=regAtenderrecepModel::where('FOLIO',$id)        
                              ->update([      
                                        'ENT_ARC1'  => $name01,

                                        'IP_M'      => $ip,
                                        'LOGIN_M'   => $nombre,
                                        'FECHA_M2'  => date('Y/m/d'),    //date('d/m/Y')                                
                                        'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                toastr()->success('nota periodistica de respuesta actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   //*** Termina actualización ************************//

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
                    toastr()->success('Trx actualización de nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de actualización de nota periodistica. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de actualización de nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verrecepcion');
    }


    public function actionBorrarNotaper($id){
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

        //************ Eliminar nota periodistica ********************************//
        $regnotamedio = regNotamediosModel::where('NM_FOLIO',$id);
        if($regnotamedio->count() <= 0)
            toastr()->error('No existe nota periodistica.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regnotamedio->delete();
            toastr()->success('nota periodistica eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

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
                    toastr()->success('Trx de elimiar de nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de Trx de elimiar de nota periodistica al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de elimiar de nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   //************ Bitacora termina *************************************//                
        }       //************* Termina de eliminar nota periodistica ***********************//
        return redirect()->route('vernotasper');
    }    

    // exportar a formato excel
    public function actionExportNotasperExcel($id){
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
               toastr()->success('Trx de exportar a excel nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error Trx de exportar a excel nota periodistica. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
            toastr()->success('Trx de exportar a excel nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /********************** Bitacora termina *************************************/  
        return Excel::download(new ExportNotasperExcel, 'recepcion_nota periodisticas_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportNotasperPDF($id,$id2){
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
               toastr()->success('Trx de exportar a PDF nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de exportar a excel nota periodistica. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
            toastr()->success('Trx de exportar a excel nota periodistica actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
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
        $regnotamedio = regNotamediosModel::select('PERIODO_ID','FOLIO','ENT_FOLIO',
                        'ENT_NOFICIO','ENT_DESTIN','ENT_REMITEN','ENT_ASUNTO','ENT_UADMON','ENT_TURNADO_A',
                        'CVE_SP','UADMON_ID','ENT_RESP','ENT_FEC_OFIC','ENT_FEC_OFIC2','ENT_FEC_OFIC3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','ENT_FEC_RECIB','ENT_FEC_RECIB2','ENT_FEC_RECIB3',
                        'PERIODO_ID2','MES_ID2','DIA_ID2','TEMA_ID','ENT_ARC1','ENT_ARC2','ENT_ARC3',
                        'ENT_OBS1','ENT_OBS2','ENT_STATUS1','ENT_STATUS2','FECHA_REG','FECHA_REG2','IP','LOGIN',
                        'FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->get();                                                                          
        $regnotamedio = regNotamediosModel::join('OFIPA_CAT_UADMON' ,'OFIPA_CAT_UADMON.UADMON_ID','=',
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
            toastr()->error('No existe nota periodistica.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }else{
            $pdf = PDF::loadView('sicinar.pdf.NotasperPdf',compact('nombre','usuario','regtema','regnnotamedio','regpersonal','regmeses','regttema'));
            //$options = new Options();
            //$options->set('defaultFont', 'Courier');
            //$pdf->set_option('defaultFont', 'Courier');
            $pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');          
            //$pdf->setPaper('A4','portrait');

            // Output the generated PDF to Browser
            return $pdf->stream('recepcion_nota periodisticas-'.$id2);
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

    //*****************************************************************************//
    //*************************************** Detalle *****************************//
    //*****************************************************************************//
    public function actionVerdNotaper($id){
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

        $regtema   = regtemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();      
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get(); 
        $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->orderBy('DEPEN_ID','asc')
                        ->get();      
        $regpersonal    = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->orderBy('FOLIO','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                                                                                                                     
        //************** Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){           
            $regprogeanual=regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(  'FOLIO'     ,$id)
                           ->orderBy('PERIODO_ID','ASC')
                           ->orderBy('DEPEN_ID1' ,'ASC')
                           ->get();
        }else{                         
            $regprogeanual=regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(['FOLIO' => $id, 'DEPEN_ID1' => $depen_id])
                           ->orderBy('PERIODO_ID','ASC')
                           ->orderBy('DEPEN_ID1' ,'ASC')
                           ->get();
        }                        
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID','TACCION_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','TEMA_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',    
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',                    
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                        ->where(  'FOLIO'  ,$id)            
                        ->orderBy('FOLIO'  ,'asc')
                        ->orderBy('PARTIDA','asc')
                        ->paginate(30);           
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen acciones o metas del nota periodistica.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }                        
        return view('sicinar.notas_periodisticas.verdProganual',compact('nombre','usuario','regdepen','regtipometa','regtema','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regpersonal','regepproy'));
    }

    public function actionNuevodNotaper($id){
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

        $regtema   = regtemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();            
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                                           
        $regpersonal    = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->orderBy('NOMBRE_COMPLETO','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_DESC','asc')
                        ->get();                   
        if(session()->get('rango') !== '0'){                           
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->orderBy('DEPEN_ID','ASC')
                          ->get();                                                        
        }else{
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->where('DEPEN_ID',$depen_id)
                          ->orderBy('DEPEN_ID','ASC')
                          ->get();            
        }    
        $regprogeanual  = regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                          'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                          'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                          'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                          'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                          ->where(  'FOLIO'     ,$id  )            
                          ->orderBy('PERIODO_ID','asc') 
                          ->orderBy('DEPEN_ID1' ,'asc') 
                          ->orderBy('FOLIO'     ,'asc')                        
                          ->get();
        $regprogdanual = regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                         'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID','TACCION_ID',
                         'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                         'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                         'OPERACIONAL_DESC','TEMA_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                         'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                         'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                         'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                         'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                         'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                         'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',
                         'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                         'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                         'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                         'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                         'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                         'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',                         
                         'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                         'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                         ->where(  'FOLIO'     ,$id  )    
                         ->orderBy('PERIODO_ID','asc') 
                         ->orderBy('DEPEN_ID1' ,'asc') 
                         ->orderBy('FOLIO'     ,'asc')
                         ->orderBy('PARTIDA'   ,'asc')
                         ->get();                                
        //dd($unidades);
        return view('sicinar.notas_periodisticas.nuevodProganual',compact('nombre','usuario','regdepen','regtipometa','regtema','regprogeanual','regprogdanual','regepproy','regpersonal','regmeses'));
    }

    public function actionAltaNuevodNotaper(Request $request){
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

        // *************** Validar duplicidad ***********************************/
        //$duplicado = regProgdAnualModel::where(['PERIODO_ID' => $request->periodo_id,
        //                                       'DEPEN_ID'     => $request->DEPEN_ID, 
        //                                       'FOLIO'      => $request->folio])
        //             ->get();
        //if($duplicado->count() <= 0 )
        //    return back()->withInput()->withErrors(['DEPEN_ID' => 'IAP '.$request->DEPEN_ID.' Ya existe nota periodistica en el mismo periodo y con la IAP referida. Por favor verificar.']);
        //else{  

            /******************* Alta **********************/ 
            //$mes1 = regMesesModel::ObtMes($request->mes_id1);
            //$dia1 = regDiasModel::ObtDia($request->dia_id1);   

            // ******** Obtiene partida ************************/
            $partida = regProgdAnualModel::where(['PERIODO_ID'=> $request->periodo_id, 
                                                  'FOLIO'     => $request->folio])
                       ->max('PARTIDA');
                       //          'DEPEN_ID1' => $request->depen_id1, 
            $partida = $partida + 1;

            $file01 =null;
            if(isset($request->soporte_01)){
                if(!empty($request->soporte_01)){
                    if($request->hasFile('soporte_01')){
                        $file01=$request->periodo_id.'_'.$request->folio.'_'.$partida.'_'.$request->file('soporte_01')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('soporte_01')->move(public_path().'/storage/', $file01);
                    }
                }
            }
            //**************** Meta por mes *******************************//
            $mesp_01 = 0;
            if(isset($request->mesp_01)){
                if(!empty($request->mesp_01)) 
                    $mesp_01 = (float)$request->mesp_01;
            }          
            $mesp_02 = 0;
            if(isset($request->mesp_02)){
                if(!empty($request->mesp_02)) 
                    $mesp_02 = (float)$request->mesp_02;
            }  
            $mesp_03 = 0;
            if(isset($request->mesp_03)){
                if(!empty($request->mesp_03)) 
                    $mesp_03 = (float)$request->mesp_03;
            }  
            $mesp_04 = 0;
            if(isset($request->mesp_04)){
                if(!empty($request->mesp_04)) 
                    $mesp_04 = (float)$request->mesp_04;
            }  
            $mesp_05 = 0;
            if(isset($request->mesp_05)){
                if(!empty($request->mesp_05)) 
                    $mesp_05 = (float)$request->mesp_05;
            }                                                  
            $mesp_06 = 0;
            if(isset($request->mesp_06)){
                if(!empty($request->mesp_06)) 
                    $mesp_06 = (float)$request->mesp_06;
            }               
            $mesp_07 = 0;
            if(isset($request->mesp_07)){
                if(!empty($request->mesp_07)) 
                    $mesp_07 = (float)$request->mesp_07;
            }          
            $mesp_08 = 0;
            if(isset($request->mesp_08)){
                if(!empty($request->mesp_08)) 
                    $mesp_08 = (float)$request->mesp_08;
            }  
            $mesp_09 = 0;
            if(isset($request->mesp_09)){
                if(!empty($request->mesp_09)) 
                    $mesp_09 = (float)$request->mesp_09;
            }  
            $mesp_10 = 0;
            if(isset($request->mesp_10)){
                if(!empty($request->mesp_10)) 
                    $mesp_10 = (float)$request->mesp_10;
            }  
            $mesp_11 = 0;
            if(isset($request->mesp_11)){
                if(!empty($request->mesp_11)) 
                    $mesp_11 = (float)$request->mesp_11;
            }                                                  
            $mesp_12 = 0;
            if(isset($request->mesp_12)){
                if(!empty($request->mesp_12)) 
                    $mesp_12 = (float)$request->mesp_12;
            }                           
            $trimp_01 = (float)$mesp_01 + (float)$mesp_02 + (float)$mesp_03;
            $trimp_02 = (float)$mesp_04 + (float)$mesp_05 + (float)$mesp_06;
            $trimp_03 = (float)$mesp_07 + (float)$mesp_08 + (float)$mesp_09;
            $trimp_04 = (float)$mesp_10 + (float)$mesp_11 + (float)$mesp_12;

            $tsemp_01 = (float)$mesp_01 + (float)$mesp_02 + (float)$mesp_03+(float)$mesp_04+(float)$mesp_05+(float)$mesp_06;
            $tsemp_02 = (float)$mesp_07 + (float)$mesp_08 + (float)$mesp_09+(float)$mesp_10+(float)$mesp_11+(float)$mesp_12;            

            //**************** Meta total *******************************//
            $totp_01 = 0;
            if(isset($request->totp_01)){
                if(!empty($request->totp_01)) 
                    $totp_01 = (float)$request->totp_01;
            }          
            //*************** Meta total calculada **********************//
            $totp_02  = (float)$tsemp_01 + (float)$tsemp_02;                        

            $nuevoprogdtrab = new regProgdAnualModel();

            $nuevoprogdtrab->CIPREP_ID      = $request->ciprep_id;
            $nuevoprogdtrab->LGOB_COD       = $request->lgob_cod;
            $nuevoprogdtrab->FOLIO          = $request->folio;
            $nuevoprogdtrab->PARTIDA        = $partida;
            $nuevoprogdtrab->PERIODO_ID     = $request->periodo_id;                            
            $nuevoprogdtrab->DEPEN_ID1      = $request->depen_id1;
            $nuevoprogdtrab->DEPEN_ID2      = $request->depen_id2;            
            $nuevoprogdtrab->EPPROG_ID      = $request->epprog_id;
            $nuevoprogdtrab->EPPROY_ID      = $request->epproy_id;             
            $nuevoprogdtrab->FECHA_ENTREGA  = $request->fecha_entrega; 
            $nuevoprogdtrab->FECHA_ENTREGA2 = $request->fecha_entrega2;
            $nuevoprogdtrab->FECHA_ENTREGA3 = $request->fecha_entrega3;

            $nuevoprogdtrab->ACTIVIDAD_DESC = substr(trim(strtoupper($request->actividad_desc))  ,0,3999);
            $nuevoprogdtrab->OBJETIVO_DESC  = substr(trim(strtoupper($request->objetivo_desc))   ,0,3999);
            $nuevoprogdtrab->OPERACIONAL_DESC=substr(trim(strtoupper($request->operacional_desc)),0,3999);
            $nuevoprogdtrab->TEMA_ID        = $request->tema_id;
            $nuevoprogdtrab->TACCION_ID     = $request->taccion_id;
            $nuevoprogdtrab->MESP_01        = $request->mesp_01;
            $nuevoprogdtrab->MESP_02        = $request->mesp_02;
            $nuevoprogdtrab->MESP_03        = $request->mesp_03;
            $nuevoprogdtrab->MESP_04        = $request->mesp_04;
            $nuevoprogdtrab->MESP_05        = $request->mesp_05;
            $nuevoprogdtrab->MESP_06        = $request->mesp_06;
            $nuevoprogdtrab->MESP_07        = $request->mesp_07;
            $nuevoprogdtrab->MESP_08        = $request->mesp_08;
            $nuevoprogdtrab->MESP_09        = $request->mesp_09;
            $nuevoprogdtrab->MESP_10        = $request->mesp_10;
            $nuevoprogdtrab->MESP_11        = $request->mesp_11;
            $nuevoprogdtrab->MESP_12        = $request->mesp_12;

            $nuevoprogdtrab->TRIMP_01       = $trimp_01;
            $nuevoprogdtrab->TRIMP_02       = $trimp_02;
            $nuevoprogdtrab->TRIMP_03       = $trimp_03;
            $nuevoprogdtrab->TRIMP_04       = $trimp_04;

            $nuevoprogdtrab->TSEMP_01       = $tsemp_01;
            $nuevoprogdtrab->TSEMP_02       = $tsemp_02;

            $nuevoprogdtrab->TOTP_01        = $totp_01;
            $nuevoprogdtrab->TOTP_02        = $totp_02;

            $nuevoprogdtrab->SOPORTE_01     = $file01;
            $nuevoprogdtrab->OBS_01         = substr(trim(strtoupper($request->obs_01)),0,3999);
      
            $nuevoprogdtrab->IP             = $ip;
            $nuevoprogdtrab->LOGIN          = $nombre;         // Usuario ;
            $nuevoprogdtrab->save();
            if($nuevoprogdtrab->save() == true){
                toastr()->success('Acción o meta del nota periodistica dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3001;
                $xtrx_id      =        42;    //Alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                        'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                        'FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                        'TRX_ID'     => $xtrx_id    ,'FOLIO'      => $request->folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->folio;          // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de act. nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de act. nota periodistica. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'      => $request->folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $request->folio])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
            }else{
                toastr()->error('Error en Trx de act. nota periodistica, Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //******************** Termina la alta ***************/
        //}     // ******************* Termina el duplicado **********/
        return redirect()->route('verdpa',$request->folio);
    }

    //******************* Editar *****************
    public function actionEditardNotaper($id, $id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');        

        $regtema   = regtemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();          
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                                             
        $regpersonal    = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->orderBy('FOLIO','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->get();                                                        
        }else{
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->where('DEPEN_ID',$depen_id)
                          ->get();            
        }                    
        $regprogeanual  = regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                          'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                          'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                          'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                          'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                          ->where(  'FOLIO'     ,$id)
                          ->orderBy('PERIODO_ID','ASC')
                          ->orderBy('FOLIO'     ,'ASC')
                          ->get();
        $regprogdanual  = regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                          'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID','TACCION_ID',
                          'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                          'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                          'OPERACIONAL_DESC','TEMA_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                          'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                          'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                          'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                          'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                          'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                          'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                          'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                          'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                          'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                          'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                          'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                          'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',                                                 
                          'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                          'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                          ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                          //->where('FOLIO',$id)
                          //->where('PARTIDA',$id2)
                          ->first();
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del nota periodistica.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_periodisticas.editardProganual',compact('nombre','usuario','regtipometa','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regtema','regepproy','regpersonal'));
    }

    public function actionActualizardNotaper(progdanualRequest $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');   
        $depen_id     = session()->get('depen_id');             

        // **************** actualizar ******************************
        $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del nota periodistica.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            //$mes1 = regMesesModel::ObtMes($request->mes_id1);
            //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //dd('año 1:',$request->periodo_id1, ' año 2:',$request->periodo_id2,' mes1:',$mes1[0]->mes_mes,' dia1:',$dia1[0]->dia_desc,' mes2:',$mes2[0]->mes_mes, ' dia2:',$dia2[0]->dia_desc);
            //************** Valida meta total ************************//
            $totp_01 = 0;
            if(isset($request->totp_01)){
                if(!empty($request->totp_01)) 
                    $totp_01 = (float)$request->totp_01;
            }                      

            //************** Valida meta por mes **********************//
            $mesp_01 = 0;
            if(isset($request->mesp_01)){
                if(!empty($request->mesp_01)) 
                    $mesp_01 = (float)$request->mesp_01;
            }          
            $mesp_02 = 0;
            if(isset($request->mesp_02)){
                if(!empty($request->mesp_02)) 
                    $mesp_02 = (float)$request->mesp_02;
            }  
            $mesp_03 = 0;
            if(isset($request->mesp_03)){
                if(!empty($request->mesp_03)) 
                    $mesp_03 = (float)$request->mesp_03;
            }  
            $mesp_04 = 0;
            if(isset($request->mesp_04)){
                if(!empty($request->mesp_04)) 
                    $mesp_04 = (float)$request->mesp_04;
            }  
            $mesp_05 = 0;
            if(isset($request->mesp_05)){
                if(!empty($request->mesp_05)) 
                    $mesp_05 = (float)$request->mesp_05;
            }                                                  
            $mesp_06 = 0;
            if(isset($request->mesp_06)){
                if(!empty($request->mesp_06)) 
                    $mesp_06 = (float)$request->mesp_06;
            }               
            $mesp_07 = 0;
            if(isset($request->mesp_07)){
                if(!empty($request->mesp_07)) 
                    $mesp_07 = (float)$request->mesp_07;
            }          
            $mesp_08 = 0;
            if(isset($request->mesp_08)){
                if(!empty($request->mesp_08)) 
                    $mesp_08 = (float)$request->mesp_08;
            }  
            $mesp_09 = 0;
            if(isset($request->mesp_09)){
                if(!empty($request->mesp_09)) 
                    $mesp_09 = (float)$request->mesp_09;
            }  
            $mesp_10 = 0;
            if(isset($request->mesp_10)){
                if(!empty($request->mesp_10)) 
                    $mesp_10 = (float)$request->mesp_10;
            }  
            $mesp_11 = 0;
            if(isset($request->mesp_11)){
                if(!empty($request->mesp_11)) 
                    $mesp_11 = (float)$request->mesp_11;
            }                                                  
            $mesp_12 = 0;
            if(isset($request->mesp_12)){
                if(!empty($request->mesp_12)) 
                    $mesp_12 = (float)$request->mesp_12;
            }                           
            $trimp_01 = (float)$mesp_01 + (float)$mesp_02 + (float)$mesp_03;
            $trimp_02 = (float)$mesp_04 + (float)$mesp_05 + (float)$mesp_06;
            $trimp_03 = (float)$mesp_07 + (float)$mesp_08 + (float)$mesp_09;
            $trimp_04 = (float)$mesp_10 + (float)$mesp_11 + (float)$mesp_12;

            $tsemp_01 = (float)$trimp_01 + (float)$trimp_02;
            $tsemp_02 = (float)$trimp_03 + (float)$trimp_04;            

            $totp_02  = (float)$tsemp_01 + (float)$tsemp_02;                        

            $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                             ->update([                
                                       'LGOB_COD'        => $request->lgob_cod,
                                       'CIPREP_ID'       => $request->ciprep_id,
                                       
                                       'ACTIVIDAD_DESC'  => substr(trim(strtoupper($request->actividad_desc))  ,0,3999),
                                       'OBJETIVO_DESC'   => substr(trim(strtoupper($request->objetivo_desc))   ,0,3999),
                                       'OPERACIONAL_DESC'=> substr(trim(strtoupper($request->operacional_desc)),0,3999),
                                       
                                       'TEMA_ID'         => $request->tema_id,
                                       'TACCION_ID'      => $request->taccion_id,
                                       'MESP_01'         => $request->mesp_01,
                                       'MESP_02'         => $request->mesp_02,
                                       'MESP_03'         => $request->mesp_03,
                                       'MESP_04'         => $request->mesp_04,
                                       'MESP_05'         => $request->mesp_05,
                                       'MESP_06'         => $request->mesp_06,
                                       'MESP_07'         => $request->mesp_07,
                                       'MESP_08'         => $request->mesp_08,
                                       'MESP_09'         => $request->mesp_09,
                                       'MESP_10'         => $request->mesp_10,
                                       'MESP_11'         => $request->mesp_11,
                                       'MESP_12'         => $request->mesp_12,

                                       'TRIMP_01'        => $trimp_01,
                                       'TRIMP_02'        => $trimp_02,
                                       'TRIMP_03'        => $trimp_03,
                                       'TRIMP_04'        => $trimp_04,

                                       'TSEMP_01'        => $tsemp_01,
                                       'TSEMP_02'        => $tsemp_02,

                                       'TOTP_01'         => $totp_01,
                                       'TOTP_02'         => $totp_02,
 
                                       'OBS_01'          => substr(trim(strtoupper($request->obs_01)),0,3999),        
                                       'STATUS_1'        => $request->status_1,

                                       'IP_M'            => $ip,
                                       'LOGIN_M'         => $nombre,
                                       'FECHA_M2'        => date('Y/m/d'),    //date('d/m/Y')
                                       'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
            toastr()->success('Acción o meta del nota periodistica actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =        43;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
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
                    toastr()->success('Trx de actualización de acción o meta del nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de actualización de acción o meta del nota periodistica. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Trx de actualización de acción o meta del nota periodistica en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verdpa',$id);
    }

    //******************* Editar 1 *****************//
    public function actionEditardRecepcion1($id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');        

        $regtema      = regtemaModel::select('TEMA_ID','TEMA_DESC')
                        ->orderBy('TEMA_ID','asc')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();                              
        $regpersonal    = regPersonalModel::select('FOLIO','NOMBRE_COMPLETO')
                        ->orderBy('NOMBRE_COMPLETO','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->get();                                                        
        }else{
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->where('DEPEN_ID',$depen_id)
                          ->get();            
        }                    
        $regprogeanual  = regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                          'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                          'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                          'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                          'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                          ->where(  'FOLIO'     ,$id)
                          ->orderBy('PERIODO_ID','ASC')
                          ->orderBy('FOLIO'     ,'ASC')
                          ->get();
        $regprogdanual  = regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                          'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID','TACCION_ID',
                          'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                          'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                          'OPERACIONAL_DESC','TEMA_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                          'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                          'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                          'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                          'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                          'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                          'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',       
                          'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                          'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                          'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                          'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                          'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                          'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',                                             
                          'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                          'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                          ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                          //->where('FOLIO',$id)
                          //->where('PARTIDA',$id2)
                          ->first();
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del nota periodistica.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.notas_periodisticas.editardProganual1',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regtema','regepproy','regpersonal'));
    }

    public function actionActualizardRecpcion1(notaper1Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');   
        $depen_id     = session()->get('depen_id');             

        // **************** actualizar ******************************
        $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe Archivo digital de Ficha de justificación.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name01 =null;
            if($request->hasFile('soporte_01')){
                $name01 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_01')->getClientOriginalName(); 
                $request->file('soporte_01')->move(public_path().'/storage/', $name01);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_01' => $name01,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital de Ficha de justificación actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3001;
                $xtrx_id      =        43;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                              'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                              ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
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
                        toastr()->success('Trx de Archivo digital de Ficha de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Archivo digital de Ficha de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                                 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                            'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M'    => $regbitacora->IP       = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de Ficha de justificación reg. en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/                     
            }       /************ Valida si viene vacio el arc. digital ****************/
        }           /************ Actualizar *******************************************/
        return redirect()->route('verdpa',$id);
    }

    public function actionBorrardNotaper($id, $id2){
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

        /************ Eliminar **************************************/
        $regprogdanual  = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        //              ->find('TEMA_ID',$id);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del del nota periodistica.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regprogdanual->delete();
            toastr()->success('Acción o meta del nota periodistica eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =        44;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID','FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                    toastr()->success('Trx de eliminar acción o meta del nota periodistica registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de eliminar Trx de acción o meta del nota periodistica. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
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
                toastr()->success('Trx de eliminar acción o meta del nota periodistica actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar *********************************/
        return redirect()->route('verdpa',$id);
    }    

}
