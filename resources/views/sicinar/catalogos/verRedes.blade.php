@extends('sicinar.principal')

@section('title','Ver redes sociales')

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
            <h1>Catálogo - Redes sociales
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos       </a></li>
                <li><a href="#">Redes sociales  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('exportredesexcel')}}" class="btn btn-success"       title="Exportar redes sociales a formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>  
                            <a href="{{route('exportredespdf')}}"   class="btn btn-danger"        title="Exportar redes sociales a formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevared')}}"        class="btn btn-primary btn_xs" title="Nueva"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Red social </th>
                                        
                                        <th style="text-align:left;   vertical-align: middle;">Logo             </th>
                                        <th style="text-align:center; vertical-align: middle;">Activo / Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha reg.       </th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones         </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regredes as $red)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$red->rs_id}}        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{Trim($red->rs_desc)}}</td>
                                        @if(!empty($red->rs_foto1)&&(!is_null($red->rs_foto1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:11px;" title="Logo">
                                                <a href="/storage/{{$red->rs_foto1}}" class="btn btn-danger" title="Logo"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarred1',array($red->rs_id))}}" class="btn badge-warning" title="Editar logo"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="oficio digital"><i class="fa fa-times"></i>    
                                                <a href="{{route('editarred1',array($red->rs_id))}}" class="btn badge-warning" title="Editar logo"><i class="fa fa-edit"></i>
                                                </a>                                                                                      
                                            </td>   
                                        @endif                       

                                        @if($red->rs_status == 'S')
                                            <td style="color:darkgreen;text-align:center;vertical-align:middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred;  text-align:center;vertical-align:middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;font-size:10px;">{{date("d/m/Y", strtotime($red->fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarred',$red->rs_id)}}" class="btn badge-warning" title="Editar red social "><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarred',$red->rs_id)}}" class="btn badge-danger"  title="Borrar red social " onclick="return confirm('¿Seguro que desea borrar la red social?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regredes->appends(request()->input())->links() !!}
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
