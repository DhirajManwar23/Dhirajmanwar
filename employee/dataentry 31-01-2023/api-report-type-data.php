<?php
header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION["employee_id"])) {
	header("Location:pgEmployeeLogIn.php");
}

// Include class definition
include_once "function.php";
	include_once "commonFunctions.php";	
	$commonfunction=new Common();
$start_date = $_POST["startDate"];
$end_date = $_POST["endDate"];
$report_type = $_POST["reportType"];
$Ward = $_POST["Ward"];
$company_id = $_SESSION["company_id"];
$line_chart1 = "";
$donut_chartseg ="";
$sign = new Signup();
$days_array = array();
$quantity_array = array();

	$settingKWardEMp=$commonfunction->getMRFSettingValue("ward_1");
	$settingDWardEMp=$commonfunction->getMRFSettingValue("ward_2");
	$employee_id = $_SESSION["employee_id"];
	$settingValuePartner=$commonfunction->getSettingValue("Partner");
	$settingCompletedStatus=$commonfunction->getSettingValue("Completed Status");
	$designationQry="SELECT employee_designation FROM `tw_employee_registration` where id='".$settingValuePartner."'";
	$designation = $sign->SelectF($designationQry,'employee_designation'); 

		if($Ward==1){
		$employee_id=$settingKWardEMp;
	}
	else if($Ward==2){
		$employee_id=$settingDWardEMp;
	}
	else{
	$employee_id = $_SESSION["employee_id"];
	}

if ($report_type == "Daily") {
	$query = "SELECT entry_date AS date ,SUM(quantity) AS quantity
	FROM tw_mixwaste_manual_entry 
	WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND ward='".$Ward."' and created_by='".$company_id."' GROUP BY entry_date ORDER BY entry_date";
	
	if($designation!=$settingValuePartner){
    $query1 = "SELECT created_on AS date ,SUM(quantity) AS quantity
	FROM tw_mix_waste_collection_details 
	WHERE created_on BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  and created_by='".$employee_id."' and status='".$settingCompletedStatus."' GROUP BY created_on ORDER BY created_on";
	
	$donut_chart_query1 = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity
	FROM tw_mix_waste_collection_details mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.created_on BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  and mme.status='".$settingCompletedStatus."' and mme.created_by='".$employee_id."' GROUP BY mme.waste_type";
	
	
	$line_chart1 = $sign->FunctionData($query1);
	$donut_chartseg = $sign->FunctionData($donut_chart_query1);
	}
	$donut_chart_query = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mixwaste_manual_entry mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  AND mme.ward='".$Ward."' and mme.created_by='".$company_id."' GROUP BY mme.waste_type";
	
   
}

if ($report_type == "Monthly") {
	$query = "SELECT CONCAT(monthname(entry_date), ' ',year(entry_date)) AS date,SUM(quantity) AS quantity
	FROM tw_mixwaste_manual_entry 
	WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND ward='".$Ward."' and created_by='".$company_id."' GROUP BY month(entry_date), year(entry_date) ORDER BY entry_date";
	
	if($designation!=$settingValuePartner){
	$query1="SELECT CONCAT(monthname(created_on), ' ',year(created_on)) AS date,SUM(quantity) AS quantity
	FROM tw_mix_waste_collection_details  
	WHERE created_on BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  and created_by='".$employee_id."' and status='".$settingCompletedStatus."' GROUP BY month(created_on), year(created_on) ORDER BY created_on";
	
	$donut_chart_query1 = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity FROM tw_mix_waste_collection_details mme, tw_inward_waste_type_master swtm WHERE mme.waste_type = swtm.id AND mme.created_on BETWEEN '" . $start_date . "' AND '" .  $end_date . "' and mme.status='".$settingCompletedStatus."' and mme.created_by='".$employee_id."' GROUP BY mme.waste_type";
	
	$line_chart1 = $sign->FunctionData($query1);
	$donut_chartseg = $sign->FunctionData($donut_chart_query1);
	}
	$donut_chart_query = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mixwaste_manual_entry mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  AND mme.ward='".$Ward."' and mme.created_by='".$company_id."' GROUP BY mme.waste_type";
	
	
}

if ($report_type == "Yearly") {
	 $query = "SELECT year(entry_date) AS date,SUM(quantity) AS quantity
	FROM tw_mixwaste_manual_entry WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND ward='".$Ward."' and created_by='".$company_id."'  GROUP BY year(entry_date) ORDER BY entry_date";
	
	if($designation!=$settingValuePartner){
	$query1 = "SELECT year(created_on) AS date,SUM(quantity) AS quantity
	FROM tw_mix_waste_collection_details WHERE created_on BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  and created_by='".$employee_id."' GROUP BY year(created_on) ORDER BY created_on";
	
	$donut_chart_query1 = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mix_waste_collection_details mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.created_on BETWEEN '" . $start_date . "' AND '" .  $end_date . "' and mme.status='".$settingCompletedStatus."'  and mme.created_by='".$employee_id."' GROUP BY mme.waste_type, year(mme.created_on)";
	$line_chart1 = $sign->FunctionData($query1);
	$donut_chartseg = $sign->FunctionData($donut_chart_query1);
	}
	$donut_chart_query = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mixwaste_manual_entry mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  AND mme.ward='".$Ward."' and mme.created_by='".$company_id."' GROUP BY mme.waste_type, year(mme.entry_date)";
	
	
}

if ($report_type == "Weekly") {
	$query = "SELECT WEEK(entry_date) AS date,SUM(quantity) AS quantity
	FROM tw_mixwaste_manual_entry WHERE entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND ward='".$Ward."' and created_by='".$company_id."'  GROUP BY WEEK(entry_date) ORDER BY WEEK(entry_date)";
	
	if($designation!=$settingValuePartner){
	$query1 = "SELECT WEEK(created_on) AS date,SUM(quantity) AS quantity
	FROM tw_mix_waste_collection_details WHERE created_on BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  and created_by='".$employee_id."' GROUP BY WEEK(created_on) ORDER BY WEEK(created_on)";
	
	$donut_chart_query1 = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mix_waste_collection_details mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.created_on BETWEEN '" . $start_date . "' AND '" .  $end_date . "'  and mme.created_by='".$employee_id."' and mme.status='".$settingCompletedStatus."' GROUP BY mme.waste_type";
	
	$line_chart1 = $sign->FunctionData($query1);
	$donut_chartseg = $sign->FunctionData($donut_chart_query1);
	}
	$donut_chart_query = "SELECT swtm.name AS waste_type, SUM(mme.quantity) AS quantity 
	FROM tw_mixwaste_manual_entry mme, tw_inward_waste_type_master swtm 
	WHERE mme.waste_type = swtm.id AND mme.entry_date BETWEEN '" . $start_date . "' AND '" .  $end_date . "' AND mme.ward='".$Ward."' and mme.created_by='".$company_id."' GROUP BY mme.waste_type";
	
	
} 

/* START MERGING */
$line_chart = $sign->FunctionData($query); //Manual

$line_chart_data=array_merge($line_chart,$line_chart1);
$donut_chartdata = $sign->FunctionData($donut_chart_query);
/*END MERGING */

// $line_chart_data = $sign->FunctionData($query); //Manual
// $line_chart_data1 = $sign->FunctionData($query1); //App



$donut_chart_data=array_merge($donut_chartdata,$donut_chartseg);

$reponse_data = array("line_chart_data" => $line_chart_data, "donut_chart_data" => $donut_chart_data);

echo json_encode($reponse_data);
