<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$settingValueCompanyImage = $commonfunction->getSettingValue("Company Image");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValuePrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValuePrimaryContact= $commonfunction->getSettingValue("Primary Contact");
$settingValueEmployeeImage= $commonfunction->getSettingValue("Employee Image");
$settingValueEmployeeImagePathOther = $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");

$verifyStatus=$commonfunction->getSettingValue("Verified Status");
$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
$ComplianceImage=$commonfunction->getCommonDataValue("Compliance Image");
$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");

$userid = $_SESSION["companyusername"];
$company_id=$_SESSION["company_id"];
$twcompany_id = $_REQUEST["id"];

$qry = "SELECT cd.CompanyName,cd.Status,ctm.company_type,cd.Company_Logo,cd.Description,cd.compliance_status FROM tw_company_details cd LEFT JOIN tw_company_type_master ctm ON cd.CompanyType = ctm.id WHERE cd.ID = '".$twcompany_id."' ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$companyStatus = $decodedJSON->response[1]->Status;
$CompanyType = $decodedJSON->response[2]->company_type; 
$Company_Logo = $decodedJSON->response[3]->Company_Logo;
$Description = $decodedJSON->response[4]->Description;
$compliance_status = $decodedJSON->response[5]->compliance_status;

$scLogo=$Company_Logo;

