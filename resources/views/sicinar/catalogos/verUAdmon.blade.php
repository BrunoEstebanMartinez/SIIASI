@extends('sicinar.principal')

@section('title','Ver Unidades Admon.')

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
            <h1>Catálogos - Unidades Admon.
                <small> Seleccionar para editar</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Unidades Admon.  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('exportuadmonexcel')}}" class="btn btn-success"        title="Exportar catálogo de Unidades Admon. a formato Excel"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('exportuadmonpdf')}}"   class="btn btn-danger"         title="Exportar catálogo de Unidades Admon. a formato PDF  "><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevauadmon')}}"       class="btn btn-primary btn_xs" title="Nueva"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva </a>
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.            </th>
                                        <th style="text-align:left;   vertical-align: middle;">Unidad Admon.  </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa/Inactiva</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro </th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones       </th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @foreach($reguadmon as $medida)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$medida->uadmon_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$medida->uadmon_desc}}</td>
                                        @if($medida->uadmon_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($medida->uadmon_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editaruadmon',$medida->uadmon_id)}}" class="btn badge-warning" title="Editar Unidad Admon."><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borraruadmon',$medida->uadmon_id)}}" class="btn badge-danger"  title="Borrar Unidad Admon." onclick="return confirm('¿Seguro que desea borrar la Unidad Admon.?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $reguadmon->appends(request()->input())->links() !!}
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
