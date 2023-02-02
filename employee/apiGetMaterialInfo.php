<?php
include_once "function.php";
$sign=new Signup();
$txtMaterialName=$_POST['txtMaterialName'];

$qry="select pm.amount_per_unit,tm.tax_value,pm.hsn from tw_product_management pm INNER JOIN tw_tax_master tm ON pm.tax=tm.id where pm.id = '".$txtMaterialName."'";
$retVal=$sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);

$amount_per_unit= $decodedJSON->response[0]->amount_per_unit;
$tax = $decodedJSON->response[1]->tax_value; 
$hsn = $decodedJSON->response[2]->hsn;

$responsearray=array();
array_push($responsearray,number_format($amount_per_unit,2), $tax, $hsn);
echo json_encode($responsearray);
?>