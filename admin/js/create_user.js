$(document).ready(function(){
	
	$("input[name='update_user']").click(function(){
		$(".msg_task").html("&nbsp;");
		if(validInput()){
			
			updateUser();	
		}else{
			$(".msg_task").html("Invalid Input");
		}
	
	});
	
	$("button[name='create_user']").click(function(){
		$(".msg_task").html("&nbsp;");
		if(validInput()){
			
			createUser();	
		}else{
			$(".msg_task").html("Invalid Input");
		}
	
	});
	
});

function updateUser(){
	var user_name_old=$("input[name='user_name_old']").val();
	var user_name_new=$("input[name='user_name']").val();
	if(user_name_old.toLowerCase() != user_name_new.toLowerCase()){
		if(confirm("Are you sure to update User Name. from '"+user_name_old+"' to '"+user_name_new+"'")){
			
		}else{
			return;
		}
	}
	
	var frm=	$("#data_frm").serialize();
	//alert(frm);	
	$(".loading_file").css("display","block");
	$.ajax({
		url: 'do_update_user.php',
		type: 'POST',
		data: frm,
		success: function(result){
			//alert(result);
			$(".msg_task").html(result);
			$(".loading_file").css("display","none");
			
		}
	});
	
}
function createUser(){
	
var frm=	$("#data_frm").serialize();
//alert(frm);	
$(".loading_file").css("display","block");
$.ajax({
	url: 'do_create_user.php',
	type: 'POST',
	data: frm,
	success: function(result){
		//alert(result);
		$(".msg_task").html(result);
		$(".loading_file").css("display","none");
		
	}
});
	
}
function validInput(){
	var valid = true;
	var user_name=$("input[name='user_name']").val();
	var password=$("input[name='password']").val();
	var department_name=$("input[name='department_name']").val();
	var email_id=$("input[name='email_id']").val();
	var mobile_no=$("input[name='mobile_no']").val();
	var city=$("input[name='city']").val();
	var pincode=$("input[name='pincode']").val();
	var address=$("textarea[name='address']").val();
	
	
	if(user_name.length==0){
		valid=false;
		$("input[name='user_name']").css("border","1px solid #A00");
	}else{
		$("input[name='user_name']").css("border","1px solid #b3afaf");
	}
	
	if(password.length==0){
		valid=false;
		$("input[name='password']").css("border","1px solid #A00");
	}else{
		$("input[name='password']").css("border","1px solid #b3afaf");
	}
	
	if(department_name.length==200){
		valid=false;
		$("input[name='department_name']").css("border","1px solid #A00");
		$(".extra_msg_task").html(" Department name should be less than 200 characters.");
	}else{
		$("input[name='department_name']").css("border","1px solid #b3afaf");
	}
	
	if(email_id.length==0){
		valid=false;
		$("input[name='email_id']").css("border","1px solid #A00");
	}else{
		$("input[name='email_id']").css("border","1px solid #b3afaf");
	}
	
	if(mobile_no.length==0){
		valid=false;
		$("input[name='mobile_no']").css("border","1px solid #A00");
	}else{
		$("input[name='mobile_no']").css("border","1px solid #b3afaf");
	}
	
	if(city.length==0){
		valid=false;
		$("input[name='city']").css("border","1px solid #A00");
	}else{
		$("input[name='city']").css("border","1px solid #b3afaf");
	}
	
	if(pincode.length==0){
		valid=false;
		$("input[name='pincode']").css("border","1px solid #A00");
	}else{
		$("input[name='pincode']").css("border","1px solid #b3afaf");
	}
	
	if(address.length==0){
		valid=false;
		$("textarea[name='address']").css("border","1px solid #A00");
	}else{
		$("textarea[name='address']").css("border","1px solid #b3afaf");
	}
	
	return valid;
}