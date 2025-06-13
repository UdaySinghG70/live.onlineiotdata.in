$(document).ready(function(){
	$("input[name='start_date']").datepicker({
		changeMonth: true, changeYear: true,dateFormat: 'yy-mm-dd', minDate: new Date('2018-01-01')
		});
		$("input[name='end_date']").datepicker({
			changeMonth: true, changeYear: true,dateFormat: 'yy-mm-dd', minDate: new Date('2018-01-01')
		});
	$(".delete_recharge").click(function(){
		var id=$(this).attr('id');
		var reload_href=$("input[name='device_id_url']").val();
		//var reload_href=$("input[name='reload_href"+id+"']").val();
		//alert(reload_href);
		if(confirm("Are you sure to delete this recharge!") == false){
			return;
		}
		$.ajax({
			url: 'do_delete_recharge.php',
			type: 'POST',
			data: "recharge_id="+id,
			success: function(result){
				if(result.indexOf("done")>-1){
					////console.log("done");
					window.location=""+reload_href;
				}else{
					alert("Some Error");
				}
				//$(".data").html(result);
			},
			error: function() {
				alert("Error occurred while deleting");
			}
		});
	});
	$(".thickbox_link").click(function(){
		var id=$(this).attr('id');
		//alert(id);	
		var reload_href=$("input[name='reload_href"+id+"']").val();
		//reload_href=encodeURIComponent(reload_href);
		//alert(reload_href)
		$.ajax({
			url: 'edit_recharge.php',
			type: 'POST',
			data: "recharge_id="+id+"&reload_href="+reload_href,
			success: function(result){
				//alert(result);
				$(".data").html(result);
				$("input[name='start_date'], input[name='end_date']").datepicker({
					changeMonth: true,
					changeYear: true,
					dateFormat: 'yy-mm-dd',
					minDate: new Date('2018-01-01')
				});

		 		$("input[name='update_recharge']").click(function(){
		 			updateRecharge();
		 		});
		 		
			},
			error: function() {
				alert("Error occurred while loading edit form");
			}
		});
	});
	
	$("button[name='recharge_device']").click(function(){
		doRecharge();
	});
	
});
function updateRecharge(){
	if(validUpdateInput()){
		var recharge_id=$("input[name='recharge_id']").val();
		var start_date=$("input[name='start_date']").val();
		var end_date=$("input[name='end_date']").val();
		
		var reload_href=$("input[name='reload_href']").val();
		//alert(encodeURIComponent(reload_href));
		//return;
		$.ajax({
			url: 'do_update_recharge.php',
			type: 'POST',
			data: "recharge_id="+recharge_id+"&start_date="+start_date+"&end_date="+end_date,
			success: function(result){
				//alert(result);
				if(result.indexOf("done")>-1){
					//alert("http://103.212.120.23/anshu/admin/"+reload_href);
					window.location=""+reload_href;
			 			
				}else{
					//alert(result);
					$(".msg_task_update").html("Error updating recharge: " + result);
				}
				
			},
			error: function() {
				$(".msg_task_update").html("Error occurred while updating recharge");
			}
		});
	}else{
		alert("Invalid Input");
	}
}
function validUpdateInput(){
	var valid = true;
	
	var start_date=$("input[name='end_date']").val();
	var end_date=$("input[name='start_date']").val();

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


function doRecharge(){
	if(validInput()){
		$(".msg_task").html("&nbsp;");
			var frm = $("#recahrge_frm").serialize();
			//alert(frm);
			//return;
			$.ajax({
				url: 'do_recharge.php',
				type: 'POST',
				data: frm,
				success: function(result){
					$(".msg_task").html(result);
					if(result.indexOf("Recharge Successfull")>-1){
						var reload_href=$("input[name='device_id_url']").val();
						window.location=""+reload_href;
					}
				},
				error: function() {
					$(".msg_task").html("Error occurred while processing recharge");
				}
			});
			
		}else{
			$(".msg_task").html("Invalid Input");	
		}
}



function validInput(){
	var valid = true;
	var start_date = $("input[name='start_date']").val();
	var end_date = $("input[name='end_date']").val();
	
	if(start_date.length==0){
		valid=false;
		$("input[name='start_date']").css("border","1px solid #A00");
	}else{
		$("input[name='start_date']").css("border","1px solid #b3afaf");
	}
	
	if(end_date.length==0){
		valid=false;
		$("input[name='end_date']").css("border","1px solid #A00");
	}else{
		$("input[name='end_date']").css("border","1px solid #b3afaf");
	}
	
	return valid;
	
}





