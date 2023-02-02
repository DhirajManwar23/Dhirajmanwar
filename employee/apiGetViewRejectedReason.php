<?php 
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
include_once "mailFunction.php";

$valquery=$_REQUEST["valquery"];

$retVal1 = $sign->FunctionJSON($valquery);
$decodedJSON2 = json_decode($retVal1);
$reason = $decodedJSON2->response[0]->reason;
echo $reason;
			 
			 
?>