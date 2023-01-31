@extends('sicinar.pdf.layout')

@section('content')
    <head>      
        <style>
        @page { margin-top: 30px; margin-bottom: 30px; margin-left: 50px; margin-right: 50px; } 
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;font-size: 12px;}
        h1 {
        page-break-before: always;
        }

        #header { position: fixed; left: 0px; top: 0px; right: 0px; height: 375px; }
        #content{ 
                  left: 50px; top: 0px; margin-bottom: 0px; right: 50px;
                  border: solid 0px #000;
                  font: 1em arial, helvetica, sans-serif;
                  color: black; text-align:left;vertical-align: middle; width:1000px;}   
        #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 60px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page); }        
        </style>
    </head>
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <body>
    <div id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;PROGRAMA ANUAL (PA) 
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </div>

    <section id="content">
        <table class="table table-hover table-striped" align="center" width="100%"> 
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr>            
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr> 
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr> 
            
            @foreach($regprogeanual as $encpa)
            <tr>
                <td style="border:0; text-align:left;font-size:10px;"  >
                    Programa: <b>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{$encpa->epprog_id}}&nbsp;
                    @foreach($regepprog as $prog)
                        @if($prog->epprog_id == $encpa->epprog_id)
                            {{Trim($prog->epprog_desc)}}
                            @break
                        @endif
                    @endforeach
                    </b><br>                    
                    Proyecto: <b>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;{{$encpa->epproy_id}}&nbsp;
                    @foreach($regepproy as $proy)
                        @if($proy->epproy_id == $encpa->epproy_id)
                            {{Trim($proy->epproy_desc)}}
                            @break
                        @endif
                    @endforeach
                    </b><br>                    
                    Unidad responsable: <b>{{$encpa->depen_id1}}&nbsp;
                    @foreach($regdepen as $depen)
                        @if($depen->depen_id == $encpa->depen_id1)
                            {{Trim($depen->depen_desc)}}
                            @break
                        @endif
                    @endforeach
                    </b><br>                    
                    Unidad ejecutora: <b>
                    &nbsp;&nbsp;&nbsp;&nbsp;{{$encpa->depen_id2}}&nbsp;
                    @foreach($regdepen as $depen)
                        @if($depen->depen_id == $encpa->depen_id2)
                            {{Trim($depen->depen_desc)}}
                            @break
                        @endif
                    @endforeach
                    </b>              
                </td>

                <td style="border:0; text-align:left;font-size:10px;">
                    Fecha de entrega:<b>&nbsp;{{$encpa->fecha_entrega2}}</b>
                    <br>
                    Tipo: <b>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;
                    @foreach($regtipometa as $tipo)
                        @if($tipo->taccion_id == $encpa->taccion_id)
                            {{$tipo->taccion_desc}}
                            @break 
                        @endif
                    @endforeach
                    </b>
                    <br> 
                    Periodo fiscal:<b>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{$encpa->periodo_id}}</b>
                    <br>
                    Folio:<b>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;
                    {{$encpa->folio}}
                    </b>
                </td>
            </tr>
            @endforeach             
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;">#</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;">Acción o meta</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">U. medida </b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Ene </b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Feb </b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Mar</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Abr</b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">May</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Jun</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Jul </b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Ago </b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Sep</b>
            </th> 
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Oct</b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Nov</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Dic</b>
            </th>            
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Meta anual</b>
            </th>             
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Objetivo</b>
            </th>            
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Descrpción operacional</b>
            </th>               
        </tr>
        </thead>

        <tbody>
            @foreach($regprogdanual as $detpa)
                <tr>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">{{$detpa->partida}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">{{$detpa->actividad_desc}}</p>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;font-size:9px;">{{trim($detpa->umed_desc)}}</td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_01}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_02}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_03}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_04}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_05}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_06}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_07}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_08}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_09}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_10}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_11}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->mesp_12}}</b>
                    </td>                   
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$detpa->meta_programada}}</b>
                    </td>     
                    <td style="text-align:left;vertical-align: middle;font-size:8px;"><p align="justify">{{Trim($detpa->objetivo_desc)}}</p>
                    </td>                                                                                                     
                    <td style="text-align:left;vertical-align: middle;font-size:8px;"><p align="justify">{{Trim($detpa->operacional_desc)}}</p>
                    </td>                       
                </tr>
            @endforeach
        </table>
       
        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center">
            @foreach($regprogeanual as $encpa)
            <tr>
                <td style="border:0;text-align:center;font-size:10px;">Elaboró<br><b>{{$encpa->responsable}}</b>
                </td>
                <td style="border:0;text-align:center;font-size:10px;">Revisó<br><b>{{$encpa->elaboro}}</b>
                </td>
                <td style="border:0;text-align:center;font-size:10px;">Autorizó<br><b>{{$encpa->autorizo}}</b>
                </td>                
            </tr>
            <tr>
                <td style="border:0;text-align:left;  font-size:10px;">  </td>
                <td style="border:0;text-align:center;font-size:10px;">  </td>
                <td style="border:0;text-align:right ;font-size:10px;">
                Fecha de emisión:<b> Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}} </b>
                </td>                 
            </tr>
            @endforeach         
        </table>
        </tbody>
    </section>

    <footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="text-align:right;">
                    <b style="border:0; text-align:right; font-size:12px;">
                    Secretaría de Desarrollo Social
                    </b>
                    <br>
                    <b style="border:0; text-align:right;font-size: 9px;">
                    Unidad de Información, Planeación, Programación y Evaluación
                    </b>
                    <br>
                    <b style="border:0; text-align:right;font-size: 7px;">
                    Heriberto Enriquez No. 209, Col. Cuauhtémoc. C.P. 50130. Toluca, Estado de México. <br>
                    Tel.: 1 99 70 90. Correo electrónico: uippe.sedesem@edomex.gob.mx
                    </b>
                </td>
            </tr>
        </table>
    </footer>    
    </body>
@endsection

@section('javascrpt')
<!-- link de referencia de este ejmplo   http://www.ertomy.es/2018/07/generando-pdfs-con-laravel-5/ -->
<!-- si el PDF tiene varias páginas entonces hay que meter numeración de las paginas. 
     Para ello tendremos que poner el siguiente código en la plantilla: -->
<script type="text/php">
    $text = 'Página {PAGE_NUM} de {PAGE_COUNT}';
    $font = Font_Metrics::get_font("sans-serif");
    $pdf->page_text(493, 800, $text, $font, 7);
</script>
@endsection