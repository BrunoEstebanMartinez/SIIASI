@extends('sicinar.principal')

@section('title','Ver medio informativo')

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
            <h1>Catálogo - Medios informativos
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos            </a></li>
                <li><a href="#">Medios informativos  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('exportmediosexcel')}}" class="btn btn-success"        title="Exportar medio informativo a formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>  
                            <a href="{{route('exportmediospdf')}}"   class="btn btn-danger"         title="Exportar medio informativo a formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevomedio')}}"        class="btn btn-primary btn_xs" title="Nuevo"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Medio informativo</th>
                                        <th style="text-align:left;   vertical-align: middle;">Link o URL       </th>
                                        <th style="text-align:left;   vertical-align: middle;">Director y/o responsable </th>                                        
                                        <th style="text-align:left;   vertical-align: middle;">Logo             </th>
                                        <th style="text-align:center; vertical-align: middle;">Activo / Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha reg.       </th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones         </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regmedio as $medios)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$medios->medio_id}}        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{Trim($medios->medio_desc)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{Trim($medios->medio_link)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{Trim($medios->medio_obs1)}}</td>
                                        @if(!empty($medios->medio_foto1)&&(!is_null($medios->medio_foto1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:11px;" title="Logo">
                                                <a href="/storage/{{$medios->medio_foto1}}" class="btn btn-danger" title="Logo"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarmedio1',array($medios->medio_id))}}" class="btn badge-warning" title="Editar logo"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="oficio digital"><i class="fa fa-times"></i>    
                                                <a href="{{route('editarmedio1',array($medios->medio_id))}}" class="btn badge-warning" title="Editar logo"><i class="fa fa-edit"></i>
                                                </a>                                                                                      
                                            </td>   
                                        @endif                       

                                        @if($medios->medio_status == 'S')
                                            <td style="color:darkgreen;text-align:center;vertical-align:middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred;  text-align:center;vertical-align:middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;font-size:10px;">{{date("d/m/Y", strtotime($medios->fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarmedio',$medios->medio_id)}}" class="btn badge-warning" title="Editar medio informativo"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarmedio',$medios->medio_id)}}" class="btn badge-danger"  title="Borrar medio informativo" onclick="return confirm('¿Seguro que desea borrar medio informativo?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regmedio->appends(request()->input())->links() !!}
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
