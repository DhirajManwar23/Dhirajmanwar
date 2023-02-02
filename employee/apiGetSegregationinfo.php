<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$mix_waste_lot_id=$_POST["id"];


$qry=" SELECT waste_type,quantity FROM tw_mix_waste_collection_details where mix_waste_lot_id='".$mix_waste_lot_id."'";
$qrycnt="SELECT COUNT(*) as cnt FROM tw_mix_waste_collection_details where mix_waste_lot_id='".$mix_waste_lot_id."'";
$Cnt = $sign->Select($qrycnt);

$retVal11 = $sign->FunctionJSON($qry);
$decodedJSON3 = json_decode($retVal11);
$i = 1;
$it1=1;
$count = 0;
$x=$Cnt;
$valtotalquantity=0;	
$table="<table class='printtbl'>
	  <th class='center-text'>#</th>
	  <th class='center-text'>Waste Name</th>
	  <th class='center-text'>Quantity</th>";

while($x>=$i){
	 $waste_type= $decodedJSON3->response[$count]->waste_type;
	 $count=$count+1;
	 $quantity= $decodedJSON3->response[$count]->quantity;
	 $count=$count+1;
	 $valtotalquantity=$valtotalquantity+$quantity;
	 
	
	 $qryproductName=" SELECT name FROM tw_segregation_waste_type_master where id='".$waste_type."'";
	 $data = $sign->FunctionJSON($qryproductName);
	 $decodedJSON = json_decode($data);
	 $name = $decodedJSON->response[0]->name; 
	 $table.="
	
	  
	  <tr>
				<td class='center-text'>".$it1."</td>
				<td >".$name."</td>
				<td class='center-text'>".number_format($quantity,2)."</td>
	  </tr> 
	 "; 


				  $i=$i+1;
				  $it1=$it1+1;
}
	$table.="<td></td>";
	$table.="<td><b>Total Quantity<b></td>";
	$table.="<td class='center-text'><b>".number_format($valtotalquantity,2)."</b></td>";
	
	
$table.="</table>";
echo $table;
?>