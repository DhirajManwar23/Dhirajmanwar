<?php  
session_start();
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$company_id = $_SESSION["company_id"];
//--
$data = array();
$datacolumn = array();
$year = date("Y"); 
$yearpuls = $year+1; 
$start_date=$year."-04-01";
$current_date=$yearpuls."-03-31";
$start=(new DateTime($start_date))->modify('first day of this month');
$end=(new DateTime($current_date))->modify('first day of next month');
$interval=DateInterval::createFromDateString('1 month');
$period=new DatePeriod($start, $interval, $end);
$srno=1;
//--
$filename = 'EPRSExportDataConsolidateCSV.csv';
$fp = new SplFileObject($filename, 'w');
$delimiter = ",";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
//----
$qry = "select id,state_name from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id=twpi.id WHERE twpi.company_id='".$company_id."')";
$retVal = $sign->FunctionJSON($qry);
$qrycnt = "select count(*) as cnt from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id=twpi.id WHERE twpi.company_id='".$company_id."')";
$retValcnt = $sign->select($qrycnt);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retValcnt;
while($x>=$i){
	
	$stateid = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$state_name = $decodedJSON2->response[$count]->state_name;
	$count=$count+1;
	array_push($data,$stateid);
	array_push($datacolumn,$state_name);
	//$fields = array("#","Month",$state_name);
	//fputcsv($fp,$state_name);
	$i=$i+1;
}
/* fputcsv($fp,$str);
fclose($fp); */
foreach ($datacolumn as $line)
{
	$fp->fputcsv($line);
   // fputcsv($fp, explode(',',$line));
}
//fclose($fp); 
//----
/* $qry2="SELECT cd.CompanyName as company_name, pi.po_number FROM tw_po_info pi Left JOIN tw_company_details cd ON pi.supplier_id=cd.ID WHERE pi.id ='".$po_id."'";
$retVal2 = $sign->FunctionJSON($qry2);
$decodedJSON = json_decode($retVal2);
$company_name = $decodedJSON->response[0]->company_name;
$po_number = $decodedJSON->response[1]->po_number;

if($type=="date"){
	$startdate=$sign->escapeString($_POST["startdate"]);
	$enddate=$sign->escapeString($_POST["enddate"]);
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,plant_wbs_number,plant_wbs_date,vehicle_number,eway_bill_number,lr_number,lr_date,category_name,material_name from tw_temp where po_id = '".$po_id."' and status='".$settingValueCompletedStatus."' and plant_wbs_date between '".$startdate." 00:00:00am' and '".$enddate." 11:59:00pm' order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValueCompletedStatus."' and plant_wbs_date between '".$startdate." 00:00:00am' and '".$enddate." 11:59:00pm'";
 }else if($type=="viewall"){
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,plant_wbs_number,plant_wbs_date,vehicle_number,eway_bill_number,lr_number,lr_date,category_name,material_name from tw_temp where po_id = '".$po_id."' and status='".$settingValueCompletedStatus."' order by id Asc";
	
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValueCompletedStatus."'";
}
$retVal = $sign->FunctionJSON($qry);
$retVal1 = $sign->Select($qry1);
$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;

while($x>=$i){
	
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$aggeragator_name = $decodedJSON2->response[$count]->aggeragator_name;
	$count=$count+1;
	$gst = $decodedJSON2->response[$count]->gst;
	$count=$count+1;
	$grn_number = $decodedJSON2->response[$count]->grn_number;
	$count=$count+1;
	$type_of_submission = $decodedJSON2->response[$count]->type_of_submission;
	$count=$count+1;
	$purchase_invoice_number = $decodedJSON2->response[$count]->purchase_invoice_number;
	$count=$count+1;
	$purchase_invoice_date = $decodedJSON2->response[$count]->purchase_invoice_date;
	$count=$count+1;
	$dispatched_state = $decodedJSON2->response[$count]->dispatched_state;
	$count=$count+1;
	$dispatched_place = $decodedJSON2->response[$count]->dispatched_place;
	$count=$count+1;
	$invoice_quantity = $decodedJSON2->response[$count]->invoice_quantity;
	$count=$count+1;
	$plant_quantity = $decodedJSON2->response[$count]->plant_quantity;
	$count=$count+1;
	$aggregator_wbs_number = $decodedJSON2->response[$count]->aggregator_wbs_number;
	$count=$count+1;
	$aggregator_wbs_date = $decodedJSON2->response[$count]->aggregator_wbs_date;
	$count=$count+1;
	$plant_wbs_number = $decodedJSON2->response[$count]->plant_wbs_number;
	$count=$count+1;
	$plant_wbs_date = $decodedJSON2->response[$count]->plant_wbs_date;
	$count=$count+1;
	$vehicle_number = $decodedJSON2->response[$count]->vehicle_number;
	$count=$count+1;
	$eway_bill_number = $decodedJSON2->response[$count]->eway_bill_number;
	$count=$count+1;
	$lr_number = $decodedJSON2->response[$count]->lr_number;
	$count=$count+1;
	$lr_date = $decodedJSON2->response[$count]->lr_date;
	$count=$count+1;
	$category_name = $decodedJSON2->response[$count]->category_name;
	$count=$count+1;
	$material_name = $decodedJSON2->response[$count]->material_name;
	$count=$count+1;
	
	if($purchase_invoice_date==""){
		$purchase_invoice_date="";
    }
    else if($purchase_invoice_date=="0000-00-00 00:00:00"){
	    $purchase_invoice_date="";
    }
	else{
		$purchase_invoice_date=date("Y-m-d", strtotime($purchase_invoice_date));
	}
	
	if($aggregator_wbs_date==""){
		$aggregator_wbs_date="";
    }
    else if($aggregator_wbs_date=="0000-00-00 00:00:00"){
	    $aggregator_wbs_date="";
    }
	else{
		$aggregator_wbs_date=date("Y-m-d", strtotime($aggregator_wbs_date));
	}
	
	if($plant_wbs_date==""){
		$plant_wbs_date="";
    }
    else if($plant_wbs_date=="0000-00-00 00:00:00"){
	    $plant_wbs_date="";
    }
	else{
		$plant_wbs_date=date("Y-m-d", strtotime($plant_wbs_date));
	}
	if($lr_date==""){
		$lr_date="";
    }
    else if($lr_date=="0000-00-00 00:00:00"){
	    $lr_date="";
    }
	else{
		$lr_date=date("Y-m-d", strtotime($lr_date));
	}
	
	$lineData = array($it,$po_number,$company_name,$aggeragator_name,$gst,$grn_number,$type_of_submission,$purchase_invoice_number,$purchase_invoice_date,$dispatched_state,$dispatched_place,$invoice_quantity,$plant_quantity,$aggregator_wbs_number,$aggregator_wbs_date,$plant_wbs_number,$plant_wbs_date,$vehicle_number,$eway_bill_number,$lr_number,$lr_date,$category_name,$material_name);	
	fputcsv($fp,$lineData,$delimiter); 
	$i++;
	$it++;
}
	 */
/* $lineData = array("karuna");	
fputcsv($fp,$lineData,$delimiter); 
fclose($fp); */
//echo $query;
echo "success";
?> 