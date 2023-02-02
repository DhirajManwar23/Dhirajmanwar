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

$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueRejectedByCompany= $commonfunction->getSettingValue("RejectedByCompany");

$ip_address= $commonfunction->getIPAddress();
$str=$_POST['str'];
$reason=$_POST['reason'];
$reasontext=$_POST['reasontext'];
$type=$_POST['type'];
//print_r($str);
if($type=="check"){
	$arrStr = array();
	$arrStr = explode(",",$str);
	$valTotalQuantity=0.00;

	for($i=0; $i<count($arrStr); $i++)
	{ 
		
		$arrStrInner = array();
		$arrStrInner = explode("/",$arrStr[$i]);	
		$EPR_id=$arrStrInner[0];
		$updateQry="UPDATE tw_temp SET status='".$settingValueRejectedStatus."', reason='".$reason."' , reasontext='".$reasontext."',rejected_id='".$employee_id."',rejected_by='".$settingValueRejectedByCompany."' where id='".$EPR_id."'";
		$retVal4 = $sign->FunctionQuery($updateQry);

	}
	echo "Success";
}
else{
		$updateQry="UPDATE tw_temp SET status='".$settingValueRejectedStatus."', reason='".$reason."' , reasontext='".$reasontext."',rejected_id='".$employee_id."',rejected_by='".$settingValueRejectedByCompany."' where id='".$str."'";
		$retVal4 = $sign->FunctionQuery($updateQry);
		if($retVal4=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
}
 
?>