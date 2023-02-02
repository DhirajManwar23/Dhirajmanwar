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
<title>Trace Waste | My Network</title>
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
								<h4 class="card-title">Pending Request</h4>
								<div class="card-body">
									<div class="row"  id="PendingDiv">     
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			
			
				<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title">Invitation</h4>
								<div class="card-body">
									<div class="row"  id="inviteDiv">     
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div id="bottomDiv">

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
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script type='text/javascript'>
 
$(document).ready(function(){
	showData();
	showDataPending();
	
});
//SHOW Pending Request
function showDataPending(){
		$.ajax({
			type:"POST",
			url:"apiPendingRequest.php",
			data:{},
			success:function(response){
				console.log(response);
				$("#PendingDiv").html(response);
				showtype();
		}
	});
}



//SHOW NEW INVITE COMPANIES
function showData(){
	
		$.ajax({
			type:"POST",
			url:"apiMyNetwork.php",
			data:{},
			success:function(response){
				console.log(response);
				$("#inviteDiv").html(response);
				showtype();
		}
	});
}

//ACCEPT THE INVITATION
function accept(sender_id){
	$.ajax({
		type:"POST",
		url:"apiAcceptInvitation.php",
		data:{sender_id:sender_id},
		success:function(response){
		console.log(response)
		if($.trim(response)=="Success"){
			showAlertRedirect("Success","Invitation accepted successfully","success","pgMyNetwork.php");
		}
		 else if($.trim(response)=="error"){
		   showAlert("Error","","warning");
		  }
		  else{
			  showAlert("Error","Something went wrong","error");
		  }
			
		}
	});
}	 

//SHOW MY NETWORK
function showtype(){
		$.ajax({
			type:"POST",
			url:"apiMyNetworkCompanyType.php",
			data:{},
			success:function(response){
				console.log(response);
				$("#bottomDiv").html(response);
		}
	});
}

function viewCompanyProfile(id)
{
	window.location.href = "pgSearchCompany.php?id=" + id;
}
function AddSupplierNo(id,count)
{
	
	if(count==0){
		window.location.href = "pgSupplierNumber.php?type=add&id=" + id;
    }
	else{
		window.location.href = "pgSupplierNumber.php?type=edit&id=" + id;
	}
}

</script>
</body>
</html>