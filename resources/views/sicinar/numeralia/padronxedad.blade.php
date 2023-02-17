@extends('sicinar.principal')

@section('title','Estadística de notas informativas')

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
      <h1>
        Estadísticas 
        <small>Notas informativas  </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Estadísticas      </a></li>
        <li class="active">Notas Informativas </li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title"><b>Estadística: Por tipo de Nota informativa</b></h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">

              <table id="tabla1" class="table table-striped table-bordered table-sm">
                <thead style="color: brown;" class="justify">
                  <tr>
                    <th style="text-align:center;vertical-align: middle; font-size:10px;width=03%;"><small>NO.      </small></th>
                    <th style="text-align:left;  vertical-align: middle; font-size:10px;width=05%;"><small>TIPO NOTA</small></th>                    

                    <th style="text-align:center;vertical-align: middle; font-size:10px;width=05%;"><small>TOTAL </small></th>
                    <th style="text-align:center;vertical-align: middle; font-size:10px;width=04%;"><small>%     </small></th>
                  </tr>
                  <tr>
                  </tr>
                </thead>

                <tbody>
                  <?php $sumatotal = 0; ?>
                  @foreach($regnotamediod as $totales)
                     <?php $sumatotal = $totales->total; ?>
                  @endforeach                

                  <?php $i     = 0; ?>
                  <?php $porcen= 0; ?>
                  <?php $sumpor= 0; ?>
                  @foreach($regnotamediod as $carga)
                    <?php $i     = $i + 1;  ?>
                    <?php $porcen= ($carga->total*100)/$sumatotal; ?>
                    <tr>
                      <td style="color:darkgreen; text-align:center;"><small>{{$i}}  </small></td> 
                      <td style="color:darkgreen; font-size:10px;"><small>{{$carga->tipon_id}}   </small></td>                       
                      <td style="color:darkgreen; font-size:10px;"><small>{{$carga->tipon_desc}} </small></td>                       
                    </tr>
                    <?php $sumpor+= $porcen; ?>
                  @endforeach
                  
                     <tr>
                        <td>                                     </td>
                        <td style="color:green;"><b>TOTAL GENERAL</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($sumatotal,0)}} </b></td>
                     </tr>
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>

      
              
        <div class="row">
          <!-- Grafica de barras 2 servicios de carga x mes -->
          <div class="col-md-6">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <!--<h3 class="box-title" style="text-align:center;">Gráfica Modelado del proceso 2D </h3>  -->
                  <!-- BOTON para cerrar ventana x -->
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <!-- Pinta la grafica de barras 2-->
                  <div class="box-body">
                    <camvas id="top_x_div" style="width: 900px; height: 500px;"></camvas>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Grafica de pie en 3D consumo de combustible -->
          <div class="col-md-6">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title" style="text-align:center;">Gráfica de Pay 3D </h3> 
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <div class="box-body">
                    <camvas id="piechart_3d_1" style="width: 900px; height: 500px;"></camvas>
                  </div>
                </div> 
              </div>
            </div>
          </div>
        </div>  

        <!-- Grafica de barras 2 consumo ($) de combustible x mes -->
        <div class="row">
          <div class="col-md-6">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <!--<h3 class="box-title" style="text-align:center;">Gráfica Modelado del proceso 2D </h3>  -->
                  <!-- BOTON para cerrar ventana x -->
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <!-- Pinta la grafica de barras 2-->
                  <div class="box-body">
                    <camvas id="combustible" style="width: 900px; height: 500px;"></camvas>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Grafica de pie en 3D consumo de combustible en $ -->
          <div class="col-md-6">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title" style="text-align:center;">Gráfica de Pay 3D </h3> 
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <div class="box-body">
                    <camvas id="piechart_3d_2" style="width: 900px; height: 500px;"></camvas>
                  </div>
                </div> 
              </div>
            </div>
          </div>          
        </div>  

        <!-- Grafica de area Consumo de combustible en cargas de servicio e importes $ -->
        <!--
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title" style="text-align:center;">Gráfica de area </h3> 
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <div class="box-body">
                    <camvas id="area_chart_div" style="width: 900px; height: 500px;"></camvas> 
                  </div>
                </div> 
              </div>
            </div>
          </div>                  
        </div>  
        -->

        <div class="row">
          <!-- Grafica de area Consumo de combustible en cargas de servicio e importes $ -->
          <div class="col-md-12">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title" style="text-align:center;">Gráfica de Combo chart </h3> 
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <div class="box-body">
                    <!--<div id = "chart_div" style = "width: 100%; height: 500px;"> </div> -->
                    <camvas id="combo_chart_div" style="width: 900px; height: 500px;"></camvas> 
                  </div>
                </div> 
              </div>
            </div>
          </div>                  
        </div>  

        <!-- Grafica de Treemaps Consumo de combustible en cargas de servicio e importes $ -->
        <!--
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title" style="text-align:center;">Gráfica de Mapas de calor (Treemaps) </h3> 
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <div class="box-body">
                    <camvas id="Treemaps_chart_div" style="width: 900px; height: 500px;"></camvas> 
                  </div>
                </div> 
              </div>
            </div>
          </div>                  
        </div>  
        -->

      </div>
    </section>
  </div>
