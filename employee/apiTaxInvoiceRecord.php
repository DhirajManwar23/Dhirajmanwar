<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";

$commonfunction=new Common();
$sign=new Signup();

$employee_id = $_SESSION["employee_id"];
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");	


$qry="SELECT id,invoice_number,invoice_date,final_amount FROM tw_thirdparty_invoice where created_by='".$employee_id."'";
$retVal = $sign->FunctionJSON($qry);



$qry1="Select count(*) as cnt FROM tw_thirdparty_invoice where created_by='".$employee_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>#</th><th>Invoice Number</th><th>Invoice Date</th><th>Amount</th><th>Edit</th><th>View</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$invoice_number = $decodedJSON2->response[$count]->invoice_number;
$count=$count+1;
$invoice_date =$decodedJSON2->response[$count]->invoice_date;
$count=$count+1;
$final_amount =$decodedJSON2->response[$count]->final_amount;
$count=$count+1;


	$table.="<tr>";
	$table.="<td>".$it."</td>";
	$table.="<td>".$invoice_number."</td>";
	$table.="<td>".date("Y-m-d",strtotime($invoice_date))."</td>";
	$table.="<td>".$final_amount."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'><ti class='ti-pencil'></ti></a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='ViewRecord(".$id.")'><ti class='ti-eye'></ti></a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'><ti class='ti-trash'></ti></a></td>";
	$it++;
	$table.="</tr>";
$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>

	