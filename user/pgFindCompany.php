<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Find a Company</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
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
                  <h4 class="card-title">Find a Company</h4>
					<div class="form-group">
						<div class="row">
							<div class="col-md-10">
								<input type="text" class="form-control" id="searchresult" placeholder="Search Term"/>
							</div>
							<div class="col-md-2">
								<button type="Submit" class="btn btn-success" 
								onclick="showData()">Search</button>
							</div>
						</div>
					</div>
					<div class="col-12 mb-5">
						<h6 id="lblSearchResult"></h6>
					</div>
					<div class="table-responsive">
						<table id="tableData" class="table">
						 
						</table>
					</div>
                </div>
              </div>
            </div>
          </div>
        </div>
		  <!-- ==============MODAL START ================= -->
	  <div class="modal fade" id="ModalInvite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel"><i class="ti-id-badge"></i> Send Invitation</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal();">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				 <div class="input-group">
					 <div class="input-group">
						<div class="form-group row">
						  <div class="col-sm-12">
							 <div class="form-group">
							  <input type="text" class="form-control form-control-lg" id="txtName" placeholder="Name of company">
							  </div>
							 <div class="form-group">
							  <input type="email" class="form-control form-control-lg" id="txtEmail" placeholder="Email">   
							</div>			
						  </div>
						</div>
					  </div>
                 </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal();">Close</button>
				<button type="button" class="btn btn-success" id="btnUpdate" onclick="SendInvitation();">Send Invitation</button>
			</div>
			</div>
		</div>
	</div>
	  <!-- ==============MODAL END ================= -->
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
$(document).ready(function(){
	 userLogs(valpgName,valaction,valdata,valresult,valstatus);	
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

function showData(){
	if($.trim($("#searchresult").val())==""){
	setErrorOnBlur("searchresult");
    $("#searchresult").focus();
  }else{
	
		$.ajax({
			type:"POST",
			url:"apiSearchCompanyDetails.php",
			data:{CompanyName:$("#searchresult").val()},
			success:function(response){
				console.log(response);
				if(response==""){
				 $("#lblSearchResult").html('Search Result Not found you can invite"<u class="ms-2">' + $("#searchresult").val() + '</u>" on <a  class="btn btn-link" onclick="Invitation();" >trace waste</a> ');
		        }
				else{
				 $("#lblSearchResult").html('Search Result For"<u class="ms-2">' + $("#searchresult").val() + '</u>"');
				$("#tableData").html(response);

				}
			}
		});
	}
}
function Invitation(){
	showModal();
}
function showModal()
{ 
	jQuery.noConflict();
	$("#ModalInvite").modal("show");
	
}
 function closeModal(){
	
  $("#ModalInvite").modal("hide");
 }
 function SendInvitation(){
 if(!validateBlank($("#txtName").val())){
		setErrorOnBlur("txtName");
	}
 if(!validateBlank($("#txtEmail").val())){
		setErrorOnBlur("txtEmail");
	}	
 else if(!validateEmail($("#txtEmail").val())){
		setError(txtEmail);
		$("#txtEmail").focus();
	}
	else{
	 var Name= $("#txtName").val();
     var Email=$("#txtEmail").val();
	 disableButton('#btnUpdate','<i class="ti-timer"></i> Processing...');

     $.ajax({
			type:"POST",
			url:"apiSendInvitation.php",
			data:{CompanyName:Name,CompanyEmail:Email},
			success:function(response){
				enableButton('#btnUpdate','Send Invitation');
				console.log(response);
				if($.trim(response)=="Success"){
					showAlert("success","Invitation Send","success");
					closeModal();
				}
				  else if($.trim(response)=="Exist"){
					   showAlert("Warning","Already exist on trace waste world","warning");
				  }
				  else{
					  showAlert("Error","Something went wrong","error");
				  }
				
			}
        });
    }		
 }
function editRecord(id){
	window.location.href = "pgSearchCompany.php?type=search&id="+id;
}
</script>
</body>
</html>