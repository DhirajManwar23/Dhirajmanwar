<?php 
session_start();
if(!isset($_SESSION["company_id"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$ip_address= $commonfunction->getIPAddress();
$settingValueDocumentStatus= $commonfunction->getSettingValue("Pending Status");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePemail=$sign->escapeString($settingValuePemail);
$UserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$VerifiedGreenTickImage= $commonfunction->getSettingValue("VerifiedGreenTickImage");
$settingValueUserImagePathEPRServicesDocument=$commonfunction->getSettingValue("UserImagePathEPRSDocument");

$requestid = $_REQUEST["id"];
$po_id = $_REQUEST["poid"];
$statusvalue = $_REQUEST["status"];
$pagetype = $_REQUEST["pagetype"];

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["company_id"];
$Status = "";

	
	$qry3 = "select aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,category_name,material_name from tw_temp where id = '".$requestid."'";
	$retVal3 = $sign->FunctionJSON($qry3);
	$decodedJSON = json_decode($retVal3);
	$aggeragator_name = $decodedJSON->response[0]->aggeragator_name;
	$gst = $decodedJSON->response[1]->gst;
	$grn_number = $decodedJSON->response[2]->grn_number;
	$type_of_submission = $decodedJSON->response[3]->type_of_submission;
	$grnfile = $decodedJSON->response[4]->grnfile;
	$purchase_invoice_number = $decodedJSON->response[5]->purchase_invoice_number;
	$purchase_invoice_date = $decodedJSON->response[6]->purchase_invoice_date;
	$dispatched_state = $decodedJSON->response[7]->dispatched_state;
	$dispatched_place = $decodedJSON->response[8]->dispatched_place;
	$invoice_quantity = $decodedJSON->response[9]->invoice_quantity;
	$invoicefile = $decodedJSON->response[10]->invoicefile;
	$plant_quantity = $decodedJSON->response[11]->plant_quantity;
	$aggregator_wbs_number = $decodedJSON->response[12]->aggregator_wbs_number;
	$aggregator_wbs_date = $decodedJSON->response[13]->aggregator_wbs_date;
	$wbsfile = $decodedJSON->response[14]->wbsfile;
	$plant_wbs_number = $decodedJSON->response[15]->plant_wbs_number;
	$plant_wbs_date = $decodedJSON->response[16]->plant_wbs_date;
	$pwbsfile = $decodedJSON->response[17]->pwbsfile;
	$vehicle_number = $decodedJSON->response[18]->vehicle_number;
	$vehiclefile = $decodedJSON->response[19]->vehiclefile;
	$eway_bill_number = $decodedJSON->response[20]->eway_bill_number;
	$ewayfile = $decodedJSON->response[21]->ewayfile;
	$lr_number = $decodedJSON->response[22]->lr_number;
	$lr_date = $decodedJSON->response[23]->lr_date;
	$lrfile = $decodedJSON->response[24]->lrfile;
	$category_name = $decodedJSON->response[25]->category_name;
	$material_name = $decodedJSON->response[26]->material_name;

?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Document Details</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
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
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Document Details</h4>
                  <div class="forms-sample">
					<div class="form-group row">
                      <label for="Aggregator_Name" class="col-sm-3 col-form-label">Aggregator Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $aggeragator_name; ?>" id="txtAggregator_Name" placeholder="Aggregator Name">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="GST" class="col-sm-3 col-form-label">GST</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $gst; ?>" id="txtGST" placeholder="GST">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Category_Name" class="col-sm-3 col-form-label">Category Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $category_name; ?>" id="txtCategory_Name" placeholder="Category Name">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="material_name" class="col-sm-3 col-form-label">Material Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $material_name; ?>" id="txtMaterial_Name" placeholder="Material Name">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="GRN_Number" class="col-sm-3 col-form-label">GRN Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="100"  value="<?php echo $grn_number; ?>" id="txtGRN_Number" placeholder="GRN Number">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Type_of_Submission" class="col-sm-3 col-form-label">Type of Submission</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $type_of_submission; ?>" id="txtTypeofSubmission" placeholder="Type of Submission">
                      </div>
                    </div>
					
					<div class="form-group row">
						  <label for="Document_Proof" class="col-sm-3 col-form-label">GRN File</label>
						  <div class="col-sm-5">
							<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg1' name='selectImg1' onchange='uploadGRN()'>				
						  </div>
						  <?php
							if($grnfile!=""){?>
								<div class="col-sm-4">          
									<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$grnfile;?>" target="_blank">View</a>
								</div>
							<?php } ?>
                    </div>
					<div class="form-group row">
                      <label for="purchase_invoice_number" class="col-sm-3 col-form-label">Purchase Invoice Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50"  value="<?php echo $purchase_invoice_number; ?>" id="txtPurchase_invoice_number" placeholder="Purchase Invoice Number Code">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="purchase_invoice_date" class="col-sm-3 col-form-label">Purchase Invoice Date</label>
                      <div class="col-sm-9">
                        <input type="date" class="form-control form-control-sm"  maxlength="50" value="<?php echo date("Y-m-d",strtotime($purchase_invoice_date)) ?>" id="txtPurchaseInvoiceDate" placeholder="Purchase Invoice Date">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="dispatched_state" class="col-sm-3 col-form-label">Dispatched State</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $dispatched_state; ?>" id="txtDispatchedState" placeholder="Dispatched State">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="dispatched_place" class="col-sm-3 col-form-label">Dispatched Place</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $dispatched_place; ?>" id="txtDispatchedPlace" placeholder="Dispatched Place">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="invoice_quantity" class="col-sm-3 col-form-label">Invoice Quantity</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $invoice_quantity; ?>" id="txtInvoice_Quantity" placeholder="Invoice Quantity">
                      </div>
                    </div>
					<div class="form-group row">
						  <label for="Document_Proof" class="col-sm-3 col-form-label">Invoice File</label>
						  <div class="col-sm-5">
							<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg2' name='selectImg2' onchange='uploadInvoice()'>				
						  </div>
						  <?php
								if($invoicefile!=""){?>
								<div class="col-sm-4">          
									<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$invoicefile;?>" target="_blank">View</a>
								</div>

							<?php } ?>
                    </div>
					<div class="form-group row">
                      <label for="plant_quantity" class="col-sm-3 col-form-label">Plant Quantity</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $plant_quantity; ?>" id="txtPlant_quantity" placeholder="Plant Quantity">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="aggregator_wbs_number" class="col-sm-3 col-form-label">Aggregator Wbs Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $aggregator_wbs_number; ?>" id="txtAggregator_wbs_number" placeholder="Aggregator Wbs Number">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="aggregator_wbs_date" class="col-sm-3 col-form-label">Aggregator Wbs Date</label>
                      <div class="col-sm-9">
                        <input type="date" class="form-control form-control-sm"  maxlength="50" value="<?php echo date("Y-m-d",strtotime($aggregator_wbs_date)) ?>" id="txtAggregator_wbs_date" placeholder="Aggregator Wbs Date">
                      </div>
                    </div>
					<div class="form-group row">
						  <label for="Document_Proof" class="col-sm-3 col-form-label">WBS File</label>
						  <div class="col-sm-5">
							<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg3' name='selectImg3' onchange='uploadWBS()'>				
						  </div>
						  <?php
							if($wbsfile!=""){?>
								<div class="col-sm-4">          
									<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$wbsfile;?>" target="_blank">View</a>
								</div>
							<?php } ?>
                    </div>
					<div class="form-group row">
                      <label for="plant_wbs_number" class="col-sm-3 col-form-label">Plant Wbs Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $plant_wbs_number; ?>" id="txtPlantWbsNumber" placeholder="Plant Wbs Number">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="plant_wbs_date" class="col-sm-3 col-form-label">Plant Wbs Date</label>
                      <div class="col-sm-9">
                        <input type="date" class="form-control form-control-sm"  maxlength="50" value="<?php echo date("Y-m-d",strtotime($plant_wbs_date)) ?>" id="txtPlantWbsDate" placeholder="Plant Wbs Date">
                      </div>
                    </div>
					<div class="form-group row">
						  <label for="Document_Proof" class="col-sm-3 col-form-label">Plant WBS File</label>
						  <div class="col-sm-5">
							<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg4' name='selectImg4' onchange='uploadPWBS()'>				
						  </div>
						  <?php
							if($pwbsfile!=""){?>
								<div class="col-sm-4">          
									<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$pwbsfile;?>" target="_blank">View</a>
								</div>
							<?php } ?>
                    </div>
					<div class="form-group row">
                      <label for="vehicle_number" class="col-sm-3 col-form-label">Vehicle Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $vehicle_number; ?>" id="txtVehicleNumber" placeholder="Vehicle Number">
                      </div>
                    </div>
					
					<div class="form-group row">
						  <label for="Document_Proof" class="col-sm-3 col-form-label">Vehicle Number</label>
						  <div class="col-sm-5">
							<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg5' name='selectImg5' onchange='uploadVehicle()'>				
						  </div>
						  <?php
							if($vehiclefile!=""){?>
								<div class="col-sm-4">          
									<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$vehiclefile;?>" target="_blank">View</a>
								</div>
							<?php } ?>
                    </div>
					<div class="form-group row">
                      <label for="eway_bill_number" class="col-sm-3 col-form-label">Eway Bill Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $eway_bill_number; ?>" id="txtEway_bill_number" placeholder="Eway Bill Number">
                      </div>
                    </div>
					<div class="form-group row">
						  <label for="Document_Proof" class="col-sm-3 col-form-label">Eway File</label>
						  <div class="col-sm-5">
							<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg6' name='selectImg6' onchange='uploadEway()'>				
						  </div>
						  <?php
							if($ewayfile!=""){?>
								<div class="col-sm-4">          
									<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$ewayfile;?>" target="_blank">View</a>
								</div>
							<?php } ?>
                    </div>
					<div class="form-group row">
                      <label for="lr_number" class="col-sm-3 col-form-label">LR Number</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $lr_number; ?>" id="txtLr_Number" placeholder="LR Number">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="lr_date" class="col-sm-3 col-form-label">LR Date</label>
                      <div class="col-sm-9">
                        <input type="date" class="form-control form-control-sm"  maxlength="50" value="<?php echo date("Y-m-d",strtotime($lr_date)) ?>" id="txtLr_Date" placeholder="LR Date">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Document_Proof" class="col-sm-3 col-form-label">LR File</label>
                      <div class="col-sm-5">
						<input type='file' accept='.png, .jpg, .jpeg, .pdf' id='selectImg7' name='selectImg7' onchange='uploadLR()'>				
                      </div>
					  <?php
						if($lrfile!=""){?>
							<div class="col-sm-4" id="diveditimg">          
								<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$lrfile;?>" target="_blank">View</a>
							</div>
						<?php } ?>
                    </div>
                    <button type="submit" class="btn btn-success mr-2" id="btncreate" onclick="adddata()">Update</button>
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
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script>
var imgGRN = "";
var imgInvoice = "";
var imgWBS = "";
var imgPWBS = "";
var imgVehicle = "";
var imgEway = "";
var imgLR = "";

var Img1="";
var Img2="";
var Img3="";
var Img4="";
var Img5="";
var Img6="";
var Img7="";
var Img1Ext="";
var Img2Ext="";
var Img3Ext="";
var Img4Ext="";
var Img5Ext="";
var Img6Ext="";
var Img7Ext="";
var valrequestid="<?php echo $requestid; ?>";
var valpo_id="<?php echo $po_id; ?>";
var valstatusvalue="<?php echo $statusvalue; ?>";
var pagetype="<?php echo $pagetype; ?>";
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

//--
function uploadGRN(id){
	var name = document.getElementById('selectImg1'); 
	var hdnIDimg = name.files.item(0).name;
	Img1Ext = hdnIDimg.split('.').pop().toLowerCase();
	//--
	var reader = new FileReader();
	reader.readAsDataURL(name.files.item(0))
	reader.onload = function () {
    imgGRN = reader.result;
		if(Img1Ext=="pdf"){
			Img1 = imgGRN.replace(/^data:application\/[a-z]+;base64,/, "");
		}
		else{
			Img1 = imgGRN.replace(/^data:image\/[a-z]+;base64,/, "");
		}
	};
}
function uploadInvoice(id){
	var name = document.getElementById('selectImg2'); 
	
	var hdnIDimg = name.files.item(0).name;
	Img2Ext = hdnIDimg.split('.').pop().toLowerCase();
	//--
	var reader = new FileReader();
	reader.readAsDataURL(name.files.item(0))
	reader.onload = function () {
		imgInvoice = reader.result;
		if(Img2Ext=="pdf"){
			Img2 = imgInvoice.replace(/^data:application\/[a-z]+;base64,/, "");
		}
		else{
			Img2 = imgInvoice.replace(/^data:image\/[a-z]+;base64,/, "");
		}
	};
}
function uploadWBS(id){
	var name = document.getElementById('selectImg3'); 
	var hdnIDimg = name.files.item(0).name;
	Img3Ext = hdnIDimg.split('.').pop().toLowerCase();
	//--
	var reader = new FileReader();
	reader.readAsDataURL(name.files.item(0))
	reader.onload = function () {
		imgWBS = reader.result;
		if(Img3Ext=="pdf"){
			Img3 = imgWBS.replace(/^data:application\/[a-z]+;base64,/, "");
		}
		else{
			Img3 = imgWBS.replace(/^data:image\/[a-z]+;base64,/, "");
		}
	};
	console.log(Img3);
}
function uploadPWBS(id){
	var name = document.getElementById('selectImg4'); 
	var hdnIDimg = name.files.item(0).name;
	Img4Ext = hdnIDimg.split('.').pop().toLowerCase();
	//--
	var reader = new FileReader();
	reader.readAsDataURL(name.files.item(0))
	reader.onload = function () {
		imgPWBS = reader.result;
		if(Img4Ext=="pdf"){
			Img4 = imgPWBS.replace(/^data:application\/[a-z]+;base64,/, "");
		}
		else{
			Img4 = imgPWBS.replace(/^data:image\/[a-z]+;base64,/, "");
		}
	};
	console.log(Img4);
}
function uploadVehicle(id){
	var name = document.getElementById('selectImg5'); 
	var hdnIDimg = name.files.item(0).name;
	Img5Ext = hdnIDimg.split('.').pop().toLowerCase();
	//--
	var reader = new FileReader();
	reader.readAsDataURL(name.files.item(0))
	reader.onload = function () {
		imgVehicle = reader.result;
		if(Img5Ext=="pdf"){
			Img5 = imgVehicle.replace(/^data:application\/[a-z]+;base64,/, "");
		}
		else{
			Img5 = imgVehicle.replace(/^data:image\/[a-z]+;base64,/, "");
		}
	};
	console.log(Img5);
}
function uploadEway(id){
	var name = document.getElementById('selectImg6'); 
	var hdnIDimg = name.files.item(0).name;
	Img6Ext = hdnIDimg.split('.').pop().toLowerCase();
	//--
	var reader = new FileReader();
	reader.readAsDataURL(name.files.item(0))
	reader.onload = function () {
		imgEway = reader.result;
		if(Img6Ext=="pdf"){
			Img6 = imgEway.replace(/^data:application\/[a-z]+;base64,/, "");
		}
		else{
			Img6 = imgEway.replace(/^data:image\/[a-z]+;base64,/, "");
		}
	};
	console.log(Img6);
}

function uploadLR(id){
	var name = document.getElementById('selectImg7'); 
	var hdnIDimg = name.files.item(0).name;
	Img7Ext = hdnIDimg.split('.').pop().toLowerCase();
	var base647 = getBase64(name.files.item(0));
	//--
	var reader = new FileReader();
	reader.readAsDataURL(name.files.item(0))
	reader.onload = function () {
		imgLR = reader.result;
		if(Img7Ext=="pdf"){
			Img7 = imgLR.replace(/^data:application\/[a-z]+;base64,/, "");
		}
		else{
			Img7 = imgLR.replace(/^data:image\/[a-z]+;base64,/, "");
		}
	};
	console.log(Img7);
}
function getBase64(file) {
   var reader = new FileReader();
   reader.readAsDataURL(file);
   reader.onload = function () {
     return (reader.result);
   };
   reader.onerror = function (error) {
     return ('Error: ', error);
   };
}
//--
function adddata(){
		disableButton('#btncreate','<i class="ti-timer"></i>Processing');
		$.ajax({
		type:"POST",
		url:"aipEPRSUpadateDocumentData.php",
		data:{Img1:Img1,Img2:Img2,Img3:Img3,Img4:Img4,Img5:Img5,Img6:Img6,Img7:Img7,Img1Ext:Img1Ext,Img2Ext:Img2Ext,Img3Ext:Img3Ext,Img4Ext:Img4Ext,Img5Ext:Img5Ext,Img6Ext:Img6Ext,Img7Ext:Img7Ext,po_id:valrequestid,aggeragator_name: $("#txtAggregator_Name").val(),gst: $("#txtGST").val(),grn_number:$("#txtGRN_Number").val(),type_of_submission:$("#txtTypeofSubmission").val(),purchase_invoice_number:$("#txtPurchase_invoice_number").val(),purchase_invoice_date:$("#txtPurchaseInvoiceDate").val(),dispatched_state:$("#txtDispatchedState").val(),dispatched_place:$("#txtDispatchedPlace").val(),invoice_quantity:$("#txtInvoice_Quantity").val(),plant_quantity:$("#txtPlant_quantity").val(),aggregator_wbs_number:$("#txtAggregator_wbs_number").val(),aggregator_wbs_date:$("#txtAggregator_wbs_date").val(),plant_wbs_number:$("#txtPlantWbsNumber").val(),plant_wbs_date:$("#txtPlantWbsDate").val(),vehicle_number:$("#txtVehicleNumber").val(),eway_bill_number:$("#txtEway_bill_number").val(),lr_number:$("#txtLr_Number").val(),lr_date:$("#txtLr_Date").val(),category_name:$("#txtCategory_Name").val(),material_name:$("#txtMaterial_Name").val(),valstatusvalue:valstatusvalue},
		success:function(response){
			
			enableButton('#btncreate','Update');
			console.log(response);
			if($.trim(response)=="Success"){
				if(pagetype=="main"){
					showAlertRedirect("Success","Details Updated Successfully","success","pgViewPODetails.php?po_id="+valpo_id);
				}
				else{
					showAlertRedirect("Success","Details Updated Successfully","success","pgEPRSDocumentUpload.php?type=view&id="+valpo_id);
				}
			}else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	});   
  
}
</script>
</body>
</html>