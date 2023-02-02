<?php
session_start();
$passsword= MD5($_POST["password"]);
$username= $_POST["username"];
$unenc_email = $_POST["email"];
$token= $_POST["token"];
//$email= $_POST["email"];

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
//echo $passsword;
//echo $confirmpassword;
  
  include_once "function.php";
  include_once "commonFunctions.php";
  include_once "mailFunction.php";
  $commonfunction=new Common();
  $sign=new Signup();
  
	$queryCPid="Select id from tw_collection_point_master where email='".$unenc_email."'";
	$CPid=$sign->SelectF($queryCPid,'id');
	
	$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
	$queryemail="Select email from tw_collection_point_master where id='".$CPid."'";
	$CollectionPointemail=$sign->SelectF($queryemail,'email');
	
	 $settingValueStatus= $commonfunction->getSettingValue("Verified Status");
	 $qry="UPDATE tw_collection_point_login SET password='".$passsword."' WHERE collection_point_id='".$CPid."' ";
	 $retVal = $sign->FunctionQuery($qry);
	
   
    if($retVal=="Success"){
		$qry2="SELECT id FROM tw_collection_point_master WHERE email='".$CollectionPointemail."'";	
	    $retVal2= $sign->SelectF($qry2,"id");
		$commonfunction = new Common();
		$ip_address= $commonfunction->getIPAddress();	
		$qry3="UPDATE tw_collection_point_reset_password SET status='".$settingValueStatus."' , reset_by='".$retVal2."',  reset_on='".$date."' ,reset_ip='".$ip_address."' WHERE token='".$token."' AND email='".$CollectionPointemail."'";
		$retVal3 = $sign->FunctionQuery($qry3);
		
		$qry3="SELECT collection_point_name FROM `tw_collection_point_master` WHERE id= '".$retVal2."';";
		$replaceLink=$sign->SelectF($qry3,"collection_point_name");
			
		$mailobj=new twMail();
		$to=$CollectionPointemail;
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




?>