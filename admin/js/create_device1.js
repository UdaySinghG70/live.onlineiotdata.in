$(document).ready(function(){
	
	$(".add_row").click(function(){
		
		$.ajax({
			url: 'modem_param_row.php',
			type: 'POST',
			success: function(result){
				//alert(result);
				$(".params_tbody").append(result);
				resetNames();

			}
		});
		
	});

	$("button[name='edit_device']").click(function(){
		$(".msg_task").html("&nbsp;");
		if(isValidModemParam()){
			if(isValidInput()){
				updateDevice();	
				
			}else{
				//alert("false");
				$(".msg_task").html("Invalid Input.");
			}	
		}
		else{
			//alert("false");
			$(".msg_task").html("Invalid Input.");
		}
		
	});

	$("button[name='create_device']").click(function(){
		$(".msg_task").html("&nbsp;");
		
		if(isValidModemParam()){
			if(isValidInput()){
					createDevice();		
			}else{
				$(".msg_task").html("Invalid Input - Please check all required fields are filled correctly.");
			}
			
		}else{
			$(".msg_task").html("Invalid Modem Parameters - Please check all parameter fields are filled correctly.");
		}
		
	});
	
	$(".add_row_db").click(function(){
		$.ajax({
			url: 'modem_param_row_db.php',
			type: 'POST',
			success: function(result){
				$(".params_tbody_db").append(result);
				resetNamesDb();
			}
		});
	});
	
});
function changeUnit(handle){
	var y=handle.name;
	var row_id= y.charAt(y.length-1);
	var selectedVal = $(handle).find("option:selected").attr("data-unit");
	//console.log("unit"+row_id);
	//var x = document.getElementsByName("unit"+row_id);
	var x = document.getElementsByName("unit"+row_id)[0];
	x.value=selectedVal;
	//alert(selectedVal);
	
}
function removeRow(handle){
	$(handle).parents("tr").remove();
	resetNames();
}

function isValidModemParam(){
	
	var count=0;
	count = 0;
	var valid= true;
	$(".param_name").each(function(){
		
		++count;
		$(this).attr("name", "param_name" + count);
		if($(this).val().length<=0){
			//alert("invalid input");
			$(this).css("border-color","#A00");
			valid= false;
		}else{
			$(this).css("border-color","#b3afaf");
		}
	});
	
	count=0;
	$(".position").each(function(){
		
		++count;
		$(this).attr("name", "position" + count);
		if($(this).val().length<=0){
			$(this).css("border-color","#A00");
			valid= false;
		}else{
//			if( Number.isInteger( parseInt($(this).val()) ) ){
				$(this).css("border-color","#b3afaf");
//			}else{
//				alert($(this).val());
//					$(this).css("border-color","#A00");
//				valid= false;
//			}
			
		}
	});
	
	
	count = 0;
	$(".unit").each(function(){
		
		++count;
		$(this).attr("name", "unit" + count);
		if($(this).val().length<=0){
			//alert("invalid input");
			$(this).css("border-color","#A00");
			valid= false;
		}else{
			$(this).css("border-color","#b3afaf");
		}
	});

	
	return valid;
}

