<?php
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$PONumber=$_POST['PONumber'];
$id=$_POST['id'];
$sid=$_POST['sid'];
$TotalQty="";
$company_id=$_POST['company_id'];
//$supplier_id=$_POST['supplier_id'];
$supplier_address_id=$_POST['supplier_address_id'];
$employee_id=$_POST['employee_id'];
$POdate=$_POST['date'];
$PYMENTTERMS=$_POST['PYMENTTERMS'];
$SHIPPINGTERMS=$_POST['SHIPPINGTERMS'];
$INVOICINGMETHOD=$_POST['INVOICINGMETHOD'];
$BILLTOADDRESS_ID=$_POST['BILLTOADDRESS_ID'];
$SHIPTOADDRESS_ID=$_POST['SHIPTOADDRESS_ID'];
$TotalQty=$_POST['TotalQty'];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d ");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");

$QueryPoQuantity="Select SUM(quantity) as quantity from tw_po_details_temp where company_id='".$company_id."'";
$TotalQty=$sign->SelectF($QueryPoQuantity,'quantity');

	$qrycount="Select count(*) as cnt from tw_po_info where id = '".$id."' AND status='".$settingValueApprovedStatus."'";
	$retVal2 = $sign->Select($qrycount);
	if($retVal2==1){
		echo "Exist";
	}
	else{
	$DeleteQry="Delete from tw_po_details where po_id='".$id."'";
	$Delete = $sign->FunctionQuery($DeleteQry);

	$updateQry="Update tw_po_info SET po_number='".$PONumber."',supplier_id='".$sid."',supplier_address_id='".$supplier_address_id."',date_of_po='".$POdate."',payment_term='".$PYMENTTERMS."',shipping_term='".$SHIPPINGTERMS."',invoicing_method='".$INVOICINGMETHOD."',bill_to_address_id='".$BILLTOADDRESS_ID."',send_invoice_to_address_id='".$SHIPTOADDRESS_ID."',total_quantity='".$TotalQty."',modified_by='".$employee_id."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where  id='".$id."'  ";
	$update = $sign->FunctionQuery($updateQry);

	if($update=="Success"){
		$InsertQry="Select fulfillment_cycle,description,state,district,supplier_part_number,product_id,quantity,start_date,delivery_date,price_per_unit,amount from tw_po_details_temp where company_id='".$company_id."' order by id asc";
		$retVal1 = $sign->FunctionJSON($InsertQry);
		
		$selectCntQry="SELECT COUNT(*)as cnt FROM tw_po_details_temp where company_id='".$company_id."'";
		$selectCnt = $sign->Select($selectCntQry);
		
		$decodedJSON2 = json_decode($retVal1);
		$count = 0;
		$i = 1;
		$x=$selectCnt;
		$table="";
		$it=1;
		$valtotalamt=0;
		
			while($x>=$i){
				$fulfillment_cycle = $decodedJSON2->response[$count]->fulfillment_cycle;
				$count=$count+1;
				$description = $decodedJSON2->response[$count]->description;
				$count=$count+1;
				$state = $decodedJSON2->response[$count]->state;
				$count=$count+1;
				$district = $decodedJSON2->response[$count]->district;
				$count=$count+1;
				$supplier_part_number = $decodedJSON2->response[$count]->supplier_part_number;
				$count=$count+1;
				$product_id = $decodedJSON2->response[$count]->product_id;
				$count=$count+1;
				$quantity = $decodedJSON2->response[$count]->quantity;
				$count=$count+1;
				$start_date = $decodedJSON2->response[$count]->start_date;
				$count=$count+1;
				$delivery_date = $decodedJSON2->response[$count]->delivery_date;
				$count=$count+1;
				$price_per_unit = $decodedJSON2->response[$count]->price_per_unit;
				$count=$count+1;
				$amount = $decodedJSON2->response[$count]->amount;
				$count=$count+1;
				
				
				
				
				$qry3="insert into tw_po_details (po_id,fulfillment_cycle,description,state,district,supplier_part_number,product_id,quantity,start_date,delivery_date,price_per_unit,amount,modified_by,modified_on,modified_ip) values('".$id."','".$fulfillment_cycle."','".$description."','".$state."','".$district."','".$supplier_part_number."','".$product_id."','".$quantity."','".$start_date."','".$delivery_date."','".$price_per_unit."','".$amount."','".$employee_id."','".$cur_date."','".$ip_address."')" ;
				$retVal3 = $sign->FunctionQuery($qry3);
				
				$i=$i+1;
				
			}
			echo "Success";
		}
	
	else{
			echo "error";
	}
}

?>