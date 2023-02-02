<?php
session_start();
	
	// Include class definition
	require "function.php";
	require "commonFunctions.php";
	include("mailFunction.php");
	$commonfunction=new Common();
	$sign=new Signup();

    $OldPassword = md5($_POST['oldpswd']);
	$NewPassword = md5($_POST['newpswd']);
	$Username = md5($_SESSION["companyusername"]);
	$company_id = $_SESSION["company_id"];
    $settingValueMailPath = $commonfunction->getSettingValue("MailPath");

	
	$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
	$settingValuePemail=$sign->escapeString($settingValuePemail);
	
	$qry="SELECT COUNT(*) as cnt from tw_company_login WHERE Password ='".$OldPassword."' and Username='".$Username."' ";
	$retVal = $sign->Select($qry);
	if($retVal==1){
          
		$qry1=" UPDATE tw_company_login SET Password ='".$NewPassword."' Where Username= '".$Username."' ";
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success")
		{
			$qry2="select value from tw_company_contact where company_id='".$company_id."' and contact_field='".$settingValuePemail."'";
			$to = $sign->SelectF($qry2,"value");
			$qry3="SELECT CompanyName FROM `tw_company_details` WHERE id= '".$company_id."';";
			$replaceLink=$sign->SelectF($qry3,"CompanyName");
			
			$mailobj=new twMail();
			$subject = "Password Changed";
			
			$myfile = fopen($settingValueMailPath."pgCompanyChangePassword.html", "r");
			$message = fread($myfile,filesize($settingValueMailPath."pgCompanyChangePassword.html"));
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