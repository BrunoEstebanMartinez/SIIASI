@extends('sicinar.principal')

@section('title','Editar nota de red social')

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
                <small> Notas informativas  </small>                
                <small> Redes sociales - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarnotared',$regnotaredes->rs_folio], 'method' => 'PUT', 'id' => 'actualizarnotared', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class = "row">
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regnotaredes->periodo_id}}"> 
                                    <input type="hidden" name="rs_folio"   id="rs_folio"   value="{{$regnotaredes->rs_folio}}">    

                                    <label>Fecha</label>
                                    <input class = "form-control" type="date" id="datepickerOf" name = "datepickerOf" value = "{{ date('Y-m-d', strtotime($regnotaredes->rs_fec_nota)) }}">
                                </div>
                                
                                <div class="col-xs-10 form-group align:rigth">
                                    <label style="color:green; text-align:rigth; vertical-align: middle;">Folio de sistema: </label>
                                    &nbsp;&nbsp;{{$regnotaredes->rs_folio}}
                                </div>                                
                            </div>

                            <div class="row" style = "display:none">
                                <div class="col-xs-1">
                                    <label>Fecha de la nota:</label>
                                </div>

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "dia_id1" id = "dia_id1" Value="{{$regnotaredes->dia_id1}}" placeholder = "dd" >
                                </div>

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "mes_id1" id = "mes_id1" Value="{{$regnotaredes->mes_id1}}" placeholder = "mm">
                                </div> 

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "periodo_id1" id = "periodo_id1" Value="{{$regnotaredes->periodo_id1}}" placeholder = "yyyy">
                                    <input class = "form-control" type="text" name = "periodo_id" id = "periodo_id" Value="{{$regnotaredes->periodo_id}}" placeholder = "yyyy">
                                </div> 
                            </div>


                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Título </label>
                                    <input type="text" class="form-control" name="rs_titulo" id="rs_titulo" placeholder="Titulo de la nota" Value="{{$regnotaredes->rs_titulo}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Link </label>
                                    <input type="text" class="form-control" name="rs_link" id="rs_link" placeholder="Link o url de la nota" Value="{{$regnotaredes->rs_link}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Resumen de la Nota (4,000 caracteres)</label>
                                    <textarea class="form-control" name="rs_nota" id="rs_nota" rows="4" cols="120" placeholder="Resumen de la Nota (4,000 caracteres)" required>{{Trim($regnotaredes->rs_nota)}}
                                    </textarea>
                                </div>                                
                            </div>         
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Resumen de la Nota 2 (4,000 caracteres)</label>
                                    <textarea class="form-control" name="rs_nota2" id="rs_nota2" rows="4" cols="120" placeholder="Resumen de la Nota 2 (4,000 caracteres)" required>{{Trim($regnotaredes->rs_nota2)}}
                                    </textarea>
                                </div>                                
                            </div>                                     

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Resumen de la Nota IA (4,000 caracteres)</label>
                                    <textarea class="form-control" name="rs_ia" id="rs_ia" rows="4" cols="120" placeholder="Resumen de la Nota IA (4,000 caracteres)" required>{{Trim($regnotaredes->rs_ia)}}
                                    </textarea>
                                </div>                                
                            </div>         
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Autor </label>
                                    <input type="text" class="form-control" name="rs_autor" id="rs_autor" placeholder="Autor" Value="{{$regnotaredes->rs_autor}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Red social  </label>
                                    <select class="form-control m-bot15" name="rs_id" id="rs_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar red social </option>
                                        @foreach($regredes as $red)
                                            @if($red->rs_id == $regnotaredes->rs_id)
                                                <option value="{{$red->rs_id}}" selected>{{trim($red->rs_desc)}}</option>
                                            @else                                        
                                                <option value="{{$red->rs_id}}">{{trim($red->rs_desc)}}</option>
                                            @endif                                                                             
                                        @endforeach
                                    </select>                                    
                                </div>                       
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Likes (Solo digitar número) </label>
                                    <input required autocomplete="off" id="rs_likes" name="rs_likes" min="0" max="999999999" class="form-control" type="decimal(9,0)" placeholder="Solo digitar números" value="{{$regnotaredes->rs_likes}}">
                                </div>
                            </div>                            
                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Reposteos (Solo digitar números) </label>
                                    <input required autocomplete="off" id="rs_reposteos" name="rs_reposteos" min="0" max="999999999" class="form-control" type="decimal(9,0)" placeholder="Solo digitar números" value="{{$regnotaredes->rs_reposteos}}">
                                </div>
                            </div>                            
                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Comentarios (Solo digitar números) </label>
                                    <input required autocomplete="off" id="rs_comen" name="rs_comen" min="0" max="999999999" class="form-control" type="decimal(9,0)" placeholder="Solo digitar números" value="{{$regnotaredes->rs_comen}}">
                                </div>
                            </div>                            
                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Alcance (Solo digitar números) </label>
                                    <input required autocomplete="off" id="rs_alcance" name="rs_alcance" min="0" max="999999999" class="form-control" type="decimal(9,0)" placeholder="Solo digitar números" value="{{$regnotaredes->rs_alcance}}">
                                </div>
                            </div>                                                        
                            <div class="row">                            
                                <div class="col-xs-4 form-group">                        
                                    <label>Calificación </label>
                                    <select class="form-control m-bot15" name="rs_calif" id="rs_calif" required>
                                    @if($regnotaredes->rs_calif == '3')
                                            <option value="1"         >Positiva </option>
                                            <option value="2"         >Neutra   </option>
                                            <option value="3" selected>Negativa </option>
                                    @else
                                        @if($regnotaredes->rs_calif == '2')
                                                <option value="1"         >Positiva  </option>
                                                <option value="2" selected>Neutra    </option>
                                                <option value="3"         >Negativa  </option>
                                       @else
                                            @if($regnotaredes->rs_calif == '1')
                                                    <option value="1" selected>Positiva   </option>
                                                    <option value="2"         >Neutra     </option>
                                                    <option value="3"         >Negativa   </option>
                                            @else
                                                    <option value="1"         >Positiva   </option>
                                                    <option value="2"         >Neutra     </option>
                                                    <option value="3"         >Negativa   </option>  
                                                    <option value="0" selected>Sin calificación </option>                                                    
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
    {!! JsValidator::formRequest('App\Http\Requests\notaredRequest','#actualizarnotared') !!}
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

