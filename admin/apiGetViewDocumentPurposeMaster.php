<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,document_purpose_value,priority,visibility from tw_document_purpose_master order by id Desc";
$format = "HTML";
$tableName = "tw_document_purpose_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
