@extends('sicinar.principal')

@section('title','Nueva nota periodistica')

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
                <small> Gestión interna </small>                
                <small> Notas periodísticas - Nueva</small>                
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'altanuevanotaper', 'method' => 'POST','id' => 'nuevanotaper', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            @php 
                                $currentDate = date('Y-m-d');
                                $currentYear = date('Y');
                                $currentDay = date('d');
                                $currentMonth = date('m');
                            @endphp

                            <div class = "row">
                                <div class="col-xs-2 form-group">
                                    <label>Fecha</label>
                                    <input class = "form-control" type="date" id="datepickerOf" name = "datepickerOf" value = "{{ $currentDate }}">
                                </div>
                            </div>

                            <div class="row" style = "display:none">
                                <div class="col-xs-1">
                                    <label>Fecha de la nota periodistíca:</label>
                                </div>

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "dia_id1" id = "dia_id1" value = "{{ $currentDay }}" placeholder = "dd" >
                                </div>

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "mes_id1" id = "mes_id1" value = "{{ $currentMonth }}" placeholder = "mm">
                                </div> 

                                <div class="col-xs-1">
                                    <input class = "form-control" type="text" name = "periodo_id1" id = "periodo_id1" value = "{{ $currentYear }}" placeholder = "yyyy">
                                    <input class = "form-control" type="text" name = "periodo_id" id = "periodo_id" value = "{{ $currentYear }}" placeholder = "yyyy">
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Título </label>
                                    <input type="text" class="form-control" name="nm_titulo" id="nm_titulo" placeholder="Titulo de la nota" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Link </label>
                                    <input type="text" class="form-control" name="nm_link" id="nm_link" placeholder="Link o url de la nota">
                                    <span id = "aux" name = ""></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Link aux </label>
                                    <input type="text" class="form-control" name="nm_link_aux" id="nm_link_aux" placeholder="Link o url de la nota">
                                </div>
                            </div>

                          
                            <div class="row">                               
                                <div class="col-md-12 offset-md-5">
                                    <label >Resumen de la Nota (4,000 caracteres)</label>
                                    <textarea class="form-control" name="nm_nota" id="nm_nota" rows="5" cols="120" placeholder="Resumen de la nota periodistíca (4,000 carácteres)" required>
                                    </textarea>
                                </div>                                
                            </div>
                            <div class="row">                               
                                <div class="col-md-12 offset-md-5">
                                    <label >Resumen de la Nota 2 (4,000 caracteres)</label>
                                    <textarea class="form-control" name="nm_nota2" id="nm_nota2" rows="5" cols="120" placeholder="Resumen de la nota periodistíca 2 (4,000 carácteres)" required>
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">                               
                                <div class="col-md-12 offset-md-5">
                                <label >Resumen de la Nota IA (4,000 caracteres)</label>
                                    <textarea class="form-control" name="nm_ia" id="nm_ia" rows="5" cols="120" placeholder="Resumen de la nota periodistíca IA (4,000 carácteres)" required></textarea> 
                                    <p id = "onPaidLoad"></p>
                                </div>                                
                            </div>

                            <div class = "row">
                                    <div class="col-md-12">
                                        <button class = "" id = "airesponse" name = "airesponse">Crear</button>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Autor </label>
                                    <input type="text" class="form-control" name="nm_autor" id="nm_autor" placeholder="Autor" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Medio informátivo  </label>
                                    <select class="form-control m-bot15" name="medio_id" id="medio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar medio </option>
                                        @foreach($regmedios as $medio)
                                            <option value="{{$medio->medio_id}}">{{Trim($medio->medio_desc)}}</option>
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
                                            <option value="{{$tipo->tipon_id}}">{{Trim($tipo->tipon_desc)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                       
                            </div>                            
 
                             <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >Calificación   </label>
                                    <select class="form-control m-bot15" name="nm_calif" id="nm_calif" required>
                                        <option selected="true" disabled="disabled">Selecciona calificación</option>
                                        <option value="1">Positiva  </option>
                                        <option value="2">Neutra    </option>
                                        <option value="3">Negativa  </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
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
    <script src = "{{ asset('js/openIAViewController.js') }}"></script>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
  
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

@endsection