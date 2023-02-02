<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
$requestid=$_POST["id"];
$date=$_REQUEST["date"];


$WasteNameQry="SELECT name,id FROM `tw_segregation_waste_type_master`";
$retValWasteName = $sign->FunctionJSON($WasteNameQry);
$decodedJSONretValWasteName = json_decode($retValWasteName);

$wasteCntQry="SELECT COUNT(*) as cnt FROM `tw_segregation_waste_type_master` ";
$wasteCnt=$sign->Select($wasteCntQry);
$table="";
$table.=" <table>
		<table width='100%' class='printtbl' >
			 <tr>
			<th  class='center-text'>#</th>
			<th  class='center-text'>Date</th>
			
			";
$count = 0;
$i = 1;
$x=$wasteCnt;

while($x>=$i){
	$FetchWasteName=$decodedJSONretValWasteName->response[$count]->name;
	$count=$count+1;
	$id=$decodedJSONretValWasteName->response[$count]->id;
	$count=$count+1;
	
	 $qryDetails="SELECT me.waste_type,sum(me.quantity) as qty,me.entry_date,sm.name FROM `tw_mixwaste_manual_entry` me INNER join tw_segregation_waste_type_master sm ON me.waste_type=sm.id where month('".$date."') AND year('".$date."') AND me.waste_type='".$id."'";
	
	$retVal = $sign->FunctionJSON($qryDetails);
	$decodedJSON7 = json_decode($retVal);
	$qty = $decodedJSON7->response[1]->qty;
	
	
	
	$table.="<th  class='center-text'>".$FetchWasteName."</th>
	
	";
	$i=$i+1;
	}
			
	$table.="</tr></table>";
echo $table;
?>