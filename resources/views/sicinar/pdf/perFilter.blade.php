@extends('sicinar.pdf.layout')


    @section('content')


    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Reporte del mes de @foreach($regmeses as $getUserMonth)
                                                                                                        @if($getUserMonth->mes_id == $id_mes1)
                                                                                                            {{ trim($getUserMonth -> mes_desc) }}
                                                                                                        @endif    
                                                                                                    @endforeach </h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>

    <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:white;text-align:center;">Fecha</th>
            <th style="background-color:white;text-align:center;">Medio informativo</th> 
            <th style="background-color:white;text-align:center;">Encabezado</th>
           <!-- <th style="background-color:white;text-align:center;">Nota</th> -->  
            <th style="background-color:white;text-align:center;">Link</th>
        </tr>
        </thead>
        <tbody>
            @foreach($filterR as $mensual)
                <tr style = "text-align:center;">
                <td>{{ date('d/m/y', strtotime(trim($mensual->nm_fec_nota)))}}  </td>
                <td>{{$mensual->medio_desc}}</td>
                <td>{{$mensual->nm_titulo}}</td>  
              <!--  <td>{{$mensual->nm_nota}}</td>  -->                  
                <td>{{$mensual->nm_link}}<td>

                @if($mensual -> nm_calif == 1)
                    <td  bgcolor="#9FEEBB"><td>
                @elseif($mensual -> nm_calif == 2)
                    <td bgcolor="#CFDDF6"><td>
                @else
                    <td bgcolor="#D6A8B5"><td>
                    @endif
                   
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="page-break-inside: avoid;">
        <tr>
            <th style="text-align:right;"><b style="font-size: x-small;"><b>Fecha de emisión: {!! date('d/m/Y') !!}</b></th>
        </tr>
    </table>

    

    @endsection