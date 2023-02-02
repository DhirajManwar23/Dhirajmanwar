<?php
session_start();
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();

$plant_wbs_date=date("Y-m-d");
$po_id = $_REQUEST["po_id"];
$_SESSION["EPRServicePOId"] = $po_id;
$ComplianceImage=$commonfunction->getCommonDataValue("Compliance Image");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
$qryPO="select po_number,company_id,supplier_id from tw_po_info where id = '".$po_id."'";
$retValPO = $sign->FunctionJSON($qryPO);
$decodedJSON = json_decode($retValPO);
$po_number = $decodedJSON->response[0]->po_number;
$company_id = $decodedJSON->response[1]->company_id;
$supplier_id = $decodedJSON->response[2]->supplier_id;

$qry = "SELECT cd.CompanyName,cd.Status,cd.Company_Logo,cc.value,cd.compliance_status FROM tw_company_details cd LEFT JOIN tw_company_contact cc ON cd.ID = cc.company_id WHERE cd.ID = '".$company_id."' ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$Status = $decodedJSON->response[1]->Status;
$cCompany_Logo = $decodedJSON->response[2]->Company_Logo;
$cuserid = $decodedJSON->response[3]->value;
$compliance_status = $decodedJSON->response[4]->compliance_status;

$qrystate = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$po_id."')";
$retqrystate = $sign->SelectF($qrystate,"state");
		

$settingValueUserImagePathEPRServicesDocument=$commonfunction->getSettingValue("UserImagePathEPRSDocument");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueOngoingStatus= $commonfunction->getSettingValue("Ongoing Status");
$settingValueOtherid= $commonfunction->getSettingValue("Otherid");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");

$qryPending="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValuePendingStatus."'";
$qryCntPending = $sign->Select($qryPending);

$qryCompleted="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValueCompletedStatus."'";
$qryCntCompleted = $sign->Select($qryCompleted);

$qryAwaiting="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValueAwaitingStatus."'";
$qryCntAwaiting = $sign->Select($qryAwaiting);	

$qryOngoing="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValueOngoingStatus."'";
$qryCntOngoing = $sign->Select($qryOngoing);			

$qryRejected="select count(*) as cnt from tw_temp where po_id='".$po_id."' and status = '".$settingValueRejectedStatus."'";
$qryCntRejected = $sign->Select($qryRejected);	

