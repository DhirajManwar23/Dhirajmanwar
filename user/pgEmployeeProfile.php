<?php 
session_start();

$request_id = $_REQUEST["id"];
 
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();


$_SESSION["employee_id"] = $request_id;

$qry8="select id from tw_employee_registration where id='".$request_id."'";
$employee_id=$sign->SelectF($qry8,"id");

$qry9="select value from tw_employee_contact where employee_id='".$employee_id."' AND contact_field='1'";
$userid=$sign->SelectF($qry9,"value");

$Token= time();

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$employee_id;
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePemail=$sign->escapeString($settingValuePemail);
$settingValuePotherEmail= $commonfunction->getSettingValue("Other Email");
$settingValuePmobile= $commonfunction->getSettingValue("Mobile");
$settingValuePhome= $commonfunction->getSettingValue("Home");
$settingValuePcontact= $commonfunction->getSettingValue("Primary Contact");
$settingValuePstatus =$commonfunction->getSettingValue("Awaiting Status");
$settingValuePstatus=$sign->escapeString($settingValuePstatus);
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueEmployeeImagePathVerification  =$commonfunction->getSettingValue("EmployeeImagePathVerification");
$settingValueEmployeeImagePathTemp  =$commonfunction->getSettingValue("EmployeeImagePathTemp");
$settingValueEmployeeImagePathOther  = $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueVerifiedGreenTickImage= $commonfunction->getSettingValue("VerifiedGreenTickImage");
$settingEmployeeBankImage  =$commonfunction->getSettingValue("BankImage");

$settingValueEmployeeImage= $commonfunction->getSettingValue("Employee Image");
// Include class definition
$qry = "SELECT er.id,er.employee_photo,er.employee_name,er.description,er.address_line_1,er.address_line_2,er.location,er.pincode,er.city,er.state,er.country,td.department_name,tdgn.designation_value,tr.role_name,trs.verification_status,er.employee_gender,er.employee_marital_status,er.date_of_birth
FROM ((tw_employee_registration er INNER JOIN tw_employee_login el ON er.id = el.employee_id) 
INNER JOIN tw_department_master td ON er.employee_department = td.id 
INNER JOIN tw_designation_master tdgn ON er.employee_designation = tdgn.id 
INNER JOIN tw_role_master tr ON er.employee_role = tr.id 
INNER JOIN tw_verification_status_master trs ON er.status = trs.id) WHERE er.id = '".$employee_id."' ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$id = $decodedJSON->response[0]->id;
$employee_photo = $decodedJSON->response[1]->employee_photo;
$CompanyName = $decodedJSON->response[2]->employee_name;
$Description = $decodedJSON->response[3]->description; 
$AddressLine1 = $decodedJSON->response[4]->address_line_1;
if($AddressLine1==""){
	$AddressLine1="---";
}
$AddressLine2 = $decodedJSON->response[5]->address_line_2; 
if($AddressLine2==""){
	$AddressLine2="---";
}
$Location = $decodedJSON->response[6]->location; 
if($Location==""){
	$Location="---";
}
$Pincode = $decodedJSON->response[7]->pincode; 
if($Pincode=="" || $Pincode==0){
	$Pincode="---";
}
$City = $decodedJSON->response[8]->city; 
if($City==""){
	$City="---";
}
$State = $decodedJSON->response[9]->state; 
if($State==""){
	$State="---";
}
$Country = $decodedJSON->response[10]->country;
if($Country==""){
	$Country="---";
}
$department_name = $decodedJSON->response[11]->department_name;
$designation_value = $decodedJSON->response[12]->designation_value;
$role_name = $decodedJSON->response[13]->role_name;
$verification_status = $decodedJSON->response[14]->verification_status;
$employee_gender = $decodedJSON->response[15]->employee_gender;
$employee_marital_status = $decodedJSON->response[16]->employee_marital_status;
$date_of_birth = $decodedJSON->response[17]->date_of_birth;

