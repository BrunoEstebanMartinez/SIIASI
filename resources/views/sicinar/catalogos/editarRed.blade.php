@extends('sicinar.principal')

@section('title','Editar redes sociales')

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
                <small> Catálogos - Redes sociales - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            
                        </div>
                        {!! Form::open(['route' => ['actualizarred',$regredes->rs_id], 'method' => 'PUT', 'id' => 'actualizarred']) !!}
                        <div class="box-body">
                            <div class="row">
                                <input type="hidden" name="rs_id" id="rs_id" value="{{$regredes->rs_id}}"> 
                                <div class="col-md-2 offset-md-12">
                                    <label>Id: {{$regredes->rs_id}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label>Red social </label>
                                    <input type="text" class="form-control" name="rs_desc" id="rs_desc" placeholder="Red social" value="{{$regredes->rs_desc}}" required>
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
                                    <a href="{{route('verredes')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\redRequest','#actualizarred') !!}
@endsection

@section('javascrpt')
@endsection
