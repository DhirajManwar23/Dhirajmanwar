<?php
session_start();
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();

$plant_wbs_date=date("Y-m-d");
$request_id = $_REQUEST["id"];
$requesttype = $_REQUEST["type"];

$company_id = $_SESSION["company_id"];
$employee_id = $_SESSION["employee_id"];

$settingValueOther= $commonfunction->getSettingValue("OtherReason");

$qryPendingPO="select  aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,reason,category_name,material_name,status,po_id from tw_temp where id = '".$request_id."' order by id Asc";

$retValPendingPO = $sign->FunctionJSON($qryPendingPO);
$decodedJSON = json_decode($retValPendingPO);

$aggeragator_name = $decodedJSON->response[0]->aggeragator_name;
$gst = $decodedJSON->response[1]->gst;
$grn_number = $decodedJSON->response[2]->grn_number;
$type_of_submission = $decodedJSON->response[3]->type_of_submission;	
$grnfile = $decodedJSON->response[4]->grnfile;	
$purchase_invoice_number = $decodedJSON->response[5]->purchase_invoice_number;	

$purchase_invoice_date = $decodedJSON->response[6]->purchase_invoice_date;	
$purchase_invoice_date=date("d-m-Y", strtotime($purchase_invoice_date));

$dispatched_state = $decodedJSON->response[7]->dispatched_state;	
$dispatched_place = $decodedJSON->response[8]->dispatched_place;
$invoice_quantity = $decodedJSON->response[9]->invoice_quantity;	
$invoicefile = $decodedJSON->response[10]->invoicefile;
$plant_quantity = $decodedJSON->response[11]->plant_quantity;
$aggregator_wbs_number = $decodedJSON->response[12]->aggregator_wbs_number;	

$aggregator_wbs_date = $decodedJSON->response[13]->aggregator_wbs_date;
$aggregator_wbs_date=date("d-m-Y", strtotime($aggregator_wbs_date)); 

$wbsfile = $decodedJSON->response[14]->wbsfile;
$plant_wbs_number = $decodedJSON->response[15]->plant_wbs_number;

$plant_wbs_date = $decodedJSON->response[16]->plant_wbs_date;	
$plant_wbs_date=date("d-m-Y", strtotime($plant_wbs_date)); 

$pwbsfile = $decodedJSON->response[17]->pwbsfile;
$vehicle_number = $decodedJSON->response[18]->vehicle_number;
$vehiclefile = $decodedJSON->response[19]->vehiclefile;	
$eway_bill_number = $decodedJSON->response[20]->eway_bill_number;
$ewayfile = $decodedJSON->response[21]->ewayfile;
$lr_number = $decodedJSON->response[22]->lr_number;

$lr_date = $decodedJSON->response[23]->lr_date;
$lr_date=date("d-m-Y", strtotime($lr_date)); 

$lrfile = $decodedJSON->response[24]->lrfile;
$reason = $decodedJSON->response[25]->reason;
$category_name = $decodedJSON->response[26]->category_name;
$material_name = $decodedJSON->response[27]->material_name;
$status = $decodedJSON->response[28]->status;
$po_id = $decodedJSON->response[29]->po_id;

$queryrejectedreason="SELECT reason FROM tw_rejected_reason_master where id='".$reason."'";
$rejectedreason=$sign->SelectF($queryrejectedreason,"reason");

$settingValueUserImagePathEPRServicesDocument=$commonfunction->getSettingValue("UserImagePathEPRSDocument");
$settingValueUserImagePathEPRServicesDocument.$lrfile;
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");

$reasonQry="SELECT id,reason FROM tw_rejected_reason_master where visibility='true' AND panel='EPR' order by priority ASC";
$Reasons=$sign->FunctionOption($reasonQry,"",'reason',"id");


