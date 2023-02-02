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


$qry="SELECT mo.id as moid,mo.customer_id,cd.CompanyName,cd.Status,cd.Company_Logo,mo.total_quantity,mo.final_total_amout,mo.created_on,supplier_geo_location FROM tw_material_outward mo INNER JOIN tw_company_details cd ON mo.customer_id = cd.ID WHERE mo.employee_id = '".$employee_id."' and mo.po_id  = '".$po_id."' and mo.status='".$settingValuePendingStatus."' ORDER BY mo.id DESC";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward where employee_id='".$employee_id."' and po_id ='".$po_id."' and status='".$settingValuePendingStatus."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$uploadphotodiv="";
$pid = "";
$uploadphotodiv = "";
if($retVal1==0 || $retVal1==0.00){
	$table.="
				<div class='card'>
				  
					<div class='card-body text-center'>
							<img src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
						</div>
					</div>
					
				  </div><br>";	
	
}
else{
		while($x>=$i){
		$outward_id = $decodedJSON2->response[$count]->moid;
		$count=$count+1;
		$customer_id = $decodedJSON2->response[$count]->customer_id;
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
		//------Karuna Start
		
		$qry3="Select value from tw_company_contact where company_id='".$customer_id."'";
		$retVal3 = $sign->SelectF($qry3,"value");
		$verifyStatus=$commonfunction->getSettingValue("Verified Status");
		$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
		$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
		$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
		$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

		$qry4="SELECT SUM(total_quantity) FROM tw_material_outward WHERE po_id='".$po_id."' and status='".$settingValuePendingStatus."'";
		$retVal4 = $sign->SelectF($qry4,"SUM(total_quantity)");
		
		$qry5="Select verification_status from tw_verification_status_master where id='".$settingValuePendingStatus."'";
		$retVal5 = $sign->SelectF($qry5,"verification_status"); 
		
		$TotalBalQty = $total_quantity-$retVal4;
		
		if(empty($Company_Logo)){
			$Company_Logo=$UserImagePathOther.$settingValueCompanyImage;
		} 
		else{
			$Company_Logo=$settingValueUserImagePathVerification.$retVal3."/".$Company_Logo;
		}
		$img="";
		if ($companyStatus==$verifyStatus) { 
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
		$hrefEwayGenerate = "";
		$hrefInvoiceGenerate = "";
		$hrefInvoiceWBS = "";
		
		$atagEway = "";
		//---Eway start
		$qryEway="SELECT COUNT(*) as cnt,id from tw_material_outward_documents WHERE type='Eway' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$valqryEway = $sign->FunctionJSON($qryEway);
		$decodedJSON = json_decode($valqryEway);
		$retValEway = $decodedJSON->response[0]->cnt; 
		$idEwayDoc = $decodedJSON->response[1]->id; 
		
		$qry1Eway="SELECT COUNT(*) as cnt,id from tw_material_outward_eway WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
		
		$valqry1Eway = $sign->FunctionJSON($qry1Eway);
		$decodedJSON = json_decode($valqry1Eway);
		$retVal1Eway = $decodedJSON->response[0]->cnt; 
		$idEway = $decodedJSON->response[1]->id; 

		if($retValEway>0 || $retVal1Eway>0){
			$disabledEway="";
		}
		else{
			$disabledEway="disabled";
		}
		
		if($retVal1Eway==0){
			$hrefEwayGenerate="pgMaterialOutwardEwayForm.php?type=add&id=&outwardid=".$outward_id."";
		}
		else{
			$hrefEwayGenerate="pgMaterialOutwardEwayForm.php?type=edit&id=".$idEway."&outwardid=".$outward_id."";
		}	
		
		if($retValEway==0){
			$atagEway="<a class='dropdown-item' href='#' onclick='EwayRecordupload(".$outward_id.")' id='yourBtn'><i class='ti-upload'></i></i> Upload</a>";
		}
		else{
			$atagEway="<a class='dropdown-item' href='#' onclick='DeleteRecordupload(".$idEwayDoc.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a>";
		}
		//---Eway end
		//---Invoice start
		$qryInvoice="SELECT COUNT(*) as cnt,id from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$valqryInvoice = $sign->FunctionJSON($qryInvoice);
		$decodedJSON = json_decode($valqryInvoice);
		$retValInvoice = $decodedJSON->response[0]->cnt; 
		$idInvoiceDoc = $decodedJSON->response[1]->id; 
		

		$qry1Invoice="SELECT COUNT(*) as cnt,id from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
		
		$valqry1Invoice = $sign->FunctionJSON($qry1Invoice);
		$decodedJSON = json_decode($valqry1Invoice);
		$retVal1Invoice = $decodedJSON->response[0]->cnt; 
		$idInvoice = $decodedJSON->response[1]->id; 
		
		if($retVal1Invoice==0){
			$hrefInvoiceGenerate="pgTaxInvoiceForm.php?type=add&id=&outward_id=".$outward_id."";
		}
		else{
			$hrefInvoiceGenerate="pgTaxInvoiceForm.php?type=edit&id=".$idInvoice."&outward_id=".$outward_id."";
			$dropdownInvoice="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>  <a class='dropdown-item' href='".$hrefInvoiceGenerate."'><i class='ti-pencil'></i></i> Edit</a>
			<a class='dropdown-item' href='#' onclick='DeleteRecordInvoice(".$idInvoice.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a> 
											</div>";
		}
		if($retValInvoice==0){
		}
		else{
			$dropdownInvoice="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''> 
												 <a class='dropdown-item' href='#' onclick='DeleteRecordupload(".$idInvoiceDoc.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a> 
												 
											</div>";
		}
		
		if($retValInvoice>0 || $retVal1Invoice>0){
			$disabledInvoice="";
		}
		else{
			$disabledInvoice="disabled";
			$dropdownInvoice="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''> 
												 <a class='dropdown-item' href='#' onclick='InvoiceRecordupload(".$outward_id.")' id='yourBtn'><i class='ti-upload'></i></i> Upload</a> 
												  <a class='dropdown-item' href='".$hrefInvoiceGenerate."'><i class='ti-plus'></i></i> Generate</a>
											</div>";
		}
		//---Invoice end
		//---WBS start
		$qryWBS="SELECT COUNT(*) as cnt,id from tw_material_outward_documents WHERE type='WBS' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
		
		$valqryWBS = $sign->FunctionJSON($qryWBS);
		$decodedJSON = json_decode($valqryWBS);
		$retValWBS = $decodedJSON->response[0]->cnt; 
		$idWBSDoc = $decodedJSON->response[1]->id; 

		$qry1WBS="SELECT COUNT(*) as cnt,id from tw_material_outward_wbs WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$valqry1WBS = $sign->FunctionJSON($qry1WBS);
		$decodedJSON = json_decode($valqry1WBS);
		$retVal1WBS = $decodedJSON->response[0]->cnt; 
		$idWBS = $decodedJSON->response[1]->id; 
		
		if($retVal1WBS==0){
			$hrefInvoiceWBS="pgMaterialOutwardWBSForm.php?type=add&id=&outwardid=".$outward_id."";
		}
		else{
			$hrefInvoiceWBS="pgMaterialOutwardWBSForm.php?type=edit&id=".$idWBS."&outwardid=".$outward_id."";
			$dropdownWBS="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
												  <a class='dropdown-item' href='".$hrefInvoiceWBS."'><i class='ti-pencil'></i></i> Edit</a>
												  <a class='dropdown-item' href='#' onclick='DeleteRecordWBS(".$idWBS.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a> 
											</div>
											";
		}
		
		if($retValWBS==0){
		}
		else{
			$dropdownWBS="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
												 <a class='dropdown-item' href='#' onclick='DeleteRecordupload(".$idWBSDoc.")' id='yourBtn'><i class='ti-trash'></i></i> Delete</a> 
											</div>
											";
		}
		
		
		if($retValWBS>0 || $retVal1WBS>0){
			$disabledWBS="";
		}
		else{
			$disabledWBS="disabled";
			$dropdownWBS="<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
												 <a class='dropdown-item' href='#' onclick='WBSRecordupload(".$outward_id.")' id='yourBtn'><i class='ti-upload'></i></i> Upload</a> 
												  
												  <a class='dropdown-item' href='".$hrefInvoiceWBS."'><i class='ti-plus'></i></i> Generate</a>
											</div>
											";
		}
		
		//---WBS end
			
		$qryGRN="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='GRN' and inward_id='".$outward_id."' ORDER BY inward_id ASC";
		$retValGRN = $sign->SelectF($qryGRN,"cnt");

		$qry1GRN="SELECT COUNT(*) as cnt from tw_material_inward_grn WHERE inward_id='".$outward_id."' ORDER BY inward_id ASC";
		$retVal1GRN = $sign->SelectF($qry1GRN,"cnt");
		if($retValGRN>0 || $retVal1GRN>0){
			$disabledGRN="";
		}
		else{
			$disabledGRN="disabled";
		}
		$qryQC="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='QC' and inward_id='".$outward_id."' ORDER BY inward_id ASC";
		$retValQC = $sign->SelectF($qryQC,"cnt");

		$qry1QC="SELECT COUNT(*) as cnt from tw_material_inward_qc WHERE inward_id='".$outward_id."' ORDER BY inward_id ASC";
		$retVal1QC = $sign->SelectF($qry1QC,"cnt");
		if($retValQC>0 || $retVal1QC>0){
			$disabledQC="";
		}
		else{
			$disabledQC="disabled";
		}
	
		
	//Count  start
	$qryii="select sum(tbl.EachTableCount)from(
	select count(*) as EachTableCount from tw_material_outward_documents WHERE type='Eway' and outward_id='".$outward_id."'
	UNION ALL
	select count(*) as EachTableCount from tw_material_outward_eway WHERE outward_id='".$outward_id."'
	)tbl";
	 $retValii = $sign->SelectF($qryii,"sum(tbl.EachTableCount)");
	$qryi="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_tax_invoice WHERE outward_id='".$outward_id."'
		)tbl";
	
	$retVali = $sign->SelectF($qryi,"sum(tbl.EachTableCount)");
	$qryiii="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_outward_documents WHERE type='WBS' and outward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_outward_wbs WHERE outward_id='".$outward_id."'
		)tbl";
	$retValiii = $sign->SelectF($qryiii,"sum(tbl.EachTableCount)");
	$qryiv="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_inward_documents WHERE type='GRN' and inward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_inward_grn WHERE inward_id='".$outward_id."'
		)tbl";
	$retValiv = $sign->SelectF($qryiv,"sum(tbl.EachTableCount)");
	$qryv="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_inward_documents WHERE type='QC' and inward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_inward_qc WHERE inward_id='".$outward_id."'
		)tbl";
	$retValv = $sign->SelectF($qryv,"sum(tbl.EachTableCount)");
		
	//--Count end
	
	$hrefEway ="";
	$hrefInvoice ="";
	$hrefWBS ="";
	$hrefGRN ="";
	$hrefQC =""; 
	//--Karuna ahref start Eway
	
	$queryZ1 = "select id,document from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Eway' Order by id Desc";
	$retValZ1 = $sign->FunctionJSON($queryZ1);
	
	$qry1Z1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Eway' Order by id Desc";
	$retVal1Z1 = $sign->Select($qry1Z1);
	$decodedJSON2Z1 = json_decode($retValZ1);
	$countZ1 = 0;
	$iZ1 = 1;
	$x1=$retVal1Z1;
	$it=1;
	while($x1>=$iZ1){

		$modid = $decodedJSON2Z1->response[$countZ1]->id;
		$countZ1=$countZ1+1;
		$document = $decodedJSON2Z1->response[$countZ1]->document;
		$countZ1=$countZ1+1;
		$iZ1=$iZ1+1;
		
		$hrefEway = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ2 = "select id from tw_material_outward_eway where outward_id = '".$outward_id."' Order by id Desc";
	$retValZ2 = $sign->FunctionJSON($queryZ2);
	
	$qry1Z2="Select count(*) as cnt from tw_material_outward_eway where outward_id = '".$outward_id."' Order by id Desc";
	$retVal1Z2 = $sign->Select($qry1Z2);
	$decodedJSON2Z2 = json_decode($retValZ2);
	$countZ2 = 0;
	$iZ2 = 1;
	$x2=$retVal1Z2;
	$it=1;
	while($x2>=$iZ2){

		$modid = $decodedJSON2Z2->response[$countZ2]->id;
		$countZ2=$countZ2+1;
		$iZ2=$iZ2+1;
		$hrefEway = "pgeWayBill.php?id=".$modid."&outward_id=".$outward_id;
	}
	
	//--Karuna ahref end Eway
	//--Karuna ahref start Invoice
	$queryZ3 = "select id,document from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Invoice' Order by id Desc";
	$retValZ3 = $sign->FunctionJSON($queryZ3);
	
	$qry1Z3="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Invoice' Order by id Desc";
	$retVal1Z3 = $sign->Select($qry1Z3);
	$decodedJSON2Z3 = json_decode($retValZ3);
	$countZ3 = 0;
	$iZ3 = 1;
	$x3=$retVal1Z3;
	$it=1;
	while($x3>=$iZ3){

		$modid = $decodedJSON2Z3->response[$countZ3]->id;
		$countZ3=$countZ3+1;
		$document = $decodedJSON2Z3->response[$countZ3]->document;
		$countZ3=$countZ3+1;
		$iZ3=$iZ3+1;
		$hrefInvoice = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ4 = "select id,invoice_number from tw_tax_invoice where outward_id = '".$outward_id."' Order by id Desc";
	$retValZ4 = $sign->FunctionJSON($queryZ4);
	
	$qry1Z4="Select count(*) as cnt from tw_tax_invoice where outward_id = '".$outward_id."' Order by id Desc";
	$retVal1Z4 = $sign->Select($qry1Z4);
	$decodedJSON2Z4 = json_decode($retValZ4);
	$countZ4 = 0;
	$iZ4 = 1;
	$x4=$retVal1Z4;
	$it=1;
	while($x4>=$iZ4){

		$modid = $decodedJSON2Z4->response[$countZ4]->id;
		$countZ4=$countZ4+1;
		$invoice_number = $decodedJSON2Z4->response[$countZ4]->invoice_number;
		$countZ4=$countZ4+1;	
		$iZ4=$iZ4+1;
		$hrefInvoice = "pgTaxInvoiceDocuments.php?id=".$modid."&voutward_id=".$outward_id;
	}
	//--Karuna ahref end Invoice
	//--Karuna ahref start WBS
	$queryZ5 = "select id,document from tw_material_outward_documents where outward_id = '".$outward_id."' and type='WBS' Order by id Desc";
	$retValZ5 = $sign->FunctionJSON($queryZ5);
	
	$qry1Z5="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='WBS' Order by id Desc";
	$retVal1Z5 = $sign->Select($qry1Z5);
	$decodedJSON2Z5 = json_decode($retValZ5);
	$countZ5 = 0;
	$iZ5 = 1;
	$x5=$retVal1Z5;
	$it=1;
	while($x5>=$iZ5){

		$modid = $decodedJSON2Z5->response[$countZ5]->id;
		$countZ5=$countZ5+1;
		$document = $decodedJSON2Z5->response[$countZ5]->document;
		$countZ5=$countZ5+1;
		$iZ5=$iZ5+1;
		$hrefWBS = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ6 = "select id from tw_material_outward_wbs where outward_id = '".$outward_id."' Order by id Desc";
	$retValZ6 = $sign->FunctionJSON($queryZ6);
	
	$qry1Z6="Select count(*) as cnt from tw_material_outward_wbs where outward_id = '".$outward_id."' Order by id Desc";
	$retVal1Z6 = $sign->Select($qry1Z6);
	$decodedJSON2Z6 = json_decode($retValZ6);
	$countZ6 = 0;
	$iZ6 = 1;
	$x6=$retVal1Z6;
	$it=1;
	while($x6>=$iZ6){

		$modid = $decodedJSON2Z6->response[$countZ6]->id;
		$countZ6=$countZ6+1;
		$iZ6=$iZ6+1;
		$hrefWBS = "pgWaybillslip.php?id=".$modid."&outward_id=".$outward_id;
	}
	//--Karuna ahref end WBS
	//--Karuna ahref start GRN
	$queryZ7 = "select id,document from tw_material_inward_documents where inward_id = '".$outward_id."' and type='GRN' Order by id Desc";
	$retValZ7 = $sign->FunctionJSON($queryZ7);
	
	$qry1Z7="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$outward_id."' and type='GRN' Order by id Desc";
	$retVal1Z7 = $sign->Select($qry1Z7);
	$decodedJSON2Z7 = json_decode($retValZ7);
	$countZ7 = 0;
	$iZ7 = 1;
	$x7=$retVal1Z7;
	$it=1;
	while($x7>=$iZ7){
		$modid = $decodedJSON2Z7->response[$countZ7]->id;
		$countZ7=$countZ7+1;
		$document = $decodedJSON2Z7->response[$countZ7]->document;
		$countZ7=$countZ7+1;
		$iZ7=$iZ7+1;
		$hrefGRN = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ8 = "select id from tw_material_inward_grn where inward_id = '".$outward_id."' Order by id Desc";
	$retValZ8 = $sign->FunctionJSON($queryZ8);
	
	$qry1Z8="Select count(*) as cnt from tw_material_inward_grn where inward_id = '".$outward_id."' Order by id Desc";
	$retVal1Z8 = $sign->Select($qry1Z8);
	$decodedJSON2Z8 = json_decode($retValZ8);
	$countZ8 = 0;
	$iZ8 = 1;
	$x8=$retVal1Z8;
	$it=1;
	while($x8>=$iZ8){
		$modid = $decodedJSON2Z8->response[$countZ8]->id;
		$countZ8=$countZ8+1;
		$iZ8=$iZ8+1;
		$hrefGRN = "pgGRN.php?id=".$modid."&inward_id=".$outward_id;
	}
	//--Karuna ahref end GRN
	//--Karuna ahref start QC
	$queryZ9 = "select id,document from tw_material_inward_documents where inward_id = '".$outward_id."' and type='QC' Order by id Desc";
	$retValZ9 = $sign->FunctionJSON($queryZ9);
	
	$qry1Z9="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$outward_id."' and type='QC' Order by id Desc";
	$retVal1Z9 = $sign->Select($qry1Z9);
	$decodedJSON2Z9 = json_decode($retValZ9);
	$countZ9 = 0;
	$iZ9 = 1;
	$x9=$retVal1Z9;
	$it=1;
	while($x9>=$iZ9){

		$modid = $decodedJSON2Z9->response[$countZ9]->id;
		$countZ9=$countZ9+1;
		$document = $decodedJSON2Z9->response[$countZ9]->document;
		$countZ9=$countZ9+1;
		$iZ9=$iZ9+1;
		$hrefQC = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$queryZ10 = "select id from tw_material_inward_qc where inward_id = '".$outward_id."' Order by id Desc";
	$retValZ10 = $sign->FunctionJSON($queryZ10);
	
	$qry1Z10="Select count(*) as cnt from tw_material_inward_qc where inward_id = '".$outward_id."' Order by id Desc";
	$retVal1Z10 = $sign->Select($qry1Z10);
	$decodedJSON2Z10 = json_decode($retValZ10);
	$countZ10 = 0;
	$iZ10 = 1;
	$x10=$retVal1Z10;
	$it=1;
	while($x10>=$iZ10){

		$modid = $decodedJSON2Z10->response[$countZ10]->id;
		$countZ10=$countZ10+1;
		$iZ10=$iZ10+1;
		$hrefQC = "pgQCDocument.php?id=".$modid."&inward_id=".$outward_id;
	}
	//--Karuna ahref end QC 
	$distanceimage = $settingValueEmployeeImagePathOther.$settingValueDistanceImage;
	$link="https://www.google.com/maps/place/".$supplier_geo_location;
		
	
		$table.="
				<div class='card'>
					<div class='card-body'>
						<div class='row'>
							<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12' style='text-align:center;'>
								<img src='".$Company_Logo."' width='100%' style='width:100px;height:100px;' class='img-sm mb-3 '><br>
								<p>".$retVal5."<br><a id='ainfo' onclick='ViewInfo(".$outward_id.")' class='text-info pointer-cursor'><i class='ti-info' ></i></a><br><a href='".$link."' class='float-center' target='_blank'><img src='".$distanceimage."' width='100%' class='img-sm mb-3 '></a></p>
							</div>
							<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
								
								 
								 <div class='row'> 
									<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12'>
										<strong>".$CompanyName." ".$img."</strong>
										<p><i class='ti-wallet'></i> Total Amount : <span>&#8377;</span> ".number_format(round($final_total_amout,0),2)." | <i class='ti-package'></i> Total Quantity : ".number_format($total_quantity,2)."<br>Date : ".$created_on."</p>
									</div>
									<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12'>
										 <div class='justify-content-end d-flex'>
										  <div class='dropdown flex-md-grow-1 flex-xl-grow-0'>
											<button class='btn btn-sm btn-light bg-white dropdown-toggle' type='button' id='dropdownMenuDate2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
											 </button>
											<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
											  <a class='dropdown-item' href='#' onclick='editRecord(".$outward_id.")'><i class='ti-pencil'></i></i> Edit</a>
											
										 </div>
										 
										</div>
									  </div>
									</div>
									
								 </div>
								 <hr>
								 <div class='row'>
									<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
										 <div class='btn-group'>
											<a href='".$hrefEway."' target='_blank'>
											<button type='button' class='btn btn-light tw-doc-button' ".$disabledEway."><i class='ti-download'></i></button></a>
											<button type='button' class='btn btn-info dropdown-toggle tw-doc-button' data-toggle='dropdown'>
											  Eway(".$retValii.")
											</button>
											<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
												  ".$atagEway." 
											</div>
											
										  </div>
									</div>
									<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
										 <div class='btn-group'>
											<a href='".$hrefInvoice."' target='_blank'>
											<button type='button' class='btn btn-light tw-doc-button' ".$disabledInvoice."><i class='ti-download'></i></button></a>
											<button type='button' class='btn btn-info dropdown-toggle tw-doc-button' data-toggle='dropdown'>
											  Invoice(".$retVali.")
											</button>
											".$dropdownInvoice."
											
										  </div>
									</div>
									<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
										 <div class='btn-group'>
											<a href='".$hrefWBS."' target='_blank'>
											<button type='button' class='btn btn-light tw-doc-button' ".$disabledWBS."><i class='ti-download'></i></button></a>
											<button type='button' class='btn btn-info dropdown-toggle tw-doc-button' data-toggle='dropdown'>
											  WBS(".$retValiii.")
											</button>
											".$dropdownWBS."
										  </div>
									</div>
									<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
										 <div class='btn-group'>
											<a href='".$hrefGRN."' target='_blank'>
											<button type='button' class='btn btn-light tw-doc-button' ".$disabledGRN."><i  class='ti-download'></i></button></a>
											<button type='button' class='btn btn-info tw-doc-button' ".$disabledGRN.">GRN(".$retValiv.")</button>
										  </div>
									</div>
									<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
										 <div class='btn-group'>
											<a href='".$hrefQC."' target='_blank'>
											<button type='button' class='btn btn-light tw-doc-button' ".$disabledQC."><i  class='ti-download'></i></button></a>
											<button type='button' class='btn btn-info tw-doc-button' ".$disabledQC.">QC(".$retValv.")</button>
										  </div>
									</div>
								 </div>
								 <br><br>
								 
								 <div class='row'>
								 
								 ";
								 
								//--Karuna start Photos
								$querypZ11 = "select id,document from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Photo' Order by id asc";
								$retValpZ11 = $sign->FunctionJSON($querypZ11);
								
								$qry1pZ11="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Photo' Order by id asc";
								$retVal1pZ11 = $sign->Select($qry1pZ11);
								$decodedJSON2Z11 = json_decode($retValpZ11);
								$countZ11 = 0;
								$iZ11 = 1;
								$x11=$retVal1pZ11;
								$it=1;
								while($x11>=$iZ11){
									$modid = $decodedJSON2Z11->response[$countZ11]->id;
									$countZ11=$countZ11+1;
									$document = $decodedJSON2Z11->response[$countZ11]->document;
									$countZ11=$countZ11+1;
									$idNo = $iZ11-1;
									$table.="
												<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
													 <div class='btn-group'>
													<a href='../assets/images/Documents/Employee/Outward/".$document."' target='_blank'>
													<button type='button' class='btn btn-light tw-doc-button'><i class='ti-download'></i></button></a>
													<button type='button' class='btn btn-info dropdown-toggle tw-doc-button' data-toggle='dropdown'>
													  Photo ".$iZ11."
													</button>
													<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
														  <a class='dropdown-item' href='#' onclick='DeleteRecordupload(".$modid.")'><i class='ti-trash'></i></i> Delete</a> 
													</div>
													
												  </div>
												</div>
											";
											$iZ11=$iZ11+1;
								}
								
								
								if($retVal1pZ11==5){
									$uploadphotodiv = "";
								}
								else{									
									$uploadphotodiv = "<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
														<div class='btn-group'>
															
															<button type='button' onclick='getFilePhotos(".$outward_id.")' class='btn btn-info tw-doc-button'>+ Upload Photo</button>
															<div style='height: 0px;width: 0px; overflow:hidden;'><input type='file' name='Document_ProofPhotos".$outward_id."' accept='.png, .jpg, .jpeg, .pdf' id='Document_ProofPhotos".$outward_id."' onchange='PhotosRecordupload(".$outward_id.");' /></div>
														</div>
																			  
														</div>";
								}
								//--Karuna end Photos 
								
							 $table.="
									".$uploadphotodiv."
							 
								 </div>
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