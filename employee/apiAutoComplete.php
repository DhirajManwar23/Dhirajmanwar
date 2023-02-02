<?php
include_once "function.php";
$sign=new Signup();

$data = array();
$qry="SELECT DISTINCT name FROM tw_mixwaste_manual_entry ";
$retVal = $sign->FunctionJSON($qry);

$qryCount="SELECT COUNT(DISTINCT name) as cnt FROM tw_mixwaste_manual_entry";
$retVal1=$sign->Select($qryCount);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$responsearray=array();
while($x>=$i){
	 $name = $decodedJSON2->response[$count]->name;
	$count=$count+1;
	
	
	array_push($responsearray, $name);
	$i=$i+1;
}


echo json_encode($responsearray);
?>