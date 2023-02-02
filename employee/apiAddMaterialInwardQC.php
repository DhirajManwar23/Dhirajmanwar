<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$txtPartyName=$_POST["txtPartyName"];
$txtPartyBillNo=$_POST["txtPartyBillNo"];
$txtDate=$_POST["txtDate"];
$txtVehicleNumber=$_POST["txtVehicleNumber"];
$txtRemark=$_POST["txtRemark"];
$inward_id=$_POST["inward_id"];
$valrequesttype=$_POST["valrequesttype"];
$valcompany_id=$_POST["valcompany_id"];
//$party_id=$_POST["party_id"];

$employee_id=$_SESSION["employee_id"];
$insertQueryValue=$_POST["insertQueryValue"];
$request_id=$_POST["request_id"];
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

if($request_id!=""){
	$qryQC="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='QC' and inward_id='".$inward_id."' and  id!='".$request_id."' ORDER BY inward_id ASC";
	$qry1QC="SELECT COUNT(*) as cnt from tw_material_inward_qc WHERE inward_id='".$inward_id."' and  id!='".$request_id."' ORDER BY inward_id ASC";
}
else{
	$qryQC="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='QC' and inward_id='".$inward_id."' ORDER BY inward_id ASC";
	$qry1QC="SELECT COUNT(*) as cnt from tw_material_inward_qc WHERE inward_id='".$inward_id."' ORDER BY inward_id ASC";
}

$retValQC = $sign->SelectF($qryQC,"cnt");
$retVal1QC = $sign->SelectF($qry1QC,"cnt");


if($retValQC>0 || $retVal1QC>0){
	$disabledQC="1";
}
else{
	$disabledQC="";
}

$qryStatus = "select status from tw_material_outward where id = '".$inward_id."'";
$retVal1Status = $sign->selectF($qryStatus,"status");
	
$qry1="Select count(*) as cnt from tw_test_report_designation_master where company_id='".$valcompany_id."'" ;
$retVal1 = $sign->Select($qry1);
if($retVal1==0){
	echo "Blank";
}
else if($retVal1Status!=$settingValuePendingStatus){
	echo "Status";
}
else if($disabledQC!=""){
	echo "Exist";
}
else{ 


	if($valrequesttype=="add"){
		$qry="insert into tw_material_inward_qc (inward_id,company_id,party_id,party_bill_no,date,vehicle_no,remark,created_by,created_on,created_ip) values('".$inward_id."','".$valcompany_id."','".$txtPartyName."','".$txtPartyBillNo."','".$txtDate."','".$txtVehicleNumber."','".$txtRemark."','".$employee_id."','".$cur_date."','".$ip_address."')";
		
		
		$retVal = $sign->FunctionQuery($qry,true);
		if($retVal!=""){
			$material_outward_qc_id=$retVal;
			$insertQueryValue=str_replace('EMPID', $employee_id, $insertQueryValue); // Replace EMPID
			$insertQueryValue=str_replace('QCID', $material_outward_qc_id, $insertQueryValue); // Replace QCID
			$insertQueryValue=str_replace('CREATEDON', $cur_date, $insertQueryValue); // Replace CREATEDON
			$insertQueryValue=str_replace('CREATEDIP', $ip_address, $insertQueryValue); // Replace CREATEDIP
		
			$retVal = $sign->FunctionQuery($insertQueryValue);
			
			
			
			
			echo "Success";
		}
		else{
			echo "error";
		}

		
		
	}
	else{
		$qry="Update tw_material_inward_qc SET party_bill_no='".$txtPartyBillNo."',date='".$txtDate."',vehicle_no='".$txtVehicleNumber."',remark='".$txtRemark."',modified_by='".$employee_id."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$request_id."'"; 
		$retVal = $sign->FunctionQuery($qry);
		if($retVal!=""){
			$insertQueryValue=str_replace('EMPID', $employee_id, $insertQueryValue); // Replace EMPID
			$insertQueryValue=str_replace('MODDATE', $cur_date, $insertQueryValue); // Replace MODDATE
			$insertQueryValue=str_replace('MODIP', $ip_address, $insertQueryValue); // Replace MODIP
			
			$uQ=explode("N;N",$insertQueryValue);
			foreach ($uQ as $qryUpdate) {
				if ($qryUpdate!="")
				{
					$sign->FunctionQuery($qryUpdate);
				}
			}
			echo "Success";
		}
		else{
			echo "error";
		}
		
	 }
 
}


	?>

