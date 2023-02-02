<?php
	// Include class definition
	include("function.php");
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
