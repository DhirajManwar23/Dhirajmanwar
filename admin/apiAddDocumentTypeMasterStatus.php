<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$documenttype=$sign->escapeString($_POST["documenttype"]);
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
$qry="Select count(*) as cnt from tw_document_type_master where document_type_value='".$documenttype."'";
$retVal = $sign->Select($qry);
if($retVal>0){
	echo "Exist";
}
else
{	
	$qry1="insert into tw_document_type_master (document_type_value,priority,description,visibility,created_by,created_on,created_ip) values('".$documenttype."','".$priority."','".$description."','".$visibility."','".$created_by."','".$date."','".$ip_address."')";
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
	$qry="Select count(*) as cnt from tw_document_type_master where document_type_value='".$documenttype."' and id!='".$requestid."' ";
	$retVal = $sign->Select($qry);
	if($retVal>0){
		echo "Exist";
	}
	else
	{	
		$qry1="Update tw_document_type_master set document_type_value='".$documenttype."',priority='".$priority."',description='".$description."',visibility='".$visibility."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
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