@extends('sicinar.principal')

@section('title','Editar Temática')

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
                <small> Catálogos - Series o Temáticas - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            
                        </div>
                        {!! Form::open(['route' => ['actualizartema',$regtema->tema_no], 'method' => 'PUT', 'id' => 'actualizartema']) !!}
                        <div class="box-body">
                            <div class="row">
                                <input type="hidden" name="tema_no" id="tema_no" value="{{$regtema->tema_no}}"> 
                                <div class="col-md-2 offset-md-12">
                                    <label>Clave: {{$regtema->tema_no}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>* Id. serie o temática </label>
                                    <input type="text" class="form-control" name="tema_id" id="tema_id" placeholder="Id. de la serie o temática" value="{{$regtema->tema_id}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label>* Serie o Temática 250 caracteres</label>
                                    <input type="text" class="form-control" name="tema_desc" id="tema_desc" placeholder="Serie o Temática" value="{{$regtema->tema_desc}}" required>
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label>* Serie o Temática (descripción corta 100 caracteres)</label>
                                    <input type="text" class="form-control" name="tema_desc_corta" id="tema_desc_corta" placeholder="Serie o Temática (descripción corta)" value="{{$regtema->tema_desc_corta}}" required>
                                </div>
                            </div>         
                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Sección </label>
                                    <select class="form-control m-bot15" name="seccion_id" id="seccion_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar sección </option>
                                        @foreach($regseccion as $sec)
                                            @if($sec->seccion_id == $regtema->seccion_id)
                                                <option value="{{$sec->seccion_id}}" selected>{{$sec->seccion_id.' '.Trim($sec->seccion_desc)}}</option>
                                            @else                                        
                                                <option value="{{$sec->seccion_id}}">{{$sec->seccion_id.' '.Trim($sec->seccion_desc)}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>   
                            </div>

                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>* Activa o Inactiva </label>
                                    <select class="form-control m-bot15" name="tema_status" required>
                                        @if($regtema->tema_status == 'S')
                                            <option value="S" selected>SI</option>
                                            <option value="N">NO</option>
                                        @else
                                            <option value="S">SI</option>
                                            <option value="N" selected>NO</option>
                                        @endif
                                    </select>
                                </div>
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
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('vertema')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\temasRequest','#actualizartema') !!}
@endsection

@section('javascrpt')
@endsection
