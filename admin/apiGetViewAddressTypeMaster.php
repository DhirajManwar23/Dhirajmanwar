<?php
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$qry="select id,address_type_value,address_icon,priority,visibility from tw_address_type_master order by id Desc";
	$format = "HTML";
	$tableName = "tw_address_type_master";
	$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
	echo $retVal;
?>	