$countawaiting = $qryCntAwaiting+$qryCntOngoing;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | View PO Details</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  
  <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/custom/style.css">
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
<!-----------------------------------------------First Row Starts -----------------------------------------> 	
		
          <div class="row float-right">
				<button type="button" class="btn btn-success btn-icon-text" onClick="CSVDownload();">
				  <i class="ti-download btn-icon-prepend"></i>Download Template
				</button>&nbsp
				<input type="file" accept=".csv" id="imgupload" style="display:none"/> 
				<button type="button"  id="OpenImgUpload" class="btn btn-info btn-icon-text" >
					<i class="ti-upload btn-icon-prepend"></i>Upload CSV
				</button>

		  </div>
		  <br>
		  <br>
		  <br>
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 com-xs-12 col-12 grid-margin">
             <div class="card">
                <div class="card-body">
					<div style="text-align:center;">
						<img src="<?php if($cCompany_Logo==""){echo $settingValueUserImagePathOther.$settingValueUserCompanyImage; }else{ echo $settingValueUserImagePathVerification.$cuserid."/".$cCompany_Logo;}?>" class="img-lg rounded-circle mb-3" />
						<br>
						<h1 class="display-4"><?php echo $CompanyName; ?> <?php if($Status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$VerifiedImage;?>" data-toggle="tooltip" data-placement="top" title="This is a verified company"/><?php }?><?php if($compliance_status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$ComplianceImage;?>" data-toggle="tooltip" data-placement="top" title="This is a compliant company"/><?php }?>
						</h1>
						<h5><small class="text-muted"><?php echo $retqrystate; ?></small></h5>
					</div>
                </div>
              </div>
			  <br>
			  <div class="card">
                <div class="card-body">
					<div>
						<nav class="sidebar" id="sidebar">
							<ul class="nav pointer-cursor">
							  <li class="nav-item">
								<a class="nav-link" onclick="dashboard();">
								  <i clQass="icon-grid menu-icon"></i>
								  <span class="menu-title">Dashboard</span>
								</a>
							  </li> 
							  <li class="nav-item">
								<a class="nav-link" onclick="showData('edit');">
								  <i clQass="icon-grid menu-icon"></i>
								  <span class="menu-title">View Pending (<?php echo $qryCntPending; ?>)</span>
								</a>
							  </li> 
							  <li class="nav-item">
								<a class="nav-link" onclick="showData('view');">
								  <i clQass="icon-grid menu-icon"></i>
								  <span class="menu-title">View Awaiting (<?php echo $countawaiting; ?>)</span>
								</a>
							  </li>
							  <li class="nav-item">
								<a class="nav-link" onclick="showData('accept');">
								  <i clQass="icon-grid menu-icon"></i>
								  <span class="menu-title">View Fulfilled (<?php echo $qryCntCompleted; ?>)</span>
								</a>
							  </li>
							  <li class="nav-item">
								<a class="nav-link" onclick="showData('query');">
								  <i clQass="icon-grid menu-icon"></i>
								  <span class="menu-title">Queries (<?php echo $qryCntRejected; ?>)</span>
								</a>
							  </li>
							</ul>
						</nav>
					</div>
                </div>
              </div>
             </div>
			 <div class="col-lg-8 col-md-8 col-sm-8 com-xs-12 col-12 grid-margin">
				 <div class="card" id="divPo">
					<div class="card-body">
						<div class="form-group row" id="divDate">
							<div class="col-ld-4 col-md-4 col-sm-4 col-xs-4 col-4">
								<label>Start Date</label>
								<input type="date" class="form-control" id="txtStartDate" placeholder="Start Date">							
							</div>
							<div class="col-ld-4 col-md-4 col-sm-4 col-xs-4 col-4">
								<label>End Date</label>
								<input type="date" class="form-control" id="txtEndDate" placeholder="End Date">							
							</div>
							<div class="col-ld-4 col-md-4 col-sm-4 col-xs-4 col-4">
								<a href="pgEPRSGenerateDocuments.php?po_id=<?php echo $po_id; ?>" title="View Documents" target="_blank" id="alinkDocuments"><i class="ti-file float-right pointer-cursor"></i></a>
								<a onclick="fullpage();" title="View Fullscreen" target="_blank"><i class="ti-fullscreen float-right pointer-cursor"></i></a>
								
								<a onclick="CSVAll();" title="Export to Excel" id="alinkExport"><i class="ti-receipt float-right pointer-cursor"></i></a>
								<a onclick="filter();" title="Date Filter"><i class="ti-filter float-right pointer-cursor"></i></a>						
							</div>
						</div>
						
						<div class="table-responsive Flipped">
							<table id="tableData" class="table FContent">
							  
							</table>
						</div>
					</div>
				  </div>
				  <div class="card" id="divDashboard">
					<div class="card-body">
						 <!--Row Start -->
						 <div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
							  
								<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
								  <div class="card card-tale">
									<div class="card-body">
									  <p class="mb-4">View Pending</p>
									  <p class="fs-30 mb-2"><?php echo $qryCntPending; ?></p>
									  <p></p>
									</div>
								  </div>
								  </div>
								<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
								  <div class="card card-dark-blue">
									<div class="card-body">
									  <p class="mb-4">View Awaiting</p>
									  <p class="fs-30 mb-2"><?php echo $countawaiting; ?></p>
									 
									</div>
								  </div>
								 </div>
							   <div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
								  <div class="card card-light-blue">
									<div class="card-body">
									  <p class="mb-4">View Fulfilled</p>
									  <p class="fs-30 mb-2"><?php echo $qryCntCompleted; ?></p>
									 
									</div>
								  </div>
								  </div>
							   <div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
								  <div class="card card-light-danger">
									<div class="card-body">
									  <p class="mb-4">View Queries</p>
									  <p class="fs-30 mb-2"><?php echo $qryCntRejected; ?></p>
									  
									</div>
								  </div>
								  </div>
							  
							  </div>
						  </div>
						  <!--Row End -->
					</div>
				  </div>
             </div>
           </div> 
			
		 <!--------First Row Ends ----> 	   
		 
		<!--------Third Row Starts -------> 	
		
		<!--------Third Row Ends -------> 	
         </div> 
		     
	   <?php
			include_once("footer.php");
		?>
	</div>
			              
			 
   </div>
    
