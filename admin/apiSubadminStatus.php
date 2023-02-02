<?php
include_once "function.php";
$sign=new Signup();
include_once "mailFunction.php";
include_once "commonFunctions.php";
$commonfunction=new Common();

$requested_id=$_POST['id'];
$Reason=$_POST['Reason'];
$txtAdminStatus=$_POST['txtAdminStatus'];
//$valcreated_by=$_POST['valcreated_by'];
$valcreated_on=$_POST['valcreated_on'];
$valcreated_ip=$_POST['valcreated_ip'];

$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath=$commonfunction->getSettingValue("MailPath");
$settingValueRejectedstatus=$commonfunction->getSettingValue("Rejected status");
$settingValueRejectedstatus=$sign->escapeString($settingValueRejectedstatus);

$SubAdminData="select name,email from tw_sub_admin where id='".$requested_id."'";
//$Email= $sign->SelectF($GetEmail,"email");
$retValSubAdminData = $sign->FunctionJSON($SubAdminData);
$decodedJSON = json_decode($retValSubAdminData);
$name = $decodedJSON->response[0]->name;
$Email = $decodedJSON->response[1]->email;
//$reason = $decodedJSON->response[2]->reason;


$qry="select sub_admin_status from tw_sub_admin where id= '".$requested_id."'";
$sub_admin_status=$sign->SelectF($qry,"sub_admin_status");

	if($sub_admin_status==$txtAdminStatus){
		echo "Exist";
	}
	
	else{

	$qryUpdateSubadmin="Update tw_sub_admin set sub_admin_status='".$txtAdminStatus."',reason='".$Reason."',modified_by='".$name."',modified_on='".$valcreated_on."',modified_ip='".$valcreated_ip."' where id = '".$requested_id."'";
	$retVal1UpdateSubadmin = $sign->FunctionQuery($qryUpdateSubadmin);
	
	$GetStatus="select verification_status from tw_verification_status_master where id='".$txtAdminStatus."'";
	$StatusValue= $sign->SelectF($GetStatus,"verification_status");
	
	if($txtAdminStatus==$settingValueRejectedstatus){
	$SelectReason="select reason from tw_sub_admin where sub_admin_status='".$settingValueRejectedstatus."' and id='".$requested_id."'";
	$RejectedReason= $sign->SelectF($SelectReason,"reason");
	}
	
	
		if($retVal1UpdateSubadmin="Success"){
			
			if($Email!=""){
					
				if($txtAdminStatus==$settingValueRejectedstatus){
					$mailobj=new twMail();
					$subject = "Verification Result";
					$myfile = fopen($settingValueMailPath."pgStatusUpdate.html", "r");
					$message = fread($myfile,filesize($settingValueMailPath."pgStatusUpdate.html"));
					$message1 = str_replace("__NAME__",$name,$message);
					$message2 = str_replace("_STATUSVALUE_",$StatusValue,$message1);
					$message3 = str_replace("_REJECTEDREASON_",$RejectedReason,$message2);
					
					fclose($myfile);
					$mail_response = $mailobj->Mailsend($Email,$subject,$message3);
			
				}
				else{
					$mailobj=new twMail();
					$subject = "Verification Result";
					$myfile = fopen($settingValueMailPath."pgStatusUpdate.html", "r");
					$message = fread($myfile,filesize($settingValueMailPath."pgStatusUpdate.html"));
					$message1 = str_replace("__NAME__",$name,$message);
					$message2 = str_replace("_STATUSVALUE_",$StatusValue,$message1);
					$message3 = str_replace("_REJECTEDREASON_",'',$message2);
					fclose($myfile);
					$mail_response = $mailobj->Mailsend($Email,$subject,$message3);
				
				}
				echo "Success";
		
			}
			else{
				$qryUpdateSubadmin="Update tw_sub_admin set sub_admin_status='".$txtAdminStatus."',reason='".$Reason."',modified_by='".$name."',modified_on='".$valcreated_on."',modified_ip='".$valcreated_ip."' where id = '".$requested_id."'";
				$retVal1UpdateSubadmin = $sign->FunctionQuery($qryUpdateSubadmin);
				echo "Success";
			}
		}
		
		else{
			echo "Error";
		}
	}
	
	



?>