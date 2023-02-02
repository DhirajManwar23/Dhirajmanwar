<?php
session_start();
include_once "function.php";	
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$responsearray=array();

$qry2 = "Select id,product_name,amount_per_unit from tw_product_management where public_visible='true' ORDER by id,product_name ASC";
$retVal2 = $sign->FunctionJSON($qry2);
$qry3="Select count(*) as cnt from tw_product_management where public_visible='true'";
$retVal3 = $sign->Select($qry3);
	
$decodedJSON2 = json_decode($retVal2);
$count = 0;
$i1 = 1;
$x1=$retVal3;
while($x1>=$i1){
	$pid = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$product_name = $decodedJSON2->response[$count]->product_name;
	$count=$count+1; 
	$amount_per_unit = $decodedJSON2->response[$count]->amount_per_unit;
	$count=$count+1; 

	$option = "<option value='".$pid."'>".$product_name."[&#8377 ".$amount_per_unit."]</option>";
		  array_push($responsearray,$option);
		 		$i1=$i1+1;


}
		
 echo json_encode($responsearray); 

?>