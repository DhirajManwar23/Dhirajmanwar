<?php
// Include class definition
include_once("function.php");
$sign=new Signup();

$date=date('Y-m-d', time());
$t_month = date('m', strtotime($date));
$t_year = date('Y', strtotime($date));
//$totaldays = cal_days_in_month(CAL_GREGORIAN, $t_month, $t_year);
$totaldays = date('d', strtotime($date));
	
	$data = array();
	$i = 1;
	$x=$totaldays;
	$table="";
	while($x>=$i){
		$newDate = $t_year."-".$t_month."-".$i;
		$qry="SELECT IFNULL (SUM(quantity), 0) as quantity from tw_mix_waste_collection where collection_date_time LIKE '".$newDate." %'";
		$quantity = $sign->SelectF($qry,"quantity");
		$data[] = [
			'datadays' => $i,
			'dataquantity' => $quantity,
			//'datademo' => 0.00,
		];
		
		$i=$i+1;
	}
	echo json_encode($data);
?>
