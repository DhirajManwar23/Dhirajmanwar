<?php
	session_start();
	// Include class definition
	include("function.php");
	$sign=new Signup();
	$Description=$_POST["Description"];
	include("commonFunctions.php");
	$commonfunction=new Common();
	$ip_address= $commonfunction->getIPAddress();

	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["companyusername"];
	$company_id=$_SESSION["company_id"];
			
		$qry1="Update tw_company_details set Description='".$Description."' where ID='".$company_id."' "; 
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
				
				
	
	
	
	
	
?>
