<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();

$qry="select id,name,priority,description,visibility,category_id,sub_category_id from tw_product_type_master order by id Desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_product_type_master";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Category Name</th><th>Sub Category Name</th><th>Material Name</th><th>Priority</th><th>Visibility</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$name = $decodedJSON2->response[$count]->name;
$count=$count+1;
$priority = $decodedJSON2->response[$count]->priority;
$count=$count+1;
$description = $decodedJSON2->response[$count]->description;
$count=$count+1;
$visibility  = $decodedJSON2->response[$count]->visibility ;
$count=$count+1;
$category_id = $decodedJSON2->response[$count]->category_id;
$count=$count+1;
$sub_category_id = $decodedJSON2->response[$count]->sub_category_id;
$count=$count+1;

$qry3="select category_name from tw_category_master where id='".$category_id."'";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON3 = json_decode($retVal3);
$category_name = $decodedJSON3->response[0]->category_name;

$qry4="select sub_category_name from tw_subcategory_master where id='".$sub_category_id."'";
$retVal4 = $sign->FunctionJSON($qry4);
$decodedJSON4 = json_decode($retVal4);
$sub_category_name = $decodedJSON4->response[0]->sub_category_name;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$category_name."</td>";
	$table.="<td>".$sub_category_name."</td>";
	$table.="<td>".$name."</td>";
	$table.="<td>".$priority."</td>";
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

	