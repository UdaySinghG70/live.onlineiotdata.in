$(document).ready(function(){
	
	$("input[name='get_recharge']").click(function(){
		var $button = $(this);
		var device_id=$("select[name='device_id']").val();
		$(".loading_file").css("display","block");
		
		// Disable button and show loading state
		$button.prop('disabled', true).val('Loading...');
		
		$.ajax({
			url: 'get_recharge.php',
			type: 'POST',
			data: "device_id="+device_id,
			success: function(result){
				$(".data").html(result);
				$(".loading_file").css("display","none");
			},
			error: function() {
				$(".data").html("<p style='color: red;'>Error loading recharge history. Please try again.</p>");
			},
			complete: function() {
				// Re-enable button and restore original text
				$button.prop('disabled', false).val('View History');
				$(".loading_file").css("display","none");
			}
		});
	});
	
	
});