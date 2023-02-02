<?php 
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
include_once "function.php";
include_once "commonFunctions.php";

$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$transporter_id  = $_REQUEST["tid"];
// $_SESSION["requestid"] = $requestid;

$ip_address= $commonfunction->getIPAddress();
$settingValueEmployeePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValueEmployeeImagePathVerification  = $commonfunction->getSettingValue("EmployeeImagePathVerification ");		
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
	
	
	$vehicle_type = "";
	$vehicle_manufacturer = "";
	$vehicle_number = "";
	$vehicle_image = "";
	$status = "";
	$name = "";
	$settingValueStatuspending= $commonfunction->getSettingValue("Pending Status");	
	
	if($requesttype=="edit")
	{
		$qry="select transporter_id,vehicle_type,vehicle_manufacturer,vehicle_number,vehicle_image,status from tw_vehicle_details_master where id='".$requestid."'";
		$retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		$transporter_id = $decodedJSON->response[0]->transporter_id; 
		$vehicle_type = $decodedJSON->response[1]->vehicle_type; 
		$vehicle_manufacturer = $decodedJSON->response[2]->vehicle_manufacturer;
		$vehicle_number = $decodedJSON->response[3]->vehicle_number;
		$vehicle_image = $decodedJSON->response[4]->vehicle_image;
		$status = $decodedJSON->response[5]->status;
		//$transporter_id = $decodedJSON->response[6]->transporter_id;
	}

$qry2 = "select id,name from tw_vehicle_type_master where visibility='true' ";
$retVal2 = $sign->FunctionOption($qry2,$name,'name','id');

$EmailQry="SELECT value FROM tw_employee_contact where employee_id='".$created_by."' AND contact_field='".$settingValueEmployeePrimaryEmail."'";
$Email=$sign->SelectF($EmailQry,"value");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste |Vehicle Details</title>
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
                  <h4 class="card-title">Vehicle Details</h4>
                  <div class="forms-sample">
					
                    <div class="form-group">
						<div class="form-group">
							<label for="txtvehicletype">Vehicle Type <code>*</code></label>
							<select id="txtvehicletype" class="form-control" >
								<?php echo $retVal2;?>
							</select>
						</div>
					<div class="form-group">
						<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								  <label for="txtvehiclemanufacturer"> Vehicle Manufacturer <code>*</code></label>
									<input type="text" class="form-control" id="txtvehiclemanufacturer" maxlength="50" value="<?php echo $vehicle_manufacturer; ?>" placeholder="Vehicle Manufacturer"/>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								     <label for="txtvehiclenumber"> Vehicle Number <code>*</code></label>
									<input type="text" class="form-control" id="txtvehiclenumber" maxlength="20" placeholder="Vehicle Number" value="<?php echo $vehicle_number; ?>">
								</div>
								
							</div>
					   
                    </div>
					<div class="form-group">
                      <label for="Document_Proof">Vehicle Image<code>*</code></label>

                      <input accept="image/*" type="file" class="form-control" onchange="showname();" id="Document_Proof" maxlength="30" placeholder="Vehicle Image" value="<?php echo $settingValueEmployeeImagePathVerification.$Email."/".$vehicle_image; ?>">
					  
					  <?php if($requesttype=="edit"){ 
								if($vehicle_image!=""){?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $settingValueEmployeeImagePathVerification.$Email."/".$vehicle_image;?>" target="_blank">View<a/>
									</div>
					  <?php } }?>
					</div>
						<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata();"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
 var varimg="<?php echo $vehicle_image;?>";
 if(varimg==""){
	 hdnIDimg="";
 }
 else{
	 hdnIDimg=varimg;
 }
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
	  var email = "<?php echo $settingValueEmployeePrimaryEmail; ?>";
	  var path = "<?php echo $settingValueEmployeeImagePathVerification; ?>"+email+"/"+name;
	  var result = checkFileExist(path,email);

	  if(fsize > 5000000)
	  {
		  showAlert("warning","Image File Size is very big","warning");
	  
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
			$('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
		},   
		success:function(data)
		
		{
			console.log(data);
			hdnIDimg=data;
			varimg=data;
		}
	   });
	  }
		  
		 
};
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
 function adddata(){
	  
	    if(!validateBlank($("#txtvehicletype").val())){
			setErrorOnBlur("txtvehicletype");
		}  
		else if(!validateBlank($("#txtvehiclemanufacturer").val())){
			setErrorOnBlur("txtvehiclemanufacturer");
		}
		else if(!validateBlank(hdnIDimg)){
			setErrorOnBlur("Document_Proof");
		} 
		else{ 
			var valtransporter_id = "<?php echo $transporter_id;?>";
			var valrequestid = "<?php echo $requestid;?>";
			var valcreated_by="<?php echo $created_by;?>";
			var valcreated_on="<?php echo $cur_date;?>";
			var valcreated_ip="<?php echo $ip_address;?>";
			var valrequesttype="<?php echo $requesttype;?>";
			var settingValueStatuspending="<?php echo $settingValueStatuspending; ?>";
			/* var valquery="";
			var valquerycount=""; */

			if(valrequesttype=="add"){
				var valquery = "insert into tw_vehicle_details_master(transporter_id,vehicle_type,vehicle_manufacturer,vehicle_number,vehicle_image,status,created_by,created_on,created_ip) values('"+valtransporter_id+"','"+$("#txtvehicletype").val()+"','"+$("#txtvehiclemanufacturer").val()+"','"+$("#txtvehiclenumber").val()+"','"+hdnIDimg+"','"+settingValueStatuspending+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				
				
				var valquerycount = "select count(*) as cnt from tw_vehicle_details_master where vehicle_number='"+$("#txtvehiclenumber").val()+"'";
			}  else{
				var valrequestid = "<?php echo $requestid;?>";
				var valquery = "update tw_vehicle_details_master set vehicle_type='"+$("#txtvehicletype").val()+"',vehicle_manufacturer='"+$("#txtvehiclemanufacturer").val()+"', vehicle_number='"+$("#txtvehiclenumber").val()+"',vehicle_image='"+hdnIDimg+"',modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
				var  valquerycount = "select count(*) as cnt from tw_vehicle_details_master where vehicle_number='"+$("#txtvehiclenumber").val()+"' and ID!='"+valrequestid+"'";
				  
			}  
			disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
				$.ajax({
				type:"POST",
				url:"apiEmployeeProfile.php",
				data:{valquery:valquery,valquerycount:valquerycount},
				success:function(response){
					console.log(response)
					if(valrequesttype=="add"){
						enableButton('#btnAddrecord','Add Record');
					}
					else
					{
						enableButton('#btnAddrecord','Update Record');
					}
					if($.trim(response)=="Success"){
						if(valrequesttype=="add"){
							showAlertRedirect("Success","Record Added Successfully","success","pgVehicleDetails.php?tid="+valtransporter_id);
						}
						else{
							showAlertRedirect("Success","Record Updated Successfully","success","pgVehicleDetails.php?tid="+valtransporter_id);
						}
					}
					else if($.trim(response)=="Exist"){
						showAlert("Warning","Record already exist","warning");
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
					}
				}
			});     
	} 
}

 </script>
 
 </body>
</html>