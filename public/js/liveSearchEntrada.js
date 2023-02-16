$(document).ready(function(){

    $('#periodo').change(function (){
        $('#periodo option:selected').each(function (){
            setOption = $(this).val();
                 textOption = $(this).text();
                if(!setOption){
                    console.log('Nada que mostrar')
                }else{
                    //window.location = setOption;
                    $('#cr_periodo').val(textOption);
                    console.log(setOption + " " + textOption);
                }
        });
    });

    $('#tipo').change(function (){
        $('#tipo option:selected').each(function (){
            setOption = $(this).val();
                 textOption = $(this).text();
                if(!setOption){
                    console.log('Nada que mostrar')
                }else{
                   // window.location = setOption;
                   $('#cr_tipo').val(textOption);
                    console.log(setOption + " " + textOption);
                }
        });
    });


    $('#medio').change(function (){
        $('#medio option:selected').each(function (){
            setOption = $(this).val();
                 textOption = $(this).text();
                if(!setOption){
                    console.log('Nada que mostrar')
                }else{

                    //window.location = setOption;
                    $('#cr_medio').val(textOption);
                    console.log(setOption + " " + textOption);
                }
        });
    });


    
    
              
  function inLiveQuery(todo = '', periodo, medio, tipo){
        $.ajax({
            url: 'buscar/',
            method: 'GET',
            data:{
                todo:todo,
                periodo:periodo,
                medio:medio,
                tipo:tipo
                },
               success:function(data){
               isData = $(data).find('tbody').html();
                console.log(isData);
                console.log(data);
                    $('tbody').html(isData);
                getPaginate = $(data).find('.pagination').html();

                    if(typeof getPaginate !== 'undefined'){
                        console.log(getPaginate);
                    }else{
                        $('.pagination').html('')
                        console.log("getPaginate");
                    }
                        
                    
            }
        })
    }


    function onFilterCUser(todo, periodo, medio, tipo, fechaInit, fechaFin){
            $.ajax({
                url: 'filterPointer/',
                method: 'GET',
                data:{
                  todo:todo,
                  periodo:periodo,
                   medio:medio,
                   tipo:tipo,
                   fechaInit:fechaInit,
                   fechaFin:fechaFin 
                },
                
            })
    }


    function allDataQuery(){
        $.ajax({
            url:"",
            method:'GET',
            success:function(data){
                var isFlush = $(data).find('tbody').html();
                $('tbody').html(isFlush);
            }
        })
    }

    $(document).on('keyup', '#isSearch', function(){
        var search = $(this).val();
            var anio = $('#cr_periodo').val();
                var medio = $('#cr_medio').val();
                     var tipo = $('#cr_tipo').val();
            if(search != ''){
                inLiveQuery(search, anio, medio, tipo);
            }else{
                var search = $(this).val('');
                allDataQuery();
            }      
        });

    $('#filter').click(function(){
        var anio = $('#cr_periodo').val();
            var medio = $('#cr_medio').val();
                var tipo = $('#cr_tipo').val();
                      inLiveQuery('', anio, medio, tipo);
                       
    });

});