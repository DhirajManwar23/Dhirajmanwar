<?php 
session_start();
include_once "function.php";	
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$company_id=$_SESSION["company_id"];
$employee_id=$_SESSION["employee_id"];
$ID=$_POST["val"];
$address_id="";
$google_map="";
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueOngoingStatus= $commonfunction->getSettingValue("Ongoing Status");
if($ID=="0"){
	$activeCount="SELECT COUNT(*) as cnt FROM tw_temp tmp WHERE status='".$settingValueOngoingStatus."' AND po_id In (SELECT id FROM tw_po_info where  id IN (SELECT po_id FROM tw_auditor_po_details where auditor_id='".$employee_id."')) ";
	$active = $sign->Select($activeCount);

}
else{
	 
	$activeCount="SELECT COUNT(*) as cnt FROM tw_temp tmp WHERE status='".$settingValueOngoingStatus."' AND po_id In (SELECT id FROM tw_po_info where supplier_id='".$ID."' and id IN (SELECT po_id FROM tw_auditor_po_details where auditor_id='".$employee_id."'))";
	echo $active = $sign->Select($activeCount);
}
?>