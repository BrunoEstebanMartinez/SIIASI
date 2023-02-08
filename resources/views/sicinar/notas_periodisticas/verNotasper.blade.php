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
            <h1>Notas informativas - Periodísticas -  
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Notas informativas    </a></li>   
                <li><a href="#">Periodísticas </a></li>               
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
                                            {{ Form::text('todo', null, ['class' => 'form-control', 'id' => 'isSearch', 'placeholder' => 'Por título, resumen, medio informativo y calificación']) }}
                                                    <span class="input-icon"><li class="fa fa-search"></li></span>
                                            </div>              
                                        </div> 

                                        <div class="col-sm-1">
                                            <a href="{{route('nuevanotaper')}}" class="btn btn-primary btn_xs" title="Nueva nota periodística"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nueva nota periodística</small></a>
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
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Resumen nota periodística </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Resumen IA     </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Autor          </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Link           </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Medio<br>Informativo</th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Tipo<br>Nota   </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Calif.         </th>
                                        <th style="text-align:left;  vertical-align: middle;font-size:11px;">Fecha<br>Nota  </th>
                                        
                                        <th style="text-align:center;vertical-align: middle;font-size:11px;">Acciones       </th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($regnotamedio as $recep)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$recep->periodo_id}}     </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$recep->nm_folio}}       </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($recep->nm_titulo)}}</td>  
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($recep->nm_nota)}}  </td>  
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($recep->nm_ia)}}    </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($recep->nm_autor)}} </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($recep->nm_link)}}  </td>  
                                         
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;"> 
                                            
                                            @foreach($regmedios as $medio)
                                                @if($medio->medio_id == $recep->medio_id)
                                                    {{Trim($medio->medio_desc)}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                                            
                                        
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;"> 
                                            
                                            @foreach($regtiponota as $tipon)
                                                @if($tipon->tipon_id == $recep->tipon_id)
                                                    {{Trim($tipon->tipon_desc)}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                                             
                                        <td style="text-align:center;">  
                                            @switch($recep->nm_calif)
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
                              
                                        <td style="text-align:center; vertical-align: middle;font-size:09px;">{{date("d/m/Y",strtotime($recep->nm_fec_nota))}}</td>

                                        <td style="text-align:center;">
                                            <a href="{{route('editarnotaper',$recep->nm_folio)}}" class="btn badge-warning" title="Editar nota peridística"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarnotaper',$recep->nm_folio)}}" class="btn badge-danger" title="Borrar nota periodística" onclick="return confirm('¿Seguro que desea borrar la nota periodística?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regnotamedio->appends(request()->input())->links() !!}
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
