$(document).ready(function(){
	
	$(".delete_recharge").click(function(){
		
	});
	$("select[name='user_name']").change(function(){
		var user_name = $(this).val();
		$(".loading_file").css("display","block");
		$(".data").html("&nbsp;");
		$(".msg_task").html("&nbsp;");
		$.ajax({
			url: 'get_device_for_recharge.php',
			//url: 'get_recharge_frm.php',
			type: 'POST',
			data: "user_name="+user_name,
			success: function(result){
				//alert(result);
				$(".data").html(result);
				$(".loading_file").css("display","none");
				//$("input[name='start_date']").datepicker({
		 			//dateFormat: 'yy-mm-dd',
		 		//});
			    //$( "#startdate" ).datepicker();
		 		//$("input[name='end_date']").datepicker({
		 			//dateFormat: 'yy-mm-dd',
		 		//});
				$("input[name='start_date']").datepicker({ 
					changeMonth: true, 
					changeYear: true, 
					dateFormat: 'yy-mm-dd',
					maxDate: '0', 
					minDate: new Date('2018-01-01')
				});
				$("input[name='end_date']").datepicker({ 
					changeMonth: true, 
					changeYear: true, 
					dateFormat: 'yy-mm-dd',
					maxDate: '0', 
					minDate: new Date('2018-01-01')
				});
				$("select[name='device_id']").change(function(){
					var url = $(this).val();
					var device = $(this).find('option:selected').text();
					if(url != "-"){
						// Add timestamp to prevent caching
						url += (url.indexOf('?') >= 0 ? '&' : '?') + '_=' + new Date().getTime();
						tb_show('Recharge ' + device, url, false);
						
						// Ensure proper positioning after load
						$('#TB_window').on('load', '#TB_iframeContent', function() {
							if(typeof tb_position === 'function') {
								tb_position();
							}
						});
					}
				});
		 		
//		 		$("input[name='recharge_device']").click(function(){
//		 			doRecharge();
//		 		});
		 		
			}
		});
	})
	
	
	
});
function doRecharge(){
	if(validInput()){
		$(".msg_task").html("&nbsp;");
			var frm = $("#recahrge_frm").serialize();
			$(".loading_file").css("display","block");
			$.ajax({
				url: 'do_recharge.php',
				type: 'POST',
				data: frm,
				success: function(result){
					$(".msg_task").html(result);
					$(".loading_file").css("display","none");
				}
			});
			
		}else{
			$(".msg_task").html("Invalid Input");	
		}
}



function validInput(){
	var valid = true;
	var user_name=$("select[name='user_name']").val();
	var device_id=$("select[name='device_id']").val();
	var start_date=$("input[name='end_date']").val();
	var end_date=$("input[name='start_date']").val();
	
	if(user_name=="-"){
		valid=false;
		$("select[name='user_name']").css("borrder","1px solid #A00");
	}else{
		$("select[name='user_name']").css("borrder","1px solid #b3afaf;");
	}
	
	if(device_id.length==0){
		valid=false;
		$("select[name='device_id']").css("borrder","1px solid #A00");
	}else{
		$("select[name='device_id']").css("borrder","1px solid #b3afaf;");
	}
	
	if(start_date.length==0){
		valid=false;
		$("input[name='start_date']").css("borrder","1px solid #A00");
	}else{
		$("input[name='start_date']").css("borrder","1px solid #b3afaf;");
	}
	
	if(end_date.length==0){
		valid=false;
		$("input[name='end_date']").css("borrder","1px solid #A00");
	}else{
		$("input[name='end_date']").css("borrder","1px solid #b3afaf;");
	}
	
	return valid;
	
}


