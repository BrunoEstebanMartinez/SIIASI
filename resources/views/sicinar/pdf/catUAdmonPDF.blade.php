@extends('sicinar.pdf.layout')

@section('content')
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Catálogo de Unidades Administrativas    </h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;width: 5px;"><b style="color:white;font-size: x-small;">id.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 300px;"><b style="color:white;font-size: x-small;">Unidad Administrativa </b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Activo/Inactivo </b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Fecha reg. </b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($reguadmon as $medida)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;width: 5px;"><b style="color:black;font-size: x-small;">{{$medida->uadmon_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 300px;"><b style="color:black;font-size: xx-small;">{{$medida->uadmon_desc}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;"><b style="color:black;font-size: x-small;">{{$medida->uadmon_status}}</b>
                    </td>
                    <td style="text-align:center; vertical-align: middle;"><b style="color:black;font-size: x-small;">{{date("d/m/Y", strtotime($medida->uadmon_fecreg))}}</b>
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
