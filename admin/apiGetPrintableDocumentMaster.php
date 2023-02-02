<?php
// Include class definition
include_once "function.php";

$qry="select id,printable_document_value,priority,visibility from tw_printable_document_master order by id Desc";
$sign=new Signup();
$format = "HTML";
$tableName = "tw_printable_document_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
