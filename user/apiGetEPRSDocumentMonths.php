<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();

date_default_timezone_set('Asia/Kolkata');
$current_date = date('m/d/Y', time());

$po_id=$_POST["po_id"];
$state_id=$_POST["state_id"];
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValueic_approved= $commonfunction->getSettingValue("ic_approved");
$settingValueic_rejected= $commonfunction->getSettingValue("ic_rejected");
$settingValueic_pending= $commonfunction->getSettingValue("ic_pending");
$data = array();
$status = array();
$arrcompany_status = array();
$arrsupplier_status = array();

$qrydate="SELECT count(id) as countid, CONCAT(DATE_FORMAT(plant_wbs_date,'%b'),' ',EXTRACT(Year from plant_wbs_date)) as tDate FROM tw_temp where status='".$settingValueCompletedStatus."' and po_id='".$po_id."' group by DATE_FORMAT(plant_wbs_date,'%Y%m')";
$Docqrydate = $sign->FunctionJSON($qrydate);
$decodedJSON2 = json_decode($Docqrydate);

$qrydatecnt="SELECT count(DISTINCT(DATE_FORMAT(plant_wbs_date,'%Y%m'))) as cnt FROM tw_temp where status='".$settingValueCompletedStatus."' and po_id='".$po_id."'";
$cntqrydate = $sign->Select($qrydatecnt);

$count = 0;
$i = 1;
$x=$cntqrydate;
while($x>=$i){
	$countid = $decodedJSON2->response[$count]->countid;
	$count=$count+1;
	$tDate = "".$decodedJSON2->response[$count]->tDate;
	$count=$count+1;
	if ($countid>0)
	{
		array_push($data,$tDate);
	}
	$i=$i+1;
}
//---Status
$qrystatus="SELECT IFNULL (replace(month, ',', ''),0) as  month,IFNULL (replace(company_status, ',', ''),0) as  company_status,IFNULL (replace(supplier_status, ',', ''),0) as  supplier_status,reason FROM tw_epr_approval WHERE (supplier_status='".$settingValueApprovedStatus."' or supplier_status='".$settingValueRejectedStatus."' or company_status='".$settingValueApprovedStatus."' or company_status='".$settingValueRejectedStatus."') and po_id='".$po_id."' order by id ASC";
$Docqrystatus = $sign->FunctionJSON($qrystatus);
$decodedJSON = json_decode($Docqrystatus);

$qrystatuscnt="SELECT count(*) as cnt FROM tw_epr_approval WHERE (supplier_status='".$settingValueApprovedStatus."' or supplier_status='".$settingValueRejectedStatus."' or company_status='".$settingValueApprovedStatus."' or company_status='".$settingValueRejectedStatus."') and po_id='".$po_id."'";
$cntqrystatus = $sign->Select($qrystatuscnt);

$count1 = 0;
$i1 = 1;
$x1=$cntqrystatus;
while($x1>=$i1){
	$month = $decodedJSON->response[$count1]->month;
	$count1=$count1+1;
	$company_status = $decodedJSON->response[$count1]->company_status;
	$count1=$count1+1;
	$supplier_status = $decodedJSON->response[$count1]->supplier_status;
	$count1=$count1+1;
	$reason = $decodedJSON->response[$count1]->reason;
	$count1=$count1+1;
	array_push($status,$month);
	array_push($arrcompany_status,$company_status,$reason);
	array_push($arrsupplier_status,$supplier_status);
	$i1=$i1+1;
}
//---Start Code
$avar="";
$qry = "SELECT DISTINCT start_date,delivery_date,fulfillment_cycle FROM tw_po_details WHERE po_id='".$po_id."' group by start_date, delivery_date";
$DocValpo = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($DocValpo);
$start_date = $decodedJSON->response[0]->start_date; 
$delivery_date = $decodedJSON->response[1]->delivery_date;
$fulfillment_cycle = $decodedJSON->response[2]->fulfillment_cycle;

$fulfillment_cycle="Yearly";
 
