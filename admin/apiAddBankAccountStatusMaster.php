<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$bank_account_status_value=$sign->escapeString($_POST["bank_account_status_value"]);
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
		$qry="Select count(*) as cnt from tw_bank_account_status_master where bank_account_status_value='".$bank_account_status_value."'";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			$qry1="insert into tw_bank_account_status_master (bank_account_status_value,priority,description,visibility,created_by,created_on,created_ip) values('".$bank_account_status_value."','".$priority."','".$description."','".$visibility."','".$created_by."','".$date."','".$ip_address."')";
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
		$qry="Select count(*) as cnt from tw_bank_account_status_master where bank_account_status_value='".$bank_account_status_value."' and id!='".$requestid."'";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			$qry1="Update tw_bank_account_status_master set bank_account_status_value='".$bank_account_status_value."',priority='".$priority."',description='".$description."',visibility='".$visibility."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."'"; 
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
