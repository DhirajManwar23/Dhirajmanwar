<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sender_id=$_POST['sender_id'];
$company_id = $_SESSION["company_id"];
$VerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$qry="UPDATE tw_company_network SET connect_status='".$VerifiedStatus."'  WHERE sender_id='".$sender_id."' AND receiver_id='".$company_id."'";;
$sign=new Signup();
$retVal = $sign->FunctionQuery($qry);
if($retVal=="Success"){
	echo "Success";
}
else{
	echo "error";
}

?>