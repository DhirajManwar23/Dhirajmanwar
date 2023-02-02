<?php
session_start();
	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$id = $_POST["id"];
$type = $_POST["type"];

$qry="select comment,segregation_comment from tw_mix_waste_lot_info where id='".$id."' order by id asc";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$comment = $decodedJSON->response[0]->comment;
$segregation_comment = $decodedJSON->response[1]->segregation_comment;

if($type=="comment"){
	echo $comment;
}
else{
	echo $segregation_comment;
}
	


?>
