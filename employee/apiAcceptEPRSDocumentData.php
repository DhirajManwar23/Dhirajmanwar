<?php
session_start();
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("d-m-Y h:i:sa");
$company_id = $_SESSION["company_id"];
$employee_id = $_SESSION["employee_id"];

$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$ip_address= $commonfunction->getIPAddress();
$str=$_POST['str'];
$type=$_POST['type'];
$po_id=$_POST['po_id'];
//print_r($str);


if($type=="check"){
	$arrStr = array();
	$arrStr = explode(",",$str);
	for($i=0; $i<count($arrStr); $i++)
	{ 
		$arrStrInner = array();
		$arrStrInner = explode("/",$arrStr[$i]);
		
		$EPR_id=$arrStrInner[0];
		$updateQry="UPDATE tw_temp SET status='".$settingValueCompletedStatus."',modified_by='".$employee_id."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$EPR_id."'";
		$retVal4 = $sign->FunctionQuery($updateQry);
		if($retVal4!="Success"){
			echo "error";
		}
	}
	
	$qry="SELECT total_quantity FROM tw_po_info where id='".$po_id."'";
	$total_quantity=$sign->SelectF($qry,"total_quantity"); 

	$qry1="SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as plant_quantity FROM tw_temp where po_id='".$po_id."' and status='".$settingValueCompletedStatus."'";
	$total_quantity_Assign=$sign->SelectF($qry1,"plant_quantity"); 

	if($total_quantity_Assign>=$total_quantity){
		$updateQry1= "Update  tw_po_info set status = '".$settingValueCompletedStatus."' where id='".$po_id."' ";
		$retVal2 = $sign->FunctionQuery($updateQry1);
		if($retVal2!="Success" ){
			echo "error";
		}
	}
	echo "Success";
}
else{
		$updateQry="UPDATE tw_temp SET status='".$settingValueCompletedStatus."',modified_by='".$employee_id."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id='".$str."'";
		$retVal4 = $sign->FunctionQuery($updateQry);
		if($retVal4=="Success"){
			$qry="SELECT total_quantity FROM tw_po_info where id='".$po_id."'";
			$total_quantity=$sign->SelectF($qry,"total_quantity"); 

			$qry1="SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as plant_quantity FROM tw_temp where po_id='".$po_id."' and status='".$settingValueCompletedStatus."'";
			$total_quantity_Assign=$sign->SelectF($qry1,"plant_quantity"); 

			if($total_quantity_Assign>=$total_quantity){
				$updateQry1= "Update  tw_po_info set status = '".$settingValueCompletedStatus."' where id='".$po_id."' ";
				$retVal2 = $sign->FunctionQuery($updateQry1);
				if($retVal2!="Success" ){
					echo "error";
				}
			}
			echo "Success";
		}
		else{
			echo "error";
		}
		
}

 
?>