<?php
session_start();
	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$statustype ="";
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");

$company_id = $_SESSION["company_id"];
$statustype = $_POST["statustype"];

if($statustype==""){
	 $qry="select id,po_number,status,company_id,supplier_id,date_of_po,total_quantity,final_total_amount,reason from  tw_po_info where supplier_id='".$company_id."' order by id asc";
	$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."'";
}
else{
	$qry="select id,po_number,status,company_id,supplier_id,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where supplier_id='".$company_id."' and status='".$statustype."' order by id asc";
	$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status='".$statustype."'";
}
$retVal = $sign->FunctionJSON($qry);
$qryCnt = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$TotalBalQty = 0.00;
$per = 0.00;
//$valper = 0.00;
$i = 1;
$x=$qryCnt;
$progressstatus="";
$table="";

if($qryCnt==0 || $qryCnt==0.00){
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
		//$valper = 0.00;
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$po_number = $decodedJSON2->response[$count]->po_number;
		$count=$count+1;
		$status = $decodedJSON2->response[$count]->status;
		$count=$count+1;
		$company_id = $decodedJSON2->response[$count]->company_id;
		$count=$count+1;
		$buyer_id = $decodedJSON2->response[$count]->supplier_id;
		$count=$count+1;
		$po_date = $decodedJSON2->response[$count]->date_of_po;
		$count=$count+1;
		$total_quantity  = $decodedJSON2->response[$count]->total_quantity ;
		$count=$count+1;
		$final_total_amount  = $decodedJSON2->response[$count]->final_total_amount ;
		$count=$count+1;
		$reason  = $decodedJSON2->response[$count]->reason ;
		$count=$count+1;
		
		$qry2="Select CompanyName,Status,Company_Logo from tw_company_details where ID='".$company_id."'";
		$retVal1 = $sign->FunctionJSON($qry2);
		$decodedJSON = json_decode($retVal1);
		$CompanyName = $decodedJSON->response[0]->CompanyName;
		$companyStatus = $decodedJSON->response[1]->Status;
		$Company_Logo = $decodedJSON->response[2]->Company_Logo;
		
		$qry3="Select value from tw_company_contact where company_id='".$company_id."'";
		$retVal3 = $sign->SelectF($qry3,"value");
		$verifyStatus=$commonfunction->getSettingValue("Verified Status");
		$VerifiedImage=$commonfunction->getSettingValue("Verified Image");

		$qry4="SELECT SUM(total_quantity) FROM  tw_epr_material_assign_info WHERE po_id='".$id."' and (status='".$settingValueApprovedStatus."'or status='".$settingValueCompletedStatus."'  )";
		$retVal4 = $sign->SelectF($qry4,"SUM(total_quantity)");
		
		$Reasonqry="SELECT reason FROM `tw_rejected_reason_master` where id='".$reason."'";
		$FetchReason = $sign->SelectF($Reasonqry,"reason");
		if($retVal4==""){
			$retVal4=0;
		}
		 
		$qry5="Select verification_status from tw_verification_status_master where id='".$status."'";
		$retVal5 = $sign->SelectF($qry5,"verification_status");
		
		$delivery_dateqry="SELECT delivery_date FROM `tw_po_details` where po_id='".$id."'";
		$delivery_date = $sign->SelectF($delivery_dateqry,"delivery_date");
		
			$docView="";
	if($statustype==$settingValueCompletedStatus){
			
		
			$docView=" <div class='row'>
			<div class='col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6'>
										 <div class='btn-group'>
											<button type='button' class='btn btn-light tw-doc-button><i  class='ti-download'><a href='javascript:void(0)' onclick='ViewEprCerticate(".$id.",".$buyer_id.")' title='View PO'><i class='ti-printer'></i> View Epr Certificate</a>
								  <br>
											</i></button></a>
											
										  </div> 
									</div>
									
					<div class='col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6'>
					 <div class='btn-group'>
					 <button type='button' class='btn btn-light tw-doc-button><i  class='ti-download'>
						<a href='javascript:void(0)' onclick='ViewRecyclingCerticate(".$id.",".$buyer_id.")' title='View PO'><i class='ti-printer'></i> View Epr Recycling Certicate</a>
						<br>
						</i></button></a>
						
					  </div> 
					</div>
					
					<div class='col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6'>
					 <div class='btn-group'>
					 <button type='button' class='btn btn-light tw-doc-button><i  class='ti-download'>
						<a href='javascript:void(0)' onclick='ViewTabulatedDetails(".$id.",".$buyer_id.")' title='View PO'><i class='ti-printer'></i> View TabulatedDetails</a>
						<br>
						</i></button></a>
						
					  </div> 
					</div>
					<div class='col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6'>
					 <div class='btn-group'>
					 <button type='button' class='btn btn-light tw-doc-button> <i  class='ti-download'>
						<a href='pgViewSummarySheet.php?po_id=".$id."&supid=".$buyer_id."' title='View PO' target='_blank'><i class='ti-printer'></i> Summary Sheet</a>
						<a href='javascript:void(0)' onclick='AuditorCertificate(".$id.")' title='View Fulfilled'> View Auditor Certificate</a>
						<br>
						</i></button></a>
						
					  </div> 
					</div>
				</div>";
							
		  }
		
		
		
		 $TotalBalQty = $total_quantity-$retVal4;
		 $per = round(($retVal4/$total_quantity)*100,2);
		 $valper=$per;
		if(empty($Company_Logo)){
			$Company_Logo=$settingValueUserImagePathOther.$settingValueCompanyImage;
		} 
		else{
			$Company_Logo=$settingValueUserImagePathVerification.$retVal3."/".$Company_Logo;
		}
		$img="";
		if ($companyStatus==$verifyStatus) { 
			$img = "<img src='".$settingValueUserImagePathOther."".$VerifiedImage."'/>";
		}
		else{
			$img="";
		}
					
		if($per>=0 && $per<=24.99){	
		
			$progressstatus = "progress-bar bg-danger";
		}
		else if($per>=25 && $per<=49.99){
			$progressstatus = "progress-bar bg-warning";
		}
		else if($per>=50 && $per<=99.99){
			
			$progressstatus = "progress-bar bg-primary";
		}
		else if($per>=100){
			$per=100.00;
			$progressstatus = "progress-bar bg-success";
		}
		else{
				$per=0.00;
				$progressstatus = "progress-bar bg-danger";

		}
		if($status==$settingValuePendingStatus){
			$div = "<div class='btn-group' role='group'>
			
						<a href='javascript:void(0)' id='alinkaccept' onclick='AcceptRecord(".$id.",".$status.")'><img src='".$settingValueUserImagePathOther."".$settingValueAcceptImage."' id='imgaccept' class='img-sm rounded-circle mb-3 '/><a>
						<a href='javascript:void(0)' id='alinkreject' onclick='RejectRecord(".$id.")'><img src='".$settingValueUserImagePathOther."".$settingValueRejectImage."' id='imgreject' class='img-sm rounded-circle mb-3 '/><a>
					</div>";
			$progressdiv = "";
			$dropdown = "";
		}
		else if($status==$settingValueRejectedStatus){
			$div = "<div class='btn-group' role='group'>
						<code>".$FetchReason ."</code>
					</div>";
			$progressdiv = "";
			$dropdown = "";
		}
		else if($status==$settingValueCompletedStatus){
			$div = "";
			$progressdiv = "";$progressdiv = "<div class='template-demo'>
								<div class='d-flex justify-content-between mt-2'>
								  <small>Quantity Fulfilled : ".number_format($retVal4,2)."</small>
								  <small>".$per."%</small>
								</div>
							 <div class='progress progress-sm mt-2'>
								  <div class='".$progressstatus."' role='progressbar' style='width: ".$per."%' aria-valuenow='per' aria-valuemin='".$per."' aria-valuemax='100'></div>
							  </div><br>
						
						</div>";
			$dropdown = "<div class='row'>
						
							<div class='col-12 col-xl-12'>
							 <div class='justify-content-end d-flex'>
							  <div class='dropdown flex-md-grow-1 flex-xl-grow-0'>
								<button class='btn btn-sm btn-light bg-white dropdown-toggle' type='button' id='dropdownMenuDate2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
								</button>
								<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
								 
								   <a class='dropdown-item' href='#' onclick='ViewApproved(".$id.")'><i class='ti-check-box'></i> Approved</a>
								   <a class='dropdown-item' href='#' onclick='ViewRejected(".$id.")'><i class='ti-trash'></i> Rejected</a>
								
							 </div>
							</div>
						  </div>
						</div>
					  </div> ";
		}
		else{
			$buttondisabled = "";
			if($per==100.00 || $per==100){
				$buttondisabled = "disabled";
			}
			else{
				$buttondisabled = "";
			} 
			
			
			$div="";
					
			$progressdiv = "<div class='template-demo'>
								<div class='d-flex justify-content-between mt-2'>
								  <small>Quantity Fulfilled : ".number_format($retVal4,2)."</small>
								  <small>".$per."%</small>
								</div>
							 <div class='progress progress-sm mt-2'>
								  <div class='".$progressstatus."' role='progressbar' style='width: ".$per."%' aria-valuenow='per' aria-valuemin='".$per."' aria-valuemax='100'></div>
							  </div><br>
						</div>";
			$dropdown = "<div class='row'>
						
							<div class='col-12 col-xl-12'>
							 <div class='justify-content-end d-flex'>
							  <div class='dropdown flex-md-grow-1 flex-xl-grow-0'>
								<button class='btn btn-sm btn-light bg-white dropdown-toggle' type='button' id='dropdownMenuDate2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
								 </button>
								<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
								
								  <a class='dropdown-item' href='#' onclick='ViewApproved(".$id.")'><i class='ti-check-box'></i> Approved</a>
								  <a class='dropdown-item' href='#' onclick='ViewInprocess(".$id.")'><i class='ti-time'></i> Inprogress</a>
								  <a class='dropdown-item' href='#' onclick='ViewRejected(".$id.")'><i class='ti-trash'></i> Rejected</a>
								  <a class='dropdown-item' href='#' onclick='assignproduct(".$id.",".$company_id.",".$buyer_id.")'><i class='ti-agenda '></i> Assign</a>
								
							 </div>
							</div>
						  </div>
						</div>
					  </div>";
		}

		
		
		$table.="<div class='card'>
				  
					<div class='card-body'>
					
					".$dropdown."
						<div class='row'>
							<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12' style='text-align:center;'>
								<img src='".$Company_Logo."' width='100%' style='width:100px;height:100px;' class='img-sm mb-3 '><br><a href='javascript:void(0)' onclick='ViewRecord(".$id.",".$buyer_id.")' title='View PO'><i class='ti-printer'></i> View</a>
							</div>
							<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
								<strong>".$CompanyName." ".$img."</strong><p>PO No : ".$po_number." | Status: ".$retVal5."</p>
								 <p><i class='ti-calendar'></i> PO Date: ".date("d-m-Y", strtotime($po_date))." | <i class='ti-calendar'></i> Delivery Date: ".date("d-m-Y", strtotime($delivery_date))."</p>
								 <p><i class='ti-wallet'></i> Total Amount : <span>&#8377;</span> ".number_format($final_total_amount,2)." | <i class='ti-package'></i> Total Quantity : ".number_format($total_quantity,2)."</p>
								
								 ".$docView."
								 
								".$progressdiv."
								".$div."
								 
							</div>
						</div>
						  
						 
					</div>
					
					
				  </div><br>";		
			

		$i=$i+1;
	}

}
echo $table;

	
?>
