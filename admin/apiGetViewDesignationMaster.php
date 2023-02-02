<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,designation_value,priority,visibility from  tw_designation_master order by id Desc";
$format = "HTML";
$tableName = "tw_designation_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
