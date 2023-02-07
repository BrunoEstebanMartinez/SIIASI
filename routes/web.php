<?php

/* 
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/  
  
Route::get('/', function () {
    return view('sicinar.login.loginInicio');
});
 
    Route::group(['prefix' => 'control-interno'], function() {
    Route::post('menu', 'usuariosController@actionLogin')->name('login');
    Route::get('status-sesion/expirada', 'usuariosController@actionExpirada')->name('expirada');
    Route::get('status-sesion/terminada','usuariosController@actionCerrarSesion')->name('terminada');
 
    // Auto registro en sistema
    Route::get( 'Autoregistro/usuario'              ,'usuariosController@actionAutoregUsu')->name('autoregusu');
    Route::post('Autoregistro/usuario/registro'     ,'usuariosController@actionRegaltaUsu')->name('regaltausu');
    Route::get( 'Autoregistro/{id}/editarbienvenida','usuariosController@actioneditarBienve')->name('editarbienve');        

    // BackOffice del sistema
    Route::get('BackOffice/usuarios'                ,'usuariosController@actionNuevoUsuario')->name('nuevoUsuario');
    Route::post('BackOffice/usuarios/alta'          ,'usuariosController@actionAltaUsuario')->name('altaUsuario');
    Route::get('BackOffice/buscar/todos'            ,'usuariosController@actionBuscarUsuario')->name('buscarUsuario');        
    Route::get('BackOffice/usuarios/todos'          ,'usuariosController@actionVerUsuario')->name('verUsuarios');
    Route::get('BackOffice/usuarios/{id}/editar'    ,'usuariosController@actionEditarUsuario')->name('editarUsuario');
    Route::put('BackOffice/usuarios/{id}/actualizar','usuariosController@actionActualizarUsuario')->name('actualizarUsuario');
    Route::get('BackOffice/usuarios/{id}/Borrar'    ,'usuariosController@actionBorrarUsuario')->name('borrarUsuario');    
    Route::get('BackOffice/usuario/{id}/activar'    ,'usuariosController@actionActivarUsuario')->name('activarUsuario');
    Route::get('BackOffice/usuario/{id}/desactivar' ,'usuariosController@actionDesactivarUsuario')->name('desactivarUsuario');

    Route::get('BackOffice/usuario/{id}/{id2}/email','usuariosController@actionEmailRegistro')->name('emailregistro');    

    //Catalogos
    //Procesos
    Route::get('proceso/nuevo'      ,'catprocesoController@actionNuevoProceso')->name('nuevoProceso');
    Route::post('proceso/alta'      ,'catprocesoController@actionAltaNuevoProceso')->name('AltaNuevoProceso');
    Route::get('proceso/ver'        ,'catprocesoController@actionVerProceso')->name('verProceso');
    Route::get('proceso/{id}/editar','catprocesoController@actionEditarProceso')->name('editarProceso');
    Route::put('proceso/{id}/update','catprocesoController@actionActualizarProceso')->name('actualizarProceso');
    Route::get('proceso/{id}/Borrar','catprocesoController@actionBorrarProceso')->name('borrarProceso');    
    Route::get('proceso/excel'      ,'catprocesoController@exportCatProcesosExcel')->name('downloadprocesos');
    Route::get('proceso/pdf'        ,'catprocesoController@exportCatProcesosPdf')->name('catprocesosPDF');

    //Funciones de procesos
    Route::get('funcion/nuevo'      ,'catfuncionController@actionNuevaFuncion')->name('nuevaFuncion');
    Route::post('funcion/alta'      ,'catfuncionController@actionAltaNuevaFuncion')->name('AltaNuevaFuncion');
    Route::get('funcion/ver'        ,'catfuncionController@actionVerFuncion')->name('verFuncion');
    Route::get('funcion/{id}/editar','catfuncionController@actionEditarFuncion')->name('editarFuncion');
    Route::put('funcion/{id}/update','catfuncionController@actionActualizarFuncion')->name('actualizarFuncion');
    Route::get('funcion/{id}/Borrar','catfuncionController@actionBorrarFuncion')->name('borrarFuncion');    
    Route::get('funcion/excel'      ,'catfuncionController@exportCatFuncionesExcel')->name('downloadfunciones');
    Route::get('funcion/pdf'        ,'catfuncionController@exportCatFuncionesPdf')->name('catfuncionesPDF');    

    //Actividades
    Route::get('actividad/nuevo'      ,'cattrxController@actionNuevaTrx')->name('nuevaTrx');
    Route::post('actividad/alta'      ,'cattrxController@actionAltaNuevaTrx')->name('AltaNuevaTrx');
    Route::get('actividad/ver'        ,'cattrxController@actionVerTrx')->name('verTrx');
    Route::get('actividad/{id}/editar','cattrxController@actionEditarTrx')->name('editarTrx');
    Route::put('actividad/{id}/update','cattrxController@actionActualizarTrx')->name('actualizarTrx');
    Route::get('actividad/{id}/Borrar','cattrxController@actionBorrarTrx')->name('borrarTrx');    
    Route::get('actividad/excel'      ,'cattrxController@exportCatTrxExcel')->name('downloadtrx');
    Route::get('actividad/pdf'        ,'cattrxController@exportCatTrxPdf')->name('cattrxPDF');

    //Medios informativos
    Route::get('mediosinf/nueva'       ,'catmediosController@actionNuevoMedio')->name('nuevomedio');
    Route::post('mediosinf/alta'       ,'catmediosController@actionAltaNuevoMedio')->name('altanuevomedio');
    Route::get('mediosinf/ver'         ,'catmediosController@actionVerMedio')->name('vermedio');
    Route::get('mediosinf/{id}/editar' ,'catmediosController@actionEditarMedio')->name('editarmedio');
    Route::put('mediosinf/{id}/update' ,'catmediosController@actionActualizarMedio')->name('actualizarmedio');
    Route::get('mediosinf/{id}/Borrar' ,'catmediosController@actionBorrarMedio')->name('borrarmedio');    
    Route::get('mediosinf/excel'       ,'catmediosController@actionExportMediosExcel')->name('exportmediosexcel');
    Route::get('mediosinf/pdf'         ,'catmediosController@actionExportMediosPdf')->name('exportmediospdf');  

    Route::get('mediosinf/edita1/{id}' ,'catmediosController@actionEditarMedio1')->name('editarmedio1');
    Route::put('mediosinf/update1/{id}','catmediosController@actionActualizarMedio1')->name('actualizarmedio1');        

    //Redes sociales
    Route::get('redessoc/nueva'       ,'catredessocialesController@actionNuevaRed')->name('nuevared');
    Route::post('redessoc/alta'       ,'catredessocialesController@actionAltaNuevaRed')->name('altanuevared');
    Route::get('redessoc/ver'         ,'catredessocialesController@actionVerRedes')->name('verredes');
    Route::get('redessoc/{id}/editar' ,'catredessocialesController@actionEditarRed')->name('editarred');
    Route::put('redessoc/{id}/update' ,'catredessocialesController@actionActualizarRed')->name('actualizarred');
    Route::get('redessoc/{id}/Borrar' ,'catredessocialesController@actionBorrarRed')->name('borrarred');    
    Route::get('redessoc/excel'       ,'catredessocialesController@actionExportRedesExcel')->name('exportredesexcel');
    Route::get('redessoc/pdf'         ,'catredessocialesController@actionExportRedesPdf')->name('exportredespdf');  

    Route::get('redessoc/edita1/{id}' ,'catredessocialesController@actionEditarRed1')->name('editarred1');
    Route::put('redessoc/update1/{id}','catredessocialesController@actionActualizarRed1')->name('actualizarred1'); 

    //Tematicas
    Route::get('tema/nueva'      ,'cattemasController@actionNuevoTema')->name('nuevotema');
    Route::post('tema/alta'      ,'cattemasController@actionAltaNuevoTema')->name('altanuevotema');
    Route::get('tema/ver/todos'  ,'cattemasController@actionVerTema')->name('vertema');
    Route::get('tema/{id}/editar','cattemasController@actionEditarTema')->name('editartema');
    Route::put('tema/{id}/update','cattemasController@actionActualizarTema')->name('actualizartema');
    Route::get('tema/{id}/Borrar','cattemasController@actionBorrarTema')->name('borrartema');    
    Route::get('tema/excel'      ,'cattemasController@actionExportTemaExcel')->name('exporttemaexcel');
    Route::get('tema/pdf'        ,'cattemasController@actionExportTemaPdf')->name('exporttemapdf');  

    //Unidades admon.
    Route::get('uadmon/nueva'      ,'catuadmonController@actionNuevaUAdmon')->name('nuevauadmon');
    Route::post('uadmon/alta'      ,'catuadmonController@actionAltaNuevaUAdmon')->name('altanuevauadmon');
    Route::get('uadmon/ver/todos'  ,'catuadmonController@actionVerUAdmon')->name('veruadmon');
    Route::get('uadmon/{id}/editar','catuadmonController@actionEditarUAdmon')->name('editaruadmon');
    Route::put('uadmon/{id}/update','catuadmonController@actionActualizarUAdmon')->name('actualizaruadmon');
    Route::get('uadmon/{id}/Borrar','catuadmonController@actionBorrarUAdmon')->name('borraruadmon');    
    Route::get('uadmon/excel'      ,'catuadmonController@actionExportUAdmonExcel')->name('exportuadmonexcel');
    Route::get('uadmon/pdf'        ,'catuadmonController@actionExportUAdmonPdf')->name('exportuadmonpdf');    
      
    //Notas periodísticas
    //Filtro de periodos 
    Route::get('nota/ver/{ANIO}'         ,'notaperiodisticaController@isWithYearAction')->name('verper');
    Route::get('nota/ver/buscar/{ANIO}'  ,'notaperiodisticaController@actionBuscarRecepcion')->name('buscarrecepcion'); 
    //Route::get('recepcion/buscar'      ,'recepcionController@actionBuscarRecepcion')->name('buscarrecepcion');
    //
    Route::get('nota/buscar'        ,'notaperiodisticaController@actionBuscarNotaper')->name('buscarnotaper');    
    Route::get('nota/nuevo'         ,'notaperiodisticaController@actionNuevaNotaper')->name('nuevanotaper');
    Route::post('nota/alta'         ,'notaperiodisticaController@actionAltanuevaNotaper')->name('altanuevanotaper');
    Route::get('nota/ver'           ,'notaperiodisticaController@actionVerNotasper')->name('vernotasper');    
    Route::get('nota/{id}/editar'   ,'notaperiodisticaController@actionEditarNotaper')->name('editarnotaper');
    Route::put('nota/{id}/update'   ,'notaperiodisticaController@actionActualizarNotaper')->name('actualizarnotaper');
    Route::get('nota/{id}/Borrar'   ,'notaperiodisticaController@actionBorrarNotaper')->name('borrarnotaper');
    Route::get('nota/excel/{id}'    ,'notaperiodisticaController@actionExportNotaperExcel')->name('exportnotaperexcel');
    Route::get('nota/pdf/{id}/{id2}','notaperiodisticaController@actionExportNotaperPDF')->name('exportnotaperpdf');

    Route::get('nota/edita1/{id}'   ,'notaperiodisticaController@actionEditarNotaper1')->name('editarnotaper1');
    Route::put('nota/update1/{id}'  ,'notaperiodisticaController@actionActualizarNotaper1')->name('actualizarnotaper1');     
    
    Route::post('nota/nuevo/openia', 'notaperiodisticaController@openAIAPI')->name('openia');
    Route::post('nota/nuevo/openia/testing', 'notaperiodisticaController@openAIAPI')->name('testing');
    //Turnar documentos a
    Route::get('turnar/ver'           ,'turnarController@actionVerTurnar')->name('verturnar');
    Route::get('turnar/buscar'        ,'turnarController@actionBuscarTurnar')->name('buscarturnar');    
    Route::get('turnar/{id}/editar'   ,'turnarController@actionEditarTurnar')->name('editarturnar');
    Route::put('turnar/{id}/update'   ,'turnarController@actionActualizarTurnar')->name('actualizarturnar');

    //Atender o dar seguimiento a documentos de entrada
    //Route::get('atender/nuevo'         ,'atenderrecepController@actionNuevaAtenrecep')->name('nuevaatenrecep');
    //Route::post('atender/alta'         ,'atenderrecepController@actionAltaNuevaAtenrecep')->name('altanuevaatenrecep');
    Route::get('atender/ver'           ,'atenderrecepController@actionVerAtenrecep')->name('veratenrecep');
    Route::get('atender/buscar'        ,'atenderrecepController@actionBuscarAtenrecep')->name('buscaratenrecep');    
    Route::get('atender/{id}/editar'   ,'atenderrecepController@actionEditarAtenrecep')->name('editaratenrecep');
    Route::put('atender/{id}/update'   ,'atenderrecepController@actionActualizarAtenrecep')->name('actualizaratenrecep');
    //Route::get('atender/{id}/Borrar'   ,'atenderrecepController@actionBorrarAtenrecep')->name('borraratenrecep');
    //Route::get('atender/excel/{id}'    ,'atenderrecepController@actionExportAtenrecepExcel')->name('exportatenrecepexcel');
    //Route::get('atender/pdf/{id}/{id2}','atenderrecepController@actionExportAtenrecepPDF')->name('exportatenreceppdf');

    Route::get('atender/{id}/editar2'  ,'atenderrecepController@actionEditarAtenrecep2')->name('editaratenrecep2');
    Route::put('atender/{id}/update2'  ,'atenderrecepController@actionActualizarAtenrecep2')->name('actualizaratenrecep2');    

    //Documentos de salida
    //Filtro de periodos 
    Route::get('periodo/ver'                ,'periodosController@showModelFilter')->name('periodos');    

    Route::get('salida/{ANIO}'              ,'remisionController@actionVerRemision')->name('versalida');
    Route::get('salida/periodo/nuevo'       ,'remisionController@actionNuevaRemision')->name('nuevasalida');
    Route::post('salida/periodo/alta'       ,'remisionController@actionAltaNuevaSalida')->name('altadesalida');
    Route::get('salida/periodo/buscar'      ,'remisionController@actionBuscarSalida')->name('buscarsalida'); 
    Route::get('salida/periodo/{id}/editar' ,'remisionController@actionEditarSalida')->name('editarsalida');
    Route::put('salida/periodo/{id}/update' ,'remisionController@actionActualizarSalida')->name('actualizarsalida');
    Route::get('salida/periodo/{id}/borrar' ,'remisionController@actionBorrarSalida')->name('borrarsalida');
         
    Route::get('salida/periodo/{id}/editar1','remisionController@actionEditarSalidaFormato')->name('editarsalidaformato');
    Route::put('salida/periodo/{id}/update1','remisionController@actionActualizarPDF')->name('actualizarsalidaformato');     
    
    //Indicadores
    Route::get('indicador/ver/todos'        ,'indicadoresController@actionVerCumplimiento')->name('vercumplimiento');
    Route::get('indicador/buscar/todos'     ,'indicadoresController@actionBuscarCumplimiento')->name('buscarcumplimiento');    
    Route::get('indicador/ver/todamatriz'   ,'indicadoresController@actionVermatrizCump')->name('vermatrizcump');
    Route::get('indicador/buscar/matriz'    ,'indicadoresController@actionBuscarmatrizCump')->name('buscarmatrizcump');      
    Route::get('indicador/ver/todasvisitas' ,'indicadoresController@actionVerCumplimientovisitas')->name('vercumplimientovisitas');
    Route::get('indicador/buscar/allvisitas','indicadoresController@actionBuscarCumplimientovisitas')->name('buscarcumplimientovisitas');    
    Route::get('indicador/{id}/oficiopdf'   ,'indicadoresController@actionOficioInscripPdf')->name('oficioInscripPdf'); 

    //Estadísticas
    //OSC
    Route::get('numeralia/graficaixedo'   ,'estadisticaOscController@OscxEdo')->name('oscxedo');
    Route::get('numeralia/graficaixmpio'  ,'estadisticaOscController@OscxMpio')->name('oscxmpio');
    Route::get('numeralia/graficaixrubro' ,'estadisticaOscController@OscxRubro')->name('oscxrubro');    
    Route::get('numeralia/graficaixrubro2','estadisticaOscController@OscxRubro2')->name('oscxrubro2'); 
    Route::get('numeralia/filtrobitacora' ,'estadisticaOscController@actionVerBitacora')->name('verbitacora');        
    Route::post('numeralia/estadbitacora' ,'estadisticaOscController@Bitacora')->name('bitacora'); 
    Route::get('numeralia/mapaxmpio'      ,'estadisticaOscController@actiongeorefxmpio')->name('georefxmpio');            
    Route::get('numeralia/mapas'          ,'estadisticaOscController@Mapas')->name('verMapas');        
    Route::get('numeralia/mapas2'         ,'estadisticaOscController@Mapas2')->name('verMapas2');        
    Route::get('numeralia/mapas3'         ,'estadisticaOscController@Mapas3')->name('verMapas3');        

    //padrón
    Route::get('numeralia/graficpadxedo'    ,'estadisticaPadronController@actionPadronxEdo')->name('padronxedo');
    //Route::get('numeralia/graficpadxmpio' ,'estadisticaPadronController@actionPadronxMpio')->name('padronxmpio');
    Route::get('numeralia/graficpadxserv'   ,'estadisticaPadronController@actionPadronxServicio')->name('padronxservicio');
    Route::get('numeralia/graficpadxsexo'   ,'estadisticaPadronController@actionPadronxsexo')->name('padronxsexo');
    Route::get('numeralia/graficpadxedad'   ,'estadisticaPadronController@actionPadronxedad')->name('padronxedad');
    Route::get('numeralia/graficpadxranedad','estadisticaPadronController@actionPadronxRangoedad')->name('padronxrangoedad');

    //Agenda
    Route::get('numeralia/graficaagenda1'     ,'progdilController@actionVerProgdilGraficaxmes')->name('verprogdilgraficaxmes');    
    Route::post('numeralia/graficaagendaxmes' ,'progdilController@actionProgdilGraficaxmes')->name('progdilgraficaxmes');
    Route::get('numeralia/graficaagenda2'     ,'progdilController@actionVerprogdilGraficaxtipo')->name('verprogdilgraficaxtipo');        
    Route::post('numeralia/graficaagendaxtipo','progdilController@actionProgdilGraficaxtipo')->name('progdilgraficaxtipo');

    // Email related routes
    Route::get('mail/ver/todos'        ,'mailController@actionVerContactos')->name('vercontactos');
    Route::get('mail/buscar/todos'     ,'mailController@actionBuscarContactos')->name('buscarcontactos');    
    Route::get('mail/{id}/editar/email','mailController@actionEditarEmail')->name('editaremail');
    //Route::put('mail/{id}/email'     ,'mailController@actionEmail')->name('Email'); 

    Route::get('mail/email'            ,'mailController@actionEmail')->name('Email'); 
    Route::put('mail/emailbienvenida'  ,'mailController@actionEmailBienve')->name('emailbienve'); 
    //Route::post('mail/send'          ,'mailController@send')->name('send');     
});

