<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$invoice_information=$_POST["invoice_information"];

$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["companyusername"];
$company_id=$_SESSION["company_id"];
	
$qry="Select count(*) as cnt from tw_invoice_info where company_id='".$company_id."'";
$retVal = $sign->Select($qry);

if($retVal==0){
	$qry1="insert into tw_invoice_info(company_id,invoice_information,created_by,created_on,created_ip)values('".$company_id."','".$invoice_information."','".$created_by."','".$date."','".$ip_address."')";
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
	$qry1="Update tw_invoice_info set invoice_information='".$invoice_information."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where company_id='".$company_id."'";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
}
?>