if($fulfillment_cycle=="Quarterly"){
	
$array1=array('April','May','June',);
$array2=array('July','August','September',);
$array3=array('October','November','December',);
$array4=array('January','February','March',);

$mon = date('F',strtotime($start_date));
$mon1 = date('F',strtotime($delivery_date)); 
$i="";

$quarter1=$array1[0].",".$array1[1].",".$array1[2]."<br />";
$quarter2=$array2[0].",".$array2[1].",".$array2[2]."<br />";
$quarter3=$array3[0].",".$array3[1].",".$array3[2]."<br />";
$quarter4=$array4[0].",".$array4[1].",".$array4[2]."<br />";
$data1=array();

 if((in_array($mon,$array1)) || (in_array($mon1,$array1)))
  {
	
	 $quarter1=$array1[0].','.$array1[1].','.$array1[2];
	array_push($data1,$quarter1);
  // $str = implode(',', $data1);
	//echo $data1; 
  }
   else if ((in_array($mon,$array2)) || (in_array($mon1,$array2)))
  {
	 
	 $quarter2=$array2[0].','.$array2[1].','.$array2[2];
	 array_push($data1,$quarter2);
	//$str = implode(',', $data1);	
	 //echo $data1; 
  }
  else if ((in_array($mon,$array3)) || (in_array($mon1,$array3)))
  {
	 $quarter3=$array3[0].','.$array3[1].','.$array3[2];
	 array_push($data1,$quarter3);
	//$str = implode(',', $data1);
	// echo $data1; 
  }
  else if ((in_array($mon,$array4)) || (in_array($mon1,$array4)))
  {

	$quarter4=$array4[0].','.$array4[1].','.$array4[2];
	array_push($data1,$quarter4);
	// $str = implode(',', $data1);	
	//echo $data1; 
  }
  else{
	  echo "Not found";
  }
  /* $str = implode(',', $data1);
	echo $str; */
	print_r($data1);

}else if($fulfillment_cycle=="Semi Annual"){
	 
$months1 = array('April','May','June','July ','August','September',);
$months2 = array('October','November','December','January','February','March',);
$i="";
$mon = date('F',strtotime($start_date));

$mon1 = date('F',strtotime($delivery_date)); 
if ((in_array($mon,$months1)) || (in_array($mon1,$months1)))
  {
	echo $SemiAnnual1=$months1[0].",".$months1[1].",".$months1[2].",".$months1[3].",".$months1[4].",".$months1[5];
  }
  else if ((in_array($mon,$months2)) || (in_array($mon1,$months2))){
	  echo $SemiAnnual2=$months2[0].",".$months2[1].",".$months2[2].",".$months2[3].",".$months2[4].",".$months2[5];
  }
  else{
	  
  }
  //-------------------------------//

}
else if($fulfillment_cycle=="Yearly"){
if (date('m') <= 4) {//Upto June 2014-2015
    $financial_year = date('m').(date('Y')-1) . '-' . date('Y');
} else {//After June 2015-2016
    $financial_year = date('m').date('Y') . '-' . (date('Y') + 1);
}
echo $financial_year;
$months = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',
);
$i="";
$mon = date('F',strtotime($start_date));

$mon1 = date('F',strtotime($delivery_date)); 
if((in_array($mon,$months)) || (in_array($mon1,$months)))
  {
  echo  $Annual=$months[3].",".$months[4].",".$months[5].",".$months[6].",".$months[7].",".$months[8].",".$months[9].",".$months[10].",".$months[11].",".$months[0].",".$months[1].",".$months[2];
  } 
  
}
else{
	//echo "karuna";
}
//---End Code
$qry = "SELECT start_date, delivery_date FROM tw_po_details WHERE po_id='".$po_id."' group by start_date, delivery_date";
$DocValpo = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($DocValpo);
$start_date = $decodedJSON->response[0]->start_date; 
$delivery_date = $decodedJSON->response[1]->delivery_date; 

$start=(new DateTime($start_date))->modify('first day of this month');
$end=(new DateTime($current_date))->modify('first day of next month');
$interval=DateInterval::createFromDateString('1 month');
$period=new DatePeriod($start, $interval, $end);
$table="";

