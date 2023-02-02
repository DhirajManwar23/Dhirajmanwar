<?php
include("function.php");
include("mailFunction.php");
include("commonFunctions.php");	
$commonfunction=new Common();

$otpfinal= (rand(100000,999999));
$email=$_POST["value"];
$id=$_POST["id"];
$contact=$_POST["idval"];

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");

$ip_address= $commonfunction->getIPAddress();
$qry="insert into tw_otp_verify(user_id,contact_number,user_type,status,otp,action,generated_by,generated_on,generated_ip) 
values('".$id."','".$contact."','Employee','".$settingValuePendingStatus."','".$otpfinal."','otp verification','".$id."','".$date."','".$ip_address."') ";
$sign=new Signup();
$retVal1 = $sign->FunctionQuery($qry);	
	 
if($retVal1="Success"){
	echo "Success";
}
else{
	echo "error";
}	

?>