@extends('sicinar.principal')

@section('title','Editar datos del personal')

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
                <small> Catálogos </small>                
                <small> Personal - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarpersonal',$regpersonal->folio], 'method' => 'PUT', 'id' => 'actualizarpersonal', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">    
                                <div class="col-xs-8 form-group">
                                    <input type="hidden" id="uadmon_id" name="uadmon_id" value="{{$regpersonal->uadmon_id}}">  
                                    <label >Unidad Admon. </label><br>
                                    <td style="text-align:left; vertical-align: middle;">   
                                        @foreach($reguadmon as $iap)
                                            @if($iap->uadmon_id == $regpersonal->uadmon_id)
                                                {{$iap->uadmon_desc}}
                                                @break
                                            @endif
                                        @endforeach
                                    </td>                                     
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <input type="hidden" id="folio" name="folio" value="{{$regpersonal->folio}}">  
                                    <label >Clave de servidor público: <br>{{$regpersonal->folio}} </label> 
                                </div>    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Apellido paterno </label>
                                    <input type="text" class="form-control" name="primer_apellido" id="primer_apellido" placeholder="Apellido paterno" value="{{$regpersonal->primer_apellido}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="segundo_apellido" id="segundo_apellido" placeholder="Apellido materno" value="{{$regpersonal->segundo_apellido}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombre(s)" value="{{$regpersonal->nombres}}" required>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >CURP </label>
                                    <input type="text" class="form-control" name="curp" id="curp" placeholder="CURP" value="{{$regpersonal->curp}}" onkeypress="return soloAlfaSE(event)" required>
                                </div>        
                                <div class="col-xs-4 form-group">                        
                                    <label>Sexo </label>
                                    <select class="form-control m-bot15" name="sexo" id="sexo" required>
                                        @if($regpersonal->sexo == 'H')
                                            <option value="H" selected>Hombre </option>
                                            <option value="M">         Mujer  </option>
                                        @else
                                            <option value="H">         Hombre </option>
                                            <option value="M" selected>Mujer  </option>
                                        @endif
                                    </select>
                                </div>                                   
                            </div>                                

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio (Calle, no.ext/int.) </label>
                                    <input type="text" class="form-control" name="domicilio" id="domicilio" value="{{trim($regpersonal->domicilio)}}" placeholder="Domicilio donde vive" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Colonia)</label>
                                    <input type="text" class="form-control" name="colonia" id="colonia" value="{{trim($regpersonal->colonia)}}" placeholder="Colonia" required>
                                </div>                           
                                <div class="col-xs-4 form-group">
                                    <label >Código postal </label>
                                    <input type="number" min="0" max="99999" class="form-control" name="cp" id="cp" placeholder="Código postal" value="{{$regpersonal->cp}}" required>
                                </div>                                               
                            </div>

                            <div class="row">                                                                               
                                <div class="col-xs-6 form-group">
                                    <label >Localidad </label>
                                    <input type="text" class="form-control" name="localidad" id="localidad" placeholder="Localidad" value="{{Trim($regpersonal->localidad)}}" required>
                                </div>                                                                     
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >e-mail institucional </label>
                                    <input type="text" class="form-control" name="e_mail" id="e_mail" placeholder="Correo electrónico institucional" value="{{$regpersonal->e_mail}}" required>
                                </div>                                                                                                          
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="{{$regpersonal->telefono}}" required>
                                </div>                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Puesto  </label>
                                    <input type="text" class="form-control" name="puesto" id="puesto" placeholder="Puesto" value="{{$regpersonal->puesto}}" required>
                                </div>                                               
                            </div>               
                                            
                            <div class="row">                                             
                                <div class="col-xs-4 form-group">                        
                                    <label>Personal Activo o Inactivo </label>
                                    <select class="form-control m-bot15" name="status_1" id="status_1" required>
                                        @if($regpersonal->status_1 == 'S')
                                            <option value="S" selected>Activo  </option>
                                            <option value="N">         Inactivo</option>
                                        @else
                                            <option value="S">         Activo  </option>
                                            <option value="N" selected>Inactivo</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Descripción de actividades (300 carácteres)</label>
                                    <textarea class="form-control" name="obs_1" id="obs_1" rows="3" cols="120" placeholder="Descripción de actividades (300 carácteres)" required>{{Trim($regpersonal->obs_1)}}
                                    </textarea>
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
                                    <a href="{{route('verpersonal')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\personalRequest','#actualizarpersonal') !!}
@endsection

@section('javascrpt')
<script>
    function soloNumeros(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "1234567890";
       especiales = "8-37-39-46";
       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }    

    function soloAlfa(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ.";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }

    function soloLetras(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
    function general(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890,.;:-_<>!%()=?¡¿/*+";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
    function soloAlfaSE(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }    
</script>

<script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        startDate: '-29y',
        endDate: '-18y',
        startView: 2,
        maxViewMode: 2,
        clearBtn: true,        
        language: "es",
        autoclose: true
    });
</script>
@endsection

