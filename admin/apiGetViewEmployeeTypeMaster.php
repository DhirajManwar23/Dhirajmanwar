<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,employee_type_value,priority,visibility from tw_employee_type_master order by id Desc";
$format = "HTML";
$tableName = "tw_employee_type_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
