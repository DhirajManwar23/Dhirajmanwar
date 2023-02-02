<?php
// Include class definition
include_once("function.php");
$sign=new Signup();
$valquery=$_POST["valquery"];
$retVal1 = $sign->FunctionQuery($valquery);
if($retVal1=="Success"){
	echo "Success";
}
else{
	echo "error";
}	
?>
