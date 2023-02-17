@extends('sicinar.principal')

@section('title','Estadística por tipo de nota')

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
            <small>Nota informativa</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
            <li><a href="#">Estadísticas    </a></li>
            <li class="active">Nota informativa </li>
          </ol>
      </section>
      <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-success">
                      <div class="box-header">
                          <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                          </div>
                      </div>
                      <div class="box-body">
                        <b style="color:green;text-align:left;"> ESTADÍSTICAS {{$periodo}}    
                            &nbsp;&nbsp; DEL MES
                             &nbsp;&nbsp;
                            @foreach($regmeses as $meses)
                                @if($meses->mes_id == $mes)
                                    {{Trim($meses->mes_desc)}}
                                    @break 
                                @endif
                            @endforeach          
                            &nbsp;&nbsp; DEL {{$dia1}} AL  {{$dia2}}
                        </b>                        

                        <table id="tabla1" class="table table-striped table-bordered table-sm">
                          <thead style="color: brown;" class="justify">
                            <tr>
                              <th rowspan="2" style="text-align:left;"  >ID.          </th>
                              <th rowspan="2" style="text-align:left;"  >TIPO DE NOTA </th>
                              <th rowspan="2" style="text-align:center;">TOTAL        </th>
                              <th rowspan="2" style="text-align:center;">%            </th>
                            </tr>
                            <tr>
                            </tr>
                          </thead>

                          <tbody>
                            <?php $sumatotal = 0; ?>
                            @foreach($regnotamediot as $totales)
                               <?php $sumatotal = $totales->total; ?>
                            @endforeach                

                            <?php $i     = 0; ?>
                            <?php $sum01 = 0; ?>
                            <?php $sum02 = 0; ?>
                            <?php $sum03 = 0; ?>
                            <?php $sum04 = 0; ?>
                            <?php $sum05 = 0; ?>
                            <?php $sum06 = 0; ?>
                            <?php $sum07 = 0; ?>
                            <?php $sum08 = 0; ?>
                            <?php $sum09 = 0; ?>
                            <?php $sum10 = 0; ?>
                            <?php $sum11 = 0; ?>
                            <?php $sum12 = 0; ?>
                            <?php $porcen= 0; ?>
                            <?php $sumpor= 0; ?>

                            @foreach($regnotamediod as $nota)
                              <?php $i     = $i + 1;  ?>
                              <?php $porcen= ($nota->total*100)/$sumatotal; ?>                            
                              <tr>
                                <td style="color:darkgreen;">{{$nota->tipon_id}}</td>
                                <td style="color:darkgreen;">{{$nota->tipon_desc}}   </td>
                                <td style="color:darkgreen; text-align:center;">{{$nota->total}}</td>
                                <td style="color:darkgreen; text-align:center;"><small>{{number_format($porcen,0)}}</small></td>
                              </tr>
                            @endforeach
                            @foreach($regnotamediot as $totales)
                               <tr>
                                  <td>                                  </td>
                                  <td style="color:green;"><b>TOTAL</b> </td>                         
                                  <td style="color:green; text-align:center;"><b>{{$totales->total}} </b></td>
                                  <td>                                  </td>
                               </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                </div>
            </div>

            <!-- Grafica de barras 2-->
            <div class="row">
                <div class="col-md-6">
                    <div class="box">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <!--<h3 class="box-title" style="text-align:center;">Gráfica por estado 2D </h3>  -->
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
            </div>  

            <!-- Grafica de pie en 3D  -->
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

            <!-- Grafica de barras 2  -->
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

              <!-- Grafica de dona 
              Making a donut chart
              https://developers.google.com/chart/interactive/docs/gallery/piechart#donut
              -->
              <div class="col-md-6">
                  <div class="box">
                      <div class="box box-danger">
                          <!-- <div class="box-header with-border"> -->
                          <!-- <h3 class="box-title" style="text-align:center;">Gráfica por Rubro social 3D </h3> -->
                          <!-- BOTON para cerrar ventana x -->
                          <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                          </div>
                          <!-- Pinta la grafica de barras 2-->
                          <div class="box-body">
                              <camvas id="donutchart" style="width: 900px; height: 500px;"></camvas>
                          </div>
                          <!-- </div> -->
                      </div>
                  </div>
              </div>        



        </div>
      </section>
  </div>
@endsection

@section('request')
  <script src="{{ asset('bower_components/chart.js/Chart.js') }}"></script>

  <!-- Grafica google de pay, barras en 3D
    https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart
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
          ['Tipo', 'Total'],
          @foreach($regnotamediod as $nota)
             ['{{$nota->tipon_desc}}', {{$nota->total}} ],
          @endforeach          
        ]);

        var options = {
          //Boolean - Whether we should show a stroke on each segment
          //segmentShowStroke    : true,
          //String - The colour of each segment stroke
          //segmentStrokeColor   : '#fff',
          //Number - The width of each segment stroke
          //segmentStrokeWidth   : 2,
          //Number - The percentage of the chart that we cut out of the middle
          //percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          //animationSteps       : 100,  
                 
          title: 'Por Tipo de nota',
          width: 500,                   // Ancho de la pantalla horizontal
          height: 300,                  // Alto de la pantall '75%',
          colors: ['green'],          // Naranja
          //backgroundColor:'#fdc', 
          stroke:'red',
          highlight: 'blue',
          legend: { position: 'none' },
          chart: { title: 'Numeralia básica',
                   subtitle: 'Gráfica por tipo de nota' },
          bars: 'horizontal', // Required for Material Bar Charts.
          //bars: 'vertical', // Required for Material Bar Charts.
          //chartArea:{left:20, top:0, width:'50%', height:'75%', backgroundColor:'#fdc', stroke:'green'},
          axes: {
            x: {
              0: { side: 'top', label: 'Total'} // Top x-axis.
              //1: { side: 'top', label: 'Total de oscS'} // Top x-axis.
              //distance: {label: 'Total'}, // Bottom x-axis.
              //brightness: {side: 'top', label: 'Total de oscS'} // Top x-axis.
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
          title: 'Tipo de nota informativa',
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
          ['Tipo nota', 'Total'],
          @foreach($regnotamediod as $tmes)
             ['{{$tmes->tipon_desc}}', {{$tmes->total}} ],
          @endforeach          
        ]);

        var options = {                 
          title: 'Por tipo de nota',
          width:  500,                // Alto de la pantalla horizontal
          height: 500,                // Ancho de la pantalla '75%',
          colors: ['green'],          // Naranja #e7711c
          legend: { position: 'none' },
          chart: { title: 'Gráfica  ',
                   subtitle: 'Por tipo de nota' },
          bars: 'horizontal',         // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'total'} // Top x-axis.
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

  <!-- Grafica de dona 
  Making a donut chart
  https://developers.google.com/chart/interactive/docs/gallery/piechart#donut
  -->
  <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Tipo nota', 'Total'],
          @foreach($regnotamediod as $placa)
             ['{{$placa->tipon_desc}}', {{$placa->total}} ],
          @endforeach            
          //['Work',     11],
          //['Eat',      2],
          //['Commute',  2],
          //['Watch TV', 2],
          //['Sleep',    7]
        ]);

        var options = {
          title: 'Gráfica por Tipo de nota',
          pieHole: 0.4,
          width: 500,                   // Ancho de la pantalla horizontal
          height: 300,                  // Alto de la pantall '75%',
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
  </script>  
@endsection
