@extends('sicinar.principal')

@section('title','Filtrar periodo Salida de documentos')

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
            <h1>Periodos</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Gestión interna     </a></li>   
                <li><a href="#">Selección de periodo fiscal </a></li>               
            </ol>
        </section>
        <section class="content">
          <div class="box-body"> 

              <div class="modal-content">
                  <div class="modal-header">
                       <h5 class="modal-title">Elija el ejercicio fiscal que desea consultar:</h5>
                  </div>
                  <div class="modal-body">
                      <div class="list-group">
                      @foreach($periodos as $periodo)
                          <a class="list-group-item list-group-item-action" href="{{ route('versalida', $periodo->periodo_id) }}" role = "button" value = "{{ Trim($periodo->periodo_id)  }}" class = "btn btn-outline-success">{{Trim($periodo->periodo_id)}}</a>
                      @endforeach
                      </div>
                  </div>
              </div>
          </div>
        </section>
    </div>
@endsection

