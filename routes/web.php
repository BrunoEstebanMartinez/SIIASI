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
    
    Route::get('nota/ver/fterAn/{ANIO}'         ,'notaperiodisticaController@isWithYearAction')->name('verper');
    Route::get('nota/ver/fterTip/{TIPO}'         ,'notaperiodisticaController@isWithTipoNota')->name('verpertipo');
    Route::get('nota/ver/fterMed/{MEDIO}'         ,'notaperiodisticaController@isWithMedioInfo')->name('verpermedio');

    Route::get('nota/ver/{ANIO}'         ,'notaperiodisticaController@isWithYearAction')->name('verper');
    Route::get('nota/ver/buscar/{ANIO}'  ,'notaperiodisticaController@actionBuscarNotaper')->name('buscarnotaper'); 
    //Route::get('recepcion/buscar'      ,'recepcionController@actionBuscarRecepcion')->name('buscarrecepcion');

    Route::get('nota/ver/fterAn/buscar/{ANIO}'  ,'notaperiodisticaController@actionBuscarNotaper')->name('buscarnotaper'); 
    Route::get('nota/ver/fterTip/buscar/{TIPO}'  ,'notaperiodisticaController@actionBuscarNotaper')->name('buscarnotatipo');
    Route::get('nota/ver/fterMed/buscar/{MEDIO}'  ,'notaperiodisticaController@actionBuscarNotaper')->name('buscarnotamedio');


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

    Route::post('nota/nuevo/openia', 'notaperiodisticaController@openAIAPI');
 
    //Notas de redes scoiales
    //Filtro de periodos 
    Route::get('notaredes/ver/{ANIO}'         ,'notaredesController@isWithYearAction')->name('verpernotared');
    Route::get('notaredes/ver/buscar/{ANIO}'  ,'notaredesController@actionBuscarNotaper')->name('buscarnotaredper'); 
    //Route::get('recepcion/buscar'      ,'recepcionController@actionBuscarRecepcion')->name('buscarrecepcion');
    //
    Route::get('notaredes/buscar'        ,'notaredesController@actionBuscarNotared')->name('buscarnotared');    
    Route::get('notaredes/nuevo'         ,'notaredesController@actionNuevaNotared')->name('nuevanotared');
    Route::post('notaredes/alta'         ,'notaredesController@actionAltanuevaNotared')->name('altanuevanotared');
    Route::get('notaredes/ver'           ,'notaredesController@actionVerNotasred')->name('vernotasred');    
    Route::get('notaredes/{id}/editar'   ,'notaredesController@actionEditarNotared')->name('editarnotared');
    Route::put('notaredes/{id}/update'   ,'notaredesController@actionActualizarNotared')->name('actualizarnotared');
    Route::get('notaredes/{id}/Borrar'   ,'notaredesController@actionBorrarNotared')->name('borrarnotared');
    Route::get('notaredes/excel/{id}'    ,'notaredesController@actionExportNotaredesExcel')->name('exportnotaredesexcel');
    Route::get('notaredes/pdf/{id}/{id2}','notaredesController@actionExportNotaredesPDF')->name('exportnotaredespdf');

    Route::get('notaredes/edita1/{id}'   ,'notaredesController@actionEditarNotared1')->name('editarnotared1');
    Route::put('notaredes/update1/{id}'  ,'notaredesController@actionActualizarNotared1')->name('actualizarnotared1');        
    
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

    //Estadíticas
    //Notas informativas
    Route::get('numeralia/nota/filtro'      ,'estadisticanotainfController@actionNotafiltro1')->name('notafiltro1');        
    Route::post('numeralia/nota/estadistica','estadisticanotainfController@actionEstadisticanota')->name('estadisticanota'); 

    //Reportes: Menu de criterios
    Route::get('reporte/nota/menu', 'reporteNotaController@viewMenuCriterio')->name('menuRepNP');
    Route::get('reporte/red/menu', 'reporteRedController@viewMenuCriterioRedes')->name('menuRepRP');

        //Reporte: rutas por opcion
    Route::post('reporte/nota/criterio', 'reporteNotaController@loadADownPDF')->name('criterio');
    Route::post('reporte/red/criterio', 'reporteRedController@loadADownPDFRedes')->name('criterioRed');

    //Redes sociales
    Route::get('numeralia/redes/filtro'      ,'estadisticaredessocController@actionRedesfiltro1')->name('redesfiltro1');        
    Route::post('numeralia/redes/estadistica','estadisticaredessocController@actionEstadisticaredes')->name('estadisticaredes'); 

    // Email related routes
    Route::get('mail/ver/todos'        ,'mailController@actionVerContactos')->name('vercontactos');
    Route::get('mail/buscar/todos'     ,'mailController@actionBuscarContactos')->name('buscarcontactos');    
    Route::get('mail/{id}/editar/email','mailController@actionEditarEmail')->name('editaremail');
    //Route::put('mail/{id}/email'     ,'mailController@actionEmail')->name('Email'); 

    Route::get('mail/email'            ,'mailController@actionEmail')->name('Email'); 
    Route::put('mail/emailbienvenida'  ,'mailController@actionEmailBienve')->name('emailbienve'); 
    //Route::post('mail/send'          ,'mailController@send')->name('send');     
});