$Data="";
if($employee_gender!="" && $employee_marital_status!="" && $date_of_birth=='0000-00-00'){ 

	$Data=$employee_gender." || ".$employee_marital_status;
}
else if($employee_gender=="" && $employee_marital_status!="" && $date_of_birth!='0000-00-00'){ 

	$Data=$employee_marital_status." || ".$date_of_birth;
}
else if($employee_gender!="" && $employee_marital_status=="" && $date_of_birth!='0000-00-00'){ 

	$Data=$employee_gender." || ".$date_of_birth;
} 
else if($employee_gender=="" && $employee_marital_status!="" && $date_of_birth=='0000-00-00'){ 

	$Data=$employee_marital_status;
} 
else if($employee_gender=="" && $employee_marital_status=="" && $date_of_birth!='0000-00-00'){ 

	$Data=$date_of_birth;
} 
else if($employee_gender!="" && $employee_marital_status=="" && $date_of_birth=='0000-00-00'){ 

	$Data=$employee_gender;
} 
else if($employee_gender!="" && $employee_marital_status!="" && $date_of_birth!='0000-00-00'){ 

	$Data=$employee_gender." || ".$employee_marital_status." || ".$date_of_birth;
} 
$qry1 = "SELECT value,status,id,count(*) as cnt FROM tw_employee_contact WHERE employee_id = '".$employee_id."' and ( contact_field='".$settingValuePemail."' or contact_field='".$settingValuePcontact."' )";
$retVal1 = $sign->FunctionJSON($qry1);
$COMPANY_NAME=$CompanyName;
$decodedJSON = json_decode($retVal1);
$empvalue = $decodedJSON->response[0]->value;
$status = $decodedJSON->response[1]->status;
$id = $decodedJSON->response[2]->id;
$cnt = $decodedJSON->response[3]->cnt;

$qryStatus="select status from tw_employee_registration where ID='".$request_id."'";
$employeeStatus= $sign->SelectF($qryStatus,'status');
$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
$verifyStatus=$commonfunction->getSettingValue("Verified Status");

$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");

$VerifiedGreenTickImage= $commonfunction->getSettingValue("VerifiedGreenTickImage");

