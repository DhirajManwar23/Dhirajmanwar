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


$qry="select id as tableid,po_id as id,requester_company_id,recycler_company_id,total_quantity,reason,date,status from  tw_epr_material_assign_info where recycler_company_id='".$company_id."' AND status='".$settingValuePendingStatus."' order by id desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="select count(*) as cnt from tw_epr_material_assign_info where recycler_company_id='".$company_id."' AND status='".$settingValuePendingStatus."' order by id desc";$settingValuePendingStatus."'";
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
		//$valper = 0.00;
		$tableid = $decodedJSON2->response[$count]->tableid;
		$count=$count+1;
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$requester_company_id = $decodedJSON2->response[$count]->requester_company_id;
		$count=$count+1;
		$recycler_company_id = $decodedJSON2->response[$count]->recycler_company_id;
		$count=$count+1;
		$total_quantity  = $decodedJSON2->response[$count]->total_quantity ;
		$count=$count+1;
		$reason  = $decodedJSON2->response[$count]->reason ;
		$count=$count+1;
		$po_date = $decodedJSON2->response[$count]->date;
		$count=$count+1;
		$status  = $decodedJSON2->response[$count]->status ;
		$count=$count+1;
	
		$Cmp_idQry= "SELECT supplier_id FROM `tw_po_info` where id='".$id."'"; 
		$Cmp_id=$sign->SelectF($Cmp_idQry,"supplier_id");
		
		$qry2="Select CompanyName,Status,Company_Logo from tw_company_details where ID='".$Cmp_id."'";
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
		if($retVal4==""){
			$retVal4=0;
		}
		 
		$qry5="Select verification_status from tw_verification_status_master where id='".$status."'";
		$retVal5 = $sign->SelectF($qry5,"verification_status");
		
		$delivery_dateqry="SELECT delivery_date FROM `tw_po_details` where po_id='".$id."'";
		$delivery_date = $sign->SelectF($delivery_dateqry,"delivery_date");
		
		//------Karuna Start
		
		$qry3="Select value from tw_company_contact where company_id='".$recycler_company_id."'";
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
		if ($companyStatus==$verifyStatus) { 
			$img = "<img src='".$UserImagePathOther."".$VerifiedImage."'/>";
		}
		else{
			$img="";
		}
		//--karuna Start
	
		
	//Count  start
	
	//--Karuna ahref end WBS
	//--Karuna ahref start GRN


	//--Karuna ahref end QC
				$dropdown = "<div class='row'>
						
							<div class='col-12 col-xl-12'>
							 <div class='justify-content-end d-flex'>
							  <div class='dropdown flex-md-grow-1 flex-xl-grow-0'>
								<button class='btn btn-sm btn-light bg-white dropdown-toggle' type='button' id='dropdownMenuDate2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
								 </button>
								<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
								
								  <a class='dropdown-item' href='#' onclick='ViewInprocess(".$id.",".$tableid.")'><i class='ti-eye'></i> Viewinfo</a>
								
							 </div>
							</div>
						  </div>
						</div>
					  </div>";
		
		
		$table.="
				<div class='card'>
				  
					<div class='card-body'>
					".$dropdown."
						<div class='row'>
							<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12' style='text-align:center;'>
								<img src='".$Company_Logo."' width='100%' style='width:100px;height:100px;' class='img-sm mb-3 '><br>
								<p>".$retVal5."</p>
							</div>
							<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
								
								 
								 <div class='row'> 
									<div class='col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12'>
										<strong>".$CompanyName." ".$img."</strong>
										<br> <i class='ti-package'></i> Total Quantity : ".number_format($total_quantity,2)."</p>
										 <p><i class='ti-calendar'></i> Assign Date: ".date("d-m-Y", strtotime($po_date))." 
									</div>
									
									<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12'>
										<div class='row'>
											<div class='col-12 col-xl-12'>
											 <div class='justify-content-end d-flex'>
											 
											
											 
										  </div>
										</div>
									  </div>
									</div>
									
								 </div>
								 ";
								
								
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