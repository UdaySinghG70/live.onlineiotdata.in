 
$(document).ready(function(){
	
	disableKeyPress(".donttype");
	$("input[name='refresh_btn']").click(function(){
		$(".loader_gif").css("display","block");
		launchIntervalGetStatus();
	});
//	$("#addData").click(function () {});

	launchIntervalGetStatus();
});

var intervalGetStatus = false;

function getStatus(){
	$.ajax({
        url: 'get_status.php',
        type: 'post',
        //data: data+"&pg="+pg,
        success: function(result){
            //alert(result);
        	$(".loader_gif").css("display","none");
        	//console.log(result);
        	var currentdate = new Date();
        	var min = currentdate.getMinutes()+"", sec = currentdate.getSeconds()+"", hour = currentdate.getHours()+"";
        	var month = (currentdate.getMonth()+1)+""
        	if (month.length < 2) month = '0' + month;
        	
        	var datetime = "Last Sync: " + currentdate.getDate() + "/"
            + month  + "/" 
            + currentdate.getFullYear() + " " ; 
//            + currentdate.getHours() + ":"  
//            + currentdate.getMinutes() + ":" 
//            + currentdate.getSeconds();
        	if (hour.length < 2) hour = '0' + hour;
        	if (min.length < 2) min = '0' + min;
        	if (sec.length < 2) sec = '0' + sec;
        	
        	datetime += hour+":"+min+":"+sec;
        	$(".refresh_status").html(datetime+" &nbsp;");
        	if(result=="invalid_login"){
        		$(".refresh_status").html("Invalid login");
        		return;
        	}
            const obj = JSON.parse(result);
            // $(".main_body").html(obj.conected);
            //console.log("status connected "+obj[0].connected);
            //console.log(obj);
            //parseClientStatus(obj);
            for(var i = 0; i<obj.length; i++){
            	$("#"+obj[i].id).html(obj[i].data);
            }
//            if(obj.length==2){
//                //console.log(obj[1]);
//                if(obj[1].head=="stop_log"){
//                    $("#label_log_msg").html( obj[1].msg+" <a target='_blank' href='download_file?file=log/"+obj[1].file_name+"'>"+obj[1].file_name+"</a>");
//                    $(".clear_log_msg").css("display","block");
//                }else if(obj[1].head=="download_success" || obj[1].head=="download_error"){
//                    $("#label_download_msg").html( obj[1].msg+" <a target='_blank' href='download_file?file=data/"+obj[1].file_name+"'>"+obj[1].file_name+"</a>");
//                    $(".clear_download_msg").css("display","block");
//                    $(".loading_download").css("display","none");
//                }
//            }
//            if(obj.length==3){
//                if(obj[2].msg=="download_success" || obj[2].msg=="download_error"){
//                    $("#label_download_msg").html( obj[2].msg+" <a target='_blank' href='download_file?file=data/"+obj[2].file_name+"'>"+obj[2].file_name+"</a>");
//                    $(".clear_download_msg").css("display","block");
//                    $(".loading_download").css("display","none");
//                }
//            }
        }, error:function(data, status, headers, config) {
            //timerCurrentStatus  = setInterval(getStatus, 2000);
            launchIntervalGetStatus();
            $(".refresh_status").html("Error while connecting server");
            console.log("call admin ");
            $('.label_connection').css('display','none');
            $('.label_connection_msg').css('display','block');
            $('.label_connection_msg').html('Error while connecting Server');
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        }
    });
}
function launchIntervalGetStatus() {
    if(false != intervalGetStatus) return;
    console.log("get status");
    intervalGetStatus = setInterval(getStatus, 5000);
}

function clearIntervalGetStatus() {
    if(false == intervalGetStatus) return
    clearInterval(intervalGetStatus)
    intervalGetStatus = false
}
 
 