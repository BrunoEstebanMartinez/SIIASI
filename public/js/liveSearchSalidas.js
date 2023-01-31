$(document).ready(function(){

    function inLiveQuery(todo = '', periodo, arbol){
        $.ajax({
            url:"periodo/buscar",
            method: 'GET',
            data:{
                todo:todo,
                periodo:periodo,
                arbol:arbol},
            success:function(data){
                //$('tbody').html(data)
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
        anio = $('#periodo').val();
        var arbol = $('#arbol').val();
        if(search != ''){
            inLiveQuery(search, anio, arbol);
        }else{
            var search = $(this).val('');
            allDataQuery();
        }
       
    });
});

