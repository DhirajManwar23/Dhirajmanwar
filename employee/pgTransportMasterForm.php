<?php 
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$_SESSION["requesttype"] = $requesttype;
$requestid = $_REQUEST["id"];
// $_SESSION["requestid"] = $requestid; 

//whether ip is from share internet
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
	
	$transporter_name = "";
	$transporter_email = "";
	$contact = "";
	$address_line_1 = "";
	$address_line_2 = "";
	$location = "";
	$pincode = "";
	$city = "";
	$state = "";
	$country = "";
	$google_map = "";
	$status = "";
	$settingValueStatuspending= $commonfunction->getSettingValue("Registration Status");	
	
	if($requesttype=="edit")
	{
		$qry="select transporter_name,transporter_email,contact,address_line_1,address_line_2,location,pincode,city,state,country,google_map,status from tw_transport_master where id='".$requestid."'";
		$retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		$transporter_name = $decodedJSON->response[0]->transporter_name; 
		$transporter_email = $decodedJSON->response[1]->transporter_email; 
		$contact = $decodedJSON->response[2]->contact;
		$address_line_1 = $decodedJSON->response[3]->address_line_1;
		$address_line_2 = $decodedJSON->response[4]->address_line_2;
		$location = $decodedJSON->response[5]->location;
		$pincode = $decodedJSON->response[6]->pincode;
		$city = $decodedJSON->response[7]->city;
		$state = $decodedJSON->response[8]->state;
		$country = $decodedJSON->response[9]->country;
		$google_map = $decodedJSON->response[10]->google_map;
		$status = $decodedJSON->response[11]->status;
	
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Transport Master</title>
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
                  <h4 class="card-title">Transporter Details</h4>
                  <div class="forms-sample">
					
                    <div class="form-group">
                      <label for="txttransporter_name">Transporter Name <code>*</code></label>
                      <input type="text" class="form-control" id="txttransporter_name" maxlength="30" placeholder="Transporter Name" value="<?php echo $transporter_name; ?>">
                    </div>
					<div class="form-group">
						<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								  <label for="txttransporter_email">Transporter Email <code>*</code></label>
									<input type="email" class="form-control" id="txttransporter_email" maxlength="30" value="<?php echo $transporter_email; ?>" placeholder="Transporter Email" />
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								   <label for="txtcontact"> Contact <code>*</code></label>
									<input type="number" class="form-control" id="txtcontact" maxlength="10" value="<?php echo $contact; ?>" placeholder="Contact" />
								</div>
								
							</div>
					    </div>
					<div class="form-group">
						<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								   <label for="txtaddressline1"> Address Line 1<code>*</code></label>
									<input type="text" class="form-control" id="txtaddressline1" maxlength="100" placeholder="Address Line 1" value="<?php echo $address_line_1; ?>"><br>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								   <label for="txtaddressline2"> Address Line 2 (Optional)</label>
									<input type="text" class="form-control" id="txtaddressline2" maxlength="100" placeholder="Address Line 2" value="<?php echo $address_line_2; ?>"><br>
								</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
								    <label for="txtlocation">Location<code>*</code></label>
									<input type="text" class="form-control" id="txtlocation" maxlength="30" placeholder="Location" value="<?php echo $location; ?>">
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
								  <label for="txtpincode">Pincode <code>*</code></label>
								  <input type="number" class="form-control form-control-sm" id="txtpincode" onchange="myFunction(this.value)" value="<?php echo $pincode; ?>" pattern="/^-?\d+\.?\d*$/"   placeholder="Pincode">
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">	
							  <label for="GoogleMap">Google Map (Optional)</label>
							 
								<input type="text" class="form-control form-control-sm" maxlength="1000" id="txtGoogleMap" value="<?php echo $google_map; ?>" placeholder="Google Map">
							  
							  </div>	
							</div>
                    </div>
					<div class="form-group">
						<div class="row">
								
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
								    <label for="txtcity">City <code>*</code></label>
									<input type="text" class="form-control form-control-sm" maxlength="20" disabled id="txtcity" value="<?php echo $city; ?>" placeholder="City">
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
								  <label for="txtstate">State <code>*</code></label>
									<input type="text" class="form-control" id="txtstate" maxlength="30" disabled id="txtstate" placeholder="State"  value="<?php echo $state; ?>">
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
								     <label for="txtcountry">Country <code>*</code></label>
									<input type="text" class="form-control" id="txtcountry" maxlength="30" disabled id="txtcountry" placeholder="Country"  value="<?php echo $country;?>">
								</div>
								
								
							</div>
					   
                    </div>
				
					<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata()"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
<script src="../assets/css/jquery/jquery.min.js"></script>
<script>
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
	var valplaceholder = $("#"+inputComponent).attr("placeholder");
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
$("#txttransporter_email").blur(function()
{
	removeError(txttransporter_email);
	if ($("#txttransporter_email").val()!="")
	{
		if(!validateEmail($("#txttransporter_email").val())){
			setError(txttransporter_email);
		}
		else
		{
			removeError(txttransporter_email);
		}
	}
});
$("#txtcontact").blur(function()
{
	removeError(txtcontact);
	if ($("#txtcontact").val()!="")
	{
		if(!isMobile($("#txtcontact").val())){
			setError(txtcontact);
		}
		else
		{
			removeError(txtcontact);
		}
	}
});

 function adddata(){
	  
	    if(!validateBlank($("#txttransporter_name").val())){
			setErrorOnBlur("txttransporter_name");
		  }		  
		  else if(!validateEmail($("#txttransporter_email").val())){
			setErrorOnBlur("txttransporter_email");
		  } 
		  else if(!isMobile($("#txtcontact").val())){
			setError(txtcontact);
		  }
		  else if(!validateBlank($("#txtaddressline1").val())){
			setErrorOnBlur("txtaddressline1");
		  } 
		 /*  else if(!validateBlank($("#txtaddressline2").val())){
			setErrorOnBlur("txtaddressline2");
		  }  */
		  else if(!validateBlank($("#txtlocation").val())){
			setErrorOnBlur("txtlocation");
		  } 
		  else if(!validateBlank($("#txtpincode").val())){
			setError(txtpincode);
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
		  else{ 
		   disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		

		var valcreated_by="<?php echo $created_by;?>";
	    var valcreated_on="<?php echo $cur_date;?>";
		var valcreated_ip="<?php echo $ip_address;?>";
		var valrequesttype="<?php echo $requesttype;?>";
		var settingValueStatuspending="<?php echo $settingValueStatuspending; ?>";
		var companyID="<?php echo $_SESSION['company_id']; ?>";
		var valquery="";
		var valquerycount="";
		if(valrequesttype=="add"){
			
			valquery = "insert into tw_transport_master(company_id,transporter_name,transporter_email,contact,address_line_1,address_line_2,location,pincode,city,state,country,google_map,status,created_by,created_on,created_ip) values('"+companyID+"','"+$("#txttransporter_name").val()+"','"+$("#txttransporter_email").val()+"','"+$("#txtcontact").val()+"','"+$("#txtaddressline1").val()+"','"+$("#txtaddressline2").val()+"','"+$("#txtlocation").val()+"','"+$("#txtpincode").val()+"','"+$("#txtcity").val()+"','"+$("#txtstate").val()+"','"+$("#txtcountry").val()+"','"+$("#txtGoogleMap").val()+"','"+settingValueStatuspending+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			
			valquerycount = "select count(*) as cnt from tw_transport_master where transporter_name='"+$("#txttranspoter_name").val()+"'";
		
		}
		
		else{
			var valrequestid = "<?php echo $requestid;?>";
			valquery = "update tw_transport_master set transporter_name='"+$("#txttransporter_name").val()+"',transporter_email='"+$("#txttransporter_email").val()+"',contact='"+$("#txtcontact").val()+"', address_line_1='"+$("#txtaddressline1").val()+"', address_line_2='"+$("#txtaddressline2").val()+"',location='"+$("#txtlocation").val()+"',pincode='"+$("#txtpincode").val()+"',city='"+$("#txtcity").val()+"',state='"+$("#txtstate").val()+"',country='"+$("#txtcountry").val()+"',google_map='"+$("#txtGoogleMap").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
			valquerycount = "select count(*) as cnt from tw_transport_master where transporter_name='"+$("#txttransporter_name").val()+"' and id!='"+valrequestid+"'";
		} 
		

	$.ajax({
			type:"POST",
			url:"apiEmployeeProfile.php",
			data:{valquery:valquery,valquerycount:valquerycount},
			success:function(response){
				
				if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
				}
				else
				{
					enableButton('#btnAddrecord','Update Record');
				}
				if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Record Added Successfully","success","pgTransportMaster.php");
					}
					else{
						showAlertRedirect("Success","Record Updated Successfully","success","pgTransportMaster.php");
					}
				}
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Record already exist","warning");
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
				}
			}
		});    
  } 
}
/* function myFunction(val) {
	getPinCodeResi($("#txtpincode").val());
} */
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
 </script>
 
 </body>
</html>