@extends('sicinar.principal')

@section('title','Nueva Unidad administrativa ')

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
                <small> Catálogos - Unidad Admon. - Nueva</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'altanuevauadmon', 'method' => 'POST','id' => 'nuevauadmon']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-3 form-group">                                  
                                    <label >* Id. Unidad Administrativa </label>
                                    <input type="text" class="form-control" id="uadmon_id" name="uadmon_id" placeholder="Digitar Id. unidad Administrativa" required>
                                </div>
                            </div>                                                
                            <div class="row">
                                <div class="col-xs-6 form-group">                                  
                                    <label >* Unidad Administrativa </label>
                                    <input type="text" class="form-control" id="uadmon_desc" name="uadmon_desc" placeholder="Digitar unidad Administrativa" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('veruadmon')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\uadmonRequest','#nuevauadmon') !!}
@endsection

@section('javascrpt')
@endsection
