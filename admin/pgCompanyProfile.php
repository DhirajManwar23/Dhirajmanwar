<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
$company_id = $_REQUEST["id"];
$_SESSION["company_id"] = $company_id;
$Token= time();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["company_id"];
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePemail=$sign->escapeString($settingValuePemail);
$settingValuePotherEmail= $commonfunction->getSettingValue("Other Email");
$settingValuePmobile= $commonfunction->getSettingValue("Mobile");
$settingValuePhome= $commonfunction->getSettingValue("Home");
$settingValuePcontact= $commonfunction->getSettingValue("Primary Contact");
$settingValuePstatus =$commonfunction->getSettingValue("Awaiting Status");
$settingValuePstatus=$sign->escapeString($settingValuePstatus);
$settingValueUserImagePathVerification=$commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
$settingValueAdminImagePathOther=$commonfunction->getSettingValue("AdminImagePathOther");
$settingValueEmployeeImagePathVerification=$commonfunction->getSettingValue("EmployeeImagePathVerification");
$EmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueCompanyImage=$commonfunction->getSettingValue("Company Image");
$settingValueEmployeeImage=$commonfunction->getSettingValue("Employee Image");
$settingValueVerifiedGreenTickImage=$commonfunction->getSettingValue("VerifiedGreenTickImage");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValuePfax= $commonfunction->getSettingValue("Fax");
$settingAdminBankImage=$commonfunction->getSettingValue("BankImage");
$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
$ComplianceImage=$commonfunction->getCommonDataValue("Compliance Image");

$EmailQry="Select value from tw_company_contact where company_id='".$created_by."' AND contact_field='".$settingValuePemail."' ";
$EMAIL= $sign->SelectF($EmailQry,"value");

$qry = "SELECT cd.CompanyName,cd.Status,ctm.company_type,cd.Company_Logo,cd.Description,cd.compliance_status  FROM tw_company_details cd LEFT JOIN tw_company_type_master ctm ON cd.CompanyType = ctm.id WHERE cd.ID = '".$company_id."' ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$Status = $decodedJSON->response[1]->Status;
$CompanyType = $decodedJSON->response[2]->company_type; 
$Company_Logo = $decodedJSON->response[3]->Company_Logo;
$Description = $decodedJSON->response[4]->Description;
$compliance_status = $decodedJSON->response[5]->compliance_status;

$qry1 = "SELECT value FROM tw_company_contact WHERE company_id = '".$company_id."' and contact_field='".$settingValuePemail."'";
$retVal1=$sign->SelectF($qry1,"value");
$userid = $retVal1;

