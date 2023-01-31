@extends('sicinar.principal')

@section('title','Ver Secciones')

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
            <h1>Catálogos -
                <small> Secciones - Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos  </a></li>
                <li><a href="#">Secciones  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('exportseccionexcel')}}" class="btn btn-success" title="Exportar Secciones a Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>  
                            <a href="{{route('exportseccionpdf')}}"   class="btn btn-danger"  title="Exportar Secciones a PDF)"  ><i class="fa fa-file-pdf-o"  ></i> PDF</a>                        
                            <a href="{{route('nuevaseccion')}}"       class="btn btn-primary" title="Nueva sección"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva</a>
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.      </th>
                                        <th style="text-align:left;   vertical-align: middle;">Sección  </th>
                                        <th style="text-align:left;   vertical-align: middle;">Tipo     </th>                                        
                                        <th style="text-align:center; vertical-align: middle;">Activa/<br>Inactiva</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regseccion as $formato)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$formato->seccion_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$formato->seccion_desc}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$formato->seccion_tipo}}</td>
                                        @if($formato->seccion_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center;">
                                            <a href="{{route('editarseccion',$formato->seccion_id)}}" class="btn badge-warning" title="Editar seccion"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarseccion',$formato->seccion_id)}}" class="btn badge-danger" title="Borrar seccion" onclick="return confirm('¿Seguro que desea borrar la seccion?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regseccion->appends(request()->input())->links() !!}
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
