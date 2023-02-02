<?php
// Include class definition
include_once "function.php";
$sign=new Signup();

$qry="select id,state_id,city_name,tier,visibility from tw_city_master order by id Desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_city_master";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count1 = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>State Name</th><th>City Name</th><th>Tier</th><th>Visibility</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){

$id = $decodedJSON2->response[$count1]->id;
$count1=$count1+1;
$state_id = $decodedJSON2->response[$count1]->state_id;
$count1=$count1+1;
$city_name = $decodedJSON2->response[$count1]->city_name;
$count1=$count1+1;
$tier = $decodedJSON2->response[$count1]->tier;
$count1=$count1+1;
$visibility  = $decodedJSON2->response[$count1]->visibility ;
$count1=$count1+1;

$qry3="select state_name from tw_state_master where id='".$state_id."'";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON3 = json_decode($retVal3);
$state_name = $decodedJSON3->response[0]->state_name;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$state_name."</td>";
	$table.="<td>".trim($city_name)."</td>";
	$table.="<td>".$tier."</td>";
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
