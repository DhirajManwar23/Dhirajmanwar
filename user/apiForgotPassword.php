<?php
  session_start();
  
  $unenc_email = $_POST["email"];
  $email = md5($unenc_email);
  $token=time();
  
  
 	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	
		// Include class definition
	include_once "function.php";
	include_once "mailFunction.php";
	include_once "commonFunctions.php";
	
	
	$commonfunction=new Common();
	$sign=new Signup();
    $ip_address= $commonfunction->getIPAddress();
	$enc_email=$commonfunction->CommonEnc($unenc_email);
	$ENCRY_email=urlencode($enc_email);
	$enc_token=$commonfunction->CommonEnc($token);
	$settingValueDocumentStatus= $commonfunction->getSettingValue("Pending Status");
	$settingValueDocumentUserUrl= $commonfunction->getSettingValue("UserUrl");
	$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
	//---------------------------------------------------------
	 $qry2="SELECT company_id FROM tw_company_contact WHERE value='".$unenc_email."'";
	 $retVal2= $sign->SelectF($qry2,"company_id");
	//echo $retVal2;
	
	
	$qry="Select count(*) as cnt from tw_company_login where Username='".$email."'";
	$retVal = $sign->Select($qry);
	if($retVal>0)
	{
	   $qry1="insert into tw_company_reset_password (email,token,Status,requested_by,requested_on,requested_ip) values('".$unenc_email."','".$token."','".$settingValueDocumentStatus."','".$retVal2."','".$date."','".$ip_address."')";
		
			$retVal1 = $sign->FunctionQuery($qry1);	
			
			 if($retVal1=="Success"){
				 $u1="";
				 $v2="";
				 $mailobj=new twMail();
				 $subject = "Reset password";
				 
				 $qry3="select CompanyName from tw_company_details where ID='".$retVal2."' ";
				 $retVal3= $sign->SelectF($qry3,"CompanyName");
				 $replaceLink= $settingValueDocumentUserUrl."pgResetPassword.php?u1=".$ENCRY_email."&&v2=".$enc_token;
				 $myfile = fopen($settingValueMailPath."pgCompanyForgotPassword.html", "r");
	
			     $message1 = fread($myfile,filesize($settingValueMailPath."pgCompanyForgotPassword.html"));
				  $message2 = str_replace("_CompanyName_",$retVal3,$message1);
			     $message = str_replace("__FORGOTPASSWORDLINKPLACEHOLDER__",$replaceLink,$message2);
				 fclose($myfile);
				 $mail_response = $mailobj->Mailsend($unenc_email,$subject,$message);
				  echo "Success";
			 }
	} 		
	else{
		echo "Exist";
	} 
	
?>