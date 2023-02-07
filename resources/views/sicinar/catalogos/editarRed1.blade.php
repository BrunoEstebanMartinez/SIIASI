@extends('sicinar.principal')

@section('title','Editar logo del medio informativo')

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
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú               
                <small> Catálogos - Medios informativos - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarred1',$regredes->rs_id], 'method' => 'PUT', 'id' => 'actualizarred1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">   
                                <div class="col-xs-2 form-group">
                                    
                                    <input type="hidden" name="rs_id" id="rs_id" value="{{$regredes->rs_id}}">    
                                    <label style="color:green; text-align:left; vertical-align: middle;">Id. </label>
                                    <td>&nbsp;&nbsp;{{$regredes->rs_id}} </td>                                    
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Red social  </label>
                                    {{$regredes->rs_desc}}
                                </div>
                            </div>                                       

                            <div class="row" style="background-color:#800000;color:white;text-align:center;">
                                <div class="col-xs-12 form-group">
                                    <label><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital en formato jpg, gif o png, NO deberá ser mayor a 2,500 kBytes.  </label>
                                </div>   
                            </div> 

                            @if (!empty($regredes->rs_foto1)||!is_null($regredes->rs_foto1))   
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital en formato jpg, gif o png</label><br>
                                        <label ><a href="/storage/{{$regredes->rs_foto1}}" class="btn btn-danger" title="Archivo digital en formato jpg, gif o png"><i class="fa fa-file-pdf-o"></i>jpg, gif o png
                                        </a>   &nbsp;&nbsp;&nbsp;{{$regredes->rs_foto1}}
                                        </label>
                                    </div>   
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="300" src="{{ asset('storage/'.$regredes->rs_foto1)}}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                        
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital en formato jpg, gif o png</label>
                                        <input type="file" class="text-md-center" style="color:red" name="rs_foto1" id="rs_foto1" placeholder="Subir archivo de digital en formato jpg, gif o png" >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">                            
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital en formato jpg, gif o png</label>
                                        <input type="file" class="text-md-center" style="color:red" name="rs_foto1" id="rs_foto1" placeholder="Subir archivo digital en formato jpg, gif o png" >
                                    </div>                                                
                                </div>
                            @endif  

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
    {!! JsValidator::formRequest('App\Http\Requests\red1Request','#actualizarred1') !!}
@endsection

@section('javascrpt')
@endsection

