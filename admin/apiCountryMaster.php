<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,country_name,symbol,currency,country_code,priority,visibility from tw_country_master order by id Desc";
$format = "HTML";
$tableName = "tw_country_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>	


