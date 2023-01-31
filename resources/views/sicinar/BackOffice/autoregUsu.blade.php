@extends('sicinar.principalauto')

@section('title','Gestión de Usuarios')

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <div class="content-wrapper" id="principal">
        <section class="content-header">
            <h1><i class="fa fa-users"></i>Auto registro </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Usuario</a></li>
                <li class="active">Nuevo</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Datos del responsable de gestionar la OSC en el Registro Social Estatal</b></h3>
                                
                        </div>
                        {!! Form::open(['route' => 'regaltausu', 'method' => 'POST', 'id' => 'regaltausu']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="paterno" id="paterno" placeholder="Apellido Paterno" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="materno" id="materno" placeholder="Apellido Materno" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre(s)" required>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Login de usuario (ejemplo: humberto@hotmail.com)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                                </div>
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <input type="text" class="form-control" name="osc_desc" id="osc_desc" placeholder="Nombre de la OSC. (Máximo 100 caractéres)." required>
                                </div>                            
                                <div class="col-xs-4 form-group">
                                    <input type="number" min="0" max="9999999999" class="form-control" name="telefono" id="telefono" placeholder="Teléfono de contacto" required>
                                </div>                                                                 
                            </div>

                            @if(count($errors) > 0)
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li><i class="fa fa-warning"></i> {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <br>
                            <div class="col-md-12 offset-md-5">
                                {!! Form::submit('Autoregistrarse',['class' => 'btn btn-danger btn-block']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>
    </div>
@endsection
 
@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\autoregaltaUsuRequest','#regaltausu') !!}
@endsection

