@extends('sicinar.principal')

@section('title','Editar medio informativo')

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
                <small> Catálogos - Medios informativos - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            
                        </div>
                        {!! Form::open(['route' => ['actualizarmedio',$regmedio->medio_id], 'method' => 'PUT', 'id' => 'actualizarmedio']) !!}
                        <div class="box-body">
                            <div class="row">
                                <input type="hidden" name="medio_id" id="medio_id" value="{{$regmedio->medio_id}}"> 
                                <div class="col-md-2 offset-md-12">
                                    <label>Id: {{$regmedio->medio_id}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>Medio informativo</label>
                                    <input type="text" class="form-control" name="medio_desc" id="medio_desc" placeholder="Medio informativo" value="{{$regmedio->medio_desc}}" required>
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>Link o URL</label>
                                    <input type="text" class="form-control" name="medio_link" id="medio_link" placeholder="Link o URL" value="{{Trim($regmedio->medio_link)}}" required>
                                </div>
                            </div>                                 
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>Director y/o responsable </label>
                                    <input type="text" class="form-control" name="medio_obs1" id="medio_obs1" placeholder="Director y/o responsable" value="{{Trim($regmedio->medio_obs1)}}" required>
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
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('vermedio')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\medioRequest','#actualizarmedio') !!}
@endsection

@section('javascrpt')
@endsection
