<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$company_id = $_SESSION["company_id"];
$sID=$_POST["val"];
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$data=array();
if($sID=="0"){
	$varCondition="";
}
else{
	$varCondition="and company_id='".$sID."'";

}
//--
	$qryState = "SELECT
  IFNULL(Sum(case when month(created_on)=4 then replace(quantity, ',', '') end),0) As Apr,
  IFNULL(Sum(case when month(created_on)=5 then replace(quantity, ',', '') end),0) As May,
  IFNULL(Sum(case when month(created_on)=6 then replace(quantity, ',', '') end),0) As Jun,
  IFNULL(Sum(case when month(created_on)=7 then replace(quantity, ',', '') end),0) As Jul,
  IFNULL(Sum(case when month(created_on)=8 then replace(quantity, ',', '') end),0) As Aug,
  IFNULL(Sum(case when month(created_on)=9 then replace(quantity, ',', '') end),0) As Sep,
  IFNULL(Sum(case when month(created_on)=10 then replace(quantity, ',', '') end),0) As Oct,
  IFNULL(Sum(case when month(created_on)=11 then replace(quantity, ',', '') end),0) As Nov,
  IFNULL(Sum(case when month(created_on)=12 then replace(quantity, ',', '') end),0) As De,
  IFNULL(Sum(case when month(created_on)=1 then replace(quantity, ',', '') end),0) As Jan,
  IFNULL(Sum(case when month(created_on)=2 then replace(quantity, ',', '') end),0) As Feb,
  IFNULL(Sum(case when month(created_on)=3 then replace(quantity, ',', '') end),0) As Mar
FROM tw_material_outward_individual
where material_outward_id in (select id from tw_material_outward where customer_id='".$company_id."' and status='".$settingValueApprovedStatus."' ".$varCondition.") and created_on>='2022-04-01' and created_on<='2023-03-31'";
	$retValState = $sign->FunctionJSON($qryState);
	
	$decodedJSON2 = json_decode($retValState);
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
		$data[] = [
			'sum' => $a
		];
		
		$i=$i+1;
	}
	echo json_encode($data);  
	//print_r($data);
?>
