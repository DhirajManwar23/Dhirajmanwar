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
$StartDate=$_POST["StartDate"];
$EndDate=$_POST["EndDate"];
$qry="select swm.name,me.quantity from tw_segregation_waste_type_master swm INNER JOIN tw_mixwaste_manual_entry me ON me.waste_type=swm.id where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."'  group by year(me.entry_date)";
$date_wise_quantity = $sign->FunctionData($qry);
echo json_encode($date_wise_quantity);

?>										
