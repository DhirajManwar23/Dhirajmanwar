<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
$days_array=array();
$quantity_array=array();

$date_wise_quantity_query="SELECT entry_date,SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry GROUP BY entry_date order by entry_date";
//$DateretVal = $sign->FunctionJSON($dateQry);
$date_wise_quantity = $sign->FunctionData($date_wise_quantity_query);

echo json_encode($date_wise_quantity);

?>										