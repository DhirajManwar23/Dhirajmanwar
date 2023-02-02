<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$id=$sign->escapeString($_POST["id"]);
$valinvoiceid=$sign->escapeString($_POST["valinvoiceid"]);
$transaction_id=$sign->escapeString($_POST["transaction_id"]);
$payment_type=$sign->escapeString($_POST["payment_type"]);
$payment_date=$sign->escapeString($_POST["payment_date"]);
$amount=$sign->escapeString($_POST["amount"]);
$comments=$sign->escapeString($_POST["comments"]);
$valpo_id=$sign->escapeString($_POST["valpo_id"]);
$valsupplierid=$sign->escapeString($_POST["valsupplierid"]);
$valcustomerid=$sign->escapeString($_POST["valcustomerid"]);
$valrequesttype=$sign->escapeString($_POST["valrequesttype"]);
$valcreated_by=$sign->escapeString($_POST["valcreated_by"]);
$status=$sign->escapeString($_POST["status"]);
$valBalInvoiceAmount=$sign->escapeString($_POST["valBalInvoiceAmount"]);
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
if($valrequesttype=="add"){
	$qry="select count(*) as cnt from tw_invoice_transaction_eprs where transaction_id='".$transaction_id."' ";
	$retVal = $sign->Select($qry);
	if($retVal>0){
		echo "Exist";
	}
	else if($valBalInvoiceAmount=='0'){
		echo "PaymentCleared";
	}
	else if($amount>$valBalInvoiceAmount){
		echo "AmountGreater";
	} 
	else
	{	
		$qry1="insert into tw_invoice_transaction_eprs(invoice_id,transaction_id,payment_type,amount,status,comments,po_id,supplier_id,customer_id,payment_date,created_by,created_on,created_ip) values('".$valinvoiceid."','".$transaction_id."','".$payment_type."','".$amount."','".$status."','".$comments."','".$valpo_id."','".$valsupplierid."','".$valcustomerid."','".$payment_date."','".$valcreated_by."','".$date."','".$ip_address."')";
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
   
	}
}
else{
	
	$qry="select count(*) as cnt from tw_invoice_transaction_eprs where transaction_id='".$transaction_id."' and id!='".$id."'";
	$retVal = $sign->Select($qry);
	if($retVal>0){
		echo "Exist";
	}
	else if($valBalInvoiceAmount=='0'){
		echo "PaymentCleared";
	} 
	else if($amount>$valBalInvoiceAmount){
		echo "AmountGreater";
	}
	else
	{	
		$qry1="update tw_invoice_transaction_eprs set invoice_id='".$valinvoiceid."',transaction_id='".$transaction_id."',payment_type='".$payment_type."', amount='".$amount."', comments='".$comments."',po_id='".$valpo_id."',supplier_id='".$valsupplierid."',customer_id='".$valcustomerid."',payment_date='".$payment_date."', modified_by='".$valcreated_by."', modified_on='".$date."', modified_ip='".$ip_address."' where id='".$id."'"; 
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}   
	}
		
} 
	
	
?>
