<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,department_name,priority,visibility from tw_department_master order by id Desc";
$format = "HTML";
$tableName = "tw_department_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
