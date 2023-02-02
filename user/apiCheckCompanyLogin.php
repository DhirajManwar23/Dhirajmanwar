<?php
session_start();
$unenc_email=$_POST["username"];
$username=md5($unenc_email);
$password=md5($_POST["password"]);
//$rememberme=$_POST["rememberme"];
// Include class definition
require "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$settingValueEPRServices=$commonfunction->getSettingValue("EPRServices");

$qry="select count(*) as cnt from tw_company_login where Username='".$username."' and Password='".$password."'" ;
$sign=new Signup();
$retVal = $sign->Select($qry);
$queryEPRLogin="select cd.CompanyType from tw_company_details cd INNER JOIN tw_company_login cl ON cl.company_id=cd.ID where cl.Username='".$username."' and cl.Password='".$password."'";
$retValEPRLogin = $sign->SelectF($queryEPRLogin,"CompanyType");

if($retVal==1){
	$qry1="select Status from tw_company_login where Username='".$username."' and Password='".$password."'" ;
	$retVal1 = $sign->SelectF($qry1,"Status");
	if($retVal1=="On")
	{
		$qry2="select company_id from tw_company_login where Username='".$username."'  and Password='".$password."'";
		$retVal2 = $sign->SelectF($qry2,"company_id");
		/* if($rememberme==1){
			$cookie_name = "twuser";
			$cookie_value = $username;
			setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
		} */
		$_SESSION["companyusername"]=$unenc_email;
		$_SESSION["company_id"]=$retVal2;
		if($retValEPRLogin == $settingValueEPRServices){
			$_SESSION["company_type"]="EPR";
			echo "EPRSERVICES";
		}
		else{
			$_SESSION["company_type"]="Company";
			echo "Success";
		}
			
	}
	else{
		echo "Blocked";
	}
	
}
else{
	echo "Error";
}
	
?>