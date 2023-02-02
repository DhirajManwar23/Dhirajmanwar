<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$tnc_value=$_POST["tnc_value"];
$tnc_for=$_POST["tnc_for"];
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["companyusername"];
$company_id=$_SESSION["company_id"];
	
$qry="Select count(*) as cnt from tw_tnc where company_id='".$company_id."' and tnc_for='".$tnc_for."'";
$retVal = $sign->Select($qry);

if($retVal==0){
	$qry1="insert into tw_tnc(company_id,tnc_for,tnc_value,created_by,created_on,created_ip)values('".$company_id."','".$tnc_for."','".$tnc_value."','".$created_by."','".$date."','".$ip_address."')";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
}
else
{	
	$qry2="Update tw_tnc set tnc_value='".$tnc_value."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where company_id='".$company_id."' and tnc_for='".$tnc_for."'";
	$retVal2 = $sign->FunctionQuery($qry2);
	if($retVal2=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
}
?>
