<?php
session_start();
include("function.php");
include("commonFunctions.php");
include("mailFunction.php");

$commonfunction=new Common();
$sign=new Signup();
$materialtype=$_POST['materialtype'];
$quantityvalue=$_POST['quantityvalue'];
$valmix_waste_lot_id=$_POST['valmix_waste_lot_id'];
$txtComment=$_POST['txtComment'];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$ip_address= $commonfunction->getIPAddress();
$company_id = $_SESSION["company_id"];


/* $qry5="select quantity from tw_mix_waste_collection where id='".$valmix_waste_id."' order by id desc";
$qryquantity = $sign->SelectF($qry5,"quantity"); */


$i = 0;
$x=count($_POST['materialtype']);
$x = $x-1;
$valquery = "";
$valTotalquantity = 0;

$qry1="Update tw_mix_waste_lot_info set segregation_comment='".$txtComment."', status='".$settingValueCompletedStatus."'  ,modified_by='".$company_id."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$valmix_waste_lot_id."'";
$retVal1 = $sign->FunctionQuery($qry1); 
if($retVal1=="Success"){
	while($x>=$i){
	
		$qry3="Insert into tw_mix_waste_collection_details(mix_waste_lot_id,waste_type,quantity,status,created_by,created_on,created_ip) values ('".$valmix_waste_lot_id."','".$materialtype[$i]."','".$quantityvalue[$i]."','".$settingValuePendingStatus."','".$company_id."','".$date."','".$ip_address."')";
		$retVal3 = $sign->FunctionQuery($qry3); 
		if($retVal3=="Success"){
			$valquery = "Success";
		}
		else{
			$valquery = "error";
		}
		
		$i=$i+1;
		
	}
	if($valquery=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
}
else{
	echo "error";
} 


?>
