<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();

$qry="select id,country_id,state_name,country_id,region,visibility from tw_state_master order by id Desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_state_master";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Country Name</th><th>State Name</th><th>Region</th><th>Visibility</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$country_id = $decodedJSON2->response[$count]->country_id;
$count=$count+1;
$state_name = $decodedJSON2->response[$count]->state_name;
$count=$count+1;
$region = $decodedJSON2->response[$count]->region;
$count=$count+1;
$visibility  = $decodedJSON2->response[$count]->visibility ;
$count=$count+1;

$qry3="select country_name from tw_country_master where id='".$country_id."'";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON3 = json_decode($retVal3);
$country_name = $decodedJSON3->response[0]->country_name;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$country_name."</td>";
	$table.="<td>".$state_name."</td>";
	$table.="<td>".$region."</td>";
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

	