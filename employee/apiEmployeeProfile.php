<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();

$query=$_POST["valquery"];
$querycount=$_POST["valquerycount"];
	
$retVal = $sign->Select($querycount);
if($retVal>0){
	echo "Exist";
}
else
{	
	$retVal1 = $sign->FunctionQuery($query);
	if($retVal1=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
}
		
				
				
	
	
	
	
	
?>
