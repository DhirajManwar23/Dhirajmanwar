<?php
session_start();
	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$employee_id = $_SESSION["employee_id"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueOngoingStatus= $commonfunction->getSettingValue("Ongoing Status");

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");

$qryGetAuditorPO = "SELECT pi.id,pi.po_number,pi.company_id,pi.status,pi.date_of_po,pi.total_quantity,pi.final_total_amount,pi.reason FROM tw_auditor_po_details apd INNER JOIN tw_po_info pi ON pi.id=apd.po_id where apd.auditor_id ='".$employee_id."'";
$retqryGetAuditorPO = $sign->FunctionJSON($qryGetAuditorPO);
$decodedJSON = json_decode($retqryGetAuditorPO);

$qrycnt1 = "select count(*) as cnt from `tw_auditor_po_details` apd INNER JOIN tw_po_info pi ON pi.id=apd.po_id where auditor_id = '".$employee_id."'";
$retValcnt = $sign->Select($qrycnt1);
$count1 = 0;
$i1 = 1;
$x1=$retValcnt;
$table="";
$progressstatus="";
$TotalBalQty = 0.00;
$per = 0.00;
if($retValcnt==0 || $retValcnt==0.00){
		$table.="
					<div class='card'>
					  
						<div class='card-body text-center'>
								<img src='".$settingValueUserImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
							</div>
						</div>
						
					  </div><br>";	
		
	}
	else{
	while($x1>=$i1){
	  
    $po_id = $decodedJSON->response[$count1]->id;
	$count1=$count1+1;
	$po_number = $decodedJSON->response[$count1]->po_number;
	$count1=$count1+1;
	$buyer_id = $decodedJSON->response[$count1]->company_id;
	$count1=$count1+1;
	$status = $decodedJSON->response[$count1]->status;
	$count1=$count1+1;
	$date_of_po = $decodedJSON->response[$count1]->date_of_po;
	$count1=$count1+1;
	$total_quantity  = $decodedJSON->response[$count1]->total_quantity ;
	$count1=$count1+1;
	$final_total_amount  = $decodedJSON->response[$count1]->final_total_amount ;
	$count1=$count1+1;
	$reasonR  = $decodedJSON->response[$count1]->reason ;
	$count1=$count1+1;
	//---Start
	$qry="select id,po_number,company_id,status,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where id='".$po_id."'  order by id asc";
	$qry1="select count(*) as cnt from tw_po_info where id='".$po_id."'";

	$retVal = $sign->FunctionJSON($qry);
	$qryCnt = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;

	//$valper = 0.00;
	$i = 1;
	$x=$qryCnt;
	

			// $id = $decodedJSON2->response[$count1]->id;
			// $count1=$count1+1;
			
			
			$qry3 = "select reason from tw_rejected_reason_master where id = '".$reasonR."'";
			$reason = $sign->SelectF($qry3,'reason');
			
			$qry4="SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as total_quantity FROM tw_temp WHERE po_id='".$po_id."' and status='".$settingValueCompletedStatus."'";
			$retVal4 = $sign->SelectF($qry4,"total_quantity");
			
			$TotalBalQty = $total_quantity-$retVal4;
			$per = round(($retVal4/$total_quantity)*100,2);
			$valper=$per;
			
			$qry2="Select CompanyName,Status,Company_Logo from tw_company_details where ID='".$buyer_id."'";
			$retVal1 = $sign->FunctionJSON($qry2);
			$decodedJSON1 = json_decode($retVal1);
			$CompanyName = $decodedJSON1->response[0]->CompanyName;
			$companyStatus = $decodedJSON1->response[1]->Status;
			$Company_Logo = $decodedJSON1->response[2]->Company_Logo;
			
			$qry4="Select value from tw_company_contact where company_id='".$buyer_id."'";
			$retVal3 = $sign->SelectF($qry4,"value");
			$verifyStatus=$commonfunction->getSettingValue("Verified Status");
			$VerifiedImage=$commonfunction->getSettingValue("Verified Image");

			
			$qry5="Select verification_status from tw_verification_status_master where id='".$status."'";
			$retVal5 = $sign->SelectF($qry5,"verification_status");
		
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
			$div="";
			$div1stline="";
			$progressdiv="";
			//---Progressbar
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
			//---Progressbar
			
			$progressdiv = "<div class='template-demo'>
									<div class='d-flex justify-content-between mt-2'>
									  <small>Quantity Fulfilled : ".number_format($retVal4,2)."</small>
									  <small>".$per."%</small>
									</div>
								 <div class='progress progress-sm mt-2'>
									  <div class='".$progressstatus."' role='progressbar' style='width: ".$per."%' aria-valuenow='per' aria-valuemin='".$per."' aria-valuemax='100'></div>
								  </div><br>
							</div>";
							
			
				
			
				$qryAwaiting="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValueAwaitingStatus."' and auditor_id='".$employee_id."'";
				$qryCntAwaiting = $sign->Select($qryAwaiting);
			
				if($qryCntAwaiting!=0){
					$ViewFullfilmentAcceptRecords="<a href='javascript:void(0)' onclick='ViewFullfilmentAcceptRecords(".$po_id.")' title='View Upload'><i class='ti-eye'></i> View Fulfilled</a>";
				}
				else{
					$ViewFullfilmentAcceptRecords="";
				}
				$qryCompleted="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValueCompletedStatus."' and auditor_id='".$employee_id."'";
				$qryCntCompleted = $sign->Select($qryCompleted);
			
				if($qryCntCompleted!=0){
					$ViewFullfilmentCompletedRecords="<a href='javascript:void(0)' onclick='ViewFullfilmentCompletedRecords(".$po_id.")' title='View Upload'><i class='ti-eye'></i> View Completed</a>
					</i><a href='javascript:void(0)' onclick='AuditorCertificate(".$po_id.")' title='View Fulfilled'><i class='ti-eye'></i> View Auditor Certificate</a>";
				}
				else{
					$ViewFullfilmentCompletedRecords="";
				}
				
				$qryOngoing="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValueOngoingStatus."'  ";
				$qryCntOngoing = $sign->Select($qryOngoing);
				
				if($qryCntOngoing!=0){
					$ViewFullfilmentRecords = "<a href='javascript:void(0)' onclick='ViewFullfilmentRecords(".$po_id.")' title='View'><i class='ti-eye'></i> View Ongoing</a>";
				}
				else{
					$ViewFullfilmentRecords="";
				}
				
				$qryRejected="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValueRejectedStatus."' and auditor_id='".$employee_id."'";
				$qryCntRejected = $sign->Select($qryRejected);
				if($qryCntRejected!=0){
					$ViewFullfilmentRejectedRecords="<a href='javascript:void(0)' onclick='ViewFullfilmentRejectedRecords(".$po_id.")' title='View Queries'><i class='ti-eye'></i> View Queries</a>";
				}
				else{
					$ViewFullfilmentRejectedRecords="";
				}
				
				
				$divatag = "".$ViewFullfilmentRecords." ".$ViewFullfilmentAcceptRecords." ".$ViewFullfilmentRejectedRecords." ".$ViewFullfilmentCompletedRecords." ";	
			
			
			$qrystate = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$po_id."')";
			$retqrystate = $sign->SelectF($qrystate,"state");
			
			$table.="<div class='card'>
					  
						<div class='card-body'>
							<div class='row'>
								<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12' style='text-align:center;'>
									<img src='".$Company_Logo."' width='100%' style='width:100px;height:100px;' class='img-sm mb-3 '><br><a href='javascript:void(0)' onclick='ViewRecord(".$po_id.")' title='View PO'><i class='ti-printer'></i> View</a>
								</div>
								<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
									<strong>".$CompanyName." [".$retqrystate."]".$img."</strong>
									".$divatag."
									<p>PO No : ".$po_number." | Status: ".$retVal5."</p>
									 <p><i class='ti-calendar'></i> PO Date: ".date("d-m-Y", strtotime($date_of_po))."</p>
									 <p><i class='ti-wallet'></i> Total Amount : <span>&#8377;</span> ".number_format(round($final_total_amount,0),2)." | <i class='ti-package'></i> Total Quantity : ".number_format($total_quantity,2)."</p>
									 ".$progressdiv."
									".$div."
									 
								</div>
							</div>
							  
							 
						</div>
						
						
					  </div><br>";		
				

		  // $i=$i+1;
		// }

	//}
	//--End
	
		$i1=$i1+1;
	}
 
}
/* $company_id = $_SESSION["company_id"];
$type = $_POST["type"];

 */
echo $table;

	
?>
