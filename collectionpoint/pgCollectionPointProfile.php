<?php 
session_start();
if(!isset($_SESSION["cp_id"])){
	header("Location:pgCollectionPointLogin.php");
}
$cp_id = $_SESSION["cp_id"];
$Token= time();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
include_once "Qrfunction.php";
$qrfunction=new TwQr();
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["cp_id"];
$Status="";
$ward_name="";

$settingValueCollectionPointImagePathVerification= $commonfunction->getSettingValue("CollectionPointImagePathVerification");
$settingValueCollectionPointImagePathOther= $commonfunction->getSettingValue("CollectionPointImagePathOther");
$settingValueCollectionPointImage= $commonfunction->getSettingValue("Collection Point Image");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");


$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");

$qry = "SELECT collection_point_name,contact_person_name,mobile_number,alternative_mobile_number,email,address_line_1,address_line_2,pincode,city,state,country,reg_number,status,location,collection_point_logo,id,collection_point_type,ward FROM tw_collection_point_master WHERE id = '".$cp_id."' ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$collection_point_name = $decodedJSON->response[0]->collection_point_name;
$contact_person_name = $decodedJSON->response[1]->contact_person_name;
$mobile_number = $decodedJSON->response[2]->mobile_number;
$alternative_mobile_number = $decodedJSON->response[3]->alternative_mobile_number;
$email = $decodedJSON->response[4]->email;
$address_line_1 = $decodedJSON->response[5]->address_line_1;
$address_line_2 = $decodedJSON->response[6]->address_line_2;
$pincode = $decodedJSON->response[7]->pincode;
$city = $decodedJSON->response[8]->city;
$state = $decodedJSON->response[9]->state;
$country = $decodedJSON->response[10]->country;
$reg_number = $decodedJSON->response[11]->reg_number; 
$status = $decodedJSON->response[12]->status; 
$location = $decodedJSON->response[13]->location; 
$collection_point_logo = $decodedJSON->response[14]->collection_point_logo; 
$id = $decodedJSON->response[15]->id; 
$collection_point_type = $decodedJSON->response[16]->collection_point_type; 
$ward = $decodedJSON->response[17]->ward; 


$querycptype="Select collection_point_name as collection_point_type from tw_collection_point_type_master where id='".$collection_point_type."'";
$CollectionPointType=$sign->SelectF($querycptype,'collection_point_type');
//$valqrfunction = $qrfunction->GetQrcode($cp_id,$settingValueCollectionPointImagePathOther);


$QuerySelWard="SELECT id,ward_name FROM tw_ward_master WHERE city_id=(select id from tw_city_master where city_name='".$city."')";
$ValueSelWard = $sign->FunctionOption($QuerySelWard,$ward,'ward_name','id');
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Collection Point Profile</title>
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

<div class="modal fade" id="modalEditLogo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-lock"></i> Collection Point Type</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true">×</span>
			</button>
			
			
		</div>
		<div class="modal-body">
			 <div class="input-group">
				 <div class="input-group">
					<div class="form-group row">
					<?php
							$qryn = "SELECT id,collection_point_name as cptype FROM tw_collection_point_type_master WHERE visibility='true' Order by priority,collection_point_name desc";
							$retValn = $sign->FunctionJSON($qryn);
							$decodedJSONn = json_decode($retValn);
							
							$qryn1="Select count(*) as cnt from tw_collection_point_type_master where visibility='true'";
							$retValn1 = $sign->Select($qryn1);
							
							$count = 0;
							$i = 1;
							$x=$retValn1;
							while($x>=$i){
									
								$collectionpointid = $decodedJSONn->response[$count]->id;
								$count=$count+1;
								$cptype = $decodedJSONn->response[$count]->cptype;
								$count=$count+1;
									
							?>
						
							<div class="col-md-4 mb-4 stretch-card transparent text-center" >
							  <div class="card card-light-blue">
								<div class="card-body center" onclick="changeCPType('<?php echo $collectionpointid; ?>');">
								  <button type="button" class="btn btn-primary btn-rounded btn-icon">
									<i class="ti-home"></i>
								</button>
								  <?php echo $cptype; ?>
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
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Change Collection Point Name</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModalLogin()";>×</span>
			</button>
		</div>
		<div class="modal-body">
					<div class="row">
					  <div class="col-lg-12">
							<div class="form-group">
								<h6 class="card-title"></i>Collection Point Name</h6>
								<input type="text" class="form-control" id="txtCompanyName" value="<?php echo $collection_point_name; ?>" placeholder="Company Name"/>	
							</div>		
					  </div>
					</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalLogin();">Close</button>
			<button type="button" class="btn btn-primary"  onclick="updatecollectionpointname()">Save</button>
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
						<input type="file" accept=".png, .jpg, .jpeg" style="display:none" id="Document_Proof" onchange="deleteimg(<?php echo $created_by; ?>,'<?php echo $collection_point_logo  ?>');"> 
						<a id="OpenImgUpload"><img src="<?php if($collection_point_logo==""){echo $settingValueCollectionPointImagePathOther.$settingValueCollectionPointImage; }else{ echo $settingValueCollectionPointImagePathVerification.$mobile_number."/".$collection_point_logo;}?>" class="img-lg rounded-circle mb-3 pointer-cursor" />  </a>
						<br>
						<?php if($collection_point_logo==""){ 
						 } else{  ?>
						<a><i  onclick="deletecompanyImge(<?php echo $created_by; ?>,'<?php echo $collection_point_logo  ?>');" class="ti-trash pointer-cursor"></i>   </a>
						 <?php  }   ?>
						<br>
						<h1 class="display-4"><?php echo $collection_point_name; ?> <?php if($Status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$VerifiedImage;?>"/> <?php }?></h1>
						<h5><small class="text-muted"><a id="CompName" class="text-primary"  onclick="showModal();"><?php //echo $CompanyType; ?></a></small></h5>
						<!-- ------------Company Name Edit Starts------------------------ -->
						
						<a class="text-info pointer-cursor" onclick="EditCPName()"> <i class="ti-pencil"></i></a>
						
						<!-- ------------Company Name Edit Ends------------------------ -->
					</div>
                </div>
              </div>
			  <br>
			  <!-- ====== REGISTRATION NUMBER VIEW STARTS-->
			  <div class="card"  id="ViewRegnumber">
                <div class="card-body">
					<div class="row">
						<div class="col-sm-10">
							<h4 class="card-title"></i>Registration Number</h4><br><?php if($reg_number==""){echo "----"; }else{ echo $reg_number;}
						?>
						</div>
						<div class="col-sm-2">
							 <a href="javascript:void(0)" onclick="viewregnumber()"><i class="ti-pencil"></i></a>
						</div>
					</div>
                </div>
              </div>  
			<!-- ====== REGISTRATION NUMBER VIEW ENDS-->
			
			
			<!-- ====== REGISTRATION NUMBER EDIT STARTS-->
			<div class="card" id="EditRegnumber">
                <div class="card-body">
					<div class="row">
						<div class="col-sm-10">
							<h4 class="card-title"></i>Registration Number</h4><br>
							<input type="text" class="form-control form-control-sm" id="txtRegnumber" value="<?php echo $reg_number; ?>" placeholder="Registration Number">
						</div>
						<div class="col-sm-2">
							<a href="javascript:void(0)" id="btnUpdateRegnumber" onclick="editregnumber()"><i class="ti-save"></i></a>
						</div>
					</div>
				</div>
            </div> 
			<!-- ====== REGISTRATION NUMBER EDIT ENDS-->
			 <br>
			  <div class="card">
                <div class="card-body text-center">
					
					<img src="<?php //echo $valqrfunction; ?>" width="120px" />
					<br>
					 <a  href="pgShowQrCode.php?<?php $cp_id; ?>" target="_blank" id="btncreate"  />View image <i class="ti-fullscreen"> </i></a>
                </div>
              </div>
            </div>	
            <div class="col-lg-8 col-md-8 grid-margin">
				<div class="card">
					<div class="card-body">
						<div class="form-group row">
							<div class="col-sm-11">
								<h4 class="card-title"><i class="ti-tablet"></i> Address Details</h4>
							</div>
							<div class="col-sm-1">
								 <a href="javascript:void(0)" id="btnAddrecord" onclick="updateaddressinfo()"><i class="ti-save"></i></a>
							</div>
						</div>	
						<hr>					
						<div class="forms-sample">
						<br>
							<div class="form-group row">
								<label for="AddressLine1" class="col-sm-3 col-form-label">Address Line 1<code>*</code></label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" maxlength="100" id="txtAddressLine1" value="<?php echo $address_line_1; ?>" placeholder="Address Line 1">
								</div>
							</div>
							<div class="form-group row">
								<label for="AddressLine2" class="col-sm-3 col-form-label">Address Line 2</label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" maxlength="100" id="txtAddressLine2" value="<?php echo $address_line_2; ?>" placeholder="Address Line 2">
								</div>
							</div>
							<div class="form-group row">
								<label for="Location" class="col-sm-3 col-form-label">Location</label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" maxlength="50" id="txtLocation" value="<?php echo $location; ?>" placeholder="Location">
								</div>
							</div>
							<div class="form-group row">
								<label for="Pincode" class="col-sm-3 col-form-label">Pincode<code>*</code></label>
								<div class="col-sm-9">
									<input type="number" class="form-control form-control-sm" maxlength="6" id="txtPincode" onchange="getPinCodeResi(this.value)" value="<?php echo $pincode; ?>" placeholder="Pincode">
								</div>
							</div>
							<div class="form-group row">
								<label for="City" class="col-sm-3 col-form-label">City<code>*</code></label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" maxlength="20" readonly id="txtCity" value="<?php echo $city; ?>" placeholder="City">
								</div>
							</div>
							<div class="form-group row">
								<label for="State" class="col-sm-3 col-form-label">State<code>*</code></label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" id="txtState" readonly maxlength="20" value="<?php echo $state; ?>" placeholder="State">
								</div>
							</div>
							<div class="form-group row">
								<label for="Country" class="col-sm-3 col-form-label">Country<code>*</code></label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" maxlength="20" readonly id="txtCountry" value="<?php echo $country; ?>" placeholder="Country">
								</div>
							</div>
							<div class="form-group row">
								<label for="Ward" class="col-sm-3 col-form-label" >Ward<code>*</code></label>	
								
								<div class="col-sm-9">									
								 <select id="txtWardName" class="form-control form-control-sm">
									<?php if($ward!=0){echo $ValueSelWard;} else { echo '<option value="Other">Other</option>'; } ?>
								 </select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="card">
					<div class="card-body">
						<div class="form-group row">
							<div class="col-sm-11">
								<h4 class="card-title"><i class="ti-tablet"></i> Contact Details</h4>
							</div>
							<div class="col-sm-1">
								 <a href="javascript:void(0);" id="btnAddrecordUpdateContact" onclick="updatecontactdetails();" ><i class="ti-save"></i></a>
							</div>
						</div>
						<hr>
						<div class="forms-sample">
						<br>
							<div class="form-group row">
								<label for="Value" class="col-sm-3 col-form-label">Contact Person Name<span class="text-danger">*</span></label>
								<div class="col-sm-9">  
									<input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $contact_person_name; ?>" id="txtContactPersonName" placeholder="Contact Person Name" >
								</div>
							</div>
							<div class="form-group row">
								<label for="Value" class="col-sm-3 col-form-label">Mobile Number<span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" disabled maxlength="50" value="<?php echo $mobile_number; ?>" id="txtMobileNumber" placeholder="Value" >
								</div>
							</div>
							<div class="form-group row">
								<label for="Value" class="col-sm-3 col-form-label">Alternative Mobile Number</label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $alternative_mobile_number; ?>" id="txtAltMobileNumber" placeholder="Value" >
								</div>
							</div>
							<div class="form-group row">
								<label for="Value" class="col-sm-3 col-form-label">Email (Optional)</label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $email; ?>" id="txtEmail" placeholder="Value">
								</div>
							</div>
						</div>
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
<script src="../assets/js/custom/twCommonValidation.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>
<script src="../assets/js/toastDemo.js"></script>
<script type='text/javascript'>
var valcreated_by = "<?php echo $created_by;?>";
var valcreated_on = "<?php echo $cur_date;?>";
var valcreated_ip = "<?php echo $ip_address;?>";
var valcpid = "<?php echo $cp_id;?>";
var email="<?php echo $mobile_number; ?>";
var WardName="<?php echo $ward_name; ?>";
var hdnIDimg="";

$(document).ready(function(){
   $("#EditRegnumber").css("display", "none");
}); 
$('input[type="checkbox"]').click(function(){
	if($(this).prop("checked") == true){
		valcheck = "checked";
		enableButton('#btncreate','CREATE ACCOUNT');
	}
	else if($(this).prop("checked") == false){
		valcheck = "";
		disableButton('#btncreate','CREATE ACCOUNT');
	}
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
	var valtext = "Please enter valid " + valplaceholder;
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

function changeCPType(id) {		
			var valquery = "Update tw_collection_point_master set collection_point_type = '"+id+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valcpid+"' ";
			$.ajax({
			type:"POST",
			url:"apiCommonQuerySingle.php",
			data:{valquery:valquery},
			success:function(response){
				console.log(response);
					if($.trim(response)=="Success"){
					
						showAlertRedirect("Success","Data Updated Successfully","success","pgCollectionPointProfile.php?id="+valcpid);
					
				}else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
			} 
		});    	  
}


 
function viewregnumber(){
	 $("#ViewRegnumber").css("display", "none");
	 $("#EditRegnumber").css("display", "block");
}
function editregnumber(){
	 
	 if(!validateBlank($("#txtRegnumber").val())){
		setError("txtRegnumber");
	 }
	 else{
		 
	 var valquery = "update tw_collection_point_master set reg_number='"+$("#txtRegnumber").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valcpid+"'";
	
	
		var buttonHtml = $('#btnUpdateRegnumber').html();
			
			$('#btnUpdateRegnumber').attr("disabled","true");
			
			$('#btnUpdateRegnumber').html('<i class="ti-timer"></i>');
			$.ajax({
				type:"POST",
				url:"apiCommonQuerySingle.php",
				data:{valquery:valquery},
				success:function(response){
					console.log(response);
					$('#btnUpdateRegnumber').removeAttr('disabled');
					
					
					if($.trim(response)=="Success"){
						
						showAlertRedirect("Success","Data Updated Successfully","success","pgCollectionPointProfile.php");
					}
					else{
						showAlert("error","Something Went Wrong. Please Try After Sometime","error");
					}
					
					$('#btnUpdateRegnumber').html(buttonHtml);
				}
			}); 
	 }
	 
}
$('#OpenImgUpload').click(function(){ $('#Document_Proof').trigger('click'); });
function showModal()
{	
	jQuery.noConflict();
	$("#modalEditLogo").modal("show");
}
function closeModal() {
	
  $("#modalEditLogo").modal("hide");
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
	var valquery="Update tw_collection_point_master set collection_point_logo='"+blank+"' where id='"+id+"' ";
		 $.ajax({
				type:"POST",
				url:"apiDeleteImg.php",
				data:{valquery:valquery,email:email,Imagename:name},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
						showConfirmAlert('Confirm action!', 'Are you sure you want to delete the image?','question', function (confirmed){
							if(confirmed==true){
							showAlertRedirect("Success","Collection Point Logo Deleted Successfully","success","pgCollectionPointProfile.php");
							showname();
							}
						});
					}
					else if($.trim(response)=="Exist"){
							showAlert("Warning","Value already exist","warning");
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
					}
				}
			 });

}

function deleteimg(id,name){
	var blank="";
	var Imagename="";
	if(name=="")
	{
		showname();
	}
	else{
	var valquery="Update tw_collection_point_master set collection_point_logo='"+name+"' where id='"+id+"' ";
		 $.ajax({
				type:"POST",
				url:"apiDeleteImg.php",
				data:{valquery:valquery,email:email,Imagename:name},
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

}
//--------------------Edit COmpany Name Starts------------------------//
function EditCPName(){
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


function updatecollectionpointname(){
	if(!validateBlank($("#txtCompanyName").val())){
		setErrorOnBlur("txtCompanyName");	
	}
	else{
		var valquery="Update tw_collection_point_master set collection_point_name='"+$("#txtCompanyName").val()+"' where id='"+valcpid+"' ";
	
		$.ajax({
		type:"POST",
		url:"apiCommonQuerySingle.php",
		data:{valquery:valquery},
		success:function(response){
			
			console.log(response);
		
		if($.trim(response)=="Success"){
			
				showAlertRedirect("Success","CollectionPoint Name Updated Successfully","success","pgCollectionPointProfile.php?id="+valcpid);
			
		}else{
			showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
		}
	
		}
	 });
	}
}
//--------------------Edit COmpany Name Ends--------------------------//
 function showname() {
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	 
	 var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg']) == -1) 
	  {
		  showAlert("Error","Invalid Image File","error");
		  $('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  
	  var path = "../assets/images/Documents/Verification/"+email+"/"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		  showAlert("Image File Size is very big","","warning");
		   
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

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

function adddata(){
	  
		if(hdnIDimg!=""){
			var valquery = "Update tw_collection_point_master set collection_point_logo = '"+hdnIDimg+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valcreated_by+"' ";

		}
	 	$.ajax({
		type:"POST",
		url:"apiCommonQuerySingle.php",
		data:{valquery:valquery},
		success:function(response){
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Collection Point Logo Updated Successfully","success","pgCollectionPointProfile.php");
			}else{
				showAlertRedirect("Something Went Wrong. Please Try After Sometime","success","pgCollectionPointProfile.php");
			}
		}
	});    
  }
//====== Pincode function starts=========//

 function getPinCodeResi(id){
	$.ajax({
		type:"GET",
		url:"https://api.postalpincode.in/pincode/"+id,
		dataType:"JSON",
		data:{},
		success: function(response){
			console.log(response);

if (response["0"]["Status"]=="Success")
	
			{
			
				$("#txtCity").val(response["0"]["PostOffice"]["0"]["Region"]);
				$("#txtState").val(response["0"]["PostOffice"]["0"]["State"]);
				$("#txtCountry").val(response["0"]["PostOffice"]["0"]["Country"]);
				$("#txtCity").attr('readonly', true);
				$("#txtState").attr('readonly', true);
				$("#txtCountry").attr('readonly', true);
				$("#txtPincode").focus();
				citychange($("#txtCity").val(),'Valid');
				
			}
			
			else
			{   
		        $("#txtCity").val("");
				$("#txtCity").removeAttr("disabled");
				$("#txtState").val("");
				$("#txtState").removeAttr("disabled");
				$("#txtCountry").val("");
				$("#txtCountry").removeAttr("disabled");
				$("#txtCity").removeAttr('readonly');
				$("#txtState").removeAttr('readonly');
				$("#txtCountry").removeAttr('readonly');
				$("#txtCity").focus();
				citychange("",'Invalid');
			}
		}
	});
}
//====== Pincode function ends =========//


//====== Address update starts =========//
function updateaddressinfo(){	
	if(!validateBlank($("#txtAddressLine1").val())){
		setError("txtAddressLine1");
	}
	else if(!validateBlank($("#txtLocation").val())){
		setError("txtLocation");
	} 
	else if(!validateBlank($("#txtPincode").val())){
		setError("txtPincode");
	} 
	else{
		var valquery = "update tw_collection_point_master set ward='"+$("#txtWardName").val()+"',address_line_1='"+$("#txtAddressLine1").val()+"', address_line_2='"+$("#txtAddressLine2").val()+"', location='"+$("#txtLocation").val()+"',pincode='"+$("#txtPincode").val()+"',city='"+$("#txtCity").val()+"',state='"+$("#txtState").val()+"',country='"+$("#txtCountry").val()+"',ward='"+$("#txtWardName").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valcpid+"'";
	
		
		var buttonHtml = $('#btnAddrecord').html();
			
			$('#btnAddrecord').attr("disabled","true");
			$('#btnAddrecord').html('<i class="ti-timer"></i>');
			$.ajax({
				type:"POST",
				url:"apiCommonQuerySingle.php",
				data:{valquery:valquery},
				success:function(response){
					console.log(response);
					$('#btnAddrecord').removeAttr('disabled');
					
					if($.trim(response)=="Success"){
						
						showAlertRedirect("Success","Data Updated Successfully","success","pgCollectionPointProfile.php");
					}
					else{
						showAlert("error","Something Went Wrong. Please Try After Sometime","error");
					}
					
					$('#btnAddrecord').html(buttonHtml);
				}
			}); 
	} 
}
//====== Address update ends =========//

//====== Ward select on city change starts =========//
function citychange(cityname,valresponse){
	var cityname= cityname;
	$.ajax({
				type:"POST",
				url:"apiWardName.php",
				data:{cityname:cityname,valresponse:valresponse},
				success:function(response){
					
					console.log(response);
				$("#txtWardName").html(response);
					 if($.trim(response)=="Success"){
						
						$("#txtWardName").html(response);
					}
					else{
						$("#txtWardName").html(response);
					} 
					 
					
				}
			}); 
	
}

//====== Ward select on city change ends =========//
//====== Ward select on city change starts =========//
function citychangeOnload(cityname,valresponse){
	var cityname= cityname;
	$.ajax({
				type:"POST",
				url:"apiWardName.php",
				data:{cityname:cityname,valresponse:valresponse},
				success:function(response){
					
					console.log(response);
				$("#txtWardName").html(response);
					 if($.trim(response)=="Success"){
						
						$("#txtWardName").html(response);
					}
					else{
						$("#txtWardName").html(response);
					} 
					 
					
				}
			}); 
	
}

//====== Ward select on city change ends =========//

//====== Contact update starts =========//
function updatecontactdetails(){	
	if(($("#txtEmail").val()!="")){
		if(!validateEmail($("#txtEmail").val())){
				setError(txtEmail);
		}
		else{
			updateCDdetails();
		}
		}
	else {
		updateCDdetails();
}
}
function updateCDdetails(){

	
	 /* var valquery = "update tw_collection_point_master set contact_person_name = '"+$("#txtContactPersonName").val()+"',alternative_mobile_number='"+$("#txtAltMobileNumber").val()+"', email='"+$("#txtEmail").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valcpid+"'";
	
	 var valquerycount = "select count(*) as cnt from tw_collection_point_master where id!='"+valcpid+"' and email='"+$("#txtEmail").val()+"'";   */
		//alert(valquerycount);
		 var buttonHtml = $('#btnAddrecordUpdateContact').html();
			
			$('#btnAddrecordUpdateContact').attr("disabled","true");
			$('#btnAddrecordUpdateContact').html('<i class="ti-timer"></i>');
			
			$.ajax({
				type:"POST",
				url:"apiupdatecontactdetails.php",
				data:{mobile_number:$("#txtMobileNumber").val(),
					   alt_number:$("#txtAltMobileNumber").val(),
					   email:$("#txtEmail").val(),
					   valcreated_by:valcreated_by,
					   valcreated_on:valcreated_on,
					   valcreated_ip:valcreated_ip
					   },
				success:function(response){
					console.log(response);
					$('#btnAddrecordUpdateContact').removeAttr('disabled');
					
					if($.trim(response)=="Success"){
						
						showAlertRedirect("Success","Data Updated Successfully","success","pgCollectionPointProfile.php");
					}
					else if($.trim(response)=="Exist"){
						
						showAlert("Warning","Email is already Present","warning");
					}
					else{
						showAlert("error","Something Went Wrong. Please Try After Sometime","error");
					}
					
					$('#btnAddrecordUpdateContact').html(buttonHtml);
					
				}
			}); 
	 
		
}

//====== Contact update ends =========//
</script>	
</body>
</html>