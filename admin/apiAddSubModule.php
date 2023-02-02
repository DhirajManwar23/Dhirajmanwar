<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$submodule_name=$sign->escapeString($_POST["submodule_name"]);
$module=$sign->escapeString($_POST["module"]);
$sub_module_icon=$sign->escapeString($_POST["sub_module_icon"]);
$url=$sign->escapeString($_POST["url"]);
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
$qry="Select count(*) as cnt from tw_submodule_master where submodule_name='".$submodule_name."' and module='".$module."'";
$retVal = $sign->Select($qry);
if($retVal>0){
	echo "Exist";
}
else
{	
	$qry1="insert into tw_submodule_master(submodule_name,module,sub_module_icon,url,priority,description,visibility,created_by,created_on,created_ip) values('".$submodule_name."','".$module."','".$sub_module_icon."','".$url."','".$priority."','".$description."','".$visibility."','".$created_by."','".$date."','".$ip_address."')";
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
	
	$qry="Select count(*) as cnt from tw_submodule_master where submodule_name='".$submodule_name."' and module='".$module."' and id!='".$requestid."' ";
	$retVal = $sign->Select($qry);
	if($retVal>0){
		echo "Exist";
	}
	else
	{	
		$qry1="Update tw_submodule_master set submodule_name='".$submodule_name."',module='".$module."',sub_module_icon='".$sub_module_icon."',url='".$url."',priority='".$priority."',description='".$description."',visibility='".$visibility."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
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
