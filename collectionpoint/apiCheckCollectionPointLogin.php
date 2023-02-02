<?php
session_start();
$username=md5($_POST["username"]);
$password=md5($_POST["password"]);		
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
$qry="select count(*) as cnt from tw_collection_point_login where username='".$username."' and password='".$password."'" ;
$retVal = $sign->Select($qry);
if($retVal==1){
	$qry1="select collection_point_id,status from tw_collection_point_login where username='".$username."' and password='".$password."'";
	$retVal1 = $sign->FunctionJSON($qry1);
	$decodedJSON1 = json_decode($retVal1);
	$cp_id = $decodedJSON1->response[0]->collection_point_id;
	$status = $decodedJSON1->response[1]->status;

		if($status=="On")
		{
			$_SESSION["cp_id"]=$cp_id;
			
			echo "Success";
		}
		else{
			echo "Blocked";
		} 

	 }else{
		echo "Error";
	}  
	
?>