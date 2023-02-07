@extends('sicinar.principal')

@section('title','Nueva seccion')

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
                <small> Catálogos - Secciones - Nueva</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'altanuevaseccion', 'method' => 'POST','id' => 'nuevaseccion']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label >Id. Sección </label>
                                        <input type="text" class="form-control" id="seccion_id" name="seccion_id" placeholder="Digitar id de seccion" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label >Sección </label>
                                        <input type="text" class="form-control" id="seccion_desc" name="seccion_desc" placeholder="Digitar seccion" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label>Tipo </label>
                                        <select class="form-control m-bot15" name="seccion_tipo" required>
                                            <option value="SUSTANTIVA" selected>SUSTANTIVA</option>
                                            <option value="COMUN"              >COMÚN     </option>
                                        </select>                      
                                    </div>
                                </div>
                            </div>    

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verseccion')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\seccionRequest','#nuevaseccion') !!}
@endsection

@section('javascrpt')
@endsection
