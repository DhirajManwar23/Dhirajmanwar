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
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");
$settingValueic_approved= $commonfunction->getSettingValue("ic_approved");
$settingValueic_rejected= $commonfunction->getSettingValue("ic_rejected");
$settingValueic_pending= $commonfunction->getSettingValue("ic_pending");
$data = array();
$status = array("");
$arrcompany_status = array("");
$arrsupplier_status = array("");

$qrydate="SELECT id,month FROM tw_epr_approval WHERE po_id='".$po_id."' and supplier_status='".$settingValueApprovedStatus."' and state='".$state_id."'";
$Docqrydate = $sign->FunctionJSON($qrydate);
$decodedJSON2 = json_decode($Docqrydate);

$qrydatecnt="SELECT count(*) as cnt FROM tw_epr_approval WHERE po_id='".$po_id."' and supplier_status='".$settingValueApprovedStatus."' and state='".$state_id."'";
$cntqrydate = $sign->Select($qrydatecnt);

$count = 0;
$i = 1;
$x=$cntqrydate;
while($x>=$i){
	$eprappid = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$month = $decodedJSON2->response[$count]->month;
	$count=$count+1;
	array_push($data,$month,$eprappid);
	$i=$i+1;
}
//---Status
$qrystatus="SELECT IFNULL (replace(month, ',', ''),0) as  month,IFNULL (replace(company_status, ',', ''),0) as  company_status,IFNULL (replace(supplier_status, ',', ''),0) as  supplier_status FROM tw_epr_approval WHERE (supplier_status='".$settingValueApprovedStatus."' or supplier_status='".$settingValueRejectedStatus."' or company_status='".$settingValueApprovedStatus."' or company_status='".$settingValueRejectedStatus."') order by id ASC";
$Docqrystatus = $sign->FunctionJSON($qrystatus);
$decodedJSON = json_decode($Docqrystatus);

$qrystatuscnt="SELECT count(*) as cnt FROM tw_epr_approval WHERE (supplier_status='".$settingValueApprovedStatus."' or supplier_status='".$settingValueRejectedStatus."' or company_status='".$settingValueApprovedStatus."' or company_status='".$settingValueRejectedStatus."')";
$cntqrystatus = $sign->Select($qrystatuscnt);

$count = 0;
$i = 1;
$x=$cntqrystatus;
while($x>=$i){
	$month = $decodedJSON->response[$count]->month;
	$count=$count+1;
	$company_status = $decodedJSON->response[$count]->company_status;
	$count=$count+1;
	$supplier_status = $decodedJSON->response[$count]->supplier_status;
	$count=$count+1;
	array_push($status,$month);
	array_push($arrcompany_status,$company_status);
	array_push($arrsupplier_status,$supplier_status);
	$i=$i+1;
}
//---
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
$table.="<thead><tr><th>#</th><th>Month</th><th>EPR Certificate</th><th>Recycler Certificate</th><th>Tabulated Details</th><th>Summary Sheet</th><th>Vendor</th><th>Company</th><th>Tax Invoice</th><th>Auditor Certificate</th><th>CPCB Certificate</th></tr></thead><tbody>";
foreach ($period as $dt) {
    $date="".$dt->format("Y-m");
	
	if (in_array($dt->format("M Y"),$data))
	{
		
		//---Start PlantQuantity
		$qrystateid="select state_name from tw_state_master where id ='".$state_id."'";
		$statename=$sign->SelectF($qrystateid,"state_name");
		
		$qryplant_quantity="select IFNULL (sum(replace(plant_quantity, ',', '')), 0) as plant_quantity FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."' and purchase_invoice_date like '".$date."%'";
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
		//---
		//--start
		$qryapproval="SELECT supplier_status,company_status FROM tw_epr_approval WHERE  po_id='".$po_id."' and month='".$dt->format("M Y")."'  and state='".$state_id."' order by id ASC";
		$Docapproval = $sign->FunctionJSON($qryapproval);
		$decodedJSONA = json_decode($Docapproval);
		$supplier_status = $decodedJSONA->response[0]->supplier_status; 
		$company_status = $decodedJSONA->response[1]->company_status; 
		//--end
		//--k
		if($supplier_status==$settingValueApprovedStatus){
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_approved."' /></td>";
		}
		else if($supplier_status==$settingValueRejectedStatus){
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_rejected."' /></td>";
		}
		else{
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_pending."' /></td>";
		}
		
		//---
		if($company_status==$settingValueApprovedStatus){
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_approved."' /></td>";
		}
		else if($company_status==$settingValueRejectedStatus){
			$table.="<td><img src='".$settingValueUserImagePathOther."".$settingValueic_rejected."' /></td>";
		}
		else{
			$passvalue='AcceptRecord("'.$data[1].'","'.$data[0].'","'.$state_id.'","'.$po_id.'")';$passRejvalue='RejectRecord("'.$data[1].'","'.$data[0].'")';
				
			$table.="<td class='text-center'><a href='javascript:void(0)' id='alinkaccept' onclick='".$passvalue."'><img src='".$settingValueUserImagePathOther."".$settingValueAcceptImage."' id='imgaccept' class='img-sm rounded-circle mb-3 '/><a>
			<a href='javascript:void(0)' id='alinkreject' onclick='".$passRejvalue."'><img src='".$settingValueUserImagePathOther."".$settingValueRejectImage."'  id='imgreject' class='img-sm rounded-circle mb-3 '/><a>
				</td>";
		}
		
		//--k
		
		
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