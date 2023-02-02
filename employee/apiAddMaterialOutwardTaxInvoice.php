<?php
   
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$company_address=$_POST["company_address"];
$bill_to_company_id=$_POST["bill_to_company_id"];
$bill_to_company_address=$_POST["bill_to_company_address"];
$ship_to_company_address=$_POST["ship_to_company_address"];
$invoice_number=$_POST["invoice_number"];
$deliverychallannumber=$_POST["deliverychallannumber"];
$obpcertificatenumber=$_POST["obpcertificatenumber"];
$centreoutwardslipnumber=$_POST["centreoutwardslipnumber"];
$invoice_date=$_POST["invoice_date"];
$date_of_supply=$_POST["date_of_supply"];
$remark=$_POST["remark"];
$txtTermsofPayment=$_POST["txtTermsofPayment"];
$final_total_amount=$_POST["final_total_amount"];
//$final_total_amount = number_format($final_total_amount,2);
$outward_id=$_POST["outward_id"];
$requestid=$_POST["requestid"];
$valrequesttype=$_POST["valrequesttype"];
$created_by=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

if($valrequesttype=="add"){
	//--
		
	$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
	$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

	$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
	$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
	if($retValInvoice>0 || $retVal1Invoice>0){
		$disabledInvoice="1";
	}
	else{
		$disabledInvoice="";
	}
	$qryStatus = "select status from tw_material_outward where id = '".$outward_id."'";
	$retVal1Status = $sign->selectF($qryStatus,"status");

	if($disabledInvoice!=""){
		echo "Exist";
	}
	else if($retVal1Status!=$settingValuePendingStatus){
		echo "Status";
	}
	else{
		$qry1="Insert into tw_tax_invoice(company_id,outward_id,company_address,bill_to_company_id,bill_to_company_address,ship_to_company_address,invoice_number,delivery_challan_no,obp_certificate_no,centre_outward_slip_no,invoice_date,date_of_supply,termsofpayment,remark,final_total_amount,created_by,created_on,created_ip) values
		('".$company_id."','".$outward_id."','".$company_address."','".$bill_to_company_id."','".$bill_to_company_address."','".$ship_to_company_address."','".$invoice_number."','".$deliverychallannumber."','".$obpcertificatenumber."','".$centreoutwardslipnumber."','".$invoice_date."','".$date_of_supply."','".$txtTermsofPayment."','".$remark."','".$final_total_amount."','".$created_by."','".$cur_date."','".$ip_address."')" ;
		
		
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success"){
			echo "Success";

		}
		else{
			echo "error";
		}
	}
	//--
}
else{
	//--
		
	$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."'  ORDER BY outward_id ASC";
	$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

	$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$outward_id."' and id!='".$requestid."' ORDER BY outward_id ASC";
	$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
	if($retValInvoice>0 || $retVal1Invoice>0){
		$disabledInvoice="1";
	}
	else{
		$disabledInvoice="";
	}
	$qryStatus = "select status from tw_material_outward where id = '".$outward_id."'";
	$retVal1Status = $sign->selectF($qryStatus,"status");

	if($disabledInvoice!=""){
		echo "Exist";
	}
	else if($retVal1Status!=$settingValuePendingStatus){
		echo "Status";
	}
	else{
			$qry1="update tw_tax_invoice set company_address= '".$company_address."', bill_to_company_id='".$bill_to_company_id."',bill_to_company_address= '".$bill_to_company_address."',ship_to_company_address='".$ship_to_company_address."',invoice_number= '".$invoice_number."',delivery_challan_no= '".$deliverychallannumber."',obp_certificate_no= '".$obpcertificatenumber."',centre_outward_slip_no= '".$centreoutwardslipnumber."',invoice_date= '".$invoice_date."',date_of_supply='".$date_of_supply."',termsofpayment='".$txtTermsofPayment."',remark='".$remark."',final_total_amount='".$final_total_amount."',modified_by='".$created_by."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$requestid."' ";
		
		
		
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success"){
			echo "Success";

		}
		else{
			echo "error";
		}
	}
	//--
	
}


	
?>