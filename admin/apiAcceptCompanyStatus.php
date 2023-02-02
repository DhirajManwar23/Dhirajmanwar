<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "mailFunction.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$query=$_POST["valquery"];
$type=$_POST['typeid'];
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath=$commonfunction->getSettingValue("MailPath");
$settingValueRejectedstatus=$commonfunction->getSettingValue("Rejected status");
$settingValueRejectedstatus=$sign->escapeString($settingValueRejectedstatus);

if($type=="Company"){
$status=$_POST["status"];
$id=$_POST["id"];
$CompanyData="select CompanyName,Status as CompanyStatus from tw_company_details where id='".$id."'";
$retValCompanyData = $sign->FunctionJSON($CompanyData);
$decodedJSON = json_decode($retValCompanyData);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$CompanyStatus = $decodedJSON->response[1]->CompanyStatus;
$retVal1 = $sign->FunctionQuery($query);
if($status==$settingValueRejectedstatus){
	$SelectReason="select reason from tw_company_details where status='".$settingValueRejectedstatus."'";
	$RejectedReason= $sign->SelectF($SelectReason,"reason");
}

$GetEmail="select value from  tw_company_contact where company_id='".$id."' AND contact_field='".$settingValuePemail."'";
$Email= $sign->SelectF($GetEmail,"value");

$GetStatus="select verification_status from tw_verification_status_master where id='".$status."'";
$StatusValue= $sign->SelectF($GetStatus,"verification_status");


	if($status==$CompanyStatus){
		echo "Exist";
	}
	else if($retVal1=="Success"){
		$mailobj=new twMail();
		$subject = "Verification Result";
		$myfile = fopen($settingValueMailPath."pgStatusUpdate.html", "r");
		$message = fread($myfile,filesize($settingValueMailPath."pgStatusUpdate.html"));
		
			if($status==$settingValueRejectedstatus){
				$message1 = str_replace("__NAME__",$CompanyName,$message);
				$message2 = str_replace("_STATUSVALUE_",$StatusValue,$message1);
				$message3 = str_replace("_REJECTEDREASON_",$RejectedReason,$message2);
				
				fclose($myfile);
				$mail_response = $mailobj->Mailsend($Email,$subject,$message3);
			
			}
			else{
				$message1 = str_replace("__NAME__",$CompanyName,$message);
				$message2 = str_replace("_STATUSVALUE_",$StatusValue,$message1);
				$message3 = str_replace("_REJECTEDREASON_",'',$message2);
				fclose($myfile);
				$mail_response = $mailobj->Mailsend($Email,$subject,$message3);
				
			}
		
		
		
		
		echo "Success";
		
		
	}
	else{
		echo "error";
	}
}
else{
	$retVal1 = $sign->FunctionQuery($query);
	if($retVal1=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
}	
?>


