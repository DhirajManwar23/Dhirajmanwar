<?php
$passsword= MD5($_POST["password"]);
$username= $_POST["username"];
$token= $_POST["token"];
$email= $_POST["email"];

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
//echo $passsword;
//echo $confirmpassword;
  
  include_once "function.php";
  include_once "commonFunctions.php";
  include_once "mailFunction.php";
  $commonfunction=new Common();
  $sign=new Signup();

  $settingValueMailPath = $commonfunction->getSettingValue("MailPath");


	 $settingValueStatus= $commonfunction->getSettingValue("Verified Status");
	 $qry="UPDATE tw_company_login SET Password='".$passsword."' WHERE Username='".$username."' ";
	 $retVal = $sign->FunctionQuery($qry);
	
   
    if($retVal=="Success"){
		$qry2="SELECT company_id FROM tw_company_contact WHERE value='".$email."'";	
	    $retVal2= $sign->SelectF($qry2,"company_id");
		$commonfunction = new Common();
		$ip_address= $commonfunction->getIPAddress();	
		$qry3="UPDATE tw_company_reset_password SET status='".$settingValueStatus."' , reset_by='".$retVal2."',  reset_on='".$date."' ,reset_ip='".$ip_address."' WHERE token='".$token."' AND email='".$email."'";
		$retVal3 = $sign->FunctionQuery($qry3);
		
		$qry3="SELECT CompanyName FROM `tw_company_details` WHERE id= '".$retVal2."';";
		$replaceLink=$sign->SelectF($qry3,"CompanyName");
			
		$mailobj=new twMail();
		$to=$email;
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




?>