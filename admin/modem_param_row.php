<?php
session_start();

if(isset($_SESSION['admin_name'])==false){
    echo "Invalid Login";
    header('Location: login.php');
    return;
}

$admin_name=$_SESSION['admin_name'];

include_once '../model/adminlogin_dao.php';
$aldao=new AdminLoginDao();

$adminDetails=$aldao->getAdminByUserName($admin_name);
if($adminDetails==null){
    echo "Invalid Login";
    header('Location: login.php?msg=error&admin_name='.$admin_name);
    return;
}

// $data_types_arr = array
// (
// 		array("datetime","Date Time +5:30",330),
// 		array("alpha","Alpha",0),
// 		array("numeric","Numeric",0),
// 		array("float","Float",0)
// );
include_once 'data_types_arr.php';

$row_id=1;
$param_types= "";
include_once '../model/admindao.php';
$adao=new AdminDao();
$unit="";

// Find the float type and set its unit as default
foreach($data_types_arr as $type) {
    if($type[0] === "float") {
        $unit = $type[3];
        break;
    }
}

echo "<tr class='row_".$row_id."'>"
    ."<td class='text-center'><label class='row_id_lbl row_id_lbl".$row_id."'>".$row_id.".</label></td>"
    ."<td><input type='text' class='form-control param_name' name='param_name".$row_id."' placeholder='Enter parameter name'></td>"
    ."<td><select class='form-control param_type' name='param_type".$row_id."' onchange='changeUnit(this)'>";
        for($i=0; $i<count($data_types_arr); $i++){
            $selected = ($data_types_arr[$i][0] === "float") ? " selected" : "";
            echo "<option value='".$data_types_arr[$i][0]."' data-unit='".$data_types_arr[$i][3]."'".$selected.">".$data_types_arr[$i][1]."</option>";
        }
echo "</select></td>"
    ."<td><input type='text' class='form-control unit' name='unit".$row_id."' value='".$unit."' placeholder='Enter unit'></td>"
    ."<td><input type='text' class='form-control position' name='position".$row_id."' placeholder='Enter position'></td>"
    ."<td class='text-center'>"
    ."<button type='button' class='btn-remove' onclick='removeRow(this)' title='Remove row'>"
    ."<span class='material-icons'>delete_outline</span>"
    ."</button>"
    ."</td>"
    ."<td class='drag-handle text-center'><span class='material-icons' style='cursor:move;'>drag_indicator</span></td>"
    ."</tr>";