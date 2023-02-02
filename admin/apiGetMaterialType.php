<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$sub_category_id = $_POST["sub_category_id"];
$qry4 = "select id,name from tw_product_type_master where visibility = 'true'  and sub_category_id = '".$sub_category_id."'";
$retVal4 = $sign->FunctionOption($qry4,'','name','id');

echo $retVal4;
?>
