<?php
session_start();
// Include class definition
include_once "function.php";
include_once "mailFunction.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#!$%^&*";
$password = substr( str_shuffle( $chars ), 0, 16 );
$name=$sign->escapeString($_POST["name"]);
$email=$sign->escapeString($_POST["email"]);
$status = $sign->escapeString($_POST["status"]);		
$ip_address= $commonfunction->getIPAddress();
$AdminImagePathVerification= $commonfunction->getSettingValue("AdminImagePathVerification");
$AdminImagePathVerified=$commonfunction->getSettingValue("AdminImagePathVerified");
$settingValuePendingStatus  = $commonfunction->getSettingValue("Pending Status");	
$settingValueAdminUrl = $commonfunction->getSettingValue("AdminUrl ");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValueMailPath=$sign->escapeString($settingValueMailPath);

$role=$_POST["role"];
$admin_id = $_SESSION["admin_id"];
$created_by=$admin_id;

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

$requesttype=$_SESSION["requesttype"];
$requestid=$_SESSION["requestid"];

if($requesttype=="add"){
 $qry="Select count(*) as cnt from tw_sub_admin where email='".$email."'";

$retVal = $sign->Select($qry);
if($retVal>0){
	echo "Exist";
}
else
{	
	$qry1="insert into tw_sub_admin (name,email,role,sub_admin_status,created_by,created_on,created_ip) values('".$name."','".$email."','".$role."','".$settingValuePendingStatus."','".$created_by."','".$date."','".$ip_address."')";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		$query="Select id from tw_sub_admin where email='".$email."'";
		$retVal4 = $sign->SelectF($query,"id");
		
		if($retVal1=="Success"){
		$qry2="insert into tw_admin_login(admin_id,Username,Password,Type,Status) values('".$retVal4."','".md5($email)."','".md5($password)."','Sub Admin','On')";
		$retVal2 = $sign->FunctionQuery($qry2);
		if($retVal2=="Success"){
		$mailobj=new twMail();
		$subject = "Successfully Register";
		
		$myfile = fopen($settingValueMailPath."pgSubAdminRegister.html", "r");

		$message = fread($myfile,filesize($settingValueMailPath."pgSubAdminRegister.html"));

		$message1 = str_replace("_ID_",$email,$message);
		$message2 = str_replace("_PASSWORD_",$password,$message1);
		$message3 = str_replace("_USERNAMEPLACEHOLDER_",$email,$message2);
		$message4 = str_replace("_PATH_",$settingValueAdminUrl,$message3);
		$mail_response = $mailobj->Mailsend($email,$subject,$message4);
		fclose($myfile);
		
		//====
		$file_path = $AdminImagePathVerification."/".$email;
		$file_path1 = $AdminImagePathVerified."/".$email;
			
		if (!file_exists($file_path))/* Check folder exists or not */
		{
			@mkdir($file_path, 0777);/* Create folder by using mkdir function */
		}
		if (!file_exists($file_path1))/* Check folder exists or not */
		{
			@mkdir($file_path1, 0777);/* Create folder by using mkdir function */
		}
			echo "Success";
				
			}
			else{
				echo "error";
			}
			}
			else{
				echo "error";
			}
		}else{
			echo "error";
		}							
}
}	
	else{
	
	 $qry="Select count(*) as cnt from tw_sub_admin where email='".$email."' and id!='".$requestid."' ";


	$retVal = $sign->Select($qry);
	if($retVal>0){
		echo "Exist";
	}
	else
	{	
		 $qry5="Update tw_sub_admin set name='".$name."',email='".$email."',role='".$role."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
		$retVal5 = $sign->FunctionQuery($qry5);
		if($retVal5=="Success"){
			echo "Success";
			 
		}
		else{
			echo "error";
		}	   
	}
	
}	
?>
