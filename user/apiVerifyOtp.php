<?php
$email=$_POST["value"];
$id=$_POST["id"];
$contact=$_POST["contact"];
$otp=$_POST["otp"];
session_start();
$company_id = $_SESSION["company_id"];
$User_type= $_POST["User_type"];
include_once("function.php");
include_once("commonFunctions.php");
$commonfunction=new Common();
$sign=new Signup();
$settingValueVerificationStatus= $commonfunction->getSettingValue("Verified Status");
$settingValuePrimaryContact= $commonfunction->getSettingValue("Primary Contact");
$settingValueVerificationStatus=$sign->escapeString($settingValueVerificationStatus);
$qry3="SELECT otp FROM `tw_otp_verify` where user_id='".$company_id."' AND 	contact_number='".$contact."' ORDER BY id DESC ";
 $otpfinal= $sign->SelectF($qry3,"otp");
	if($otp==$otpfinal)
	{
		
		if($User_type=="Company"){
			$qry="update tw_company_contact SET status='".$settingValueVerificationStatus."' where value='".$contact."' ";
			 
		}
		else{
			$qry="update tw_employee_contact SET status='".$settingValueVerificationStatus."' where value='".$contact."' ";
		}
	   
		$retVal1 = $sign->FunctionQuery($qry);   
		if($retVal1=="Success"){
			$qry2="update tw_otp_verify SET status='".$settingValueVerificationStatus."' where contact_number='".$contact."' AND user_id='".$company_id."' ORDER BY id DESC  "; 
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