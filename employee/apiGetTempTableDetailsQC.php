<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();

$employee_id=$_SESSION["employee_id"];
$hdnOrderID=$_POST["hdnOrderID"];

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$qry="select description,norms,first,second,third,total,average from tw_material_inward_qc_individual WHERE employee_id = '".$employee_id."'  and material_inward_qc_id='".$hdnOrderID."' ORDER BY id DESC";
$retVal = $sign->FunctionJSON($qry);

echo $qry1="Select count(*) as cnt from tw_material_inward_qc_individual where employee_id='".$employee_id."' and material_inward_qc_id='".$hdnOrderID."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$it=1;
while($x>=$i){
		
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
	
	$qry2="insert into tw_material_inward_qc_temp (employee_id,description,norms,first,second,third,total,average,created_by,created_on,created_ip) values('".$employee_id."','".$description."','".$norms."','".$first."','".$second."','".$third."','".$total."','".$average."','".$employee_id."','".$cur_date."','".$ip_address."')";
	$retVal2 = $sign->FunctionQuery($qry2);
	
	
	$it++;

	$i=$i+1;
}

$qry3="Select id,description,norms,first,second,third,total,average from tw_material_inward_qc_temp where employee_id = '".$employee_id."' ORDER BY id DESC";
$retVal3 = $sign->FunctionJSON($qry3);


$qry4="Select count(*) as cnt from tw_material_inward_qc_temp where employee_id = '".$employee_id."' ORDER BY id DESC";
$retVal4 = $sign->Select($qry4);

$decodedJSON2 = json_decode($retVal3);
$count = 0;
$i = 1;
$x=$retVal4;
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
if($valtotalamt==0){
	echo $table.",0";
}
else{
	echo $table.",".$valtotalamt;
}

?>