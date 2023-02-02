<?php
include_once "function.php";
$sign=new Signup();
include_once "mailFunction.php";
include_once "commonFunctions.php";
$commonfunction=new Common();

$requested_id=$_POST['id'];
$Reason=$_POST['Reason'];
$txtCPStatus=$_POST['txtCPStatus'];
$valcreated_by=$_POST['valcreated_by'];
$valcreated_on=$_POST['valcreated_on'];
$valcreated_ip=$_POST['valcreated_ip'];

$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath=$commonfunction->getSettingValue("MailPath");
$settingValueRejectedstatus=$commonfunction->getSettingValue("Rejected status");
$settingValueRejectedstatus=$sign->escapeString($settingValueRejectedstatus);

$CPData="select collection_point_name,email from tw_collection_point_master where id='".$requested_id."'";
//$Email= $sign->SelectF($GetEmail,"email");
$retValCPData = $sign->FunctionJSON($CPData);
$decodedJSON = json_decode($retValCPData);
$collection_point_name = $decodedJSON->response[0]->collection_point_name;
$Email = $decodedJSON->response[1]->email;
//$reason = $decodedJSON->response[2]->reason;


$qry="select status as CPStatus from tw_collection_point_master where id= '".$requested_id."'";
$CPStatus=$sign->SelectF($qry,"CPStatus");

	if($CPStatus==$txtCPStatus){
		echo "Exist";
	}
	
	else{

	$qryUpdateCP="Update tw_collection_point_master set status='".$txtCPStatus."',reason='".$Reason."',modified_by='".$valcreated_by."',modified_on='".$valcreated_on."',modified_ip='".$valcreated_ip."' where id = '".$requested_id."'";
	$retVal1UpdateCP = $sign->FunctionQuery($qryUpdateCP);
	
	$GetStatus="select verification_status from tw_verification_status_master where id='".$txtCPStatus."'";
	$StatusValue= $sign->SelectF($GetStatus,"verification_status");
	
	if($txtCPStatus==$settingValueRejectedstatus){
	$SelectReason="select reason from tw_collection_point_master where status='".$settingValueRejectedstatus."' and id='".$requested_id."'";
	$RejectedReason= $sign->SelectF($SelectReason,"reason");
	}
	
	
		if($retVal1UpdateCP="Success"){
			
			if($Email!=""){
					
				if($txtCPStatus==$settingValueRejectedstatus){
					$mailobj=new twMail();
					$subject = "Verification Result";
					$myfile = fopen($settingValueMailPath."pgStatusUpdate.html", "r");
					$message = fread($myfile,filesize($settingValueMailPath."pgStatusUpdate.html"));
					$message1 = str_replace("__NAME__",$collection_point_name,$message);
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
					$message1 = str_replace("__NAME__",$collection_point_name,$message);
					$message2 = str_replace("_STATUSVALUE_",$StatusValue,$message1);
					$message3 = str_replace("_REJECTEDREASON_",'',$message2);
					fclose($myfile);
					$mail_response = $mailobj->Mailsend($Email,$subject,$message3);
				
				}
				echo "Success";
		
			}
			else{
				$qryUpdateCP="Update tw_collection_point_master set status='".$txtCPStatus."',reason='".$Reason."',modified_by='".$valcreated_by."',modified_on='".$valcreated_on."',modified_ip='".$valcreated_ip."' where id = '".$requested_id."'";
				$retVal1UpdateCP = $sign->FunctionQuery($qryUpdateCP);
				echo "Success";
			}
		}
		
		else{
			echo "Error";
		}
	}
	
	



?>