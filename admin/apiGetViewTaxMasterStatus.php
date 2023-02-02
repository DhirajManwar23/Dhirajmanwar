<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,tax_name,tax_value,priority,visibility from tw_tax_master order by id Desc";
$format = "HTML";
$tableName = "tw_tax_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
