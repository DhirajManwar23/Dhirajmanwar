<?php
session_start();
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$company_id = $_SESSION["company_id"];
$employee_id = $_SESSION["employee_id"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$ip_address= $commonfunction->getIPAddress();
$str=$_POST['str'];
$txtActualQuantity=$_POST['txtActualQuantity'];
$txtComment=$_POST['txtComment'];
//print_r($str);
$arrStr = array();
$arrStr = explode(",",$str);
$valTotalQuantity=0.00;
$selectedWMId="";
for($i=0; $i<count($arrStr); $i++)
{ 
	$arrStrInner = array();
	$arrStrInner = explode("/",$arrStr[$i]);
	$total_quantity=$arrStrInner[1];
	$valTotalQuantity = $valTotalQuantity+$total_quantity;
	$selectedWMId.=$arrStrInner[0].",";
}

 $insertQry="insert into  tw_mix_waste_lot_info (company_id,employee_id,total_quantity,actual_quantity,comment,status,created_by,created_on,created_ip) values('".$company_id."','".$employee_id."','".$valTotalQuantity."','".$txtActualQuantity."','".$txtComment."','".$settingValuePendingStatus."','".$employee_id."','".$cur_date."','".$ip_address."')";
 $retVal = $sign->FunctionQuery($insertQry,true);
 if($retVal!=""){
	 for($i=0; $i<count($arrStr); $i++)
		{ 
			$arrStrInner = array();
			$arrStrInner = explode("/",$arrStr[$i]);
			
			$mix_waste_collection_id=$arrStrInner[0];
			
			$insertQry1="insert into  tw_mix_waste_lot_details (mix_waste_collection_id,	mix_waste_lot_id,created_by,created_on,created_ip) values('".$mix_waste_collection_id."','".$retVal."','".$employee_id."','".$cur_date."','".$ip_address."')";
			$retVal2 = $sign->FunctionQuery($insertQry1);
			if($retVal2!="Success"){
				echo "error";
			}
			$updateQry="UPDATE tw_mix_waste_collection SET status='".$settingValueCompletedStatus."'  where id='".$arrStr[$i]."'";
			$retVal4 = $sign->FunctionQuery($updateQry);
			if($retVal4!="Success"){
				echo "error";
			}
		}
		//---Send Notification Start
		$selectedWMId = substr($selectedWMId, 0, -1);
		$qry="select agent_id, sum(quantity) as TotalSum from tw_mix_waste_collection where id in (".$selectedWMId.") group by agent_id";
		$qry1="select count(DISTINCT(agent_id)) as cnt from tw_mix_waste_collection where id in (".$selectedWMId.")";
		$retVal = $sign->FunctionJSON($qry);
		$qryCnt = $sign->Select($qry1);

		$decodedJSON2 = json_decode($retVal);
		$i = 1;
		$x=$qryCnt;
		$count = 0;
		while($x>=$i){
			$agent_id = $decodedJSON2->response[$count]->agent_id;
			$count=$count+1;
			$TotalSum = $decodedJSON2->response[$count]->TotalSum;
			$count=$count+1;
			
			$qrySelect = "select agent_name,devicetoken from tw_agent_details where id='".$agent_id."'";
			$ValSel = $sign->FunctionJSON($qrySelect);
			$decodedJSON = json_decode($ValSel);
			$agent_name = $decodedJSON->response[0]->agent_name; 
			$DeviceToken = $decodedJSON->response[1]->devicetoken; 
			$msg = "Dear ".$agent_name." ".$TotalSum."kg Waste has been collected.";
			$sendGCM = $commonfunction->sendGCM('Waste Collection', $msg, $DeviceToken);
			$i=$i+1;
		}
		//---Send Notification End
		echo "Success";
 }
 else{
	 echo "error";
 } 
?>