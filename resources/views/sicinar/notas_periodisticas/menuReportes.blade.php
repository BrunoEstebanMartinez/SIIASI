@extends('sicinar.principal')

@section('title','Ver oficios de entrada')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')



    <div class="content-wrapper">
        <section class="content-header">
            <h1>Notas informativas - Reportes -  
                <small> Selección de criterios </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Notas informativas    </a></li>   
                <li><a href="#">Reportes </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">                             
                        <div class="box-body">


                        <div class = "row">
                            <div class="col-xs-4">
                                <label>Seleccione el rango correspondiente: </label>
                            </div>
                        </div>


                        <div class = "row">
                            <div class = "col-xs-4">
                                <br>
                            </div>
                        </div>


                            
                                <div class = "row">
                                    
                                    <form method = "POST" action = "{{ route('criterio') }}" >
                                        @csrf
                                    <div class = "col-xs-2 form-group">
                                        <label>Periodo inicial</label>
                                        <select class = "form-control" name = "periodo_id1" id = "periodo_id1">
                                        @if(is_array($regperiodos) || is_object($regperiodos))
                                            @foreach($regperiodos as $getPPeriodo)
                                                <option value = "{{ $getPPeriodo -> periodo_id }}">{{ $getPPeriodo -> periodo_desc }}</option>
                                            @endforeach
                                        @endif    
                                        </select>
                                    </div>

                                     
                                    <div class = "col-xs-1 form-group">
                                        <label>Mes inicial</label>
                                        <select class = "form-control" name = "mes_id1" id = "mes_id1">
                                        @if(is_array($regmeses) || is_object($regmeses))
                                            @foreach($regmeses as $getPMeses)
                                                <option value = "{{ $getPMeses -> mes_id }}">{{ $getPMeses -> mes_desc }}</option>
                                            @endforeach
                                        @endif    
                                        </select>
                                    </div>


                                     
                                    <div class = "col-xs-1 form-group">
                                        <label>Día inicial</label>
                                        <select class = "form-control" name = "dia_id1" id = "dia_id1">
                                        @if(is_array($regdias) || is_object($regdias))
                                            @foreach($regdias as $getPDias)
                                                <option value = "{{ $getPDias -> dia_id }}">{{ $getPDias -> dia_desc }}</option>
                                            @endforeach
                                        @endif    
                                        </select>
                                    </div>

                                    
                                
                                </div>

                                <div class = "row">

                                     
                                <div class = "col-xs-2 form-group">
                                        <label>Periodo final</label>
                                        <select class = "form-control" name = "periodo_id2" id = "periodo_id2">
                                        @if(is_array($regperiodos) || is_object($regperiodos))
                                            @foreach($regperiodos as $getPPeriodo)
                                                <option value = "{{ $getPPeriodo -> periodo_id }}">{{ $getPPeriodo -> periodo_desc }}</option>
                                            @endforeach
                                        @endif    
                                        </select>
                                    </div>

                                     
                                    <div class = "col-xs-1 form-group">
                                        <label>Mes final</label>
                                        <select class = "form-control" name = "mes_id2" id = "mes_id2">
                                        @if(is_array($regmeses) || is_object($regmeses))
                                            @foreach($regmeses as $getPMeses)
                                                <option value = "{{ $getPMeses -> mes_id }}">{{ $getPMeses -> mes_desc }}</option>
                                            @endforeach
                                        @endif    
                                        </select>
                                    </div>


                                    
                                    <div class = "col-xs-1 form-group">
                                        <label>Día final</label>
                                        <select class = "form-control" name = "dia_id2" id = "dia_id2">
                                        @if(is_array($regdias) || is_object($regdias))
                                            @foreach($regdias as $getPDias)
                                                <option value = "{{ $getPDias -> dia_id }}">{{ $getPDias -> dia_desc }}</option>
                                            @endforeach
                                        @endif    
                                        </select>
                                    </div>


                                </div>


                                <div class = "row">
                                    <div class = "col-xs-3">
                                            <select name="tipoR" id="tipoR" class="form-control">
                                                <option value="1">Calificación</option>
                                              <!--  <option value="2">Medios</option> -->
                                              <!--  <option value="3">Tipo de nota</option> -->
                                            </select>
                                    </div>
                                </div>

        <!--
                                <div class = "row">
                                    <div class="col-md-12">
                                        <span class="badge badge-primary" id = "onPaidLoad"></span>
                                    </div>
                                 </div>

-->
                                <div class = "row pull-right">
                                    <div class = "col-xs-2">
                                            <button type = "submit" id = "isGn" class = "btn btn-danger">Generar reporte</a>
                                           
                                    </div>
                                </div>

                           
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!--<script src = "{{ asset('js/repFeedBack.js') }}"></script> -->

@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection