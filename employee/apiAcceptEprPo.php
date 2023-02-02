<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValuePendingStatus=$sign->escapeString($settingValuePendingStatus);
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");

$po_id=$_REQUEST["id"];
$tid=$_POST["tid"];


$qry="SELECT total_quantity FROM tw_po_info where id='".$po_id."'";
$total_quantity=$sign->SelectF($qry,"total_quantity"); 

$qry1="SELECT sum(total_quantity) FROM tw_epr_material_assign_info where po_id='".$po_id."'";
$total_quantity_Assign=$sign->SelectF($qry1,"sum(total_quantity)"); 

if($total_quantity_Assign>=$total_quantity){
	$updateQry= "Update tw_epr_material_assign_info set status = '".$settingValueApprovedStatus."' where po_id='".$po_id."'  AND   id='".$tid."'";
	$updateQry1= "Update  tw_po_info set status = '".$settingValueCompletedStatus."' where id='".$po_id."' ";
	$retVal2 = $sign->FunctionQuery($updateQry1);
}
else{
  $updateQry= "Update tw_epr_material_assign_info set status = '".$settingValueApprovedStatus."' where po_id='".$po_id."'  AND id='".$tid."'"; 
}

$retVal1 = $sign->FunctionQuery($updateQry);

if($retVal1=="Success" ){
	echo "Success";
}
else{
	echo "error";
}	
?>