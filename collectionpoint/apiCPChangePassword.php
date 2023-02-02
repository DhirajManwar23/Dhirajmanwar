<?php
session_start();
	
	// Include class definition
	require "function.php";
	require "commonFunctions.php";
	include("mailFunction.php");
	$commonfunction=new Common();
	$sign=new Signup();
	$cp_id = $_SESSION["cp_id"];
    $OldPassword = md5($_POST['oldpswd']);
	$NewPassword = md5($_POST['newpswd']);
	$username = md5($_SESSION["cp_id"]);
	
    $settingValueMailPath = $commonfunction->getSettingValue("MailPath");
	
	$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
	$settingValuePemail=$sign->escapeString($settingValuePemail);
	
	$qry="SELECT COUNT(*) as cnt from tw_collection_point_login WHERE password ='".$OldPassword."' and id='".$cp_id."' ";
	$retVal = $sign->Select($qry);
	if($retVal==1){
          
		$qry1=" UPDATE tw_collection_point_login SET password ='".$NewPassword."' Where id= '".$cp_id."' ";
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success")
		{
			$qry2="select email from tw_collection_point_master where id='".$cp_id."' and email='".$settingValuePemail."'";
			$to = $sign->SelectF($qry2,"email");
			$qry3="SELECT collection_point_name FROM `tw_collection_point_master` WHERE id= '".$cp_id."';";
			$replaceLink=$sign->SelectF($qry3,"collection_point_name");
			
			$mailobj=new twMail();
			$subject = "Password Changed";
			
			$myfile = fopen($settingValueMailPath."pgCPChangePassword.html", "r");
			$message = fread($myfile,filesize($settingValueMailPath."pgCPChangePassword.html"));
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