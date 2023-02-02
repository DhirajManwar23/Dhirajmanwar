<?php
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$qry="select id,bank_account_status_value,priority,visibility from tw_bank_account_status_master order by id Desc";
	$format = "HTML";
	$tableName = "tw_bank_account_status_master";
	$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
	echo $retVal;
?>
