<?php
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$qry = "SELECT id,collection_point_name,priority,visibility,description from tw_collection_point_type_master order by id Desc";
	$format = "HTML";
	$tableName = "tw_collection_point_type_master";
	$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
	echo $retVal;
?>	