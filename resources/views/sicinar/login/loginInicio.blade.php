@extends('main')

@section('title','Iniciar Sesión')

@section('content')

<body class="hold-transition">

  <div style = "position: absolute; 
                width: 100%;
                height: 100%;
               ">

  
  <div style = "position: relative; 
                width: 100%;
                height: 30%;
                background: url( {{ asset('images/wave.png') }});
                background-position: center;
                background-size:cover;
                background-repeat: no-repeat;
               
              
                "></div>
        

        <div style = "position: absolute; 
                      width: 100%;
                      height: 20%;
                      top: 10px;
                      display: block;">

   
        <img src="{{ asset('images/Logo-Gobierno.png') }}" border="0" width="200" height="60" style = "position: absolute;
                                                                                                        left: 40px;">
    


        
    
        <img src="{{ asset('images/Edomex.png') }}" border="0" width="80" height="60" style = "position: absolute;
                                                                                                        right: 40px;">            

         
        
       
     

      </div>
        
    

  


      <div style = "position: relative;
                                      width: 100%;
                                      height: 45%;
                                      background: #EBF0F5;">
                                      
                                    
                                    </div>

      
      <div style = "position: relative;
                                      width: 100%;
                                      height: 22%;
                                      background: #EBF0F5;">
                  
                         <b style = "position: absolute;
                                    font-size: 17px;
                                    bottom: 10px;
                                    left: 35%;">Copyright &copy;. Derechos reservados. Secretaría de Desarrollo Social - UDITI.</b>
                  

                                    </div>
                <div class = "shadow p-3 mb-5 rounded" style = "position: absolute;
                                    width: 30%;
                                    height: 65%;
                                    background: #FFFFFF;
                                    top: 25%;
                                    left: 34%;
                                    padding: 7em;">

                                    
                          
                                    <!--<img src="{{ asset('images/Logo-Gobierno.png') }}" border="1" width="200" height="60" style="margin-right: 20px;">
                                    <img src="{{ asset('images/Edomex.png') }}" border="1" width="80" height="60">-->
                                    <!-- /.login-logo -->
                              
                                    <p class="login-box-msg" style = "font-size: 20px">SISTEMA DE INTELIGENCIA ARTIFICIAL DE SÍSTESIS INFORMATIVA "SIIASI"</p>
                                  

                                      <p class="login-box-msg" style = "font-size: 15px">Iniciar sesión</p>

                                      {!! Form::open(['route' => 'login', 'method' => 'POST', 'id' => 'logeo']) !!}
                                        <div class="form-group has-feedback">
                                            <input type="text" class="form-control" name="usuario" placeholder="Usuario">
                                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <input type="password" class="form-control" name="password" placeholder="Contraseña">
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        </div>


                                        @if(count($errors) > 0)
                                          <div class="alert alert-danger" role="alert">
                                            <ul>
                                              @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                              @endforeach
                                            </ul>
                                          </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-12 offset-md-5">
                                                <button type="submit" class="btn btn-primary btn-block btn-success">Entrar</button>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="col-md-12 offset-md-5">
                                            <p style="font-family:'Arial, Helvetica, sans-serif'; font-size:12px; text-align:center;">          
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="/images/AVISO_PRIVACIDAD_UDITI.pdf"> Aviso de privacidad</a>
                                            </p>
                                        </div>   
                                      {!! Form::close() !!}
                                
                                    <!-- /.login-box-body -->

                            </div>
                               



 

                </div>


                            <!-- Laravel Javascript Validation -->
            <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

          {!! JsValidator::formRequest('App\Http\Requests\usuarioRequest','#logeo') !!}
</body>

    
@endsection
