<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();

$queryLogin=$_POST["valqueryLogin"];

$retVal2 = $sign->FunctionQuery($queryLogin);
if($retVal2=="Success"){
	echo "Success";
}
else{
	echo "error";
}
				
				
	
	
	
	
	
?>