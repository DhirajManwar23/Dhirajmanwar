<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,verification_status,priority,visibility from tw_verification_status_master order by id Desc";
$format = "HTML";
$tableName = "tw_verification_status_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>