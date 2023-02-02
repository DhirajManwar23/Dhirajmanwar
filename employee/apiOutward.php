<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");

$qry="select id,entry_date,customer_name,material_name,quantity,ward from tw_outward_data_entry order by id asc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_outward_data_entry";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Date</th><th>Material Name</th><th>Customer Name</th><th>Quantity</th><th>Ward</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$entry_date = $decodedJSON2->response[$count]->entry_date;
$count=$count+1;
$customer_name = $decodedJSON2->response[$count]->customer_name;
$count=$count+1;
$material_name = $decodedJSON2->response[$count]->material_name;
$count=$count+1;
$quantity  = $decodedJSON2->response[$count]->quantity ;
$count=$count+1;
$ward  = $decodedJSON2->response[$count]->ward ;
$count=$count+1;

$materialName="select name from tw_inward_waste_type_master where id='".$material_name."'";
$retVal2 = $sign->FunctionJSON($materialName);
$decodedJSON3 = json_decode($retVal2);
$materialName = $decodedJSON3->response[0]->name;

$customerName="select name from tw_partner_outward_master where id='".$customer_name."'";
$retVal3 = $sign->FunctionJSON($customerName);
$decodedJSON3 = json_decode($retVal3);
$name = $decodedJSON3->response[0]->name;

$wardName="select ward_name from tw_ward_master where id='".$ward."'";
$retVal4 = $sign->FunctionJSON($wardName);
$decodedJSON3 = json_decode($retVal4);
$ward_name = $decodedJSON3->response[0]->ward_name;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	//$table.="<td>".$cur_date."</td>";
	$table.="<td>".date("d-m-Y",strtotime($entry_date))."</td>";
	$table.="<td>".$materialName."</td>";
	$table.="<td>".$name."</td>";
	$table.="<td>".$quantity."</td>";
	$table.="<td>".$ward_name."</td>";
	
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$it++;
	$table.="</tr>";
$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>

	