<?php 
session_start();
if(!isset($_SESSION["employeeusername"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$company_id = $_SESSION["company_id"];
$po_id = $_REQUEST["po_id"];
$type = $_REQUEST["type"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueOther= $commonfunction->getSettingValue("OtherReason");
$settingValueOtherid= $commonfunction->getSettingValue("Otherid");
$settingValueEPRPO= $commonfunction->getSettingValue("PO");

$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");

$qry7 = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$po_id."')";
$statename = $sign->SelectF($qry7,'state');

$qry8 = "SELECT cd.CompanyName as companyname FROM tw_po_info pi LEFT JOIN tw_company_details cd ON pi.company_id=cd.ID WHERE pi.id='".$po_id."'";
$companyname = $sign->SelectF($qry8,'companyname');

$reasonQry="SELECT id,reason FROM tw_rejected_reason_master where visibility='true' AND panel='EPR' order by priority ASC";
$Reasons=$sign->FunctionOption($reasonQry,"",'reason',"id");
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
	  	    <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalRejectedReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Reason of Rejection</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body">
					<div class="form-group row">
						<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<div class="form-group row">	
								<div class="card-body" >
										<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
											<label class="col-sm-12">Select rejection reason</label>
											<select class="form-control" placeholder="Reason of Rejection" id="txtInputReason" >
											<option value="">Choose...</option>
											<?php echo  $Reasons; ?>
											</select>
										</div>
										<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12" id="reasondiv">
											<label class="col-sm-12">Enter Rejection reason</label>
											<textarea class="form-control" id="txReason" maxlength="5000"  placeholder="Reason of Rejection"></textarea>
										</div>
								</div>
							</div>
									  
						</div>
					</div>
					
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal();">Close</button>
			<button type="button" class="btn btn-success" id="Status" onclick="adddataReject();">Submit</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
  <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalViewReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Reason of Rejection</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body">
					<div class="form-group row">
					
									<label class="col-sm-12">Rejection reason</label>
                                    <textarea class="form-control" id="txtViewReason" maxlength="5000"  placeholder="Reason of Rejection"></textarea>
								
					</div>
					
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalViewReason();">Close</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
       <!-- partial -->
      <div class="main-panel">        
         <div class="content-wrapper">
          <div class="row">
           
		<div class="col-md-12 col-md-12 col-sm-12 col-xs-12 col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body ">
                  <h4 class="card-title">Document Upload for <?php echo $companyname; ?> [<?php echo $statename; ?>]</h4>
					<?php if($type=="accept"){ ?>
					<a href="pgEPRSGenerateDocuments.php?po_id=<?php echo $po_id; ?>" class="float-right pointer-cursor" >  </a><a onClick="CSV();" class="float-right pointer-cursor" ><i class="fa fa-download" aria-hidden="true"></i> Export To Excel  </a>
					<?php } ?>
					 <div class="table-responsive Flipped">
						<table id="tableData" class="table FContent">
						  
						</table>
					 </div>
					 <?php if($type=="ongoing"){?>
					 <div id="divButton">
					 
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 grid-margin stretch-card">
						<button type='button' id='btnAccept' class='btn btn-success' onclick='PutData(0,"check");'>Accept</button>
						<button type='button' id='btnReject' class='btn btn-danger' onclick='showModal(0,"check");'>Reject</button>
					</div>
			  </div>
			  <?php } ?>
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
var valsettingValueOther='<?php echo $settingValueOther;?>';

var imgGRN = "";
var imgInvoice = "";
var imgWBS = "";
var imgPWBS = "";
var imgVehicle = "";
var imgEway = "";
var imgLR = "";
var valpoid='<?php echo $po_id; ?>';

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


$(document).ready(function(){
	showData(valtype);
	document.getElementById( 'reasondiv' ).style.display = 'none';
});
$("#txtInputReason").on('change keyup paste', function () {
	var valtext = $('#txtInputReason option:selected').text();
	if(valtext.trim()==valsettingValueOther){
		document.getElementById( 'reasondiv' ).style.display = 'block';
	}
	else{
		document.getElementById( 'reasondiv' ).style.display = 'none';
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


$(document).ready(function(){
	showData(valtype);
});
function showData(valtype){
	$.ajax({
		type:"POST",
		url:"apiAuditorPORecordDetails.php",
		data:{poid:valpoid,type:valtype},
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
		data:{Img1:Img1,Img2:Img2,Img3:Img3,Img4:Img4,Img5:Img5,Img6:Img6,Img7:Img7,Img1Ext:Img1Ext,Img2Ext:Img2Ext,Img3Ext:Img3Ext,Img4Ext:Img4Ext,Img5Ext:Img5Ext,Img6Ext:Img6Ext,Img7Ext:Img7Ext,po_id:id},
		success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					showAlert("Success","Data Added Successfully","success");
					showData(valtype);

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

//-----------------Accept PO Details Starts--------------------//

function checkAll(){
	var yes = document.getElementById("cbCheckall");  
	if (yes.checked == true){  
		// selecting all checkboxes
		var checkboxes = document.getElementsByName('cbCheck');
		var values = [];
		// looping through all checkboxes
		for (var i = 0; i < checkboxes.length; i++) {
		  checkboxes[i].checked = true;
		  values.push(checkboxes[i].value);
		}
	}
	else{
		// deselecting all checkboxes
		var checkboxes = document.getElementsByName('cbCheck');
		var values = [];
		// looping through all checkboxes
		for (var i = 0; i < checkboxes.length; i++) {
		  checkboxes[i].checked = false;
		  values.push(checkboxes[i].value);
		}
		
	}
}
function checkIndividual(){
	document.getElementById("cbCheckall").checked = false;
	
}
function PutData(id,type){
	if(type=="check")
	{
		var value="";
		$('.cbCheck:checkbox:checked').each(function(){
			value=value+$(this).val()+",";
		});
		str=value.replace(/[, ]+$/, "").trim();
		
	}
	else
	{
		str=id;
	}
	if(str==""){
		showAlert("Warning","Please select Data","warning");
	}
	else{	
		if(type=="check")
		{
			document.getElementById("btnAccept").style.pointerEvents = "none";
			document.getElementById("btnReject").style.pointerEvents = "none";
		}
		else{
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
		}
		showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
			if(confirmed==true){	
				$.ajax({
					type:"POST",
					url:"apiAcceptAuditorPORecordsDetails.php",
					data:{str:str,type:type,po_id:valpoid},	
					success:function(response){
					console.log(response);
						if(response=="Success"){
							showAlertRedirect("Success","Data Added Successfully","success","pgAuditorPORecordDetails.php?type=ongoing&po_id="+valpoid);
						}
						else{
							showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
						}
					}
				}); 

			}
			else{
				location.reload();
			}
		});			
	}
	
}
//----------------------// 
function adddataReject(){
	var valtext = $('#txtInputReason option:selected').text();
	if(!validateBlank($("#txtInputReason").val())){
		setErrorOnBlur("txtInputReason");
	}
	else if(valtext.trim()==valsettingValueOther){
		if(!validateBlank($("#txReason").val())){
			setErrorOnBlur("txReason");
		}
		else{
			SubmitReject();
		}
	}
	else{
		SubmitReject();
	}
}
function SubmitReject(){
	
	if(varrejtype=="check")
			{
				var value="";
				$('.cbCheck:checkbox:checked').each(function(){
					value=value+$(this).val()+",";
				});
				str=value.replace(/[, ]+$/, "").trim();
			}
			else
			{
				str=varrejid;
			}
			if(str==""){
				showAlert("Warning","Please select Data for rejection","warning");
			}
			else{
				if(varrejtype=="check")
				{
					document.getElementById("btnAccept").style.pointerEvents = "none";
					document.getElementById("btnReject").style.pointerEvents = "none";
				}
				else{
					document.getElementById("alinkaccept").style.pointerEvents = "none";
					document.getElementById("alinkreject").style.pointerEvents = "none";
				}
				showConfirmAlert('Confirm action!', 'Are you sure to Reject this record?','question', function (confirmed){
				if(confirmed==true){
						$.ajax({
							type:"POST",
							url:"apiRejectAuditorPORecordDetails.php",
							data:{str:str,reason:$("#txtInputReason").val(),reasontext:$("#txReason").val(),type:varrejtype,po_id:valpoid},	
							success:function(response){
							console.log(response);
								if(response=="Success"){
									showAlertRedirect("Success","Data Rejected Successfully","success","pgAuditorPORecordDetails.php?type=ongoing&po_id="+valpoid);
								}
								else{
									showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
								}
							}
						}); 
					}else{
						location.reload();
					}
					 
				});
			}
			
}
function showModal(id,type)
{	
	varrejid = id;
	varrejtype = type;
	jQuery.noConflict();
	$("#modalRejectedReason").modal("show");
	document.getElementById( 'reasondiv' ).style.display = 'none';
}
function closeModal() {
	
  $("#modalRejectedReason").modal("hide");
  location.reload();
}
function getReason(id) {
	$.ajax({
	  type:"POST",
	  url:"apiInwardViewReject.php",
	  data:{id:id},
	  success:function(response){
		  console.log(response);
		  showModalViewReason();
		  $("#txtViewReason").val(response);
	  }
  }); 
}

//-----------------Accept PO Details Ends----------------------//
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
			url:"AuditorEPRSExportDataCSV.php",
			data:{type:'viewall',po_id:valpoid},
			success:function(response){
				console.log(response);
				if($.trim(response)=="success"){
					$(location).attr('href','EPRSExportDataCSV.csv');
				} 
			}
		});
}
//------------------------------ View Record Starts---------------------------------//

/* function viewrecord(id){
	alert(id);
		$.ajax({
			type:"POST",
			url:"apiViewTableRecord.php",
			data:{id:id},
			success:function(response){
				console.log(response);
				if($.trim(response)=="success"){
				showAlert("Success","Record Viewed Successfully","success"); 	
				} 
			}
		}); 
} */

//------------------------------ View Record Ends---------------------------------//
</script>
</body>
</html>