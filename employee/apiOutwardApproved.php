<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$id=$_POST["id"];
$po_id=$_POST["po_id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employeeusername"];
$employee_id=$_SESSION["employee_id"];
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$disabledEway="";
$disabledInvoice="";
$disabledWBS="";
$disabledGRN="";
$disabledQC="";

$qryEway="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Eway' and outward_id='".$id."' ORDER BY outward_id ASC";
$retValEway = $sign->SelectF($qryEway,"cnt");

$qry1Eway="SELECT COUNT(*) as cnt from tw_material_outward_eway WHERE outward_id='".$id."' ORDER BY outward_id ASC";
$retVal1Eway = $sign->SelectF($qry1Eway,"cnt");

if($retValEway>0 || $retVal1Eway>0){
	$disabledEway="";
}
else{
	$disabledEway="0";
}


$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$id."' ORDER BY outward_id ASC";
$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$id."' ORDER BY outward_id ASC";
$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");

if($retValInvoice>0 || $retVal1Invoice>0){
	$disabledInvoice="";
}
else{
	$disabledInvoice="0";
}

$qryWBS="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='WBS' and outward_id='".$id."' ORDER BY outward_id ASC";
$retValWBS = $sign->SelectF($qryWBS,"cnt");

$qry1WBS="SELECT COUNT(*) as cnt from tw_material_outward_wbs WHERE outward_id='".$id."' ORDER BY outward_id ASC";
$retVal1WBS = $sign->SelectF($qry1WBS,"cnt");

if($retValWBS>0 || $retVal1WBS>0){
	$disabledWBS="";
}
else{
	$disabledWBS="0";
}

$qryGRN="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='GRN' and inward_id='".$id."' ORDER BY inward_id ASC";
$retValGRN = $sign->SelectF($qryGRN,"cnt");

$qry1GRN="SELECT COUNT(*) as cnt from tw_material_inward_grn WHERE inward_id='".$id."' ORDER BY inward_id ASC";
$retVal1GRN = $sign->SelectF($qry1GRN,"cnt");

if($retValGRN>0 || $retVal1GRN>0){
	$disabledGRN="";
}
else{
	$disabledGRN="0";
}

$qryQC="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='QC' and inward_id='".$id."' ORDER BY inward_id ASC";
$retValQC = $sign->SelectF($qryQC,"cnt");

$qry1QC="SELECT COUNT(*) as cnt from tw_material_inward_qc WHERE inward_id='".$id."' ORDER BY inward_id ASC";
$retVal1QC = $sign->SelectF($qry1QC,"cnt");

if($retValQC>0 || $retVal1QC>0){
	$disabledQC="";
}
else{
	$disabledQC="0";
}
if($disabledEway=="0" || $disabledInvoice=="0" || $disabledWBS=="0" || $disabledGRN=="0" || $disabledQC=="0"){
	echo "Document";
}
else{
	$qry="select ca.google_map from tw_material_outward mo INNER JOIN tw_company_address ca ON mo.ship_to=ca.id where mo.id='".$id."'";
	$retVal = $sign->SelectF($qry,"google_map");

	$qry1="Update tw_material_outward set status='".$settingValueApprovedStatus."' ,receiver_geo_location='".$retVal."' where id='".$id."' "; 
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		$qry5="SELECT total_quantity FROM tw_temp_po_info WHERE id='".$po_id."' and status='".$settingValueApprovedStatus."'";
		$retVal5 = $sign->SelectF($qry5,"total_quantity");
		
		$qry4="SELECT SUM(total_quantity) FROM tw_material_outward WHERE po_id='".$po_id."' and status='".$settingValueApprovedStatus."'";
		$retVal4 = $sign->SelectF($qry4,"SUM(total_quantity)");
		if($retVal4>=$retVal5){
			$qry6="Update tw_temp_po_info set status = '".$settingValueCompletedStatus."' where id='".$po_id."'";
			$retVal6 = $sign->FunctionQuery($qry6);
			if($retVal6=="Success"){
				//echo "Success";
			}
			else{
				echo "error";
			}
		}
		echo "Success";
	}
	else{
		echo "error";
	}
		
}	
	
	
	
	
	
?>
