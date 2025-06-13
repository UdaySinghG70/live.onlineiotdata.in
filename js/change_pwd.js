$(document).ready(function(){
	
	$("input[name='btn_change_pwd']").click(function(){
		var old_pass=$("input[name='old_pass']").val();
		var new_pass=$("input[name='new_pass']").val();
		var c_new_pass=$("input[name='c_new_pass']").val();
		
		$("input[name='new_pass']").css("border","1px solid #ddd");
		$("input[name='c_new_pass']").css("border","1px solid #ddd");
		
		if(new_pass.length<=0){
			$("input[name='new_pass']").css("border","1px solid #A00");
			$(".msgtask").html("Invalid input");
			return;
		}
		
		if(new_pass!=c_new_pass){
			$("input[name='new_pass']").css("border","1px solid #A00");
			$("input[name='c_new_pass']").css("border","1px solid #A00");
			$(".msgtask").html("Password dosen't match.");
			return;
		}
		
		var frm = $("#data_frm").serialize();
		
		$(".loading_file").css("display","block");
		$.ajax({
			url: 'do_update_user.php',
			type: 'POST',
			data: frm,
			success: function(result){
				//alert(result);
				if(result.indexOf("done")>-1){
					window.location="change_password.php";
				}
				$(".msgtask").html(result);
				$(".loading_file").css("display","none");
				
			}
		});
		
		
		
	});
	
});


