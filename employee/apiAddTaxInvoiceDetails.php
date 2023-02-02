<?php
   
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$requestid=$_POST["requestid"];
$supplier_name=$_POST["supplier_name"];
$supplier_id=$_POST["supplie_id"];
$company_address=$_POST["company_address_id"];
$bill_to_company_id=$_POST["bill_to_address"];
$bill_to_company_address=$_POST["bill_to_address"];
$bill_to_gst_number=$_POST["bill_to_gst_number"];
$bill_to_pincode=$_POST["bill_to_pincode"];
$bill_to_state=$_POST["bill_to_state"];
$vehicle_no=$_POST["vehicle_no"];
$mode_of_transport=$_POST["mode_of_transport"];

$ship_to_company_address=$_POST["ship_to_address"];
$ship_to_gst=$_POST["ship_to_gst"];
$ship_to_pincode=$_POST["ship_to_pincode"];
$ship_to_state=$_POST["ship_to_state"];

$invoice_number=$_POST["txtinvoicenumber"];
$deliverychallannumber=$_POST["delivery_challan"];
$obpcertificatenumber=$_POST["obp_number"];
$lr_no=$_POST["lr_no"];
$lr_date=$_POST["lr_date"];
$centreoutwardslipnumber=$_POST["centreoutwardslipnumber"];
$invoice_date=$_POST["invoice_date"];
$date_of_supply=$_POST["date_of_supply"];
$remark=$_POST["remark"];
$txtTermsofPayment=$_POST["txtTermsofPayment"];
$final_total_amount=$_POST["final_amount"];
//$final_total_amount = number_format($final_total_amount,2);
// $outward_id=$_POST["outward_id"];
// $requestid=$_POST["requestid"];
$valrequesttype=$_POST["valrequesttype"];
$created_by=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:s");

if($valrequesttype=="add"){
	
		$qry1="Insert into  tw_thirdparty_invoice (invoice_number,delivery_challan,obp_number,centre_outward_number,invoice_date, 	date_of_supply,company_address_id,lr_no,lr_date,supplier_name,supplier_id,bill_to_address,bill_to_gst_number ,bill_to_pincode,bill_to_state,vehicle_no,mode_of_transport,ship_to_address,ship_to_gst,ship_to_pincode,ship_to_state,remark,termsofpayment ,final_amount,created_by,created_on,created_ip) values
		('".$invoice_number."','".$deliverychallannumber."','".$obpcertificatenumber."','".$centreoutwardslipnumber."','".$invoice_date."','".$date_of_supply."','".$company_address."','".$lr_no."','".$lr_date."','".$supplier_name."','".$supplier_id."','".$bill_to_company_address."','".$bill_to_gst_number."','".$bill_to_pincode."','".$bill_to_state."','".$vehicle_no."','".$mode_of_transport."','".$ship_to_company_address."','".$ship_to_gst."','".$ship_to_pincode."','".$ship_to_state."','".$remark."','".$txtTermsofPayment."','".$final_total_amount."','".$created_by."','".$cur_date."','".$ip_address."')" ;
		
		
		 $retVal1 = $sign->FunctionQuery($qry1,'true');
		
		if($retVal1!=""){
			$INV_id=$retVal1;
			
			$qry2="select  id,material_id,quantity,hsn,tax,rate,total from tw_tax_invoice_temp  where employee_id='".$created_by."' order by id Asc";
			$retVal2 = $sign->FunctionJSON($qry2);
			
			$qry3="Select count(*) as cnt from tw_tax_invoice_temp  where employee_id='".$created_by."' order by id Asc";
			$retVal3 = $sign->Select($qry3);

			$decodedJSON2 = json_decode($retVal2);
			$count = 0;
			$i = 1;
			$x=$retVal3;
			$it=1;
			while($x>=$i){
					
				$id = $decodedJSON2->response[$count]->id;
				$count=$count+1;
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
				
				 $qry3="Insert into  tw_tax_invoice_details (invoice_id ,employee_id,material_id,quantity,hsn ,tax,rate,total,created_by,created_on,created_ip) values('".$INV_id."','".$created_by."','".$material_id."','".$quantity."','".$hsn."','".$tax."','".$rate."','".$total."','".$created_by."','".$cur_date."','".$ip_address."')" ;
				$retVal3 = $sign->FunctionQuery($qry3);
				$i=$i+1;
			}
		
			if($retVal3=="Success")
			 echo "Success";
		}
		else{
		echo "error";	
	
	}
		
	//--
	}

else{
			 $qry1="update tw_thirdparty_invoice  set company_address_id= '".$company_address."',lr_no='".$lr_no."', lr_date='".$lr_date."',supplier_name='".$supplier_name."',supplier_id='".$supplier_id."',bill_to_address = '".$bill_to_company_id."',bill_to_gst_number='".$bill_to_gst_number."',bill_to_pincode='".$bill_to_pincode."',bill_to_state='".$bill_to_state."',vehicle_no='".$vehicle_no."',mode_of_transport='".$mode_of_transport."',ship_to_address='".$ship_to_company_address."',ship_to_gst='".$ship_to_gst."', ship_to_pincode='".$ship_to_pincode."',ship_to_state='".$ship_to_state."',remark='".$remark."',invoice_number= '".$invoice_number."',delivery_challan= '".$deliverychallannumber."',obp_number= '".$obpcertificatenumber."', 	centre_outward_number= '".$centreoutwardslipnumber."',invoice_date= '".$invoice_date."',date_of_supply='".$date_of_supply."',termsofpayment='".$txtTermsofPayment."',remark='".$remark."',final_amount='".$final_total_amount."',modified_by='".$created_by."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$requestid."' ";
		
			 $retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1=="Success"){
		
				 $Deleteqry="DELETE FROM tw_tax_invoice_details  WHERE invoice_id='".$requestid."'";
				$Delete = $sign->FunctionQuery($Deleteqry);
				if($Delete=="Success"){
						$qry2="select  id,material_id,quantity,hsn,tax,rate,total from tw_tax_invoice_temp  where employee_id='".$created_by."' order by id Asc";
						$retVal2 = $sign->FunctionJSON($qry2);
						
						$qry3="Select count(*) as cnt from tw_tax_invoice_temp  where employee_id='".$created_by."' order by id Asc";
						$retVal3 = $sign->Select($qry3);

						$decodedJSON2 = json_decode($retVal2);
						$count = 0;
						$i = 1;
						$x=$retVal3;
						$it=1;
						while($x>=$i){
								
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
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
							
							 $qry3="Insert into  tw_tax_invoice_details (invoice_id ,employee_id,material_id,quantity,hsn ,tax,rate,total,created_by,created_on,created_ip) values('".$requestid."','".$created_by."','".$material_id."','".$quantity."','".$hsn."','".$tax."','".$rate."','".$total."','".$created_by."','".$cur_date."','".$ip_address."')" ;
							$retVal3 = $sign->FunctionQuery($qry3);
							$i=$i+1;
						}
						if($retVal3=="Success"){
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
		
	//--
	
}


	
?>