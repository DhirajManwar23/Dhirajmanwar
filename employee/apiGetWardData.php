<?php
session_start();
include("function.php");
include("commonFunctions.php");
include("mailFunction.php");

$commonfunction=new Common();
$sign=new Signup();

$Ward=$_POST['ward'];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");


$cur_date = date("Y-m-d");
 $YesterdayDate=date('Y-m-d',strtotime("-1 days"));
$date = $cur_date;
$year = explode('-', $date);
$Currentfetchyear=$year[0];
$fetchmonth = $year[1];
if($fetchmonth==01 || $fetchmonth==02 || $fetchmonth==03){
	$fetchyear = $year[0]-1;
	$fetchyear2 = $year[0];
}else{
$fetchyear = $year[0];
$fetchyear2 = $year[0]+1;
}


$fiyear=$fetchyear."-04-01";
$fiyear2=$fetchyear2."-03-31";

$dateCntqry = "select IFNULL(SUM(quantity), 00.00) as quantity
	FROM tw_mixwaste_manual_entry where entry_date='" . $YesterdayDate . "' AND ward='".$Ward."'";
$dateCnt = Round($sign->SelectF($dateCntqry, "quantity"));

$monthcount = "select IFNULL(SUM(quantity), 00.00) as quantity
	FROM tw_mixwaste_manual_entry where month(entry_date)='" . $fetchmonth . "' and year(entry_date)='".$Currentfetchyear."' AND ward='".$Ward."' ";
$retmonthVal = Round($sign->SelectF($monthcount, "quantity"));

$year = date('Y') + (int)((date('m') - 1) / 6);
 $qry1 = " select IFNULL(SUM(quantity), 00.00) as quantity
	FROM tw_mixwaste_manual_entry where entry_date BETWEEN '".$fiyear."' AND '".$fiyear2."' AND ward='".$Ward."'";
$retVal1 = Round($sign->SelectF($qry1, 'quantity'));

$reponse_data=array();
 array_push($reponse_data,$retVal1,$retmonthVal,$dateCnt);
echo json_encode($reponse_data);
?>