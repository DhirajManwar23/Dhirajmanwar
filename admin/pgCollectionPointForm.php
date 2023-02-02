<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];

$cprequesttype = $_REQUEST["type"];
$id = $_REQUEST["id"];
$_SESSION["request_type"] = $cprequesttype;
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["username"];

$CPID="";
$collection_point_name = ""; 
$collection_point_type = "";
$collection_point_logo = "";
$contact_person_name = "";
$mobile_number = "";
$alternative_mobile_number = "";
$email = "";
$ward = "";
$address_line_1 = "";
$address_line_2 = "";
$location = "";
$pincode = "";
$city = "";
$state = "";
$country = "";
$geo_coordinate = "";
$is_registered = "";
$reg_number = "";
$status = "";
$is_registered="";

if($cprequesttype=="edit"){

$qry="SELECT id as CPID,collection_point_name,collection_point_type,collection_point_logo,contact_person_name,mobile_number,alternative_mobile_number,email,ward,address_line_1,address_line_2,location,pincode,city,state,country,geo_coordinate,is_registered,reg_number,id
FROM tw_collection_point_master WHERE id='".$id."'"; 

$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);

$CPID = $decodedJSON->response[0]->CPID; 
$collection_point_name = $decodedJSON->response[1]->collection_point_name; 
$collection_point_type = $decodedJSON->response[2]->collection_point_type;
$collection_point_logo = $decodedJSON->response[3]->collection_point_logo;
$contact_person_name = $decodedJSON->response[4]->contact_person_name;
$mobile_number = $decodedJSON->response[5]->mobile_number;
$alternative_mobile_number = $decodedJSON->response[6]->alternative_mobile_number;
$email = $decodedJSON->response[7]->email;
$ward = $decodedJSON->response[8]->ward;
$address_line_1 = $decodedJSON->response[9]->address_line_1;
$address_line_2 = $decodedJSON->response[10]->address_line_2;
$location = $decodedJSON->response[11]->location;
$pincode = $decodedJSON->response[12]->pincode;
$city = $decodedJSON->response[13]->city;
$state = $decodedJSON->response[14]->state;
$country = $decodedJSON->response[15]->country;
$geo_coordinate = $decodedJSON->response[16]->geo_coordinate;
$is_registered = $decodedJSON->response[17]->is_registered;
$reg_number = $decodedJSON->response[18]->reg_number;

}

$QueryCollectionPointType = "select id,collection_point_name from tw_collection_point_type_master where visibility='true'";
$ValueCollectionPointType = $sign->FunctionOption($QueryCollectionPointType,$collection_point_type,'collection_point_name','id');


$QueryWardType = "select id,ward_name from tw_ward_master where visibility='true' Order by priority,ward_name";
$ValueWardType = $sign->FunctionOption($QueryWardType,$ward,'ward_name','id');

$settingValueCollectionPointImagePathVerification = $commonfunction->getSettingValue("CollectionPointImagePathVerification");
?>

