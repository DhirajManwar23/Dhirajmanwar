<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$data=array();
//--
 $qryMixWasteDataEntry = "SELECT
	IFNULL(Sum(case when month(entry_date) =4 then replace(quantity, ',', '') end),0) As Apr,
	IFNULL(Sum(case when month(entry_date) =5 then replace(quantity, ',', '') end),0) As May,
	IFNULL(Sum(case when month(entry_date) =6 then replace(quantity, ',', '') end),0) As Jun,
	IFNULL(Sum(case when month(entry_date) =7 then replace(quantity, ',', '') end),0) As Jul,
	IFNULL(Sum(case when month(entry_date) =8 then replace(quantity, ',', '') end),0) As Aug,
	IFNULL(Sum(case when month(entry_date)  =9 then replace(quantity, ',', '') end),0) As Sep,
	IFNULL(Sum(case when month(entry_date)  =10 then replace(quantity, ',', '') end),0) As Oct,
	IFNULL(Sum(case when month(entry_date)  =11 then replace(quantity, ',', '') end),0) As Nov,
	IFNULL(Sum(case when month(entry_date) =12 then replace(quantity, ',', '') end),0) As De,
	IFNULL(Sum(case when month(entry_date)  =1 then replace(quantity, ',', '') end),0) As Jan,
	IFNULL(Sum(case when month(entry_date) =2 then replace(quantity, ',', '') end),0) As Feb,
	IFNULL(Sum(case when month(entry_date) =3 then replace(quantity, ',', '') end),0) As Mar
	FROM tw_mixwaste_manual_entry where entry_date>='2022-04-01' and entry_date<='2023-03-31'";
	$retMixWasteDataEntry = $sign->FunctionJSON($qryMixWasteDataEntry);
	$decodedJSON2 = json_decode($retMixWasteDataEntry);
	$count = 0;
	$i = 1;
	$x=1;
	while($x>=$i){
		$Apr = $decodedJSON2->response[$count]->Apr;
		$count=$count+1;
		$May = $decodedJSON2->response[$count]->May;
		$count=$count+1;
		$Jun = $decodedJSON2->response[$count]->Jun;
		$count=$count+1;
		$Jul = $decodedJSON2->response[$count]->Jul;
		$count=$count+1;
		$Aug = $decodedJSON2->response[$count]->Aug;
		$count=$count+1;
		$Sep = $decodedJSON2->response[$count]->Sep;
		$count=$count+1;
		$Oct = $decodedJSON2->response[$count]->Oct;
		$count=$count+1;
		$Nov = $decodedJSON2->response[$count]->Nov;
		$count=$count+1;
		$De = $decodedJSON2->response[$count]->De;
		$count=$count+1;
		$Jan = $decodedJSON2->response[$count]->Jan;
		$count=$count+1;
		$Feb = $decodedJSON2->response[$count]->Feb;
		$count=$count+1;
		$Mar = $decodedJSON2->response[$count]->Mar;
		$count=$count+1;
		$a=array();
		array_push($a,$Apr,$May,$Jun,$Jul,$Aug,$Sep,$Oct,$Nov,$De,$Jan,$Feb,$Mar);
		$arraycolor= $commonfunction->getArrayColors($i);
		$data [] = [
			'sum' => $a
		];
		$i=$i+1;
	}
	echo json_encode($data);  
	//print_r($data);
?>
