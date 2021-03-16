jQuery(document).ready(function($) {
	$(document).on('submit','#form',function(e){
		e.preventDefault()
		let date = $("#date_input").val();


		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		

		$.ajax({
			url: $(this).attr('action'),
			type: 'post',
			data: {date},
			success:(r)=>{
				$('#message_container').remove()
				if(r){
					r = JSON.parse(r)
					let div = `<div id='message_container' class="alert alert-success">
					  				${r.celebrity}`
					
					if(r.add_day_offs){
						var text = `<br><span>Additional days offs `
						for(let i=0;i<r.add_day_offs.length;i++){
							text+=`${r.add_day_offs[i]} , `
						}
						text+='</span>'
						div+=text+"</div>"
					}
					$(this).append(div)
				}
			}
		})		
	})
});