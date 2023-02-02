<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
$settingValuepdf= $commonfunction->getSettingValue("Pdf Image");
$settingValuepdf=$sign->escapeString($settingValuepdf);
$type=$_REQUEST["type"];
$type=$sign->escapeString($type);
$id=$_REQUEST["id"];
$valDocumentType="";
$disabledEway="";
$disabledInvoice="";
$disabledWBS="";

$qry="SELECT po_id FROM tw_material_outward where id='".$id."'";
$po_id = $sign->SelectF($qry,"po_id");

if($type=="LorryReceipt"){
	$valDocumentType = "Lorry Receipt";
}
else if($type=="COA"){
	$valDocumentType = "Certificate of Analysis";
}
else if($type=="PackingList"){
	$valDocumentType = "Packing List";
}
else if($type=="Eway"){
	$valDocumentType = "Eway";
}
else if($type=="Invoice"){
	$valDocumentType = "Invoice";
}
else if($type=="WBS"){
	$valDocumentType = "Weigh Bridge Slip";
}
else if($type=="Photo"){
	$valDocumentType = "Photo";
}

else if($type=="COO"){
	$valDocumentType = "Certificate of Origin";
}

$qryEway="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Eway' and outward_id='".$id."' ORDER BY outward_id ASC";
$retValEway = $sign->SelectF($qryEway,"cnt");

$qry1Eway="SELECT COUNT(*) as cnt from tw_material_outward_eway WHERE outward_id='".$id."' ORDER BY outward_id ASC";
$retVal1Eway = $sign->SelectF($qry1Eway,"cnt");

if($retValEway>0 || $retVal1Eway>0){
	$disabledEway="disabled";
}
else{
	$disabledEway="";
}

$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$id."' ORDER BY outward_id ASC";
$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$id."' ORDER BY outward_id ASC";
$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
if($retValInvoice>0 || $retVal1Invoice>0){
	$disabledInvoice="disabled";
}
else{
	$disabledInvoice="";
}

$qryWBS="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='WBS' and outward_id='".$id."' ORDER BY outward_id ASC";
$retValWBS = $sign->SelectF($qryWBS,"cnt");

$qry1WBS="SELECT COUNT(*) as cnt from tw_material_outward_wbs WHERE outward_id='".$id."' ORDER BY outward_id ASC";
$retVal1WBS = $sign->SelectF($qry1WBS,"cnt");
if($retValWBS>0 || $retVal1WBS>0){
	$disabledWBS="disabled";
}
else{
	$disabledWBS="";
}
$retValInvoiceAmt=0;
if ($valDocumentType == "Invoice")
{
$qryInvoiceAmt="SELECT final_total_amout from tw_material_outward WHERE id='".$id."'";
$retValInvoiceAmt = $sign->SelectF($qryInvoiceAmt,"final_total_amout");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Material Outward Attach Documents</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
					<h4 class="card-title"><?php echo $valDocumentType; ?></h4>
					
						<div class="form-group">
						<?php if($type=='Invoice'){ ?>
						  <label for="txtAmount">Amount</label>
						  <input type="number" class="form-control" id="txtAmount"  placeholder="Amount" value='<?php echo $retValInvoiceAmt; ?>'>
						</div>
					<?php }
					?>
					
						<div class="form-group">
						  <label for="txtDocumentNumber"> Document Number <code>*</code></label>
						  <input type="text" class="form-control" id="txtDocumentNumber" maxlength="20" placeholder="Document Number" value="">
						</div>	
						<div class="form-group row">
						  <div class="col-sm-12">
							<input type="file" name="Document_Proof" accept=".png, .jpg, .jpeg, .pdf" id="Document_Proof" placeholder="Document" onchange="showname();" />				
						  </div>
						</div>
						<div class="form-group row">
							 <div class="col-sm-12" id="diveditimg" style="display:none;">          
									<img id="my_image" src='' class='img-lg mb-3'/>
							 </div>
						 </div>
						
						<?php if($type=="Eway"){ ?>
							<button type="button" id="btnUpdateRecord" class="btn btn-success" <?php echo $disabledEway;?> onclick="adddata();">Upload</button>
						<?php }else if($type=="Invoice"){ ?>
							<button type="button" id="btnUpdateRecord" class="btn btn-success" <?php echo $disabledInvoice;?> onclick="adddata();">Upload</button>
						<?php }else if($type=="WBS"){ ?>
							<button type="button" id="btnUpdateRecord" class="btn btn-success" <?php echo $disabledWBS;?> onclick="adddata();">Upload</button>
						<?php }else if($type=="Photo"){ ?>
							<button type="button" id="btnUpdateRecord" class="btn btn-success" onclick="adddata();">Upload</button>
						<?php }?>
						 
						 
						 <div class="table-responsive">
							<table id="tableData" class="table">
							 
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
var hdnIDimg="";
var hdnIDsize="";
var valType="<?php echo $type; ?>";
var valid="<?php echo $id; ?>";
var valpo_id="<?php echo $po_id; ?>";
var valAction="add";
var valrowid=0;
$(document).ready(function(){
});
$('input').blur(function()
{
	var valplaceholder = $(this).attr("placeholder");
	var vallblid = $(this).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please upload " + valplaceholder;
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
	var valtext = "Please upload " + valplaceholder;
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
function showname() {
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	  var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg','pdf']) == -1) 
	  {
		$('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  
	  var path = "../assets/images/Documents/Employee/Outward"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		   showAlert("Warning","Image File Size is very big","warning");
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

						   $.ajax({
							url:"uploadoutward.php",
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
								adddata();
							}
						   });
					}
					
				});
		} 
	  else
	  {
			form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

		   $.ajax({
			url:"uploadoutward.php",
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
				hdnIDimg=data;
				hdnIDsize=fsize;
				
			}
		   });
	  }
		  
		 
};
function adddata(){
	var valAmount="<?php echo $retValInvoiceAmt; ?>";
	if(valType!='Invoice'){
			$retValInvoiceAmt = 0;
	}
	if(!validateBlank($("#txtDocumentNumber").val())){
		setErrorOnBlur('txtDocumentNumber');
	  }
	else if(!validateBlank(hdnIDimg)){
		setErrorOnBlur("Document_Proof");
	  }
	else{
			$.ajax({
			type:"POST",
			url:"apiOutwardDocuments.php",
			data:{hdnIDimg:hdnIDimg,valoutwardid:valid,type:valType,size:hdnIDsize,valAction:valAction,rowid:valrowid,txtAmount:$("#txtAmount").val(),txtDocumentNumber:$("#txtDocumentNumber").val()},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					if(valAction=="add"){
						showAlertRedirect("Success","Data Saved Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
					}
				}
				else if($.trim(response)=="Exit"){
					showAlert("Warning","Entry already Exit","warning");
				}
				else if($.trim(response)=="Status"){
					showAlert("Warning","Outward has been completed you can not upload this document","warning");
				}
				else{
					showAlertRedirect("Error","Something Went Wrong. Please Try After Sometime","error");
				}
			}
		});    
	}
}
$("#Document_Proof").change(function() {
  $("#diveditimg").css("display", "none");
});
 
</script>
</body>

</html>