<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,payment_type_value,priority,visibility from tw_payment_type_master order by id Desc";
$format = "HTML";
$tableName = "tw_payment_type_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
