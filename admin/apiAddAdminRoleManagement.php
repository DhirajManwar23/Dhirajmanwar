<?php
session_start();
include_once("function.php");
include_once("commonFunctions.php");
$sign=new Signup();
$admin_id = $_SESSION["admin_id"];
$created_by=$admin_id;
$role_id = $_REQUEST["role_id"];
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

	$qryDel="delete from tw_admin_rights_management where role_id='".$role_id."'";
	$retValDel = $sign->FunctionQuery($qryDel,true);
	if (isset($_REQUEST["arrValue"]))
	{
		$cnt=0;
		$arrValue = $_REQUEST["arrValue"];
		$arrType = $_REQUEST["arrType"];
		foreach($arrValue as $value)
		{
			$qry1="insert into tw_admin_rights_management(admin_id,role_id,rights_id,rights_type,created_by,created_on,created_ip) values('".$admin_id."','".$role_id."','".$value."','".$arrType[$cnt]."','".$created_by."','".$date."','".$ip_address."')";
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
