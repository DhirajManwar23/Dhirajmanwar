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
	$txtAmount=0;
	$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");	
	$disabledEway="";
	$disabledInvoice="";
	$disabledWBS="";
	if($type=="Eway"){
		$qryEway="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Eway' and outward_id='".$valoutwardid."' ORDER BY outward_id ASC";
		$retValEway = $sign->SelectF($qryEway,"cnt");

		$qry1Eway="SELECT COUNT(*) as cnt from tw_material_outward_eway WHERE outward_id='".$valoutwardid."' ORDER BY outward_id ASC";
		$retVal1Eway = $sign->SelectF($qry1Eway,"cnt");

		if($retValEway>0 || $retVal1Eway>0){
			$disabledEway="1";
		}
		else{
			$disabledEway="";
		}
	}
	else if($type=="Invoice"){
		$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$valoutwardid."' ORDER BY outward_id ASC";
		$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

		$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$valoutwardid."' ORDER BY outward_id ASC";
		$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
		if($retValInvoice>0 || $retVal1Invoice>0){
			$disabledInvoice="1";
		}
		else{
			$disabledInvoice="";
		}
	}
	else if($type=="WBS"){
		$qryWBS="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='WBS' and outward_id='".$valoutwardid."' ORDER BY outward_id ASC";
		$retValWBS = $sign->SelectF($qryWBS,"cnt");

		$qry1WBS="SELECT COUNT(*) as cnt from tw_material_outward_wbs WHERE outward_id='".$valoutwardid."' ORDER BY outward_id ASC";
		$retVal1WBS = $sign->SelectF($qry1WBS,"cnt");
		if($retValWBS>0 || $retVal1WBS>0){
			$disabledWBS="1";
		}
		else{
			$disabledWBS="";
		}
	}
	
	$qryStatus = "select status from tw_material_outward where id = '".$valoutwardid."'";
	$retVal1Status = $sign->selectF($qryStatus,"status");
	
	if($retVal1Status!=$settingValuePendingStatus){
		echo "Status";
	}
	else if($disabledEway=="1"){
		echo "Exit";
	}
	else if($disabledInvoice=="1"){
		echo "Exit";
	}
	else if($disabledWBS=="1"){
		echo "Exit";
	}
	else{
		
		if ($type=="Invoice")
		{
			$txtAmount=$_POST["txtAmount"];
		}
		if($valAction=="add"){
			$query1 = "insert into tw_material_outward_documents(outward_id,type,document,size,document_value,amount,created_by,created_on,created_ip) values ('".$valoutwardid."','".$type."','".$hdnIDimg."','".$size."','".$txtDocumentNumber."','".$txtAmount."','".$created_by."','".$cur_date."','".$ip_address."')";
			$retVal1 = $sign->FunctionQuery($query1);
			if($retVal1=="Success"){
				echo "Success";
			}
			else{
				echo "error";
			}
		}
		else{
			$query1 = "update tw_material_outward_documents set document='".$hdnIDimg."',size='".$size."',document_value='".$txtDocumentNumber."',amount='".$txtAmount."',modified_by='".$created_by."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$rowid."'";
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
