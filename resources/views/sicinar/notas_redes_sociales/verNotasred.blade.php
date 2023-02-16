@extends('sicinar.principal')

@section('title','Ver Notas de redes sociales')

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
            <h1>Notas informativas - Redes sociales -  
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Notas informativas      </a></li>   
                <li><a href="#">Redes sociales </a></li>               
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
                                                        <option value="{{ route('verpernotared',  $periodos -> periodo_id)}}">{{ $periodos -> periodo_id }}</option>
                                                    @endforeach
                                                @endif
                                            </select> 
                                        </div>
                                   
                                        <div class="col-sm-1">
                                            <input type="text" class = "form-control" name = "cr_periodo" id="cr_periodo" value = "{{ $ANIO }}" disabled>
                                        </div>  

                                        <div class="col-sm-5 ">
                                            <div class="input-icon-wrap">
                                            {{ Form::text('todo', null, ['class' => 'form-control', 'id' => 'isSearch', 'placeholder' => 'Por título']) }}
                                                    <span class="input-icon"><li class="fa fa-search"></li></span>
                                            </div>              
                                        </div> 

                                        <div class="col-sm-2">
                                            <a href="{{route('nuevanotared')}}" class="btn btn-primary btn_xs" title="Nueva nota de red social"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nueva nota</small>
                                            </a>
                                        </div>
                                </div>  
                        </div>
                               
                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Periodo           </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Folio<br>Sistema  </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Título            </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Resumen de la nota</th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Autor          </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Link ó URL     </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Red<br>Social  </th>

                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Likes          </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Reposteos      </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Comentarios    </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Alcance        </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Calif.         </th>

                                        <th style="text-align:center;vertical-align: middle;font-size:11px;">Acciones       </th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($regnotaredes as $redes)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$redes->periodo_id}}     </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$redes->rs_folio}}       </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($redes->rs_titulo)}}</td>  
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($redes->rs_nota)}}  </td>  
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($redes->rs_autor)}} </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($redes->rs_link)}}  </td>  
                                         
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;"> 
                                            @foreach($regredes as $red)
                                                @if($red->rs_id == $redes->rs_id)
                                                    {{Trim($red->rs_desc)}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                                            
                                        
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($redes->rs_likes    ,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($redes->rs_reposteos,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($redes->rs_comen    ,0)}}</td>  
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($redes->rs_alcance  ,0)}}</td>  

                                        <td style="text-align:center;">  
                                            @switch($redes->rs_calif)
                                                @case('1')
                                                    <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;" title="Positivo"/> 
                                                    @break                                                                
                                                @case('2')
                                                    <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;" title="Neutro"/> 
                                                    @break
                                                @case('3')
                                                    <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;" title="Negativo"/> 
                                                    @break                                                
                                                @case('0')   
                                                    @break                                                                                                                
                                                @default
                                                    @break
                                            @endswitch          
                                        </td> 
                              
                                        <td style="text-align:center; vertical-align: middle;font-size:09px;">{{date("d/m/Y",strtotime($redes->rs_fec_nota))}}</td>

                                        <td style="text-align:center;">
                                            <a href="{{route('editarnotared',$redes->rs_folio)}}" class="btn badge-warning" title="Editar nota de red social"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarnotared',$redes->rs_folio)}}" class="btn badge-danger" title="Borrar nota de red social" onclick="return confirm('¿Seguro que desea borrar la nota de red social?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regnotaredes->appends(request()->input())->links() !!}
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
