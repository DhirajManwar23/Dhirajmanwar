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
$stateQry="SELECT id,state_name FROM tw_state_master";
$state=$sign->FunctionOption($stateQry,"",'state_name',"id");
$po_id=$_REQUEST['po_id'];

$employee_id=$_SESSION['employee_id'];
$company_id=$_SESSION['company_id'];
$recyclerCompnay_id=$_REQUEST['recyclerCompnay_id'];
$requestCompany_id=$_REQUEST['requestCompany_id'];
$productQry="SELECT pm.id,pm.product_name FROM  tw_product_management pm where pm.public_visible='true' ";
$product=$sign->FunctionOption($productQry,"",'product_name',"id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | PO</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
   <!-- endinject -->
  <!-- tw-css:start -->
  <link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../assets/images/favicon.png" />
  
  <link rel="stylesheet" href="../assets/css/custom/style.css">
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
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="ViewInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Material Info</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body modal-body">
					
							<div class="form-group row">	
								<div class="card-body" id="MaterialInfo">
								
								
																	  
						</div>
					</div>
					
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalViewReason();">Close</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->   
	  
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
              <div class="card">
			  <div class="card-body">
			  <h4 class="card-title"><span id="ProductName"></span> Material Assign <span id="getName"></span></h4>
			 
			  <div class="forms-sample">
			   <div class="row">
					<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
						<select  class="form-control" id="selState" onchange="loadCity();" placeholder="State">
							<option value="">Choose...</option>
							<?php echo $state; ?>
						</select>
					</div>
					<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
						<select  class="form-control" id="selcity"  placeholder="city">
						<option value="">Choose...</option>
						
					  </select>
					   </div>
					  <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
						<select  class="form-control" id="selproduct"  placeholder="Product">
						<option value="">Choose...</option>
						<?php echo $product; ?>
						<option value="0">ALL</option>
					  </select>
					</div>
					<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
						<button type="button" id="btnAddrecord"  class="btn btn-success" onclick="showData();"> <i class="ti-search" /></i> </button>
					</div>
					
				</div>	
				
			   <div class="table-responsive">
					<table id="tableData" class="table">
				 
					</table>
			  </div>

			  <div class="table-responsive">
					<table id="tableDataLoad" class="table">
				 
					</table>
			  </div>
			  <br>
			  
			  <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
				<button type="button" id="btnPutRecord"  class="btn btn-success" onclick="PutData();">Submit</button>
			 </div>
			  <div class="card card-inverse-danger ol-lg-6 col-md-6 col-sm-12 col-xs-12 col-12" id="ERROR">	
			<div class="card-body">
				  <p class="card-text">Add material
				  </p>
				
				</div>
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
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/sweetAlert.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script type='text/javascript'>
var valcheck = "";
var valpgName = "";
var valaction = "";
var valdata = "";
var valresult = "";
var valstatus = "";
var request_company_id=<?php echo $requestCompany_id; ?>;
var recycler_company_id=<?php echo $company_id; ?>;
var total_quantity="";
var material_id="";
var Company_id="";
$(document).ready(function(){
   
});

$(document).ready(function(){
	 userLogs(valpgName,valaction,valdata,valresult,valstatus);	
	  $("#ERROR").css("display", "none");
	  OnLoadData();
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

$("#txtEmail").blur(function()
 {
	removeError(txtEmail);
	if ($("#txtEmail").val()!="")
	{
		if(!validateEmail($("#txtEmail").val())){
			setError(txtValue);
		}
		else
		{
			removeError(txtValue);
		}
	}
	 
});
function loadCity(){
	
	if ($("#selState").val()!="")
	{
		var SELECTID=$("#selState").val();
	  
	
		$.ajax({
				type:"POST",
				url:"apiGetCity.php",
				data:{SELECTID:SELECTID},
				dataType: 'JSON',
				success:function(response){
					console.log(response);
					console.log(response[0]);
					console.log(response[1]);	
					 $("#selcity").html("<option value=''>Choose</option>" +response[0]);
				}
			});	
	}
	
};
function fetchMaterial(Company_id,product_name,total_quantity,material_id){
	
 total_quantity=total_quantity;
 material_id=material_id;
 Company_id=Company_id;
	if(Company_id=="" && product_name=="" && total_quantity=="" )
	{
		
	}
};
function OnLoadData(){
	var po_id="<?php echo $po_id; ?>";
		$.ajax({
			type:"POST",
			url:"apiGetTableAssign.php",
		    data:{po_id:po_id},	
			dataType: 'JSON',
			success:function(response){
			console.log(response);
					
				
				$("#tableDataLoad").html(response[0]);
				$("#getName").html(response[1]);
				//$("#ProductName").html(response[2]);
				
		        
				
			}
		});
	
}

function PutData(){
	var po_id="<?php echo $po_id; ?>";
	var value="";
	$('.cbCheck:checkbox:checked').each(function(){
		value=value+$(this).val()+",";
	});
	str=value.replace(/[, ]+$/, "").trim();
 if(str=="")
   {
		$("#ERROR").fadeIn();
	   $("#ERROR").fadeOut(5000);
	   
   } 
   else{
	   disableButton('#btnPutRecord','<i class="ti-timer"></i> Processing...');
		$.ajax({
			type:"POST",
			url:"apiassignMaterial.php",
		    data:{str:str,request_company_id:request_company_id,recycler_company_id,po_id:po_id},	
			success:function(response){
			console.log(response);
				if(response=="Success"){
					showAlertRedirect("Success","Data Added Successfully","success","pgEprpoList.php");
					 enableButton('#btnPutRecord','sumbit');
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
				
		        
				
			}
		});
   }
}
function showModalViewInfo()
{	
	jQuery.noConflict();
	$("#ViewInfo").modal("show");
}
function closeModalViewReason() {
	
  $("#ViewInfo").modal("hide");
}
function closeModal() {
	
  $("#ViewInfo").modal("hide");
}

function ViewInfo(moi_id){
	$.ajax({
			type:"POST",
			url:"apiGetinfomaterial.php",
			data:{id:moi_id},
			success:function(response){
				console.log(response);
				if(response!=""){
					
				 $("#MaterialInfo").html(response);	
		        showModalViewInfo();
			
				}
			}	
		}); 
}

function showData(){
	var state=$("#selState").val();
	var city=$("#selcity").val();
	var product=$("#selproduct").val();
	var po_id=<?php echo $po_id ; ?>;
	if(!validateBlank($("#selState").val())){
	setErrorOnBlur("selState");
   }else if(!validateBlank($("#selcity").val())){
	setErrorOnBlur("selcity");
   }else if(!validateBlank($("#selproduct").val())){
	setErrorOnBlur("selproduct");
   }
   else{
		$.ajax({
			type:"POST",
			url:"apiGetMaterial.php",
			data:{state:state,city:city,po_id:po_id,product:product},
			success:function(response){
				console.log(response);
				if(response!=""){
				$("#tableData").html(response);
				$("#tableDataLoad").html("");

				}
				else{
					$("#tableData").html(response);
				}
				
			}
		});
   }
};



</script>
</body>

</html>