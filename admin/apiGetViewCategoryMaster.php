<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,category_name,priority,visibility from tw_category_master order by id Desc";
$format = "HTML";
$tableName = "tw_category_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
