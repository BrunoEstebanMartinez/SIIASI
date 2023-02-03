@extends('sicinar.principal')

@section('title','Nuevo medio informativo')

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
            <h1>Menú
                <small> Catálogos - Medios informativos - Nuevo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        {!! Form::open(['route' => 'altanuevomedio', 'method' => 'POST','id' => 'nuevomedio', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>Medio informativo </label>
                                    <input type="text" class="form-control" name="medio_desc" id="medio_desc" placeholder="Medio informativo" required>
                                </div>
                            </div>         
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>Link o URL </label>
                                    <input type="text" class="form-control" name="medio_link" id="medio_link" placeholder="Link o URL" required>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>Director y/o responsable </label>
                                    <input type="text" class="form-control" name="medio_obs1" id="medio_obs1" placeholder="Director y/o responsable" required>
                                </div>
                            </div>                             

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital del logo, NO deberá ser mayor a 2,500 kBytes.  </label>
                                </div>   
                            </div>  
                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital logo (gif, jpg o png) </label>
                                    <input type="file" class="text-md-center" style="color:red" name="medio_foto1" id="medio_foto1" placeholder="Subir archivo digital del logo en formato (gif, jpg o png">
                                </div>   
                            </div>    

                            <div class="row">    
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('vermedio')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\medioRequest','#nuevomedio') !!}
@endsection

@section('javascrpt')
@endsection
