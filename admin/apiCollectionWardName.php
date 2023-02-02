<?php   
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
include_once "mailFunction.php";
$sign=new Signup();
$commonfunction=new Common();
$cp_id = $_SESSION["cp_id"];
echo $cityname=$sign->escapeString($_POST["cityname"]);
echo $valresponse=$sign->escapeString($_POST["valresponse"]);

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

echo $qry = "SELECT city,ward FROM tw_collection_point_master WHERE id = '".$cp_id."' ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
echo $city = $decodedJSON->response[0]->city; 
$ward = $decodedJSON->response[1]->ward; 

if($valresponse=="Valid"){
	$QuerySelWard="SELECT id,ward_name FROM tw_ward_master WHERE city_id=(select id from tw_city_master where city_name='".$cityname."')";
	echo $retVal1 = $sign->FunctionOption($QuerySelWard,$city,'ward_name','id');
}
else{
	echo $ValueSelWard = "<option value='Other'>Other</option>";
	
}
?> 