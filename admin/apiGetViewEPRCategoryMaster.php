<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,epr_category_name,priority,visibility from tw_epr_category_master order by id Desc";
$format = "HTML";
$tableName = "tw_epr_category_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
