<?php
	session_start();
	// Include class definition
	include("function.php");
	$sign=new Signup();
	//$Description=$sign->escapeString($_POST["Description"]);
	$Description=$_POST["Description"];
	
	
	//whether ip is from share internet
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip_address = $_SERVER['HTTP_CLIENT_IP'];
	}
	//whether ip is from proxy
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  {
		$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	//whether ip is from remote address
	else{
		$ip_address = $_SERVER['REMOTE_ADDR'];
	}
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	$employee_id=$_SESSION["employee_id"];
			
		$qry1="Update tw_employee_registration set Description='".$Description."' where ID='".$employee_id."' "; 
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
				
				
	
	
	
	
	
?>
