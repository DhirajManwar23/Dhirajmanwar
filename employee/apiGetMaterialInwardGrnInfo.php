<?php
include_once "function.php";
$sign=new Signup();
$txtMaterialName=$_POST['txtMaterialName'];

$qry="select pm.amount_per_unit,tuom.name,pm.tax from tw_product_management pm INNER JOIN tw_unit_of_measurement tuom where pm.id = '".$txtMaterialName."'";
$retVal=$sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);

$amount_per_unit= $decodedJSON->response[0]->amount_per_unit;
$uom= $decodedJSON->response[1]->name;
$tax= $decodedJSON->response[2]->tax;

$responsearray=array();
array_push($responsearray,$amount_per_unit,$uom,$tax);
echo json_encode($responsearray);
?>