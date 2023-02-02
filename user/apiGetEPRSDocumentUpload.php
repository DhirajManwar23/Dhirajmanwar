<?php
session_start();
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();

$po_id=$sign->escapeString($_POST["poid"]);
$type=$sign->escapeString($_POST["type"]);
$pagetype=$sign->escapeString($_POST["pagetype"]);
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueOngoingStatus= $commonfunction->getSettingValue("Ongoing Status");
$settingValueUserImagePathEPRServicesDocument=$commonfunction->getSettingValue("UserImagePathEPRSDocument");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");
if($type=="add" || $type=="edit"){
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,status,reason,category_name,material_name,rejected_by from tw_temp where po_id = '".$po_id."' and status='".$settingValuePendingStatus."' order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValuePendingStatus."'";
}
else if($type=="accept"){
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,status,reason,category_name,material_name,rejected_by from tw_temp where po_id = '".$po_id."' and status='".$settingValueCompletedStatus."' order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValueCompletedStatus."'";
}
else if($type=="view"){
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,status,reason,category_name,material_name,rejected_by from tw_temp where po_id = '".$po_id."' and (status='".$settingValueOngoingStatus."' or status='".$settingValueAwaitingStatus."') order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and (status='".$settingValueOngoingStatus."' or status='".$settingValueAwaitingStatus."')";
}
else if($type=="query"){
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,status,reason,category_name,material_name,rejected_by from tw_temp where po_id = '".$po_id."' and status='".$settingValueRejectedStatus."' order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValueRejectedStatus."'";
}
else if($type=="date"){
	$startdate=$sign->escapeString($_POST["startdate"]);
	$enddate=$sign->escapeString($_POST["enddate"]);

	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,status,reason,category_name,material_name,rejected_by from tw_temp where po_id = '".$po_id."' and status='".$settingValueCompletedStatus."' and purchase_invoice_date between '".$startdate." 00:00:00am' and '".$enddate." 11:59:00pm' order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValueCompletedStatus."' and purchase_invoice_date between '".$startdate." 00:00:00am' and '".$enddate." 11:59:00pm'";
}
else{
	$qry="select id,aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,status,reason,category_name,material_name,rejected_by from tw_temp where po_id = '".$po_id."' and status='".$settingValueAwaitingStatus."' order by id Asc";
	$qry1="select count(*) as cnt from tw_temp where po_id ='".$po_id."' and status='".$settingValueAwaitingStatus."'";
}

