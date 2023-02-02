<?php 
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
include_once "mailFunction.php";

$id=$_REQUEST["id"];

$qry1="Select reason from tw_mix_waste_collection where id='".$id."'";
$retVal1 = $sign->FunctionJSON($qry1);
$decodedJSON2 = json_decode($retVal1);
$reason = $decodedJSON2->response[0]->reason;
echo $reason;
			 
			 
?>