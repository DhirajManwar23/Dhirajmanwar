<?php
session_start();
require "function.php";
include("mailFunction.php");
include("commonFunctions.php");
$Name=$_POST['CompanyName'];
$Email=$_POST['CompanyEmail'];
$token=time();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$status="Pending";
$created_by=$_SESSION["company_id"];
$commonfunction=new Common();
$sign=new Signup();
$ip_address= $commonfunction->getIPAddress();
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValueUserUrl = $commonfunction->getSettingValue("UserUrl");

$qry1="select CompanyName from tw_company_details where ID='".$created_by."'";
$retVal1= $sign->SelectF($qry1,"CompanyName");

$qry2="select value from tw_company_contact where value LIKE '%".$Email."%'";
$retVal2= $sign->SelectF($qry2,"value");
if($Email==$retVal2){
	echo "Exist";
}
else{

$qry="insert into tw_company_invite(company_name,company_email,invitation_datetime,token,status,created_by,created_on,created_ip)
values('".$Name."','".$Email."','".$date."','".$token."','".$status."','".$created_by."','".$date."','".$ip_address."')";
$retVal = $sign->FunctionQuery($qry);
if($retVal=="Success"){
	 $u1="";
	 $v2="";
	 $mailobj=new twMail();
	 $subject = "Invitation to register on Trace Waste";
	 $replaceLink= $settingValueUserUrl
	 $myfile = fopen($settingValueMailPath."pgInvitationForCompany.html", "r");

	 $message1 = fread($myfile,filesize($settingValueMailPath."pgInvitationForCompany.html"));
	 $message2 = str_replace("_Company_",$Name,$message1);
	 $message3= str_replace("_COMPANYNAME_",$retVal1,$message2);
	 $message = str_replace("_LINK_",$replaceLink,$message3);
	 fclose($myfile);
	 $mail_response = $mailobj->Mailsend($Email,$subject,$message);
	 echo "Success";
	}
else{
	echo "error"; 
}
}
	


?>