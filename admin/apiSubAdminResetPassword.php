<?php
session_start();
	include_once "mailFunction.php";

	$id = $_POST['id'];
	//$Username = md5($_SESSION["username"]);
	//$company_id = $_SESSION["company_id"];
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#!$%^&*";
    $password = substr( str_shuffle( $chars ), 0, 16 );
	// Include class definition
	include_once "function.php";
	include_once "commonFunctions.php";
	$commonfunction=new Common();
	$sign=new Signup();
	$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
	$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
	$settingValueAdminUrl = $commonfunction->getSettingValue("AdminUrl");
	

        /*$qry1=" UPDATE tw_admin_login SET forced_password_change ='true' Where admin_id= '".$id."' ";
		$retVal1 = $sign->FunctionQuery($qry1);*/
		$qry1=" UPDATE tw_admin_login SET Password ='".md5($password)."'Where admin_id= '".$id."' ";
		$retVal1 = $sign->FunctionQuery($qry1);

		if($retVal1=="Success")
		{
			$qry2="select name from tw_sub_admin where id='".$id."'";
			$to = $sign->SelectF($qry2,"name");
			$qry3="SELECT email FROM `tw_sub_admin` WHERE id= '".$id."';";
			$replaceLink=$sign->SelectF($qry3,"email"); 			
			$mailobj=new twMail();
			$subject = "Password Reset";
			
			$myfile = fopen($settingValueMailPath."pgSubAdminResetPassword.html", "r");
			$message = fread($myfile,filesize($settingValueMailPath."pgSubAdminResetPassword.html"));
			$message1 = str_replace("_Password_",$password,$message);
			$message2 = str_replace("_SubAdmin_",$to,$message1);
			$message3 = str_replace("_PATH_",$settingValueAdminUrl,$message2);
			
			fclose($myfile);
				 
			$mail_response = $mailobj->Mailsend($replaceLink,$subject,$message3);
			echo "Success";
		}
		else{
			echo "error";
		}
  
	
?>