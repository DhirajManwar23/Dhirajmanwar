<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$settingValueEmployeeImagePathVerification= $commonfunction->getSettingValue("EmployeeImagePathVerification");
$query=$_POST["valquery"];
$value=$_POST["empvalue"];
$Imagename=$_POST["Imagename"];

$retVal1 = $sign->FunctionQuery($query);
	if($retVal1=="Success"){
		$path=$settingValueEmployeeImagePathVerification.$value."/".$Imagename;
		if (!unlink($path)) {
		echo "error";
		}
		else {
			echo "Success";
		}
	 }
else{
	echo "error";
}
?>
