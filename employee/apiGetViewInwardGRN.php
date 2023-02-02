<?php
// Include class definition
include_once "function.php";
session_start();
$company_id=$_SESSION["company_id"];
$inward_id = $_REQUEST["inward_id"];
$qry="SELECT mi.id,cd.CompanyName ,mi.supplier_id,mi.date FROM tw_material_inward_grn mi INNER JOIN tw_company_details cd ON mi.company_id=cd.ID WHERE mi.inward_id='".$inward_id."' order by mi.id desc";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);
$qry1="SELECT count(*) as cnt FROM tw_material_inward_grn WHERE inward_id='".$inward_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Supplier Name</th><th>Date</th><th>Edit</th><th>Delete</th><th>Document</th></tr></thead><tbody>";
	while($x>=$i){
		
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$CompanyName = $decodedJSON2->response[$count]->CompanyName;
	$count=$count+1;
	$supplier_id = $decodedJSON2->response[$count]->supplier_id;
	$count=$count+1;
	$date = $decodedJSON2->response[$count]->date;
	$count=$count+1;
	
	
	$table.="<tr>";
 	$table.="<td>".$it."</td>"; 
	$table.="<td>".$CompanyName."</td>";
	
	$table.="<td>".$date."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='DocumentRecord(".$id.")'><i class='ti-printer'></i></a></td>";
	$it++;
	$table.="</tr>";

	$i=$i+1;
}

	$table.="</tbody>";
	echo $table;
	?>