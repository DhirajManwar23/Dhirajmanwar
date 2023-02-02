<?php
session_start();
// Include class definition

include_once("function.php");
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$agent_id = $_SESSION["agent_id"];
	$qry="SELECT m.month, IFNULL (SUM(quantity), 0) as quantity
	FROM (
	SELECT 'January' AS
	MONTH
	UNION SELECT 'February' AS
	MONTH
	UNION SELECT 'March' AS
	MONTH
	UNION SELECT 'April' AS
	MONTH
	UNION SELECT 'May' AS
	MONTH
	UNION SELECT 'June' AS
	MONTH
	UNION SELECT 'July' AS
	MONTH
	UNION SELECT 'August' AS
	MONTH
	UNION SELECT 'September' AS
	MONTH
	UNION SELECT 'October' AS
	MONTH
	UNION SELECT 'November' AS
	MONTH
	UNION SELECT 'December' AS
	MONTH
	) AS m
	LEFT JOIN tw_mix_waste_collection ON m.month = MONTHNAME(collection_date_time) and agent_id='".$agent_id."' and status='".$settingValueCompletedStatus."'
	group by m.month
	order by FIELD(m.month,'January','February','March','April','May','June','July','August','September','October','November','December');";
	$data = array();
	$retVal = $sign->FunctionJSON($qry);
	
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=12;
	$table="";
	while($x>=$i){
		$month = $decodedJSON2->response[$count]->month;
		$count=$count+1;
		$quantity = $decodedJSON2->response[$count]->quantity;
		$count=$count+1;
		$data[] = [
			'month' => $month,
			'sum' => $quantity,
		];
		
		$i=$i+1;
	}
	echo json_encode($data);
?>
