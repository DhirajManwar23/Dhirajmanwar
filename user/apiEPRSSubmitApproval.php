<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

date_default_timezone_set('Asia/Kolkata');
$current_date = date('m/d/Y', time());
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");

$ip_address= $commonfunction->getIPAddress();
$created_by=$_SESSION["company_id"];

$po_id=$_POST["valpo_id"];
$id=$_POST["id"];
$valstateid=$_POST["valstateid"];
$data = array();
$qrypo = "SELECT company_id, supplier_id FROM tw_po_info WHERE id='".$po_id."'";
$DocValpoD = $sign->FunctionJSON($qrypo);
$decodedJSON = json_decode($DocValpoD);
$company_id = $decodedJSON->response[0]->company_id; 
$supplier_id = $decodedJSON->response[1]->supplier_id; 

$qry = "SELECT start_date, delivery_date FROM `tw_po_details` WHERE po_id='".$po_id."' group by start_date, delivery_date";
$DocValpo = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($DocValpo);
$start_date = $decodedJSON->response[0]->start_date; 
$delivery_date = $decodedJSON->response[1]->delivery_date; 

$start=(new DateTime($start_date))->modify('first day of this month');
$end=(new DateTime($current_date))->modify('first day of next month');
$interval=DateInterval::createFromDateString('1 month');
$period=new DatePeriod($start, $interval, $end);
$table="";
$srno=1;
array_push($data,$id);
foreach ($period as $dt){
    $date="".$dt->format("Y-m");
	
	if (in_array($srno,$data))
	{
		
		$qry2 = "SELECT count(*) as cnt from tw_epr_approval where month='".$dt->format("M Y")."' and po_id='".$po_id."'";
		$retVal2 = $sign->select($qry2);
		if($retVal2>0){
			$qry1="update tw_epr_approval set supplier_status='".$settingValueApprovedStatus."', company_status='".$settingValuePendingStatus."',modified_by='".$created_by."',modified_on='".$current_date."',modified_ip='".$ip_address."' where month='".$dt->format("M Y")."'";
			$retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1=="Success"){
				echo "Success";
			}
			else {
				echo "error";
			}
		}
		else{
			$qry1="insert into tw_epr_approval(po_id ,company_id ,supplier_id ,month,company_status,supplier_status,state,created_by,created_on,created_ip) values('".$po_id."','".$company_id."','".$supplier_id."','".$dt->format("M Y")."','".$settingValuePendingStatus."','".$settingValueApprovedStatus."','".$valstateid."','".$created_by."','".$current_date."','".$ip_address."')";
			$retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1=="Success"){
				echo "Success";
			}
			else {
				echo "error";
			}
		} 
	}
	$srno++;
}
?>