@extends('sicinar.principal')

@section('title','Ver gestión interna - Turnar a ')

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
            <h1>Gestión interna - Turnar a -  
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Gestión interna   </a></li>   
                <li><a href="#">Turnar a    </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
 
                        <div class="page-header" style="text-align:right;">
                            
                            {{ Form::open(['route' => 'buscarturnar', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('idd', null, ['class' => 'form-control', 'placeholder' => 'No. oficio']) }}
                                </div>                                             
                                <div class="form-group">
                                    {{ Form::text('desti', null, ['class' => 'form-control', 'placeholder' => 'Destinatario']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('remi', null, ['class' => 'form-control', 'placeholder' => 'Remitente']) }}
                                </div>                                
                                <div class="form-group">
                                    {{ Form::text('asun', null, ['class' => 'form-control', 'placeholder' => 'Asunto']) }}
                                </div>                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Periodo          </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">folio<br>Sistema </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">No. oficio       </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Destinatario     </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Remitente        </th>                                        
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Asunto           </th>                                        
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Unidad           </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Turnado a        </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">St.<br>Turnado   </th>   
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Temática         </th>  
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Fecha<br>Oficio  </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Fecha<br>Recibido</th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">PDF <br>Ent.     </th>
                                        
                                        <th style="text-align:center;vertical-align: middle;font-size:11px;">Turnar <br>Docto.</th>
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
                                        <td style="text-align:center;margin-right: 15px;vertical-align: middle;">
                                        @switch($recep->ent_status3)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Turnado"/> 
                                            @break                                            
                                        @case('0')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Sin turnar"/> 
                                            @break                                             
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Sin turnar"/> 
                                        @endswitch
                                        </td>                                                                          

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

                                        @if( !empty(trim($recep->ent_arc1))&&(!is_null(trim($recep->ent_arc1))) )
                                            <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:11px;" title="Oficio digital">
                                                <a href="/storage/{{$recep->ent_arc1}}" class="btn btn-danger" title="Oficio digital"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred;text-align:center; vertical-align:middle;font-size:11px;" title="Oficio digital"><i class="fa fa-times"></i>
                                            </td>  
                                        @endif                                                                                                        

                                        <td style="text-align:center;">
                                            <a href="{{route('editarturnar',$recep->folio)}}" class="btn badge-warning" title="Seguimiento"><i class="fa fa-edit"></i></a>
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
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection
