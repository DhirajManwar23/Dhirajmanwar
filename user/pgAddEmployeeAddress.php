<?php 
session_start();
	if(!isset($_SESSION["companyusername"])){
		header("Location:pgLogin.php");
	}
	require "function.php";
	require "commonFunctions.php";
	$sign=new Signup();
	
	$requesttype = ($sign->escapeString($_REQUEST["type"]));
	$_SESSION["requesttype"] = $requesttype;
	$requestid = ($sign->escapeString($_REQUEST["id"]));
    $employee_id=$_SESSION['employee_id'];
	$commonfunction=new Common();
    $ip_address= $commonfunction->getIPAddress();
	date_default_timezone_set("Asia/Kolkata");
	$cur_date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["employee_id"];
	// Include class definition
	
	
	$address_line1 = "";
	$address_line2 = "";
	$location = "";
	$pincode = ""; 
	$city = "";
	$state = "";
	$country = "";
	$google_map = "";
	$public_visible = "";
	$default_address = "";
	$address_type = "";
	$Status = "";
	$cnt=0;
	$isFirst="no";
	
	if($requesttype=="add"){
		$queryinitialcount = "Select count(*) as cnt from tw_employee_address where employee_id = '".$employee_id."'";
		$cnt = $sign->Select($queryinitialcount);
			if($cnt == 0){
				$isFirst="yes";
			}

		$qry1 = "select id,address_type_value from tw_address_type_master where visibility = 'true' ORDER by priority, address_type_value  ASC";
		$retVal1 = $sign->FunctionOption($qry1,$Status,'address_type_value','id');
	
	}
	else{
			$qry = "SELECT address_line1,address_line2,location,pincode,city,state,country,google_map,public_visible,default_address,address_type FROM tw_employee_address WHERE id = '".$requestid."' ";
			$retVal = $sign->FunctionJSON($qry);
			$decodedJSON = json_decode($retVal);
			$address_line1 = $decodedJSON->response[0]->address_line1;
			$address_line2 = $decodedJSON->response[1]->address_line2; 
			$location = $decodedJSON->response[2]->location; 
			$pincode = $decodedJSON->response[3]->pincode; 
			$city = $decodedJSON->response[4]->city; 
			$state = $decodedJSON->response[5]->state; 
			$country = $decodedJSON->response[6]->country;
			$google_map = $decodedJSON->response[7]->google_map;
			$public_visible = $decodedJSON->response[8]->public_visible;
			$default_address = $decodedJSON->response[9]->default_address;
			$address_type = $decodedJSON->response[10]->address_type;
			
			$qry3 = "select id,address_type_value from tw_address_type_master where visibility = 'true' ORDER by priority, address_type_value  ASC";
			$retVal1 = $sign->FunctionOption($qry3,$address_type,'address_type_value','id');
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Address Info</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <!-- endinject -->
  <!-- tw-css:start -->
  <link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Contact Details</h4>
                  <div class="forms-sample">
                   <div class="form-group row">
					  <label for="ContactField" class="col-sm-3 col-form-label">Address Type <code>*</code></label>
					  <div class="col-sm-9">
						<select id="selAddresType" class="form-control form-control-sm">
							<?php echo $retVal1; ?>
						</select>
					  </div>
                    </div>
                    <div class="form-group row">
                      <label for="AddressLine1" class="col-sm-3 col-form-label">Address Line 1 <code>*</code></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="100" id="txtAddressLine1" value="<?php echo $address_line1; ?>" placeholder="Address Line 1">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="AddressLine2" class="col-sm-3 col-form-label">Address Line 2 <code>*</code></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="100" id="txtAddressLine2" value="<?php echo $address_line2; ?>" placeholder="Address Line 2">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Location" class="col-sm-3 col-form-label">Location <code>*</code></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="20" id="txtLocation" value="<?php echo $location; ?>" placeholder="Location">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Pincode" class="col-sm-3 col-form-label">Pincode <code>*</code></label>
                      <div class="col-sm-9">
                        <input type="number" class="form-control form-control-sm" maxlength="6" id="txtPincode" onchange="myFunction(this.value)" value="<?php echo $pincode; ?>" placeholder="Pincode">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="City" class="col-sm-3 col-form-label">City <code>*</code></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="20" disabled id="txtCity" value="<?php echo $city; ?>" placeholder="City">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="State" class="col-sm-3 col-form-label">State <code>*</code></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="txtState" disabled maxlength="20" value="<?php echo $state; ?>" placeholder="State">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Country" class="col-sm-3 col-form-label">Country <code>*</code></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="20" disabled id="txtCountry" value="<?php echo $country; ?>" placeholder="Country">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="GoogleMap" class="col-sm-3 col-form-label">Google Map (Optional)</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="1000" id="txtGoogleMap" value="<?php echo $google_map; ?>" placeholder="Google Map">
                      </div>
                    </div>                   
					<div class="form-group row">
						<label for="chkVisibility" class="col-sm-3 col-form-label">Public Visible (Optional)</label><br>
					    <div class="col-sm-9">
							<label class="switch">
							<input type="checkbox" id="chkVisibility" <?php if ($public_visible=="true") { echo "checked"; } ?>/>
							<span class="slider round"></span>
							</label>						
						</div>
                    </div>
						
					<div class="form-group row">
							<label for="chkDeafult" class="col-sm-3 col-form-label">Default Address</label><br>
							<div class="col-sm-9">
								<label class="switch">
								<input type="checkbox" onchange="setPublicVisibility()" id="chkDeafult"   <?php  if ($default_address=="true"){ echo "checked";  echo " disabled"; } ?>/>
								<span class="slider round"></span>
								</label>						
							</div>
						</div>
                    <button type="submit" class="btn btn-success mr-2" id="btncreate" onclick="adddata()"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
  <script src="../assets/js/custom/twCommonValidation.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
  <!-- endinject -->
</body>
<script src="../assets/css/jquery/jquery.min.js"></script>
 <script>
var valcheck = "";
var valpgName = "";
var valaction = "";
var valdata = "";
var valresult = "";
var valstatus = "";
function setPublicVisibility(){
	
	if (document.getElementById('chkDeafult').checked) {
      		document.getElementById("chkVisibility").checked = true;
			
  	}
}

function setDefaultVisibility(){
	
	if (document.getElementById('chkVisibility').checked) {
      		document.getElementById("chkDeafult").checked = "";
  	}
	else{
		document.getElementById("chkDeafult").checked = false;
		}
	
}
$(document).ready(function(){
	employeeLogs(valpgName,valaction,valdata,valresult,valstatus);
	$("swal-overlay swal-overlay--show-modal").attr("style", "display:none");
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

$("#txtPriority").blur(function()
{
	removeError(txtPriority);
	if ($("#txtPriority").val()!="")
	{
		if(!isNumber($("#txtPriority").val())){
			setError(txtPriority);
		}
		else
		{
			removeError(txtPriority);
		}
	}
});
var valrequesttype = "<?php echo $requesttype;?>";


  function adddata(){
	  var buttonHtml = $('#btncreate').html();
	        if(!validateBlank($("#txtAddressLine1").val())){
			setErrorOnBlur("txtAddressLine1");
			}
			else if(!validateBlank($("#txtLocation").val())){
			setErrorOnBlur("#txtLocation");
			}
			else if(!validateBlank($("#txtPincode").val())){
			setErrorOnBlur("#txtPincode");
			}
			else{
				 if($('#chkDeafult').prop('checked'))
			  {
			 	var valemployee_id = "<?php echo $employee_id;?>";
			  //--Default Address
			  var valquery = "Update tw_employee_address set default_address='false' where employee_id='"+valemployee_id+"'";
			  $.ajax({
				type:"POST",
				url:"apiCommonQuerySingle.php",
				data:{valquery:valquery},
				success:function(response){
					console.log(response);
						if($.trim(response)=="Success"){
							addAddress();
						}else{
							showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
						}
					} 
				});
			  //--
			}
			  else
			  {
				  addAddress();
			  }
  }
  }
function addAddress() {
	var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valrequesttype = "<?php echo $requesttype;?>";
			var valemployee_id = "<?php echo $employee_id;?>";
			var varDefaultAddress = $('#chkDeafult').prop('checked');
			var isFirst = "<?php echo $isFirst;?>";
			
			if (isFirst=="yes")
			{
				varDefaultAddress="true";
			}
			
			
			if(valrequesttype=="add"){
				var valquery = "insert into tw_employee_address(employee_id,address_type,address_line1,address_line2,location,pincode,city,state,country,google_map,public_visible,default_address,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#selAddresType").val()+"','"+$("#txtAddressLine1").val()+"','"+$("#txtAddressLine2").val()+"','"+$("#txtLocation").val()+"','"+$("#txtPincode").val()+"','"+$("#txtCity").val()+"','"+$("#txtState").val()+"','"+$("#txtCountry").val()+"','"+$("#txtGoogleMap").val()+"','"+$('#chkVisibility').prop('checked')+"','"+varDefaultAddress+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				
			}
			else{
				var valrequestid = "<?php echo $requestid;?>";
				var valquery = "Update tw_employee_address set address_type = '"+$("#selAddresType").val()+"' , address_line1 = '"+$("#txtAddressLine1").val()+"', address_line2 = '"+$("#txtAddressLine2").val()+"', location = '"+$("#txtLocation").val()+"', pincode = '"+$("#txtPincode").val()+"', city = '"+$("#txtCity").val()+"', state = '"+$("#txtState").val()+"', country = '"+$("#txtCountry").val()+"', google_map = '"+$("#txtGoogleMap").val()+"', public_visible = '"+$('#chkVisibility').prop('checked')+"', default_address = '"+$('#chkDeafult').prop('checked')+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
			}
			disableButton('#btncreate','<i class="ti-timer"></i> Processing');
		  
			$.ajax({
			type:"POST",
			url:"apiCommonQuerySingle.php",
			data:{valquery:valquery},
			success:function(response){
				console.log(response);				
					if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Data Added Successfully","success","pgEmployeeProfile.php?id="+valemployee_id);
							enableButton('#btncreate','Add');
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgEmployeeProfile.php?id="+valemployee_id);
						 enableButton('#btncreate','Update'); 
					}
				}else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
			} 
		}); 
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
 
 </script>
 
</html>