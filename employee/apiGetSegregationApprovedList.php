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
$employee_id = $_SESSION["employee_id"];
$company_id = $_SESSION["company_id"];
$statustype = $_POST["statustype"];
if($statustype==$settingValueApprovedStatus){
	$qry="select id,total_quantity,actual_quantity,status,created_on from tw_mix_waste_lot_info where company_id='".$company_id."' and status='".$settingValuePendingStatus."' and employee_id='".$employee_id."' order by id asc";
	$qry1="select count(*) as cnt from tw_mix_waste_lot_info where company_id='".$company_id."' and status='".$settingValuePendingStatus."' and employee_id='".$employee_id."'";
}
else{
	$qry="select id,total_quantity,actual_quantity,status,created_on from tw_mix_waste_lot_info where company_id='".$company_id."' and status='".$settingValueCompletedStatus."' and employee_id='".$employee_id."' order by id asc";
	$qry1="select count(*) as cnt from tw_mix_waste_lot_info where company_id='".$company_id."' and status='".$settingValueCompletedStatus."' and employee_id='".$employee_id."'";
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
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$total_quantity = $decodedJSON2->response[$count]->total_quantity;
		$count=$count+1;
		$actual_quantity = $decodedJSON2->response[$count]->actual_quantity;
		$count=$count+1;
		$status = $decodedJSON2->response[$count]->status;
		$count=$count+1;
		$created_on = $decodedJSON2->response[$count]->created_on;
		$count=$count+1;
		
		$qry5="Select verification_status from tw_verification_status_master where id='".$status."'";
		$verification_status = $sign->SelectF($qry5,"verification_status");
		if($statustype==$settingValueApprovedStatus){
			$dropdown="<div class='row'>
							
								<div class='col-12 col-xl-12'>
								 <div class='justify-content-end d-flex'>
								  <div class='dropdown flex-md-grow-1 flex-xl-grow-0'>
									<button class='btn btn-sm btn-light bg-white dropdown-toggle' type='button' id='dropdownMenuDate2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
									 </button>
									<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
									  <a class='dropdown-item' href='#' onclick='addRecord(".$id.")'><i class='ti-plus'></i> Segregate</a>  <a class='dropdown-item' href='#' onclick='viewDetails(".$id.")'><i class='ti-info'></i> Details</a>
									
								 </div>
								</div>
							  </div>
							</div>
						  </div>";
			$ViewComment="";
			$ViewSegComment="";
		}
		else{
			$dropdown="<div class='row'>
							
								<div class='col-12 col-xl-12'>
								 <div class='justify-content-end d-flex'>
								  <div class='dropdown flex-md-grow-1 flex-xl-grow-0'>
									<button class='btn btn-sm btn-light bg-white dropdown-toggle' type='button' id='dropdownMenuDate2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
									 </button>
									<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
									<a class='dropdown-item' href='#' onclick='viewDetails(".$id.")'><i class='ti-info'></i> Details</a>
									<a class='dropdown-item' href='#' onclick='viewSeggregationDetails(".$id.")'><i class='ti-info'></i> Segregation Details</a>
									
								 </div>
								</div>
							  </div>
							</div>
						  </div>";
			$ViewSegComment="| <a class='text-black' href='#' onclick='viewSeggregationComment(".$id.")'><i class='ti-info-alt'></i> View Comment</a>";
		}
				
		$table.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
				  <div class='bg-white p-3'>
						
						<div class='row bg-primary p-3'>
							<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10'>
								<h3 class='tw-heading text-white left-text'><a class='text-white' href='#' onclick='viewComment(".$id.")'><i class='ti-info-alt'></i></a> Lot".$id."</h3>
							</div>
							<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2'>
								".$dropdown."
							</div>
						</div>
						<div class='row bg-light'>
							<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12'>
								<br><p class='left-text'>Status: ".$verification_status." ".$ViewSegComment."</p>
								 <p class='left-text'><i class='ti-calendar'></i> Collection Date: ".date("d-m-Y", strtotime($created_on))." 
								 <br><i class='ti-package'></i> Received Quantity : ".number_format($total_quantity,2)." | <i class='ti-package'></i> Actual Quantity : ".number_format($actual_quantity,2)."</p>
							</div>
						</div>
						 
					</div>
					
				  </div><br>";

		$i=$i+1;
	}

}
echo $table;

	
?>