function resetNames(){

	count = 0;
	var first = true;
	$(".row_id_lbl").each(function(){
		
		++count;
		$(this).attr("name", "serialnumber" + count);
		$(this).html(count + ".");
	});

	count = 0;
	$(".param_name").each(function(){
		
		++count;
		$(this).attr("name", "param_name" + count);
	});

	count = 0;
	$(".param_type").each(function(){
		
		++count;
		$(this).attr("name", "param_type" + count);
	});

	count = 0;
	$(".position").each(function(){
		
		++count;
		$(this).attr("name", "position" + count);
	});

	count = 0;
	$(".unit").each(function(){
		
		++count;
		$(this).attr("name", "unit" + count);
	});

 	$("input[name='count']").val(count-1);
}
function updateDevice(){
	var user_name_got=$("input[name='user_name_got']").val();
	var user_name_new=$("select[name='user_name']").val();
	if(user_name_got.toLowerCase() != user_name_new.toLowerCase()){
		if(confirm("Are you sure to update User Name. from '"+user_name_got+"' to '"+user_name_new+"'")){
			
		}else{
			return;
		}
	}
	var device_id_got=$("input[name='device_id_got']").val();
	var device_id_new=$("input[name='device_id']").val();
	if(device_id_got.toLowerCase() != device_id_new.toLowerCase()){
		if(confirm("Are you sure to update Device ID. from '"+device_id_got+"' to '"+device_id_new+"'")){
			
		}else{
			return;
		}
	}
	
	// Reset names for both live and database parameters
	resetNames();
	resetNamesDb();
	
	var frm = $("#data_frm").serialize();
	console.log("Form data being sent:", frm);
	$(".loading_file").css("display","block");
	
	// First update the device details and live data parameters
	$.ajax({
		url: 'do_update_device.php',
		type: 'POST',
		data: frm,
		success: function(result){
			console.log("Device update response:", result);
			if(result.indexOf("Updated") >= 0 || result.indexOf("updated") >= 0) {
				// If device update was successful, save the database parameters
				var dbParams = {
					device_id: $("input[name='device_id']").val(),
					count: $("input[name='count_db']").val()
				};
				
				// Add database parameters to the data
				var index = 0;
				$(".params_tbody_db tr").each(function() {
					var row = $(this);
					var paramName = row.find("input[name^='param_name_db']").val();
					var paramType = row.find("select[name^='param_type_db']").val();
					var paramUnit = row.find("input[name^='unit_db']").val();
					var paramPosition = row.find("input[name^='position_db']").val();
					
					// Only add if we have valid data
					if (paramName && paramType) {
						dbParams['paramName_db[' + index + ']'] = paramName;
						dbParams['paramType_db[' + index + ']'] = paramType;
						dbParams['paramUnit_db[' + index + ']'] = paramUnit;
						dbParams['paramPosition_db[' + index + ']'] = paramPosition;
						index++;
					}
				});
				
				// Update the count to reflect actual number of parameters
				dbParams.count = index;
				
				console.log("Sending database parameters:", dbParams);
				
				$.ajax({
					url: 'do_save_logparams.php',
					type: 'POST',
					data: dbParams,
					success: function(paramResult){
						// Try to handle JSON error for unauthorized
						try {
							var parsed = (typeof paramResult === 'string') ? JSON.parse(paramResult) : paramResult;
							if (parsed && parsed.error === 'unauthorized') {
								window.location.href = parsed.login_url || '../index.php';
								return;
							}
						} catch (e) {
							// Not JSON, continue as normal
						}
						console.log("Parameter save response:", paramResult);
						$(".msg_task").html(result + " " + paramResult);
						$(".loading_file").css("display","none");
					},
					error: function(xhr, status, error) {
						console.error("Error saving parameters:", error);
						$(".msg_task").html(result + " But failed to save database parameters: " + error);
						$(".loading_file").css("display","none");
					}
				});
			} else {
				console.error("Device update failed:", result);
				$(".msg_task").html("Failed to update device: " + result);
				$(".loading_file").css("display","none");
			}
		},
		error: function(xhr, status, error) {
			console.error("Ajax error:", error);
			$(".msg_task").html("Error updating device: " + error);
			$(".loading_file").css("display","none");
		}
	});
}

function createDevice(){
	$(".loading_file").css("display","block");
	$(".msg_task").html("Creating device...");
	
	// Reset names for both live and database parameters
	resetNames();
	resetNamesDb();
	
	// First create the device
	var frm = $("#data_frm").serialize();
	console.log("Form data being sent:", frm);
	
	$.ajax({
		url: 'do_create_device.php',
		type: 'POST',
		data: frm,
		success: function(result){
			console.log("Device creation response:", result);
			if(result.indexOf("Created") >= 0) {
				// If device creation was successful, save the database parameters
				var dbParams = {
					device_id: $("input[name='device_id']").val()
				};
				
				// Collect all database parameter rows (manual and preset)
				var paramNames = [];
				var paramTypes = [];
				var paramUnits = [];
				var paramPositions = [];
				$(".params_tbody_db tr").each(function() {
					var row = $(this);
					var paramName = row.find(".param_name_db").val();
					var paramType = row.find(".param_type_db").val();
					var paramUnit = row.find(".unit_db").val();
					var paramPosition = row.find(".position_db").val();
					if (paramName && paramType) {
						paramNames.push(paramName);
						paramTypes.push(paramType);
						paramUnits.push(paramUnit);
						paramPositions.push(paramPosition);
					}
				});
				dbParams['paramName_db'] = paramNames;
				dbParams['paramType_db'] = paramTypes;
				dbParams['paramUnit_db'] = paramUnits;
				dbParams['paramPosition_db'] = paramPositions;
				dbParams.count = paramNames.length;
				
				console.log("Sending database parameters:", dbParams);
				
				$.ajax({
					url: 'do_save_logparams.php',
					type: 'POST',
					data: dbParams,
					success: function(paramResult){
						// Try to handle JSON error for unauthorized
						try {
							var parsed = (typeof paramResult === 'string') ? JSON.parse(paramResult) : paramResult;
							if (parsed && parsed.error === 'unauthorized') {
								window.location.href = parsed.login_url || '../index.php';
								return;
							}
						} catch (e) {
							// Not JSON, continue as normal
						}
						console.log("Parameter save response:", paramResult);
						$(".msg_task").html(result + " " + paramResult);
						$(".loading_file").css("display","none");
					},
					error: function(xhr, status, error) {
						console.error("Error saving parameters:", error);
						$(".msg_task").html(result + " But failed to save database parameters: " + error);
						$(".loading_file").css("display","none");
					}
				});
			} else {
				console.error("Device creation failed:", result);
				$(".msg_task").html("Failed to create device: " + result);
				$(".loading_file").css("display","none");
			}
		},
		error: function(xhr, status, error) {
			console.error("Ajax error:", error);
			$(".msg_task").html("Error creating device: " + error);
			$(".loading_file").css("display","none");
		}
	});
}

