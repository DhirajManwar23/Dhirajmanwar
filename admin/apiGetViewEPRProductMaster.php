<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();

$qry="select id,epr_category_id,epr_product_name,priority,visibility from tw_epr_product_master order by id Desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_epr_product_master";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>#</th><th>EPR Category Name</th><th>EPR Product Name</th><th>Priority</th><th>Visibility</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$epr_category_id = $decodedJSON2->response[$count]->epr_category_id;
$count=$count+1;
$epr_product_name = $decodedJSON2->response[$count]->epr_product_name;
$count=$count+1;
$priority = $decodedJSON2->response[$count]->priority;
$count=$count+1;
$visibility  = $decodedJSON2->response[$count]->visibility ;
$count=$count+1;

$qry3="select epr_category_name from tw_epr_category_master where id='".$epr_category_id."'";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON3 = json_decode($retVal3);
$epr_category_name = $decodedJSON3->response[0]->epr_category_name;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$epr_category_name."</td>";
	$table.="<td>".$epr_product_name."</td>";
	$table.="<td>".$priority."</td>";
	$table.="<td>".$visibility."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'><i class='ti-pencil'></i></a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'><i class='ti-trash'></i></a></td>";
	$it++;
	$table.="</tr>";
	

$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>

