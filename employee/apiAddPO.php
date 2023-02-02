<?php
   
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
include_once "mailFunction.php";

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
$created_by=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValuePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValueSalesManager = $commonfunction->getSettingValue("SalesManager");

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$qry="Select count(*) as cnt from tw_temp_po_details_individual_temp where employee_id='".$created_by."'" ;
$retVal = $sign->Select($qry);
if($retVal==0){
	echo "Blank";
}
else{
	$qry1="Insert into tw_temp_po_info(supplier_id,supplier_address,bill_to_address,buyer_id,status,po_date,delivery_date,total_quantity,final_total_amount,delivery_terms,disp_instruction,payment_terms,sp_instruction,created_by,created_on,created_ip) values('".$customer_id."','".$Address_id."','".$BillAddress_id."','".$company_id."','".$settingValuePendingStatus."','".$po_date."','".$delivery_date."','".$total_quantity."','".$final_total_amount."','".$delivery_terms."','".$disp_instruction."','".$payment_terms."','".$sp_instruction."','".$created_by."','".$cur_date."','".$ip_address."')" ;
	$retVal1 = $sign->FunctionQuery($qry1,true);
	if($retVal1!=""){
		$po_id=$retVal1;
		
		$qry2="select material_id,quantity,hsn,tax,rate,total from tw_temp_po_details_individual_temp where employee_id='".$created_by."' order by id Asc";
		$retVal2 = $sign->FunctionJSON($qry2);
		
		$qry3="Select count(*) as cnt from tw_temp_po_details_individual_temp where employee_id='".$created_by."' order by id Asc";
		$retVal3 = $sign->Select($qry3);

		$decodedJSON2 = json_decode($retVal2);
		$count = 0;
		$i = 1;
		$x=$retVal3;
		$it=1;
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
			
			$qry3="Insert into tw_temp_po_details(po_id,employee_id,material_id,quantity,hsn,tax,rate,total,created_by,created_on,created_ip) values('".$po_id."','".$created_by."','".$material_id."','".$quantity."','".$hsn."','".$tax."','".$rate."','".$total."','".$created_by."','".$cur_date."','".$ip_address."')" ;
			$retVal3 = $sign->FunctionQuery($qry3);
			
			
			$i=$i+1;
		}
		
		$qry4="Select cd.CompanyName,cc.value from tw_company_details cd Inner join tw_company_contact cc ON cd.ID=cc.company_id where cd.ID='".$customer_id."' and cc.contact_field='".$settingValuePrimaryEmail."' and cc.public_visible='true'" ;
		$retVal4 = $sign->FunctionJSON($qry4);
		$decodedJSON = json_decode($retVal4);
		$CompanyName = $decodedJSON->response[0]->CompanyName;
		$email = $decodedJSON->response[1]->value;
		
			//
			$mailobj=new twMail();
			$subject = "Purchase Order";
			$myfile = fopen($settingValueMailPath."pgPONewRequest.html", "r");

			$message = fread($myfile,filesize($settingValueMailPath."pgPONewRequest.html"));

			$message = str_replace("_Company_",$CompanyName,$message);
			fclose($myfile);
			//
			$mail_response = $mailobj->Mailsend($email,$subject,$message); 
			
			
			 //--Mail function end (User) 
			 //---Sending Push Notification Start
			$qrySaleEmp="SELECT employee_name,devicetoken FROM tw_employee_registration WHERE company_id='".$customer_id."' and employee_role='".$settingValueSalesManager."' and notification_status='On'";
			$retValSaleEmp = $sign->FunctionJSON($qrySaleEmp);
			$qrySaleEmpCount="Select count(*) as cnt from tw_employee_registration WHERE company_id='".$customer_id."' and employee_role='".$settingValueSalesManager."' and notification_status='On'";
			$retValSaleEmpCount = $sign->Select($qrySaleEmpCount);
			$decodedJSON3 = json_decode($retValSaleEmp);
			$count1 = 0;
			$i1 = 1;
			$x1=$retValSaleEmpCount;
			$it=1;
			while($x1>=$i1){
				$employee_name = $decodedJSON3->response[$count1]->employee_name;
				$count1=$count1+1;
				$devicetoken = $decodedJSON3->response[$count1]->devicetoken;
				$count1=$count1+1;
				
				if($devicetoken!=""){
					$msg = "Dear ".$employee_name." New PO has been Raised.";
					$sendGCM = $commonfunction->sendGCM('Purchase Order', $msg, $devicetoken);
				}
				$i1=$i1+1;
			}
			
			 //---Sending Push Notification End
			 echo "Success";
	}
	else{
		echo "error";
	}
}

?>