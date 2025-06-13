var deviceWidth;//=$(document).width();
var deviceHeight;//=$(document).height();

//function confirm(message,nobutton,yesbutton,yesfunction){
//	  $('#overlaymessage').html(message);
//	  $('#nobutton').html(nobutton);
//	  $('#yesbutton').html(yesbutton);
//	  $('#overlay').show();
//	  $('#yesbutton').off("click").click(yesfunction);
//	}
$(document).ready(function(){
	

	
	$("input[name='get_devices']").click(function(){
		getDevices();
	});
	
	
});

function getDevices(){
	var username=$("select[name='user_name']").val();
	//var frm=	$("#data_frm").serialize();
	//alert(frm);	
	$(".loading_file").css("display","block");
	$.ajax({
		url: 'get_device_by_user.php',
		type: 'POST',
		data: "username="+username,
		success: function(result){
			//alert(result);
			$(".data").html(result);
			$(".loading_file").css("display","none");
			tb_init('a.thickbox');
			$("input[name='btn_delete']").click(function(){
				var x = confirm("Are you sure to delete Device id?");
				var device = this.getAttribute("device");
				//alert(username+"  "+device);
				if (x) {
					$.ajax({
						url: 'do_delete_device.php',
						type: 'POST',
						data: "username="+username+"&device="+device,
						success: function(result1){
								if(result1.indexOf("Device deleted successfully")>-1){
									getDevices();
								}else{
									$(".msgdata").html(result1);
								}
							}
						});
				    //alert("Good!");
				} else {
				    //alert("Too bad");
				}
			});
		}
	});
}