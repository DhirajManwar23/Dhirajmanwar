<?php
	session_start();
	// Include class definition
	include("function.php");
	$sign=new Signup();
	
	$query=$_POST["valquery"];
	
	$retVal1 = $sign->FunctionQuery($query);
	if($retVal1=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
				
				
	
	
	
	
	
?>
