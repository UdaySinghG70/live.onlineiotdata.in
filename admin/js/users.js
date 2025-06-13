$(document).ready(function(){
	$(".prev_btn").click(function(){
		var pageIndex=$("input[name='current_page']").val();
		
		if(parseInt(pageIndex)>=1){
			pageIndex=parseInt(pageIndex)-1;
		}
		//alert(pageIndex);
		//getData(pageIndex);
		document.location = "users.php?pg="+(parseInt(pageIndex));
	});
	$(".next_btn").click(function(){
		var pageIndex=$("input[name='current_page']").val();
		//getData(parseInt(pageIndex)+1);
		document.location = "users.php?pg="+(parseInt(pageIndex)+1);
	});
	$(".btn_goto_page").click(function(){
		var pageIndex=$("input[name='go_to_page']").val();
		//getData(parseInt(pageIndex));
		document.location = "users.php?pg="+(parseInt(pageIndex));
	});	
	
	$(".delete_user").click(function(){
		var x = confirm("Are you sure to delete Device id?");
		if(x){
			var user = this.getAttribute("rel-user");
			$(".msgdata").html("&nbsp;");
			$.ajax({
				url: 'do_delete_user.php',
				type: 'POST',
				data: "username="+user,
				success: function(result1){
						if(result1.indexOf("User deleted successfully")>-1){
							var pageIndex=$("input[name='current_page']").val();
							//getDevices();
							document.location = "users.php?pg="+(parseInt(pageIndex));
						}else if(result1.indexOf("Delete Devices under this user")>-1){
							alert("Can't delete User, Delete devices first under this user "+user);
							$(".msgdata").html(result1 +" "+user);
						}else{
							$(".msgdata").html(result1);
						}
					}
			});
				
		}else{
			
		}
	});
});

