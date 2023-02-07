@extends('sicinar.pdf.layout')

@section('content')
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Catálogo de Medios informativos </h4></th>
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
            <th style="background-color:darkgreen;text-align:left;width: 200px;"><b style="color:white;font-size:10px;">Medio informativo </b></th> 
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Link o URL        </b></th>
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Director y/o responsable</b></th>         
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Fecha reg.        </b></th>
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Logo              </b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($regmedio as $medios)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:5px;"  >{{$medios->medio_id}}  </td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:200px;">{{$medios->medio_desc}}</td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:5px;"  >{{$medios->medio_link}}</td>                    
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:5px;"  >{{$medios->medio_obs1}}</td>  
                    <td style="text-align:center; vertical-align: middle;font-size:09px;"            >{{date("d/m/Y",strtotime($medios->fecreg))}}<td>
                    
                    @if(!empty($medios->medio_foto1)&&(!is_null($medios->medio_foto1)))                        
                        <th><img src="{{ asset('storage/'.$medios->medio_foto1)}}" width="80px" height="45px"></th>  
                    @else
                        <th>-</th>  
                    @endif
<<<<<<< HEAD
=======
                    
>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
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