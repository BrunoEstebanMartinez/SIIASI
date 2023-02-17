@extends('sicinar.principal')

@section('title','Filtro de Estadisticas de notas periodisticas')

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small> Estadísticas - Redes sociales </small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => 'estadisticaredes', 'method' => 'POST','id' => 'estadisticaredes', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >Periodo fiscal inicial </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal inicial</option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Mes inicial </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes inicial</option>
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Día inicial </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia inicial</option>
                                        @foreach($regdias as $dia)
                                            <option value="{{$dia->dia_id}}">{{$dia->dia_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div> 
                            </div>
 
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >Periodo fiscal final </label>
                                    <select class="form-control m-bot15" name="periodo_id2" id="periodo_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal final</option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Mes final </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes final</option>
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Día final </label>
                                    <select class="form-control m-bot15" name="dia_id2" id="dia_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia final</option>
                                        @foreach($regdias as $dia)
                                            <option value="{{$dia->dia_id}}">{{$dia->dia_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>                           

                            <div class="row">    
                                <div class="col-xs-2 form-group"> 
                                    <select name="tipo" class="form-control" id="tipo">
                                    <option value="1">Red social    </option>
                                    <option value="2">Calificación  </option>
                                    </select>
                                </div>
                            </div>                     
                        
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-12 offset-md-5">
                                        {!! Form::submit('Generar estadísticas',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    </div>                                
                                </div>                            
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\filtroredes1Request','#estadisticaredes') !!}
@endsection

@section('javascrpt')
@endsection

