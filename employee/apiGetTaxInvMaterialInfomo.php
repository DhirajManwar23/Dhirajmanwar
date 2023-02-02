<?php
include_once "function.php";
$sign=new Signup();
$txtMaterialName=$_POST['txtMaterialName'];

$qry="Select hsn,tax from tw_product_management  where id='".$txtMaterialName."'";
$retVal=$sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);


$hsn = $decodedJSON->response[0]->hsn;
$Fetchtax = $decodedJSON->response[1]->tax; 

$fetchTaxQry="SELECT tax_value FROM `tw_tax_master` where id='".$Fetchtax."'";
$tax = $sign->SelectF($fetchTaxQry,'tax_value');

$responsearray=array();
array_push($responsearray, $tax, $hsn);
echo json_encode($responsearray);
?>