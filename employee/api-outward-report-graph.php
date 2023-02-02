<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
if (!isset($_SESSION["employee_id"])) {
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
$start_date = $_POST["startDate"];
$end_date = $_POST["endDate"];
$report_type = $_POST["reportType"];
$Ward = $_POST["Ward"];
$employee_id = $_SESSION["employee_id"];
$sign = new Signup();
$days_array = array();
$quantity_array = array();

if ($report_type == "Daily" ) {
    $query = "SELECT  DATE(entry_date) as date ,SUM(quantity) AS quantity
	FROM tw_outward_data_entry 
	WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND ward='".$Ward."' and created_by='".$employee_id."'  GROUP BY entry_date ORDER BY entry_date";
	
	$donut_chart_query = "SELECT t2.name, SUM(t1.quantity) AS quantity FROM tw_outward_data_entry t1 INNER join tw_inward_waste_type_master t2 ON t1.material_name=t2.id WHERE t1.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' and t1.ward='".$Ward."' and t1.created_by='".$employee_id."' GROUP BY (t2.name), t1.entry_date";
}

if ($report_type == "Monthly") {
	$query = "SELECT CONCAT(monthname(entry_date), ' ',year(entry_date)) AS date,SUM(quantity) AS quantity
	FROM tw_outward_data_entry 
	WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND ward='".$Ward."' and created_by='".$employee_id."' GROUP BY month(entry_date), year(entry_date) ORDER BY entry_date";
	
	$donut_chart_query = "SELECT DISTINCT t2.name, SUM(t1.quantity) AS quantity 
	FROM tw_outward_data_entry t1 INNER join tw_inward_waste_type_master t2 ON t1.material_name=t2.id WHERE t1.entry_date BETWEEN '" . $start_date . "' AND '" . $end_date . "' and t1.ward='".$Ward."' and t1.created_by='".$employee_id."' GROUP BY (t2.name), month(t1.entry_date)";
}

if ($report_type == "Yearly") {
	$query = "SELECT year(entry_date) AS date,SUM(quantity) AS quantity
	FROM tw_outward_data_entry WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND ward='".$Ward."' and created_by='".$employee_id."' GROUP BY year(entry_date) ORDER BY entry_date";
	
	$donut_chart_query = "SELECT t2.name, SUM(t1.quantity) AS quantity FROM tw_outward_data_entry t1 INNER join tw_inward_waste_type_master t2 ON t1.material_name=t2.id WHERE t1.entry_date BETWEEN '" . $start_date . "' AND '" . $end_date . "' and t1.ward='".$Ward."' and t1.created_by='".$employee_id."' GROUP BY (t2.name), year(t1.entry_date)";
}

if ($report_type == "Weekly") {
	$query = "SELECT WEEK(entry_date) AS date,SUM(quantity) AS quantity
	FROM tw_outward_data_entry WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND ward='".$Ward."' and created_by='".$employee_id."' GROUP BY WEEK(entry_date) ORDER BY WEEK(entry_date)";
	
	$donut_chart_query = "SELECT t2.name, SUM(t1.quantity) AS quantity 
	FROM tw_outward_data_entry t1 INNER join tw_inward_waste_type_master t2 ON t1.material_name=t2.id WHERE t1.entry_date BETWEEN '" . $start_date . "' AND '" . $end_date . "' and t1.ward='".$Ward."' and t1.created_by='".$employee_id."' GROUP BY (t2.name), week(t1.entry_date)";
}
$line_chart_data = $sign->FunctionData($query);
$donut_chart_data = $sign->FunctionData($donut_chart_query);

$reponse_data = array("line_chart_data" => $line_chart_data, "donut_chart_data" => $donut_chart_data);

echo json_encode($reponse_data);
