<?php 
session_start();
	if(!isset($_SESSION["employeeusername"])){
		header("Location:pgLogin.php");
	}
	
     $employee_id= $_SESSION["employee_id"];
	//echo $company_id;
	// Include class definition
	require "function.php";
	$sign=new Signup();
	$qry = "SELECT address_line_1,address_line_2,location,pincode,city,state,country FROM tw_employee_registration WHERE id = '".$employee_id."' ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$AddressLine1 = $decodedJSON->response[0]->address_line_1;
	$AddressLine2 = $decodedJSON->response[1]->address_line_2; 
	$Location = $decodedJSON->response[2]->location; 
	$Pincode = $decodedJSON->response[3]->pincode; 
	$City = $decodedJSON->response[4]->city; 
	$State = $decodedJSON->response[5]->state; 
	$Country = $decodedJSON->response[6]->country;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Basic Info</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
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
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Basic Info</h4>
                  <div class="forms-sample">
                    <div class="form-group row">
                      <label for="AddressLine1" class="col-sm-3 col-form-label">Address Line 1</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="100" id="txtAddressLine1" value="<?php echo $AddressLine1; ?>" placeholder="Address Line 1">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="AddressLine2" class="col-sm-3 col-form-label">Address Line 2</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="100" id="txtAddressLine2" value="<?php echo $AddressLine2; ?>" placeholder="Address Line 2">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Location" class="col-sm-3 col-form-label">Location</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="20" id="txtLocation" value="<?php echo $Location; ?>" placeholder="Location">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Pincode" class="col-sm-3 col-form-label">Pincode</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="6" id="txtPincode" onblur="myFunction(this.value)" value="<?php echo $Pincode; ?>" placeholder="Pincode">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="City" class="col-sm-3 col-form-label">City</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="20" disabled id="txtCity" value="<?php echo $City; ?>" placeholder="City">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="State" class="col-sm-3 col-form-label">State</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="txtState" disabled maxlength="20" value="<?php echo $State; ?>" placeholder="State">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Country" class="col-sm-3 col-form-label">Country</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="20" disabled id="txtCountry" value="<?php echo $Country; ?>" placeholder="Country">
                      </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2" onclick="update()">Update</button>
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
</body>
<script src="../assets/css/jquery/jquery.min.js"></script>

 <script>
 $(document).ready(function(){
   
});

 $('input').blur(function()
{
		
	var valplaceholder = $(this).attr("placeholder");
	var vallblid = $(this).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $(this).val();
	if(check == '')
	{
		$(this).parent().addClass('has-danger');
		$(this).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');	
	}
	else
	{
		$(this).parent().removeClass('has-danger');  
		$("#"+valid+"").fadeOut(); 
	}
});
function myFunction(val) {
	getPinCodeResi($("#txtPincode").val());
}
  function update(){
		$.ajax({
		type:"POST",
		url:"apiUpdateBasicInfo.php",
		data:{AddressLine1:$("#txtAddressLine1").val(),AddressLine2:$("#txtAddressLine2").val(),Pincode:$("#txtPincode").val(),Location:$("#txtLocation").val(),City:$("#txtCity").val(),State:$("#txtState").val(),Country:$("#txtCountry").val()},
		success:function(response){
			//console.log(response);
			if($.trim(response)=="Success"){
				alertBox('Data Updated Successfully');
				window.location.href="pgEmployeeProfile.php";
			}else{
				alertBox('Something Went Wrong. Please Try After Sometime');
			}
		}
	}); 
  }
 function getPinCodeResi(id){
	$.ajax({
		type:"GET",
		url:"https://api.postalpincode.in/pincode/"+id,
		dataType:"JSON",
		data:{},
		success: function(response){
			console.log(response);
			if (response["0"]["Message"]!="No records found")
			{
				$("#txtCity").val(response["0"]["PostOffice"]["0"]["Region"]);
				$("#txtState").val(response["0"]["PostOffice"]["0"]["State"]);
				$("#txtCountry").val(response["0"]["PostOffice"]["0"]["Country"]);
				$("#txtPincode").focus();
			}
			else
			{
				$("#txtCity").val("");
				$("#txtCity").focus();
			}
		}
	});
}
 
 </script>
 
</html>