<?php
session_start();
// Include class definition
include_once("function.php");
include_once "commonFunctions.php";
$sign=new Signup();
$agent_id = $_SESSION["agent_id"];
$date=date('Y-m-d', time());
$commonfunction=new Common();
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$t_month = date('m', strtotime($date));
$t_year = date('Y', strtotime($date));
//$totaldays = cal_days_in_month(CAL_GREGORIAN, $t_month, $t_year);
$totaldays = date('d', strtotime($date));
	
	$data = array();
	$i = 1;
	
	$x=$totaldays;
	$table="";
	$cnt ="";
	while($x>=$i){
		$newDate = $t_year."-".$t_month."-".$i;
		$qry="SELECT IFNULL(SUM(quantity), 0) as quantity from tw_mix_waste_collection where collection_date_time LIKE '".$newDate." %' and agent_id='".$agent_id."' and status='".$settingValueCompletedStatus."'";
		$quantity = $sign->SelectF($qry,"quantity");
		
		$qrycollectioncount="SELECT count(*) as cnt from tw_mix_waste_collection where collection_date_time LIKE '".$newDate." %' and agent_id='".$agent_id."' and status='".$settingValueCompletedStatus."'";
		$Collectioncount = $sign->SelectF($qrycollectioncount,"cnt");
		
		$data[] = [
			'datadays' => $i,
			'dataquantity' => $quantity,
			'datacollectioncount' => $Collectioncount,
			//'datademo' => 0.00,
		];
		
		$i=$i+1;
	}
	echo json_encode($data);
?>
