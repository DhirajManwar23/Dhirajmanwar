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


$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");


$qry="SELECT mo.id,mo.customer_id,cd.CompanyName,cd.Status,cd.Company_Logo,mo.total_quantity,mo.final_total_amout,mo.reason FROM tw_material_outward mo INNER JOIN tw_company_details cd ON mo.customer_id = cd.ID WHERE mo.employee_id = '".$employee_id."' and mo.po_id  = '".$po_id."' and mo.status='".$settingValueRejectedStatus."' ORDER BY mo.id DESC";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward where employee_id='".$employee_id."' and po_id ='".$po_id."' and status='".$settingValueRejectedStatus."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
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
			
		$id = $decodedJSON2->response[$count]->id;
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
		$reasonR = $decodedJSON2->response[$count]->reason;
		$count=$count+1;
		//------Karuna Start
		$qry3 = "select reason from tw_rejected_reason_master where id = '".$reasonR."'";
		$reason = $sign->SelectF($qry3,'reason');
		
		$qry3="Select value from tw_company_contact where company_id='".$customer_id."'";
		$retVal3 = $sign->SelectF($qry3,"value");
		$verifyStatus=$commonfunction->getSettingValue("Verified Status");
		$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
		$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
		$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
		$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

		$qry4="SELECT SUM(total_quantity) FROM tw_material_outward WHERE po_id='".$po_id."' and status='".$settingValueRejectedStatus."'";
		$retVal4 = $sign->SelectF($qry4,"SUM(total_quantity)");
		
		$qry5="Select verification_status from tw_verification_status_master where id='".$settingValueRejectedStatus."'";
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
		
		$qryGRN="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='GRN' and inward_id='".$id."' ORDER BY inward_id ASC";
		$retValGRN = $sign->SelectF($qryGRN,"cnt");

		$qry1GRN="SELECT COUNT(*) as cnt from tw_material_inward_grn WHERE inward_id='".$id."' ORDER BY inward_id ASC";
		$retVal1GRN = $sign->SelectF($qry1GRN,"cnt");
		if($retValGRN>0 || $retVal1GRN>0){
			$disabledGRN="";
		}
		else{
			$disabledGRN="disabled";
		}
		$qryQC="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='QC' and inward_id='".$id."' ORDER BY inward_id ASC";
		$retValQC = $sign->SelectF($qryQC,"cnt");

		$qry1QC="SELECT COUNT(*) as cnt from tw_material_inward_qc WHERE inward_id='".$id."' ORDER BY inward_id ASC";
		$retVal1QC = $sign->SelectF($qry1QC,"cnt");
		if($retValQC>0 || $retVal1QC>0){
			$disabledQC="";
		}
		else{
			$disabledQC="disabled";
		}
		
		
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
	//--Karuna ahref start Eway
	
	$query = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='Eway' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='Eway' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){

		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$document = $decodedJSON2->response[$count]->document;
		$count=$count+1;
		$i=$i+1;
		
		$hrefEway = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$query = "select id from tw_material_outward_eway where outward_id = '".$id."' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_outward_eway where outward_id = '".$id."' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){

		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$i=$i+1;
		$hrefEway = "pgeWayBill.php?id=".$modid."&outward_id=".$id;
	}
	
	//--Karuna ahref end Eway
	//--Karuna ahref start Invoice
	$query = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='Invoice' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='Invoice' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){

		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$document = $decodedJSON2->response[$count]->document;
		$count=$count+1;
		$i=$i+1;
		$hrefInvoice = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$query = "select id,invoice_number from tw_tax_invoice where outward_id = '".$id."' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_tax_invoice where outward_id = '".$id."' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){

		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$invoice_number = $decodedJSON2->response[$count]->invoice_number;
		$count=$count+1;	
		$i=$i+1;
		$hrefInvoice = "pgTaxInvoiceDocuments.php?id=".$modid."&voutward_id=".$id;
	}
	//--Karuna ahref end Invoice
	//--Karuna ahref start WBS
	$query = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='WBS' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='WBS' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){

		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$document = $decodedJSON2->response[$count]->document;
		$count=$count+1;
		$i=$i+1;
		$hrefWBS = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$query = "select id from tw_material_outward_wbs where outward_id = '".$id."' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_outward_wbs where outward_id = '".$id."' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){

		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$i=$i+1;
		$hrefWBS = "pgWaybillslip.php?id=".$modid."&outward_id=".$id;
	}
	//--Karuna ahref end WBS
	//--Karuna ahref start GRN
	$query = "select id,document from tw_material_inward_documents where inward_id = '".$id."' and type='GRN' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$id."' and type='GRN' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){
		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$document = $decodedJSON2->response[$count]->document;
		$count=$count+1;
		$i=$i+1;
		$hrefGRN = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$query = "select id from tw_material_inward_grn where inward_id = '".$id."' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_inward_grn where inward_id = '".$id."' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){
		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$i=$i+1;
		$hrefGRN = "pgGRN.php?id=".$modid."&inward_id=".$id;
	}
	//--Karuna ahref end GRN
	//--Karuna ahref start QC
	$query = "select id,document from tw_material_inward_documents where inward_id = '".$id."' and type='QC' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$id."' and type='QC' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){

		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$document = $decodedJSON2->response[$count]->document;
		$count=$count+1;
		$i=$i+1;
		$hrefQC = "../assets/images/Documents/Employee/Outward/".$document;
	}
	$query = "select id from tw_material_inward_qc where inward_id = '".$id."' Order by id Desc";
	$retVal = $sign->FunctionJSON($query);
	
	$qry1="Select count(*) as cnt from tw_material_inward_qc where inward_id = '".$id."' Order by id Desc";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){

		$modid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$i=$i+1;
		$hrefQC = "pgQCDocument.php?id=".$modid."&inward_id=".$id;
	}
	//--Karuna ahref end QC
	
		
		
		$table.="
				<div class='card'>
					<div class='card-body'>
						<div class='row'>
							<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12' style='text-align:center;'>
								<img src='".$Company_Logo."' width='100%' style='width:100px;height:100px;' class='img-sm mb-3 '><br>
								<p>".$retVal5."<br><a id='ainfo' onclick='ViewInfo(".$id.")' class='text-info pointer-cursor'><i class='ti-info'></i></a></p>
							</div>
							<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
								
								 
								 <div class='row'> 
									<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12'>
										<strong>".$CompanyName." ".$img."</strong>
										<p><i class='ti-wallet'></i> Total Amount : <span>&#8377;</span> ".number_format(round($final_total_amout,0),2)." | <i class='ti-package'></i> Total Quantity : ".number_format($total_quantity,2)."</p>
									</div>
									
								 </div>
								 <hr>
								 <div class='row'>
									<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
										 <div class='btn-group'>
											<a href='".$hrefEway."' target='_blank'>
											<button type='button' class='btn btn-light tw-doc-button' ".$disabledEway." ><i class='ti-download'></i></button></a>
											<button type='button' class='btn btn-info tw-doc-button' ".$disabledEway.">Eway(".$retValii.")</button>
										  </div>
									</div>
									<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
										 <div class='btn-group'>
											<a href='".$hrefInvoice."' target='_blank'>
											<button type='button' class='btn btn-light tw-doc-button' ".$disabledInvoice."><i  class='ti-download'></i></button></a>
											<button type='button' class='btn btn-info tw-doc-button' ".$disabledInvoice.">Invoice(".$retVali.")</button>
										  </div> 
									</div>
									<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
										 <div class='btn-group'>
											<a href='".$hrefWBS."' target='_blank'>
											<button type='button' class='btn btn-light tw-doc-button' ".$disabledWBS."><i  class='ti-download'></i></button></a>
											<button type='button' class='btn btn-info tw-doc-button' ".$disabledWBS.">WBS(".$retValiii.")</button>
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
								 <div class='row'>";
								 
								 //--Karuna start Photos
								$queryp = "select id,document from tw_material_outward_documents where outward_id = '".$id."' and type='Photo' Order by id Desc";
								$retValp = $sign->FunctionJSON($queryp);
								
								$qry1p="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$id."' and type='Photo' Order by id Desc";
								$retVal1p = $sign->Select($qry1p);
								$decodedJSON2 = json_decode($retValp);
								$count = 0;
								$i = 1;
								$x=$retVal1p;
								$it=1;
								while($x>=$i){

									$modid = $decodedJSON2->response[$count]->id;
									$count=$count+1;
									$document = $decodedJSON2->response[$count]->document;
									$count=$count+1;
									
									$table.="
												<div class='col-lg-2 col-md-2 col-sm-4 col-xs-4 col-4'>
													 <div class='btn-group'>
														<a href='../assets/images/Documents/Employee/Outward/".$document."' target='_blank'>
														<button type='button' class='btn btn-light tw-doc-button'><i  class='ti-download'></i></button></a>
														<button type='button' class='btn btn-info tw-doc-button'>Photo ".$i."</button>
													  </div>
												</div>
											";
											$i=$i+1;
								}
								//--Karuna end Photos
								
							 $table.="</div>
							 <br><code>".$reason."</code>
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