<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$company_id = $_SESSION["company_id"];
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");

$qry="select swm.id,swm.name,me.quantity from tw_segregation_waste_type_master swm INNER JOIN tw_mixwaste_manual_entry me ON me.waste_type=swm.id where swm.visibility='true' and me.entry_date='".$cur_date."' and quantity!='0.00' order by swm.priority,me.id asc";
$retVal = $sign->FunctionJSON($qry);
$qry1="select count(*) as cnt from tw_segregation_waste_type_master swm INNER JOIN tw_mixwaste_manual_entry me ON me.waste_type=swm.id where swm.visibility='true' and me.entry_date='".$cur_date."' and quantity!='0.00' order by swm.priority,me.id asc";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
while($x>=$i){
	 $id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$name = $decodedJSON2->response[$count]->name;
	$count=$count+1;
	$quantity = $decodedJSON2->response[$count]->quantity;
	$count=$count+1;
	$a=array();
	$b=array();
	array_push($a,$name);
	array_push($b,$quantity);
	$data[] = [
		'name' => $a,
		'quantity' => $b,
	];
		
	$i=$i+1;
}
echo json_encode($data); 
?>