@extends('sicinar.principal')

@section('title','Ver oficios de salida')

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
            <h1>Gestión interna - Documentos de salida  
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Gestión interna     </a></li>   
                <li><a href="#">Documentos de salida </a></li>
                <li>Periodo - {{ $ANIO }}</li>             
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
 
                        <div class="page-header" style="text-align:right;">
                            
                            {{ Form::open(['route' => 'buscarsalida', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}

                                <div class="form-group">
                                <input class = "form-control" type="text" name = "periodo" id = "periodo" value = "{{ $ANIO }}" placeholder = "{{ $ANIO }}" disabled>
                                <input class = "form-control" type="text" name = "arbol" id = "arbol" value = "{{ $arbol_id }}" placeholder = "{{ $arbol_id }}" disabled>
                                    {{ Form::text('todo', null, ['class' => 'form-control', 'id' => 'isSearch', 'placeholder' => 'Busqueda']) }}
                                </div>                                             
                                <div class="form-group">   
                                    <a href="{{route('nuevasalida')}}" class="btn btn-primary btn_xs" title="Nuevo documento"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nuevo documento </small>
                                    </a>
                                </div> 

                                

                            {{ Form::close() }}
                             
                        </div>
                        
                        <div class="box-body">
                        <table id="tabla1" class="table table-hover table-striped">


                        <thead style="color: brown;" class="justify">
                            <tr>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Periodo      </th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">No. oficio   </th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Destinatario </th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Remitente    </th>                                        
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Asunto       </th>                                        
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Unidad       </th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Quien solicita </th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Temática         </th>  
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Fecha<br>Oficio  </th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Fecha<br>Recibido</th>
                                 <th style="text-align:left;  vertical-align: middle;font-size:11px;">Escaneo</th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">En respuesta al oficio No.</th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">Expediente</th>
                                <th style="text-align:left;  vertical-align: middle;font-size:11px;">PDF              </th>                                     
                                <th style="text-align:center;vertical-align: middle;font-size:11px; width:100px;">Acciones </th>
                            </tr>
                        </thead> 
                        <tbody>

                            @foreach($regremision as $salida) 
                            <tr>
                                <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$salida->periodo_id}}</td>
                                <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$salida->sal_noficio}} </td>  
                                <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($salida->sal_destin)}} </td>
                                <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($salida->sal_remiten)}}</td> 
                                <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($salida->sal_asunto)}} </td>
                                <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($salida->sal_uadmon)}} </td>  
                                                                   
                                <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$salida->cve_sp.' '.Trim($salida->nombre_completo)}} </td>
                                       
                                <td style="text-align:left; vertical-align: middle;font-size:09px;">
                                    @foreach($regtema as $tema)
                                        @if($tema->tema_id == $salida->tema_id)
                                            {{Trim($tema->tema_desc)}}
                                            @break 
                                        @endif
                                    @endforeach
                                    </td> 
                                <td style="text-align:center; vertical-align: middle;font-size:09px;">{{date("d/m/Y",strtotime($salida->sal_fec_ofic)) }}</td>
                                <td style="text-align:center; vertical-align: middle;font-size:09px;">{{date("d/m/Y",strtotime($salida->sal_fec_recib))}}</td>
                          
                                  @if(!empty($salida->sal_dochis)&&(!is_null($salida->sal_dochis)))
                                <td style="text-align:center; vertical-align: middle;font-size:09px;">{{$salida->sal_dochis}}</td>
                                @else 
                                <td style="text-align:center; vertical-align: middle;font-size:09px;" title = "Nombre del documento no disponible"><i class="fa fa-times"> <i class="fa fa-file"></i>
                               
                                    </td>
                                @endif

                                 <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($salida->sal_obs2)}} </td>  
                                 <td style="text-align:left; vertical-align: middle;font-size:09px;">{{Trim($salida->sal_obs1)}} </td>  


                                @if(!empty($salida->sal_arc1)&&(!is_null($salida->sal_arc1)))
                                    <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:11px;" title="documento">
                                        <a href="/storage/{{$salida->sal_arc1}}" class="btn btn-danger" title="documento"><i class="fa fa-file-pdf-o"></i>
                                        </a>
                                        <a href="{{route('editarsalidaformato',array($salida->folio) )}}" class="btn badge-warning" title="Editar documento"><i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                @else
                                <td style="text-align:center; vertical-align: middle;font-size:09px;" title = "Documento digital no dispnible"><i class="fa fa-times"> <i class="fa fa-file"></i>
                                <a href="{{route('editarsalidaformato',array($salida->folio))}}" class="btn badge-warning" title="Editar documento"><i class="fa fa-edit"></i>
                                        </a>
                                    </td>   
                                @endif                                        
                                
                                <td style="text-align:center;">
                                    <a href="{{route('editarsalida',array($salida->folio))}}" class="btn badge-warning" title="Editar documento"><i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{route('borrarsalida',array($salida->folio))}}" class="btn badge-danger" title="Borrar documento" onclick="return confirm('¿Seguro que desea borrar documento?')"><i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                          
                        </tbody>
                        </table>
                        {!! $regremision->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    
    <script src = "{{ asset('js/liveSearchSalidas.js') }}"></script>
@endsection

@section('request')
@endsection

@section('javascrpt')

@endsection
