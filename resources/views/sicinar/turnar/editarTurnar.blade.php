@extends('sicinar.principal')

@section('title','Editar turnar documento')

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
                <small> Turnar a - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarturnar',$regrespuesta->folio], 'method' => 'PUT', 'id' => 'actualizarturnar', 'enctype' => 'multipart/form-data']) !!}
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
                                <div class="col-xs-12 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Asunto </label>
                                    <textarea class="form-control" name="ent_asunto" id="ent_asunto" rows="2" cols="120" placeholder="Asunto 4,000 caracteres" required>
                                    {{Trim($regrespuesta->ent_asunto)}}
                                    </textarea>
                                </div>                                
                            </div>                 


                            <table id="tabla1" class="table table-hover table-striped">
                                <tr>
                                    <td> 
                                        <div class="row">                                       
                                            <div class="col-xs-10 form-group">
                                                <label >Temática  </label>
                                                <select class="form-control m-bot15" name="tema_id" id="tema_id" required>
                                                    <option selected="true" disabled="disabled">Seleccionar temática </option>
                                                    @foreach($regtema as $tema)
                                                        @if($tema->tema_id == $regrespuesta->tema_id)
                                                            <option value="{{$tema->tema_id}}" selected>{{trim($tema->tema_desc)}}</option>
                                                        @else                                        
                                                            <option value="{{$tema->tema_id}}">{{trim($tema->tema_desc)}}</option>
                                                        @endif                                                                             
                                                    @endforeach
                                                </select>                                    
                                            </div>                       
                                        </div>                            

                                        <div class="row">                                       
                                            <div class="col-xs-8 form-group">
                                                <label >Turnar documento a  </label>
                                                <select class="form-control m-bot15" name="cve_sp" id="cve_sp" required>
                                                    <option selected="true" disabled="disabled">Seleccionar a quien turnar </option>
                                                    @foreach($regpersonal as $perso)
                                                        @if($perso->folio === $regrespuesta->cve_sp)
                                                            <option value="{{$perso->folio}}" selected>{{trim($perso->nombre_completo)}}</option>
                                                        @else                                        
                                                            <option value="{{$perso->folio}}">{{trim($perso->nombre_completo)}}</option>
                                                        @endif                                                                             
                                                    @endforeach
                                                </select>  
                                            </div>         
                                        </div>
                                        <div class="row">                                               
                                            <div class="col-xs-4 form-group">                        
                                                <label>Estado del turnado del documento  </label>
                                                <select class="form-control m-bot15" name="ent_status3" id="ent_status3" required>
                                                    @if($regrespuesta->ent_status3 === '0')
                                                        <option value="0" selected>Sin turnar </option>
                                                        <option value="1">         Turnado    </option>
                                                    @else
                                                        @if($regrespuesta->ent_status3 === '1')             
                                                            <option value="0">         Sin turnar</option>
                                                            <option value="1" selected>Turnado   </option>
                                                        @endif
                                                    @endif
                                                </select>
                                            </div> 
                                        </div>

                                    </td>
                                    <td>
                                        <table id="tabla1" class="table table-hover table-striped">
                                            <tr>
                                                <td> 
                                                @foreach($regrecepcion as $recep)
                                                    @if($regrespuesta->folio == $recep->folio)
                                                        @if (!empty($recep->ent_arc1) && !is_null($recep->ent_arc1)) 
                                                            <input type="hidden" name="ent_arc1" id="ent_arc1" value="{{$recep->ent_arc1}}">   
                                                            <div class="row">
                                                                <div class="col-xs-8 form-group">                                        
                                                                    <label>Archivo digital PDF de entrada<br>
                                                                        <iframe width="400" height="300" src="{{ asset('storage/'.$recep->ent_arc1)}}" frameborder="0"></iframe> 
                                                                    </label>                                       
                                                                </div>                                        
                                                            </div>
                                                        @else     <!-- se captura archivo 1 -->
                                                            <div class="row">                            
                                                                <div class="col-xs-8 form-group">
                                                                    <label >Archivo digital PDF de entrada</label>
                                                                     <div style="color:darkred;text-align:center; vertical-align:middle;font-size:11px;" title="Oficio digital"><i class="fa fa-times">* Sin archivo PDF *</i>
                                                                     </div> 
                                                                </div>                                                
                                                            </div>   
                                                        @endif
                                                        @break
                                                    @endif
                                                @endforeach
                                                </td>
                                            </tr>
                                        </table>
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
                                    <a href="{{route('verturnar')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\turnarRequest','#actualizarturnar') !!}
@endsection

@section('javascrpt')
@endsection

