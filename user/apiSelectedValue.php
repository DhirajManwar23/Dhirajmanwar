<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$selectedTnC=$_POST["selectedTnC"];
$company_id = $_SESSION["company_id"];

$qry1="select tnc_value from tw_tnc where tnc_for='".$selectedTnC."' and company_id='".$company_id."' "; 
$retVal1 = $sign->SelectF($qry1,"tnc_value");
echo $retVal1;
		
?>
