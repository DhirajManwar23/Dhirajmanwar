<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,contact_type,select_contact_type,allow_duplicate,contact_icon,priority,visibility from tw_contact_field_master order by id Desc";
$format = "HTML";
$tableName = "tw_contact_field_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>

