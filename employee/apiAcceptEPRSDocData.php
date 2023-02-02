<?php
session_start();
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
date_default_timezone_set("Asia/Kolkata");
$company_id = $_SESSION["company_id"];
$employee_id = $_SESSION["employee_id"];
$current_date = date('Y-m-d', time());
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$ip_address= $commonfunction->getIPAddress();
$eprappid=$_POST['eprappid'];
$state_id = $_POST["state_id"];
$po_id = $_POST["po_id"];

$StateNameQry="SELECT state_name FROM tw_state_master where id='".$state_id."'";
$StateName= $sign->SelectF($StateNameQry,"state_name");

$DateUpdateQry="UPDATE  tw_temp SET accepted_date='".$current_date."' where po_id='".$po_id."' AND dispatched_state='".$StateName."'";
$DateUpdate=$sign->FunctionQuery($DateUpdateQry);

$qry1="update tw_epr_approval set company_status = '".$settingValueApprovedStatus."',modified_by='".$company_id."',modified_on='".$current_date."',modified_ip='".$ip_address."' where id='".$eprappid."' ";
$retVal1 = $sign->FunctionQuery($qry1);
if($retVal1=="Success" && $DateUpdate=="Success"  ){
	echo "Success";
}
else {
	echo "error";
}
 
?>