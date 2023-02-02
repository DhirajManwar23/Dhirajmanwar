<?php
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
session_start();

$employee_id=$_SESSION["employee_id"];

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

if($_POST["requestidid"]==""){
	$qry="delete from tw_material_inward_qc_temp where employee_id='".$employee_id."'";
}
else{
	$qry="DELETE FROM tw_material_inward_qc_temp WHERE id='".$_POST["requestidid"]."'";
}
	

$retVal = $sign->FunctionQuery($qry);
if($retVal=="Success"){
	$qry1="Select id,description,norms,first,second,third,total,average from tw_material_inward_qc_temp where employee_id = '".$employee_id."' ORDER BY id DESC";
	$retVal1 = $sign->FunctionJSON($qry1);


	$qry1="Select count(*) as cnt from tw_material_inward_qc_temp where employee_id = '".$employee_id."' ORDER BY id DESC";
	$retVal2 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal1);
	$count = 0;
	$i = 1;
	$x=$retVal2;
	$table="";
	$it=1;
	$valtotalamt=0;
	$table.="<thead><tr><th>#SR.NO</th><th>Description</th><th>Norms</th><th>First</th><th>Second</th><th>Third</th><th>Total</th><th>Average</th><th>Remove</th></tr></thead><tbody>";
		while($x>=$i){
		
		
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$description = $decodedJSON2->response[$count]->description;
		$count=$count+1;
		$norms = $decodedJSON2->response[$count]->norms;
		$count=$count+1;
		$first = $decodedJSON2->response[$count]->first;
		$count=$count+1;
		$second = $decodedJSON2->response[$count]->second;
		$count=$count+1;
		$third = $decodedJSON2->response[$count]->third;
		$count=$count+1;
		$total = $decodedJSON2->response[$count]->total;
		$count=$count+1;
		$average = $decodedJSON2->response[$count]->average;
		$count=$count+1;
		
		
		$qry2="Select description from tw_test_report_designation_master where id = '".$description."' ORDER BY id DESC";
		$retVal2 = $sign->SelectF($qry2,"description");
		
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$retVal2."</td>";
		$table.="<td>".$norms."</td>";
		$table.="<td>".$first."</td>";
		$table.="<td>".$second."</td>";
		$table.="<td>".$third."</td>";
		$table.="<td>".$total."</td>";
		$table.="<td>".$average."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='funcremoverow(".$id.")'><i class='ti-close'></i></a></td>";
		$it++;
		$table.="</tr>";

		$i=$i+1;
	}

	$table.="</tbody>";
	//echo $table; 
	if($valtotalamt==0){
		echo $table.",0";
	}
	else{
		echo $table.",".$valtotalamt;
	}
}
else{
	echo "error";
}




	?>