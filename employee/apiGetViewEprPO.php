<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValuePendingStatus=$sign->escapeString($settingValuePendingStatus);
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$ComplianceImage=$commonfunction->getCommonDataValue("Compliance Image");


$company_id = $_SESSION["company_id"];
$statustype = $_POST["statustype"];
date_default_timezone_set("Asia/Kolkata");
$cur_date=(date('Y-m-d'));

if($statustype==""){
	$qry="select id,po_number,status,company_id,supplier_id,date_of_po,total_quantity,final_total_amount,reason from  tw_po_info where company_id='".$company_id."' order by id Desc";
	$qry1="select count(*) as cnt from tw_po_info where company_id='".$company_id."'";
}
else if($statustype=="Queries"){
	$qry="SELECT DISTINCT(pi.id),pi.po_number,pi.status,pi.company_id,pi.supplier_id,pi.date_of_po,pi.total_quantity,pi.final_total_amount,pi.reason FROM tw_po_info pi INNER JOIN tw_temp tt ON tt.po_id=pi.id WHERE pi.company_id='".$company_id."' and tt.status='".$settingValueRejectedStatus."' order by pi.id Desc";
	$qry1="select count(DISTINCT(pi.id)) as cnt FROM tw_po_info pi INNER JOIN tw_temp tt ON tt.po_id=pi.id WHERE pi.company_id='".$company_id."' and tt.status='".$settingValueRejectedStatus."'";
}
else{
	$qry="select id,po_number,status,company_id,supplier_id,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where company_id='".$company_id."' and status='".$statustype."' order by id Desc";
	$qry1="select count(*) as cnt from tw_po_info where company_id='".$company_id."' and status='".$statustype."'";
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
		$qry2="Select CompanyName,Status,Company_Logo,compliance_status from tw_company_details where ID='".$buyer_id."'";
		$retVal1 = $sign->FunctionJSON($qry2);
		$decodedJSON = json_decode($retVal1);
		$CompanyName = $decodedJSON->response[0]->CompanyName;
		$companyStatus = $decodedJSON->response[1]->Status;
		$Company_Logo = $decodedJSON->response[2]->Company_Logo;
		$compliance_status = $decodedJSON->response[3]->compliance_status;

		$qry3="Select value from tw_company_contact where company_id='".$buyer_id."'";
		$retVal3 = $sign->SelectF($qry3,"value");
		$verifyStatus=$commonfunction->getSettingValue("Verified Status");
		$VerifiedImage=$commonfunction->getSettingValue("Verified Image");

		$qry4="SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as total_quantity FROM tw_temp WHERE po_id='".$id."' and status='".$settingValueCompletedStatus."'";
		$retVal4 = $sign->SelectF($qry4,"total_quantity");
		 $Reasonqry="SELECT reason FROM `tw_rejected_reason_master` where id='".$reason."'";
		$FetchReason = $sign->SelectF($Reasonqry,"reason");
		$qry5="Select verification_status from tw_verification_status_master where id='".$status."'";
		$retVal5 = $sign->SelectF($qry5,"verification_status");
		
		$delivery_dateqry="SELECT delivery_date FROM tw_po_details where po_id='".$id."'";
		$delivery_date = $sign->SelectF($delivery_dateqry,"delivery_date");
		//------------Date Diff starts-----------------//
		 $difference = strtotime($delivery_date)-strtotime($cur_date);
		//-------------Date Diff Ends----------//
		$docView="";
		
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
		$complianceimg="";
		if ($compliance_status==$verifyStatus) { 
			$complianceimg = "<img class='verified-img' src='".$settingValueUserImagePathOther."".$ComplianceImage."'/>";
		}
		else{
			$complianceimg="";
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
		//--------------------------- Inprocess Count Starts ---------------------------------//
		$InprocessCnt="";
		$qryCntInprocess="select count(*) as cnt from tw_temp where po_id='".$id."' and status='".$settingValueAwaitingStatus."'";
		$qryCntInprocess = $sign->Select($qryCntInprocess);
		
		if($qryCntInprocess!=0){
			$InprocessCnt="<a href='#' class='text-success' onclick='ViewInprocess(".$id.")'>   (You Have ".$qryCntInprocess." records for approval)</a>";
		}
		
		//--------------------------- Inprocess Count Ends ---------------------------------//
		if($status==$settingValuePendingStatus){
			$docView="";
			
			$div = "";
			$progressdiv = "";
			$dropdown = "<div class='row'>
						
							<div class='col-12 col-xl-12'>
							 <div class='justify-content-end d-flex'>
							  <div class='dropdown flex-md-grow-1 flex-xl-grow-0'>
								<button class='btn btn-sm btn-light bg-white dropdown-toggle' type='button' id='dropdownMenuDate2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
								 </button>
								<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
								  <a class='dropdown-item' href='#' onclick='editRecord(".$id.",".$buyer_id.")'><i class='ti-pencil'></i></i> Edit</a>
								
							 </div>
							</div>
						  </div>
						</div>
					  </div>";
					  	
			
		}
		else if($status==$settingValueRejectedStatus){
			$docView="";
			$div = "<div class='btn-group' role='group'>
						<code>".$FetchReason."</code>
					</div>";
			$progressdiv = "";
			$dropdown = "";
			
		}
		else if($status==$settingValueCompletedStatus){
			$div = "";
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
								  <a class='dropdown-item' href='#' onclick='ViewRejected(".$id.")'><i class='ti-trash'></i> Rejected</a>
								
							 </div>
							</div>
						  </div>
						</div>
					  </div>";
				$docView=" <i class='ti-printer'></i><a href='javascript:void(0)' onclick='ViewFulfilled(".$id.")' title='View Fulfilled'> View Fulfilled</a>
				";
		}
		else{
			$buttondisabled = "";
			if($status==$settingValueVerifiedStatus){
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
								  <a class='dropdown-item' href='#' onclick='ViewInprocess(".$id.")'><i class='ti-time'></i> Inprogress</a>
								  <a class='dropdown-item' href='#' onclick='ViewApproved(".$id.")'><i class='ti-check-box'></i> Approved</a>
								  <a class='dropdown-item' href='#' onclick='ViewRejected(".$id.")'><i class='ti-trash'></i> Rejected</a>
								
							 </div>
							</div>
						  </div>
						</div>
					  </div>";
		}
		//--
		if($statustype=="Queries"){
			$dropdown="<a href='pgEprMaterialInward.php?type=Rejected&po_id=".$id."&req=' title='View' class='float-right'><i class='ti-arrow-right'></i></a>";
		}
		$qrystate = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$id."')";
		$retqrystate = $sign->selectF($qrystate,"state");

		$table.="<div class='card'>
					<div class='card-body'>
					".$dropdown."
						<div class='row'>
							<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12' style='text-align:center;'>
								<img src='".$Company_Logo."' width='100%' style='width:100px;height:100px;' class='img-sm mb-3 '><br><a href='javascript:void(0)' onclick='ViewRecord(".$id.",".$buyer_id.")' title='View PO'><i class='ti-printer'></i> View PO</a>
							</div>
							<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
								<strong>".$CompanyName." ".$img."".$complianceimg." [".$retqrystate."]</strong><p>PO No : ".$po_number." | Status: ".$retVal5." ".$InprocessCnt."</p>
								 <p><i class='ti-calendar'></i> PO Date: ".date("d-m-Y", strtotime($po_date))." | <i class='ti-calendar'></i> Delivery Date: ".date("d-m-Y", strtotime($delivery_date))." | Pending Days: Difference is: ".$difference/(24*60*60)." days</p>
								 <p><i class='ti-wallet'></i> Total Amount : <span>&#8377;</span> ".number_format($final_total_amount,2)." | <i class='ti-package'></i> Total Quantity : ".number_format($total_quantity,2)."</p>
								
								 ".$docView."
								 
								".$progressdiv."
								".$div."
								 
							</div>
						</div>
						  
						 
					</div>
					
					
				  </div><br>";	
				  
			

		$i=$i+1;	}
}


 echo  $table;

	
?>
