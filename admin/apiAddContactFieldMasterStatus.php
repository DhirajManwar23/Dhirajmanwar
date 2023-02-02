<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$contact_type=$sign->escapeString($_POST["contact_type"]);
$select_contact_type=$sign->escapeString($_POST["select_contact_type"]);
$allow_duplicate=$sign->escapeString($_POST["allow_duplicate"]);
$contact_icon=$sign->escapeString($_POST["contact_icon"]);
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
		$qry="Select count(*) as cnt from tw_contact_field_master where contact_type='".$contact_type."'";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			
			$qry1="insert into tw_contact_field_master (contact_type,select_contact_type,allow_duplicate,contact_icon,priority,description,visibility,created_by,created_on,created_ip) values('".$contact_type."','".$select_contact_type."','".$allow_duplicate."','".$contact_icon."','".$priority."','".$description."','".$visibility."','".$created_by."','".$date."','".$ip_address."')";
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
		$qry="Select count(*) as cnt from  tw_contact_field_master where contact_type='".$contact_type."' and id!='".$requestid."' ";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			$qry1="Update tw_contact_field_master set contact_type='".$contact_type."',select_contact_type='".$select_contact_type."',allow_duplicate='".$allow_duplicate."',contact_icon='".$contact_icon."',priority='".$priority."',description='".$description."',visibility='".$visibility."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
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
