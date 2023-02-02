<?php
session_start();
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("d-m-Y h:i:sa");
$company_id = $_SESSION["company_id"];
$employee_id = $_SESSION["employee_id"];

$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$ip_address= $commonfunction->getIPAddress();
$str=$_POST['str'];
$type=$_POST['type'];
$po_id=$_POST['po_id'];
if($type=="check"){
	$arrStr = array();
	$arrStr = explode(",",$str);
	for($i=0; $i<count($arrStr); $i++)
	{ 
		$arrStrInner = array();
		$arrStrInner = explode("/",$arrStr[$i]);
		
		$EPR_id=$arrStrInner[0];
		$updateQry="UPDATE tw_temp SET status='".$settingValueAwaitingStatus."',auditor_id='".$employee_id."',modified_by='".$employee_id."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$EPR_id."' and po_id='".$po_id."'";
		$retVal4 = $sign->FunctionQuery($updateQry);
		if($retVal4!="Success"){
			echo "error";
		}
	}
	
	echo "Success";
}
else{
		$updateQry="UPDATE tw_temp SET status='".$settingValueAwaitingStatus."',auditor_id='".$employee_id."',modified_by='".$employee_id."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$str."' and po_id='".$po_id."'";
		$retVal4 = $sign->FunctionQuery($updateQry);
		if($retVal4=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
		
}

 
?>