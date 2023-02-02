<?php
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$qry="select id,panel,priority,visibility from tw_panel_master order by id Desc";
	$format = "HTML";
	$tableName = "tw_panel_master";
	$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
	echo $retVal;
?>
