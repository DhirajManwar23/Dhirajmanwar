<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();

$qry="select id,city_id,ward_name,city_id,priority,visibility from tw_ward_master order by id Desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_ward_master";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>City Name</th><th>Ward Name</th><th>Visibility</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$city_id = $decodedJSON2->response[$count]->city_id;
$count=$count+1;
$ward_name = $decodedJSON2->response[$count]->ward_name;
$count=$count+1;
$priority = $decodedJSON2->response[$count]->priority;
$count=$count+1;
$visibility  = $decodedJSON2->response[$count]->visibility ;
$count=$count+1;

$qry3="select city_name from tw_city_master where id='".$city_id."'";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON3 = json_decode($retVal3);
$city_name = $decodedJSON3->response[0]->city_name;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$city_name."</td>";
	$table.="<td>".$ward_name."</td>";
	$table.="<td>".$visibility."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$it++;
	$table.="</tr>";
	

$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	