<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,compliance_document_type,priority,visibility from tw_compliance_type_master order by id asc";
$format = "HTML";
$tableName = "tw_compliance_type_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>	


