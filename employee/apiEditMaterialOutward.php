<?php
   
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();

$customer_id=$sign->escapeString($_POST["customer_id"]);
$material_name=$sign->escapeString($_POST["material_name"]);
$final_total_amout=$sign->escapeString($_POST["final_total_amout"]);
$valTotalQuantity=$sign->escapeString($_POST["valTotalQuantity"]);
$hdnOrderID=$sign->escapeString($_POST["hdnOrderID"]);
$company_address=$sign->escapeString($_POST["company_address"]);
$billto=$sign->escapeString($_POST["billto"]);
$shiptto=$sign->escapeString($_POST["shiptto"]);
$txtVehicle_Name=$sign->escapeString($_POST["txtVehicle_Name"]);
$created_by=$_SESSION["employee_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$qry="Select count(*) as cnt from tw_material_outward_temp where employee_id='".$created_by."'" ;
$retVal = $sign->Select($qry);

$qrycount="Select count(*) as cnt from tw_material_outward where id = '".$hdnOrderID."' AND status!='".$settingValuePendingStatus."'";
$retValcount = $sign->Select($qrycount);


if($retVal==0){
	echo "Blank";
}
else if($retValcount==1){
	echo "Exist";
}
else{
	
	$qry1="Delete from tw_material_outward_individual where material_outward_id='".$hdnOrderID."' ";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		$qry2="Update tw_material_outward set customer_id='".$customer_id."',final_total_amout='".$final_total_amout."' ,company_address='".$company_address."',bill_to='".$billto."',ship_to='".$shiptto."',vehicle_id='".$txtVehicle_Name."',total_quantity='".$valTotalQuantity."' where id='".$hdnOrderID."' ";
		$retVal2 = $sign->FunctionQuery($qry2);
		if($retVal1=="Success"){

			$qry3="select material_id,quantity,hsn,tax,rate,total from tw_material_outward_temp where employee_id='".$created_by."' order by id Asc";
			$retVal3 = $sign->FunctionJSON($qry3);
			$qry4="Select count(*) as cnt from tw_material_outward_temp where employee_id='".$created_by."' order by id Asc";
			$retVal4 = $sign->Select($qry4);
			
			$decodedJSON2 = json_decode($retVal3);
			$count = 0;
			$i = 1;
			$x=$retVal4;
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
				
				$qry5="Insert into tw_material_outward_individual(material_outward_id,employee_id,material_id,quantity,tax,rate,total,created_by,created_on,created_ip) values('".$hdnOrderID."','".$created_by."','".$material_id."','".$quantity."','".$tax."','".$rate."','".$total."','".$created_by."','".$cur_date."','".$ip_address."')" ;
				$retVal5 = $sign->FunctionQuery($qry5);
				
				
				$i=$i+1;
		}
		$qry6="delete from tw_material_outward_temp where employee_id='".$created_by."'";
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