</div>  
      <!-- main-panel ends -->
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>

var valpoid='<?php echo $po_id; ?>';
var valsettingValueOtherid='<?php echo $settingValueOtherid; ?>';

var valtype="";
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
$(document).ready(function(){
	$("#divPo").attr("style", "display:none");
	$("#divDashboard").attr("style", "display:block");
	$('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });
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
function dashboard(){
	$("#divPo").attr("style", "display:none");
	$("#divDashboard").attr("style", "display:block");
}
function fullpage(){
	if(valtype=="edit"){
		window.location.href = "pgEPRSDocumentUpload.php?type=edit&id="+valpoid;
	}
	else if(valtype=="view"){
		window.location.href = "pgEPRSDocumentUpload.php?type=view&id="+valpoid;
	}
	else if(valtype=="accept"){
		window.location.href = "pgEPRSDocumentUpload.php?type=accept&id="+valpoid;
	}
	else{
		window.location.href = "pgEPRSDocumentUpload.php?type=query&id="+valpoid;
	}
	
}
function showData(type){
	$("#divPo").attr("style", "display:block");
	$("#divDashboard").attr("style", "display:none");
	
	valtype=type;
	if(valtype!='accept'){
		$("#alinkDocuments").attr("style", "display:none");
		$("#alinkExport").attr("style", "display:none");
	} 
	else{
		$("#alinkDocuments").attr("style", "display:block");
		$("#alinkExport").attr("style", "display:block");
	}
	$.ajax({
		type:"POST",
		url:"apiGetEPRSDocumentUpload.php",
		data:{poid:valpoid,type:valtype,pagetype:'main'},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);
			
		}
	});
}
function uploadGRN(id){
	var name = document.getElementById('selectImg1-'+id); 
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
	document.getElementById("select1-"+id).style.color = "green";
}
function uploadInvoice(id){
	var name = document.getElementById('selectImg2-'+id); 
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
	document.getElementById("select2-"+id).style.color = "green";
}
function uploadWBS(id){
	var name = document.getElementById('selectImg3-'+id); 
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
	document.getElementById("select3-"+id).style.color = "green";
}
function uploadPWBS(id){
	var name = document.getElementById('selectImg4-'+id); 
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
	document.getElementById("select4-"+id).style.color = "green";
}
function uploadVehicle(id){
	var name = document.getElementById('selectImg5-'+id); 
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
	document.getElementById("select5-"+id).style.color = "green";
}
function uploadEway(id){
	var name = document.getElementById('selectImg6-'+id); 
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
	document.getElementById("select6-"+id).style.color = "green";
}

function uploadLR(id){
	var name = document.getElementById('selectImg7-'+id); 
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
	document.getElementById("select7-"+id).style.color = "green";
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
function adddata(id){
	if(Img1=="" && Img2=="" && Img3=="" && Img4=="" && Img5=="" && Img6=="" && Img7==""){
		showAlert("warning","Please Upload one image","warning");
	}
	else{
		document.getElementById("alink").style.pointerEvents = "none";
		$.ajax({
		type:"POST",
		url:"apiUploadSaveDocumentData.php",
		data:{Img1:Img1,Img2:Img2,Img3:Img3,Img4:Img4,Img5:Img5,Img6:Img6,Img7:Img7,Img1Ext:Img1Ext,Img2Ext:Img2Ext,Img3Ext:Img3Ext,Img4Ext:Img4Ext,Img5Ext:Img5Ext,Img6Ext:Img6Ext,Img7Ext:Img7Ext,tempid:id,valpoid:valpoid},
		success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					showAlert("Success","Data Added Successfully","success");
					showData('edit');

				}
				else if($.trim(response)=="setauditor"){
					showAlert("Warning","Please assign auditor","warning");
					showData('edit');

				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
			}
		});  
	}
} 
function RejectRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure to delete this record?','question', function (confirmed){
		if(confirmed==true){
			
			document.getElementById("alinkreject").style.pointerEvents = "none";
			var valquery = "delete from tw_temp where id="+id;
			 $.ajax({
				type:"POST",
				url:"apiCommonQuerySingle.php",
				data:{valquery:valquery},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
						showAlert("Success","Record Deleted Successfully","success"); 
						showData(valtype);

					}
					else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
					}
					
				}
		});  
			
		}
	});
}
function viewReason(id,tempid){
	if(id==valsettingValueOtherid){
		var valquery="Select reasontext as reason from tw_temp where id="+tempid;
	}
	else{
		var valquery="Select reason from tw_rejected_reason_master where id="+id;
	}
		 $.ajax({
			type:"POST",
			url:"apiGetViewRejectedReason.php",
			data:{valquery:valquery},
			success:function(response){
				showAlert("",response,"");
			}
	});  
}
function CSV(){
	$.ajax({
		type:"POST",
		url:"EPRSExportDataCSV.php",
		data:{type:'viewall',po_id:valpoid},
		success:function(response){
			console.log(response);
			if($.trim(response)=="success"){
				$(location).attr('href','EPRSExportDataCSV.csv');
			} 
		}
	});
	
	
}
function CSVDownload(){
		$.ajax({
			type:"POST",
			url:"EPRSExportTemplateDataCSV.php",
			data:{},
			success:function(response){
				console.log(response);
				if($.trim(response)=="success"){
					$(location).attr('href','EPRSExportTemplateDataCSV.csv');
				} 
			}
		});
}
function CSVAll(){
	var valfilertype="";
	if($("#txtStartDate").val()!="" && $("#txtEndDate").val()!="")
	{
		valfilertype="date";
		
	}
	else{
		valfilertype="viewall";
	}
	$.ajax({
		type:"POST",
		url:"EPRSExportDataCSV.php",
		data:{type:valfilertype,po_id:valpoid,startdate:$("#txtStartDate").val(),enddate:$("#txtEndDate").val()},
		success:function(response){
			console.log(response);
			if($.trim(response)=="success"){
				$(location).attr('href','EPRSExportDataCSV.csv');
			} 
		}
	});
}
function filter(){
	valtype='date';
	$.ajax({
		type:"POST",
		url:"apiGetEPRSDocumentUpload.php",
		data:{poid:valpoid,type:valtype,startdate:$("#txtStartDate").val(),enddate:$("#txtEndDate").val(),pagetype:'half'},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);
			
		}
	});
}
$("#imgupload").on('change',function(){
		showConfirmAlert('Confirm action!', 'Do you want upload this file?','question', function (confirmed){
		if(confirmed==true){
			uploadFile();			
		}
	});
});
function uploadFile() {
   var files = document.getElementById("imgupload").files;
   if(files.length > 0 ){

      var formData = new FormData();
      formData.append("file", files[0]);

      var xhttp = new XMLHttpRequest();
      // Set POST method and ajax file path
      xhttp.open("POST", "apiFileUpload.php", true);

      // call on request changes state
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {

           var response = this.responseText;
		   console.log(response);
           if(response == 1){
			   showAlertRedirect("Success","File Upload Successfully","success","pgViewPODetails.php?po_id="+valpoid);			  
           }else{
			   showAlert("Warning","File not uploaded.","warning");
           }
         }
      };
      // Send request with data
      xhttp.send(formData);

   }else{
	   showAlert("Warning","Please select a file","warning");
   }

}
</script>
</body>
</html>
