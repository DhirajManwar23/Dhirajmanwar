<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
$years_array=array();
$quantity_array=array();

$qry="select swm.name,me.quantity from tw_segregation_waste_type_master swm INNER JOIN tw_mixwaste_manual_entry me ON me.waste_type=swm.id  group by month(me.entry_date)";
$date_wise_quantity = $sign->FunctionData($qry);
echo json_encode($date_wise_quantity);

?>										