$retVal = $sign->FunctionJSON($qry);
$retVal1 = $sign->Select($qry1);
$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
if($type=="add" || $type=="edit"){
	$table.="<thead><tr><th>#</th><th>Aggregator Name</th><th>GST</th><th>Category Name</th><th>Material Name</th><th>GRN Number</th><th>Type Of Submission</th><th>GRN File</th><th>Purchase Invoice Number</th><th>Purchase Invoice Date</th><th>Dispatched State</th><th>Dispatched Date</th><th>Invoice Quantity</th><th>Invoice File</th><th>Plant Quantity</th><th>Aggregator WBS No</th></th><th>Aggregator WBS Date</th></th><th>WBS File</th></th><th>Plant WBS No</th></th><th>Plant WBS Date</th></th><th>Plant WBS File</th></th><th>Vehicle No</th><th>Vehicle Photo</th><th>Eway Bill No</th><th>Eway File</th><th>LR Number</th><th>LR Date</th><th>LR File</th><th>Edit</th><th>Delete</th><th>Save</th><th>View Record</th></tr></thead><tbody>";
}
else if($type=="query"){
	$table.="<thead><tr><th></th><th>#</th><th>Aggregator Name</th><th>GST</th><th>Category Name</th><th>Material Name</th><th>GRN Number</th><th>Type Of Submission</th><th>GRN File</th><th>Purchase Invoice Number</th><th>Purchase Invoice Date</th><th>Dispatched State</th><th>Dispatched Date</th><th>Invoice Quantity</th><th>Invoice File</th><th>Plant Quantity</th><th>Aggregator WBS No</th></th><th>Aggregator WBS Date</th></th><th>WBS File</th></th><th>Plant WBS No</th></th><th>Plant WBS Date</th></th><th>Plant WBS File</th></th><th>Vehicle No</th><th>Vehicle Photo</th><th>Eway Bill No</th><th>Eway File</th><th>LR Number</th><th>LR Date</th><th>LR File</th><th>Rejected By</th><th>Edit</th><th>View Record</th></tr></thead><tbody>";
}
else if($type=="accept"){
	$table.="<thead><tr><th>#</th><th>Aggregator Name</th><th>GST</th><th>Category Name</th><th>Material Name</th><th>GRN Number</th><th>Type Of Submission</th><th>GRN File</th><th>Purchase Invoice Number</th><th>Purchase Invoice Date</th><th>Dispatched State</th><th>Dispatched Date</th><th>Invoice Quantity</th><th>Invoice File</th><th>Plant Quantity</th><th>Aggregator WBS No</th></th><th>Aggregator WBS Date</th></th><th>WBS File</th></th><th>Plant WBS No</th></th><th>Plant WBS Date</th></th><th>Plant WBS File</th></th><th>Vehicle No</th><th>Vehicle Photo</th><th>Eway Bill No</th><th>Eway File</th><th>LR Number</th><th>LR Date</th><th>LR File</th><th>View Record</th></tr></thead><tbody>";
}
else{
	$table.="<thead><tr><th>#</th><th>Aggregator Name</th><th>GST</th><th>Category Name</th><th>Material Name</th><th>GRN Number</th><th>Type Of Submission</th><th>GRN File</th><th>Purchase Invoice Number</th><th>Purchase Invoice Date</th><th>Dispatched State</th><th>Dispatched Date</th><th>Invoice Quantity</th><th>Invoice File</th><th>Plant Quantity</th><th>Aggregator WBS No</th></th><th>Aggregator WBS Date</th></th><th>WBS File</th></th><th>Plant WBS No</th></th><th>Plant WBS Date</th></th><th>Plant WBS File</th></th><th>Vehicle No</th><th>Vehicle Photo</th><th>Eway Bill No</th><th>Eway File</th><th>LR Number</th><th>LR Date</th><th>LR File</th><th>Edit</th><th>Delete</th><th>View Record</th><th>Awaiting from</th></tr></thead><tbody>";
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
	$status = $decodedJSON2->response[$count]->status;
	$count=$count+1;
	$reason = $decodedJSON2->response[$count]->reason;
	$count=$count+1;
	$category_name = $decodedJSON2->response[$count]->category_name;
	$count=$count+1;
	$material_name = $decodedJSON2->response[$count]->material_name;
	$count=$count+1;
	$rejected_by = $decodedJSON2->response[$count]->rejected_by;
	$count=$count+1;
	

		$table.="<tr>";
		if($type=="query"){
			$table.="<td class='text-center'><a href='javascript:void(0)' onclick='viewReason(".$reason.",".$id.")' id='alink'><i class='ti-info danger'></i></a></td>";
		}
		$table.="<td>".$i."</td>"; 
		
		$table.="<td>".$aggeragator_name."</td>"; 
		$table.="<td>".$gst."</td>"; 
		$table.="<td>".$category_name."</td>"; 
		$table.="<td>".$material_name."</td>"; 
		$table.="<td>".$grn_number."</td>";
		$table.="<td>".$type_of_submission."</td>";
		if($type=="add" || $type=="edit"){
			if($grn_number!=""){
				$table.="<td class='text-center'>
				<div class='tw-image-upload'>
					<label for='selectImg1-".$id."'>
						<i class='ti-link pointer-cursor tw-blue' id='select1-".$id."'></i>
					</label>
					<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg1-".$id."' name='selectImg1-".$id."' onchange='uploadGRN(".$id.")'>
				</div>
				</td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		else{
			if($grnfile!=""){
				$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$grnfile."' target='_blank'><i class='ti-link pointer-cursor tw-blue'></i></a></td>";
			}
			else{
				$table.="<td></td>";
			}
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
		if($type=="add" || $type=="edit"){
			if($purchase_invoice_number!=""){
				$table.="<td class='text-center'>
				<div class='tw-image-upload'>
					<label for='selectImg2-".$id."'>
						<i class='ti-link pointer-cursor tw-blue' id='select2-".$id."'></i>
					</label>
					<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg2-".$id."' name='selectImg2-".$id."' onchange='uploadInvoice(".$id.")'>
				</div>
				</td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		else{
			if($invoicefile!=""){
				$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$invoicefile."' target='_blank'><i class='ti-link pointer-cursor tw-blue'></i></a></td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		
		
		$table.="<td>".$plant_quantity."</td>";
		$table.="<td>".$aggregator_wbs_number."</td>";
		if($aggregator_wbs_date=="0000-00-00 00:00:00"){
			$table.="<td><center>--</center></td>";
		}
		else{
			$table.="<td>".date("d-m-Y", strtotime($aggregator_wbs_date))."</td>";
		}
		if($type=="add" || $type=="edit"){
			if($aggregator_wbs_number!=""){
				$table.="<td class='text-center'>
				<div class='tw-image-upload'>
					<label for='selectImg3-".$id."'>
						<i class='ti-link pointer-cursor tw-blue' id='select3-".$id."'></i>
					</label>
					<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg3-".$id."' name='selectImg3-".$id."' onchange='uploadWBS(".$id.")'>
				</div>
				</td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		else{
			if($wbsfile!=""){
				$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$wbsfile."' target='_blank'><i class='ti-link pointer-cursor tw-blue'></i></a></td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		$table.="<td>".$plant_wbs_number."</td>";
		if($plant_wbs_date=="0000-00-00 00:00:00"){
			$table.="<td><center>--</center></td>";
		}
		else{
			$table.="<td>".date("d-m-Y", strtotime($plant_wbs_date))."</td>";
		}
		if($type=="add" || $type=="edit"){
			if($plant_wbs_number!=""){
				$table.="<td class='text-center'>
				<div class='tw-image-upload'>
					<label for='selectImg4-".$id."'>
						<i class='ti-link pointer-cursor tw-blue' id='select4-".$id."'></i>
					</label>
					<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg4-".$id."' name='selectImg4-".$id."' onchange='uploadPWBS(".$id.")'>
				</div>
				</td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		else{
			if($pwbsfile!=""){
				$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$pwbsfile."' target='_blank'><i class='ti-link pointer-cursor tw-blue'></i></a></td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		$table.="<td>".$vehicle_number."</td>";
		if($type=="add" || $type=="edit"){
			if($vehicle_number!=""){
				$table.="<td class='text-center'>
				<div class='tw-image-upload'>
					<label for='selectImg5-".$id."'>
						<i class='ti-link pointer-cursor tw-blue' id='select5-".$id."'></i>
					</label>
					<input type='file' accept='.png, .jpg, .jpeg, .pdf, .pdf' id='selectImg5-".$id."' name='selectImg5-".$id."' onchange='uploadVehicle(".$id.")'>
				</div>
				</td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		else{
			if($vehiclefile!=""){
				$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$vehiclefile."' target='_blank'><i class='ti-link pointer-cursor tw-blue'></i></a></td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		$table.="<td>".$eway_bill_number."</td>";
		if($type=="add" || $type=="edit"){
			if($eway_bill_number!=""){
				$table.="<td class='text-center'>
				<div class='tw-image-upload'>
					<label for='selectImg6-".$id."'>
						<i class='ti-link pointer-cursor tw-blue' id='select6-".$id."'></i>
					</label>
					<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg6-".$id."' name='selectImg6-".$id."' onchange='uploadEway(".$id.")'>
				</div>
				</td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		else{
			if($ewayfile!=""){
				$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$ewayfile."' target='_blank'><i class='ti-link pointer-cursor tw-blue'></i></a></td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		$table.="<td>".$lr_number."</td>";
		if($lr_date=="0000-00-00 00:00:00"){
			$table.="<td><center>--</center></td>";
		}
		else{
			$table.="<td>".date("d-m-Y", strtotime($lr_date))."</td>";
		}
		if($type=="add" || $type=="edit"){
			if($lr_number!=""){
				$table.="<td class='text-center'>
				<div class='tw-image-upload'>
					<label for='selectImg7-".$id."'>
						<i class='ti-link pointer-cursor tw-blue' id='select7-".$id."'></i>
					</label>
					<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg7-".$id."' name='selectImg7-".$id."' onchange='uploadLR(".$id.")'>
				</div>
				</td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		else{
			if($lrfile!=""){
				$table.="<td class='text-center'><a href='".$settingValueUserImagePathEPRServicesDocument."".$lrfile."' target='_blank'><i class='ti-link pointer-cursor tw-blue'></i></a></td>";
			}
			else{
				$table.="<td></td>";
			}
		}
		
		if($type=="add" || $type=="edit"){
			$table.="<td class='text-center'><a href='pgEPRDEditDocumentDataPending.php?id=".$id."&poid=".$po_id."&pagetype=".$pagetype."' id='alink'><i class='ti-pencil'></i></a></td>";
			$table.="<td class='text-center'><a href='javascript:void(0)' id='alinkreject' onclick='RejectRecord(".$id.")'><i class='ti-trash'></i></a></td>";
			$table.="<td class='text-center'><a href='javascript:void(0)' onclick='adddata(".$id.")' id='alink'><i class='ti-save'></a></td>";
		}
		else if($type=="query"){		
			$table.="<td class='text-center'>".$rejected_by."</td>";
			$table.="<td class='text-center'><a href='pgEPRDEditDocumentData.php?id=".$id."&poid=".$po_id."&status=".$settingValueRejectedStatus."&pagetype=".$pagetype."' id='alink'><i class='ti-pencil'></i></a></td>";
		}
		else if($type=="view"){
			if($status==$settingValueOngoingStatus){
				$table.="<td class='text-center'><a href='pgEPRDEditDocumentData.php?id=".$id."&poid=".$po_id."&status=".$settingValueOngoingStatus."&pagetype=".$pagetype."' id='alink'><i class='ti-pencil'></i></a></td>";
			}
			else{
				$table.="<td class='text-center'><a href='pgEPRDEditDocumentData.php?id=".$id."&poid=".$po_id."&status=".$settingValueAwaitingStatus."&pagetype=".$pagetype."' id='alink'><i class='ti-pencil'></i></a></td>";
			}
			$table.="<td class='text-center'><a href='javascript:void(0)' id='alinkreject' onclick='RejectRecord(".$id.")'><i class='ti-trash'></i></a></td>";
		}
		else{
			$table.="";
						
		}
		if($type=="query"){		
			$table.="<td class='text-center'><a href='pgViewTableRecord.php?type=edit&id=".$id."&poid=".$po_id."''  id='alink'><i class='ti-home'></a></td>";
		}
		else if($type=="view"){		
			$table.="<td class='text-center'><a href='pgViewTableRecord.php?type=edit&id=".$id."&poid=".$po_id."''  id='alink'><i class='ti-home'></a></td>";
			if($status==$settingValueOngoingStatus){
				$table.="<td class='text-center'>Auditor</td>";
			}
			else{
				$table.="<td class='text-center'>Company</td>";
			}
		}
		else{
			$table.="<td class='text-center'><a href='pgViewTableRecord.php?type=editonly&id=".$id."&poid=".$po_id."''  id='alink'><i class='ti-home'></a></td>";
		}
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	