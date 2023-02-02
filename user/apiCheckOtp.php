<?php
include_once("function.php");
include_once("mailFunction.php");
include_once("commonFunctions.php");	
$sign=new Signup();
$commonfunction=new Common();
//$otp= $_POST["Otp"];
$otpfinal=(rand(100000,999999));
$email=$_POST["value"];
$user_id=$_POST["id"];
$contact=$_POST["contact"];
$User_type= $_POST["User_type"];
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueExpiredStatus= $commonfunction->getSettingValue("Expired");
$ip_address= $commonfunction->getIPAddress();

$qryusertypeid="SELECT id FROM tw_user_type_master where User_type='".$User_type."'";
$usertypeid= $sign->SelectF($qryusertypeid,"id");

		
$qry3="update tw_otp_verify set status='".$settingValueExpiredStatus."' where contact_number='".$contact."' and status='".$settingValuePendingStatus."'" ;
$retVal3 = $sign->FunctionQuery($qry3);
if($retVal3="Success"){
	$qry1="insert into tw_otp_verify(user_id,contact_number,user_type,status,otp,action,generated_by,generated_on,generated_ip) 
	values('".$user_id."','".$contact."','".$usertypeid."','".$settingValuePendingStatus."','".$otpfinal."','Mobile Number','".$user_id."','".$date."','".$ip_address."') ";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
}
 else{
	echo "error";
}	
	
		 





?>