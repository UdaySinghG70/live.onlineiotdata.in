<?php
// $dt=1565599740+19800;

// echo $tm = date("Y-m-d H:i:s",$dt);

// echo "<br/>";
// $time=strtotime($tm);

// $time=$time+(330*60);
// echo $submit_time=date("Y-m-d H:i:s",$time);

echo dirname(__FILE__).'\model\admindao.php'."<br/>";
include_once dirname(__FILE__).'\model\admindao.php';
$ado=new AdminDao();
