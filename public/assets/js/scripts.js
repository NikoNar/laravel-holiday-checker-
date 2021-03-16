jQuery(document).ready(function($) {
	$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});	
	$(document).on('submit','#form',function(e){
		e.preventDefault()
		let date = $("#date_input").val();
		$('.toast').toast('hide')
		$('.toast-header').removeClass('bg-danger')
		$('.toast-header').removeClass('bg-success')
		$('.toast-header').removeClass('bg-warning')
		$.ajax({
			url: $(this).attr('action'),
			type: 'post',
			data: {date},
			success:(r)=>{
				if(r){
					r = JSON.parse(r)
					if(!r.day_off){
						var message = `<p>${r.celebrity}</p>`
						$('.toast-header').addClass('bg-success')
					}

					if(r.day_off && r.day_off == true){
						var message = `<span>Day off. <br> Previous celebrity - ${r.previous_celebrity}</span>`
						$('.toast-header').addClass('bg-warning')
					}

					if(!r.day_off && r.add_day_offs){
						var text = `<br><span>Additional days offs `
						for(let i=0;i<r.add_day_offs.length;i++){
							text+=`${r.add_day_offs[i]} , `
						}
						text+='</span>'
						message+=text+"</p>"
					}
					$(".toast").find('.toast-body').html(message)
					$('.toast').toast('show')
				}
			},
			error:(r)=>{
				$(".toast").find('.toast-body').html(r.responseJSON.errors.date[0])
				$('.toast-header').addClass('bg-danger')
				$('.toast').toast('show')
			}
		})		
	})
});