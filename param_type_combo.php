<?php 
$row_id=$_REQUEST['row_id'];

echo "<select name='param_type".$row_id."'>"
		."<option value='datetime'>datetime</option>"
		."<option value='alpha'>alpha</option>"
		."<option value='num'>num</option>"
	."</select>";
?>