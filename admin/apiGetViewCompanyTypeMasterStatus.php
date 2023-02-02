<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,company_type,company_icon,priority,visibility from tw_company_type_master order by id Desc";
$format = "HTML";
$tableName = "tw_company_type_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>