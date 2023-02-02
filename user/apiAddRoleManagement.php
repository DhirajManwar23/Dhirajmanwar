<?php
session_start();
include_once  "function.php";
include_once "commonFunctions.php";
$sign=new Signup();

$company_id = $_SESSION["company_id"];
$created_by=$_SESSION["company_id"];
$role_id = $_REQUEST["role_id"];

$commonfunction=new Common();
	$ip_address= $commonfunction->getIPAddress();
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	$qryDel="delete from tw_company_rights_management where company_id='".$company_id."' and role_id='".$role_id."'";
	$retValDel = $sign->FunctionQuery($qryDel,true);
	if (isset($_REQUEST["arrValue"]))
	{
		$cnt=0;
		$arrValue = $_REQUEST["arrValue"];
		$arrType = $_REQUEST["arrType"];
		foreach($arrValue as $value)
		{
			$qry1="insert into tw_company_rights_management(company_id,role_id,rights_id,rights_type,created_by,created_on,created_ip) values('".$company_id."','".$role_id."','".$value."','".$arrType[$cnt]."','".$created_by."','".$date."','".$ip_address."')";
			$retVal1 = $sign->FunctionQuery($qry1,true);
			$cnt=$cnt+1;
		}
		echo "Success";
	}
	else
	{
		echo "Success";
	}
?>
