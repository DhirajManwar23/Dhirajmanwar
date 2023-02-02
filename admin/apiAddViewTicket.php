<?php
	session_start();
	// Include class definition
	include("function.php");
	$sign=new Signup();
	
	$CompanyName=$sign->escapeString($_POST["CompanyName"]);
	$date=$sign->escapeString($_POST["date"]);
	$ticket_attachment=$sign->escapeString($_POST["ticket_attachment"]);
	
	$ip_address= $commonfunction->getIPAddress();
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["companyusername"];
	
	
	$qry="Select count(*) as cnt from tw_ticket_system where subject='".$subject."'";
	
	$retVal = $sign->Select($qry);
	if($retVal>0){
		echo "Exist";
	}
	/*else
	{	
		$qry1="insert into tw_ticket_system (company_name,date,ticket_attachment,created_by,created_on,created_ip) values('".$company_name."','".$date."','".$ticket_attachment."','".$created_by."','".$date."','".$ip_address."')";
		$retVal1 = $sign->FunctionQuery($qry1);
		if($retVal1=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
   
	}*/

	

	
?>
