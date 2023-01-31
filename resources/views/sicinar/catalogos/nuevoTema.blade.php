@extends('sicinar.principal')

@section('title','Nuevo Rubro social')

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
                <small> Catálogos - Series o Temáticas - Nuevo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'altanuevotema', 'method' => 'POST','id' => 'nuevotema']) !!}
                        <div class="box-body">
                            <div class="row">
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>* Id. serie o temática </label>
                                    <input type="text" class="form-control" name="tema_id" id="tema_id" placeholder="Id. de la serie o temática" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label>* Serie o Temática 250 caracteres</label>
                                    <input type="text" class="form-control" name="tema_desc" id="tema_desc" placeholder="Serie o Temática" required>
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label>* Serie o Temática (descripción corta 100 caracteres)</label>
                                    <input type="text" class="form-control" name="tema_desc_corta" id="tema_desc_corta" placeholder="Serie o Temática (descripción corta)" required>
                                </div>
                            </div>         
                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Sección </label>
                                    <select class="form-control m-bot15" name="seccion_id" id="seccion_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar sección </option>
                                        @foreach($regseccion as $sec)
                                            <option value="{{$sec->seccion_id}}">{{$sec->seccion_id.' '.Trim($sec->seccion_desc)}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                            </div>

                            <div class="row">    
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('vertema')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\temasRequest','#nuevotema') !!}
@endsection

@section('javascrpt')
@endsection
