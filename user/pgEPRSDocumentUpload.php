<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$company_id = $_SESSION["company_id"];
$poid = $_REQUEST["id"];
$type = $_REQUEST["type"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueOtherid= $commonfunction->getSettingValue("Otherid");
$settingValueEPRPO= $commonfunction->getSettingValue("PO");
$ComplianceImage=$commonfunction->getCommonDataValue("Compliance Image");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");

$qry7 = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$poid."' order by state)";
$statename = $sign->SelectF($qry7,'state');

$qry = "SELECT cd.CompanyName as companyname,cd.Status,cd.compliance_status FROM tw_po_info pi LEFT JOIN tw_company_details cd ON pi.company_id=cd.ID WHERE pi.id='".$poid."'";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$companyname = $decodedJSON->response[0]->companyname;
$Status = $decodedJSON->response[1]->Status;
$compliance_status = $decodedJSON->response[2]->compliance_status;

?>
<!DOCTYPE html>
<html lang="en">
        
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Document Upload</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
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
       <!-- partial -->
      <div class="main-panel">        
         <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">	
              <div class="card">
                <div class="card-body ">
                  <h4 class="card-title">Document Upload for <?php echo $companyname; ?><?php if($Status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$VerifiedImage;?>" data-toggle="tooltip" data-placement="top" title="This is a verified company"/><?php }?><?php if($compliance_status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$ComplianceImage;?>" data-toggle="tooltip" data-placement="top" title="This is a compliant company"/><?php }?> [<?php echo $statename; ?>]</h4>
				  <?php if($type=="accept"){ ?>
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
							<a href="pgEPRSGenerateDocuments.php?po_id=<?php echo $poid; ?>" class="float-right pointer-cursor" ><i class="fa fa-download" aria-hidden="true"></i> Document  </a><a onClick="CSV();" class="float-right pointer-cursor" ><i class="fa fa-download" aria-hidden="true"></i> Export To Excel | </a>
							<a onclick="filter();" title="Date Filter"><i class="ti-filter float-right pointer-cursor"></i></a>							
						</div>
					</div>
					<?php } ?>
					 <div class="table-responsive Flipped">
						<table id="tableData" class="table FContent">
						  
						</table>
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>
var imgGRN = "";
var imgInvoice = "";
var imgWBS = "";
var imgPWBS = "";
var imgVehicle = "";
var imgEway = "";
var imgLR = "";
var valpoid='<?php echo $poid; ?>';
var valtype='<?php echo $type; ?>';
var valsettingValueOtherid='<?php echo $settingValueOtherid; ?>';
const input1 = document.getElementById("selectImg1");
const input2 = document.getElementById("selectImg2");
const input3 = document.getElementById("selectImg3");
const input4 = document.getElementById("selectImg4");
const input5 = document.getElementById("selectImg5");
const input6 = document.getElementById("selectImg6");
const input7 = document.getElementById("selectImg7");

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


$(document).ready(function(){
	showData();
});
function showData(){
	$.ajax({
		type:"POST",
		url:"apiGetEPRSDocumentUpload.php",
		data:{poid:valpoid,type:valtype,pagetype:'full'},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);
			
		}
	});
}
function filter(){
	valtype='date';
	$.ajax({
		type:"POST",
		url:"apiGetEPRSDocumentUpload.php",
		data:{poid:valpoid,type:valtype,startdate:$("#txtStartDate").val(),enddate:$("#txtEndDate").val(),pagetype:'full'},
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
					showData();

				}
				else if($.trim(response)=="setauditor"){
					showAlert("Warning","Please assign auditor","warning");
					showData();

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
						showData();

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
				console.log(response);
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
</script>
</body>
</html>