<?php
session_start();
// Include class definition
include "function.php";
$sign=new Signup();
$module_name=$sign->escapeString($_POST["module_name"]);
$panel=$sign->escapeString($_POST["panel"]);
$module_icon=$sign->escapeString($_POST["module_icon"]);
$url=$sign->escapeString($_POST["url"]);
$priority=$sign->escapeString($_POST["priority"]);
$description=$sign->escapeString($_POST["description"]);
$visibility=$sign->escapeString($_POST["visibility"]);

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
$created_by=$_SESSION["username"];
$requesttype=$_SESSION["requesttype"];
$requestid=$_SESSION["requestid"];

	
	if($requesttype=="add"){
	 $qry="Select count(*) as cnt from tw_module_master where module_name='".$module_name."' and panel='".$panel."'";
	
	
	$retVal = $sign->Select($qry);
	if($retVal>0){
		echo "Exist";
	}
	else
	{	
		$qry1="insert into tw_module_master(module_name,panel,module_icon,url,priority,description,visibility,created_by,created_on,created_ip)values('".$module_name."','".$panel."','".$module_icon."','".$url."','".$priority."','".$description."','".$visibility."','".$created_by."','".$date."','".$ip_address."')";
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
		
	$qry="Select count(*) as cnt from tw_module_master where module_name='".$module_name."' and panel='".$panel."' and id!='".$requestid."' ";

	$retVal = $sign->Select($qry);
	if($retVal>0){
		echo "Exist";
	}
	else
	{	
		$qry1="Update tw_module_master set module_name='".$module_name."',panel='".$panel."',module_icon='".$module_icon."',url='".$url."',priority='".$priority."',description='".$description."',visibility='".$visibility."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
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
