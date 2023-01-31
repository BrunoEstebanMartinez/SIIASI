@extends('sicinar.principal')

@section('title','Editar recepcion de documentos')

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
                <small> Oficialia de partes   </small>                
                <small> Recepción de documentos - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarrecepcion',$regrecepcion->folio], 'method' => 'PUT', 'id' => 'actualizarrecpcion', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">   
                                <div class="col-xs-4 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regrecepcion->periodo_id}}"> 
                                    <input type="hidden" name="folio"      id="folio"      value="{{$regrecepcion->folio}}">    
                                    <label style="color:green; text-align:left; vertical-align: middle;">Periodo fiscal: </label>
                                    <td>&nbsp;&nbsp;{{$regrecepcion->periodo_id}} </td>                                    
                                </div>                                
                                <div class="col-xs-4 form-group align:rigth">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Folio de sistema: </label>
                                    <td>&nbsp;&nbsp;{{$regrecepcion->folio}}</td>    
                                </div>
                            </div>
                            <div class="row">    
                                <label>Fecha de documento </label><br>
                                <div class="col-xs-4 form-group">
                                    <label >Año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodos as $anio)
                                            @if($anio->periodo_id == $regrecepcion->periodo_id1)
                                                <option value="{{$anio->periodo_id}}" selected>{{trim($anio->periodo_desc)}}</option>
                                            @else                                        
                                                <option value="{{$anio->periodo_id}}">{{trim($anio->periodo_desc)}}</option>
                                            @endif    
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-2 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes</option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regrecepcion->mes_id1)
                                                <option value="{{$mes->mes_id}}" selected>{{trim($mes->mes_desc)}}</option>
                                            @else                                        
                                                <option value="{{$mes->mes_id}}">{{trim($mes->mes_desc)}}</option>
                                            @endif    
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-2 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar día</option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regrecepcion->dia_id1)
                                                <option value="{{$dia->dia_id}}" selected>{{trim($dia->dia_desc)}}</option>
                                            @else                                        
                                                <option value="{{$dia->dia_id}}">{{trim($dia->dia_desc)}}</option>
                                            @endif                                                                             
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>

                            <div class="row">    
                                <label>Fecha de recibido </label><br>                            
                                <div class="col-xs-4 form-group">
                                    <label>Año </label>
                                    <select class="form-control m-bot15" name="periodo_id2" id="periodo_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodos as $anio)
                                            @if($anio->periodo_id == $regrecepcion->periodo_id2)
                                                <option value="{{$anio->periodo_id}}" selected>{{trim($anio->periodo_desc)}}</option>
                                            @else                                        
                                                <option value="{{$anio->periodo_id}}">{{trim($anio->periodo_desc)}}</option>
                                            @endif    
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-2 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes</option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regrecepcion->mes_id2)
                                                <option value="{{$mes->mes_id}}" selected>{{trim($mes->mes_desc)}}</option>
                                            @else                                        
                                                <option value="{{$mes->mes_id}}">{{trim($mes->mes_desc)}}</option>
                                            @endif    
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-2 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id2" id="dia_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar día</option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regrecepcion->dia_id2)
                                                <option value="{{$dia->dia_id}}" selected>{{$dia->dia_id.' '.trim($dia->dia_desc)}}</option>
                                            @else                                        
                                                <option value="{{$dia->dia_id}}">{{$dia->dia_id.' '.trim($dia->dia_desc)}}</option>
                                            @endif                                                                             
                                        @endforeach
                                    </select>                                    
                                </div>                                     
                            </div>
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >No. oficio </label>
                                    <input type="text" class="form-control" name="ent_noficio" id="ent_noficio" placeholder="No. de oficio" Value="{{$regrecepcion->ent_noficio}}" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Destinatario </label>
                                    <input type="text" class="form-control" name="ent_destin" id="ent_destin" placeholder="Destinatario" Value="{{$regrecepcion->ent_destin}}" required>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label >Remitente </label>
                                    <input type="text" class="form-control" name="ent_remiten" id="ent_remiten" placeholder="Remitente" Value="{{$regrecepcion->ent_remiten}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Unidad admon. remitente </label>
                                    <input type="text" class="form-control" name="ent_uadmon" id="ent_uadmon" placeholder="Unidad admon. remite oficio" Value="{{$regrecepcion->ent_uadmon}}" required>
                                </div>  
                            </div>                                                      
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Asunto (4,000 caracteres)</label>
                                    <textarea class="form-control" name="ent_asunto" id="ent_asunto" rows="2" cols="120" placeholder="Asunto 4,000 caracteres" required>{{Trim($regrecepcion->ent_asunto)}}
                                    </textarea>
                                </div>                                
                            </div>                                                        

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Se turna a:   </label>
                                    <select class="form-control m-bot15" name="cve_sp" id="cve_sp" required>
                                        <option selected="true" disabled="disabled">Seleccionar a quien se turna </option>
                                        @foreach($regpersonal as $perso)
                                            @if($perso->folio == $regrecepcion->cve_sp)
                                                <option value="{{$perso->folio}}" selected>{{$perso->folio.' '.Trim($perso->nombre_completo)}}</option>
                                            @else                                        
                                                <option value="{{$perso->folio}}">{{$perso->folio.' '.Trim($perso->nombre_completo)}}</option>
                                            @endif                                            
                                        @endforeach
                                    </select>                                    
                                </div>                      
                            </div> 
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Temática  </label>
                                    <select class="form-control m-bot15" name="tema_id" id="tema_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar temática </option>
                                        @foreach($regtema as $tema)
                                            @if($tema->tema_id == $regrecepcion->tema_id)
                                                <option value="{{$tema->tema_id}}" selected>{{trim($tema->tema_desc)}}</option>
                                            @else                                        
                                                <option value="{{$tema->tema_id}}">{{trim($tema->tema_desc)}}</option>
                                            @endif                                                                             
                                        @endforeach
                                    </select>                                    
                                </div>                       
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 caracteres)</label>
                                    <textarea class="form-control" name="ent_obs1" id="ent_obs1" rows="2" cols="120" placeholder="Asunto 4,000 caracteres" required>{{Trim($regrecepcion->ent_obs1)}}
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
                                    <a href="{{route('verrecepcion')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\recepcionRequest','#actualizarrepcion') !!}
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

