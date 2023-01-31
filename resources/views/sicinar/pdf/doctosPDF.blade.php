@extends('sicinar.pdf.layout')

@section('content')
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Catálogo de Documentos </h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;">id.</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Documento</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Layout archivo digital</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Formato </b>
            </th>  
             <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Periodicidad </b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Frec.<br>entrega</b>
            </th>                                    
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Edo.</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Control</b>
            </th>

             <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Observaciones </b>
            </th>              
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Tipo</b>
            </th>            
        </tr>
        </thead>
        <tbody>
            @foreach($regdocto as $docto)
                <tr>
                    <td style="text-align:center;vertical-align: middle;font-size:xx-small;">{{$docto->doc_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;font-size:xx-small;">{{$docto->doc_desc}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;font-size:xx-small;">{{$docto->doc_file}}</b>
                    </td>                    
                    @foreach($regformato as $formato)
                        @if($formato->formato_id == $docto->formato_id)
                            <td style="text-align:justify;vertical-align: middle;font-size:xx-small;">{{trim($formato->formato_desc)}}</b>
                            </td>
                        @endif
                    @endforeach       
                    @foreach($regper as $per)
                        @if($per->per_id == $docto->per_id)
                            <td style="text-align:justify;vertical-align: middle;font-size: xx-small;">{{trim($per->per_desc)}}
                            </td>
                        @endif
                    @endforeach                                                                      
                    <td style="text-align:center;vertical-align: middle;font-size: xx-small;">{{$docto->per_frec}}</b>
                    </td>
                    @if($docto->doc_status == 'S')
                        <td style="color:darkgreen;text-align:center; vertical-align: middle; font-size: xx-small;">Activo
                        </td>                                            
                    @else
                        <td style="color:darkred; text-align:center; vertical-align: middle; font-size: xx-small;">Inactivo
                        </td>                                            
                    @endif
                    @if($docto->doc_status2 == 'S')
                        <td style="color:darkgreen;text-align:center; vertical-align: middle; font-size: xx-small;">Interno
                        </td>                                            
                    @else
                        <td style="color:darkred; text-align:center; vertical-align: middle; font-size: xx-small;">Externo
                        </td>                                            
                    @endif        
                    <td style="text-align:justify;vertical-align: middle;font-size:08pt;">{{Trim($docto->doc_obs)}}</b>
                    </td>                    
                    @if($docto->doc_status3 == 'S')
                        <td style="color:darkgreen;text-align:center; vertical-align: middle; font-size: xx-small;" title="Activo"><i class="fa fa-check">Obligatorio</i>
                        </td>                                            
                    @else
                        <td style="color:darkred; text-align:center; vertical-align: middle; font-size: xx-small;" title="Inactivo"><i class="fa fa-times">Opcional</i>
                        </td>                                            
                    @endif                                
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="page-break-inside: avoid;" class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th style="text-align:right;"><b style="font-size: x-small;"><b>Fecha de emisión: {!! date('d/m/Y') !!}</b></th>
        </tr>
        </thead>
    </table>
@endsection
