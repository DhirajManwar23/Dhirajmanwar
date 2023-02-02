<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
$company_id=$_POST['company_id'];

$qry="select id,sub_category_id,product_name,quantity,months_and_year from tw_consumption where company_id='".$company_id."' order by id asc";
$retVal = $sign->FunctionJSON($qry);
$qry1="Select count(*) as cnt from tw_consumption where company_id='".$company_id."'";
$retVal1 = $sign->Select($qry1);
$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Material Type</th><th>Product Type </th><th>Quantity</th><th>Month </th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$sub_category_id = $decodedJSON2->response[$count]->sub_category_id;
$count=$count+1;
$product_name = $decodedJSON2->response[$count]->product_name;
$count=$count+1;
$quantity = $decodedJSON2->response[$count]->quantity;
$count=$count+1;
$months_and_year = $decodedJSON2->response[$count]->months_and_year;
$count=$count+1;

$qry3="select sub_category_name from tw_subcategory_master where id='".$sub_category_id."'";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON3 = json_decode($retVal3);
$sub_category_name = $decodedJSON3->response[0]->sub_category_name;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$sub_category_name."</td>";
	$table.="<td>".$product_name."</td>";
	$table.="<td>".$quantity."</td>";
	$table.="<td>".$months_and_year."</td>";

	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$it++;
	$table.="</tr>";
	

$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>

