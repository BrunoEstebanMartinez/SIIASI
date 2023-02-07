@extends('sicinar.principal')

@section('title','Editar Unidad Administrativa')

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
                <small> Catálogos - Unidades Admon. - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        
                        {!! Form::open(['route' => ['actualizaruadmon',$reguadmon->uadmon_id], 'method' => 'PUT', 'id' => 'actualizaruadmon']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <input type="hidden" name="uadmon_id" id="uadmon_id" value="{{$reguadmon->uadmon_id}}"> 
                                    <label>Id.: {{$reguadmon->uadmon_id}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label>* Unidad administrativa </label>
                                    <input type="text" class="form-control" name="uadmon_desc" id="uadmon_desc" placeholder="Unidad administrativa" value="{{$reguadmon->uadmon_desc}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>* Activa o Inactiva </label>
                                    <select class="form-control m-bot15" name="uadmon_status" required>
                                        @if($reguadmon->uadmon_status == 'S')
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
    {!! JsValidator::formRequest('App\Http\Requests\uadmonRequest','#actualizaruadmon') !!}
@endsection

@section('javascrpt')
@endsection
