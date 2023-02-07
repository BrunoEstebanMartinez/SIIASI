$(document).ready(function(){

  $(document).ready(function() {
	$("#airesponse").click(function(){
		//responseOpen();
			isResponse = $('#nm_link').val();
				test = $('#aux').text(isResponse);
					point = $('#aux').text();
				console.log(isResponse + " " + point);		
					responseOpenO(point);
			
	});

  });


  function responseOpenO(VALUE){

	const tokenApi = "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";
	const max_tokens = 3625;
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
				$('#onPaidLoad').html("...");
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