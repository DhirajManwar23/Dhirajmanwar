<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$id=$_POST["id"];
$reason=$_POST["reason"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employeeusername"];
$employee_id=$_SESSION["employee_id"];
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");

$qry1="Update tw_material_outward set status='".$settingValueRejectedStatus."' ,reason='".$reason."' where id='".$id."' "; 
$retVal1 = $sign->FunctionQuery($qry1);
if($retVal1=="Success"){
	echo "Success";
}
else{
	echo "error";
}
			
			
	
	
	
	
	
?>
