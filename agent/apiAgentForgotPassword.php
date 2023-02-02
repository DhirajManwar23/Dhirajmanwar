<?php
session_start();

//$email = md5($unenc_email);
$token=time();

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
	// Include class definition
include_once "function.php";
include_once "mailFunction.php";
include_once "commonFunctions.php";
//$agent_id = $_SESSION["agent_id"];
$unenc_email = $_POST["email"];
$commonfunction=new Common();
$sign=new Signup();
$ip_address= $commonfunction->getIPAddress();
//$enc_email=$unenc_email;
$ENCRY_email=urlencode($unenc_email);
$enc_token=$commonfunction->CommonEnc($token);
$settingValueDocumentStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueDocumentUserUrl= $commonfunction->getSettingValue("AgentUrl");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
//$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");

//---------------------------------------------------------
/* $qryselemail="SELECT count(*) as cnt from tw_agent_details where email='".$unenc_email."'";
$valueselemail = $sign->SelectF($qryselemail,"email");

if($valueselemail=$unenc_email){ */
	

$qry2="SELECT id FROM tw_agent_details WHERE email='".$unenc_email."'";
$sign=new Signup();
$retVal2= $sign->SelectF($qry2,"id");
$retVal2; 

$qry="Select count(*) as cnt from tw_agent_details where email='".$unenc_email."'";
$retVal = $sign->Select($qry);
if($retVal>0)
{
   $qry1="insert into tw_agent_reset_password (email,token,status,requested_by,requested_on,requested_ip) values('".$unenc_email."','".$token."','pending','".$retVal2."','".$date."','".$ip_address."')";
   $retVal1 = $sign->FunctionQuery($qry1);	
   
		$email = md5($unenc_email);
		$enc_email=$commonfunction->CommonEnc($unenc_email);
		$ENCRY_email=urlencode($enc_email);
		 if($retVal1=="Success"){
			 $u1="";
			 $v2="";
			 $mailobj=new twMail();
			 $subject = "Reset password";
			 
			 $qry3="select agent_name from tw_agent_details where id='".$retVal2."' ";
			 $retVal3= $sign->SelectF($qry3,"agent_name");
			 $replaceLink= $settingValueDocumentUserUrl."pgAgentResetPassword.php?u1=".$ENCRY_email."&v2=".$enc_token;
			 $myfile = fopen($settingValueMailPath."pgAgentForgotPassword.html", "r");
			 
			 "<h1>".$settingValueDocumentUserUrl.".pgAgentResetPassword.php?u1=".$ENCRY_email."&&v2=".$enc_token."</h1>";
			  $myfile = fopen($settingValueMailPath."pgAgentForgotPassword.html", "r");

			 $message1 = fread($myfile,filesize($settingValueMailPath."pgAgentForgotPassword.html"));
			 $message2 = str_replace("_Employee_",$retVal3,$message1);
			 $message = str_replace("__FORGOTPASSWORDLINKPLACEHOLDER__",$replaceLink,$message2);
			 fclose($myfile);
			 $mail_response = $mailobj->Mailsend($unenc_email,$subject,$message);
			  echo "Success";
		 }
} 		
else{
	echo "NoRecord";
}
/* }
else{
	echo "NoRecord";
}  */

?>