<?php
session_start();
$username=md5($_POST["username"]);
$password=md5($_POST["password"]);		
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
$settingValueType=$commonfunction->getSettingValue("MasterAdmin"); 
$settingValueType=$sign->escapeString($settingValueType);
$qry="select count(*) as cnt from tw_admin_login where Username='".$username."' and Password='".$password."'" ;
$retVal = $sign->Select($qry);
if($retVal==1){
	$qry1="select admin_id,Type,Status from tw_admin_login where Username='".$username."' and Password='".$password."'";
	$retVal1 = $sign->FunctionJSON($qry1);
	$decodedJSON1 = json_decode($retVal1);
	$admin_id = $decodedJSON1->response[0]->admin_id;
	$Type = $decodedJSON1->response[1]->Type;
	$Status = $decodedJSON1->response[2]->Status;
	if($Type==$settingValueType){		
		if($Status=="On")
		{
			$_SESSION["admin_id"]=$admin_id;
			$_SESSION["admin_type"]=$Type;
			$_SESSION["username"]=$username;
			echo "Success";
		}
		else{
			echo "Blocked";
		} 
	
	}else{
		if($Status=="On")
		{
			$_SESSION["admin_id"]=$admin_id;
			$_SESSION["admin_type"]=$Type;
			$_SESSION["username"]=$username;
			echo "Success";
		}
		else{
			echo "Blocked";
		} 
	}
}
else{
	echo "Invalid";

}  
	
?>