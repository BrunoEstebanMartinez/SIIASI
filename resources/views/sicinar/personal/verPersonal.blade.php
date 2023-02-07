@extends('sicinar.principal')

@section('title','Ver plantilla de personal')

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
            <h1>Catálogos
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos  </a></li>   
                <li><a href="#">Personal   </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            
                            {{ Form::open(['route' => 'buscarpersonal', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre']) }}
                                </div>                                                
                                <div class="form-group">
                                    {{ Form::text('nameuadmon', null, ['class' => 'form-control', 'placeholder' => 'Unidad admon.']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('exportpersonalexcel')}}" class="btn btn-success"        title="Exportar plantilla de personal a formato Excel)"><i class="fa fa-file-excel-o"></i> Excel  </a>                            
                                    <a href="{{route('nuevopersonal')}}"       class="btn btn-primary btn_xs" title="Nueva"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva </a>
                                </div>                                
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Cve.SP.          </th>
                                        <th style="text-align:left;   vertical-align: middle;">U. admon.        </th>
                                        <th style="text-align:left;   vertical-align: middle;">Servidor público </th>
                                        <th style="text-align:left;   vertical-align: middle;">Telefóno         </th>
                                        <th style="text-align:left;   vertical-align: middle;">e-mail           </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa <br>Inact.</th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regpersonal as $personal)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$personal->folio}}    </td>
                                        <td style="text-align:left; vertical-align: middle;">
                                        {{$personal->uadmon_id}}   &nbsp;&nbsp; 
                                        @foreach($reguadmon as $iap)
                                            @if($iap->uadmon_id == $personal->uadmon_id)
                                                {{$iap->uadmon_desc}}
                                                @break
                                            @endif
                                        @endforeach
                                        </td>                                          
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($personal->nombre_completo)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($personal->telefono)}} </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($personal->e_mail)}}   </td>
                                                                                
                                        @if($personal->status_1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarpersonal',$personal->folio)}}" class="btn badge-warning" title="Editar personal"><i class="fa fa-edit"></i></a>
                                            @if(session()->get('rango') == '4')
                                                <a href="{{route('borrarpersonal',$personal->folio)}}" class="btn badge-danger" title="Borrar personal" onclick="return confirm('¿Seguro que desea borrar la persona de la plantilla?')"><i class="fa fa-times"></i></a>
                                            @endif                                                
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regpersonal->appends(request()->input())->links() !!}
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
