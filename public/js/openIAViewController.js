$(document).ready(function(){


	function responseOpen(option_n){

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url:"nuevo/openia",
			method:'POST',
			data:{option_n:option_n},

			success:function(data){
				console.log("Hecho" + data);
			}
		})
	}

	$(document).on('keyup', '#nm_link', function(){
		var option_n = $(this).val();
			responseOpen(option_n);
	});



});