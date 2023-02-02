<?php
session_start();
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
include_once "mailFunction.php";
$PONumber=$_POST['PONumber'];
$company_id=$_POST['company_id'];
$LogIncompany_id=$_SESSION['company_id'];
$supplier_id=$_POST['supplier_id'];
$supplier_address_id=$_POST['supplier_address_id'];
$employee_id=$_POST['employee_id'];
$POdate=$_POST['date'];
$PYMENTTERMS=$_POST['PYMENTTERMS'];
$SHIPPINGTERMS=$_POST['SHIPPINGTERMS'];
$INVOICINGMETHOD=$_POST['INVOICINGMETHOD'];
$BILLTOADDRESS_ID=$_POST['BILLTOADDRESS_ID'];
$SHIPTOADDRESS_ID=$_POST['SHIPTOADDRESS_ID'];
$TotalQty=$_POST['TotalQty'];
$TotalFinalAmount=$_POST['valTotalAmount'];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValuePrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath= $commonfunction->getSettingValue("MailPath");
$settingValueEPRManager= $commonfunction->getSettingValue("EPR Manager");
$ip_address= $commonfunction->getIPAddress();

$settingValueDocumentUserUrl= $commonfunction->getSettingValue("EmployeeUrl");
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$empId="";
$aggEmailQry="select CompanyName from  tw_company_details where ID='".$LogIncompany_id."'";
$LogInCompanyName=$sign->SelectF($aggEmailQry,"CompanyName"); 

$empIdQry="select ec.value from tw_employee_registration er INNER JOIN tw_employee_contact ec ON er.id=ec.employee_id where er.company_id='".$supplier_id."' and er.employee_role='".$settingValueEPRManager."' AND ec.contact_field='".$settingValuePrimaryEmail."'";


$CntQry="select count(*) as cnt from tw_employee_registration er INNER JOIN tw_employee_contact ec ON er.id=ec.employee_id where er.company_id='".$supplier_id."' and er.employee_role='".$settingValueEPRManager."' AND ec.contact_field='".$settingValuePrimaryEmail."'";
$cnt=$sign->Select($CntQry);
$i1 = 1;
$x1=$cnt;
$it=1;
$count1 = 0;
	
$empId=$sign->FunctionJSON($empIdQry); 
$decodedJSON1 = json_decode($empId);

$emails = "";
while($x1>=$i1){
	$value = $decodedJSON1->response[$count1]->value;
	$emails.=$value.",";
	$count1=$count1+1;
	 $i1=$i1+1;
 }
$str = substr($emails,0,strlen($emails)-1);

$qry="Select count(*) as cnt from tw_po_details_temp where company_id='".$company_id."'" ;
$retVal = $sign->Select($qry);
if($retVal==0){
	echo "Blank";
}
else{
	$qry1="insert into tw_po_info(po_number,company_id,supplier_id,supplier_address_id,employee_id,status,
	date_of_po,payment_term,shipping_term,invoicing_method,bill_to_address_id,send_invoice_to_address_id,total_quantity,final_total_amount,created_by,created_on,created_ip)
	values('".$PONumber."','".$company_id."','".$supplier_id."','".$supplier_address_id."','".$employee_id."','".$settingValuePendingStatus."','".$POdate."','".$PYMENTTERMS."',
	'".$SHIPPINGTERMS."','".$INVOICINGMETHOD."','".$BILLTOADDRESS_ID."','".$SHIPTOADDRESS_ID."','".$TotalQty."','".$TotalFinalAmount."','".$employee_id."','".$date."','".$ip_address."')" ;
	$retVal1 = $sign->FunctionQuery($qry1,true);
	if($retVal1!=""){
		$po_id=$retVal1;
		
		$qry2="Select id,fulfillment_cycle,description,state,district,supplier_part_number,product_id,quantity,start_date,delivery_date,price_per_unit,amount from tw_po_details_temp where company_id = '".$company_id."' ORDER BY id Asc";
		$retVal2 = $sign->FunctionJSON($qry2);
		
		$qry3="Select count(*) as cnt from tw_po_details_temp where company_id = '".$company_id."'";
		$retVal3 = $sign->Select($qry3);

		$decodedJSON2 = json_decode($retVal2);
		$count = 0;
		$i = 1;
		$x=$retVal3;
		$it=1;
			while($x>=$i){
				
			$id = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			
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
				
			$qry3="insert into tw_po_details (po_id,fulfillment_cycle,description,state,district,supplier_part_number,product_id,quantity,start_date,delivery_date,price_per_unit,amount,created_by,created_on,created_ip) values('".$po_id."','".$fulfillment_cycle."','".$description."','".$state."','".$district."','".$supplier_part_number."','".$product_id."','".$quantity."','".$start_date."','".$delivery_date."','".$price_per_unit."','".$amount."','".$employee_id."','".$date."','".$ip_address."')" ;
			$retVal3 = $sign->FunctionQuery($qry3);
			
			$i=$i+1;
		}
		$qry4="Select cd.CompanyName,cc.value from tw_company_details cd Inner join tw_company_contact cc ON cd.ID=cc.company_id where cd.ID='".$supplier_id."' and cc.contact_field='".$settingValuePrimaryEmail."' and cc.public_visible='true'" ;
		$retVal4 = $sign->FunctionJSON($qry4);
		$decodedJSON = json_decode($retVal4);
		$CompanyName = $decodedJSON->response[0]->CompanyName;
		$email = $decodedJSON->response[1]->value;
		
		$replaceLink= $settingValueDocumentUserUrl."pgEmployeeLogIn.php";
		//
			$mailobj=new twMail();
			$subject = "EPR Purchase Order";
			$myfile = fopen($settingValueMailPath."pgEPRPONewRequest.html", "r");
			$message = fread($myfile,filesize($settingValueMailPath."pgEPRPONewRequest.html"));
			$message1 = str_replace("_Company_",$CompanyName,$message);
			$message2 = str_replace("_FROMCOMPANY_",$LogInCompanyName,$message1);
			$message3 = str_replace("_PATH_",$replaceLink,$message2);
		
			fclose($myfile);
			//
			 $mail_response = $mailobj->Mailsend($str,$subject,$message3,$email); 
			
		
		echo "Success";

	}
	else{
		echo "error";
	}
	
}


?>