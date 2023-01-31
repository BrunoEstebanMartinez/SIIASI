@extends('sicinar.principal')

@section('title','Editar salida de documentos')

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
                <small> Documentos de salida - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarsalidaformato',$regremision->folio], 'method' => 'PUT', 'id' => 'actualizarsalida1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">   
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regremision->periodo_id}}"> 
                                    <input type="hidden" name="folio"      id="folio"      value="{{$regremision->folio}}">    
                                    <label style="color:green; text-align:left; vertical-align: middle;">Periodo fiscal: </label>
                                    <td>&nbsp;&nbsp;{{$regremision->periodo_id}} </td>                                    
                                </div>                                
                                <div class="col-xs-3 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Fecha de oficio : </label>
                                    <td>&nbsp;&nbsp;{{date("d/m/Y",strtotime($regremision->sal_fec_ofic)) }} </td>
                                </div>                                   
                                <div class="col-xs-3 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Fecha de recibido : </label>
                                    <td>&nbsp;&nbsp; {{date("d/m/Y",strtotime($regremision->sal_fec_recib))}} </td>
                                </div>                                   
                                <div class="col-xs-2 form-group align:rigth">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Folio de sistema: </label>
                                    <td>&nbsp;&nbsp;{{$regremision->folio}}</td>    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">No. oficio: </label>
                                    <td>&nbsp;&nbsp; {{$regremision->sal_noficio}} </td>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Destinatario: </label>
                                    <td>&nbsp;&nbsp; {{Trim($regremision->sal_destin)}} </td>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Remitente: </label>
                                    <td>&nbsp;&nbsp; {{Trim($regremision->sal_remiten)}} </td>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;" >Unidad admon. remitente:</label>
                                    <td>&nbsp;&nbsp;  {{Trim($regremision->sal_uadmon)}} </td>
                                </div>  
                            </div>                                                      
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Asunto (4,000 caracteres) </label>
                                    <td><br>{{Trim($regremision->sal_asunto)}} </td>
                                </div>                                
                            </div>                                                        

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Quien solicita 1:   </label>
                                    <select class="form-control m-bot15" name="cve_sp" id="cve_sp" required>
                                        <option selected="true" disabled="disabled">Seleccionar a quien se turna </option>
                                        @foreach($regpersonal as $perso)
                                            @if($perso->folio == $regremision->cve_sp)
                                                <option value="{{$perso->folio}}" selected>{{$perso->folio.' '.Trim($perso->nombre_completo)}}</option>
                                            @else                                        
                                                <option value="{{$perso->folio}}">{{$perso->folio.' '.Trim($perso->nombre_completo)}}</option>
                                            @endif                                            
                                        @endforeach
                                    </select>                                    
                                </div>       
                                        
                                <div class="col-xs-4 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Quien solicita 2 (Opcional):   </label>
                                    <select class="form-control m-bot15" name="cve_spseg" id="cve_spseg" required>
                                        <option selected="true" disabled="disabled">Seleccionar a quien se turna </option>
                                        @foreach($regpersonal as $perso)
                                            @if($perso->folio == $regremision->cve_spseg)
                                                <option value="{{$perso->folio}}" selected>{{$perso->folio.' '.Trim($perso->nombre_completo)}}</option>
                                            @else                                        
                                                <option value="{{$perso->folio}}">{{$perso->folio.' '.Trim($perso->nombre_completo)}}</option>
                                            @endif                                            
                                        @endforeach
                                    </select>                                    
                                </div>                     
                            </div>

                        <div class = "row">
                            <div class="col-xs-12 form-group">
                                    <label style="color:green; text-align:left; vertical-align: middle;">Temática  </label>
                                    <select class="form-control m-bot15" name="tema_id" id="tema_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar temática </option>
                                        @foreach($regtema as $tema)
                                            @if($tema->tema_id == $regremision->tema_id)
                                                <option value="{{$tema->tema_id}}" selected>{{trim($tema->tema_desc)}}</option>
                                            @else                                        
                                                <option value="{{$tema->tema_id}}">{{trim($tema->tema_desc)}}</option>
                                            @endif                                                                             
                                        @endforeach
                                    </select>                                    
                                </div> 
                        </div>
                              

                            <div class="row" style="background-color:#800000;color:white;text-align:center;">
                                <div class="col-xs-12 form-group">
                                    <label><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital en formato PDF, NO deberá ser mayores a 2,500 kBytes.  </label>
                                </div>   
                            </div> 

                            @if (!empty($regremision->sal_arc1)||!is_null($regremision->sal_arc1))   
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital en formato PDF</label><br>
                                        <label ><a href="/storage/{{$regremision->sal_arc1}}" class="btn btn-danger" title="Archivo digital en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>   &nbsp;&nbsp;&nbsp;{{$regremision->sal_arc1}}
                                        </label>
                                    </div>   
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="300" src="{{ asset('storage/'.$regremision->sal_arc1)}}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                        
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="sal_arc1" id="sal_arc1" placeholder="Subir archivo de digital en formato PDF" >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">                            
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="sal_arc1" id="sal_arc1" placeholder="Subir archivo digital en formato PDF" >
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
                                    <a href="{{route('versalida')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\remision1Request','#actualizarsalida1') !!}
@endsection

@section('javascrpt')
@endsection