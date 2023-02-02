<?php 
session_start();
	if(!isset($_SESSION["username"])){
		header("Location:pgLogin.php");
	}
	// Include class definition 
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$table="";
//$id="";
$role_name="";
$description="";
$settingValueAdminPanel=$commonfunction->getSettingValue("AdminPanel");

$qry="select id,role_name,description from tw_role_master where visibility='true' and panel='".$settingValueAdminPanel."' order by id desc";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_role_master where visibility='true'and panel='".$settingValueAdminPanel."'";
$retVal1 = $sign->Select($qry1);
$decodedJSON = json_decode($retVal);

$count = 0;
$i = 1;
$x=$retVal1;
$table="";
while($x>=$i){
	$id = $decodedJSON->response[$count]->id;
	$count=$count+1;	
	$role_name = $decodedJSON->response[$count]->role_name;
	$count=$count+1;
	$description = $decodedJSON->response[$count]->description;
	$count=$count+1;
	
	$table.="<div class='row'>
		<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
			<strong><a href='pgAdminRoleManagementDetails.php?id=".$id."'>".$role_name."</a></strong>
			<br>
			<p>".$description."</p>
		</div>
	</div>";		
		

	 $i=$i+1;
}
//echo $table; 
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
		   <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
              <div class="card">
			  
                <div class="card-body">
					<div class="card-body">
					
					
					
					<div class="row">
						<div class="card-body col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                  <h4 class="card-title ">Sub Admin Roles</h4>
                  </div>
				<div class="card-body col-lg-12">
                      <?php echo $table;?>
                 </div>
				</div>
                </div>
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
  <script src="../assets/js/sweetalert2.min.js"></script>  
</body>

</html>