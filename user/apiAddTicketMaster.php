<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();

$subject=$sign->escapeString($_POST["subject"]);
$description=$sign->escapeString($_POST["description"]);
$ticket_attachment=$_POST["ticket_attachment"];
$company_id=$_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
date_default_timezone_set("Asia/Kolkata");
$date=date("d-m-Y h:i:sa");
$user=$_SESSION["companyusername"];

$qry1="insert into tw_ticket_system (company_id,subject,description,ticket_attachment,status,created_by,created_on,created_ip,modified_by,modified_on,modified_ip) values('".$company_id."','".$subject."','".$description."','".$ticket_attachment."','".$settingValuePendingStatus."','".$user."','".$date."','".$ip_address."','".$user."','".$date."','".$ip_address."')";
$retVal1 = $sign->FunctionQuery($qry1);
if($retVal1=="Success"){
	echo "Success";
}
else{
	echo "error";
}
?>
