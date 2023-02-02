<?php
session_start();
	include_once "mailFunction.php";

	$id = $_POST['id'];
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#!$%^&*";
    $password = substr( str_shuffle($chars ), 0, 16 );
	// Include class definition
	include_once "function.php";
	include_once "commonFunctions.php";
	$commonfunction=new Common();
	$sign=new Signup();
	$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
	$settingValueMailPath = $commonfunction->getSettingValue("MailPath ");
	
	$qryselemail="SELECT email FROM tw_agent_details where id='".$id."';";
	$valueselemail = $sign->SelectF($qryselemail,"email");
	if($valueselemail!=""){
	
		$qry1=" UPDATE tw_agent_login SET Password ='".md5($password)."' Where agent_id= '".$id."' ";
		$retVal1 = $sign->FunctionQuery($qry1);

		if($retVal1=="Success")
		{
			$qry2="select agent_name from tw_agent_details where id='".$id."'";
			$to = $sign->SelectF($qry2,"agent_name");
			$qry3="SELECT email FROM tw_agent_details WHERE id= '".$id."';";
			$replaceLink=$sign->SelectF($qry3,"email"); 			
			$mailobj=new twMail();
			$subject = "Password Reset";
			
			$myfile = fopen($settingValueMailPath."pgAgentResetPassword.html", "r");
			$message = fread($myfile,filesize($settingValueMailPath."pgAgentResetPassword.html"));
			$message1 = str_replace("_Password_",$password,$message);
			$message2 = str_replace("_AgentResetPassword_",$to,$message1);
			
			fclose($myfile);
				 
			$mail_response = $mailobj->Mailsend($replaceLink,$subject,$message2);
			echo "Success";
		}
		else{
			echo "error";
		}
  
	}
	else{
		echo "NoRecord";
	}
?>