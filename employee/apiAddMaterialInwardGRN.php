<?php
   
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$company_id=$sign->escapeString($_POST["company_id"]);
$txtsuppliername=$sign->escapeString($_POST["txtsuppliername"]);
$txtDate=$sign->escapeString($_POST["txtDate"]);
$txtremark=$sign->escapeString($_POST["txtremark"]);
$inward_id=$sign->escapeString($_POST["inward_id"]);
$created_by=$_SESSION["employee_id"];

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");

$qryStatus = "select status from tw_material_outward where id = '".$inward_id."'";
$retVal1Status = $sign->selectF($qryStatus,"status");


$qryGRN="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='GRN' and inward_id='".$inward_id."' ORDER BY inward_id ASC";
$retValGRN = $sign->SelectF($qryGRN,"cnt");

$qry1GRN="SELECT COUNT(*) as cnt from tw_material_inward_grn WHERE inward_id='".$inward_id."' ORDER BY inward_id ASC";
$retVal1GRN = $sign->SelectF($qry1GRN,"cnt");

if($retValGRN>0 || $retVal1GRN>0){
	$disabledGRN="1";
}
else{
	$disabledGRN="";
}


if($retVal1Status!=$settingValuePendingStatus){
	echo "Status";
}
else if($disabledGRN!=""){
	echo "Exist";
}
else{ 
	$qry1="Insert into tw_material_inward_grn(inward_id,company_id,supplier_id,date,remark,created_by,created_on,created_ip) values
	('".$inward_id."','".$company_id."','".$txtsuppliername."','".$txtDate."','".$txtremark."','".$created_by."','".$cur_date."','".$ip_address."')" ;
	$retVal1 = $sign->FunctionQuery($qry1,true);
	if($retVal1!=""){
		$material_inward_grn_id=$retVal1;
		
		$qry2="select id,description,quantity,rate,uom,tax,total from tw_material_inward_grn_temp where employee_id='".$created_by."' order by id Desc";
		$retVal2 = $sign->FunctionJSON($qry2);
		
		$qry3="Select count(*) as cnt from tw_material_inward_grn_temp where employee_id='".$created_by."' order by id Desc";
		$retVal3 = $sign->Select($qry3);

		$decodedJSON2 = json_decode($retVal2);
		$count = 0;
		$i = 1;
		$x=$retVal3;
		$it=1;
			while($x>=$i){
				
			$id = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			$description = $decodedJSON2->response[$count]->description;
			$count=$count+1;
			$quantity = $decodedJSON2->response[$count]->quantity;
			$count=$count+1;
			$rate = $decodedJSON2->response[$count]->rate;
			$count=$count+1;
			$uom = $decodedJSON2->response[$count]->uom;
			$count=$count+1;
			$tax = $decodedJSON2->response[$count]->tax;
			$count=$count+1;
			$total = $decodedJSON2->response[$count]->total;
			$count=$count+1;
		
				
			$qry3="Insert into tw_material_inward_grn_individual(material_inward_grn_id,employee_id,description,quantity,rate,tax,uom,total,created_by,created_on,created_ip) values('".$material_inward_grn_id."','".$created_by."','".$description."','".$quantity."','".$rate."','".$uom."','".$tax."','".$total."','".$created_by."','".$cur_date."','".$ip_address."')" ;
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