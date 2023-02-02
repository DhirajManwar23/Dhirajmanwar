<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
/* $employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
 */
$po_id=$sign->escapeString($_POST["po_id"]);
$valType=$sign->escapeString($_POST["valType"]);
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueUserImagePathEPRServicesDocument=$commonfunction->getSettingValue("UserImagePathEPRSDocument");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

if($valType=="Approved"){
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,reason,category_name,material_name from tw_temp where po_id = '".$po_id."' and status='".$settingValueCompletedStatus."' order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValueCompletedStatus."'";
}
else{
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,reason,category_name,material_name from tw_temp where po_id = '".$po_id."' and status='".$settingValueRejectedStatus."' order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValueRejectedStatus."'";
}

$retVal = $sign->FunctionJSON($qry);
$retVal1 = $sign->Select($qry1);
$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
if($valType=="Approved"){
	$table.="<thead><tr><th>#</th><th>Aggregator Name</th><th>GST</th><th>Category Name</th><th>Material Name</th><th>GRN Number</th><th>Type Of Submission</th><th>GRN File</th><th>Purchase Invoice Number</th><th>Purchase Invoice Date</th><th>Dispatched State</th><th>Dispatched Date</th><th>Invoice Quantity</th><th>Invoice File</th><th>Plant Quantity</th><th>Aggregator WBS No</th></th><th>Aggregator WBS Date</th></th><th>WBS File</th></th><th>Plant WBS No</th></th><th>Plant WBS Date</th></th><th>Plant WBS File</th></th><th>Vehicle No</th><th>Vehicle Photo</th><th>Eway Bill No</th><th>Eway File</th><th>LR Number</th><th>LR Date</th><th>LR File</th><th>View Record</th></tr></thead><tbody>";
}
else{
	$table.="<thead><tr><th></th><th>#</th><th>Aggregator Name</th><th>GST</th><th>Category Name</th><th>Material Name</th><th>GRN Number</th><th>Type Of Submission</th><th>GRN File</th><th>Purchase Invoice Number</th><th>Purchase Invoice Date</th><th>Dispatched State</th><th>Dispatched Date</th><th>Invoice Quantity</th><th>Invoice File</th><th>Plant Quantity</th><th>Aggregator WBS No</th></th><th>Aggregator WBS Date</th></th><th>WBS File</th></th><th>Plant WBS No</th></th><th>Plant WBS Date</th></th><th>Plant WBS File</th></th><th>Vehicle No</th><th>Vehicle Photo</th><th>Eway Bill No</th><th>Eway File</th><th>LR Number</th><th>LR Date</th><th>LR File</th><th>View Record</th></tr></thead><tbody>";
}


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
	$grnfile = $decodedJSON2->response[$count]->grnfile;
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
	$invoicefile = $decodedJSON2->response[$count]->invoicefile;
	$count=$count+1;
	$plant_quantity = $decodedJSON2->response[$count]->plant_quantity;
	$count=$count+1;
	$aggregator_wbs_number = $decodedJSON2->response[$count]->aggregator_wbs_number;
	$count=$count+1;
	$aggregator_wbs_date = $decodedJSON2->response[$count]->aggregator_wbs_date;
	$count=$count+1;
	$wbsfile = $decodedJSON2->response[$count]->wbsfile;
	$count=$count+1;
	$plant_wbs_number = $decodedJSON2->response[$count]->plant_wbs_number;
	$count=$count+1;
	$plant_wbs_date = $decodedJSON2->response[$count]->plant_wbs_date;
	$count=$count+1;
	$pwbsfile = $decodedJSON2->response[$count]->pwbsfile;
	$count=$count+1;
	$vehicle_number = $decodedJSON2->response[$count]->vehicle_number;
	$count=$count+1;
	$vehiclefile = $decodedJSON2->response[$count]->vehiclefile;
	$count=$count+1;
	$eway_bill_number = $decodedJSON2->response[$count]->eway_bill_number;
	$count=$count+1;
	$ewayfile = $decodedJSON2->response[$count]->ewayfile;
	$count=$count+1;
	$lr_number = $decodedJSON2->response[$count]->lr_number;
	$count=$count+1;
	$lr_date = $decodedJSON2->response[$count]->lr_date;
	$count=$count+1;
	$lrfile = $decodedJSON2->response[$count]->lrfile;
	$count=$count+1;
	$reason = $decodedJSON2->response[$count]->reason;
	$count=$count+1;
	$category_name = $decodedJSON2->response[$count]->category_name;
	$count=$count+1;
	$material_name = $decodedJSON2->response[$count]->material_name;
	$count=$count+1;

		$table.="<tr>";
		if($valType=="Rejected"){
			$table.="<td class='text-center'><a href='javascript:void(0)' onclick='viewReason(".$reason.",".$id.")' id='alink'><i class='ti-info danger'></i></a></td>";
		}
		$table.="<td>".$i."</td>"; 
		$table.="<td>".$aggeragator_name."</td>"; 
		$table.="<td>".$gst."</td>"; 
		$table.="<td>".$category_name."</td>"; 
		$table.="<td>".$material_name."</td>"; 
		$table.="<td>".$grn_number."</td>";
		$table.="<td>".$type_of_submission."</td>";
		if($grnfile!=""){
			$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$grnfile."' target='_blank'><i class='ti-eye'></i></a></td>";
		}
		else{
			$table.="<td></td>";
		}
		
		
		$table.="<td>".$purchase_invoice_number."</td>";
		if($purchase_invoice_date=="0000-00-00 00:00:00"){
			$table.="<td><center>--</center></td>";
		}
		else{
			$table.="<td>".date("d-m-Y", strtotime($purchase_invoice_date))."</td>";
		}
		$table.="<td>".$dispatched_state."</td>";
		$table.="<td>".$dispatched_place."</td>";
		$table.="<td>".$invoice_quantity."</td>";
		if($invoicefile!=""){
			$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$invoicefile."' target='_blank'><i class='ti-eye'></i></a></td>";
		}
		else{
			$table.="<td></td>";
		}
		$table.="<td>".$plant_quantity."</td>";
		$table.="<td>".$aggregator_wbs_number."</td>";
		if($aggregator_wbs_date=="0000-00-00 00:00:00"){
			$table.="<td><center>--</center></td>";
		}
		else{
			$table.="<td>".date("d-m-Y", strtotime($aggregator_wbs_date))."</td>";
		}
		if($wbsfile!=""){
			$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$wbsfile."' target='_blank'><i class='ti-eye'></i></a></td>";
		}
		else{
			$table.="<td><center>--</center></td>";
		}
		
		$table.="<td>".$plant_wbs_number."</td>";
		if($plant_wbs_date=="0000-00-00 00:00:00"){
			$table.="<td><center>--</center></td>";
		}
		else{
			$table.="<td>".date("d-m-Y", strtotime($plant_wbs_date))."</td>";
		}
		if($pwbsfile!=""){
			$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$pwbsfile."' target='_blank'><i class='ti-eye'></i></a></td>";
		}
		else{
			$table.="<td></td>";
		}
		
		$table.="<td>".$vehicle_number."</td>";
		if($vehiclefile!=""){
			$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$vehiclefile."' target='_blank'><i class='ti-eye'></i></a></td>";
		}
		else{
			$table.="<td></td>";
		}
		
		$table.="<td>".$eway_bill_number."</td>";
		if($ewayfile!=""){
			$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$ewayfile."' target='_blank'><i class='ti-eye'></i></a></td>";
		}
		else{
			$table.="<td></td>";
		}
		
		$table.="<td>".$lr_number."</td>";
		if($lr_date=="0000-00-00 00:00:00"){
			$table.="<td><center>--</center></td>";
		}
		else{
			$table.="<td>".date("d-m-Y", strtotime($lr_date))."</td>";
		}
		if($lrfile!=""){
			$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$lrfile."' target='_blank'><i class='ti-eye'></i></a></td>";
		}
		else{
			$table.="<td></td>";
		}
		$table.="<td class='text-center'><a href='pgViewTableRecord.php?type=edit&id=".$id."'  id='alink'><i class='ti-home'></a></td>";
						
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
$table.="</tbody>";
echo $table;
	?>