<?php
session_start();
include_once("function.php");
include_once("mailFunction.php");
include_once("commonFunctions.php");
$commonfunction=new Common();
$sign=new Signup();

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

$employee_Id=$_SESSION["employee_id"];
$email= $_POST["userVal"];
$Token= $_POST["Token"];
$username= $_POST["username"];
$User_type= $_POST["User_type"];
$action= "Email verification";

$ip_address= $commonfunction->getIPAddress();
$enc_email=$commonfunction->CommonEnc($email);
$enc_token=$commonfunction->CommonEnc($Token);

$settingValueOemail= $commonfunction->getSettingValue("Other Email");
$settingValueOemail=$sign->escapeString($settingValueOemail);
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePemail=$sign->escapeString($settingValuePemail);
$settingValueStatusAwaiting= $commonfunction->getSettingValue("Awaiting Status");
$settingValueStatusAwaiting=$sign->escapeString($settingValueStatusAwaiting);
$settingValueUserUrl= $commonfunction->getSettingValue("UserUrl");
$settingValueEmployeeUrl= $commonfunction->getSettingValue("EmployeeUrl");
$settingValueMailPath= $commonfunction->getSettingValue("MailPath");

$qry="Insert into tw_verify_email(email,token,status,user_type,action,requested_by,requested_on,requested_ip) values('".$email."','".$Token."','".$settingValueStatusAwaiting."','".$User_type."','".$action."','".$enc_email."','".$date."','".$ip_address."') ";
$retVal1 = $sign->FunctionQuery($qry,true);

if($retVal1!=""){
	
	if($User_type=="Company"){
		$qry2="select CompanyName from tw_company_details where ID='".$username."'";
		$retVal2= $sign->SelectF($qry2,"CompanyName");
	}
	else{
		$qry2="select employee_name from tw_employee_registration where id='".$username."'";
		$retVal2= $sign->SelectF($qry2,"employee_name");
		$qry3="Update tw_employee_contact SET status='".$settingValueStatusAwaiting."' Where employee_id='".$employee_Id."' AND (contact_field='".$settingValueOemail."'or contact_field='".$settingValuePemail."' ) ";
		$retVal3=$sign->FunctionQuery($qry3);
	}
	$u1="";
	$v2="";
	$mailobj=new twMail();
	
	$subject = "Verification of your email";
	if($User_type=="Company"){
		$replaceLink= $settingValueUserUrl."pgCheckVerification.php?u1=".urlencode($enc_email)."&&v2=".urlencode($enc_token);
	}
	else{
		$replaceLink= $settingValueEmployeeUrl."pgCheckVerification.php?u1=".urlencode($enc_email)."&&v2=".urlencode($enc_token);
	}
	
	$myfile = fopen($settingValueMailPath."pgverify.html", "r");  
	$message1 = fread($myfile,filesize($settingValueMailPath."pgverify.html"));
	$message2= str_replace("__FORGOTPASSWORDLINKPLACEHOLDER__",$replaceLink,$message1);
	$message= str_replace("__Employee__",$retVal2,$message2);
	$mail_response = $mailobj->Mailsend($email,$subject,$message);
   
	echo "Success";
	
	
}
else{
	echo "error";
} 

?>
