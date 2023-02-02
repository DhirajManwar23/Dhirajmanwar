<?php 
session_start();
include_once "function.php";	
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$company_id=$_SESSION["company_id"];
$ID=$_POST['val'];
$address_id="";
$google_map="";
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueRejectedByCompany= $commonfunction->getSettingValue("RejectedByCompany");
$settingValueRejectedByAuditor= $commonfunction->getSettingValue("RejectedByAuditor");

if($ID=="0"){
	$queryawaitingpocount = "SELECT IFNULL (sum(replace(final_total_amout, ',', '')), 0) as awaitingpocount FROM tw_material_outward where status='".$settingValuePendingStatus."' and po_id in (select id from tw_temp_po_info where supplier_id='".$company_id."')";
	$awaitingpocount = $sign->SelectF($queryawaitingpocount,"awaitingpocount");
	if($awaitingpocount==""){
		$awaitingpocount=0.00;
	}

	
	$queryacceptedpocount = "SELECT IFNULL (sum(replace(final_total_amout, ',', '')), 0) as acceptedpo FROM tw_material_outward where status='".$settingValueApprovedStatus."' and po_id in (select id from tw_temp_po_info where supplier_id='".$company_id."')";
	$acceptedpo = $sign->SelectF($queryacceptedpocount,"acceptedpo");
	if($acceptedpo==""){
		$acceptedpo=0.00;
	}
	
	$queryverifiedpocount = "SELECT IFNULL (sum(total_quantity), 0) as activepo FROM tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."' ";
	$activepo = $sign->SelectF($queryverifiedpocount,"activepo");
	if($activepo==""){
		$activepo=0.00;
	}
	
	$UNFULLFILLED = $activepo - ($acceptedpo + $awaitingpocount);
	if($UNFULLFILLED==""){
		$UNFULLFILLED=0.00;
	}
}
else{
	
	$queryawaitingpocount = "SELECT IFNULL (sum(replace(final_total_amout, ',', '')), 0) as awaitingpocount FROM tw_material_outward where status='".$settingValuePendingStatus."' and po_id in (select id from tw_temp_po_info where supplier_id='".$company_id."' and buyer_id='".$ID."')";
	$awaitingpocount = $sign->SelectF($queryawaitingpocount,"awaitingpocount");
	if($awaitingpocount==""){
		$awaitingpocount=0.00;
	}
	
	$queryacceptedpocount = "SELECT IFNULL (sum(replace(final_total_amout, ',', '')), 0) as acceptedpo FROM tw_material_outward where status='".$settingValueApprovedStatus."' and po_id in (select id from tw_temp_po_info where supplier_id='".$company_id."' and buyer_id='".$ID."')";
	$acceptedpo = $sign->SelectF($queryacceptedpocount,"acceptedpo");
	if($acceptedpo==""){
		$acceptedpo=0.00;
	}
	
	$queryverifiedpocount = "SELECT IFNULL (sum(total_quantity), 0) as activepo FROM tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."' and buyer_id='".$ID."'";
	$activepo = $sign->SelectF($queryverifiedpocount,"activepo");
	if($activepo==""){
		$activepo=0.00;
	}
	
	$UNFULLFILLED = $activepo - ($acceptedpo + $awaitingpocount);
	if($UNFULLFILLED==""){
		$UNFULLFILLED=0.00;
	}
}
	
 $CompanyDetails=array();
 array_push($CompanyDetails,number_format($activepo),number_format($acceptedpo),number_format($UNFULLFILLED),number_format($awaitingpocount));
 echo json_encode($CompanyDetails);
?>