$querySelEmp="SELECT employee_role FROM tw_employee_registration where id='".$employee_id."'";
$SelEmp=$sign->SelectF($querySelEmp,"employee_role");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | View Record</title>
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
		?>	  	    <!-- ==============MODAL START ================= -->
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
<!-----------------------------------------------First Row Starts -----------------------------------------> 	
          <div class="row">
            <div class="col-lg-12 col-md-12 grid-margin">
              <div class="card">
                <div class="card-body">
					<div style="text-align:center;">
						<h3><?php  if($aggeragator_name==""){echo "---" ;} else { echo $aggeragator_name;}?></h3>
					</div>
					<br>
					<h5>Aggregator GST: <?php if($gst==""){echo "---" ;} else { echo $gst;}?></h5>				
					<h5>Type Of Submission: <?php if($type_of_submission==""){echo "---" ;} else {echo $type_of_submission;}?></h5>
						
				</div>
               </div>
             </div>
           </div> 
			
		 <!-----------------------------------------------First Row Ends -----------------------------------------> 	   
		 <!-----------------------------------------------Second Row Starts -----------------------------------------> 	
		<div class="row">
			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Aggregator GRN: <?php if($grn_number==""){echo "---" ;} else { echo $grn_number;}?></h5>	
						<h5>GRN File: <?php if($grnfile!=""){?>
						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$grnfile;?>" target="_blank">View</a>
						<?php }else {echo "---";}?></h5>
					</div>
				</div>
			</div>  


			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Purchase Invoice Number: <?php if($purchase_invoice_number==""){echo "---" ;} else{ echo $purchase_invoice_number;}?></h5>	
						<h5>Purchase Invoice Date: <?php if($purchase_invoice_date==""){echo "---" ;} else{ echo $purchase_invoice_date;}?></h5>	
					</div>
				</div>
			</div> 

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
					<h5>Dispatched State: <?php if($dispatched_state==""){echo "---" ;} else{ echo $dispatched_state;}?></h5>				
					<h5>Dispatched Place:<?php if($dispatched_place==""){echo "---" ;} else{ echo $dispatched_place;}?></h5>	
					</div>
				</div>
			</div>
		</div>
             
<!-----------------------------------------------Second Row Ends -----------------------------------------> 
<!-----------------------------------------------Third Row Starts -----------------------------------------> 
		
		<div class="row">
			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Invoice Quantity: <?php if($invoice_quantity==""){echo "---" ;} else{ echo $invoice_quantity;}?></h5>
						<h5>Invoice File:<?php if($invoicefile!=""){?>
						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$invoicefile;?>" target="_blank">View</a>
						<?php }else {echo "---";}?></h5>
					</div>
				</div>
			</div> 

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Aggregator WBS Number: <?php if($aggregator_wbs_number==""){echo "---" ;} else{ echo $aggregator_wbs_number;}?></h5>		
						<h5>Aggregator WBS Date: <?php if($aggregator_wbs_date==""){echo "---" ;} else{ echo $aggregator_wbs_date;}?></h5>		
						<h5> WBS File: <?php if($wbsfile!=""){?>
						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$wbsfile;?>" target="_blank">View</a>
						<?php }else{ echo"---";}?></h5>		
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Plant Quantity: <?php if($plant_quantity==""){echo "---" ;} else{  echo $plant_quantity;}?></h5>
						<h5>Plant WBS Number: <?php if($plant_wbs_number==""){echo "---" ;} else{  echo $plant_wbs_number;}?></h5>		
						<h5>Plant WBS Date: <?php if($plant_wbs_date==""){echo "---" ;} else{  echo $plant_wbs_date;}?></h5>		
						<h5> Plant WBS File: <?php if($pwbsfile!=""){?>

						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$pwbsfile;?>" target="_blank">View</a>

						<?php } else{ echo"---";}?></h5>		
					</div>
				</div>
			</div>
		</div>
<!-----------------------------------------------Third Row Ends ----------------------------------------->       
<!-----------------------------------------------Fourth Row Starts ----------------------------------------->       

		<div class="row">
			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<div style="text-align:left;">
							<h5>Vehicle Number: <?php if($vehicle_number==""){echo "---" ;} else{ echo $vehicle_number;}?></h5>
							<h5>Vehicle File:<?php if($vehiclefile!=""){?>
							<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$vehiclefile;?>" target="_blank">View</a>
							<?php } else{ echo"---";}?></h5>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<div style="text-align:left;">
						<h5>Eway Bill Number: <?php if($eway_bill_number==""){echo "---" ;} else{ echo $eway_bill_number;}?></h5>
						<h5>Eway File: <?php if($ewayfile!=""){?>

						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$ewayfile;?>" target="_blank">View</a>
						<?php } else{ echo"---";}?>	</h5>
						</div>	
					</div>
				</div>
			</div> 

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<div style="text-align:left;">
							<h5>LR Number: <?php if($lr_date==""){echo "---" ;} else { echo $lr_number;}?></h5>
							<h5>LR Date: <?php if($lr_date==""){echo "---" ;} else { echo $lr_date;}?></h5>
							<h5>LR File: 
							<?php if($lrfile!=""){?>
							<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$lrfile;?>" target="_blank">View</a>
							<?php } else{ echo"---";}?>			
							</h5>
						</div>	
					</div>
				</div>
			</div> 
		</div> 

