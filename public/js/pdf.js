$(document).ready(function() {


    function responseOpenO(VALUE){

        tokenApi = "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";
        max_tokens = 3625;
    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        $.ajax({
            method: 'POST',
            url: "https://api.openai.com/v1/completions",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + tokenApi,
            },
            data: JSON.stringify({
                    model: 'text-davinci-003',
                    prompt: VALUE + "Tl;dr",
                    temperature: 0.7,
                    max_tokens: max_tokens,
                    top_p: 1.0,
                    frequency_penalty: 0,
                    presence_penalty: 1
            }),
    
    
            beforeSend: function(){
                    $('#onPaidLoad').html("Cargando...");
                    console.log('Is Working');
            },
    
            success:function(data){
    
                var TEST = JSON.stringify(data, undefined, 2);
    
                    responseAI = data["choices"][0]["text"];
                    $("#nm_ia").val(responseAI);
                    console.log(TEST + " " + responseAI);	
            },
    
            complete: function(){
                $('#onPaidLoad').html("Respuesta")
                console.log("All done");
            }
    
        });
    
      }

      
});

