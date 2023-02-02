<?php
session_start();
$username=md5($_POST["username"]);
$password=md5($_POST["password"]);		
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
/* $settingValueType=$commonfunction->getSettingValue("MasterAdmin"); 
$settingValueType=$sign->escapeString($settingValueType); */
$qry="select count(*) as cnt from tw_agent_login where username='".$username."' and password='".$password."'" ;
$retVal = $sign->Select($qry);
if($retVal==1){
	$qry1="select id,status from tw_agent_login where username='".$username."' and password='".$password."'";
	$retVal1 = $sign->FunctionJSON($qry1);
	$decodedJSON1 = json_decode($retVal1);
	$agent_id = $decodedJSON1->response[0]->id;
	$status = $decodedJSON1->response[1]->status;
	
	 /*  if($Type==$settingValueType){		  */
		if($status=="On")
		{
			$_SESSION["agent_id"]=$agent_id;
			
			echo "Success";
		}
		else{
			echo "Blocked";
		} 
	
	 }else{
		if($status=="On")
		{
			$_SESSION["agent_id"]=$agent_id;
			
			echo "Success";
		}
		else{
			echo "Blocked";
		} 
	}  
/* }
else{
	echo "Invalid";

}   */
	
?>