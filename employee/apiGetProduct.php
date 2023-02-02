<?php
session_start();

$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];

$selectSubCategory=$_POST['SELECTID'];

include_once "function.php";	
$sign=new Signup();

$productQry="SELECT id,epr_product_name FROM  tw_epr_product_master where epr_category_id='".$selectSubCategory."'  AND 	visibility='true' ORDER by priority ASC";
$product = $sign->FunctionOption($productQry,"",'epr_product_name',"id");


$SubCategory=array();
 array_push($SubCategory,$product);
 
 echo json_encode($SubCategory);
?>