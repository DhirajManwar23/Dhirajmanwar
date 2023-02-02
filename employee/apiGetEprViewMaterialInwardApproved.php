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
$settingValueCompletedStatus = $commonfunction->getSettingValue("Completed Status");


$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");


$qry2="select po_id as id,requester_company_id,recycler_company_id,total_quantity,reason,date,status from  tw_epr_material_assign_info where requester_company_id='".$company_id."' AND (status='".$settingValueApprovedStatus."' or status='".$settingValueCompletedStatus."') AND po_id='".$po_id."' order by id desc";
$retVal2 = $sign->FunctionJSON($qry2);

$qry1="select count(*) as cnt from tw_epr_material_assign_info where requester_company_id='".$company_id."' AND(status='".$settingValueApprovedStatus."' or status='".$settingValueCompletedStatus."')  AND po_id='".$po_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal2);
$count = 0;
$i1 = 1;
$x1=$retVal1;
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
	while($x1>=$i1){
		//$valper = 0.00;
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
		
		$qry5="Select verification_status from tw_verification_status_master where id='".$settingValueApprovedStatus."'";
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
		
		
		
		$table.="
				<div class='card-body'>
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
										 <p><i class='ti-calendar'></i> PO Date: ".date("d-m-Y", strtotime($po_date))." 
									</div>
									
								 </div>
								 <hr>
								
								 </div>
								 <br><br>
								 <div class='row'>";
								 
								
								
							 $table.="</div>
							 </div>
						</div>
						  
						 
					</div>
				  </div><br>";		
			

		$i1=$i1+1;
		//------Karuna end
		
		
	}

		//$table.="</tbody>";
}
	echo $table;
	?>