@endsection

@section('request')
  <script src="{{ asset('bower_components/chart.js/Chart.js') }}"></script>
  <!-- Grafica google de pay, barras en 3D
    https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart
    https://developers.google.com/chart/interactive/docs/gallery/areachart
  -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
@endsection

@section('javascrpt')
  <!-- Grafica de barras 2D Google/chart -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Tipo_nota', 'Total'],
          @foreach($regnotamediod as $tnota)
             ['{{$tnota->tipon_desc}}', {{$tnota->total}} ],
          @endforeach          
        ]);

        var options = {                 
          title: 'Por Mes',
          width:  500,                // Alto de la pantalla horizontal
          height: 500,                // Ancho de la pantalla '75%',
          colors: ['green'],          // Naranja #e7711c
          legend: { position: 'none' },
          chart: { title: 'Gráfica  ',
                   subtitle: 'Por tipo de nota' },
          bars: 'horizontal',         // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Tipo de nota...'} // Top x-axis.
            }
          },
          annotations: {
            textStyle: {
            fontName: 'Times-Roman',
            fontSize: 18,
            bold: true,
            italic: true,
            // The color of the text.
            color: '#871b47',
            // The color of the text outline.
            auraColor: '#d799ae',
            // The transparency of the text.
            opacity: 0.8
            }
          },
          //backgroundColor: { fill:  '#666' },
          //bar: { groupWidth: "90%" }
          bar: { groupWidth: "50%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };
  </script> 

  <!-- Grafica de pie (pay) 3D Google/chart -->
  <script type="text/javascript">
      // https://www.youtube.com/watch?v=Y83fxTpNSsY ejemplo de grafica de pay google
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Tipo nota', 'Total'],
          @foreach(($regnotamediod as $tipogasto)
             ['{{$tipogasto->tipon_desc}}', {{$tipogasto->total}} ],
          @endforeach
          //['Work',     11],
          //['Eat',      2],
          //['Commute',  2],
          //['Watch TV', 2],
          //['Sleep',    7]
        ]);

        var options = {
          title: 'Tipo de nota',
          //chart: { title: 'Gráfica de Pay',
          //subtitle: 'IAPS por Rubro Social',          
          is3D: true,
          width: 500,                   // Ancho de la pantalla horizontal
          height: 300,                  // Alto de la pantall '75%',          
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d_1'));
        chart.draw(data, options);
      }
  </script>

  <!-- Grafica de barras 2D Google/chart -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Tipo', 'Total'],
          @foreach(($regnotamediod as $tmes)
             ['{{$tmes->tipon_desc}}', {{$tmes->total}} ],
          @endforeach          
        ]);

        var options = {                 
          title: 'Por Mes',
          width:  500,                // Alto de la pantalla horizontal
          height: 500,                // Ancho de la pantalla '75%',
          colors: ['green'],          // Naranja #e7711c
          legend: { position: 'none' },
          chart: { title: 'Gráfica ......... ',
                   subtitle: 'Por tipo de nota' },
          bars: 'horizontal',         // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: '$ Pesos mexicanos'} // Top x-axis.
            }
          },
          annotations: {
            textStyle: {
            fontName: 'Times-Roman',
            fontSize: 18,
            bold: true,
            italic: true,
            // The color of the text.
            color: '#871b47',
            // The color of the text outline.
            auraColor: '#d799ae',
            // The transparency of the text.
            opacity: 0.8
            }
          },
          //backgroundColor: { fill:  '#666' },
          //bar: { groupWidth: "90%" }
          bar: { groupWidth: "50%" }
        };

        var chart = new google.charts.Bar(document.getElementById('combustible'));
        chart.draw(data, options);
      };
  </script>    

  <!-- Grafica de pie (pay) 3D Google/chart consumo $ -->
  <script type="text/javascript">
      // https://www.youtube.com/watch?v=Y83fxTpNSsY ejemplo de grafica de pay google
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Tipo nota', 'Importe total'],
          @foreach($regnotamediod as $tipogasto)  
             ['{{$tipogasto->tipon_desc}}', {{$tipogasto->total}} ],
          @endforeach
          //['Work',     11],
          //['Eat',      2],
          //['Commute',  2],
          //['Watch TV', 2],
          //['Sleep',    7]
        ]);

        var options = {
          title: 'reporte por tipo de nota',
          //chart: { title: 'Gráfica de Pay',
          //         subtitle: 'IAPS por Rubro Social' },          
          is3D: true,
          width: 500,                   // Ancho de la pantalla horizontal
          height: 300,                  // Alto de la pantall '75%',          
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d_2'));
        chart.draw(data, options);
      }
  </script>

      
@endsection
