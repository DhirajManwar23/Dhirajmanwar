<?php
	session_start();// Include class definition
	require "function.php";
	include("commonFunctions.php");
	$sign=new Signup();
	$commonfunction=new Common();
	$ip_address= $commonfunction->getIPAddress();
	date_default_timezone_set("Asia/Kolkata");
	$cur_date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["employee_id"];

	$hdnIDimg=$_POST["hdnIDimg"];
	$valoutwardid=$_POST["valoutwardid"];
	$type=$_POST["type"];
	$size=$_POST["size"];
	$valAction=$_POST["valAction"];
	$rowid=$_POST["rowid"];
	$txtDocumentNumber=$_POST["txtDocumentNumber"];
	$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");

	$qryStatus = "select status from tw_material_outward where id = '".$valoutwardid."'";
	$retVal1Status = $sign->selectF($qryStatus,"status");
	
	
	$disabledGRN="";
	$disabledQC="";
	
	if($type=="GRN"){
		$qryGRN="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='GRN' and inward_id='".$valoutwardid."' ORDER BY inward_id ASC";
		$retValGRN = $sign->SelectF($qryGRN,"cnt");

		$qry1GRN="SELECT COUNT(*) as cnt from tw_material_inward_grn WHERE inward_id='".$valoutwardid."' ORDER BY inward_id ASC";
		$retVal1GRN = $sign->SelectF($qry1GRN,"cnt");

		if($retValGRN>0 || $retVal1GRN>0){
			$disabledGRN="1";
		}
		else{
			$disabledGRN="";
		}

	}
	else if($type=="QC"){
		$qryQC="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='QC' and inward_id='".$valoutwardid."' ORDER BY inward_id ASC";
		$retValQC = $sign->SelectF($qryQC,"cnt");

		$qry1QC="SELECT COUNT(*) as cnt from tw_material_inward_qc WHERE inward_id='".$valoutwardid."' ORDER BY inward_id ASC";
		$retVal1QC = $sign->SelectF($qry1QC,"cnt");

		if($retValQC>0 || $retVal1QC>0){
			$disabledQC="1";
		}
		else{
			$disabledQC="";
		}
	}
	
	if($retVal1Status!=$settingValuePendingStatus){
		echo "Status";
	}
	else if($disabledGRN=="1"){
		echo "Exit";
	}
	else if($disabledQC=="1"){
		echo "Exit";
	}
	else{
		if($valAction=="add"){
			$query1 = "insert into tw_material_inward_documents(inward_id,type,document,size,document_value,created_by,created_on,created_ip) values ('".$valoutwardid."','".$type."','".$hdnIDimg."','".$size."','".$txtDocumentNumber."','".$created_by."','".$cur_date."','".$ip_address."')";
			$retVal1 = $sign->FunctionQuery($query1);
			if($retVal1=="Success"){
				echo "Success";
			}
			else{
				echo "error";
			}
		}
		else{
			$query1 = "update tw_material_inward_documents set document='".$hdnIDimg."',size='".$size."',document_value='".$txtDocumentNumber."',type='".$type."',modified_by='".$created_by."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$rowid."'";
			$retVal1 = $sign->FunctionQuery($query1);
			if($retVal1=="Success"){
				echo "Success";
			}
			else{
				echo "error";
			}
		}
	}
?>
