<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$company_id = $_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$data = array();
	
	$qryState = "SELECT DISTINCT(dispatched_state) as state FROM tw_temp";
	echo $retValState = $sign->FunctionJSON($qryState);
	
	$qryStatecount = "SELECT count(DISTINCT(dispatched_state)) as cntstate FROM tw_temp";
	echo $retValStatecount = $sign->FunctionJSON($qryStatecount);
	
	
	$decodedJSON2 = json_decode($retValState);
	$count = 0;
	$i = 1;
	$x=$retValStatecount;
	while($x>=$i){
		
		$state = $decodedJSON2->response[$count]->state;
		$count=$count+1;
		
		$qry1="SELECT IFNULL(SUM(plant_quantity),0) as scount FROM tw_temp WHERE dispatched_state='".$state."' and status='".$settingValuePendingStatus."'";
	
		$value = $sign->selectF($qry1,"scount");
		
		$data[] = [
			'months' => $months,
			'sum' => $value
		];
		
		$i=$i+1;
	}
	//echo json_encode($data);
?>
