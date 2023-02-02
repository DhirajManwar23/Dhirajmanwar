<?php
// Include class definition
include_once("function.php");
$sign=new Signup();

$queriesJSON = file_get_contents('php://input');
$queries = json_decode($queriesJSON)->queries;

// // echo json_encode($queris);


// $queries = $queris->queries;

// echo json_encode($queries);
// foreach ($queris as $key => $value) {
//     echo "Key: $key; Value: $value\n";
// }

// echo $querisJSON;

foreach ($queries as $query) {
	foreach ($query as $k => $v) {
		if($k == "valquery") $sign->FunctionQuery($v);
	}
}

echo "Success";

// // $retVal1 = $sign->FunctionQuery($valquery);
// if($retVal1=="Success"){
// 	echo "Success";
// }
// else{
// 	echo "error";
// }	
?>
