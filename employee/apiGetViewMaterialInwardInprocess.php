<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$po_id=$_POST["po_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");

$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueDistanceImage = $commonfunction->getSettingValue("DistanceImage");

$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");


$qry="SELECT mo.id,mo.company_id,cd.CompanyName,cd.Status,cd.Company_Logo,mo.total_quantity,mo.final_total_amout,mo.created_on,supplier_geo_location,receiver_geo_location FROM tw_material_outward mo INNER JOIN tw_company_details cd ON mo.company_id = cd.ID WHERE mo.customer_id  = '".$company_id."' and mo.po_id  = '".$po_id."' and mo.status='".$settingValuePendingStatus."' ORDER BY mo.id DESC";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward where customer_id ='".$company_id."' and po_id ='".$po_id."' and status='".$settingValuePendingStatus."'";
$retVal1Z = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1Z;
$table="";
$it=1;
if($retVal1Z==0 || $retVal1Z==0.00){
	$table.="
				<div class='card'>
				  
					<div class='card-body'>
					 <div class='col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12 left-s'>
                       <div class='row'>
					 <div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
					
							<img class='piclogo' src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
						</div>
					</div>
					</div>
					</div>
					
				 </div><br>";	
			
}
else{
		while($x>=$i){
			
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$Supplier_company_id = $decodedJSON2->response[$count]->company_id;
		$count=$count+1;
		$CompanyName = $decodedJSON2->response[$count]->CompanyName;
		$count=$count+1;
		$companyStatus = $decodedJSON2->response[$count]->Status;
		$count=$count+1;
		$Company_Logo = $decodedJSON2->response[$count]->Company_Logo;
		$count=$count+1;
		$total_quantity = $decodedJSON2->response[$count]->total_quantity;
		$count=$count+1;
		$final_total_amout = $decodedJSON2->response[$count]->final_total_amout;
		$count=$count+1;
		$created_on = $decodedJSON2->response[$count]->created_on;
		$count=$count+1;
		$supplier_geo_location = $decodedJSON2->response[$count]->supplier_geo_location;
		$count=$count+1;
		$receiver_geo_location = $decodedJSON2->response[$count]->receiver_geo_location;
		$count=$count+1;
		
		//------Karuna Start
		
		$qry3="Select value from tw_company_contact where company_id='".$Supplier_company_id."'";
		$retVal3 = $sign->SelectF($qry3,"value");
		$verifyStatus=$commonfunction->getSettingValue("Verified Status");
		$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
		$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
		$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
		$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

		$qry4="SELECT SUM(total_quantity) FROM tw_material_outward WHERE po_id='".$po_id."' and status='".$settingValueApprovedStatus."'";
		$retVal4 = $sign->SelectF($qry4,"SUM(total_quantity)");
		
		$qry5="Select verification_status from tw_verification_status_master where id='".$settingValuePendingStatus."'";
		$retVal5 = $sign->SelectF($qry5,"verification_status"); 
		
		$TotalBalQty = $total_quantity-$retVal4;
		$TotalBalQty;
		
		if(empty($Company_Logo)){
			$Company_Logo=$UserImagePathOther.$settingValueCompanyImage;
		} 
		else{
			$Company_Logo=$settingValueUserImagePathVerification.$retVal3."/".$Company_Logo;
		}
		$img="";
		if($companyStatus==$verifyStatus){ 
			$img = "<img src='".$UserImagePathOther."".$VerifiedImage."'/>";
		}
		else{
			$img="";
		}
		//--karuna Start
		$disabledEway="";
		$disabledInvoice="";
		$disabledWBS="";
		$disabledGRN="";
		$disabledQC="";
		$iclasseway="";
		$iclassinvoice="";
		$iclasswbs="";
		$hrefGRNGenerate="";
		$hrefQCGenerate="";
		$disabledRL="";
		$qryEway="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Eway' and outward_id='".$id."' ORDER BY outward_id ASC";
		$retValEway = $sign->SelectF($qryEway,"cnt");

		$qry1Eway="SELECT COUNT(*) as cnt from tw_material_outward_eway WHERE outward_id='".$id."' ORDER BY outward_id ASC";
		$retVal1Eway = $sign->SelectF($qry1Eway,"cnt");

		if($retValEway>0 || $retVal1Eway>0){
			$disabledEway="";
		}
		else{
			$disabledEway="disabled";
		}
		$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$id."' ORDER BY outward_id ASC";
		$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

		$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$id."' ORDER BY outward_id ASC";
		$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
		if($retValInvoice>0 || $retVal1Invoice>0){
			$disabledInvoice="";
		}
		else{
			$disabledInvoice="disabled";
		}
		$qryWBS="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='WBS' and outward_id='".$id."' ORDER BY outward_id ASC";
		$retValWBS = $sign->SelectF($qryWBS,"cnt");

		$qry1WBS="SELECT COUNT(*) as cnt from tw_material_outward_wbs WHERE outward_id='".$id."' ORDER BY outward_id ASC";
		$retVal1WBS = $sign->SelectF($qry1WBS,"cnt");
		if($retValWBS>0 || $retVal1WBS>0){
			$disabledWBS="";
		}
		else{
			$disabledWBS="disabled";
		}
		//RL
		$qryRL="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='RL' and outward_id='".$id."' ORDER BY outward_id ASC";
		$retValRL = $sign->SelectF($qryRL,"cnt");

		
		if($retValRL>0 ){
			$disabledRL="";
		}
		else{
			$disabledRL="disabled";
		}
		
		//---GRN start
		$qryGRN="SELECT COUNT(*) as cnt,id from tw_material_inward_documents WHERE type='GRN' and inward_id='".$id."' ORDER BY inward_id ASC";
		
		$valqryGRN = $sign->FunctionJSON($qryGRN);
		$decodedJSON = json_decode($valqryGRN);
		$retValGRN = $decodedJSON->response[0]->cnt; 
		$idGRNDoc = $decodedJSON->response[1]->id; 
		
		$qry1GRN="SELECT COUNT(*) as cnt,id from tw_material_inward_grn WHERE inward_id='".$id."' ORDER BY inward_id ASC";		
		$valqry1GRN = $sign->FunctionJSON($qry1GRN);
		$decodedJSON = json_decode($valqry1GRN);
		$retVal1GRN = $decodedJSON->response[0]->cnt; 
		$idGRN = $decodedJSON->response[1]->id; 
		
	
		if($retVal1GRN==0){
			$hrefGRNGenerate="pgInwardGRNForm.php?type=add&id=&inward_id=".$id."";
		}
		else{
			$hrefGRNGenerate="pgInwardGRNFormEdit.php?type=edit&id=".$idGRN."&inward_id=".$id."";
			$dropdownGRN="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>  <a class='dropdown-item' href='".$hrefGRNGenerate."'><i class='ti-pencil'></i></i> Edit</a>
			 <a class='dropdown-item' href='#' onclick='DeleteRecordGRN(".$idGRN.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a> 
											</div>";
		}
		if($retValGRN==0){
		}
		else{
			$dropdownGRN="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''> 
												 <a class='dropdown-item' href='#' onclick='DeleteRecordupload(".$idGRNDoc.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a> 
												 
											</div>";
		}
		
		if($retValGRN>0 || $retVal1GRN>0){
			$disabledGRN="";
		}
		else{
			$disabledGRN="disabled";
			$dropdownGRN="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
												  <a class='dropdown-item' href='#' onclick='GRNRecordupload(".$id.")'><i class='ti-upload'></i></i> Upload</a> 
												 
												  <a class='dropdown-item' href='".$hrefGRNGenerate."'><i class='ti-plus'></i></i> Generate</a>
											</div>";
		}
		
		//---GRN end
		//---QC start
		$qryQC="SELECT COUNT(*) as cnt,id from tw_material_inward_documents WHERE type='QC' and inward_id='".$id."' ORDER BY inward_id ASC";
		$valqryQC = $sign->FunctionJSON($qryQC);
		$decodedJSON = json_decode($valqryQC);
		$retValQC = $decodedJSON->response[0]->cnt; 
		$idQCDoc = $decodedJSON->response[1]->id; 
		
		$qry1QC="SELECT COUNT(*) as cnt,id from tw_material_inward_qc WHERE inward_id='".$id."' ORDER BY inward_id ASC";
		$valqry1QC = $sign->FunctionJSON($qry1QC);
		$decodedJSON = json_decode($valqry1QC);
		$retVal1QC = $decodedJSON->response[0]->cnt; 
		$idQC = $decodedJSON->response[1]->id; 
		
		if($retVal1QC==0){
			$hrefQCGenerate="pgInwardQualityCheckForm.php?type=add&id=&inward_id=".$id."";
		}
		else{
			$hrefQCGenerate="pgInwardQualityCheckForm.php?type=edit&id=".$idQC."&inward_id=".$id."";
			$dropdownQC="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>  <a class='dropdown-item' href='".$hrefQCGenerate."'><i class='ti-pencil'></i></i> Edit</a>
			 <a class='dropdown-item' href='#' onclick='DeleteRecordQC(".$idQC.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a> 
											</div>";
		}
		
		if($retValQC==0){
		}
		else{
			$dropdownQC="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''> 
												 <a class='dropdown-item' href='#' onclick='DeleteRecordupload(".$idQCDoc.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a> 
												 
											</div>";
		}
		if($retValQC>0 || $retVal1QC>0){
			$disabledQC="";
		}
		else{
			$disabledQC="disabled";
			$dropdownQC="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
												  <a class='dropdown-item' href='#' onclick='QCRecordupload(".$id.")'><i class='ti-upload'></i></i> Upload</a> 
												
												  <a class='dropdown-item' href='".$hrefQCGenerate."'><i class='ti-plus'></i></i> Generate</a>
											</div>";
		}
	//QC end
	//Count  start
	$qryii="select sum(tbl.EachTableCount)from(
	select count(*) as EachTableCount from tw_material_outward_documents WHERE type='Eway' and outward_id='".$id."'
	UNION ALL
	select count(*) as EachTableCount from tw_material_outward_eway WHERE outward_id='".$id."'
	)tbl";
	 $retValii = $sign->SelectF($qryii,"sum(tbl.EachTableCount)");
	$qryi="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$id."'
		UNION ALL
		select count(*) as EachTableCount from tw_tax_invoice WHERE outward_id='".$id."'
		)tbl";
	
	$retVali = $sign->SelectF($qryi,"sum(tbl.EachTableCount)");
	$qryiii="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_outward_documents WHERE type='WBS' and outward_id='".$id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_outward_wbs WHERE outward_id='".$id."'
		)tbl";
	$retValiii = $sign->SelectF($qryiii,"sum(tbl.EachTableCount)");
	$qryiv="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_inward_documents WHERE type='GRN' and inward_id='".$id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_inward_grn WHERE inward_id='".$id."'
		)tbl";
	$retValiv = $sign->SelectF($qryiv,"sum(tbl.EachTableCount)");
	$qryv="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_inward_documents WHERE type='QC' and inward_id='".$id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_inward_qc WHERE inward_id='".$id."'
		)tbl";
	$retValv = $sign->SelectF($qryv,"sum(tbl.EachTableCount)");
		
	//--Count end
	
	$hrefEway ="";
	$hrefInvoice ="";
	$hrefWBS ="";
	$hrefGRN ="";
	$hrefQC ="";
	$hrefRL ="";
	//--Karuna ahref start Eway
	
	$queryZ1 = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='Eway' Order by id Desc";
	$retValZ1 = $sign->FunctionJSON($queryZ1);
	
	$qryZ1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='Eway' Order by id Desc";
	$retVal1Z1 = $sign->Select($qryZ1);
	$decodedJSON21 = json_decode($retValZ1);
	$count1 = 0;
	$i1 = 1;
	$x1=$retVal1Z1;
	$it=1;
	while($x1>=$i1){

		$modid = $decodedJSON21->response[$count1]->id;
		$count1=$count1+1;
		$document = $decodedJSON21->response[$count1]->document;
		$count1=$count1+1;
		$i1=$i1+1;
		
		$hrefEway = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ2 = "select id from tw_material_outward_eway where outward_id = '".$id."' Order by id Desc";
	$retValZ2 = $sign->FunctionJSON($queryZ2);
	
	$qry1Z2="Select count(*) as cnt from tw_material_outward_eway where outward_id = '".$id."' Order by id Desc";
	$retVal1Z2 = $sign->Select($qry1Z2);
	$decodedJSON22 = json_decode($retValZ2);
	$count2 = 0;
	$i2 = 1;
	$x2=$retVal1Z2;
	$it=1;
	while($x2>=$i2){

		$modid = $decodedJSON22->response[$count2]->id;
		$count2=$count2+1;
		$i2=$i2+1;
		$hrefEway = "pgeWayBill.php?id=".$modid."&outward_id=".$id;
	}
	
	//--Karuna ahref end Eway
	//--Karuna ahref start Invoice
	$queryZ3 = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='Invoice' Order by id Desc";
	$retValZ3 = $sign->FunctionJSON($queryZ3);
	
	$qry1Z3="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='Invoice' Order by id Desc";
	$retVal1Z3 = $sign->Select($qry1Z3);
	$decodedJSON23 = json_decode($retValZ3);
	$count3 = 0;
	$i3 = 1;
	$x3=$retVal1Z3;
	$it=1;
	while($x3>=$i3){

		$modid = $decodedJSON23->response[$count3]->id;
		$count3=$count3+1;
		$document = $decodedJSON23->response[$count3]->document;
		$count3=$count3+1;
		$i3=$i3+1;
		$hrefInvoice = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ4 = "select id,invoice_number from tw_tax_invoice where outward_id = '".$id."' Order by id Desc";
	$retValZ4 = $sign->FunctionJSON($queryZ4);
	
	$qry1Z4="Select count(*) as cnt from tw_tax_invoice where outward_id = '".$id."' Order by id Desc";
	$retVal1Z4 = $sign->Select($qry1Z4);
	$decodedJSON24 = json_decode($retValZ4);
	$count4 = 0;
	$i4 = 1;
	$x4=$retVal1Z4;
	$it=1;
	while($x4>=$i4){

		$modid = $decodedJSON24->response[$count4]->id;
		$count4=$count4+1;
		$invoice_number = $decodedJSON24->response[$count4]->invoice_number;
		$count4=$count4+1;	
		$i4=$i4+1;
		$hrefInvoice = "pgTaxInvoiceDocuments.php?id=".$modid."&voutward_id=".$id;
	}
	//--Karuna ahref end Invoice
	//--Karuna ahref start WBS
	$queryZ5 = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='WBS' Order by id Desc";
	$retValZ5 = $sign->FunctionJSON($queryZ5);
	
	$qry1Z5="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='WBS' Order by id Desc";
	$retVal1Z5 = $sign->Select($qry1Z5);
	$decodedJSON25 = json_decode($retValZ5);
	$count5 = 0;
	$i5 = 1;
	$x5=$retVal1Z5;
	$it=1;
	while($x5>=$i5){

		$modid = $decodedJSON25->response[$count5]->id;
		$count5=$count5+1;
		$document = $decodedJSON25->response[$count5]->document;
		$count5=$count5+1;
		$i5=$i5+1;
		$hrefWBS = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ6 = "select id from tw_material_outward_wbs where outward_id = '".$id."' Order by id Desc";
	$retValZ6 = $sign->FunctionJSON($queryZ6);
	
	$qry1Z6="Select count(*) as cnt from tw_material_outward_wbs where outward_id = '".$id."' Order by id Desc";
	$retVal1Z6 = $sign->Select($qry1Z6);
	$decodedJSON26 = json_decode($retValZ6);
	$count6 = 0;
	$i6 = 1;
	$x6=$retVal1Z6;
	$it=1;
	while($x6>=$i6){

		$modid = $decodedJSON26->response[$count6]->id;
		$count6=$count6+1;
		$i6=$i6+1;
		$hrefWBS = "pgWaybillslip.php?id=".$modid."&outward_id=".$id;
	}
	//--Karuna ahref end WBS
	//RL
	$queryRLZ5 = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='WBS' Order by id Desc";
	$retValRLZ5 = $sign->FunctionJSON($queryZ5);
	
	$qry1RLZ5="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='RL' Order by id Desc";
	$retVal1RLZ5 = $sign->Select($qry1RLZ5);
	$decodedJSONRL25 = json_decode($retVal1RLZ5);
	$countRL5 = 0;
	$iRL5 = 1;
	$xRL5=$retVal1RLZ5;
	$it=1;
	while($xRL5>=$iRL5){

		$modid = $decodedJSONRL25->response[$countRL5]->id;
		$countRL5=$countRL5+1;
		$document = $decodedJSONRL25->response[$count5]->document;
		$countRL5=$countRL5+1;
		$iRL5=$iRL5+1;
		$hrefRL = "../assets/images/Documents/Employee/Outward/".$document;
	}
	
	//RL END
	
	//--Karuna ahref start GRN
	$queryZ7 = "select id,document from tw_material_inward_documents where inward_id = '".$id."' and type='GRN' Order by id Desc";
	$retValZ7 = $sign->FunctionJSON($queryZ7);
	
	$qry1Z7="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$id."' and type='GRN' Order by id Desc";
	$retVal1Z7 = $sign->Select($qry1Z7);
	$decodedJSON27 = json_decode($retValZ7);
	$count7 = 0;
	$i7 = 1;
	$x7=$retVal1Z7;
	$it=1;
	while($x7>=$i7){
		$modid = $decodedJSON27->response[$count7]->id;
		$count7=$count7+1;
		$document = $decodedJSON27->response[$count7]->document;
		$count7=$count7+1;
		$i7=$i7+1;
		$hrefGRN = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ8 = "select id from tw_material_inward_grn where inward_id = '".$id."' Order by id Desc";
	$retValZ8 = $sign->FunctionJSON($queryZ8);
	
	$qry1Z8="Select count(*) as cnt from tw_material_inward_grn where inward_id = '".$id."' Order by id Desc";
	$retVal1Z8 = $sign->Select($qry1Z8);
	$decodedJSON28 = json_decode($retValZ8);
	$count8 = 0;
	$i8 = 1;
	$x8=$retVal1Z8;
	$it=1;
	while($x8>=$i8){
		$modid = $decodedJSON28->response[$count8]->id;
		$count8=$count8+1;
		$i8=$i8+1;
		$hrefGRN = "pgGRN.php?id=".$modid."&inward_id=".$id;
	}
	//--Karuna ahref end GRN
	//--Karuna ahref start QC
	$queryZ9 = "select id,document from tw_material_inward_documents where inward_id = '".$id."' and type='QC' Order by id Desc";
	$retValZ9 = $sign->FunctionJSON($queryZ9);
	
	$qry1Z9="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$id."' and type='QC' Order by id Desc";
	$retVal1Z9 = $sign->Select($qry1Z9);
	$decodedJSON29 = json_decode($retValZ9);
	$count9 = 0;
	$i9 = 1;
	$x9=$retVal1Z9;
	$it=1;
	while($x9>=$i9){

		$modid = $decodedJSON29->response[$count9]->id;
		$count9=$count9+1;
		$document = $decodedJSON29->response[$count9]->document;
		$count9=$count9+1;
		$i9=$i9+1;
		$hrefQC = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ10 = "select id from tw_material_inward_qc where inward_id = '".$id."' Order by id Desc";
	$retValZ10 = $sign->FunctionJSON($queryZ10);
	
	$qry1Z10="Select count(*) as cnt from tw_material_inward_qc where inward_id = '".$id."' Order by id Desc";
	$retVal1Z10 = $sign->Select($qry1Z10);
	$decodedJSON210 = json_decode($retValZ10);
	$count10 = 0;
	$i10 = 1;
	$x10=$retVal1Z10;
	$it=1;
	while($x10>=$i10){

		$modid = $decodedJSON210->response[$count10]->id;
		$count10=$count10+1;
		$i10=$i10+1;
		$hrefQC = "pgQCDocument.php?id=".$modid."&inward_id=".$id;
	}
	
	$disabledQcDownload = "";
	$disabledGRNDownload = "";
	if($hrefGRN==""){

			$disabledGRNDownload = "<button type='button' class='greenbg ' disabled ><i class='ti-download'></i></button>";
	}
	else{
		$disabledGRNDownload = "<a href='".$hrefGRN."' target='_blank'>
						<button type='button' class='greenbg ' disabled><i class='ti-download'></i></button></a>";
	}
	
	if($hrefQC==""){
		$disabledQcDownload = "<button type='button' class='greenbg' disabled><i  class='ti-download'></i></button>";
	}
	else{
		$disabledQcDownload = "<a href='".$hrefQC."' target='_blank'><button type='button' class='greenbg' disabled><i  class='ti-download'></i></button></a>";
	}
		
		
	//--Karuna ahref end QC
	if($disabledEway=="" && $disabledInvoice=="" && $disabledWBS==""){
		
		$divAcceptReject = "<div class='btn-group' role='group'>
								<a href='javascript:void(0)' id='alinkaccept' onclick='AcceptRecord(".$id.")'><img src='".$settingValueUserImagePathOther."".$settingValueAcceptImage."' id='imgaccept' class='img-sm rounded-circle mb-3 '/><a>
								<a href='javascript:void(0)' id='alinkreject' onclick='RejectRecord(".$id.")'><img src='".$settingValueUserImagePathOther."".$settingValueRejectImage."'  id='imgreject' class='img-sm rounded-circle mb-3 '/><a>
							</div>";
							
							
		$divGrnQc = "<div class='row'>
						<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
						<div class='row'>
							<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
							<div class='btn-group'>
							".$disabledGRNDownload."
							<button type='button' class='whitebg dropdown-toggle tw-doc-button-custom' data-toggle='dropdown'>
							  GRN(".$retValiv.")
							</button>
							".$dropdownGRN."
						</div> 
					</div>
					</div> 
					</div>
				</div>

				<div class='row'>
					<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
					<div class='row'>
							<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
						 <div class='btn-group'>
							".$disabledQcDownload."
							<button type='button' class='whitebg dropdown-toggle tw-doc-button-custom' data-toggle='dropdown'>
							  QC(".$retValv.")
							</button>
							".$dropdownQC."
							
						  </div>
					</div>
					</div>
					</div>";
	}
	else{
		
		$divAcceptReject = "";
		$divGrnQc = "<div class='row'>
						<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
						<div class='row'>
							<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
							<div class='btn-group'>
							".$disabledGRNDownload."
							<button type='button' class='whitebg dropdown-toggle tw-doc-button' >
							  GRN(".$retValiv.")
							</button>
							".$dropdownGRN."
						  </div> 
						</div>
						</div> 
						</div>
					</div>

					
					<div class='row'>
						<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
						<div class='row'>
							<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
						 <div class='btn-group'>
							
							".$disabledQcDownload."
							<button type='button' class='btn btn-info dropdown-toggle disabled tw-doc-button' data-toggle='dropdown'>
							  QC(".$retValv.")
							</button>
							".$dropdownQC."
							</div>
						  </div>
						  </div>
						  </div>
					</div>";

	}
	$distanceimage = $settingValueEmployeeImagePathOther.$settingValueDistanceImage;
	if($supplier_geo_location=="" || $receiver_geo_location!=""){
		$link="https://www.google.com/maps/place/".$receiver_geo_location;
	}
	else if($supplier_geo_location!="" || $receiver_geo_location==""){
		$link="https://www.google.com/maps/place/".$supplier_geo_location;
	}
	else{
		$link="https://www.google.com/maps/dir/".$supplier_geo_location."/".$receiver_geo_location;	
	}
	
	
	$table.="<div class='card'>
	  
		<div class='card-body'>
			<div class='row'>
		<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12 left-s'>
                <div class='row'>
                    <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 text-center'>
                        <img src='".$Company_Logo."' class='piclogo'>
                    </div>
                    <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6'>
                        <div class='detail'>
                           <a id='ainfo' onclick='ViewInfo(".$id.")' target='_blank'><img src='".$settingValueEmployeeImagePathOther."/info.png' class='picinfo'><span>Details</span>
						   </a>
                        </div>
                        <div class='maps'>
                          <a href='".$link."' target='_blank' >  <img src='".$settingValueEmployeeImagePathOther."/maps.png' class='picmaps'><span>Maps</span>
						  </a>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 '>
                            <h2 class='title'>".$CompanyName." ".$img."</h2>
                            <div class='amount '>
                                <img src='".$settingValueEmployeeImagePathOther."/Total Amount.png' class='picamount'><span>Total Amount : ".number_format(round($final_total_amout,0),2)."</span>
                            </div>
                            <div class='quantity '>
                                <img src='".$settingValueEmployeeImagePathOther."/Total Quantity.png' class='picquantity'><span>Total Quantity : ".number_format($total_quantity,2)."</span>
                            </div>
                            <div class='calender'>
                                <img src='".$settingValueEmployeeImagePathOther."/calender.png' class='piccalender'><span>Date :  ".date("d-m-Y", strtotime($created_on))."</span>
                            </div>
                                <div class='approved'>Inprocess</div>
                        </div>
                    </div>
                </div>

		";	
		$table.="		
									<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12'>
										<div class='row'>
											<div class='col-12 col-xl-12'>
											 <div class='justify-content-end d-flex'>
										
										  </div>
										</div>
									  </div>
									</div>
									
								 </div>
								
								  <div class='col-lg-7 col-md-7 col-sm-12 col-xs-12 col-12'>
								   <div class=' float-right col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12'>
									<div class='row justify-content-end d-flex'>
									
									".$divAcceptReject."
									</div>
									</div>
									<div class='row'>
							    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12'>
								 <div class='row'>
										<div class='row'>
											<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
										 <div class='btn-group'>
												<a href=".$hrefEway." target='_blank'>
													<button type='button' class='greenbg' ".$disabledEway." ><i class='ti-download'></i></button></a>
												
													<button type='button' class='whitebg' ".$disabledEway.">Eway(".$retValii.")</button>
												</div> 
											</div>
										</div>
									<div class='col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6'>
										<div class='row'>
											<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
										 <div class='btn-group'>
											<a href='".$hrefInvoice."' target='_blank'>
											<button type='button' class='greenbg' ".$disabledInvoice."><i  class='ti-download'></i></button></a>
											<button type='button' class='whitebg' ".$disabledInvoice.">Invoice(".$retVali.")</button>
										  </div> 
										</div>
									</div>
								</div>
								<div class='col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6'>
									<div class='row'>
										<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
										 <div class='btn-group'>
											<a href='".$hrefWBS."' target='_blank'>
											<button type='button' class='greenbg' ".$disabledWBS."><i  class='ti-download'></i></button></a>
											<button type='button' class='whitebg' ".$disabledWBS.">WBS(".$retValiii.")</button>
										</div> 
									</div>
								</div>
							</div>	
							<div class='col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6'>
								<div class='row'>
									<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
										 <div class='btn-group'>
											<a href='".$hrefRL."' target='_blank'>
											<button type='button' class='greenbg' ".$disabledRL."><i  class='ti-download'></i></button></a>
											<button type='button' class='whitebg' ".$disabledRL.">RL(".$retValiii.")</button>
										  </div>
										</div> 
									</div>
								</div>
							</div>
							<br>
							<div class='row'>
									".$divGrnQc."
								</div>	
									
								 </div>
								 </div>
								</div>
								 <br>
								 <div class='row'>";
								 
								 //--Karuna start Photos
								$queryZ11 = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='Photo' Order by id asc";
								$retValZ11 = $sign->FunctionJSON($queryZ11);
								
								$qry1Z11="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='Photo' Order by id asc";
								$retVal1Z11 = $sign->Select($qry1Z11);
								$decodedJSON211 = json_decode($retValZ11);
								$count11 = 0;
								$i11 = 1;
								$x11=$retVal1Z11;
								$it=1;
								while($x11>=$i11){

									$modid = $decodedJSON211->response[$count11]->id;
									$count11=$count11+1;
									$document = $decodedJSON211->response[$count11]->document;
									$count11=$count11+1;
									
									$table.="
											
												<div class='row'>
													<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3'>
														<div class='btn-group'>
														<a href='../assets/images/Documents/Employee/Outward/".$document."' target='_blank'>
														<button type='button' class='greenbg'><i  class='ti-download'></i></button></a>
														<button type='button' class='whitebg'>Photo ".$i11."</button>
													  </div>
												</div>
											</div>
											";
											$i11=$i11+1;
											 //$table.=" ".$photodiv."";
								}
								//--Karuna end Photos
								
							 $table.="</div>
							 </div>
						</div> 
					</div>
				  </div><br>";		
			

		$i=$i+1;
		//------Karuna end
		
		
	}

		//$table.="</tbody>";
}
	echo $table;
	?>