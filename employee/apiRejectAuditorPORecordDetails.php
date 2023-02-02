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
$po_id=$_POST['po_id'];
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueRejectedByAuditor= $commonfunction->getSettingValue("RejectedByAuditor");

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
		$updateQry="UPDATE tw_temp SET status='".$settingValueRejectedStatus."',auditor_id='".$employee_id."', reason='".$reason."' , reasontext='".$reasontext."',rejected_id=".$employee_id.",rejected_by='".$settingValueRejectedByAuditor."' where id='".$EPR_id."' and po_id='".$po_id."'";
		$retVal4 = $sign->FunctionQuery($updateQry);

	}
	echo "Success";
}
else{
		$updateQry="UPDATE tw_temp SET status='".$settingValueRejectedStatus."', reason='".$reason."' ,auditor_id='".$employee_id."', reasontext='".$reasontext."',rejected_id=".$employee_id.",rejected_by='".$settingValueRejectedByAuditor."' where id='".$str."' and po_id='".$po_id."'";
		$retVal4 = $sign->FunctionQuery($updateQry);
		if($retVal4=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
}
 
?>