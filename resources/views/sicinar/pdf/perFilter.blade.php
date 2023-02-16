@extends('sicinar.pdf.layout')


    @section('content')

    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            @php
                @if($request)
            @endphp
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Reporte mensual de {{ date('M') }}</h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;width:5px;"><b style="color:white;font-size:10px;">Fecha</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 200px;"><b style="color:white;font-size:10px;">Medio informativo </b></th> 
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Encabezado        </b></th>
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Resumen</b></th>         
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Link        </b></th>
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Positivo              </b></th>
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Neutro              </b></th>
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Negativo              </b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($filterR as $mensual)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:10px;">{{$mensual->nm_fec_nota}}  </td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:20px;">{{$medios->medio_desc}}</td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:10px;">{{$medios->nm_titulo}}</td>                    
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:200px;">{{$medios->nm_nota}}</td>  
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:100px;">{{$medios->nm_link}}<td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:100px;">{{$medios->positivo}}<td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:100px;">{{$medios->neutro}}<td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:100px;">{{$medios->negativo}}<td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="page-break-inside: avoid;" class="table table-hover table-striped" align="center">
        <tr>
            <th style="text-align:right;"><b style="font-size: x-small;"><b>Fecha de emisión: {!! date('d/m/Y') !!}</b></th>
        </tr>
    </table>

    @endsection