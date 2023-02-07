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
                <small> Gestión interna   </small>                
                <small> Seguimiento - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizaratenrecep2',$regrespuesta->folio], 'method' => 'PUT', 'id' => 'actualizaratenrecep2', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">   
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regrespuesta->periodo_id}}"> 
                                    <input type="hidden" name="folio"      id="folio"      value="{{$regrespuesta->folio}}">    
                                    <label style="color:green; text-align:left; vertical-align: middle;">Periodo fiscal: </label>
                                    <td>&nbsp;&nbsp;{{$regrespuesta->periodo_id}} </td>                                    
                                </div>                                
                                <div class="col-xs-3 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Fecha de oficio : </label>
                                    <td>&nbsp;&nbsp;{{date("d/m/Y",strtotime($regrespuesta->ent_fec_ofic)) }} </td>
                                </div>                                   
                                <div class="col-xs-3 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Fecha de recibido : </label>
                                    <td>&nbsp;&nbsp; {{date("d/m/Y",strtotime($regrespuesta->ent_fec_recib))}} </td>
                                </div>                                   
                                <div class="col-xs-2 form-group align:rigth">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Folio de sistema: </label>
                                    <td>&nbsp;&nbsp;{{$regrespuesta->folio}}</td>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">No. oficio: </label>
                                    <td>&nbsp;&nbsp; {{$regrespuesta->ent_noficio}} </td>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Destinatario: </label>
                                    <td>&nbsp;&nbsp; {{Trim($regrespuesta->ent_destin)}} </td>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Remitente: </label>
                                    <td>&nbsp;&nbsp; {{Trim($regrespuesta->ent_remiten)}} </td>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;" >Unidad admon. remitente:</label>
                                    <td>&nbsp;&nbsp;  {{Trim($regrespuesta->ent_uadmon)}} </td>
                                </div>  
                            </div>                                                      
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Se turna a:    </label>
                                    <td>
                                        &nbsp;&nbsp; 
                                        @foreach($regpersonal as $perso)
                                            @if($perso->folio == $regrespuesta->cve_sp)
                                                {{$perso->folio.' '.Trim($perso->nombre_completo)}}
                                            @break                                        
                                            @endif                                            
                                        @endforeach
                                    </td>                                    
                                </div>                      
                                <div class="col-xs-6 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Temática: </label>
                                    <td>
                                        &nbsp;&nbsp; 
                                        @foreach($regtema as $tema)
                                            @if($tema->tema_id == $regrespuesta->tema_id)
                                                {{trim($tema->tema_desc)}}
                                            @break                                        
                                            @endif                                                                             
                                        @endforeach
                                    </td>                                    
                                </div>                       
                            </div>                            
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Asunto </label>
                                    <textarea class="form-control" name="ent_asunto" id="ent_asunto" rows="2" cols="120" placeholder="Asunto 4,000 caracteres" required>
                                    {{Trim($regrespuesta->ent_asunto)}}
                                    </textarea>
                                </div>                                
                            </div>                 


                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="color:orange; text-align:left; vertical-align: middle;">
                                        Resumen de seguimiento 
                                    </label>
                                    <textarea class="form-control" name="ent_resp" id="ent_resp" rows="2" cols="120" placeholder="Asunto 4,000 caracteres" required>
                                        {{Trim($regrespuesta->ent_resp)}}
                                    </textarea>
                                </div>                                

                                <div class="col-xs-6 form-group"> 
                                    <label style="color:orange; text-align:left; vertical-align: middle;">
                                       &nbsp;&nbsp; Fecha de seguimiento: 
                                    </label>
                                    <td>&nbsp;&nbsp; {{date("d/m/Y",strtotime($regrespuesta->ent_fec_resp))}} </td>
                                </div>                                        
                            </div>                                                        

                            <table id="tabla1" class="table table-hover table-striped">
                            <tr>
                                <td>
                                    @if ( !empty(trim($regrespuesta->ent_arc2))&&!is_null(trim($regrespuesta->ent_arc2)) )
                                        <div class="row">
                                            <div class="col-xs-6 form-group">                                        
                                                <label style="color:orange; text-align:left; vertical-align: middle;">Archivo digital PDF de seguimiento<br>
                                                <iframe width="400" height="300" src="{{ asset('storage/'.$regrespuesta->ent_arc2)}}" frameborder="0"></iframe> 
                                                </label>                                       
                                            </div>                                        
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 form-group" style="background-color:#800000;color:white;text-align:center;">
                                                <label><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital en formato PDF, NO deberá ser mayor a 2,500 kBytes.  </label>
                                            </div>   
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6 form-group">
                                                <label >Actualizar archivo digital PDF de seguimiento</label>
                                                <input type="file" class="text-md-center" style="color:red" name="ent_arc2" id="ent_arc2" placeholder="Subir archivo digital PDF de seguimiento" >
                                            </div>      
                                        </div>
                                    @else     <!-- se captura archivo 1 -->
                                        <div class="row">
                                            <div class="col-xs-12 form-group" style="background-color:#800000;color:white;text-align:center;">
                                                <label><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital en formato PDF, NO deberá ser mayor a 2,500 kBytes.  </label>
                                            </div>   
                                        </div>                                    
                                        <div class="row">                            
                                            <div class="col-xs-6 form-group">
                                                <label style="color:orange; text-align:left; vertical-align: middle;">Archivo digital PDF de seguimiento</label>
                                                <input type="file" class="text-md-center" style="color:red" name="ent_arc2" id="ent_arc2" placeholder="Subir archivo digital PDF de seguimiento" >
                                            </div>                                                
                                        </div>
                                    @endif     
                                </td>

                                <td>                            
                                    @if ( !empty(trim($regrespuesta->ent_arc1))&&!is_null(trim($regrespuesta->ent_arc1)) )   
                                        <div class="row">
                                            <div class="col-xs-8 form-group">                                        
                                                <label style="color:green; text-align:left; vertical-align: middle;">Archivo digital PDF turnado <br>
                                                    <iframe width="400" height="300" src="{{ asset('storage/'.$regrespuesta->ent_arc1)}}" frameborder="0"></iframe> 
                                                </label>                                       
                                            </div>                                        
                                        </div>
                                    @else     <!-- se captura archivo 1 -->
                                        <div class="row">                            
                                            <div class="col-xs-8 form-group">
                                                <label style="color:green; text-align:left; vertical-align: middle;">Archivo digital PDF turnado</label>
                                                <input type="file" class="text-md-center" style="color:red" name="ent_arc1" id="ent_arc1" placeholder="Subir archivo digital PDF de entrada" >
                                            </div>                                                
                                        </div>
                                    @endif  
                                </td>
                            </tr>
                            </table>

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
                                    <a href="{{route('veratenrecep')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\atenrecep2Request','#actualizaratenrep2') !!}
@endsection

@section('javascrpt')
@endsection