$qryEmail = "SELECT value FROM tw_company_contact WHERE public_visible='true' and contact_field='".$settingValuePrimaryEmail."' and company_id='".$twcompany_id."'";
$retValEmail = $sign->FunctionJSON($qryEmail);
$decodedJSONEmail = json_decode($retValEmail);
$companyEmail = $decodedJSONEmail->response[0]->value;
 
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Search Profile</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/vendors/jquery-toast-plugin/jquery.toast.min.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-4 col-md-4 grid-margin">
              <div class="card">
                <div class="card-body">
					<div style="text-align:center;">
						<img src="<?php if($scLogo==""){echo $UserImagePathOther.$settingValueCompanyImage;}else{echo $settingValueUserImagePathVerification.$companyEmail."/".$scLogo;}?>" class="img-lg rounded-circle mb-3" />
						<br><br>
						<h1 class="display-4">
						<?php echo $CompanyName; ?><?php if($companyStatus==$verifyStatus){?><img src="<?php echo $UserImagePathOther.$VerifiedImage;?>" data-toggle="tooltip" data-placement="top" title="This is a verified company"/><?php }?><?php if($compliance_status==$verifyStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$ComplianceImage;?>" data-toggle="tooltip" data-placement="top" title="This is a compliant company"/> <?php }?></small></h5></h1>
						<h5<small class="text-muted"><?php echo $CompanyType; ?>
						<br>
						<br>
						<?php 
					     $qry7="select count(*) as cnt from tw_company_network where (receiver_id='".$twcompany_id."' and sender_id='".$company_id."') or (sender_id='".$twcompany_id."' and receiver_id='".$company_id."')";
					     $status=$sign->Select($qry7);
						$flag=true;
						if($status==0) { ?>
						<button type="button" class="btn btn-primary" id="invitation" onclick="invite(<?php echo $twcompany_id;?>);">
							  <i class="ti-user"></i>                      
							 Invite to connect
						</button>
					<?php	}
						?>
					</div>
                </div>
              </div>
			  <br>
			  <div class="card">
                <div class="card-body">
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
							 <i class="ti-mobile"></i>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
							
							<?php
								$qry2 = "SELECT cc.value, cc.status,cc.id,cc.contact_field ,cfm.contact_type FROM tw_company_contact cc LEFT JOIN tw_contact_field_master cfm ON cc.contact_field = cfm.id WHERE cc.company_id = '".$twcompany_id."' and ( cc.contact_field='".$settingValuePrimaryEmail."' or cc.contact_field='".$settingValuePrimaryContact."')";
								$retVal2 = $sign->FunctionJSON($qry2);
								$decodedJSON2 = json_decode($retVal2);
								
								$qry3="Select count(*) as cnt from tw_company_contact where company_id = '".$twcompany_id."' and ( contact_field='".$settingValuePrimaryEmail."' or contact_field='".$settingValuePrimaryContact."')";
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
										$contact_field  = $decodedJSON2->response[$count]->contact_field ;
										$count=$count+1;
										$contact_type  = $decodedJSON2->response[$count]->contact_type ;
										$count=$count+1;
										
									
							?>
							<b><?php echo $contact_type; ?></b><br>
							
							<p class="card-description"><?php echo $value; ?><br>
								<?php if($status==$settingValuePendingStatus){ ?>
								
								<?php }else{?>
								
								<?php }?>
								
							</p>
					<hr>
					 <?php 
								$i=$i+1;
						}
						if($i==2){?>
							<b>Primary Contact</b><br>
							
							<p class="card-description">---<br>
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
								$qry2 = "SELECT cc.id,cc.employee_name, cc.employee_photo ,cfm.department_name,td.designation_value  FROM tw_employee_registration cc LEFT JOIN tw_department_master cfm ON cc.employee_department = cfm.id LEFT JOIN tw_designation_master td
ON 								cc.employee_designation = td.id WHERE cc.company_id = '".$twcompany_id."'";
								$retVal2 = $sign->FunctionJSON($qry2);
								$decodedJSON2 = json_decode($retVal2);
								
								$qry3="Select count(*) as cnt from tw_employee_registration where company_id = '".$twcompany_id."'";
								$retVal3 = $sign->Select($qry3);
								
								$count = 0;
								$i = 1;
								$x=$retVal3;
								while($x>=$i){
										
										$id = $decodedJSON2->response[$count]->id;
										$count=$count+1;
										$employee_name = $decodedJSON2->response[$count]->employee_name;
										$count=$count+1;
										$employee_photo = $decodedJSON2->response[$count]->employee_photo;
										$count=$count+1;
										$department_name = $decodedJSON2->response[$count]->department_name;
										$count=$count+1;
										$designation_value  = $decodedJSON2->response[$count]->designation_value ;
										$count=$count+1;
										
									    $qryn = "SELECT value FROM tw_employee_contact where  employee_id='".$id."' and contact_field='".$settingValuePrimaryEmail."'";
										$retValn = $sign->SelectF($qryn,"value");
										
									
							?>
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3">
							<img src="<?php if($employee_photo==""){echo $settingValueEmployeeImagePathOther.$settingValueEmployeeImage; }else{ echo $settingValueEmployeeImagePathVerification.$retValn."/".$employee_photo;}?>" width="100%" />
						</div>
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 col-9">
							<b><?php echo $employee_name; ?></b><br>
							<p class="card-description"><?php echo $department_name." | ".$designation_value; ?></p>
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
						<h4 class="card-title"><i class="ti-user"></i> Description</h4>
						<hr>
						<p><?php echo $Description;  ?></p>
					</div>
				</div>
				<br>
            <div class="card">
                <div class="card-body">
					<h4 class="card-title"><i class="ti-user"></i> Address Info</h4>
					<hr>
					   <?php
						$qry5 = "SELECT ta.id,ta.location,ta.city,ta.pincode,ta.state,atm.address_type_value,atm.address_icon,ta.default_address FROM tw_company_address ta LEFT JOIN tw_address_type_master atm ON ta.address_type  = atm.id WHERE company_id = '".$twcompany_id."' AND public_visible='true'";
						$retVal5 = $sign->FunctionJSON($qry5);
						$decodedJSON5 = json_decode($retVal5);
						
						$qry5="Select count(*) as cnt from tw_company_address where company_id = '".$twcompany_id."' AND public_visible='true'";
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
							<strong><?php echo $address_type_value;?></strong><?php if($default_address=="true") { ?><i class="ti-bookmark"></i><?php } ?><br>
							 <p><?php echo $location;?> | <?php echo $city; ?> | <?php echo $pincode;?> | <?php echo $state; ?></p>
						</div>
						
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-1">
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
					<h4 class="card-title"><i class="ti-write"></i> Contact Details</h4>
					<hr>
					 <?php
						$qry2 = "SELECT cc.id,cc.value,cc.status,ctm.contact_type,ctm.contact_icon  FROM tw_company_contact cc LEFT JOIN tw_contact_field_master ctm ON cc.contact_field = ctm.id WHERE company_id = '".$twcompany_id."' and cc.public_visible='true' and ( cc.contact_field!='".$settingValuePrimaryEmail."' and cc.contact_field!='".$settingValuePrimaryEmail."' )";
						$retVal2 = $sign->FunctionJSON($qry2);
						$decodedJSON2 = json_decode($retVal2);
						
						$qry3="Select count(*) as cnt from tw_company_contact where company_id = '".$twcompany_id."' and public_visible='true' and (contact_field!='".$settingValuePrimaryEmail."' and contact_field!='".$settingValuePrimaryEmail."')";
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
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
								<a href="pgAddContactDetails.php"></a>
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
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>
<script src="../assets/js/toastDemo.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>
var hdnIDimg="";
$('#OpenImgUpload').click(function(){ $('#Document_Proof').trigger('click'); });

function invite(receiver){
	    disableButton('#invitation','<i class="ti-timer"></i> Processing...');
	 var sender_id= "<?php echo $company_id; ?>";
	 var type= "<?php echo $CompanyType; ?>";
	 var CompanyName= "<?php echo $CompanyName; ?>";
	 var CompanyEmail= "<?php echo $companyEmail; ?>";
	$.ajax({
		type:"POST",
			url:"invite.php",
			data:{reciver:receiver,sender:sender_id,companyType:type,CompanyName:CompanyName,CompanyEmail:CompanyEmail},
			success:function(response){
			console.log(response);
			enableButton('#invitation','Invite to connect');
			 if($.trim(response)=="Success"){
				$("#invitation").remove();
				showAlert("Success","Invitation Send","success");
			   }
				else if($.trim(response)=="Exist"){
				showAlert("Warning","Invitation aleady send","warning");
			  }
				else{
					showAlert("Error","Something went wrong","error");
					
				} 
				
			}
	});  
}
</script>	
</body>
</html>