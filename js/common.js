function disableKeyPress1(obj){

	$(obj).bind('keypress', 
		function(e) {
			e.preventDefault();
		}
	);	
}
function disableKeyPress(obj){

	$(obj).bind('keypress', 
		function(e) {
			e.preventDefault();
		}
	);	
}