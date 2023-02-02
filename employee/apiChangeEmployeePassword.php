<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";
include_once "mailFunction.php";
$commonfunction=new Common();
$sign=new Signup();
	 
    $OldPassword = md5($sign->escapeString($_POST['oldpswd']));
	$NewPassword = md5($sign->escapeString($_POST['newpswd']));

	$Username = md5($sign->escapeString($_SESSION["employeeusername"]));
    $employee_id = $_SESSION["employee_id"];
	$settingValueMailPath = $commonfunction->getSettingValue("MailPath");

	// Include class definition

    $qry="SELECT COUNT(*) as cnt from tw_employee_login WHERE password ='".$OldPassword."' and username='".$Username."' ";
   
    $retVal = $sign->Select($qry);
	if($retVal==1){
          
	$qry1=" UPDATE tw_employee_login SET Password ='".$NewPassword."' , forced_password_change='false' Where username= '".$Username."' ";
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success")
		{
		 
			$to = $_SESSION["employeeusername"];
			$qry3="select employee_name FROM tw_employee_registration where id='".$employee_id ."'";
		    $retVal3 = $sign->SelectF($qry3,'employee_name');
			$mailobj=new twMail();
			$subject = "Password Changed";
			$myfile = fopen($settingValueMailPath."pgCompanyChangePassword.html", "r");
	
			$message1 = fread($myfile,filesize($settingValueMailPath."pgCompanyChangePassword.html"));
			//$message .= str_replace("__FORGOTPASSWORDLINKPLACEHOLDER__",$retVal3,$message);
			$message = str_replace("_USERNAMEPLACEHOLDER_",$retVal3,$message1);
			fclose($myfile);
			
			//
			$mail_response = $mailobj->Mailsend($to,$subject,$message);
			echo "Success";
		}
		else{
			echo "error";
		} 
    }
	else{               
          echo "Invalid";
    }
  
	
?>