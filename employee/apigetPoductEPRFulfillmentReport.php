<?php
ini_set('max_execution_time', 300);
error_reporting(E_ALL);

session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$company_id = $_SESSION["company_id"];
$sID=$_POST["val"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$data = array();
$datearray = array();
if($sID=="0"){
	$varCondition="";
}
else{
	$varCondition="and supplier_id='".$sID."'";

}

		$qryState = "SELECT
  dispatched_state,
  IFNULL(Sum(case when month(purchase_invoice_date)=4 then replace(plant_quantity, ',', '') end),0) As Apr,
  IFNULL(Sum(case when month(purchase_invoice_date)=5 then replace(plant_quantity, ',', '') end),0) As May,
  IFNULL(Sum(case when month(purchase_invoice_date)=6 then replace(plant_quantity, ',', '') end),0) As Jun,
  IFNULL(Sum(case when month(purchase_invoice_date)=7 then replace(plant_quantity, ',', '') end),0) As Jul,
  IFNULL(Sum(case when month(purchase_invoice_date)=8 then replace(plant_quantity, ',', '') end),0) As Aug,
  IFNULL(Sum(case when month(purchase_invoice_date)=9 then replace(plant_quantity, ',', '') end),0) As Sep,
  IFNULL(Sum(case when month(purchase_invoice_date)=10 then replace(plant_quantity, ',', '') end),0) As Oct,
  IFNULL(Sum(case when month(purchase_invoice_date)=11 then replace(plant_quantity, ',', '') end),0) As Nov,
  IFNULL(Sum(case when month(purchase_invoice_date)=12 then replace(plant_quantity, ',', '') end),0) As De,
  IFNULL(Sum(case when month(purchase_invoice_date)=1 then replace(plant_quantity, ',', '') end),0) As Jan,
  IFNULL(Sum(case when month(purchase_invoice_date)=2 then replace(plant_quantity, ',', '') end),0) As Feb,
  IFNULL(Sum(case when month(purchase_invoice_date)=3 then replace(plant_quantity, ',', '') end),0) As Mar
FROM tw_temp
where po_id in (select id from tw_po_info where company_id='".$company_id."' ".$varCondition.") and status='".$settingValueCompletedStatus."' and purchase_invoice_date>='2022-04-01' and purchase_invoice_date<='2023-03-31'
GROUP BY dispatched_state";
	$retValState = $sign->FunctionJSON($qryState);
	$qryStatecount = "SELECT COUNT(distinct(dispatched_state)) as ds FROM tw_temp
	where po_id in (select id from tw_po_info where company_id='".$company_id."' ".$varCondition.") and status='".$settingValueCompletedStatus."' and purchase_invoice_date>='2022-04-01' and purchase_invoice_date<='2023-03-31'";
	$retValStatecount = $sign->selectF($qryStatecount,"ds"); 
	
	
	$decodedJSON2 = json_decode($retValState);
	$count = 0;
	$i = 1;
	$x=$retValStatecount;
		
	while($x>=$i){
		$dispatched_state = $decodedJSON2->response[$count]->dispatched_state;
		$count=$count+1;
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
			'state' => $dispatched_state,
			'month' => $a,
			'arraycolor' => $arraycolor
		];
		
		$i=$i+1;
	}
	echo json_encode($data);  
?>
