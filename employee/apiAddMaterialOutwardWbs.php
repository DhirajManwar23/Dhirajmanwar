<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$valquery=$_POST["valquery"];
$outward_id=$_POST["valoutwardid"];
$valrequestid=$_POST["valrequestid"];
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

if($valrequestid!=""){
	$qryWBS="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='WBS' and outward_id='".$outward_id."' and  id!='".$valrequestid."' ORDER BY outward_id ASC";
 	$qry1WBS="SELECT COUNT(*) as cnt from tw_material_outward_wbs WHERE outward_id='".$outward_id."' and id!='".$valrequestid."' ORDER BY outward_id ASC";
}
else{
	$qryWBS="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='WBS' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
	$qry1WBS="SELECT COUNT(*) as cnt from tw_material_outward_wbs WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";

}

$retValWBS = $sign->SelectF($qryWBS,"cnt");


$retVal1WBS = $sign->SelectF($qry1WBS,"cnt");


if($retValWBS>0 || $retVal1WBS>0){
	$disabledWBS="1";
}
else{
	$disabledWBS="";
}
$qryStatus = "select status from tw_material_outward where id = '".$outward_id."'";
$retVal1Status = $sign->selectF($qryStatus,"status");

if($disabledWBS!=""){
	echo "Exist";
}
else if($retVal1Status!=$settingValuePendingStatus){
	echo "Status";
}
else{
	$retVal1 = $sign->FunctionQuery($valquery);
	if($retVal1=="Success"){
		echo "Success";

	}
	else{
		echo "error";
	}
}
	
?>