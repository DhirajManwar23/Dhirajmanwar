<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");
$StartDate=$_POST["StartDate"];
$EndDate=$_POST["EndDate"];
$Ward=$_POST["Ward"];
$employee_id = $_SESSION["employee_id"];

$qry="select t1.id,t1.entry_date,t1.customer_name,t2.name,t1.quantity from tw_outward_data_entry t1 INNER join tw_inward_waste_type_master t2 ON t1.material_name=t2.id WHERE t1.entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."'  AND ward='".$Ward."' order by t1.id asc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_outward_data_entry where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."'  AND ward='".$Ward."' and created_by='".$employee_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
if($retVal1==0){
		$table.="<tr><td colspan='7' class='text-center'>No records found</td></tr>";
		echo $table;
	}
else{

$valtotalQty=array();
$first_run=true;

$table.="<thead><tr><th>#</th><th>Date</th><th>Customer Name</th><th>Material Name</th><th>Quantity</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$entry_date = $decodedJSON2->response[$count]->entry_date;
$count=$count+1;
$customer_name = $decodedJSON2->response[$count]->customer_name;
$count=$count+1;
$material_name = $decodedJSON2->response[$count]->name;
$count=$count+1;
$quantity  = $decodedJSON2->response[$count]->quantity ;
$count=$count+1;

$customerNameQry="SELECT name FROM `tw_partner_outward_master` where id='".$customer_name."'";
$customerName = $sign->selectF($customerNameQry,"name");

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	//$table.="<td>".$cur_date."</td>";
	$table.="<td>".date("d-m-Y",strtotime($entry_date))."</td>";
	$table.="<td>".$customerName."</td>";
	$table.="<td>".$material_name."</td>";
	$table.="<td>".round($quantity)."</td>";
		
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$it++;
	$table.="</tr>";
	

$i=$i+1;

}
$table.="</tbody>";
echo $table;
}
?>

	