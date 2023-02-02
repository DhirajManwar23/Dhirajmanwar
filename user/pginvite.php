<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";
include_once "mailFunction.php";
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
$sign=new Signup();
$reciver_id=$_POST['reciver'];
$Company_type= $_POST['companyType'];
$sender_id=$_POST['sender'];
$Name=$_POST['CompanyName'];
$CompanyEmail=$_POST['CompanyEmail'];
$token=time();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["company_id"];
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValuePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");


$qry3="SELECT value FROM tw_company_contact where company_id='".$reciver_id."' and contact_field='".$settingValuePrimaryEmail."'";
$retval3=$sign->selectF($qry3,"value");

$qry2="SELECT CompanyName FROM tw_company_details WHERE ID='".$reciver_id."'";
$retVal2= $sign->SelectF($qry2,"CompanyName");

$qry="SELECT count(*) as cnt FROM tw_company_network where connect_status='".$settingValuePendingStatus."' AND 
sender_id='".$sender_id."' AND receiver_id='".$reciver_id."';";
$retVal = $sign->Select($qry);

if($retVal==0){
    $qry1="insert into tw_company_network(sender_id,receiver_id,connect_date,connect_status,created_by,created_on,created_ip) 
	values('".$sender_id."','".$reciver_id."','".$date."','".$settingValuePendingStatus."','".$created_by."','".$date."','".$ip_address."') ";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
	  $mailobj=new twMail();
	  $subject = "";
	  $myfile = fopen($settingValueMailPath."pgInvitationForCompany.html", "r");
      $message = fread($myfile,filesize($settingValueMailPath."pgInvitationForCompany.html"));
      $message1 = str_replace("_Company_",$retVal2,$message);
	  $message2 = str_replace("_COMPANYNAME_",$Company_type,$message1);
	  fclose($myfile);						
      $mail_response = $mailobj->Mailsend($retval3,$subject,$message2);
	  
      if($mail_response=="success")
	  {
		  echo "Success";
		}
		else{
			  echo "error";
		  }	  
    	
		}
}
else{
	echo "Exist";
}
?>