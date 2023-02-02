<?php
$selectCategory=$_POST['SELECTID'];
include_once "function.php";	
$sign=new Signup();

$CategoryQry="SELECT id,sub_category_name FROM  tw_subcategory_master where category_id='".$selectCategory."' ORDER by priority ASC";
$Category = $sign->FunctionOption($CategoryQry,"",'sub_category_name',"id");



$SubCategory=array();
 array_push($SubCategory,$Category);
 
 echo json_encode($SubCategory);
?>