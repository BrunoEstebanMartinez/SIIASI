@extends('sicinar.pdf.layout')

@section('content')
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Catálogo de Redes sociales </h4></th>
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
            <th style="background-color:darkgreen;text-align:left;width: 200px;"><b style="color:white;font-size:10px;">Red social </b></th> 
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Fecha reg.        </b></th>
            <th style="background-color:darkgreen;text-align:center;"           ><b style="color:white;font-size:10px;">Logo              </b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($regredes as $red)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:5px;"  >{{$red->rs_id}}  </td>
                    <td style="text-align:justify;vertical-align: middle;font-size:09px;width:200px;">{{$red->rs_desc}}</td>
                    <td style="text-align:center; vertical-align: middle;font-size:09px;"            >{{date("d/m/Y",strtotime($red->fecreg))}}<td>
                    
                    @if(!empty($red->rs_foto1)&&(!is_null($red->rs_foto1)))                        
                        <th><img src="{{ asset('storage/'.$red->rs_foto1)}}" width="50px" height="40px"></th>  
                    @else
                        <th>-</th>  
                    @endif
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
