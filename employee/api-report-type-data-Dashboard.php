<?php
header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION["employee_id"])) {
	header("Location:pgEmployeeLogIn.php");
}

// Include class definition
include_once "function.php";

$cur_date = date("Y-m-d");
$date = $cur_date;
$year = explode('-', $date);
$fetchyear = $year[0];
$fetchmonth = $year[1];

$fetchyear2 = $year[0]+1;

// $start_date=$fetchyear."-04-01";
// $end_date=$fetchyear2."-03-31";



$start_date = '2022-04-01';
$end_date = '2023-03-31';
$report_type = $_POST["reportType"];

$sign = new Signup();
$days_array = array();
$quantity_array = array();


if ($report_type == "Daily") {
	$query = "SELECT entry_date AS date ,SUM(quantity) AS quantity
	FROM tw_mixwaste_manual_entry 
	WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' GROUP BY entry_date ORDER BY entry_date";
	
	$donut_chart_query = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity,mme.entry_date 
	FROM tw_mixwaste_manual_entry mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' GROUP BY mme.waste_type";
}

if ($report_type == "Monthly") {
	$query = "SELECT CONCAT(monthname(entry_date), ' ',year(entry_date)) AS date,SUM(quantity) AS quantity
	FROM tw_mixwaste_manual_entry 
	WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' GROUP BY month(entry_date), year(entry_date) ORDER BY entry_date";
	
	$donut_chart_query = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mixwaste_manual_entry mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' GROUP BY mme.waste_type";
}

if ($report_type == "Yearly") {
	$query = "SELECT year(entry_date) AS date,SUM(quantity) AS quantity
	FROM tw_mixwaste_manual_entry WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' GROUP BY year(entry_date) ORDER BY entry_date";
	
	$donut_chart_query = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mixwaste_manual_entry mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' GROUP BY mme.waste_type, year(mme.entry_date)";
}

if ($report_type == "Weekly") {
	$query = "SELECT WEEK(entry_date) AS date,SUM(quantity) AS quantity
	FROM tw_mixwaste_manual_entry WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' GROUP BY WEEK(entry_date) ORDER BY WEEK(entry_date)";
	
	$donut_chart_query = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mixwaste_manual_entry mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' GROUP BY mme.waste_type";
} 

$line_chart_data = $sign->FunctionData($query);
$donut_chart_data = $sign->FunctionData($donut_chart_query);

$reponse_data = array("line_chart_data" => $line_chart_data, "donut_chart_data" => $donut_chart_data);

echo json_encode($reponse_data);
