<?php
// Include class definition
require "function.php";
session_start();
$employee_id=$_SESSION["employee_id"];
$qry="SELECT mi.id,cd.CompanyName,mm.product_name,mi.inward_quantity,mi.net_quantity FROM tw_material_inward mi INNER join tw_company_details cd ON mi.supplier=cd.ID INNER JOIN tw_product_management mm ON mi.material_name = mm.id WHERE employee_id='".$employee_id."' ORDER BY id DESC";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_inward where employee_id='".$employee_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Customer Name</th><th>Material Name</th><th>Inward Quantity</th><th>Net Amount</th><th>Edit</th><th>Delete</th><th>Document</th></tr></thead><tbody>";
	while($x>=$i){
		
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$CompanyName = $decodedJSON2->response[$count]->CompanyName;
	$count=$count+1;
	$product_name = $decodedJSON2->response[$count]->product_name;
	$count=$count+1;
	$inward_quantity = $decodedJSON2->response[$count]->inward_quantity;
	$count=$count+1;
	$net_quantity  = $decodedJSON2->response[$count]->net_quantity ;
	$count=$count+1; 
	
	$table.="<tr>";
 	$table.="<td>".$it."</td>"; 
	$table.="<td>".$CompanyName."</td>";
	$table.="<td>".$product_name."</td>";
	$table.="<td>".$inward_quantity."</td>";
	$table.="<td>".$net_quantity."</td>"; 
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