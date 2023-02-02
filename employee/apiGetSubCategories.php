<?php
// Include class definition
include_once "function.php";
$sign=new Signup();

$category_id = $_POST["category_id"];

$qry2 = "select id,sub_category_name from tw_subcategory_master where visibility='true' and category_id = '".$category_id."'";
$retVal2 = $sign->FunctionOption($qry2,'','sub_category_name','id');

echo $retVal2;
?>
