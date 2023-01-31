@extends('sicinar.principal')

@section('title','Nuevo Recepción de documentos')

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
    <meta charset="utf-8">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small> Oficialia de partes</small>                
                <small> Rcepeción de documentos - Nuevo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'altanuevarecepcion', 'method' => 'POST','id' => 'nuevarecepcion', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Periodo fiscal </label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal</option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>

                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <label >Fecha de oficio - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodos as $anio)
                                            <option value="{{$anio->periodo_id}}">{{$anio->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-2 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes</option>
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-2 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar día</option>
                                        @foreach($regdias as $dia)
                                            <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>

                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <label >Fecha de recibido - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id2" id="periodo_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodos as $anio)
                                            <option value="{{$anio->periodo_id}}">{{$anio->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-2 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes</option>
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-2 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id2" id="dia_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar día</option>
                                        @foreach($regdias as $dia)
                                            <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >No. oficio </label>
                                    <input type="text" class="form-control" name="ent_noficio" id="ent_noficio" placeholder="No. de oficio" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Destinatario </label>
                                    <input type="text" class="form-control" name="ent_destin" id="ent_destin" placeholder="Destinatario" required>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label >Remitente </label>
                                    <input type="text" class="form-control" name="ent_remiten" id="ent_remiten" placeholder="Remitente" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Unidad admon. remitente </label>
                                    <input type="text" class="form-control" name="ent_uadmon" id="ent_uadmon" placeholder="Unidad admon. remite oficio" required>
                                </div>  
                            </div>                                                      
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Asunto (4,000 caracteres)</label>
                                    <textarea class="form-control" name="ent_asunto" id="ent_asunto" rows="2" cols="120" placeholder="Asunto 4,000 caracteres" required>
                                    </textarea>
                                </div>                                
                            </div>                                                        

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Se turna a:  </label>
                                    <select class="form-control m-bot15" name="cve_sp" id="cve_sp" required>
                                        <option selected="true" disabled="disabled">Seleccionar a quien se turna </option>
                                        @foreach($regpersonal as $perso)
                                            <option value="{{$perso->folio}}">{{$perso->folio.' '.Trim($perso->nombre_completo)}}</option>
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
                                            <option value="{{$tema->tema_id}}">{{Trim($tema->tema_desc)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                       
                            </div>
 
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital en formato PDF, NO deberá ser mayores a 2,500 kBytes.  </label>
                                </div>   
                            </div>  
                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital del documento en formato PDF </label>
                                    <input type="file" class="text-md-center" style="color:red" name="ent_arc1" id="ent_arc1" placeholder="Subir archivo digital del documento en formato PDF">
                                </div>   
                            </div>    

                            <div class="row">
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
    {!! JsValidator::formRequest('App\Http\Requests\recepcionRequest','#nuevarecepcion') !!}
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