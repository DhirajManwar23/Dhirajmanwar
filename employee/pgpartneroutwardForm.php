<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$id = $_REQUEST["id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$UserImagePathVerification = $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");	
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$created_by=$_SESSION["company_id"];
$company_id = $_SESSION["company_id"];
$employee_id = $_SESSION["employee_id"];

$EmailQry = "select value from tw_employee_contact where employee_id = '".$employee_id."' and contact_field='".$settingValuePemail."'";
$EMAIL = $sign->SelectF($EmailQry,'value'); 

$name = "";
$address = "";
$logo = "";
$url1 = "";
$converted_material = "";
$description = "";
$photograph = "";
if($requesttype=="edit")
{
	$qry="select name,address,logo,url,converted_material,description,photograph from tw_partner_outward_master where ID='".$requestid."' order by id Desc";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$name = $decodedJSON->response[0]->name; 
	$address = $decodedJSON->response[1]->address; 
	$logo = $decodedJSON->response[2]->logo;
	$url1 = $decodedJSON->response[3]->url;
	$converted_material = $decodedJSON->response[4]->converted_material;
	$description = $decodedJSON->response[5]->description;
	$photograph = $decodedJSON->response[6]->photograph;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Add Customer</title>
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
                  <h4 class="card-title">Add Customer</h4>
					<div class="forms-sample">
						<div class="form-group">
						<label for="txtname">Name <code>*</code></label>
						<input type="text" class="form-control" id="txtname" maxlength="30" placeholder="Name" value="<?php echo $name; ?>">
						</div>
						<div class="form-group">
						<label for="txtaddress">Address <code>*</code></label>
						<input type="text" class="form-control" id="txtaddress" maxlength="30" placeholder="Address" value="<?php echo $address; ?>">
						</div>
						<div class="form-group">
						<label for="txtlogo">Customer Logo <code>*</code></label>
						<div class="col-sm-5">
						<input type="file" name="logo" accept=".png, .jpg, .jpeg, .pdf" id="logo" class="rounded" value="<?php echo $logo;?>" placeholder="Photo" onchange="showname(this.id);" />				
						<span id="IMGAGE" class="text-danger"></span>
						</div>
					  	<?php if($requesttype=="edit"){
								if($logo!=""){ ?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $settingValueEmployeeImagePathVerification; ?><?php echo $EMAIL."/".$logo;?>"  target="_blank">View<a/>
									</div>
						<?php }}?>
						</div>
						<div class="form-group">
						<label for="txturl">URL <code>*</code></label>
						<input type="text" class="form-control" id="txturl" maxlength="30" placeholder="URL" value="<?php echo $url1; ?>" />
						</div>
						<div class="form-group">
						<label for="txtconvertedmaterial">Converted Material <code>*</code></label>
						<input type="text" class="form-control" id="txtconvertedmaterial" maxlength="30" placeholder="Converted Material" value="<?php echo $converted_material; ?>">
						</div>
						<div class="form-group">
						<label for="txtdescription">Description <code>*</code></label>
						<input type="text" class="form-control" id="txtdescription" maxlength="30" placeholder="Description" value="<?php echo $description; ?>">
						</div>
						
					<div class="form-group">
                      <label for="photo" class="col-sm-3 col-form-label">Converted Material Photo<span class="text-danger">*</span></label>
                      <div class="col-sm-5">
						<input type="file" name="photo" accept=".png, .jpg, .jpeg, .pdf" id="photo"  value="<?php echo $photograph;?>"  placeholder="Photo" onchange="showname(this.id);" />				
                      <span id="IMGAGE" class="text-danger"></span>
					  </div>
					  	<?php if($requesttype=="edit"){
								if($photograph!=""){ ?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $settingValueEmployeeImagePathVerification; ?><?php echo $EMAIL."/".$photograph;?>" target="_blank">View<a/>
									</div>
						<?php }}?>
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
<script>
var hdnIDimg="";
var hdnIDfsize="";
 $( document ).ready(function() {
     $("#ERROR").css("display", "none");
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
function showname(id){
	  var name = document.getElementById(id); 
	  hdnIDimg = name.files.item(0).name;
	 
	  var name = document.getElementById(id).files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['pdf','png','jpg','jpeg']) == -1) 
	  {
		document.getElementById(id).value = '';
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById(id).files[0]);
	  var f = document.getElementById(id).files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
		  removeError(id);
		   $("#IMGAGE").html("Image File Size is very big")
	  }
	  else
	  {
		form_data2.append('Document_Proof', document.getElementById(id).files[0]);
		hdnIDfsize=fsize;

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
		}
	   });
	  }
};
  function adddata(){
		var photo = $("#photo").val().split('\\')[2];
		if(!photo) photo = document.getElementById("photo").defaultValue; 
		var logo = $("#logo").val().split('\\')[2];
		if(!logo) logo = document.getElementById("logo").defaultValue; 
	    if(!validateBlank($("#txtname").val())){
			setErrorOnBlur("txtname");
		}
		else if(!validateBlank($("#txtaddress").val())){
		setErrorOnBlur("txtaddress");
		} 
		else if(!validateBlank(logo)){
		setErrorOnBlur("logo");
		}
		else if(!validateBlank($("#txturl").val())){
		setErrorOnBlur("txturl");
		}
		else if(!validateBlank($("#txtconvertedmaterial").val())){
		setErrorOnBlur("txtconvertedmaterial");
		}
		else if(!validateBlank($("#txtdescription").val())){
		setErrorOnBlur("txtdescription");
		}
		else if(!validateBlank(photo)){
		setErrorOnBlur("photo");
		}
		else{ 
			disableButton('#btnAddrecord','Processing...');
			var valcreated_by="<?php echo $company_id;?>";
			var valcreated_on="<?php echo $cur_date;?>";
			var valcreated_ip="<?php echo $ip_address;?>";
			var valrequesttype="<?php echo $requesttype;?>";
			var valrequestid = "<?php echo $id;?>";

			if(valrequesttype=="add"){

				var valquery ="insert into tw_partner_outward_master(company_id,name,address,logo,url,converted_material,description,photograph,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#txtname").val()+"','"+$("#txtaddress").val()+"','"+logo+"','"+$("#txturl").val()+"','"+$("#txtconvertedmaterial").val()+"','"+$("#txtdescription").val()+"','"+photo+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";

				var valquerycount = "select count(*) as cnt from tw_partner_outward_master where name='"+$("#txtname").val()+"'";
			}
			else{
				
				var valquery = "update tw_partner_outward_master set name = '"+$("#txtname").val()+"' , address = '"+$("#txtaddress").val()+"', logo = '"+logo+"', url = '"+$("#txturl").val()+"', converted_material = '"+$("#txtconvertedmaterial").val()+"', description = '"+$("#txtdescription").val()+"', photograph = '"+photo+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
				var valquerycount = "select count(*) as cnt from tw_partner_outward_master where name = '"+$("#txtname").val()+"' and ID!='"+valrequestid+"'";
			}
			
				$.ajax({
				type:"POST",
				url:"apiCommonQuerySingle.php",
				data:{valquery:valquery,valquerycount:valquerycount},
				success:function(response){
				console.log(response);
					if(valrequesttype=="add"){
							enableButton('#btnAddrecord','Add Record');
						}
					else{
						enableButton('#btnAddrecord','Update Record');
					}	
					if($.trim(response)=="Success"){
						if(valrequesttype=="add"){
							$('#btnAddrecord').html('Add Record');
							showAlertRedirect("Success","Record Added Successfully","success","pgPartnerOutward.php");
						}
						else{
							$('#btnAddrecord').html('Update Record');
							showAlertRedirect("Success","Record Updated Successfully","success","pgPartnerOutward.php");
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