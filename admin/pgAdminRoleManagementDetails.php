<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
$sign=new Signup();
$role_id = $_REQUEST["id"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Admin Role Management</title>
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
	 
	  
	  <div id="bottomDiv"></div>
		<button type="button" id="btnAddrecord" class="btn btn-success" onclick="checkCheckbox();">Save</button>
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
<script type='text/javascript'>
 
$(document).ready(function(){
	showtype();
	
});

$('#select-all').click(function(event) {   
    if(this.checked) {
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
});
var submodule_id="";
var role_id = "<?php echo $role_id;?>";
function showtype(){
		$.ajax({
			type:"POST",
			url:"apiAdminRoleManagementType.php",
			data:{checkbox:$("#choose-all").prop('checked'),role_id:role_id},
			
			success:function(response){
				$("#bottomDiv").html(response);
				
		}
	});
}

var arrValue = [];
var arrType = [];

function checkCheckbox(){
	arrValue = [];
	arrType = [];
	
	$("input:checkbox[name=chkModule]:checked").each(function(){
		arrValue.push($(this).val());
		arrType.push("Module");
	});
	$("input:checkbox[name=chkSubModule]:checked").each(function(){
		arrValue.push($(this).val());
		arrType.push("Sub Module");
	});
	 var role_id = "<?php echo $role_id;?>";
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing');	
		 $.ajax({
			type:"POST",
			url:"apiAddAdminRoleManagement.php",
			data:{arrValue:arrValue,arrType:arrType,role_id:role_id},
			success:function(response){
				console.log(response);
				enableButton('#btnAddrecord','Save');
				if($.trim(response)=="Success"){
						showAlertRedirect("Success","Record Saved Successfully","success","pgAdminRoleManagement.php");

				}else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
				}
				}
			});
		 }			
  


</script>
</body>

</html>