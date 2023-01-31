@extends('sicinar.principal')

@section('title','Editar seccion')

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
            <h1>
                Menú
                <small> Catálogos - Secciones - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">

                        </div>
                        {!! Form::open(['route' => ['actualizarseccion',$regseccion->seccion_id], 'method' => 'PUT', 'id' => 'actualizarseccion']) !!}
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-3 offset-md-12">
                                    <input type="hidden" name="seccion_id" id="seccion_id" value="{{$regseccion->seccion_id}}"> 
                                    <label>Clave: {{$regseccion->seccion_id}}</label>
                                </div>
                            </div>    
                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label>Sección </label>
                                    <input type="text" class="form-control" id="seccion_desc" name="seccion_desc" placeholder="Sección" value="{{$regseccion->seccion_desc}}" required>
                                </div>
                            </div>    
                            <div class="row">                                     
                                <div class="col-xs-3 form-group">
                                    <label>Tipo </label>
                                    <select class="form-control m-bot15" name="seccion_tipo" required>
                                        @if(Trim($regseccion->seccion_tipo) == 'SUSTANTIVA')
                                            <option value="SUSTANTIVA" selected>SUSTANTIVA</option>
                                            <option value="COMUN"              >COMÚN     </option>
                                        @else
                                            <option value="SUSTANTIVA"    >SUSTANTIVA</option>
                                            <option value="COMUN" selected>COMÚN     </option>
                                        @endif
                                    </select>                                    
                                </div>
                            </div>    
                            <div class="row">                                                                 
                                <div class="col-xs-3 form-group">
                                    <label>Activa o Inactiva </label>
                                    <select class="form-control m-bot15" name="seccion_status" required>
                                        @if($regseccion->seccion_status == 'S')
                                            <option value="S" selected>SI</option>
                                            <option value="N">NO</option>
                                        @else
                                            <option value="S">SI</option>
                                            <option value="N" selected>NO</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row">    
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verseccion')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
                                </div>
                            </div><br>
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
    {!! JsValidator::formRequest('App\Http\Requests\seccionRequest','#actualizarseccion') !!}
@endsection

@section('javascrpt')
@endsection
