<?php
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$employee_id = $_SESSION["employee_id"];
	
	$qry="SELECT id,date,SUM(PET) PET,SUM(Hard_Plastics) Hard_Plastics, SUM(Soiled_Paper) Soiled_Paper,SUM(Hard_Plastic_Mixture) Hard_Plastic_Mixture,SUM(Soft_Plastic) Soft_Plastic,SUM(Reject_Waste) Reject_Waste,SUM(Incoming_Waste) Incoming_Waste,SUM(Recyclable_Waste) Recyclable_Waste
	FROM tw_manualdata_entry where employee_id='".$employee_id."' GROUP BY year(date) order by date";
	$retVal = $sign->FunctionJSON($qry);
	
	$qry1="select count(distinct(year(date))) as cnt from tw_manualdata_entry where employee_id='".$employee_id."'";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>SR.NO</th><th>Year</th><th>PET </th><th>Hard Plastic</th><th>Solid Paper</th><th>Hard Plastic Mixture</th><th>Soft Plastic</th><th>Reject Waste</th><th>Incoming Waste</th><th>Recycleable Waste</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$date = $decodedJSON2->response[$count]->date;
	$count=$count+1;
	$PET  = $decodedJSON2->response[$count]->PET ;
	$count=$count+1;
	$Hard_Plastics = $decodedJSON2->response[$count]->Hard_Plastics;
	$count=$count+1;
	$Soiled_Paper = $decodedJSON2->response[$count]->Soiled_Paper;
	$count=$count+1;
	$Hard_Plastic_Mixture = $decodedJSON2->response[$count]->Hard_Plastic_Mixture;
	$count=$count+1;
	$Soft_Plastic = $decodedJSON2->response[$count]->Soft_Plastic;
	$count=$count+1;
	$Reject_Waste = $decodedJSON2->response[$count]->Reject_Waste;
	$count=$count+1;
	$Incoming_Waste = $decodedJSON2->response[$count]->Incoming_Waste;
	$count=$count+1;
	$Recyclable_Waste = $decodedJSON2->response[$count]->Recyclable_Waste;
	$count=$count+1;
	$newDate = date('Y', strtotime($date));
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$newDate."</td>";
		$table.="<td>".number_format($PET,2)."</td>";
		$table.="<td>".number_format($Hard_Plastics,2)."</td>";
		$table.="<td>".number_format($Soiled_Paper,2)."</td>";
		$table.="<td>".number_format($Hard_Plastic_Mixture,2)."</td>";
		$table.="<td>".number_format($Soft_Plastic,2)."</td>";
		$table.="<td>".number_format($Reject_Waste,2)."</td>";
		$table.="<td>".number_format($Incoming_Waste,2)."</td>";
		$table.="<td>".number_format($Recyclable_Waste,2)."</td>";
		
		$it++;
		$table.="</tr>";
		$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	






