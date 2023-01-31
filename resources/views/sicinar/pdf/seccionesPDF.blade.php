@extends('sicinar.pdf.layout')

@section('content')
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Catálogo de Secciones </h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;width: 5px;"><b style="color:white;font-size:11px;">Id.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 300px;"><b style="color:white;font-size:11px;">Sección </b></th>
            <th style="background-color:darkgreen;text-align:left;width:   5px;"><b style="color:white;font-size:11px;">Tipo</b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:11px;">Activo/Inactivo</b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:11px;">Fecha reg.</b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($regseccion as $seccion)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;width: 5px;"  ><b style="color:black;font-size:10px;">{{$seccion->seccion_id}}</b>
                    </td>                    
                    <td style="text-align:justify;vertical-align: middle;width: 300px;"><b style="color:black;font-size:10px;">{{$seccion->seccion_desc}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 5px;"  ><b style="color:black;font-size:10px;">{{$seccion->seccion_tipo}}</b>
                    </td>                    
                    <td style="text-align:center;vertical-align: middle;"><b style="color:black;font-size:10px;">{{$seccion->seccion_status}}</b>
                    </td>
                    <td style="text-align:center; vertical-align: middle;"><b style="color:black;font-size:10px;">{{date("d/m/Y", strtotime($seccion->seccion_fecreg))}}</b>
                    </td>
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
