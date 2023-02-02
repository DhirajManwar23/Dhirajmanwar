<?php
// Include class definition
include("function.php");
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$AgentImagePathVerification= $commonfunction->getSettingValue("AgentImagePathVerification");
$query=$_POST["valquery"];
$value=$_POST["email"];
$Imagename=$_POST["Imagename"];

$retVal1 = $sign->FunctionQuery($query);
	if($retVal1=="Success"){
		$path=$AgentImagePathVerification.$value."/".$Imagename;
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
