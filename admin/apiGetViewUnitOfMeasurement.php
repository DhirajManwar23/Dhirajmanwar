<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,name,priority,description,visibility from tw_unit_of_measurement order by id Desc";
$format = "HTML";
$tableName = "tw_unit_of_measurement";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