<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |Collection Point Master</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Collection Point Master</h4>
					<br>
					<div class="forms-sample">
					 <div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 grid-margin">
							<div class="form-group">
							  <label for="txtCollectionPointName">Collection Point Name <code>*</code></label>
							  <input type="text" class="form-control" value="<?php echo $collection_point_name; ?>" id="txtCollectionPointName" maxlength="50" placeholder="Collection Point Name " />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 grid-margin">
							<div class="form-group" >
								<label>Collection Point Type<code>*</code></label>
								<select name="department" id="txtCollectionPointType" class="form-control" >
								 <?php echo $ValueCollectionPointType;?>
								</select>
							</div>
						</div>
					 </div>
					 <div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
								<div class="form-group" >
									<label>Collection Point Logo<code>*</code></label>
									<input type="file" class="form-control" value="<?php echo $collection_point_logo; ?>" accept=".png, .jpg, .jpeg, .pdf" id="Document_Proof"  placeholder="Collection Point Logo" onchange="showname();"/>
									 <?php if($cprequesttype=="edit"){ 
											if($collection_point_logo!=""){?>
												<div class="col-sm-4" id="diveditimg">          
<a href="<?php echo $settingValueCollectionPointImagePathVerification; ?><?php echo $mobile_number; ?>/
<?php echo $collection_point_logo;?>" target="_blank">View</a>
												</div>
									<?php } }?>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
								<div class="form-group" >
									<label>Contact Person Name<code>*</code></label>
									<input type="text" class="form-control" value="<?php echo $contact_person_name; ?>" id="txtContactPersonName" maxlength="50" placeholder="Contact Person Name" />
								</div>
							</div>

							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
								<div class="form-group">
									<label>Mobile Number<code>*</code></label>
									<input type="number" class="form-control" value="<?php echo $mobile_number; ?>" id="txtMobile" maxlength="10" placeholder="Mobile Number " />
								</div>
							</div>
						</div>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group">
						<label>Alternative Mobile Number</label>
						<input type="number" class="form-control" value="<?php echo $alternative_mobile_number; ?>" id="txtAlternativeMobile" maxlength="20" placeholder="Alternative Mobile Number" />
						</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group">
						<label>Email</label>
						<input type="int" class="form-control" value="<?php echo $email; ?>" id="txtEmail" maxlength="20" placeholder="Email" />
						</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group" >
						<label>Ward<code>*</code></label>
						<select name="Ward" id="txtWard" class="form-control" >
						 <?php echo $ValueWardType;?>
						</select>
						</div>
						</div>
						</div>
						<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group">
						<label>Address Line 1<code>*</code></label>
						<input type="text" class="form-control" value="<?php echo $address_line_1; ?>" id="txtAddressLine1" maxlength="50" placeholder="Address Line 1" />
						</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group" >
						<label>Address Line 2</label>
						<input type="text" class="form-control" value="<?php echo $address_line_2; ?>" id="txtAddressLine2" maxlength="50" placeholder="Address Line 2" />
						</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group" >
						<label>Location<code>*</code></label>
						<input type="text" class="form-control" value="<?php echo $location; ?>" id="txtLocation" maxlength="50" placeholder="Location" />
						</div>
						</div>
						</div>
						
						
						<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 grid-margin">
						<div class="form-group" >
						<label>Pincode<code>*</code></label>
						<input type="number" class="form-control" onchange="myFunction(this.value)" value="<?php echo $pincode; ?>" id="txtpincode" maxlength="6" placeholder="Pincode" />
						</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 grid-margin">
						<div class="form-group" >
						<label>City<code>*</code></label>
						<input type="text" class="form-control" disabled value="<?php echo $city; ?>" id="txtcity" maxlength="50" placeholder="City" />
						</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 grid-margin">
						<div class="form-group" >
						<label>State<code>*</code></label>
						<input type="text" class="form-control"  disabled value="<?php echo $state; ?>" id="txtstate" maxlength="50" placeholder="State" />
						</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 grid-margin">
						<div class="form-group" >
						<label>Country<code>*</code></label>
						<input type="text" class="form-control"  disabled value="<?php echo $country; ?>" id="txtcountry" maxlength="50" placeholder="Country" />
						</div> 
						</div> 
						</div> 
						
						<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group" >
						<label>Geo Coordinate<code>*</code></label>
						<input type="text" class="form-control" value="<?php echo $geo_coordinate; ?>" id="txtGeoCoordinate" maxlength="50" placeholder="Geo Coordinate" />
						</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group" >
						<label>Is Registered<code>*</code></label>
						<select id="selisregistered" class="form-control">
							<option value="Yes" <?php if($is_registered=="Yes"){ echo "selected";} ?>>Yes</option>
							<option value="No" <?php  if($is_registered=="No") { echo "selected";} ?>>No</option>
						</select>
						</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group"  id="divRegNo">
						<label>Registration Number<code>*</code></label>
						<input type="text" class="form-control" value="<?php echo $reg_number; ?>" id="txtRegistrationNumber" maxlength="50" placeholder="Registration Number" />
						</div>
						</div>
						</div>

						<button type="button" class="btn btn-success" id="btnAddrecord" onclick="addrecord();"><?php if($cprequesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<!-- endinject -->

<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script>
var hdnIDimg="";
var valrequesttype = "<?php echo $cprequesttype;?>";
var valstatus = "<?php echo $status;?>";
var valCPID = "<?php echo $CPID;?>";
var valvalue="<?php echo $is_registered;?>";
$('#selisregistered').change(function(){
    var valvalue = $(this).val();
    if(valvalue=="Yes"){
		$('#divRegNo').css('display','block');
	}
	else{
		$('#divRegNo').css('display','none');
		 if(!validateBlank($("#txtregnumber").val())){
	    setErrorOnBlur("txtregnumber");}
}
}); 
$(document).ready(function(){
	
	 if($('#selisregistered').val()=="Yes"){
		$('#divRegNo').css('display','block');
	}
	else{
		$('#divRegNo').css('display','none');
		 if(!validateBlank($("#txtRegistrationNumber").val())){
	    setErrorOnBlur("txtRegistrationNumber");}
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
$("#txtEmail").blur(function()
{
	removeError(txtEmail);
	if ($("#txtEmail").val()!="")
	{
		if(!validateEmail($("#txtEmail").val())){
			setError(txtEmail);
		}
		else
		{
			removeError(txtEmail);
		}
	}
});
$("#txtMobile").blur(function()
{
	removeError(txtMobile);
	if ($("#txtMobile").val()!="")
	{
		if(!isMobile($("#txtMobile").val())){
			setError(txtMobile);
		}
		else
		{
			removeError(txtMobile);
		}
	}
});


function showname() {
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	 
	 var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['pdf','png','jpg','jpeg']) == -1) 
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
	   
	   $.ajax({
		url:"uploadcollectionpoint.php",
		method:"POST",
		data: form_data2,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend:function(){
			//$('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
		},   
		success:function(data)
		
		{
			console.log(data);
			hdnIDimg=data;
		}
	   });
	  }
		  
		 
};

//====== Pincode function starts=========//

 function myFunction(id){
	$.ajax({
		type:"GET",
		url:"https://api.postalpincode.in/pincode/"+id,
		dataType:"JSON",
		data:{},
		success: function(response){
			console.log(response);

if (response["0"]["Status"]=="Success")
	
			{
			
				$("#txtcity").val(response["0"]["PostOffice"]["0"]["Region"]);
				$("#txtstate").val(response["0"]["PostOffice"]["0"]["State"]);
				$("#txtcountry").val(response["0"]["PostOffice"]["0"]["Country"]);
				$("#txtcity").attr('readonly', true);
				$("#txtstate").attr('readonly', true);
				$("#txtcountry").attr('readonly', true);
				$("#txtpincode").focus();
				//citychange($("#txtcity").val(),'Valid');
				
			}
			
			else
			{   
		        $("#txtcity").val("");
				$("#txtcity").removeAttr("disabled");
				$("#txtstate").val("");
				$("#txtstate").removeAttr("disabled");
				$("#txtcountry").val("");
				$("#txtcountry").removeAttr("disabled");
				$("#txtcity").removeAttr('readonly');
				$("#txtstate").removeAttr('readonly');
				$("#txtcountry").removeAttr('readonly');
				$("#txtcity").focus();
				//citychange("",'Invalid');
			}
		}
	});
}

//====== Pincode function ends =========//

function addrecord(){
	
	if(!validateBlank($("#txtCollectionPointName").val())){
		setErrorOnBlur("txtCollectionPointName");	
	}
	else if(!validateBlank($("#txtCollectionPointType").val())){
		setErrorOnBlur("txtCollectionPointType");
	}
	else if(!validateBlank($("#txtContactPersonName").val())){
		setErrorOnBlur("txtContactPersonName");
	}
	else if(!validateBlank($("#txtMobile").val())){
		setErrorOnBlur("txtMobile");
	}
	else if(!isMobile($("#txtMobile").val())){
		setErrorOnBlur("txtMobile");
	}
	else if(!validateBlank($("#txtAddressLine1").val())){
		setErrorOnBlur("txtAddressLine1");
	}
	else if(!validateBlank($("#txtLocation").val())){
		setErrorOnBlur("txtLocation");
	}
	else if(!validateBlank($("#txtpincode").val())){
		setErrorOnBlur("txtpincode");
	}
	else if(!validateBlank($("#txtcity").val())){
		setErrorOnBlur("txtcity");
	}
	else if(!validateBlank($("#txtstate").val())){
		setErrorOnBlur("txtstate");
	}
	else if(!validateBlank($("#txtcountry").val())){
		setErrorOnBlur("txtcountry");
	}
	else if(!validateBlank($("#txtGeoCoordinate").val())){
		setErrorOnBlur("txtGeoCoordinate");
	}
	
	else if(!validateBlank($("#selisregistered").val())){
		setErrorOnBlur("selisregistered");
	}
	else if($('#selisregistered').val()=='Yes' && !validateBlank($("#txtRegistrationNumber").val())){
	setErrorOnBlur("txtRegistrationNumber");
	}
	else{
	
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');

		$.ajax({
			type:"POST",
			url:"apiAddColletionPoint.php",
			data:{valCPID:valCPID,
				collection_point_name : $("#txtCollectionPointName").val(),
				collection_point_type : $("#txtCollectionPointType").val(),
				collection_point_logo : hdnIDimg,
				contact_person_name : $("#txtContactPersonName").val(),
				mobile_number : $("#txtMobile").val(),
				alternative_mobile_number : $("#txtAlternativeMobile").val(),
				email : $("#txtEmail").val(),
				ward : $("#txtWard").val(),
				address_line_1 : $("#txtAddressLine1").val(),
				address_line_2 : $("#txtAddressLine2").val(),
				location : $("#txtLocation").val(),
				pincode : $("#txtpincode").val(),
				city : $("#txtcity").val(),
				state : $("#txtstate").val(),
				country : $("#txtcountry").val(),
				geo_coordinate : $("#txtGeoCoordinate").val(),
				is_registered : $("#selisregistered").val(),
				reg_number : $("#txtRegistrationNumber").val(),
				valstatus:valstatus},
			
				success:function(response){
				console.log(response); 
				if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
				}
				else{
					enableButton('#btnAddrecord','Update Record');
				}	
				if($.trim(response)=="Success"){
					console.log(response);
					if(valrequesttype=="add"){
						$('#btnAddrecord').html('Add Record');
						showAlertRedirect("Success","Data Added Successfully","success","pgCollectionPoint.php");
						}
					else{
						$('#btnAddrecord').html('Update Record');
 						showAlertRedirect("Success","Data Updated Successfully","success","pgCollectionPoint.php");
					}
					$(hdnIDimg).val("");
					$("#txtCollectionPointName").val("");
					$("#txtCollectionPointType").val("");
					$("#txtContactPersonName").val("");
					$("#txtMobile").val("");
					$("#txtAddressLine1").val("");
					$("#txtLocation").val("");
					$("#txtpincode").val("");
					$("#txtGeoCoordinate").val("");
					$("#selisregistered").val(),
					$("#txtRegistrationNumber").val("");
				}
				else if($.trim(response)=="ExistMobile"){
					showAlert("Warning","Mobile already exist","warning");
					
				}
				else if($.trim(response)=="ExistEmail"){
					showAlert("Warning","Email already exist","warning");
					
				}
				
				else{
					showAlert("Error","Something Went Wrong","error");
					
				} 
			}
		});
	 
	}
}
	
 </script>
</body>
</html>