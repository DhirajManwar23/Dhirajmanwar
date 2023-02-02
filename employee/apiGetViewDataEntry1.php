<?php
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$employee_id = $_SESSION["employee_id"];
	$qry="select id,date as tdate,PET,Hard_Plastics,Soiled_Paper,Hard_Plastic_Mixture,Soft_Plastic,Reject_Waste,Incoming_Waste,Recyclable_Waste from tw_manualdata_entry where employee_id='".$employee_id."' order by id Desc";

	$retVal = $sign->FunctionJSON($qry);

	$qry1="Select count(*) as cnt from tw_manualdata_entry where employee_id='".$employee_id."'";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>SR.NO</th><th>Date</th><th>PET </th><th>Hard Plastic</th><th>Solid Paper</th><th>Hard Plastic Mixture</th><th>Soft Plastic</th><th>Reject Waste</th><th>Incoming Waste</th><th>Recycleable Waste</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$date = $decodedJSON2->response[$count]->tdate;
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
	$newDate = date('d-m-Y', strtotime($date));
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$newDate."</td>";
		$table.="<td>".$PET."</td>";
		$table.="<td>".$Hard_Plastics."</td>";
		$table.="<td>".$Soiled_Paper."</td>";
		$table.="<td>".$Hard_Plastic_Mixture."</td>";
		$table.="<td>".$Soft_Plastic."</td>";
		$table.="<td>".$Reject_Waste."</td>";
		$table.="<td>".$Incoming_Waste."</td>";
		$table.="<td>".$Recyclable_Waste."</td>";
		
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	






