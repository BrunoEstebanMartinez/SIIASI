$(document).ready(function(){

<<<<<<< HEAD
=======

>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
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
<<<<<<< HEAD
               
=======
              
>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
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
<<<<<<< HEAD
               $('tbody').html(isData);     
=======
               $('tbody').html(isData);
>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
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
<<<<<<< HEAD
    
=======

>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
    $(document).on('keyup', '#isSearch', function(){
        var search = $(this).val();
        var anio = $('#cr_periodo').val();
            if(search != ''){
                inLiveQuery(search, anio);
            }else{
                var search = $(this).val('');
                allDataQuery();
<<<<<<< HEAD
            }
        });
=======
            }      
        });

>>>>>>> 16305e8a577e18fdc3bf29ddadcab125d75cea1b
});