<?php
session_start();
// Include class definition
include("function.php");

include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
$agent_id = $_SESSION["agent_id"];

$mobile_number=$sign->escapeString($_POST["mobile_number"]);
$alt_number=$sign->escapeString($_POST["alt_number"]);
$email=$sign->escapeString($_POST["email"]);
$valcreated_by=$sign->escapeString($_POST["valcreated_by"]);
$valcreated_on=$sign->escapeString($_POST["valcreated_on"]);
$valcreated_ip=$sign->escapeString($_POST["valcreated_ip"]);

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

if($email==""){
	$qryupdateagent="update tw_agent_details set mobilenumber='".$mobile_number."', alternative_mobilenumber='".$alt_number."',email='".$email."', modified_by='".$agent_id."', modified_on='".$valcreated_on."', modified_ip='".$valcreated_ip."' where id='".$agent_id."'";
	$retValupdateagent = $sign->FunctionQuery($qryupdateagent);
	if($retValupdateagent=="Success"){
		echo "Success"; 
	
	}
	else{
		echo "error";
	}
}
else{
	
	$qrycntemail="Select count(*) as cnt from tw_agent_details where email = '".$email."' and id!='".$agent_id."'";
	$countemail = $sign->Select($qrycntemail);
	if($countemail>0){
		echo "EmailExists";
	}
	else{
		$qryupdateagent="update tw_agent_details set mobilenumber='".$mobile_number."', alternative_mobilenumber='".$alt_number."',email='".$email."', modified_by='".$agent_id."', modified_on='".$valcreated_on."', modified_ip='".$valcreated_ip."' where id='".$agent_id."'";
		$retValupdateagent = $sign->FunctionQuery($qryupdateagent);
		if($retValupdateagent=="Success"){
			echo "Success"; 
		
		}
		else{
			echo "error";
		} 
	}
	
}
?>
