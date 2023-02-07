$(document).ready(function(){


    $('#periodo').change(function (){
        $('#periodo option:selected').each(function (){
            setOption = $(this).val();
            textOption = $(this).text();
                if(!setOption){
                    console.log('Nada que mostrar')
                }else{
                    window.location = setOption;
                    console.log(setOption + " " + textOption);
                }
        })
    })
              
  function inLiveQuery(todo = '', periodo){
        $.ajax({
            url: 'buscar/' + periodo,
            method: 'GET',
            data:{
                todo:todo,
                periodo:periodo
                },
               success:function(data){
               var isData = $(data).find('tbody').html();
               console.log(isData);
               $('tbody').html(isData);
            }
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
            if(search != ''){
                inLiveQuery(search, anio);
            }else{
                var search = $(this).val('');
                allDataQuery();
            }      
        });

});