<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$requesttype = $_REQUEST["valrequesttype"];

$AuditorName=$sign->escapeString($_POST["AuditorName"]);
$PoId=$sign->escapeString($_POST["valrequestid"]);
$valcreated_by=$sign->escapeString($_POST["valcreated_by"]);
$valcreated_on=$sign->escapeString($_POST["valcreated_on"]);
$valcreated_ip=$sign->escapeString($_POST["valcreated_ip"]);
$employee_role=$sign->escapeString($_POST["employee_role"]);
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueAuditorFlag= $commonfunction->getSettingValue("AuditorFlag");
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

$created_by=$_SESSION["companyusername"];

	$qry="Select count(*) as cnt from tw_auditor_po_details where auditor_id='".$AuditorName."' and po_id = '".$PoId."'";
	$retVal = $sign->Select($qry);
	if($retVal==0){
		
		if($requesttype=="Update"){
			$queryAuditorDetails="Insert into tw_auditor_po_details (auditor_id,po_id,status,created_by,created_on,created_ip) values ('".$AuditorName."','".$PoId."','".$settingValuePendingStatus."','".$valcreated_by."','".$valcreated_on."','".$valcreated_ip."')";
		}
		else{
			
			$queryAuditorDetails="Insert into tw_auditor_po_details (auditor_id,po_id,status,created_by,created_on,created_ip) values ('".$settingValueAuditorFlag."','".$PoId."','".$settingValuePendingStatus."','".$valcreated_by."','".$valcreated_on."','".$valcreated_ip."')";
		}
		 
		$retValAuditorDetails = $sign->FunctionQuery($queryAuditorDetails);
		if($retValAuditorDetails=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}	   
		
		
	}
	
else{
	
	$qry1="Update tw_auditor_po_details set auditor_id='".$AuditorName."',modified_by='".$valcreated_by."',modified_on='".$date."',modified_ip='".$ip_address."' where po_id='".$PoId."' "; 
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}   
		
} 
	
	
		
//}	
?>
