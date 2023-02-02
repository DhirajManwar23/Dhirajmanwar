<?php
session_start();
	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");

$company_id = $_SESSION["company_id"];
$type = $_POST["type"];
$POID = $_POST["POID"];

if($type=="Pending"){
	$qry="select id,po_number,company_id,status,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValuePendingStatus."' order by id desc";
	$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValuePendingStatus."'";
}
else if($type=="Rejected"){
	$qry="select id,po_number,company_id,status,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValueRejectedStatus."' order by id desc";
	$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValueRejectedStatus."'";
}
else if($type=="Approved"){
	$qry="select id,po_number,company_id,status,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValueApprovedStatus."' order by id desc";
	$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValueApprovedStatus."'";
}
else if($type=="Queries"){
	$qry="SELECT pi.id,pi.po_number,pi.company_id,pi.status,pi.date_of_po,pi.total_quantity,pi.final_total_amount,pi.reason FROM tw_po_info pi INNER JOIN tw_temp tt ON tt.po_id=pi.id WHERE pi.supplier_id='".$company_id."' and tt.status='".$settingValueRejectedStatus."'";
	$qry1="select count(*) as cnt FROM tw_po_info pi INNER JOIN tw_temp tt ON tt.po_id=pi.id WHERE pi.supplier_id='".$company_id."' and tt.status='".$settingValueRejectedStatus."'";
}
else if($type=="Awaiting"){
	$qry="select id,po_number,company_id,status,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValueApprovedStatus."' order by id desc";
	$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValueApprovedStatus."'";
}
else if($type=="Completed"){
	$qry="select id,po_number,company_id,status,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValueCompletedStatus."' order by id desc";
	$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status = '".$settingValueCompletedStatus."'";
}
else{
	$qry="select id,po_number,company_id,status,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where supplier_id='".$company_id."' order by id desc";
	$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."'";
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
							<img src='".$settingValueUserImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
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
		$buyer_id = $decodedJSON2->response[$count]->company_id;
		$count=$count+1;
		$status = $decodedJSON2->response[$count]->status;
		$count=$count+1;
		$date_of_po = $decodedJSON2->response[$count]->date_of_po;
		$count=$count+1;
		$total_quantity  = $decodedJSON2->response[$count]->total_quantity ;
		$count=$count+1;
		$final_total_amount  = $decodedJSON2->response[$count]->final_total_amount ;
		$count=$count+1;
		$reasonR  = $decodedJSON2->response[$count]->reason ;
		$count=$count+1;
		
		$qry3 = "select reason from tw_rejected_reason_master where id = '".$reasonR."'";
		$reason = $sign->SelectF($qry3,'reason');
		
		$qry4="SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as total_quantity FROM tw_temp WHERE po_id='".$id."' and status='".$settingValueCompletedStatus."'";
		$retVal4 = $sign->SelectF($qry4,"total_quantity");
		
		$TotalBalQty = $total_quantity-$retVal4;
		$per = round(($retVal4/$total_quantity)*100,2);
		$valper=$per;
		
		$qry2="Select CompanyName,Status,Company_Logo from tw_company_details where ID='".$buyer_id."'";
		$retVal1 = $sign->FunctionJSON($qry2);
		$decodedJSON = json_decode($retVal1);
		$CompanyName = $decodedJSON->response[0]->CompanyName;
		$companyStatus = $decodedJSON->response[1]->Status;
		$Company_Logo = $decodedJSON->response[2]->Company_Logo;
		
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
		if($status==$settingValuePendingStatus){
			$div = "<div class='btn-group' role='group'>
						<a href='javascript:void(0)' id='alinkaccept' onclick='AcceptRecord(".$id.")'><img src='".$settingValueUserImagePathOther."".$settingValueAcceptImage."' id='imgaccept' class='img-sm rounded-circle mb-3 '/><a>
						<a href='javascript:void(0)' id='alinkreject' onclick='RejectRecord(".$id.")'><img src='".$settingValueUserImagePathOther."".$settingValueRejectImage."' id='imgreject' class='img-sm rounded-circle mb-3 '/><a>
					</div>";
		}
		else if($status==$settingValueRejectedStatus){
			$div = "<div class='btn-group' role='group'>
						<code>".$reason."</code>
					</div>";
		}
		else if($status==$settingValueApprovedStatus){
			$progressdiv = "<div class='template-demo'>
								<div class='d-flex justify-content-between mt-2'>
								  <small>Quantity Fulfilled : ".number_format($retVal4,2)."</small>
								  <small>".$per."%</small>
								</div>
							 <div class='progress progress-sm mt-2'>
								  <div class='".$progressstatus."' role='progressbar' style='width: ".$per."%' aria-valuenow='per' aria-valuemin='".$per."' aria-valuemax='100'></div>
							  </div><br>
						</div>";
						
		}
		
		if($type=="Approved"){
			
			$qryPending="select count(*) as cnt from tw_temp where po_id='".$id."' and status = '".$settingValuePendingStatus."'";
			$qryCntPending = $sign->Select($qryPending);

			$qryCompleted="select count(*) as cnt from tw_temp where po_id='".$id."' and status = '".$settingValueCompletedStatus."'";
			$qryCntCompleted = $sign->Select($qryCompleted);
			
			if($qryCntPending!=0){
				$ViewFullfilmentPendingRecords="<a href='javascript:void(0)' onclick='ViewFullfilmentPendingRecords(".$id.")' title='View Upload'><i class='ti-eye'></i> View Pending</a>";
			}
			else{
				$ViewFullfilmentPendingRecords="";
			}
			if($qryCntCompleted!=0){
				$ViewFullfilmentAcceptRecords="<a href='javascript:void(0)' onclick='ViewFullfilmentAcceptRecords(".$id.")' title='View Upload'><i class='ti-eye'></i> View Fulfilled</a>";
			}
			else{
				$ViewFullfilmentAcceptRecords="";
			}
			
			$qryAwaiting="select count(*) as cnt from tw_temp where po_id='".$id."' and status = '".$settingValueAwaitingStatus."'";
			$qryCntAwaiting = $sign->Select($qryAwaiting);
			
			if($qryCntAwaiting!=0){
				$ViewFullfilmentRecords = "<a href='javascript:void(0)' onclick='ViewFullfilmentRecords(".$id.")' title='View'><i class='ti-eye'></i> View Awaiting</a>";
			}
			else{
				$ViewFullfilmentRecords="";
			}
			
			
			$divatag = "<a href='javascript:void(0)' onclick='ViewFullfilment(".$id.")' title='Upload New Excel'><i class='ti-cloud-up'></i> Upload New Excel</a>
			".$ViewFullfilmentPendingRecords." ".$ViewFullfilmentRecords." ".$ViewFullfilmentAcceptRecords." 
			";
		}
		else if($type=="Queries"){
			$qryRejected="select count(*) as cnt from tw_temp where po_id='".$id."' and status = '".$settingValueRejectedStatus."'";
			$qryCntRejected = $sign->Select($qryRejected);
			if($qryCntRejected!=0){
				$ViewFullfilmentRejectedRecords="<a href='javascript:void(0)' onclick='ViewFullfilmentRejectedRecords(".$id.")' title='View Queries'><i class='ti-eye'></i> View Queries</a>";
			}
			else{
				$ViewFullfilmentRejectedRecords="";
			}
			
			$divatag = $ViewFullfilmentRejectedRecords;
		}
		else{
			$divatag="";
		}
		
		$qrystate = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$id."')";
		$retqrystate = $sign->SelectF($qrystate,"state");
		
		$table.="<div class='card'>
				  
					<div class='card-body'>
						<div class='row'>
							<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12' style='text-align:center;'>
								<img src='".$Company_Logo."' width='100%' style='width:100px;height:100px;' class='img-sm mb-3 '><br><a href='javascript:void(0)' onclick='ViewRecord(".$id.")' title='View PO'><i class='ti-printer'></i> View</a>
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
			

		$i=$i+1;
	}

}
echo $table;

	
?>
