@extends('sicinar.principal')

@section('title','Ver oficios de entrada')

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



    <div class="content-wrapper">
        <section class="content-header">
            <h1>Oficialia de partes - Recepción de documentos -  
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Oficialia de partes     </a></li>   
                <li><a href="#">Recepción de documentos </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
 
                        <div class="page-header" style="text-align:right;">
                                <div class="form-group row columns-list" style = "padding: 20px 10px 5px 20px;">
                                        <div class="col-sm-1">
                                            <select name="periodo" id="periodo" class="form-control">
                                                <option selected = "true" value="0" disabled>Periodo</option>
                                                @if(is_array($histPeriodos) || is_object($histPeriodos))
                                                    @foreach($histPeriodos as $periodos)
                                                        <option value="{{ route('verper',  $periodos -> periodo_id)}}">{{ $periodos -> periodo_id }}</option>
                                                    @endforeach
                                                @endif
                                            </select> 
                                        </div>
                                   
                                        <div class="col-sm-1">
                                            <input type="text" class = "form-control" name = "cr_periodo" id="cr_periodo" value = "{{ $ANIO }}" disabled>
                                        </div>  

                                        <div class="col-sm-5 ">
                                            <div class="input-icon-wrap">
                                            {{ Form::text('todo', null, ['class' => 'form-control', 'id' => 'isSearch', 'placeholder' => 'No. Oficio, remitente, destinatario y/o asunto']) }}
                                                    <span class="input-icon"><li class="fa fa-search"></li></span>
                                            </div>              
                                        </div>

                                        <div class="col-sm-1">
                                            <a href="{{route('nuevarecepcion')}}" class="btn btn-primary btn_xs" title="Nueva recepción de documento"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nueva recepción </small></a>
                                        </div>
                                </div>  
                        </div>
                               
                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Periodo      </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">folio<br>Sistema</th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">No. oficio   </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Destinatario </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Remitente    </th>                                        
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Asunto       </th>                                        
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Unidad       </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Turnado a    </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Respuesta        </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Temática         </th>  
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Fecha<br>Oficio  </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Fecha<br>Recibido</th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">PDF              </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">St.              </th>                                          
                                        
                                        <th style="text-align:center;vertical-align: middle;font-size:11px; width:100px;">Acciones </th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($regrecepcion as $recep)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$recep->periodo_id}}  </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$recep->folio}}       </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$recep->ent_noficio}} </td>  
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($recep->ent_destin)}} </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($recep->ent_remiten)}}</td>  
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($recep->ent_asunto)}} </td> 
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($recep->ent_uadmon)}} </td>                                         
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;"> 
                                            {{$recep->cve_sp}} &nbsp;&nbsp;
                                            @foreach($regpersonal as $perso)
                                                @if($perso->folio == $recep->cve_sp)
                                                    {{Trim($perso->nombre_completo)}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                                            
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($recep->ent_resp)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;"> 
                                            {{$recep->tema_id}} &nbsp;&nbsp;
                                            @foreach($regtema as $tema)
                                                @if($tema->tema_id == $recep->tema_id)
                                                    {{Trim($tema->tema_desc)}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                                            

                                        <td style="text-align:center; vertical-align: middle;font-size:09px;">{{date("d/m/Y",strtotime($recep->ent_fec_ofic)) }}</td>
                                        <td style="text-align:center; vertical-align: middle;font-size:09px;">{{date("d/m/Y",strtotime($recep->ent_fec_recib))}}</td>

                                        @if(!empty($recep->ent_arc1)&&(!is_null($recep->ent_arc1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:11px;" title="Oficio digital">
                                                <a href="/storage/{{$recep->ent_arc1}}" class="btn btn-danger" title="Oficio digital"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarrecepcion1',array($recep->folio))}}" class="btn badge-warning" title="Editar oficio digital"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="oficio digital"><i class="fa fa-times"></i>
                                                <a href="{{route('editarrecepcion1',array($recep->folio))}}" class="btn badge-warning" title="Editar oficio digital"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif                                        
                                        <td style="text-align:center;margin-right: 15px;vertical-align: middle;">
                                        @switch($recep->ent_status2)
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" /> 
                                            @break
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg') }}" width="15px" height="15px" /> 
                                            @break                                            
                                        @case('0')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" /> 
                                            @break                                             
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px"/> 
                                        @endswitch
                                        </td>                                                                          

                                        <td style="text-align:center;">
                                            <a href="{{route('editarrecepcion',$recep->folio)}}" class="btn badge-warning" title="Editar recepción de documento"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarrecepcion',$recep->folio)}}" class="btn badge-danger" title="Borrar recepción de documento" onclick="return confirm('¿Seguro que desea borrar la recepción del documento?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regrecepcion->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src = "{{ asset('js/liveSearchEntrada.js') }}"></script>

@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection
