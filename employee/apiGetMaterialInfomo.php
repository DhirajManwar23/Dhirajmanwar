<?php
include_once "function.php";
$sign=new Signup();
$txtMaterialName=$_POST['txtMaterialName'];

$qry="Select rate,hsn,tax from tw_temp_po_details where id='".$txtMaterialName."'";
$retVal=$sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);

$amount_per_unit= $decodedJSON->response[0]->rate;
$hsn = $decodedJSON->response[1]->hsn;
$tax = $decodedJSON->response[2]->tax; 

$responsearray=array();
array_push($responsearray,number_format($amount_per_unit,2), $tax, $hsn);
echo json_encode($responsearray);
?>