$query="SELECT cd.CompanyName as EmployeeCompanyName FROM tw_company_details cd INNER JOIN tw_employee_registration er ON er.company_id=cd.ID where er.id='".$employee_id."'";
$Companydata = $sign->FunctionJSON($query);
$decodedJSON1 = json_decode($Companydata);
$EmployeeCompanyName = $decodedJSON1->response[0]->EmployeeCompanyName; 		
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | My Profile</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  
  <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/custom/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../assets/images/favicon.png" />
      
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
		<?php
			include_once("navTopHeader.php");
		?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
        <?php
			include_once("navRightSideSetting.php");
		?>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
		<?php
			include_once("navSideBar.php");
		?>
      <!-- partial -->
	  <!-- ==============MODAL START ================= -->
	  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalOtp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Mobile phone verification</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalOtp()">
				<span aria-hidden="true" onclick="closeModalOtp()";>×</span>
				</button>
				
				
			</div>
			<div class="modal-body">
				 <div class="input-group">
					 <div class="input-group">
						<div class="form-group row">
						  <div class="col-sm-12">
							<div class="d-flex justify-content-center align-items-center container">
							<div class="card py-5 px-3">
								<h5 class="m-0"></h5><span class="mobile-text">Enter the code we just send on your mobile phone <b class="text-danger"></b></span>
								<div class="d-flex flex-row mt-5" id="otp">
								<input type="text" class="form-control text-center" id="first" maxlength="1">
								<input type="text" class="form-control text-center" id="second" maxlength="1">
								<input type="text" class="form-control text-center" id="third" maxlength="1">
								<input type="text" class="form-control text-center" id="fourth" maxlength="1">
								<input type="text" class="form-control text-center" id="fifth" maxlength="1">
								<input type="text" class="form-control text-center" id="sixth" maxlength="1">
								</div>
								<div class="text-center mt-5"><span class="d-block mobile-text">Don't receive the code?</span><a href="#" onclick="submitOtp();" id="resend" class="font-weight-bold text-danger cursor" onclick="">Resend</a> <span id="timer"></span></div>
							</div>
						</div>			
						  </div>
							
						</div>
                     
					  </div>
                    </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalOtp();">Close</button>
				<button type="button" class="btn btn-primary" onclick="checkotp();">Submit</button>
			</div>
			</div>
		</div>
	</div>
	  <!-- ==============MODAL END ================= -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-4 col-md-4 grid-margin">
              <div class="card">
                <div class="card-body">
					<div style="text-align:center;">
						<input type="file" accept=".png, .jpg, .jpeg" style="display:none" id="Document_Proof" onchange="deleteimg(<?php echo $created_by; ?>,'<?php echo $employee_photo ?>')"> 
						<a id="OpenImgUpload"><img src="<?php if($employee_photo==""){echo $settingValueEmployeeImagePathOther.$settingValueEmployeeImage; }else{ echo $settingValueEmployeeImagePathVerification.$empvalue."/".$employee_photo;}?>" class="img-lg rounded-circle mb-3 pointer-cursor " /></a>
						<br>
						<?php if($employee_photo==""){ 
							//echo $settingValueEmployeeImagePathOther.$settingValueEmployeeImage; 
							}
						  else{  ?>
						<a> <i class="ti-trash pointer-cursor" onclick="deletecompanyImge(<?php echo $created_by; ?>,'<?php echo $employee_photo  ?>');"></i>   </a>
						 <?php  }   ?>
						<br>
						<h1 class="display-4"><?php echo $CompanyName; ?> <?php if($employeeStatus==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$VerifiedImage;?>"/> <?php }?></h1>
						<small class="ms-4 text-muted">
						<?php echo $Data; ?>
						</small>
						<!-- ------------Company Name Edit Starts------------------------ -->
						<?php if($employee_gender!="" || $employee_marital_status!="" || $date_of_birth!='0000-00-00'){?>
						<a href="pgUpdateEmpPersonalDetails.php?type=edit&id=<?php echo $employee_id;?>"><i class="ti-pencil"></i></a>
						<?php }?>
						<!-- ------------Company Name Edit Ends------------------------ -->
						<h5><small class="text-muted"><a id="CompName" class="text-primary"  onclick="showModal();"><?php //echo $CompanyType; ?></a></small></h5>
				
					</div>
                </div>
              </div>
			  <br>
			  <div class="card">
                <div class="card-body">
					<div class="row">
						
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							
							<?php
								$qry2 = "SELECT cc.value, cc.status,cc.id,cc.contact_field ,cfm.contact_type FROM tw_employee_contact cc LEFT JOIN tw_contact_field_master cfm ON cc.contact_field = cfm.id WHERE cc.employee_id = '".$employee_id."' and ( cc.contact_field='".$settingValuePcontact."' or cc.contact_field='".$settingValuePemail."' )";
							    $retVal2 = $sign->FunctionJSON($qry2);
								$decodedJSON2 = json_decode($retVal2);
								
								$qry3="Select count(*) as cnt from tw_employee_contact where employee_id = '".$employee_id."' and ( contact_field='".$settingValuePemail."' or contact_field='".$settingValuePcontact."' )";
								$retVal3 = $sign->Select($qry3);
								
								$count = 0;
								$i = 1;
								$x=$retVal3;
								while($x>=$i){
										
									    $value = $decodedJSON2->response[$count]->value;
										$count=$count+1;
										$status = $decodedJSON2->response[$count]->status;
										$count=$count+1;
										$id = $decodedJSON2->response[$count]->id;
										$count=$count+1;
									    $contact_field = $decodedJSON2->response[$count]->contact_field ;
										$count=$count+1;
										$contact_type  = $decodedJSON2->response[$count]->contact_type ;
										$count=$count+1;
										
									
							?>
							
							<?php if($contact_type=="Primary Email") { ?> <i class="ti-email"></i> <?php } else {  ?> <i class="ti-mobile"></i> <?php } echo $contact_type; ?></b><br>
							<p class="card-description" id="Uservalue"><?php echo $value; ?> <br>
								
								<?php if($status==$settingValuePendingStatus){ ?>
								<small class="text-warning"><i class="ti-alert"></i> Pending </small>
							<?php }
							 else if($status==$settingValuePstatus) { ?>
							<small><label class="text-muted"> Awaiting to Verify</small></label>
							<?php }
							else{?>
							<small class="text-success"><img src="<?php echo $settingValueEmployeeImagePathOther.$settingValueVerifiedGreenTickImage; ?>" /> Verified</small>
							<?php }?>
								
							</p>
					<hr>
					 <?php 
								$i=$i+1;
						}
						if($i==2){?>
							<b>Primary Contact</b><br>
							
							<p class="card-description">---<br>
								<small><a href="pgAddContactDetails.php?type=add&id=&contactfield=<?php echo $settingValuePcontact;?>">Change</a></small>
								
							</p>
						<?php }

					?>
					
					
						</div>
					</div>
                </div>
              </div>  
			  <br>
			  <div class="card">
                <div class="card-body">
					<div class="row">
					
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<i class="ti-user"></i> <b><?php echo $role_name; ?></b><br>
							<p class="card-description"><?php echo $department_name." | ".$designation_value; ?>
							<a href="pgEditEmployeeRole.php?id=<?php echo $request_id;?>" class="float-right">
						<i class="ti-pencil"></i></a></p>
						</div>
					</div>
                </div>
              </div>
			  <br>
			   <!------------------------------- Employee Personal Details Starts  -------------------------->
			 <?php  $Gender='<i class="ti-user"></i> ';
			  $MarriedStatus='<i class="ti-control-record"></i> ';
			  $DOB='<i class="ti-calendar"></i> ';
			 ?>
			  <div class="card">
                <div class="card-body">
					<div class="row">
						<div class="col-sm-12">
							<b><i class="ti-file"></i> Personal Details</b>
							<a href="pgUpdateEmpPersonalDetails.php?type=edit&id=<?php echo $employee_id;?>" class="float-right"><i class="ti-pencil"></i></a><hr>
							<?php 	
								 if($employee_gender!=""){  
								 
									echo $Gender.$employee_gender."<hr>";
									 
								} 
								else{
									
								}	
							?>
							<?php 	
								 if($employee_marital_status!=""){  
								 
									echo $MarriedStatus.$employee_marital_status."<hr>";
									 
								} 
								else{
									
								}	
							?>
							<?php 	
								 if($date_of_birth!="0000-00-00"){  
								 
									echo $DOB.date("d-m-Y", strtotime($date_of_birth))."<hr>";
									 
								} 
								else{
									
								}
							?>	
						</div>
						
					</div>
				</div>
            </div> 
			 <!------------------------------- Employee Personal Details Ends  -------------------------->
            </div>			
            <div class="col-lg-8 col-md-8 grid-margin">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title"><i class="ti-info-alt"></i> Description<a href="pgEmployeeDescription.php" class="float-right"><i class="ti-plus"></i>
						</a>
					</h4>
					<hr>
					<p><?php echo $Description; ?></p>
                </div>
			</div><br>
              <div class="card">
                <div class="card-body">
					<h4 class="card-title"><i class="ti-direction"></i> Address Info<a href="pgAddEmployeeAddress.php?type=add&id=" class="float-right"><i class="ti-plus"></i></a></h4>
					<hr>
					 <?php
						$qry5 = "SELECT ta.id,ta.location,ta.city,ta.pincode,ta.state,atm.address_type_value,atm.address_icon,ta.default_address FROM tw_employee_address ta LEFT JOIN tw_address_type_master atm ON ta.address_type  = atm.id WHERE employee_id = '".$created_by."'";
						$retVal5 = $sign->FunctionJSON($qry5);
						$decodedJSON5 = json_decode($retVal5);
						
						$qry5="Select count(*) as cnt from tw_employee_address where employee_id = '".$created_by."'";
						$retVal5 = $sign->Select($qry5);
						
						$count = 0;
						$i = 1;
						$x=$retVal5;
						while($x>=$i){
								
								$id = $decodedJSON5->response[$count]->id;
								$count=$count+1;
								$location = $decodedJSON5->response[$count]->location;
								$count=$count+1;
								$city = $decodedJSON5->response[$count]->city;
								$count=$count+1;
								$pincode = $decodedJSON5->response[$count]->pincode;
								$count=$count+1;
								$state = $decodedJSON5->response[$count]->state;
								$count=$count+1;
								$address_type_value = $decodedJSON5->response[$count]->address_type_value;
								$count=$count+1;
								$address_icon = $decodedJSON5->response[$count]->address_icon;
								$count=$count+1;
								$default_address = $decodedJSON5->response[$count]->default_address;
								$count=$count+1;
							
							
							?>
					  <div class="row">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12">
								<strong><p><i class="<?php echo $address_icon;?>"></i></p></strong>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-11 col-xs-11 col-11">
							<strong><?php echo $address_type_value;?></strong> <?php if($default_address=="true") { ?><i class="ti-bookmark"></i><?php } ?><br>
							 <p><?php echo $location;?> | <?php echo $city; ?> | <?php echo $pincode;?> | <?php echo $state; ?></p>
						</div>
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-1">
							<a href="pgAddEmployeeAddress.php?type=edit&id=<?php echo $id;?>" class="float-right"><i class="ti-pencil"></i></a>
						</div>				
					</div>
					<hr>
					 <?php 
								$i=$i+1;
						}	
						if($i==1){?>
							<small>No Data Found</small>
						<?php }
					?>					
                </div>
              </div>
			  <br>
			  <div class="card">
                <div class="card-body">
					<h4 class="card-title"><i class="ti-tablet"></i> Contact Details <a href="pgAddEmployeeContactDetails.php?type=add&id=&contactfield=" class="float-right"><i class="ti-plus"></i></a></h4>
					<hr>
					<?php
						$qry2 = "SELECT cc.id,cc.value,cc.status,ctm.contact_type,ctm.contact_icon,cc.contact_field  FROM tw_employee_contact cc LEFT JOIN tw_contact_field_master ctm ON cc.contact_field = ctm.id WHERE employee_id = '".$employee_id."' and ( contact_field!='".$settingValuePemail."' and contact_field!='".$settingValuePcontact."' )";
						$retVal2 = $sign->FunctionJSON($qry2);
						$decodedJSON2 = json_decode($retVal2);	
						$qry3="Select count(*) as cnt from tw_employee_contact where employee_id = '".$employee_id."' and ( contact_field!='".$settingValuePemail."' and contact_field!='".$settingValuePcontact."')";
						$retVal3 = $sign->Select($qry3);						
						$count = 0;
						$i = 1;
						$x=$retVal3;
						while($x>=$i){								
							    $id = $decodedJSON2->response[$count]->id;
								$count=$count+1;
								$value1 = $decodedJSON2->response[$count]->value;
								$count=$count+1;
								$status = $decodedJSON2->response[$count]->status;
								$count=$count+1;
								$contact_type = $decodedJSON2->response[$count]->contact_type;
								$count=$count+1;
								$contact_icon = $decodedJSON2->response[$count]->contact_icon;
								$count=$count+1;
							    $contact_field = $decodedJSON2->response[$count]->contact_field;
								$count=$count+1;
							?>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<strong><p><i class="<?php echo $contact_icon;?>"></i> <?php echo $contact_type;?></p></strong>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-10 col-xs-10 col-10">
								<?php echo $value1;?><br>
								
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
								<a href="pgAddEmployeeContactDetails.php?type=edit&id=<?php echo $id;?>&contactfield=" class="float-right"><i class="ti-pencil"></i></a>
							</div>						
						</div>
						<hr>
					
							<?php 
								$i=$i+1;
						}	
						if($i==1){?>
							<small>No Data Found</small>
						<?php }
					?>					
                </div>
              </div>
			  <br>
			  <div class="card">
                <div class="card-body">
					<h4 class="card-title"> <i class="ti-wallet"></i> Bank Details<a href="pgAddEmployeeBankDetails.php?type=add&id=" class="float-right"><i class="ti-plus"></i></a></h4>
					<hr>
					<?php
					    $qry2 = "SELECT cb.id,cb.bank_name,cb.branch_location,cb.bank_account_status,ba.bank_account_type_value,cb.remark,cb.bank_account_proof FROM tw_employee_bankdetails cb LEFT JOIN tw_bank_account_type_master ba ON cb.account_type  = ba.id WHERE employee_id = '".$employee_id."' ";
						 $retVal2 = $sign->FunctionJSON($qry2);
						 $decodedJSON2 = json_decode($retVal2);
						
						$qry3="Select count(*) as cnt from tw_employee_bankdetails  WHERE employee_id = '".$employee_id."' ";
						 $retVal3 = $sign->Select($qry3);						
						$count = 0;
						$i = 1;
						$x=$retVal3;
						while($x>=$i){								
								$id = $decodedJSON2->response[$count]->id;
								$count=$count+1;
								$bank_name = $decodedJSON2->response[$count]->bank_name;
								$count=$count+1;
								$branch_location = $decodedJSON2->response[$count]->branch_location;
								$count=$count+1;
								$bank_account_status = $decodedJSON2->response[$count]->bank_account_status;
								$count=$count+1;
								$account_type = $decodedJSON2->response[$count]->bank_account_type_value;
								$count=$count+1;
                                $remark = $decodedJSON2->response[$count]->remark;
								$count=$count+1;
                                $bank_account_proof = $decodedJSON2->response[$count]->bank_account_proof;
								$count=$count+1;								
							?>
					  <div class="row">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12">
							<img src="<?php echo $settingValueEmployeeImagePathOther.$settingEmployeeBankImage;?>" class="img-sm" loading="lazy" />
						</div>
						<div class="col-lg-9 col-md-9 col-sm-11 col-xs-11 col-11">
							<strong><?php echo $bank_name;?></strong> <a href="<?php echo $settingValueEmployeeImagePathVerification; ?><?php echo $empvalue."/".$bank_account_proof;?>" target="_blank"><i class="ti-file"></i><a/><br>
							 <p><?php echo $branch_location;?> | <?php echo $account_type;?>
							<?php if($bank_account_status==$settingValuePendingStatus) {
							 ?>
								<br><small class="text-warning"><i class="ti-alert"></i> Pending</small>
						 <?php }
						 
							else if($bank_account_status==$settingValueVerifiedStatus){?>
								<br><small class="text-success"><img src="<?php echo $settingValueEmployeeImagePathOther.$settingValueVerifiedGreenTickImage; ?>"> Verified</small>
							<?php }
						 else if($bank_account_status==$settingValueRejectedStatus){?>
							<br><small class="text-danger"><i class="ti-na"></i> Rejected</small>
							<?php
							}
							else { ?></p>
							<small class="text-warning"><i class="ti-alert"></i></small>
							<?php   } ?>
						</div>
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-1">
						<?php if($bank_account_status==$settingValuePendingStatus) {
							 ?>
							<a href="pgAddEmployeeBankDetails.php?type=edit&id=<?php echo $id;?>" class="float-right"><i class="ti-pencil"></i></a>
							
						<?php } ?>
						</div>
					</div>
					<hr>
					 <?php 
								$i=$i+1;
						}	
						if($i==1){?>
							<small>No Data Found</small>
						<?php }
					?>					 
                </div>
              </div>
			  <br>
			  <div class="card">
                <div class="card-body">
					<h4 class="card-title"> <i class="ti-file"></i> Employee Documents<a href="pgAddEmployeeDocumentsDetails.php?type=add&id=" class="float-right"><i class="ti-plus"></i></a></h4>
					<hr>
					  <?php
						$qry2 = "SELECT ctm.id,ctm.document_number, ctm.document_verification_status, dtm.document_type_value,ctm.document_proof FROM tw_employee_document ctm LEFT JOIN tw_document_type_master dtm ON ctm.document_type = dtm.ID WHERE employee_id = '".$employee_id."'";
						$retVal2 = $sign->FunctionJSON($qry2);
						$decodedJSON2 = json_decode($retVal2);
						
						$qry3="Select count(*) as cnt from tw_employee_document  WHERE employee_id = '".$employee_id."'";
						$retVal3 = $sign->Select($qry3);
						
						$count = 0;
						$i = 1;
						$x=$retVal3;
						while($x>=$i){
								$id = $decodedJSON2->response[$count]->id;
								$count=$count+1;
								$document_number = $decodedJSON2->response[$count]->document_number;
								$count=$count+1;
								$document_verification_status = $decodedJSON2->response[$count]->document_verification_status;
								$count=$count+1;
								$document_type_value = $decodedJSON2->response[$count]->document_type_value;
								$count=$count+1;
								$document_proof = $decodedJSON2->response[$count]->document_proof;
								$count=$count+1;							
							?>
					  <div class="row">
						<div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 col-11">
							<strong><?php echo $document_type_value;?></strong> <a href="<?php echo $settingValueEmployeeImagePathVerification; ?><?php echo $empvalue."/".$document_proof;?>" target="_blank"><i class="ti-file"></i><a/><br>
							 <p><?php echo $document_number;?><br>
							<?php if($document_verification_status==$settingValuePendingStatus){ ?>
									<small class="text-warning"><i class="ti-alert"></i> Pending</small>
						 <?php }
						 
							else if($document_verification_status==$settingValueVerifiedStatus){?>
								<small class="text-success"><img src="<?php echo $settingValueEmployeeImagePathOther.$VerifiedGreenTickImage ?>"/> Verified</small>
							<?php }
						 else if($document_verification_status==$settingValueRejectedStatus){?>
							<small class="text-danger"><i class="ti-na"></i> Rejected</small>
							<?php
							}
							else { ?></p>
							<small class="text-warning"><i class="ti-alert"></i> </small>
							<?php } ?>
								</p>
						</div>
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-1">
							<?php if($document_verification_status==$settingValuePendingStatus || $document_verification_status==$settingValueRejectedStatus){ ?>
							
							<?php if($document_verification_status==$settingValuePendingStatus ){
								 ?>
								<a href="pgAddEmployeeDocumentsDetails.php?type=edit&id=<?php echo $id;?>" class="float-right"><i class="ti-pencil"></i></a>
								
							<?php }

							}?>
						</div>
					</div>
					<hr>
					 <?php 
								$i=$i+1;
						}	
						if($i==1){?>
							<small>No Data Found</small>
						<?php }
					?>
					 
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
			<?php
				include_once("footer.php");
			?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../assets/js/off-canvas.js"></script>
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
  <script src="../assets/js/todolist.js"></script>
  
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>

<script src="../assets/js/popover.js"></script>

 <script type='text/javascript'>



var valContact_field=0;
var UserValue=0; 
var commonComponentID="";
var id = "<?php echo $employee_id; ?>";
$(document).keydown(function(event) { 
  if (event.keyCode == 27) { 
    closeModalOtp();
  }
});
var empvalue='<?php echo $empvalue; ?>';
var settingValuePemail= '<?php echo $settingValuePemail; ?>';
var settingValuePotherEmail= '<?php $settingValuePotherEmail?>';
var settingValuePcontact='<?php echo $settingValuePcontact; ?>';
var settingValuePmobile='<?php echo $settingValuePmobile; ?>';
var settingValuePhome='<?php echo $settingValuePhome; ?>';
 
  
function verify(id,idval,component_id){
 commonComponentID = component_id;
$('#' + component_id).attr("disabled","true");
$('#' + component_id).css("pointer-events","none");
$('#' + component_id).addClass('text-muted');//secondary;
$('#' + component_id).html('<i class="ti-timer"></i> Processing...');
$('#' + component_id).css("cursor","no-drop");

 if(id==settingValuePemail || id==settingValuePotherEmail){
	var userVal= "<?php echo $userid;?>";
	var Token = "<?php echo $Token; ?>";
	var User_type= 'Employee';	        	
	var username= "<?php echo $employee_id; ?>";
   $.ajax({
		 url:"apiVerification.php",
		 method:"POST",
		 data:{userVal:userVal,Token:Token,User_type:User_type,username:username},
		success:function(response){
		console.log(response);
		if($.trim(response)=="Success"){
			showAlertRedirect("Verification link send to your Email","","success","pgEmployeeProfile.php");
			
		}
		else if($.trim(response)=="error"){
			showAlert("Value already exist","","error");
			$('#' + component_id).removeAttr("disabled");
			$('#' + component_id).css("pointer-events","auto");
			$('#' + component_id).removeClass('text-muted');//secondary;
			$('#' + component_id).html('Verify');
			$('#' + component_id).css("cursor","pointer");
			commonComponentID="";
		}else{
			showAlert("Something Went Wrong. Please Try After Sometime","","error");
			$('#' + component_id).removeAttr("disabled");
			$('#' + component_id).css("pointer-events","auto");
			$('#' + component_id).removeClass('text-muted');//secondary;
			$('#' + component_id).html('Verify');
			$('#' + component_id).css("cursor","pointer");
			commonComponentID="";
		}
		
	   }	
	 }); 
}
 else if(id==settingValuePcontact || id==settingValuePmobile){
	valContact_field=id;
	submitOtp(idval);
 }
 else if(id==settingValuePhome){
	 var number= "<?php echo $value;?>";
	 showConfirmAlert('Number verification','We will send an OTP on '+number+', Do you want to continue?','question', function (confirmed) {
		if (confirmed) {
			valContact_field=id;
			submitOtp(idval);
		}
	 });	
 }
 else{
 }
}
 
function checkotp(){
	
var First= $("#first").val();
var Second= $("#second").val();
var Third= $("#third").val();
var Fourth= $("#fourth").val();
var Fifth= $("#fifth").val();
var Sixth= $("#sixth").val();
var result= First + Second + Third + Fourth + Fifth + Sixth ; 
var email="<?php echo $userid; ?>";
var id = "<?php echo $employee_id; ?>";
var contact= "<?php echo $value; ?>";
 $.ajax({
		type:"POST",
		url:"apiVerifyOtp.php",
		data:{value:email, id:id, contact:UserValue,otp:result,valContact_field:valContact_field},
		  success:function(response){
			console.log(response);
		if($.trim(response)=="Success"){
			showAlertRedirect("Verify otp","","success","pgEmployeeProfile.php");
		}
		else if($.trim(response)=="error"){
			
			showAlert("Invalid Otp","","error");
			$("#txtValue").focus();
		}else if($.trim(response)=="invalid"){
			showAlert("Otp already send","","info");
		}		
		else{
			
			showAlertRedirect("Something Went Wrong. Please Try After Sometime","","warning","pgEmployeeProfile.php")
		}	
		
		  }
	});  
		 
 
 }
 
function submitOtp(idval){
		var email="<?php echo $userid; ?>";
		
		UserValue= idval;
		$.ajax({
			type:"POST",
		    url:"apiCheckOtp.php",
		    data:{value:email, id:id, idval:idval},
		    success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showModalOtp();	
			}
			else if($.trim(response)=="error"){
				
				showAlertRedirect("Error values","","error","pgEmployeeProfile.php")
				$("#txtValue").focus();
			}else{
				
				showAlertRedirect("Something Went Wrong. Please Try After Sometime","","info","pgEmployeeProfile.php")
			}		
        }				
	});  
}
function showModalOtp(){
	jQuery.noConflict();
	$("#modalOtp").modal("show");
	
}
function closeModalOtp() {
	 $("#modalOtp").modal("hide");
	 $('#' + commonComponentID).removeAttr("disabled");
	 $('#' + commonComponentID).css("pointer-events","auto");
	 $('#' + commonComponentID).removeClass('text-muted');//secondary;
	 $('#' + commonComponentID).html('Verify');
	 $('#' + commonComponentID).css("cursor","pointer");
	 commonComponentID="";
	 } 
