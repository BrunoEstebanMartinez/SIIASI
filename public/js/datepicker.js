$(document).ready(function(){
    $(document).on('keyup', '#datepickerOf', function(){
        splitDFOficio();
    });
    $(document).on('keyup', '#datepickerRec', function(){
        splitDFRecibido();
    });
})

function splitDFOficio(){

        getDPickerOf  = $('#datepickerOf').val().split("-");

       //Fecha de oficio
        perYearO = getDPickerOf[0];
        perMonthO = getDPickerOf[1];
        perDayO = getDPickerOf[2];

        console.log(perDayO + " " + perMonthO + " " + perYearO);
        console.log(getDPickerOf);

        dia_id1     = $('#dia_id1').val(perDayO);
        mes_id1     = $('#mes_id1').val(perMonthO);
        periodo_id1 = $('#periodo_id1').val(perYearO);

}

function splitDFRecibido(){
    
    getDPickerRec = $('#datepickerRec').val().split("-"); 

     //Fecha de recibido
     perYearR = getDPickerRec[0];
     perMonthR = getDPickerRec[1];
     perDayR = getDPickerRec[2];

     console.log(perDayR + " " + perMonthR + " " + perYearR);
     console.log(getDPickerRec);

     dia_id2     = $('#dia_id2').val(perDayR);
     mes_id2     = $('#mes_id2').val(perMonthR);
     periodo_id2 = $('#periodo_id2').val(perYearR);
}

