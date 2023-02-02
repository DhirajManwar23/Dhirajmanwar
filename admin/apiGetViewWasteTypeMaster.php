<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select ID,name,priority,description,visibility from tw_waste_type_master order by id Desc";
$format = "HTML";
$tableName = "tw_waste_type_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