var hdnIDimg="";
$('#OpenImgUpload').click(function(){ $('#Document_Proof').trigger('click'); });


function deletecompanyImge(id,name){
	var blank="";
	var Imagename="";
	showConfirmAlert('Confirm action!', 'Are you sure you want to delete the image?','question', function (confirmed){
	if(confirmed==true){
	var valquery="Update tw_employee_registration set employee_photo='"+blank+"' where ID='"+id+"' ";
		 $.ajax({
				type:"POST",
				url:"apiEmployeeDeleteImg.php",
				data:{valquery:valquery,empvalue:empvalue,Imagename:name},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
						
								showAlertRedirect("Success","Logo Deleted Successfully","success","pgEmployeeProfile.php?type=edit&id="+id);
								showname();
						
					}
					else if($.trim(response)=="Exist"){
							showAlert("Warning","Value already exist","warning");
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
					}
				}
			 });
	}
						});
}
function deleteimg(id,name){
	
	var blank="";
	var Imagename="";
	if(name=="")
	{
		showname(id);
	}
	else{
	var valquery="Update tw_employee_registration set employee_photo='"+blank+"' where id='"+id+"' ";
		 $.ajax({
				type:"POST",
				url:"apiEmployeeDeleteImg.php",
				data:{valquery:valquery,empvalue:empvalue,Imagename:name},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
					
						showAlertRedirect("Success","Logo Deleted Successfully","success","pgEmployeeProfile.php?type=edit&id="+id);
						showname(id);
					}
					else if($.trim(response)=="Exist"){
						showAlert("Warning","Value already exist","warning");
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
					}
				}
			 });
	}

}

 function showname(id) {
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;	 
	  var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg']) == -1) 
	  {
		$('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
		   showAlert("warning","Image File Size is very big","warning");
	  }
	  else
	  {
		form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);
	    form_data2.append("id",id);
	   $.ajax({
		url:"uploademployee.php",
		method:"POST",
		data: form_data2,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend:function(){
		},   
		success:function(data)
		{
			hdnIDimg=data;
			adddata();
		}
	   });
	  }
		  
		 
};
function adddata(){
		var valcreated_by = "<?php echo $created_by;?>";
	    var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";		
		if(hdnIDimg!=""){
			var valquery = "Update tw_employee_registration set employee_photo = '"+hdnIDimg+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valcreated_by+"' ";
		}
	 	$.ajax({
		type:"POST",
		url:"apiCommonQuerySingle.php",
		data:{valquery:valquery},
		success:function(response){
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Data Updated Successfully","success","pgEmployeeProfile.php?type=edit&id="+id);
			}
			else if($.trim(response)=="Exist"){
				showAlert("Warning","Value already exist","warning");
				$("#txtValue").focus();
			}else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	});    
  }
  
  //===============================================
  function OTPInput() {
  const inputs = document.querySelectorAll('#otp > *[id]');
  for (let i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('keydown', function(event) {
      if (event.key === "Backspace") {
        inputs[i].value = '';
        if (i !== 0)
          inputs[i - 1].focus();
      } else {
        if (i === inputs.length - 1 && inputs[i].value !== '') {
          return true;
        } else if (event.keyCode > 47 && event.keyCode < 58) {
          inputs[i].value = event.key;
          if (i !== inputs.length - 1)
            inputs[i + 1].focus();
          event.preventDefault();
        } else if (event.keyCode > 64 && event.keyCode < 91) {
          inputs[i].value = String.fromCharCode(event.keyCode);
          if (i !== inputs.length - 1)
            inputs[i + 1].focus();
          event.preventDefault();
        }
      }
    });
  }
}
OTPInput();

</script>	
</body>
</html>