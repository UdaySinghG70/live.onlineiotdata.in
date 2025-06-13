$(document).ready(function(){
	
	$("button[name='get_devices']").click(function(){
		var username = $("select[name='user_name']").val();
		
		if (!username) {
			alert("Please select a user");
			return;
		}
		
		$(".loading_file").css("display","block");
		
		var $button = $(this);
		$button.prop('disabled', true).html('<span class="material-icons">hourglass_empty</span> Loading...');
		
		$.ajax({
			url: 'get_device_data_frm.php',
			type: 'POST',
			data: "username="+username,
			success: function(result){
				$(".data").html(result);
				$(".loading_file").css("display","none");
				tb_init('a.thickbox');
				
				if($.datepicker){
					$("input[name='start_date']").datepicker({
						dateFormat: 'yy-mm-dd'
					});
					$("input[name='end_date']").datepicker({
						dateFormat: 'yy-mm-dd'
					});
				}
				
				$("input[name='get_data']").click(function(){
					if(validInput()){
						getData(1);
					}else{
						$(".msg_task").html("Invalid Input.");
					}
				});
			},
			error: function(xhr, status, error) {
				$(".data").html('<div class="msg-container error"><span class="material-icons">error</span>Error loading data: ' + error + '</div>');
			},
			complete: function() {
				$button.prop('disabled', false).html('<span class="material-icons">search</span> Get Data');
				$(".loading_file").css("display","none");
			}
		});
	});
	
});

function getData(pg){
	$(".loading_file").css("display","block");
	
	var start_date = $("input[name='start_date']").val();
	var end_date = $("input[name='end_date']").val();
	var device_id = $("select[name='device_id']").val();
	
	var $dataButton = $("input[name='get_data']");
	$dataButton.prop('disabled', true).val('Loading...');
	
	$.ajax({
		url: 'get_device_data.php',
		type: 'POST',
		data: "startdate="+start_date+"&enddate="+end_date+"&device_id="+device_id,
		success: function(result){
			$(".received_data").html(result);
		},
		error: function(xhr, status, error) {
			$(".received_data").html('<div class="msg-container error"><span class="material-icons">error</span>Error loading data: ' + error + '</div>');
		},
		complete: function() {
			$(".loading_file").css("display","none");
			$dataButton.prop('disabled', false).val('Get Data');
		}
	});
}

function validInput(){
	var valid = true;
	var start_date = $("input[name='start_date']").val();
	var end_date = $("input[name='end_date']").val();
	var device_id = $("select[name='device_id']").val();
	
	if(start_date.length <= 0){
		$("input[name='start_date']").css("border-color","red");
		valid = false;
	}else{
		$("input[name='start_date']").css("border-color","#ddd");
	}
	
	if(end_date.length <= 0){
		$("input[name='end_date']").css("border-color","red");
		valid = false;
	}else{
		$("input[name='end_date']").css("border-color","#ddd");
	}
	
	if(!device_id) {
		$("select[name='device_id']").css("border-color","red");
		valid = false;
	}else{
		$("select[name='device_id']").css("border-color","#ddd");
	}
	
	if(!valid) {
		$(".msg_task").html("Please fill in all required fields").css("color", "red");
	}
	
	return valid;
}