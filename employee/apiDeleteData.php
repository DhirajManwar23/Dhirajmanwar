<?php
	// Include class definition
	require "function.php";
	$sign=new Signup();
	$tablename = $sign->escapeString($_POST["tablename"]);
	$id = $sign->escapeString($_POST["id"]);
	$qry="Delete from ".$tablename." where ID = ".$id." order by ID Desc";
	$retVal = $sign->FunctionQuery($qry);
	echo $retVal;
?>
