<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,document_type,priority,visibility from tw_ragpicker_documents order by id asc";
$format = "HTML";
$tableName = "tw_ragpicker_documents";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>	


