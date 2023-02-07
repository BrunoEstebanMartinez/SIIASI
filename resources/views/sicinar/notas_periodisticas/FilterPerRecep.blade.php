@extends('sicinar.principal')

@section('title','Filtrar periodo recepcion de documentos')

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
             <h1>Oficialia de partes - Recepción de documentos -  
                <small> Seleccionar periodo fiscal </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Oficialia de partes     </a></li>   
                <li><a href="#">Recepción de documentos </a></li>  
        </section>
        <section class="content">
          <div class="box-body"> 

              <div class="modal-content">
                  <div class="modal-header">
                       <h5 class="modal-title">Seleccionar periodo fiscal:</h5>
                  </div>
                  <div class="modal-body">
                      <div class="list-group">
                      @foreach($regrecepcion as $periodo)
                          <a class="list-group-item list-group-item-action" href="{{ route('verrecepcion', $periodo->periodo_id1) }}" role="button" value="{{$periodo->periodo_id1}}" class="btn btn-outline-success">{{$periodo->periodo_id1}}
                          </a>
                      @endforeach
                      </div>
                  </div>
              </div>
          </div>
        </section>
    </div>
@endsection