$queryDocumentProof= "SELECT document_proof FROM tw_compliance_document where company_id='".$company_id."'";
$ComplianceDocumentProof = $sign->SelectF($queryDocumentProof,"document_proof");
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
<link rel="stylesheet" href="../assets/vendors/jquery-toast-plugin/jquery.toast.min.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
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
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
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
							<div class="d-flex flex-row mt-5"><input type="text" class="form-control" autofocus="" id="1"><input type="text" class="form-control" id="2"><input type="text" class="form-control" id="3">
							<input type="text" class="form-control" id="4"><input type="text" class="form-control" id="5"><input type="text" class="form-control" id="6"></div>
							<div class="text-center mt-5"><span class="d-block mobile-text">Don't receive the code?</span><a href="#" onclick="submitOtp();" class="font-weight-bold text-danger cursor">Resend</a></div>
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
   <!-- ==============MODAL START ================= -->
  <div class="modal fade" id="modalEditLogo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-lock"></i> Company Type</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true">×</span>
			</button>

		</div>
		<div class="modal-body">
			 <div class="input-group">
				 <div class="input-group">
					<div class="form-group row" id="Type">
					<?php
							$qryn = "SELECT id,company_type FROM tw_company_type_master WHERE visibility='true' Order by priority,company_type desc";
							$retValn = $sign->FunctionJSON($qryn);
							$decodedJSONn = json_decode($retValn);
							
							$qryn1="Select count(*) as cnt from tw_company_type_master where visibility='true' Order by priority,company_type desc";
							$retValn1 = $sign->Select($qryn1);
							
							$count = 0;
							$i = 1;
							$x=$retValn1;
							while($x>=$i){
									
								$company_typeid = $decodedJSONn->response[$count]->id;
								$count=$count+1;
								$company_type = $decodedJSONn->response[$count]->company_type;
								$count=$count+1;
									
							?>
						
							<div class="col-md-4 mb-4 stretch-card transparent text-center" >
							  <div class="card card-light-blue">
								<div class="card-body center" onclick="changeCompanyType('<?php echo $company_typeid; ?>');">
								  <!--<p class="mb-4 center"> <i class="ti-home"></i><br>-->
								  <button type="button" class="btn btn-primary btn-rounded btn-icon">
									<i class="ti-home"></i>
								</button>
								  <?php echo $company_type; ?></p>
								</div>
							  </div>
							</div>
						  
						<?php 
							$i=$i+1;
						}
					?>
					</div>
				 
				  </div>
				</div>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
  <!-- ==============COMPANY NAME MODAL START ================= -->
    <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalCompanyLoginStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Change Company Name</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModalLogin()";>×</span>
			</button>
		</div>
		<div class="modal-body">
					<div class="row">
					  <div class="col-lg-12">
							<div class="form-group">
								<h6 class="card-title"></i>Company Name</h6>
								<input type="text" class="form-control" id="txtCompanyName" value="<?php echo $CompanyName; ?>" placeholder="Company Name"/>	
							</div>		
					  </div>
					</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalLogin();">Close</button>
			<button type="button" class="btn btn-primary"  onclick="updatecompanyname()">Save</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
  <!-- ==============COMPANY NAME MODAL END ================= -->
  <div class="main-panel">        
	<div class="content-wrapper">
	  <div class="row">
		<div class="col-lg-4 col-md-4 grid-margin">
		  <div class="card">
			<div class="card-body">
				<div style="text-align:center;">
					<input type="file" accept=".png, .jpg, .jpeg" style="display:none" id="Document_Proof" onchange="deleteimg(<?php echo $created_by; ?>,'<?php echo $Company_Logo  ?>');"> 
						<a id="OpenImgUpload">
						<img src="<?php if($Company_Logo==""){echo $settingValueUserImagePathOther.$settingValueCompanyImage; }else{ echo $settingValueUserImagePathVerification.$userid."/".$Company_Logo;}?>" class="img-lg rounded-circle mb-3 pointer-cursor"/>  </a>
						<br>
						<?php if($Company_Logo==""){ 
						 } else{  ?>
						<a ><i onclick="deletecompanyImge(<?php echo $created_by; ?>,'<?php echo $Company_Logo  ?>');" class="ti-trash pointer-cursor pointer-cursor"></i>   </a>
						 <?php  }   ?>
						<br>
						<h1 class="display-4"><?php echo $CompanyName; ?> <?php if($Status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$VerifiedImage;?>" data-toggle="tooltip" data-placement="top" title="This is a verified company"/> <?php }?>
						<?php if($compliance_status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$ComplianceImage;?>" data-toggle="tooltip" data-placement="top" title="This is a compliant company"/> <?php }?></h1>
						<!-- ------------Company Name Edit Starts------------------------ -->
						
						<a class="text-info pointer-cursor" onclick="EditCompanyName()"> <i class="ti-pencil"></i></a>
						
						<!-- ------------Company Name Edit Ends------------------------ -->
						<h5><small class="text-muted"><a id="CompName" class="text-primary"  onclick="showModal();"><?php echo $CompanyType; ?></a></small></h5>
			
				</div>
			</div>
		  </div>
		  <br>
		  <div class="card">
			<div class="card-body">
				<div class="row">
					
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
						
						<?php
							$qry2 = "SELECT cc.value, cc.status,cc.id,cc.contact_field ,cfm.contact_type FROM tw_company_contact cc LEFT JOIN tw_contact_field_master cfm ON cc.contact_field = cfm.id WHERE cc.company_id = '".$company_id."' and ( cc.contact_field='".$settingValuePcontact."' or cc.contact_field='".$settingValuePemail."' )";
							$retVal2 = $sign->FunctionJSON($qry2);
							$decodedJSON2 = json_decode($retVal2);
							
							$qry3="Select count(*) as cnt from tw_company_contact where company_id = '".$company_id."' and ( contact_field='".$settingValuePemail."' or contact_field='".$settingValuePcontact."' )";
							$retVal3 = $sign->Select($qry3);
							
							$count = 0;
							$i = 1;
							$x=$retVal3;
							while($x>=$i){
									
									$pvalue = $decodedJSON2->response[$count]->value;
									$count=$count+1;
									$status = $decodedJSON2->response[$count]->status;
									$count=$count+1;
									$id = $decodedJSON2->response[$count]->id;
									$count=$count+1;
									$contact_field  = $decodedJSON2->response[$count]->contact_field ;
									$count=$count+1;
									$contact_type  = $decodedJSON2->response[$count]->contact_type ;
									$count=$count+1;
									
								
						?>
						<?php if($contact_type=="Primary Email") { ?> <i class="ti-email"></i> <?php } else {  ?> <i class="ti-mobile"></i> <?php } echo $contact_type; ?></b><br>
						
						<p class="card-description" id="Uservalue"><?php echo $pvalue; ?><br>
							
							<?php if($status==$settingValuePendingStatus){ ?>
							<small class="text-warning"><i class="ti-alert"></i> Pending </small>
							<?php }
							 else if($status==$settingValuePstatus) { ?>
							<small><label class="text-muted"> Awaiting to Verify</small></label>
							<?php }
							else{?>
							<small class="text-success"><img src="<?php echo $settingValueAdminImagePathOther.$settingValueVerifiedGreenTickImage; ?>" /> Verified</small>
							<?php }?>
							
						</p>
				<hr>
				 <?php 
							$i=$i+1;
					}
					if($i==2){?>
						<b>Primary Contact</b><br>
						
						<p class="card-description">---<br>
							<small><a href="pgAddContactDetails.php?type=add&id=&contactfield=<?php echo $settingValuePcontact;?>"></a></small>								
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
				<h4 class="card-title">Team Members</h4>
				<?php
							$qry2 = "SELECT cc.employee_name,cc.id,cc.employee_photo ,cfm.department_name,td.designation_value  FROM tw_employee_registration cc LEFT JOIN tw_department_master cfm ON cc.employee_department = cfm.id LEFT JOIN tw_designation_master td ON cc.employee_designation = td.id WHERE cc.company_id = '".$company_id."'";
							$retVal2 = $sign->FunctionJSON($qry2);
							$decodedJSON2 = json_decode($retVal2);
							
							$qry3="Select count(*) as cnt from tw_employee_registration where company_id = '".$company_id."'";
							$retVal3 = $sign->Select($qry3);
							
							$count = 0;
							$i = 1;
							$x=$retVal3;
							while($x>=$i){
									
									$employee_name = $decodedJSON2->response[$count]->employee_name;
									$count=$count+1;
									$id = $decodedJSON2->response[$count]->id;
									$count=$count+1;
									$employee_photo = $decodedJSON2->response[$count]->employee_photo;
									$count=$count+1;
									$department_name = $decodedJSON2->response[$count]->department_name;
									$count=$count+1;
									$designation_value  = $decodedJSON2->response[$count]->designation_value ;
									$count=$count+1;
								
								$qry9="Select value from tw_employee_contact where employee_id = '".$id."' and contact_field='".$settingValuePemail."'";
								$EmployeeEmail=$sign->SelectF($qry9,"value");
						?>
                    
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3">
						<img src="<?php if($employee_photo==""){echo $EmployeeImagePathOther.$settingValueEmployeeImage; }else{ echo $settingValueEmployeeImagePathVerification.$EmployeeEmail."/".$employee_photo;}?>" class="img-sm" loading="lazy" />
					</div>
					<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 col-9">
						<b><?php echo $employee_name; ?></b><br>
						<p class="card-description"><?php echo $department_name." | ".$designation_value; ?><a href="EditEmployeeRole.php?id=<?php echo $id;?>" class="float-right">
						<i class="ti-pencil"></i></a></p>
					</div>
				</div>
				 <?php 
							$i=$i+1;
					}
				if($i==1){?>
						<small>No Data Found</small>
					<?php }?>
				
			</div>
		  </div>
		</div>
		
		<div class="col-lg-8 col-md-8 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"><i class="ti-info-alt"></i> Description<a href="pgCompanyDescription.php" class="float-right"><i class="ti-plus"></i>
					</a>
				</h4>
				<hr>
				<p><?php echo $Description; ?></p>
			</div>
		</div><br>
		   <div class="card">
			<div class="card-body">
				<h4 class="card-title"><i class="ti-direction"></i> Address Info<a href="pgAddAddress.php?type=add&id=" class="float-right"><i class="ti-plus"></i></a></h4>
				<hr>
				 <?php
					$qry5 = "SELECT ta.id,ta.location,ta.city,ta.pincode,ta.state,atm.address_type_value,atm.address_icon,ta.default_address FROM tw_company_address ta LEFT JOIN tw_address_type_master atm ON ta.address_type  = atm.id WHERE company_id = '".$company_id."'";
					$retVal5 = $sign->FunctionJSON($qry5);
					$decodedJSON5 = json_decode($retVal5);
					
					$qry5="Select count(*) as cnt from tw_company_address where company_id = '".$company_id."'";
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
						<a href="pgAddAddress.php?type=edit&id=<?php echo $id;?>" class="float-right"><i class="ti-pencil"></i></a>
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
				<h4 class="card-title"><i class="ti-tablet"></i> Contact Details <a href="pgAddContactDetails.php?type=add&id=&contactfield=" class="float-right"><i class="ti-plus"></i></a></h4>
				<hr>
				
				
			
				<?php
					$qry2 = "SELECT cc.id,cc.value,cc.status,ctm.contact_type,ctm.contact_icon  FROM tw_company_contact cc LEFT JOIN tw_contact_field_master ctm ON cc.contact_field = ctm.id WHERE company_id = '".$company_id."' and ( contact_field!='".$settingValuePemail."' and contact_field!='".$settingValuePcontact."' )";
					$retVal2 = $sign->FunctionJSON($qry2);
					$decodedJSON2 = json_decode($retVal2);
					
					$qry3="Select count(*) as cnt from tw_company_contact where company_id = '".$company_id."' and ( contact_field!='".$settingValuePemail."' and contact_field!='".$settingValuePcontact."' )";
					$retVal3 = $sign->Select($qry3);
					
					$count = 0;
					$i = 1;
					$x=$retVal3;
					while($x>=$i){
							
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$value = $decodedJSON2->response[$count]->value;
							$count=$count+1;
							$status = $decodedJSON2->response[$count]->status;
							$count=$count+1;
							$contact_type = $decodedJSON2->response[$count]->contact_type;
							$count=$count+1;
							$contact_icon = $decodedJSON2->response[$count]->contact_icon;
							$count=$count+1;
						
						?>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<strong><p><i class="<?php echo $contact_icon;?>"></i> <?php echo $contact_type;?></p></strong>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-10 col-xs-10 col-10">
							<?php echo $value;?><br>
							<?php if($status==$settingValuePendingStatus){ ?>
								<small><a href="#" onclick="verify('<?php echo $contact_field;?>','<?php echo $value; ?>','PcontactVerify<?php echo $id; ?>');" id="PcontactVerify<?php echo $id; ?>">Verify</a></small>
								<?php }
								else if($status==$settingValuePstatus) { ?>
								<small><label class="text-muted">Awaiting to Verify</small></label>
								<?php }
								else{?>
								<small class="text-success"><img src="<?php echo $settingValueUserImagePathOther.$settingValueVerifiedGreenTickImage ?>" /> Verified</small>
								<?php  }?><br>
							
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
							<a href="pgAddContactDetails.php?type=edit&id=<?php echo $id;?>&contactfield=" class="float-right"><i class="ti-pencil"></i></a>
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
				<h4 class="card-title"> <i class="ti-wallet"></i> Bank Details<a href="pgAddBankDetails.php?type=add&id=" class="float-right"><i class="ti-plus"></i></a></h4>
				<hr>
				<?php
					$qry2 = "SELECT cb.id,cb.bank_name,cb.branch_location,cb.bank_account_status,ba.bank_account_type_value,cb.remark ,cb.bank_account_proof FROM tw_company_bankdetails cb LEFT JOIN tw_bank_account_type_master ba ON cb.account_type  = ba.ID WHERE company_id = '".$company_id."'";
					$retVal2 = $sign->FunctionJSON($qry2);
					$decodedJSON2 = json_decode($retVal2);
					
					$qry3="Select count(*) as cnt from tw_company_bankdetails where company_id = '".$company_id."'";
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
							$checkBankACStatus=$bank_account_status;
							$count=$count+1;
							$account_type = $decodedJSON2->response[$count]->bank_account_type_value;
							$count=$count+1;
							$remark = $decodedJSON2->response[$count]->remark;
							$count=$count+1;
							$bank_account_proof = $decodedJSON2->response[$count]->bank_account_proof;
							$count=$count+1;
							$qryReason="select reason from tw_rejected_reason_master where id='".$remark."'";
							$reason=$sign->SelectF($qryReason,"reason");
						
						?>
				  <div class="row">
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12">
						<img src="<?php echo $settingValueAdminImagePathOther.$settingAdminBankImage;?>" class="img-sm" loading="lazy" />					</div>
					<div class="col-lg-9 col-md-9 col-sm-11 col-xs-11 col-11">
						<strong><?php echo $bank_name;?></strong><a href="<?php echo $settingValueUserImagePathVerification; ?><?php echo $EMAIL."/".$bank_account_proof;?>" target="_blank"><i class="ti-file"></i><a/><br>
						 <p><?php echo $branch_location;?> | <?php echo $account_type;?>
						 
						 <?php if($bank_account_status==$settingValuePendingStatus){
							 ?>
								<br><small class="text-warning"><i class="ti-alert"></i> Pending</small>
						 <?php }
						 
							else if($bank_account_status==$settingValueVerifiedStatus){?>
								<br><small class="text-success"><img src="<?php echo $settingValueAdminImagePathOther.$settingValueVerifiedGreenTickImage; ?>"> Verified</small>
							<?php }
						 else if($bank_account_status==$settingValueRejectedStatus){?>
							<br><small class="text-danger"><i class="ti-na"></i> Rejected(<em><?php echo $reason;?></em>)</small>
							<?php
							}
							else { ?></p>
							<small class="text-warning"><i class="ti-alert"></i></small>
							<?php } ?>
							
						 </p>
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-1">
					 <?php if($bank_account_status==$settingValuePendingStatus){
							 ?>
								<a href="pgAddBankDetails.php?type=edit&id=<?php echo $id;?>" class="float-right"><i class="ti-pencil"></i></a>
						 <?php } 
						 ?>
						 
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
				<h4 class="card-title"> <i class="ti-file"></i> Company Documents<a href="pgAddCompanyDocumentsDetails.php?type=add&id=" class="float-right"><i class="ti-plus"></i></a></h4>
				<hr>
				  <?php
					$qry2 = "SELECT ctm.id,ctm.document_number, ctm.document_verification_status, dtm.document_type_value, ctm.remark,ctm.document_proof FROM tw_company_document ctm LEFT JOIN tw_document_type_master dtm ON ctm.document_type = dtm.ID WHERE company_id = '".$company_id."'";
					$retVal2 = $sign->FunctionJSON($qry2);
					$decodedJSON2 = json_decode($retVal2);
					
					$qry3="Select count(*) as cnt from tw_company_document where company_id = '".$company_id."'";
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
							$remark = $decodedJSON2->response[$count]->remark;
							$count=$count+1;
							$document_proof = $decodedJSON2->response[$count]->document_proof;
							$count=$count+1;
							$qryReason="select reason from tw_rejected_reason_master where id='".$remark."'";
							$Docreason=$sign->SelectF($qryReason,"reason");
						
						
						?>
				  <div class="row">
					<div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 col-11">
						<strong><?php echo $document_type_value;?></strong><br>
						 <p><?php echo $document_number;?><a href="<?php echo $settingValueUserImagePathVerification; ?><?php echo $EMAIL."/".$document_proof;?>" target="_blank"><i class="ti-file"></i><a/><br>
							 <?php if($document_verification_status==$settingValuePendingStatus){
							 ?>
								<small class="text-warning"><i class="ti-alert"></i> Pending</small>
						 <?php }
						 
							else if($document_verification_status==$settingValueVerifiedStatus){?>
								<small class="text-success"><img src="<?php echo $settingValueAdminImagePathOther.$settingValueVerifiedGreenTickImage; ?>"/> Verified</small>
							<?php }
						 else if($document_verification_status==$settingValueRejectedStatus){?>
							<small class="text-danger"><i class="ti-na"></i> Rejected(<em><?php echo $Docreason;?></em>)</small>
							<?php
							}
							else { ?></p>
							<small class="text-warning"><i class="ti-alert"></i> </small>
							<?php } ?>
							</p>
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-1">
					 <?php if($document_verification_status==$settingValuePendingStatus ){
							 ?>
								<a href="pgAddCompanyDocumentsDetails.php?type=edit&id=<?php echo $id;?>" class="float-right"><i class="ti-pencil"></i></a>
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
				<h4 class="card-title"> <i class="ti-file"></i> Compliance Documents<a href="pgAddComplianceDocument.php?type=add&id=" class="float-right"><i class="ti-plus"></i></a></h4>
				<hr>
				  <?php
					$qry2 = "SELECT cd.id, cd.document_number, cd.document_verification_status, ctm.compliance_document_type, cd.document_verification_status,cd.remark, cd.document_proof FROM tw_compliance_document cd, tw_compliance_type_master ctm where ctm.id = cd.document_type and cd.company_id = '".$company_id."'";
						$retVal2 = $sign->FunctionJSON($qry2);
						$decodedJSON2 = json_decode($retVal2);
					
					$qry3="Select count(*) as cnt from tw_compliance_document where company_id = '".$company_id."'";
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
								$compliance_document_type = $decodedJSON2->response[$count]->compliance_document_type;
								$count=$count+1;
								$remark = $decodedJSON2->response[$count]->remark;
								$count=$count+1;
								$document_proof = $decodedJSON2->response[$count]->document_proof;
								$count=$count+1;
								
							$qryReason="select reason from tw_rejected_reason_master where id='".$remark."'";
							$Docreason=$sign->SelectF($qryReason,"reason");
		
						?>
				  <div class="row">
					<div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 col-11">
						<strong><?php echo $compliance_document_type;?></strong><a href="<?php echo $settingValueUserImagePathVerification; ?><?php echo $EMAIL."/".$document_proof;?>" target="_blank"> <i class="ti-file"></i><a/><br>
						 <p><?php echo $document_number;?><br>
							 <?php if($document_verification_status==$settingValuePendingStatus){
							 ?>
								<small class="text-warning"><i class="ti-alert"></i> Pending</small>
						 <?php }
						 
							else if($document_verification_status==$settingValueVerifiedStatus){?>
								<small class="text-success"><img src="<?php echo $settingValueAdminImagePathOther.$settingValueVerifiedGreenTickImage; ?>"/> Verified</small>
							<?php }
						 else if($document_verification_status==$settingValueRejectedStatus){?>
							<small class="text-danger"><i class="ti-na"></i> Rejected(<em><?php echo $Docreason;?></em>)</small>
							<?php
							}
							else { ?></p>
							<small class="text-warning"><i class="ti-alert"></i> </small>
							<?php } ?>
							</p>
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-1">
					 <?php if($document_verification_status==$settingValuePendingStatus ){
							 ?>
								<a href="pgAddComplianceDocument.php?type=edit&id=<?php echo $id;?>" class="float-right"><i class="ti-pencil"></i></a>
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
<script src="../assets/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>

<script src="../assets/js/toastDemo.js"></script>
<script type='text/javascript'>

var hdnIDimg="";
var User_type= "Company";	
var email="<?php echo $userid; ?>";
var company_id= "<?php echo $company_id; ?>";

$(document).keydown(function(event) { 
  if (event.keyCode == 27) { 
    closeModalOtp();
  }
});
$(document).ready(function(){
    $('#OpenImgUpload').css({'cursor': 'pointer'});
	$('#CompName').css({'cursor': 'pointer'});
	$('#Type').css({'cursor': 'pointer'});
});
$('input').blur(function()
{
	var valplaceholder = $(this).attr("placeholder");
	var vallblid = $(this).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $(this).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{		
		if(!checkElementExists)
		{
			$(this).parent().addClass('has-danger');
			$(this).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
		}

	}
	else
	{
		$(this).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
});
function setErrorOnBlur(inputComponent)
{
	var valplaceholder = $("#" +inputComponent).attr("placeholder");
	var vallblid = $("#" +inputComponent).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $("#" +inputComponent).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		
		if(!checkElementExists)
		{
			$("#" +inputComponent).parent().addClass('has-danger');
			$("#" +inputComponent).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
			$("#" +inputComponent).focus();
		}

	}
	else
	{
		$("#" +inputComponent).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
}

function setError(inputComponent)
{	
	var valplaceholder = $(inputComponent).attr("placeholder");
	var vallblid = $(inputComponent).attr("id");
	var valid = "errSet" + vallblid;
	var valtext = "Password mismatch!";
	var checkElementExists = document.getElementById(valid);
	if(!checkElementExists)
	{
		$("#" + vallblid).parent().addClass('has-danger');
		$("#" + vallblid).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
	}
	
}
function removeError(inputComponent)
{
	var vallblid = $(inputComponent).attr("id");
	$("#" + vallblid).parent().removeClass('has-danger');
	const element = document.getElementById("errSet"+vallblid);
	if (element)
	{
		element.remove();
	}
}
function showModal()
{	
	jQuery.noConflict();
	$("#modalEditLogo").modal("show");
	//CopyPassword();
}
function closeModal() {
	
  $("#modalEditLogo").modal("hide");
}

var settingValuePemail= '<?php echo $settingValuePemail; ?>';
var settingValuePotherEmail= '<?php $settingValuePotherEmail?>';
var settingValuePcontact='<?php echo $settingValuePcontact; ?>';
var settingValuePmobile='<?php echo $settingValuePmobile; ?>';
var settingValuePhome='<?php echo $settingValuePhome; ?>';

$('#OpenImgUpload').click(function(){ $('#Document_Proof').trigger('click'); });
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
		var username= "<?php echo $company_id; ?>";
	   $.ajax({
		  	 url:"apiVerification.php",
		     method:"POST",
			 data:{userVal:userVal,Token:Token,User_type:User_type,username:username},
			success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Verification link send to your Email","success","pgCompanyProfile.php");
			}
			else if($.trim(response)=="error"){
				showAlert("Error","Value already exist","error");
				$('#' + component_id).removeAttr("disabled");
				$('#' + component_id).css("pointer-events","auto");
				$('#' + component_id).removeClass('text-muted');//secondary;
				$('#' + component_id).html('Verify');
				$('#' + component_id).css("cursor","pointer");
				commonComponentID="";
			}else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
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
		 var number= "<?php echo $pvalue;?>";
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

 function checkFileExist(urlToFile) {
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();
     
    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
}

function deletecompanyImge(id,name){
	var blank="";
	var Imagename="";
		
						showConfirmAlert('Confirm action!', 'Are you sure you want to delete the image?','question', function (confirmed){
							if(confirmed==true){
							showAlertRedirect("Success","Company Logo Deleted Successfully","success","pgCompanyProfile.php?id="+id);
							
							
					
				var valquery="Update tw_company_details set Company_Logo='"+blank+"' where ID='"+id+"' ";
			    $.ajax({
				type:"POST",
				url:"apiDelteImg.php",
				data:{valquery:valquery,empvalue:email,Imagename:name},
				success:function(response){
					console.log(response);
				if($.trim(response)=="Success"){
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
	profileId=id;
	var blank="";
	var Imagename="";
	if(name=="")
	{
		showname(id);
	}
	else{
	var valquery="Update tw_company_details set Company_Logo='"+name+"' where id='"+id+"' ";
		 $.ajax({
				type:"POST",
				url:"apiDelteImg.php",
				data:{valquery:valquery,empvalue:email,Imagename:name},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
					
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
//--------------------Edit COmpany Name Starts------------------------//
function EditCompanyName(){
	 showModalLogin();
}
function showModalLogin()
{	
	jQuery.noConflict();
	$("#modalCompanyLoginStatus").modal("show");
	
}
function closeModalLogin() {

$("#modalCompanyLoginStatus").modal("hide");

location.reload();
}


function updatecompanyname(){
	if(!validateBlank($("#txtCompanyName").val())){
		setErrorOnBlur("txtCompanyName");	
	}
	else{
		var valquery="Update tw_company_details set CompanyName='"+$("#txtCompanyName").val()+"' where ID='"+company_id+"' ";

		$.ajax({
		type:"POST",
		url:"apiCommonQuerySingle.php",
		data:{valquery:valquery},
		success:function(response){
			
			console.log(response);
		
		if($.trim(response)=="Success"){
			
				showAlertRedirect("Success","Company Name Updated Successfully","success","pgCompanyProfile.php?id="+valcompany_id);
			
		}else{
			showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
		}
	
		}
	 });
	}
}
//--------------------Edit COmpany Name Ends--------------------------//
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
	  
	  var path = "<?php echo $settingValueUserImagePathVerification; ?>"+email+"/"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		  showAlert("Image File Size is very big","","warning");
		   
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);
							form_data2.append("id",company_id);

						   $.ajax({
							url:"upload.php",
							method:"POST",
							data: form_data2,
							contentType: false,
							cache: false,
							processData: false,
							beforeSend:function(){
							},   
							success:function(data)
							
							{
								console.log(data);
								hdnIDimg=data;
								adddata();
							}
						   });
					}
					
				});
		} 
	  else
	  {
			form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);
            form_data2.append("id",id);
		   $.ajax({
			url:"upload.php",
			method:"POST",
			data: form_data2,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend:function(){
			},   
			success:function(data)
			
			{
				console.log(data);
				hdnIDimg=data;
				adddata();
			}
		   });
	  }		  		 
};

var valcreated_by = "<?php echo $created_by;?>";
var valcreated_on = "<?php echo $cur_date;?>";
var valcreated_ip = "<?php echo $ip_address;?>";
var valcompany_id = "<?php echo $company_id;?>";

function changeCompanyType(id) {		
			var valquery = "Update tw_company_details set CompanyType = '"+id+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valcompany_id+"' ";
			$.ajax({
			type:"POST",
			url:"apiUpdateBasicInfo.php",
			data:{valquery:valquery},
			success:function(response){
				console.log(response);
					if($.trim(response)=="Success"){
					
						showAlertRedirect("Success","Company Type Updated Successfully","success","pgCompanyProfile.php?id="+valcompany_id);
					
				}else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
			} 
		});    	  
}

function adddata(){  
		var valcreated_by = "<?php echo $created_by;?>";
	    var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		
		if(hdnIDimg!=""){
			var valquery = "Update tw_company_details set Company_Logo = '"+hdnIDimg+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where ID = '"+valcreated_by+"' ";

		}
	 	$.ajax({
		type:"POST",
		url:"apiCompanylogo.php",
		data:{valquery:valquery},
		success:function(response){
			//console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Data Updated Successfully","success","pgCompanyProfile.php?id="+valcompany_id);
			}else{
				showAlertRedirect("Error","Something Went Wrong. Please Try After Sometime","pgCompanyProfile.php?id="+valcompany_id);
			}
		}
	});    
  }
  
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