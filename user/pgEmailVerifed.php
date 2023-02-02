<?php
include_once "commonFunctions.php";
include_once "function.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValueEmployeeImagePathOther =$commonfunction->getSettingValue("EmployeeImagePathOther"); 
$settingValueExpiredStatus= $commonfunction->getSettingValue("Expired");
$email=$_REQUEST["Email"];
$Token=$_REQUEST["Token"];
$Qry="update tw_verify_email set status='".$settingValueExpiredStatus."' Where email='".$email."' and  user_type='Company' and token='".$Token."' ";
$retVal3=$sign->FunctionQuery($Qry);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
<!-- endinject -->
<!-- Plugin css for this page -->
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="<?php echo $settingValueEmployeeImagePathOther."logo.png";?>" alt="logo" style="width:100%;">
              </div>
               <div align="center">
				<h3>Your email verification done</h3>
				<img src="../assets/images/gif/verified.gif" class"img-lg rounded" alt="image small">
				 </div>  
				<div align="center">
			  <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;font-family:'Montserrat',sans-serif;"><tr><td style="font-family:'Montserrat',sans-serif;" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:49px; v-text-anchor:middle; width:160px;" arcsize="8%" stroke="f" fillcolor="#ffb200"><w:anchorlock/><center style="color:#FFFFFF;font-family:'Montserrat',sans-serif;"><![endif]-->
			   <div class="mt-3">
				<a href="pgEmployeeLogIn.php" class="btn btn-block btn-success btn-lg font-weight-medium auth-form-btn">
				  <span ><span style="font-size: 16px; line-height: 19.2px;"><strong><span style="">Back to home page</span></strong></span></span>
				</a>
				</div>
			  <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
				</div>
               
               </div>
              </div>
             </div>
            </div>
          </div>
         </div>		  
</body>
</html>