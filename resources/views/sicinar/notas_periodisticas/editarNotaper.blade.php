@extends('sicinar.principal')

@section('title','Editar nota periodistica')

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
                <small> Gestión interna   </small>                
                <small> Notas periodísticas - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarnotaper',$regnotamedio->nm_folio], 'method' => 'PUT', 'id' => 'actualizarnotaper', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class = "row">
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regnotamedio->periodo_id}}"> 
                                    <input type="hidden" name="nm_folio"   id="nm_folio"   value="{{$regnotamedio->nm_folio}}">    

                                    <label>Fecha</label>
                                    <input class = "form-control" type="date" id="datepickerOf" name = "datepickerOf" value = "{{ date('Y-m-d', strtotime($regnotamedio->nm_fec_nota)) }}">
                                </div>
                                
                                <div class="col-xs-10 form-group align:rigth">
                                    <label style="color:green; text-align:rigth; vertical-align: middle;">Folio de sistema: </label>
                                    &nbsp;&nbsp;{{$regnotamedio->nm_folio}}
                                </div>                                
                            </div>

                            <div class="row" style = "display:none">
                                <div class="col-xs-1">
                                    <label>Fecha de la nota periodistíca:</label>
                                </div>

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "dia_id1" id = "dia_id1" Value="{{$regnotamedio->dia_id1}}" placeholder = "dd" >
                                </div>

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "mes_id1" id = "mes_id1" Value="{{$regnotamedio->mes_id1}}" placeholder = "mm">
                                </div> 

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "periodo_id1" id = "periodo_id1" Value="{{$regnotamedio->periodo_id1}}" placeholder = "yyyy">
                                    <input class = "form-control" type="text" name = "periodo_id" id = "periodo_id" Value="{{$regnotamedio->periodo_id}}" placeholder = "yyyy">
                                </div> 
                            </div>


                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Título </label>
                                    <input type="text" class="form-control" name="nm_titulo" id="nm_titulo" placeholder="Titulo de la nota" Value="{{$regnotamedio->nm_titulo}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Link </label>
                                    <input type="text" class="form-control" name="nm_link" id="nm_link" placeholder="Link o url de la nota" Value="{{$regnotamedio->nm_link}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Resumen de la Nota (4,000 caracteres)</label>
                                    <textarea class="form-control" name="nm_nota" id="nm_nota" rows="4" cols="120" placeholder="Resumen de la Nota (4,000 caracteres)" required>{{Trim($regnotamedio->nm_nota)}}
                                    </textarea>
                                </div>                                
                            </div>         
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Resumen de la Nota 2 (4,000 caracteres)</label>
                                    <textarea class="form-control" name="nm_nota2" id="nm_nota2" rows="4" cols="120" placeholder="Resumen de la Nota 2 (4,000 caracteres)" required>{{Trim($regnotamedio->nm_nota2)}}
                                    </textarea>
                                </div>                                
                            </div>                                     

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Resumen de la Nota IA (4,000 caracteres)</label>
                                    <textarea class="form-control" name="nm_ia" id="nm_ia" rows="4" cols="120" placeholder="Resumen de la Nota IA (4,000 caracteres)" required>{{Trim($regnotamedio->nm_ia)}}
                                    </textarea>
                                </div>                                
                            </div>         
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Autor </label>
                                    <input type="text" class="form-control" name="nm_autor" id="nm_autor" placeholder="Autor" Value="{{$regnotamedio->nm_autor}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Medio informátivo  </label>
                                    <select class="form-control m-bot15" name="medio_id" id="medios_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar medio informativo </option>
                                        @foreach($regmedios as $medio)
                                            @if($medio->medio_id == $regnotamedio->medio_id)
                                                <option value="{{$medio->medio_id}}" selected>{{trim($medio->medio_desc)}}</option>
                                            @else                                        
                                                <option value="{{$medio->medio_id}}">{{trim($medio->medio_desc)}}</option>
                                            @endif                                                                             
                                        @endforeach
                                    </select>                                    
                                </div>                       
                            </div>
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >Tipo de nota  </label>
                                    <select class="form-control m-bot15" name="tipon_id" id="tipon_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de nota </option>
                                        @foreach($regtiponota as $tipo)
                                            @if($tipo->tipon_id == $regnotamedio->tipon_id)
                                                <option value="{{$tipo->tipon_id}}" selected>{{trim($tipo->tipon_desc)}}</option>
                                            @else                                        
                                                <option value="{{$tipo->tipon_id}}">{{trim($tipo->tipon_desc)}}</option>
                                            @endif                                                                             
                                        @endforeach
                                    </select>                                    
                                </div>                       
                            </div>

                            <div class="row">                            
                                <div class="col-xs-4 form-group">                        
                                    <label>Calificación </label>
                                    <select class="form-control m-bot15" name="nm_calif" id="nm_calif" required>
                                    @if($regnotamedio->nm_calif == '3')
                                            <option value="1"         >Positiva </option>
                                            <option value="2"         >Neutra   </option>
                                            <option value="3" selected>Negativa </option>
                                    @else
                                        @if($regnotamedio->nm_calif == '2')
                                                <option value="1"         >Positiva  </option>
                                                <option value="2" selected>Neutra    </option>
                                                <option value="3"         >Negativa  </option>
                                       @else
                                            @if($regnotamedio->nm_calif == '1')
                                                    <option value="1" selected>Positiva   </option>
                                                    <option value="2"         >Neutra     </option>
                                                    <option value="3"         >Negativa   </option>
                                            @endif
                                        @endif
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
                                    <a href="{{route('vernotasper')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src = "{{ asset('js/datepicker.js') }}"></script>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\notaperRequest','#actualizarnotaper') !!}
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

