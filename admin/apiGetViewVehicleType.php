<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,name,priority,description,visibility from tw_vehicle_type_master order by id Desc";
$format = "HTML";
$tableName = "tw_vehicle_type_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
	