$srno=1;
$table.="<thead><tr><th>#</th><th>Month</th><th>EPR Certificate</th><th>Recycler Certificate</th><th>Tabulated Details</th><th>Summary Sheet</th><th>Company</th><th>Client</th><th>Tax Invoice</th><th>Auditor Certificate</th><th>CPCB Certificate</th></tr></thead><tbody>";
foreach ($period as $dt) {
   $date="".$dt->format("Y-m");
	if (in_array($dt->format("M Y"),$data))
	{
		//---Start PlantQuantity
		$qrystateid="select state_name from tw_state_master where id ='".$state_id."'";
		$statename=$sign->SelectF($qrystateid,"state_name");
		
		$qryplant_quantity="select IFNULL (sum(replace(plant_quantity, ',', '')), 0) as plant_quantity FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."' and plant_wbs_date like '".$date."%'";
		$plant_quantity=$sign->SelectF($qryplant_quantity,"plant_quantity");
		

		//End PlantQuantity


		$table.="<tr>";
		$table.="<td>".$srno."</td>";
		$table.="<td>".$dt->format("M Y")."</td>";
		if($plant_quantity!=0){
			$table.="<th class='text-center'><a href='pgEPRSCertificate.php?po_id=".$po_id."&date=".$date."&state=".$state_id."' target='_blank'><i class='ti-download success'></i></a></th>";
			$table.="<th class='text-center'><a href='pgEPRSRecyclingCertificate.php?po_id=".$po_id."&date=".$date."&state=".$state_id."' target='_blank'><i class='ti-download success'></i></a></th>";
			$table.="<th class='text-center'><a href='pgEPRSViewTabulatedDetails.php?po_id=".$po_id."&date=".$date."&state=".$state_id."' target='_blank'><i class='ti-download success'></i></a></th>";
			$table.="<th class='text-center'><a href='pgEPRSViewSummarySheet.php?po_id=".$po_id."&date=".$date."&state=".$state_id."' target='_blank'><i class='ti-download success'></i></a></th>";
		}
		else{
			$table.="<th class='text-center'>-</th>";
			$table.="<th class='text-center'>-</th>";
			$table.="<th class='text-center'>-</th>";
			$table.="<th class='text-center'>-</th>";
		}
		//--start
		$qryapproval="SELECT supplier_status,company_status FROM tw_epr_approval WHERE  po_id='".$po_id."' and month='".$dt->format("M Y")."' and state='".$state_id."' order by id ASC";
		$Docapproval = $sign->FunctionJSON($qryapproval);
		$decodedJSONA = json_decode($Docapproval);
		$supplier_status = $decodedJSONA->response[0]->supplier_status; 
		$company_status = $decodedJSONA->response[1]->company_status; 
		//--end
		if($supplier_status==$settingValueApprovedStatus){
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_approved."' /></td>";
		}
		else if($supplier_status==$settingValueRejectedStatus){
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_rejected."' /></td>";
		}
		else{
			$table.="<td><button type='button' class='btn btn-success btn-icon-text' onclick='saveApproval(".$srno.");' id='btnSubmit'><i class='ti-check-box btn-icon-prepend'></i>Submit</button></td>";
		}
		
		//---
		if($company_status==$settingValueApprovedStatus){
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_approved."' /></td>";
		}
		else if($company_status==$settingValueRejectedStatus){
			$table.="<td><a onclick='viewReason(".$arrcompany_status[1].");' class='pointer-cursor'><img src='".$settingValueUserImagePathOther."".$settingValueic_rejected."' /></a></td>";
		}
		else{
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_pending."' /></td>";
		}
		
		
		//---
		if($company_status==$settingValueApprovedStatus){
			
			$table.="<td class='text-center'><a href='pgEprTaxInvoiceDocuments.php?po_id=".$po_id."&date=".$date."&state=".$state_id."' target='_blank'><i class='ti-download success'></i></a></td>";
			
			$Auditorditorqry="SELECT COUNT(*) as cnt FROM `tw_auditor_po_details` where po_id='".$po_id."'";
			$AuditorCnt=$sign->SelectF($Auditorditorqry,"cnt");
			if($AuditorCnt>0){ 
				$table.="<td class='text-center'><a href='pgAuditorCertificate.php?po_id=".$po_id."&req=&date=".$date."&state=".$state_id."' title='Auditor Certificate' target='_blank'> <i class='ti-printer'></i></a></td>";
			}else{
				$table.="<td>-</td>";
			}
			
			$table.="<td class='text-center'><a href='pgViewCertificate.php?po_id=".$po_id."&state=".$state_id."&date=".$date."' title='CPCB Certificate' target='_blank'><i class='ti-printer'></i></a></td>";
			$table.="</tr>";
		}
		else{
			$table.="<td>-</td>";
			$table.="<td>-</td>";
			$table.="<td>-</td>";
		}
		
		$table.="</tr>";
	}
	else
	{
		$table.="<tr>";
		$table.="<td>".$srno."</td>";
		$table.="<td>".$dt->format("M Y")."</td>";
		$table.="<td></td>";
		$table.="<td></td>";
		$table.="<td></td>";
		$table.="<td></td>";
		$table.="<td></td>";
		$table.="<td></td>";
		$table.="<td></td>";
		$table.="<td></td>";
		$table.="<td></td>";
		$table.="</tr>";
	}
	$srno++;
}
//$table.="</tbody>";
echo $table;
?>