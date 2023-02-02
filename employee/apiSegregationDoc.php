<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$startDate=$_POST["startdate"];
$endDate=$_POST["enddate"];

$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");

$qry=" SELECT mix_waste_lot_id,waste_type,quantity FROM tw_mix_waste_collection_details where created_on BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY mix_waste_lot_id, waste_type";
$qrycnt="SELECT COUNT(*) as cnt FROM tw_mix_waste_collection_details where created_on BETWEEN '".$startDate."' AND '".$endDate."'";
$Cnt = $sign->Select($qrycnt);
if($Cnt==0 ){

		
	
	
}
else{


$wasteNameQry="SELECT name FROM tw_segregation_waste_type_master ORDER BY id";
$retValWasteName = $sign->FunctionData($wasteNameQry);
// echo json_encode($retValWasteName);

$table="<table width='100%' class='printtbl'><tr>
		<th class='center-text'>#</th>
		<th class='center-text'>Date</th>
		<th class='center-text'>Lot</th>";

foreach ($retValWasteName as $v1) {
	$table.="<th>".$v1['name']."</th>";
}



$table.="<th>Total Quantity<b></th></tr>";

$qry="SELECT mix_waste_lot_id, waste_type,quantity, created_on FROM tw_mix_waste_collection_details where created_on BETWEEN '".$startDate."' AND '".$endDate."'";
$qrycnt="SELECT COUNT(*) as cnt FROM tw_mix_waste_collection_details where created_on BETWEEN '".$startDate."' AND '".$endDate."'";
$Cnt = $sign->Select($qrycnt);
$retVal11 = $sign->FunctionData($qry);

$result = group_by("mix_waste_lot_id",$retVal11);
$seq=1;

foreach ($result as $k1=>$v1) {
	$total_quantity = 0;
	$table.="<tr><td>".$seq."</td>";
	$count = 0;
	foreach($v1 as $v2){
		if($count ==0){
			$table.="<td>".date('d-m-Y', strtotime($v2['created_on']))."</td>
					<td> Lot ".$v2['mix_waste_lot_id']."</td>";
		}
		$table.="<td>".$v2['quantity']."</td>";
		$total_quantity = $total_quantity + $v2['quantity'];
		$count = $count + 1;
	}
	
	$count = 0;
	$table.="<td>".$total_quantity."</td>";
	$table.="</tr>";
	$seq = $seq+1;
}


echo $table;
}

function group_by($key, $data) {
    $result = array();

    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        }else{
            $result[""][] = $val;
        }
    }

    return $result;
}
?>