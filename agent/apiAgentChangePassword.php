<?php
session_start();
	
	// Include class definition
	require "function.php";
	require "commonFunctions.php";
	include("mailFunction.php");
	$commonfunction=new Common();
	$sign=new Signup();
	$agent_id = $_SESSION["agent_id"];
    $OldPassword = md5($_POST['oldpswd']);
	$NewPassword = md5($_POST['newpswd']);
	$username = md5($_SESSION["agent_id"]);
	
    $settingValueMailPath = $commonfunction->getSettingValue("MailPath");
	
	$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
	$settingValuePemail=$sign->escapeString($settingValuePemail);
	
	$qry="SELECT COUNT(*) as cnt from tw_agent_login WHERE password ='".$OldPassword."' and id='".$agent_id."' ";
	$retVal = $sign->Select($qry);
	if($retVal==1){
          
		$qry1=" UPDATE tw_agent_login SET password ='".$NewPassword."' Where id= '".$agent_id."' ";
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success")
		{
			$qry2="select email from tw_agent_details where id='".$agent_id."' and email='".$settingValuePemail."'";
			$to = $sign->SelectF($qry2,"email");
			$qry3="SELECT agent_name FROM tw_agent_details WHERE id= '".$agent_id."';";
			$replaceLink=$sign->SelectF($qry3,"agent_name");
			
			$mailobj=new twMail();
			$subject = "Password Changed";
			
			$myfile = fopen($settingValueMailPath."pgAgentChangePassword.html", "r");
			$message = fread($myfile,filesize($settingValueMailPath."pgAgentChangePassword.html"));
			$message = str_replace("_USERNAMEPLACEHOLDER_",$replaceLink,$message);
			fclose($myfile);
				 
			$mail_response = $mailobj->Mailsend($to,$subject,$message);
			echo "Success";
		}
		else{
			echo "error";
		} 
    }else{               
          echo "Invalid";
    }
  
	
?>