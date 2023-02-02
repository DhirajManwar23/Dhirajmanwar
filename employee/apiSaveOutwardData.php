<?php
session_start();
include("function.php");
include("commonFunctions.php");
include("mailFunction.php");

$commonfunction=new Common();
$sign=new Signup();
$materialtype=$_POST['materialtype'];
$quantityvalue=$_POST['quantityvalue'];

$EntryDate=$_POST['EntryDate'];
$Name=$_POST['Name'];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$ip_address= $commonfunction->getIPAddress();
$employee_id = $_SESSION["employee_id"];




echo $qry3="Insert into  tw_outward_data_entry(entry_date,customer_name,quantity,created_on,created_by,created_ip) values ('".$EntryDate."','".$Name."','".$quantityvalue."','".$settingValuePendingStatus."','".$employee_id."','".$date."','".$ip_address."')";
	$retVal3 = $sign->FunctionQuery($qry3); 
if($retVal3=="Success"){
	$valquery = "Success";
}
else{
	$valquery = "error";
}
?>