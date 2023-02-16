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
                            
                        <div class = "row pull-right">

                            <div class = "col-xs-1 form-group">

                            </div>

                            <div class = "col-xs-1 form-group">

                            </div>

                            <div class = "col-xs-1 form-group">

                            </div>

                        </div>

                                <div class = "row">
                                    <div class = "col-xs-1 form-group">
                                        <label>Periodo</label>
                                        <select name="" id="" class = "form-control"></select>
                                    </div>

                                    <div class = "col-xs-1 form-group">
                                        <label>Medio</label>
                                        <select name="" id="" class = "form-control"></select>
                                    </div>

                                    <div class = "col-xs-1 form-group">
                                            <label>Tipo</label>
                                            <select name="" id="" class = "form-control"></select>
                                    </div>

                                    <div class = "col-xs-1 form-group">
                                        <label>Calificación</label>
                                         <select name="" id="" class = "form-control"></select>
                                    </div>

                                    <div class = "col-xs-2 form-group">
                                        <label>Fecha inicio</label>
                                        <input class = "form-control" type="date" id="datepickerOf" name = "datepickerOf">
                                    </div>

                                    <div class = "col-xs-2 form-group">
                                        <label>Fecha fin</label>
                                        <input class = "form-control" type="date" id="datepickerOf" name = "datepickerOf">
                                    </div>

                                    <div class = "col-xs-2 form-group">
                                        <label>Aplicar</label>
                                        <button class = "btn btn-primary btn-sm form-control" id="filterPointer" name = "filterPointer">Vista</button>
                                    </div>
                                
                                </div>

                           
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src = "{{ asset('js/liveSearchEntrada.js') }}"></script>

@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection