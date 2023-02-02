<?php 
session_start();
if(!isset($_SESSION["agent_id"])){
	header("Location:pgAgentLogin.php");
}
$agent_id = $_SESSION["agent_id"];
$Token= time();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["agent_id"];

$settingValueAgentImagePathOther=$commonfunction->getSettingValue("AgentImagePathOther");
$settingValueAgentImagePathVerification= $commonfunction->getSettingValue("AgentImagePathVerification");
$settingValueAgentImage= $commonfunction->getSettingValue("Agent Image");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");

$qry = "SELECT agent_name,agent_photo,mobilenumber,alternative_mobilenumber,email,address_line_1,address_line_2,location,pincode,city,state,country,status,id FROM tw_agent_details WHERE id = '".$agent_id."' ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$agent_name = $decodedJSON->response[0]->agent_name;
$agent_photo = $decodedJSON->response[1]->agent_photo;
$mobile_number = $decodedJSON->response[2]->mobilenumber;
$alternative_mobile_number = $decodedJSON->response[3]->alternative_mobilenumber;
$email = $decodedJSON->response[4]->email;
$address_line_1 = $decodedJSON->response[5]->address_line_1;
$address_line_2 = $decodedJSON->response[6]->address_line_2;
$location = $decodedJSON->response[7]->location;
$pincode = $decodedJSON->response[8]->pincode;
$city = $decodedJSON->response[9]->city;
$state = $decodedJSON->response[10]->state;
$country = $decodedJSON->response[11]->country;
$status = $decodedJSON->response[12]->status; 


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
	
</div>
  <!-- ==============MODAL END ================= -->
	  
	  
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-4 col-md-4 grid-margin">
              <div class="card">
                <div class="card-body">
					<div style="text-align:center;">
						<input type="file" accept=".png, .jpg, .jpeg" style="display:none" id="Document_Proof" onchange="showname();"/> 
						<a id="OpenImgUpload"><img src="<?php if($agent_photo==""){echo $settingValueAgentImagePathOther.$settingValueAgentImage; }else{ echo $settingValueAgentImagePathVerification.$mobile_number."/".$agent_photo;}?>" class="img-lg rounded-circle mb-3" /></a>
						
						<br><br>
						<h1 class="display-4"><?php echo $agent_name; ?> <?php if($status==$settingValueVerifiedStatus){?><img src="<?php echo $settingValueAgentImagePathOther.$VerifiedImage;?>"/> <?php }?></h1>
						<h5><small class="text-muted"><a class="text-primary" onclick="showModal();"><?php //echo $WardName; ?></a></small></h5>
					</div>
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
								<a href="javascript:void(0)" id="btnAddAddress" onclick="updateaddressinfo()"><i class="ti-save"></i></a>
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
									<input type="number" class="form-control form-control-sm" maxlength="6" id="txtPincode" onchange="myFunction(this.value)" value="<?php echo $pincode; ?>" placeholder="Pincode">
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
								<a href="javascript:void(0)" id="btnAddrecord" onclick="updatecontactdetails()"><i class="ti-save"></i></a>
							</div>
						</div>
						<hr>
						<div class="forms-sample">
						<br>
							<div class="form-group row">
								<label for="Value" class="col-sm-3 col-form-label">Agent Name<span class="text-danger">*</span></label>
								<div class="col-sm-9">  
									<input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $agent_name; ?>" id="txtContactPersonName" placeholder="Contact Person Name" >
								</div>
							</div>
							<div class="form-group row">
								<label for="Value" class="col-sm-3 col-form-label">Mobile Number<span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $mobile_number; ?>" id="txtMobileNumber" placeholder="Value" >
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
									<input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $email; ?>" id="txtEmail" placeholder="Value" >
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
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>
<script src="../assets/js/toastDemo.js"></script>
<script type='text/javascript'>
var valcreated_by = "<?php echo $created_by;?>";
var valcreated_on = "<?php echo $cur_date;?>";
var valcreated_ip = "<?php echo $ip_address;?>";
var valagentid = "<?php echo $agent_id;?>";
var email="<?php echo $mobile_number; ?>";
var hdnIDimg="";
$('#OpenImgUpload').click(function(){ $('#Document_Proof').trigger('click'); });
function showModal()
{	
	jQuery.noConflict();
	$("#modalEditLogo").modal("show");
	//CopyPassword();
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
function myFunction(val) {
	getPinCodeResi($("#txtPincode").val());
}
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
			}
		}
	});
}
function showname() {
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
			var valquery = "Update tw_agent_details set agent_photo = '"+hdnIDimg+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valcreated_by+"' ";
			
		}
	 	$.ajax({
		type:"POST",
		url:"apiCommonQuerySingle.php",
		data:{valquery:valquery},
		success:function(response){
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Data Updated Successfully","success","pgAgentProfile.php");
			}else{
				showAlertRedirect("Something Went Wrong. Please Try After Sometime","success","pgAgentProfile.php");
			}
		}
	});    
  }


function updateaddressinfo(){	
	
		var valquery = "update tw_agent_details set address_line_1='"+$("#txtAddressLine1").val()+"', address_line_2='"+$("#txtAddressLine2").val()+"',location='"+$("#txtLocation").val()+"',pincode='"+$("#txtPincode").val()+"',city='"+$("#txtCity").val()+"',state='"+$("#txtState").val()+"',country='"+$("#txtCountry").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valagentid+"'";
	
		
		var buttonHtml = $('#btnAddAddress').html();
			$('#btnAddAddress').attr("disabled","true");
			$('#btnAddAddress').html('<i class="ti-timer"></i>');
			$.ajax({
				type:"POST",
				url:"apiCommonQuerySingle.php",
				data:{valquery:valquery},
				success:function(response){
					console.log(response);
					$('#btnAddAddress').removeAttr('disabled');
					
					if($.trim(response)=="Success"){
						
							showAlertRedirect("Success","Data Updated Successfully","success","pgAgentProfile.php");
					}
					else{
						showAlert("error","Something Went Wrong. Please Try After Sometime","error");
					}
					
					$('#btnAddAddress').html(buttonHtml);
				}
			}); 
}
function updatecontactdetails(){	
	
		var valquery = "update tw_agent_details set  mobilenumber='"+$("#txtMobileNumber").val()+"', alternative_mobilenumber='"+$("#txtAltMobileNumber").val()+"',email='"+$("#txtEmail").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valagentid+"'";
		
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
						
							showAlertRedirect("Success","Data Updated Successfully","success","pgAgentProfile.php");
					
					}
					else{
						showAlert("error","Something Went Wrong. Please Try After Sometime","error");
					}
					
					$('#btnAddrecord').html(buttonHtml);
				}
			}); 
}
	
</script>	
</body>
</html>