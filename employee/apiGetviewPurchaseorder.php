<?php 

session_start();
include_once "function.php";
$sign=new Signup();
$company_id=$_SESSION["company_id"];
$employee_id=$_SESSION["employee_id"];

$qry="select PI.ID,PI.supplier_id,PI.date_of_po,PI.po_number,PI.total_quantity,CD.CompanyName from tw_po_info PI INNER JOIN tw_company_details CD ON PI.supplier_id=CD.ID where PI.employee_id='".$employee_id."'";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_po_info where employee_id='".$employee_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>PO Number</th><th>Supplier Name</th><th>quantity</th><th>Date of po</th><th>Edit</th><th>Print</th></thead><tbody>";
while($x>=$i){
		
	$id = $decodedJSON2->response[$count]->ID;
	$count=$count+1;
	$supplier_id = $decodedJSON2->response[$count]->supplier_id;
	$count=$count+1;
	$date_of_po = $decodedJSON2->response[$count]->date_of_po;
	$count=$count+1;
	$po_number = $decodedJSON2->response[$count]->po_number;
	$count=$count+1;
	$Qty  = $decodedJSON2->response[$count]->total_quantity ;
	$count=$count+1; 
	$SupplierName  = $decodedJSON2->response[$count]->CompanyName ;
	$count=$count+1; 
	
	
	//$QtyQry="Select quantity from tw_po_details where "
	
	$table.="<tr>";
 	$table.="<td>".$it."</td>"; 
 	$table.="<td>".$po_number."</td>"; 
	$table.="<td>".$SupplierName."</td>";
	$table.="<td>".$Qty."</td>";
	$table.="<td>".$date_of_po."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.",".$supplier_id.")'><i class='ti-pencil' /></a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='PrintRecord(".$id.",".$supplier_id.")'><i class='ti-printer' /></a></td>";
	$it++;
	$table.="</tr>";

	$i=$i+1;
}



$table.="</tbody>";
echo $table;
?>