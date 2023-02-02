<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$company_type=$sign->escapeString($_POST["company_type"]);
$company_icon=$sign->escapeString($_POST["company_icon"]);
$show_in_web=$sign->escapeString($_POST["show_in_web"]);
$show_in_app=$sign->escapeString($_POST["show_in_app"]);
$priority=$sign->escapeString($_POST["priority"]);
$description=$sign->escapeString($_POST["description"]);
$visibility=$sign->escapeString($_POST["visibility"]);
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["username"];
$requesttype=$_SESSION["requesttype"];
$requestid=$_SESSION["requestid"];

	if($requesttype=="add"){
		$qry="Select count(*) as cnt from tw_company_type_master where company_type='".$company_type."'";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			$qry1="insert into tw_company_type_master (company_type,company_icon,show_in_web,show_in_app,priority,description,visibility,created_by,created_on,created_ip) values('".$company_type."','".$company_icon."','".$show_in_web."','".$show_in_app."','".$priority."','".$description."','".$visibility."','".$created_by."','".$date."','".$ip_address."')";
			$retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1=="Success"){
				echo "Success";
			}
			else{
				echo "error";
			}	  
		}
	}
    else{
		$qry="Select count(*) as cnt from tw_company_type_master where company_type='".$company_type."' and id!='".$requestid."'";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			$qry1="Update tw_company_type_master set company_type='".$company_type."',company_icon='".$company_icon."',show_in_web='".$show_in_web."',show_in_app='".$show_in_app."',priority='".$priority."',description='".$description."',visibility='".$visibility."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
			$retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1=="Success"){
				echo "Success";
			}
			else{
				echo "error";
			}
		}	
} 	
	
	
?>
