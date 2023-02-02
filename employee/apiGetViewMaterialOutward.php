<?php
// Include class definition
require "function.php";
session_start();
$employee_id=$_SESSION["employee_id"];
$qry="SELECT mo.id,cd.CompanyName,mo.final_total_amout FROM tw_material_outward mo INNER JOIN tw_company_details cd ON mo.customer_id = cd.ID WHERE mo.employee_id = '".$employee_id."' ORDER BY mo.id DESC";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward where employee_id='".$employee_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Customer Name</th><th>Total Amount</th><th>Edit</th><th>Delete</th><th>Document</th></tr></thead><tbody>";
	while($x>=$i){
		
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$CompanyName = $decodedJSON2->response[$count]->CompanyName;
	$count=$count+1;
	$final_total_amout = $decodedJSON2->response[$count]->final_total_amout;
	$count=$count+1;
	
	
	$table.="<tr>";
 	$table.="<td>".$it."</td>"; 
	$table.="<td>".$CompanyName."</td>";
	$table.="<td>".$final_total_amout."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='DocumentRecord(".$id.")'>Document</a></td>";
	$it++;
	$table.="</tr>";

	$i=$i+1;
}

	$table.="</tbody>";
	echo $table;
	?>