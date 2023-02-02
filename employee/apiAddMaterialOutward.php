<?php
   
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
include_once "mailFunction.php";
$sign=new Signup();
$commonfunction=new Common();

$customer_id=$sign->escapeString($_POST["customer_id"]);
$company_address=$sign->escapeString($_POST["company_address"]);
$billto=$sign->escapeString($_POST["billto"]);
$shiptto=$sign->escapeString($_POST["shiptto"]);
$supplier_geo_location=$sign->escapeString($_POST["supplier_geo_location"]);
$final_total_amout=$sign->escapeString($_POST["final_total_amout"]);
$vehicle_id=$sign->escapeString($_POST["vehicle_id"]);
$po_id=$sign->escapeString($_POST["po_id"]);
$valTotalQuantity=$sign->escapeString($_POST["valTotalQuantity"]);
$created_by=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");

$settingValuePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$qry="Select count(*) as cnt from tw_material_outward_temp where employee_id='".$created_by."'" ;
$retVal = $sign->Select($qry);
if($retVal==0){
	echo "Blank";
}
else{
	$qry1="Insert into tw_material_outward(total_quantity,po_id,company_id,employee_id,customer_id,company_address,bill_to,ship_to,supplier_geo_location,final_total_amout,status,vehicle_id,created_by,created_on,created_ip) values('".$valTotalQuantity."','".$po_id."','".$company_id."','".$created_by."','".$customer_id."','".$company_address."','".$billto."','".$shiptto."','".$supplier_geo_location."','".$final_total_amout."','".$settingValuePendingStatus."','".$vehicle_id."','".$created_by."','".$cur_date."','".$ip_address."')" ;
	$retVal1 = $sign->FunctionQuery($qry1,true);
	if($retVal1!=""){
		$material_outward_id=$retVal1;
		
		$qry2="select material_id,quantity,hsn,tax,rate,total from tw_material_outward_temp where employee_id='".$created_by."' order by id Asc";
		$retVal2 = $sign->FunctionJSON($qry2);
		
		$qry3="Select count(*) as cnt from tw_material_outward_temp where employee_id='".$created_by."' order by id Asc";
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
			
			$qry3="Insert into tw_material_outward_individual(material_outward_id,employee_id,material_id,quantity,tax,rate,total,created_by,created_on,created_ip) values('".$material_outward_id."','".$created_by."','".$material_id."','".$quantity."','".$tax."','".$rate."','".$total."','".$created_by."','".$cur_date."','".$ip_address."')" ;
			$retVal3 = $sign->FunctionQuery($qry3);
			$i=$i+1;
		}
		$qry4="Select cd.CompanyName,cc.value from tw_company_details cd Inner join tw_company_contact cc ON cd.ID=cc.company_id where cd.ID='".$customer_id."' and cc.contact_field='".$settingValuePrimaryEmail."' and cc.public_visible='true'" ;
		$retVal4 = $sign->FunctionJSON($qry4);
		$decodedJSON = json_decode($retVal4);
		$CompanyName = $decodedJSON->response[0]->CompanyName;
		$email = $decodedJSON->response[1]->value;
		
			//--
			$mailobj=new twMail();
			$subject = "Material Outward";
			$myfile = fopen($settingValueMailPath."pgMONewRequest.html", "r");

			$message = fread($myfile,filesize($settingValueMailPath."pgMONewRequest.html"));

			$message = str_replace("_Company_",$CompanyName,$message);
			fclose($myfile);
			//--
			$mail_response = $mailobj->Mailsend($email,$subject,$message); 
			
			 //--Mail function end (User)
		echo "Success";

	}
	else{
		echo "error";
	}
}

?>