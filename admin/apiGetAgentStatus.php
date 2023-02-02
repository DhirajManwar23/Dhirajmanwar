<?php
include_once "function.php";
$sign=new Signup();
include_once "mailFunction.php";
include_once "commonFunctions.php";
$commonfunction=new Common();

$requested_id=$_POST['id'];
$Reason=$_POST['Reason'];
$txtAgentStatus=$_POST['txtAgentStatus'];
$valcreated_by=$_POST['valcreated_by'];
$valcreated_on=$_POST['valcreated_on'];
$valcreated_ip=$_POST['valcreated_ip'];

$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath=$commonfunction->getSettingValue("MailPath");
$settingValueRejectedstatus=$commonfunction->getSettingValue("Rejected status");
$settingValueRejectedstatus=$sign->escapeString($settingValueRejectedstatus);

	$AgentData="select agent_name,email from tw_agent_details where id='".$requested_id."'";
	$retValAgentData = $sign->FunctionJSON($AgentData);
	$decodedJSON = json_decode($retValAgentData);
	$agent_name = $decodedJSON->response[0]->agent_name;
	$Email = $decodedJSON->response[1]->email;
	
	$qry="select status as AgentStatus from tw_agent_details where id= '".$requested_id."'";
	$AgentStatus=$sign->SelectF($qry,"AgentStatus");

	if($AgentStatus==$txtAgentStatus){
		echo "Exist";
	}
	else{

	$qryUpdateAgent="Update tw_agent_details set status='".$txtAgentStatus."',reason='".$Reason."',modified_by='".$valcreated_by."',modified_on='".$valcreated_on."',modified_ip='".$valcreated_ip."' where id = '".$requested_id."'";
	$retVal1UpdateAgent = $sign->FunctionQuery($qryUpdateAgent);
	
	$GetStatus="select verification_status from tw_verification_status_master where id='".$txtAgentStatus."'";
	$StatusValue= $sign->SelectF($GetStatus,"verification_status");
	
	if($txtAgentStatus==$settingValueRejectedstatus){
	$SelectReason="select reason from tw_agent_details where status='".$settingValueRejectedstatus."' and id='".$requested_id."'";
	$RejectedReason= $sign->SelectF($SelectReason,"reason");
	}
	
	
		if($retVal1UpdateAgent="Success"){
			if($Email!=""){
					
				if($txtAgentStatus==$settingValueRejectedstatus){
					$mailobj=new twMail();
					$subject = "Verification Result";
					$myfile = fopen($settingValueMailPath."pgStatusUpdate.html", "r");
					$message = fread($myfile,filesize($settingValueMailPath."pgStatusUpdate.html"));
					$message1 = str_replace("__NAME__",$agent_name,$message);
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
					$message1 = str_replace("__NAME__",$agent_name,$message);
					$message2 = str_replace("_STATUSVALUE_",$StatusValue,$message1);
					$message3 = str_replace("_REJECTEDREASON_",'',$message2);
					fclose($myfile);
					$mail_response = $mailobj->Mailsend($Email,$subject,$message3);
				
				}
				echo "Success";
		
			}
			else{
				$qryUpdateAgent="Update tw_agent_details set status='".$txtAgentStatus."',reason='".$Reason."',modified_by='".$valcreated_by."',modified_on='".$valcreated_on."',modified_ip='".$valcreated_ip."' where id = '".$requested_id."'";
				$retVal1UpdateAgent = $sign->FunctionQuery($qryUpdateAgent);
				echo "Success";
			}
		}
		
		else{
			echo "Error";
		};
		}
	
	



?>