<!-----------------------------------------------Fourth Row Ends ----------------------------------------->       
<!-----------------------------------------------Fifth Row Starts -----------------------------------------> 			 
		<div class="row">
			<?php if($status == $settingValueRejectedStatus){?>
				<div class="col-lg-4 col-md-4 grid-margin">
					<div class="card">
						<div class="card-body">
							<div style="text-align:left;">
								<h5>Reason: <?php echo $rejectedreason;?></h5>
							</div>	
						</div>
					</div>
				</div> 
			<?php }  ?>

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<div style="text-align:left;">
							<h5>Category Name: <?php if($category_name==""){echo "---" ;} else { echo $category_name;}?></h5>
							<h5>Material Name: <?php if($material_name==""){echo "---" ;} else { echo $material_name;}?></h5>
						</div>	
					</div>
				</div>
			</div>
		</div>
		<?php if($requesttype=="edit/ongoing"){?>
			<button type="sumbit" class="btn btn-success mr-2" id="btnaccept" onclick="PutData('<?php echo $request_id; ?>',0)">Accept</button>
			<button type="sumbit" class="btn btn-danger mr-2" id="btnreject" onclick="showModal('<?php echo $request_id; ?>',0)">Reject</button>
		<?php } else if($requesttype=="edit/In Process"){?>
			<button type="sumbit" class="btn btn-success mr-2" id="btnaccept" onclick="PutDataBrand('<?php echo $request_id; ?>',0)">Accept</button>
			<button type="sumbit" class="btn btn-danger mr-2" id="btnreject" onclick="showModal('<?php echo $request_id; ?>',0)">Reject</button>
		<?php }else{}?>
			 
 <!-----------------------------------------------Fifth Row Ends ----------------------------------------->			 
			 
         </div> 
		     
	   <?php
			include_once("footer.php");
		?>
	</div>
			              
			 
   </div>
    
</div>  
      <!-- main-panel ends -->
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
var valsettingValueOther='<?php echo $settingValueOther;?>';
var valselEmp='<?php echo $SelEmp;?>';
var requesttype='<?php echo $requesttype;?>';
$(document).ready(function(){
	showData(valtype);
	document.getElementById( 'reasondiv' ).style.display = 'none';
	
});
$("#txtInputReason").on('change keyup paste', function () {
	var valtext = $('#txtInputReason option:selected').text();
	if(valtext.trim()==valsettingValueOther){
		document.getElementById('reasondiv' ).style.display = 'block';
	}
	else{
		document.getElementById('reasondiv' ).style.display = 'none';
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

	

 function checkIndividual(){
	document.getElementById("cbCheckall").checked = false;
}
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


function PutData(request_id,type){
	
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
		str=request_id;
	}
		showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
			if(confirmed==true){	
				$.ajax({
					type:"POST",
					url:"apiAcceptAuditorPORecordsDetails.php",
					data:{type:type,po_id:valpoid,str:str},	
					success:function(response){
					console.log(response);
						if(response=="Success"){
							if(requesttype=="edit/ongoing"){
								showAlertRedirect("Success","Data Added Successfully","success","pgAuditorPORecordDetails.php?type=In%20Process&po_id="+valpoid);
							}
							else{
								showAlertRedirect("Success","Data Added Successfully","success","pgEprMaterialInward.php?type=In%20Process&po_id="+valpoid+"&req=");
							}
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

function PutDataBrand(request_id,type){
	
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
		str=request_id;
	}
	
		showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
			if(confirmed==true){	
				$.ajax({
					type:"POST",
					url:"apiAcceptEPRSDocumentData.php",
					data:{type:type,po_id:valpoid,str:str},	
					success:function(response){
					console.log(response);
						if(response=="Success"){
							if(requesttype=="edit/ongoing"){
								showAlertRedirect("Success","Data Added Successfully","success","pgAuditorPORecordDetails.php?type=In%20Process&po_id="+valpoid);
							}
							else{
								showAlertRedirect("Success","Data Added Successfully","success","pgEprMaterialInward.php?type=In%20Process&po_id="+valpoid+"&req=");
							}
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
				showConfirmAlert('Confirm action!', 'Are you sure to Reject this record?','question', function (confirmed){
				if(confirmed==true){
						$.ajax({
							type:"POST",
							url:"apiRejectAuditorPORecordDetails.php",
							data:{str:str,reason:$("#txtInputReason").val(),reasontext:$("#txReason").val(),type:varrejtype,po_id:valpoid,valselEmp:valselEmp,},	
							success:function(response){
							console.log(response);
								if(response=="Success"){
									if(requesttype=="edit/ongoing"){
										showAlertRedirect("Success","Data Added Successfully","success","pgAuditorPORecordDetails.php?type=In%20Process&po_id="+valpoid);
									}
									else{
										showAlertRedirect("Success","Data Added Successfully","success","pgEprMaterialInward.php?type=In%20Process&po_id="+valpoid+"&req=");
									}
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
</script>    
</body>
</html>