@extends('sicinar.principal')

@section('title','Ver temáticas')

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
            <h1>Catálogo - Series o Temáticas
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos    </a></li>
                <li><a href="#">Series o Temáticas  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('exporttemaexcel')}}" class="btn btn-success"        title="Exportar Temáticas a formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>  
                            <a href="{{route('exporttemapdf')}}"   class="btn btn-danger"         title="Exportar Temáticas a formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevotema')}}"       class="btn btn-primary btn_xs" title="Nuevo"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Temática </th>
                                        <th style="text-align:left;   vertical-align: middle;">Sección  </th>
                                        <th style="text-align:center; vertical-align: middle;">Activo / Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regtema as $tema)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$tema->tema_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$tema->tema_desc}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">
                                        {{$tema->seccion_id.' '}}
                                        @foreach($regseccion as $sec)
                                            @if($sec->seccion_id == $tema->seccion_id)
                                                {{Trim($sec->seccion_desc)}}
                                                @break
                                            @endif
                                        @endforeach                                        
                                        </td>                                                                                
                                        @if($tema->tema_status == 'S')
                                            <td style="color:darkgreen;text-align:center;vertical-align:middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred;  text-align:center;vertical-align:middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;font-size:10px;">{{date("d/m/Y", strtotime($tema->tema_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editartema',$tema->tema_no)}}" class="btn badge-warning" title="Editar temática"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrartema',$tema->tema_no)}}" class="btn badge-danger"  title="Borrar temática" onclick="return confirm('¿Seguro que desea borrar la temática?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regtema->appends(request()->input())->links() !!}
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
