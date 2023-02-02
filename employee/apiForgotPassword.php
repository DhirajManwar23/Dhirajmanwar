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
	$settingValueDocumentUserUrl= $commonfunction->getSettingValue("EmployeeUrl");
	$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
	$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");

	//---------------------------------------------------------
	 $qry2="SELECT employee_id FROM tw_employee_contact WHERE value='".$unenc_email."'";
	 $sign=new Signup();
	 $retVal2= $sign->SelectF($qry2,"employee_id");
	 $retVal2;
	
	
    $qry="Select count(*) as cnt from tw_employee_login where username='".$email."'";
    $retVal = $sign->Select($qry);
	if($retVal>0)
	{
	   $qry1="insert into tw_employee_reset_password (email,token,Status,requested_by,requested_on,requested_ip) values('".$unenc_email."','".$token."','pending','".$retVal2."','".$date."','".$ip_address."')";
		
			$retVal1 = $sign->FunctionQuery($qry1);	
			
			 if($retVal1=="Success"){
				 $u1="";
				 $v2="";
				 $mailobj=new twMail();
				 $subject = "Reset password";
				 
				 $qry3="select employee_name from tw_employee_registration where ID='".$retVal2."' ";
				 $retVal3= $sign->SelectF($qry3,"employee_name");
				 $replaceLink= $settingValueDocumentUserUrl."pgResetPassword.php?u1=".$ENCRY_email."&&v2=".$enc_token;
				 $myfile = fopen($settingValueMailPath."pgEmployeeForgotPassword.html", "r");
				 
				 "<h1>".$settingValueDocumentUserUrl.".pgResetPassword.php?u1=".$enc_email."&&v2=".$enc_token."</h1>";
				  $myfile = fopen($settingValueMailPath."pgEmployeeForgotPassword.html", "r");
	
			     $message1 = fread($myfile,filesize($settingValueMailPath."pgEmployeeForgotPassword.html"));
				 $message2 = str_replace("_Employee_",$retVal3,$message1);
			     $message = str_replace("__FORGOTPASSWORDLINKPLACEHOLDER__",$replaceLink,$message2);
				 fclose($myfile);
				 $mail_response = $mailobj->Mailsend($unenc_email,$subject,$message);
				  echo "Success";
			 }
	} 		
	else{
		echo "error";
	} 
	
?>