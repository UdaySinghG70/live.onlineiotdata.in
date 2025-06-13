$(document).ready(function(){
	
	disableKeyPress(".donttype");
	$("input[name='get_data_datewise']").click(function(){
		var device_id = $('select[name="device_select"]').val();
		var start_date = $('input[name="start_date"]').val();
		var end_date = $('input[name="end_date"]').val();
		
		if (!start_date || !end_date) {
			$('.msg_task').text('Please select both start and end dates');
			return;
		}
		
		$('.msg_task').text('Loading...');
		
		$.ajax({
			url: 'get_data_datewise.php',
			type: 'POST',
			data: {
				device_id: device_id,
				start_date: start_date,
				end_date: end_date,
				pg: 1
			},
			success: function(response) {
				$('.data').html(response);
				$('.msg_task').text('');
			},
			error: function() {
				$('.msg_task').text('Error loading data. Please try again.');
			}
		});
	});
	
	// Handle pagination clicks
	$(document).on('click', '.nav_ctrl.prev_btn', function() {
		var currentPage = parseInt($('input[name="current_page"]').val());
		loadPage(currentPage - 1);
	});
	
	$(document).on('click', '.nav_ctrl.next_btn', function() {
		var currentPage = parseInt($('input[name="current_page"]').val());
		loadPage(currentPage + 1);
	});
	
	$(document).on('click', '.btn_goto_page', function() {
		var page = parseInt($('input[name="go_to_page"]').val());
		loadPage(page);
	});
	
	function loadPage(page) {
		var device_id = $('select[name="device_select"]').val();
		var start_date = $('input[name="start_date"]').val();
		var end_date = $('input[name="end_date"]').val();
		
		$('.msg_task').text('Loading...');
		
		$.ajax({
			url: 'get_data_datewise.php',
			type: 'POST',
			data: {
				device_id: device_id,
				start_date: start_date,
				end_date: end_date,
				pg: page
			},
			success: function(response) {
				$('.data').html(response);
				$('.msg_task').text('');
			},
			error: function() {
				$('.msg_task').text('Error loading data. Please try again.');
			}
		});
	}
});