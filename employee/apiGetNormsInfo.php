<?php
include_once "function.php";
$sign=new Signup();
$selDescription=$_POST['selDescription'];

$qry="select norms from  tw_test_report_designation_master  where id= '".$selDescription."'";
$retVal=$sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);

$norms= $decodedJSON->response[0]->norms;

$responsearray=array();
array_push($responsearray,$norms);
echo json_encode($responsearray);
?>