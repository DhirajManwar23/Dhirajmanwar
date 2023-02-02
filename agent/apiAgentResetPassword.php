<?php
session_start();
$passsword= MD5($_POST["password"]);
$username= $_POST["username"];
$unenc_email = $_POST["email"];
$token= $_POST["token"];
//$email= $_POST["email"];
//$agent_id = $_SESSION["agent_id"];
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
//echo $passsword;
//echo $confirmpassword;
  
  include_once "function.php";
  include_once "commonFunctions.php";
  include_once "mailFunction.php";
  $commonfunction=new Common();
  $sign=new Signup();
	
	$queryAgentid="Select id from tw_agent_details where email='".$unenc_email."'";
	$Agentid=$sign->SelectF($queryAgentid,'id');
	
	$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
	$queryemail="Select email from tw_agent_details where id='".$Agentid."'";
	$Agentemail=$sign->SelectF($queryemail,'email');
	
	 $settingValueStatus= $commonfunction->getSettingValue("Verified Status");
	 $qry="UPDATE tw_agent_login SET password='".$passsword."' WHERE agent_id='".$Agentid."' ";
	 $retVal = $sign->FunctionQuery($qry);
	
   
    if($retVal=="Success"){
		/* $qry2="SELECT id FROM tw_agent_details WHERE email='".$Agentemail."'";	
	    $retVal2= $sign->SelectF($qry2,"id"); */
		$commonfunction = new Common();
		$ip_address= $commonfunction->getIPAddress();	
		$qry3="UPDATE tw_agent_reset_password SET status='".$settingValueStatus."' , reset_by='".$Agentid."',  reset_on='".$date."' ,reset_ip='".$ip_address."' WHERE token='".$token."' AND email='".$Agentemail."'";
		$retVal3 = $sign->FunctionQuery($qry3);
		
		$qry3="SELECT agent_name FROM tw_agent_details WHERE id= '".$Agentid."';";
		$replaceLink=$sign->SelectF($qry3,"agent_name");
			
		$mailobj=new twMail();
		$to=$Agentemail;
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




?>