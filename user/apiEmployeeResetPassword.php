<?php
session_start();
include_once "mailFunction.php";
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$employee_id = $_POST['employee_id'];
$Username = md5($_SESSION["companyusername"]);
$company_id = $_SESSION["company_id"];
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#!$%^&*";
$password = substr( str_shuffle( $chars ), 0, 16 );
// Include class definition
	
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePemail=$sign->escapeString($settingValuePemail);
$settingValueDocumentEmployeeUrl= $commonfunction->getSettingValue("EmployeeUrl");
$settingValueMailPath= $commonfunction->getSettingValue("MailPath");
$settingValueDocumentEmployeeUrl=$sign->escapeString($settingValueDocumentEmployeeUrl);

$qry1=" UPDATE tw_employee_login SET forced_password_change ='true' Where employee_id= '".$employee_id."' ";
$retVal1 = $sign->FunctionQuery($qry1);

$qry1=" UPDATE tw_employee_login SET Password ='".md5($password)."' Where employee_id= '".$employee_id."' ";
$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success")
	{
		$qry2="select value from tw_employee_contact where employee_id='".$employee_id."' and contact_field='".$settingValuePemail."'";
		$to = $sign->SelectF($qry2,"value");
		$qry3="SELECT employee_name FROM tw_employee_registration WHERE id= '".$employee_id."';";
		$employeename=$sign->SelectF($qry3,"employee_name");
		
		$replaceLink= $settingValueDocumentEmployeeUrl."pgEmployeeLogIn.php";
		
		$mailobj=new twMail();
		$subject = "Password Reset";
		
		$myfile = fopen($settingValueMailPath."pgCompanyEmployeeResetPassword.html", "r");
		$message = fread($myfile,filesize($settingValueMailPath."pgCompanyEmployeeResetPassword.html"));
		$message1 = str_replace("_Password_",$password,$message);
		$message2 = str_replace("_Employee_",$employeename,$message1);
		$message3 = str_replace("_PATH_",$replaceLink,$message2);
		
		fclose($myfile);
			 
		$mail_response = $mailobj->Mailsend($to,$subject,$message3);
		echo "Success";
	}
	else{
		echo "error";
	}
  
	
?>