<?php
session_start();
include("function.php");
include_once("commonFunctions.php");
$commonfunction=new Common();
$employee_id= $_SESSION["employee_id"];
$email=$_POST["value"];
$id=$_POST["id"];
$contact=$_POST["contact"];
$otp=$_POST["otp"];

$valContact_field= $_POST["valContact_field"];
$User_type= "Employee";

$settingValueVerificationStatus= $commonfunction->getSettingValue("Verified Status");
$settingValuePrimaryContact= $commonfunction->getSettingValue("Primary Contact");
$sign=new Signup(); 
$qry3="SELECT otp FROM `tw_otp_verify` where user_id='".$employee_id."' AND contact_number='".$contact."'  ORDER BY id  DESC";
$otpfinal= $sign->SelectF($qry3,"otp");
if($otp==$otpfinal)
{
	if($User_type=="Employee"){
		   $qry="update tw_employee_contact SET status='".$settingValueVerificationStatus."' where value='".$contact."' ";
		
	}
	else{
		$qry="update tw_company_contact SET status='".$settingValueVerificationStatus."' where value='".$contact."' and contact_field='".$settingValuePrimaryContact."'";
	}
   
	$retVal1 = $sign->FunctionQuery($qry);  
	if($retVal1=="Success"){
		$qry2="update tw_otp_verify SET status='".$settingValueVerificationStatus."' where contact_number='".$contact."' "; 
		$retVal2 = $sign->FunctionQuery($qry2);
		if($retVal2=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
		
	}
	else{
		echo "error";
	}
	
}
else{
	echo "error";
}
?>