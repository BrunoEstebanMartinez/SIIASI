@extends('sicinar.pdf.layout')

@section('content')
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Catálogo de Series o Temáticas </h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;width:5px;"><b style="color:white;font-size:10px;">id.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 200px;"><b style="color:white;font-size:10px;">Serie o Temática </b></th>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;width:5px;"><b style="color:white;font-size:10px;">id.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 200px;"><b style="color:white;font-size:10px;">Sección </b></th>            
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Activo/<br>Inactivo</b></th>
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Fecha reg.</b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($regtema as $tematica)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:5px;"   >{{$tematica->tema_id}}   </td>
                    <td style="text-align:justify;vertical-align: middle0;font-size:09px;width:200px;">{{$tematica->tema_desc}} </td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:5px;"   >{{$tematica->seccion_id}}</td>                    
                    <td style="text-align:left; vertical-align: middle;font-size:09px;">
                    {{$tematica->seccion_id.' '}}
                    @foreach($regseccion as $sec)
                        @if($sec->seccion_id == $tematica->seccion_id)
                            {{Trim($sec->seccion_desc)}}
                            @break
                        @endif
                    @endforeach                                        
                    </td>                     
                    <td style="text-align:center;vertical-align:middle;">{{$tematica->tema_status}}     </td>
                    <td style="text-align:center;vertical-align:middle;">{{date("d/m/Y",strtotime($tematica->tema_fecreg))}}<td>
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