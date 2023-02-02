<?php 
session_start();
if(!isset($_SESSION["cp_id"])){
	header("Location:pgLogin.php");
}
$cp_id = $_SESSION["cp_id"];
$Token= time();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
include_once "Qrfunction.php";
$commonfunction=new Common();
$sign=new Signup();
$qrfunction=new TwQr();
$settingValueCollectionPointImagePathOther= $commonfunction->getSettingValue("CollectionPointImagePathOther");
$valqrfunction = $qrfunction->GetQrcode($cp_id,$settingValueCollectionPointImagePathOther);


?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | My Profile</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/vendors/jquery-toast-plugin/jquery.toast.min.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
		
    <!-- partial -->
  
      <!-- partial:partials/_settings-panel.html -->
      
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
		
		
              <div class="card">
                <div class="card-body">
					<div style="text-align:center;">
					
						<img src="<?php echo $valqrfunction; ?>" width="650px" />
						
					</div>
                </div>
              </div>
             
            
	</div>
</body>
</html>