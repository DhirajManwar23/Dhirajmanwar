<?php
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$company_id = $_SESSION["company_id"];
	
	$qry="select id,transporter_name,transporter_email,contact from tw_transport_master where company_id='".$company_id."'";
	
	$retVal = $sign->FunctionJSON($qry);

	$qry1="Select count(*) as cnt from tw_transport_master where company_id='".$company_id."' order by id Desc";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>SR.NO</th><th>Transporter Name</th><th>Transporter Email</th><th>Contact</th><th>KYC</th><th>Vehicles</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$transporter_name = $decodedJSON2->response[$count]->transporter_name;
	$count=$count+1;
	$transporter_email  = $decodedJSON2->response[$count]->transporter_email ;
	$count=$count+1;
	$contact = $decodedJSON2->response[$count]->contact;
	$count=$count+1;
	
	// $status  = $decodedJSON2->response[$count]->status ;
	// $count=$count+1;
	
	
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$transporter_name."</td>";
		$table.="<td>".$transporter_email."</td>";
		$table.="<td>".$contact."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='showKYC(".$id.")'><i class='ti-files'></a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='showVehicles(".$id.")'><i class='ti-truck'></a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	



