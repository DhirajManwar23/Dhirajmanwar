<?php
include_once "function.php";
$sign=new Signup();

$data = array();
$qry="SELECT ID,CompanyName FROM `tw_company_details`";
$retVal = $sign->FunctionJSON($qry);

$qryCount="SELECT COUNT(CompanyName ) as cnt FROM tw_company_details";
$retVal1=$sign->Select($qryCount);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$responsearray=array();
while($x>=$i){
	$ID = $decodedJSON2->response[$count]->ID;
	$count=$count+1;
	$name = $decodedJSON2->response[$count]->CompanyName;
	$count=$count+1;
	
	array_push($responsearray, $name,$ID);
	$i=$i+1;
}


echo json_encode( $responsearray);
?>