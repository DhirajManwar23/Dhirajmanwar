<?php
session_start();
// Include class definition
include_once("function.php");
include_once("commonFunctions.php");
$sign=new Signup();
//$Description=$sign->escapeString($_POST["Description"]);
$Description=$_POST["Description"];
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employeeusername"];
$employee_id=$_SESSION["employee_id"];
		
	$qry1="Update tw_employee_registration set Description='".$Description."' where ID='".$employee_id."' "; 
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
			
			
	
	
	
	
	
?>
