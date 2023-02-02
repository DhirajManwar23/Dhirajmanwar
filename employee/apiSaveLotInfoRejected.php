<?php
session_start();
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$company_id = $_SESSION["company_id"];
$employee_id = $_SESSION["employee_id"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");

$ip_address= $commonfunction->getIPAddress();
$str=$_POST['str'];
$reason=$_POST['reason'];
//print_r($str);
$arrStr = array();
$arrStr = explode(",",$str);
$valTotalQuantity=0.00;

for($i=0; $i<count($arrStr); $i++)
{ 
	
	$arrStrInner = array();
	$arrStrInner = explode("/",$arrStr[$i]);	
	$mix_waste_collection_id=$arrStrInner[0];
	$updateQry="UPDATE tw_mix_waste_collection SET status='".$settingValueRejectedStatus."', reason='".$reason."' where id='".$mix_waste_collection_id."'";
	$retVal4 = $sign->FunctionQuery($updateQry);

}
echo "Success";
 
?>