function isValidInput(){
	var valid = true;
	var mobile_nr=$("input[name='mobile_nr']").val();
	
	var device_id=$("input[name='device_id']").val();
	var imei_nr=$("input[name='imei_nr']").val();
	var timezone=$("input[name='timezone']").val();
	var latitude=$("input[name='latitude']").val();
	var longitude=$("input[name='longitude']").val();
	var city=$("input[name='city']").val();
	var place=$("input[name='place']").val();
	//var country=$("select[name='country']").val();
	var user_name=$("select[name='user_name']").val();
	
	if(user_name.length==0){
		valid=false;
	console.log("username false");	
		$("select[name='user_name']").css("border","1px solid #A00");
	}else{
		$("select[name='user_name']").css("border","1px solid #b3afaf");
	}
	
	if(device_id.length==0){
		console.log("device id false");		
		valid=false;
		$("input[name='device_id']").css("border","1px solid #A00");
	}else{
		$("input[name='device_id']").css("border","1px solid #b3afaf");
	}
	
	if(imei_nr.length==0){
		console.log("imei nrfalse");	
		valid=false;
		$("input[name='imei_nr']").css("border","1px solid #A00");
	}else{
		$("input[name='imei_nr']").css("border","1px solid #b3afaf");
	}
	
	if(timezone.length==0){
		valid=false;
		console.log("timwzone false");	
		$("input[name='timezone']").css("border","1px solid #A00");
	}else{
		$("input[name='timezone']").css("border","1px solid #b3afaf");
	}
	
	if(mobile_nr.length<=1){
		console.log("mobile nr false");	
		valid=false;
		$("input[name='mobile_nr']").css("border","1px solid #A00");
	}else{
		$("input[name='mobile_nr']").css("border","1px solid #b3afaf");
	}
	
	if(latitude.length<=1){
		console.log("latitude false");	
		valid=false;
		$("input[name='latitude']").css("border","1px solid #A00");
	}else{
		$("input[name='latitude']").css("border","1px solid #b3afaf");
	}
	
	if(longitude.length<=1){
		console.log("longitude false");	
		valid=false;
		$("input[name='longitude']").css("border","1px solid #A00");
	}else{
		$("input[name='longitude']").css("border","1px solid #b3afaf");
	}
	
	if(city.length<=1){
		console.log("city false");	
		valid=false;
		$("input[name='city']").css("border","1px solid #A00");
	}else{
		$("input[name='city']").css("border","1px solid #b3afaf");
	}
	
	if(place.length<=1){
		console.log("place false");	
		valid=false;
		$("input[name='place']").css("border","1px solid #A00");
	}else{
		$("input[name='place']").css("border","1px solid #b3afaf");
	}

	// Add validation for database parameters
	$(".param_name_db").each(function(){
		if($(this).val().length <= 0){
			$(this).css("border-color","#A00");
			valid = false;
		} else {
			$(this).css("border-color","#b3afaf");
		}
	});

	$(".unit_db").each(function(){
		if($(this).val().length <= 0){
			$(this).css("border-color","#A00");
			valid = false;
		} else {
			$(this).css("border-color","#b3afaf");
		}
	});

	$(".position_db").each(function(){
		if($(this).val().length <= 0){
			$(this).css("border-color","#A00");
			valid = false;
		} else {
			$(this).css("border-color","#b3afaf");
		}
	});

	return valid;
}

function resetNamesDb() {
	count = 0;
	var first = true;
	$(".row_id_lbl_db").each(function(){
		++count;
		$(this).attr("name", "serialnumber_db" + count);
		$(this).html(count + ".");
	});

	count = 0;
	$(".param_name_db").each(function(){
		++count;
		$(this).attr("name", "param_name_db" + count);
	});

	count = 0;
	$(".param_type_db").each(function(){
		++count;
		$(this).attr("name", "param_type_db" + count);
	});

	count = 0;
	$(".position_db").each(function(){
		++count;
		$(this).attr("name", "position_db" + count);
	});

	count = 0;
	$(".unit_db").each(function(){
		++count;
		$(this).attr("name", "unit_db" + count);
	});

	$("input[name='count_db']").val(count-1);
}

function changeUnitDb(handle){
	var y = handle.name;
	var row_id = y.charAt(y.length-1);
	var selectedVal = $(handle).find("option:selected").attr("data-unit");
	var x = document.getElementsByName("unit_db"+row_id)[0];
	x.value = selectedVal;
}

function removeRowDb(handle){
	$(handle).parents("tr").remove();
	resetNamesDb();
}