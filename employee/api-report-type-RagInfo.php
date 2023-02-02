<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
if (!isset($_SESSION["employee_id"])) {
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
$sign = new Signup();

	$Gender = array();
	$donut_chart_query = "select 100*sum(case when Gender = 'm' then 1 else 0 end)/count(*) Male, 100*sum(case when Gender = 'f' then 1 else 0 end)/count(*) Female from tw_ragpicker_information";
	
	$Bar_chart_query = "SELECT DISTINCT(location), (SELECT COUNT(*) FROM tw_ragpicker_information where SchemeDoc=,(SELECT COUNT(*) FROM tw_ragpicker_information where SchemeDoc='',(SELECT COUNT(*) FROM tw_ragpicker_information where SchemeDoc='' AND Status='Scheme/Document received' ) as PMSBY,(SELECT COUNT(*) FROM tw_ragpicker_information where SchemeDoc='PM Jeevan Jyoti Bima Yojna (PMJJBY)' FROM tw_ragpicker_information as r1 GROUP by SchemeDoc";
	
	$Genderdata = $sign->FunctionJSON($donut_chart_query);
	$GenderdecodedJSON1 = json_decode($Genderdata);
	$Male = $GenderdecodedJSON1->response[0]->Male;
	$Female = $GenderdecodedJSON1->response[1]->Female;
	
	array_push($Gender,number_format($Male,0),number_format($Female,0)  );
    $donut_chart_data =  $Gender ;
	$Bar_chart_data = $sign->FunctionData($Bar_chart_query);

	$reponse_data = array("donut_chart_data" => $donut_chart_data,"Bar_chart_data" => $Bar_chart_data);

	echo json_encode($reponse_data);
?>