<?php
   
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();

$customer_id=$sign->escapeString($_POST["customer_id"]);
$po_date=$sign->escapeString($_POST["po_date"]);
$delivery_date=$sign->escapeString($_POST["delivery_date"]);
$final_total_amount=$sign->escapeString($_POST["final_total_amount"]);
$total_quantity=$sign->escapeString($_POST["valTotalQuantity"]);
$Address_id=$sign->escapeString($_POST["Address_id"]);
$BillAddress_id=$sign->escapeString($_POST["BillAddress_id"]);
$delivery_terms=$sign->escapeString($_POST["delivery_terms"]);
$disp_instruction=$sign->escapeString($_POST["disp_instruction"]);
$payment_terms=$sign->escapeString($_POST["payment_terms"]);
$sp_instruction=$sign->escapeString($_POST["sp_instruction"]);
$po_id = $_REQUEST["po_id"];
//$id = $_REQUEST["id"];
$hdnOrderID=$_POST["hdnOrderID"];
$created_by=$_SESSION["employee_id"];
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d ");
$qry="Select count(*) as cnt from tw_temp_po_details_individual_temp where employee_id='".$created_by."'" ;
$retVal = $sign->Select($qry);

$qrycount="Select count(*) as cnt from tw_temp_po_info where id = '".$hdnOrderID."' AND status!='".$settingValuePendingStatus."'";
$retValcount = $sign->Select($qrycount);

if($retVal==0){
	echo "Blank";
}
else if($retValcount==1){
	echo "Exist";
}
else{
	 $qry1="Delete from tw_temp_po_details where po_id='".$po_id."' ";
	 $retVal1 = $sign->FunctionQuery($qry1);
	 if($retVal1=="Success"){
		$qry2="Update tw_temp_po_info set supplier_id='".$customer_id."',supplier_address='".$Address_id."',bill_to_address='".$BillAddress_id."',buyer_id='".$company_id."',status='".$settingValuePendingStatus."',po_date='".$po_date."',delivery_date='".$delivery_date."',total_quantity='".$total_quantity."',final_total_amount='".$final_total_amount."',delivery_terms='".$delivery_terms."',disp_instruction='".$disp_instruction."',payment_terms='".$payment_terms."',sp_instruction='".$sp_instruction."',modified_by='".$created_by."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$po_id."'";
		$retVal2 = $sign->FunctionQuery($qry2);
		if($retVal2=="Success"){
		$query="Select material_id,quantity,hsn,tax,rate,total from tw_temp_po_details_individual_temp where employee_id = '".$employee_id."' ORDER BY id ASC";
		$retVal4 = $sign->FunctionJSON($query);
		
		$qry3="Select count(*) as cnt from  tw_temp_po_details_individual_temp where employee_id = '".$employee_id."'";
		$retVal3 = $sign->Select($qry3);

		$decodedJSON2 = json_decode($retVal4);
		$count = 0;
		$i = 1;
		$x=$retVal3;
		$table="";
		$it=1;
		$valtotalamt=0;
		$table.="<thead><tr><th>#SR.NO</th><th>Material ID</th><th>Quantity</th><th>HSN</th><th>Tax</th><th>Rate</th><th>Total GST</th><th>Total</th><th>TotalGST+Total</th><th>Remove</th></tr></thead><tbody>";
			while($x>=$i){
			
			$material_id = $decodedJSON2->response[$count]->material_id;
			$count=$count+1;
			$quantity = $decodedJSON2->response[$count]->quantity;
			$count=$count+1;
			$hsn = $decodedJSON2->response[$count]->hsn;
			$count=$count+1;
			$tax = $decodedJSON2->response[$count]->tax;
			$count=$count+1;
			$rate = $decodedJSON2->response[$count]->rate;
			$count=$count+1;
			$total = $decodedJSON2->response[$count]->total;
			$count=$count+1;
			
			
			$qry5="Insert into tw_temp_po_details(po_id,employee_id,material_id,quantity,hsn,tax,rate,total,created_by,created_on,created_ip) values('".$hdnOrderID."','".$employee_id."','".$material_id."','".$quantity."','".$hsn."','".$tax."','".$rate."','".$total."','".$employee_id."','".$cur_date."','".$ip_address."')" ;
			$retVal5 = $sign->FunctionQuery($qry5);
			
			$i=$i+1;
			}
			$qry6="delete from tw_temp_po_details_individual_temp where employee_id = '".$employee_id."'";
			$retVal6 = $sign->FunctionQuery($qry6);

			if($retVal6=="Success"){
				echo "Success";
			}
			else{
				echo "error";
			}

			}
			else{
				echo "error";
			}
	}
	else{
		echo "error";
	}
}
?>