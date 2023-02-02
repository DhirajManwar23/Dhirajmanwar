<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$agentrequesttype = $_REQUEST["type"];
$_SESSION["request_type"] = $agentrequesttype;
$id = $_REQUEST["id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["username"];
$settingValueAgentImagePathVerification  = $commonfunction->getSettingValue("AgentImagePathVerification");

$AgentID = "";
$agent_name = "";
$agent_photo = "";
$mobilenumber= "";
$alternative_mobilenumber= "";
$email= "";
$address_line_1= "";
$address_line_2= "";
$location= "";
$pincode= "";
$city= "";
$state= "";
$country= "";
$status= "";
$agent_gender= "";
$agent_marital_status= "";
$agent_dob= "";
	
if($agentrequesttype=="edit"){ 
	
	$qry = "select id  AgentID,agent_name,agent_photo,mobilenumber,alternative_mobilenumber,email,address_line_1,address_line_2,location,pincode,city,state,country,status,agent_gender,agent_marital_status,agent_dob from tw_agent_details where id ='".$id."'";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	
	$AgentID = $decodedJSON->response[0]->AgentID;
	$agent_name = $decodedJSON->response[1]->agent_name;
	$agent_photo = $decodedJSON->response[2]->agent_photo;
	$mobilenumber = $decodedJSON->response[3]->mobilenumber;
	$alternative_mobilenumber = $decodedJSON->response[4]->alternative_mobilenumber;
	$email = $decodedJSON->response[5]->email;
	$address_line_1 = $decodedJSON->response[6]->address_line_1;
	$address_line_2 = $decodedJSON->response[7]->address_line_2;
	$location = $decodedJSON->response[8]->location;
	$pincode = $decodedJSON->response[9]->pincode;
	$city = $decodedJSON->response[10]->city;
	$state = $decodedJSON->response[11]->state;
	$country = $decodedJSON->response[12]->country;
	$status = $decodedJSON->response[13]->status;
	$agent_gender = $decodedJSON->response[14]->agent_gender;
	$agent_marital_status = $decodedJSON->response[15]->agent_marital_status;
	$agent_dob = $decodedJSON->response[16]->agent_dob;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |Agent Details Master</title>
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
                  <h4 class="card-title">Agent Details Master</h4>
					<div class="forms-sample">
					 <div class="row">
						 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 grid-margin">
							<div class="form-group">
								<label for="txtagenttname">Agent Name<code>*</code></label>
								<input type="text" class="form-control" id="txtagenttname" maxlength="100" value="<?php echo $agent_name; ?>" placeholder="Agent Name" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 grid-margin">
							<div class="form-group">
							  <label for="Document_Proof">Agent Photo<code></code></label>
							  <input type="file" class="form-control" onchange="showname();" accept=".png, .jpg, .jpeg" id="Document_Proof" maxlength="30" placeholder="Agent Photo" value="<?php echo $agent_photo; ?>">
							  <?php if($agentrequesttype=="edit"){ 
										if($agent_photo!=""){?>
											<div class="col-sm-4" id="diveditimg">          
												<a href="<?php echo $settingValueAgentImagePathVerification; ?><?php echo $mobilenumber; ?>/<?php echo $agent_photo;?>" target="_blank">View<a/>
											</div>
								<?php } }?>
							</div>
						</div>
					</div>
					<!-------------------------------------Personal Details Starts ---------------------------->
					
					<div class="row">
						 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
							<div class="form-group">
								<label for="txtagentgender">Agent Gender<code>*</code></label>
								<select name="selcontact" id="txtselGender" class="form-control" >
									<option value="" <?php  if($agent_gender=="Select Gender"){ echo "selected";} ?>>Select Gender</option>
									<option value="Male" <?php  if($agent_gender=="Male"){ echo "selected";} ?>>Male</option>
									<option value="Female" <?php  if($agent_gender=="Female"){ echo "selected";} ?>>Female</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
							<div class="form-group">
								<label for="txtagentmaritalstatus">Agent Marital Status</label>
								<select name="selmaritalstatus" id="txtselMaritalStatus" class="form-control" >
									<option value="" <?php  if($agent_marital_status=="Select Marital Status"){ echo "selected";} ?>>Select Marital Status</option>
									<option value="Married" <?php  if($agent_marital_status=="Married"){ echo "selected";} ?>>Married</option>
									<option value="Unmarried" <?php  if($agent_marital_status=="Unmarried"){ echo "selected";} ?>>Unmarried</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
							<div class="form-group">
								<label for="txtagentdob">Agent DOB</label>
								<input type="date" class="form-control" id="txtagentdob" value="<?php echo $agent_dob; ?>" placeholder="Agent DOB" />
							</div>
						</div>
                    </div>
					<!-------------------------------------Personal Details Ends ---------------------------->
					
					<div class="row">
						 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
							<div class="form-group">
								<label for="txtmobilenumber">Mobile Number<code>*</code></label>
								<input type="text" class="form-control" id="txtmobilenumber" maxlength="100" value="<?php echo $mobilenumber; ?>" placeholder="Mobile Number" />
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
							<div class="form-group">
								<label for="txtalternativemobilenumber">Alternative Mobile Number</label>
								<input type="text" class="form-control" id="txtalternativemobilenumber" maxlength="100" value="<?php echo $alternative_mobilenumber; ?>" placeholder="Alternative Mobile Number" />
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
							<div class="form-group">
								<label for="txtemail">Email</label>
								<input type="text" class="form-control" id="txtemail" maxlength="100" value="<?php echo $email; ?>" placeholder="Email" />
							</div>
						</div>
                    </div>
					
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
							<div class="form-group">
								<label for="txtaddressline1">Address Line_1<code>*</code></label>
								<input type="text" class="form-control" id="txtaddressline1" maxlength="100" value="<?php echo $address_line_1; ?>" placeholder="Address Line 1" />
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">	
							<div class="form-group">
								<label for="txtaddressline2">Address Line 2</label>
								<input type="text" class="form-control" id="txtaddressline2" maxlength="100" value="<?php echo $address_line_2; ?>" placeholder="Address Line 2" />
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
							<div class="form-group">
								<label for="txtlocation">Location<code>*</code></label>
								<input type="text" class="form-control" id="txtlocation" maxlength="100" value="<?php echo $location; ?>" placeholder="Location" />
							</div>
						</div>
                    </div>
					
					
					<div class="row">
						
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 grid-margin">
							<div class="form-group ">
							  <label for="pincode" >Pincode<span class="text-danger">*</span></label>
							  <div class="">
								<input type="number" class="form-control " maxlength="6" id="txtpincode" onchange="myFunction(this.value)" value="<?php echo $pincode; ?>" placeholder="Pincode">
							  </div>
							</div>
						</div>
         
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 grid-margin">
					<div class="form-group">
                      <label for="City">City<span class="text-danger">*</span></label>
                      <div class="">
                        <input type="text" class="form-control form-control-sm" maxlength="20" disabled id="txtcity" value="<?php echo $city; ?>" placeholder="City">
                      </div>
                    </div>
                    </div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 grid-margin">
					<div class="form-group">
                      <label for="State" >State<span class="text-danger">*</span></label>
                      <div class="">
                        <input type="text" class="form-control form-control-sm" disabled id="txtstate"  maxlength="20" value="<?php echo $state; ?>" placeholder="State">
                      </div>
                    </div>
                    </div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 grid-margin">
					<div class="form-group ">
                      <label for="Country" class="">Country<span class="text-danger">*</span></label>
                      <input type="text" class="form-control form-control-sm" maxlength="20" disabled id="txtcountry" value="<?php echo $country; ?>" placeholder="Country">
                     
                    </div> 
                    </div> 
                    </div> 
					
					<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata()"><?php if($agentrequesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
					
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
var valAgentID = "<?php echo $AgentID;?>";	 
var valrequesttype = "<?php echo $agentrequesttype;?>";
var valstatus = "<?php echo $status;?>";





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

$("#txtemail").blur(function()
 {
 removeError(txtemail);
     if ($("#txtemail").val()!="")
	{
		if(!validateEmail($("#txtemail").val())){
			setError(txtemail);
		}
		else
		{
			removeError(txtemail);
		}
	}
});


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
 $("#txtmobilenumber").blur(function()
 {
	 removeError(txtmobilenumber);
	 if ($("#txtmobilenumber").val()!="")
		{
			if(!isMobile($("#txtmobilenumber").val())){
				setError(txtmobilenumber);
			}
			else
			{
				removeError(txtmobilenumber);
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
		url:"uploadImgAgent.php",
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


	function myFunction(val) {
	getPinCodeResi($("#txtpincode").val());
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
				$("#txtcity").val(response["0"]["PostOffice"]["0"]["Region"]);
				$("#txtstate").val(response["0"]["PostOffice"]["0"]["State"]);
				$("#txtcountry").val(response["0"]["PostOffice"]["0"]["Country"]);
				$("#txtcity").attr('readonly', true);
				$("#txtstate").attr('readonly', true);
				$("#txtcountry").attr('readonly', true);
				$("#txtpincode").focus();
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
			}
		}
	});
}

	/* function adddata(){
	
		if(!validateBlank($("#txtagenttname").val())){
			setErrorOnBlur("txtagenttname");
		  }
		 
		  else  if(!isMobile($("#txtmobilenumber").val())){
			setErrorOnBlur("txtmobilenumber");
	      }
		  
		  else if(!validateBlank($("#txtaddressline1").val())){
			setErrorOnBlur("txtaddressline1");
		  }
		  else if(!validateBlank($("#txtlocation").val())){
			setErrorOnBlur("txtlocation");
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
		 
	else{
		disableButton('#btnAddrecord','Processing...');

		var valcreated_by="<?php echo $created_by;?>";
	    var valcreated_on="<?php echo $cur_date;?>";
		var valcreated_ip="<?php echo $ip_address;?>";
		var valrequesttype="<?php echo $requesttype;?>";
		var valrequestid = "<?php echo $requestid;?>";
		
		if(valrequesttype=="add"){
			var valquery = "insert into tw_agent_details(agent_name,agent_photo,mobilenumber,alternative_mobilenumber,email,address_line_1,address_line_2,location,pincode,city,state,country,status,created_by,created_on,created_ip) values('"+$("#txtagenttname").val()+"','"+hdnIDimg+"','"+$("#txtmobilenumber").val()+"','"+$("#txtalternativemobilenumber").val()+"','"+$("#txtemail").val()+"','"+$("#txtaddressline1").val()+"','"+$("#txtaddressline2").val()+"','"+$("#txtlocation").val()+"','"+$("#txtpincode").val()+"','"+$("#txtcity").val()+"','"+$("#txtstate").val()+"','"+$("#txtcountry").val()+"','"+"On"+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			//console.log(valquery);
			
			var valquerycount = "select count(*) as cnt from tw_agent_details where mobilenumber='"+$("#txtmobilenumber").val()+"' and id!='"+valrequestid+"'";
		}
		
		else{
			var valrequestid = "<?php echo $requestid;?>";
			if(hdnIDimg!=""){
			
			var valquery = "Update tw_agent_details set agent_name='"+$("#txtagenttname").val()+"',agent_photo='"+hdnIDimg+"',mobilenumber='"+$("#txtmobilenumber").val()+"',alternative_mobilenumber='"+$("#txtalternativemobilenumber").val()+"',email='"+$("#txtemail").val()+"',address_line_1='"+$("#txtaddressline1").val()+"',address_line_2='"+$("#txtaddressline2").val()+"',location='"+$("#txtlocation").val()+"',pincode='"+$("#txtpincode").val()+"',city='"+$("#txtcity").val()+"',state='"+$("#txtstate").val()+"',country='"+$("#txtcountry").val()+"',modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
			
			}
			
		else{
			
			var valquery = "Update tw_agent_details set agent_name='"+$("#txtagenttname").val()+"',mobilenumber='"+$("#txtmobilenumber").val()+"',alternative_mobilenumber='"+$("#txtalternativemobilenumber").val()+"',email='"+$("#txtemail").val()+"',address_line_1='"+$("#txtaddressline1").val()+"',address_line_2='"+$("#txtaddressline2").val()+"',location='"+$("#txtlocation").val()+"',pincode='"+$("#txtpincode").val()+"',city='"+$("#txtcity").val()+"',state='"+$("#txtstate").val()+"',country='"+$("#txtcountry").val()+"',modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
			//console.log(valquery);
		}
			var valquerycount = "select count(*) as cnt from tw_agent_details where mobilenumber='"+$("#txtmobilenumber").val()+"' and id!='"+valrequestid+"'";
			//console.log(valquerycount);
			
		}
			
		$.ajax({
			type:"POST",
			url:"apiAddAgentDetails.php",
			data:{valquery:valquery,valquerycount:valquerycount,mobilenumber:$("#txtmobilenumber").val(),requesttype:valrequesttype,requestid:valrequestid,agent_photo:hdnIDimg},
			success:function(response){
				console.log(response);
				if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
				}
				else
				{
					enableButton('#btnAddrecord','Update Record');
				}
				if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Record Added Successfully","success","pgAgentDetails.php");
					}
					else{
						showAlertRedirect("Success","Record Updated Successfully","success","pgAgentDetails.php");
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
	} */
	
function adddata(){
	
	if(!validateBlank($("#txtagenttname").val())){
			setErrorOnBlur("txtagenttname");
		  }
		  else  if(!isMobile($("#txtmobilenumber").val())){
			setErrorOnBlur("txtmobilenumber");
	      }
		  else if(!validateBlank($("#txtaddressline1").val())){
			setErrorOnBlur("txtaddressline1");
		  }
		  else if(!validateBlank($("#txtlocation").val())){
			setErrorOnBlur("txtlocation");
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
		 
	else{
	
		
		//alert(valCPID);
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');

		$.ajax({
			type:"POST",
			url:"apiAddAgentDetails.php",
			data:{	valAgentID:valAgentID,
					agent_name : $("#txtagenttname").val(),
					agent_photo : hdnIDimg,
					agent_gender : $("#txtselGender").val(),
					agent_marital_status : $("#txtselMaritalStatus").val(),
					agent_dob : $("#txtagentdob").val(),
					mobilenumber : $("#txtmobilenumber").val(),
					alternative_mobilenumber : $("#txtalternativemobilenumber").val(),
					email : $("#txtemail").val(),
					address_line_1 : $("#txtaddressline1").val(),
					address_line_2 : $("#txtaddressline2").val(),
					location : $("#txtlocation").val(),
					pincode : $("#txtpincode").val(),
					city : $("#txtcity").val(),
					state : $("#txtstate").val(),
					country : $("#txtcountry").val(),
					valstatus : valstatus},
			
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
						showAlertRedirect("Success","Data Added Successfully","success","pgAgentDetails.php");
						}
					else{
						$('#btnAddrecord').html('Update Record');
 						showAlertRedirect("Success","Data Updated Successfully","success","pgAgentDetails.php");
					}
					$(hdnIDimg).val("");
					$("#txtagenttname").val("");
					$("#txtselGender").val("");
					$("#txtselMaritalStatus").val("");
					$("#txtagentdob").val("");
					$("#txtmobilenumber").val("");
					$("#txtalternativemobilenumber").val("");
					$("#txtEmail").val("");
					$("#txtaddressline1").val("");
					$("#txtaddressline2").val("");
					$("#txtlocation").val("");
					$("#txtpincode").val("");
					$("#txtcity").val();
					$("#txtstate").val("");
					$("#txtcountry").val("");
				}
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Record already exist","warning");
					
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