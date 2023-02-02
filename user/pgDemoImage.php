<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$form_type="";
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |Digital Signature</title>
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
                  <h4 class="card-title">Image Upload</h4>
					<div class="row">
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
						<label for="cars">Image 1</label>
						 <input
							class="form-control form-control-lg" id="selectAvatar1" name="selectAvatar1" type="file" accept=".png, .jpg, .jpeg, .pdf"/>
						</div>					
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="cars">Image 2</label>
							<input
							class="form-control form-control-lg"
							id="selectAvatar2" name="selectAvatar2"
							type="file"
							/>					
						</div>
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="cars">Image 3</label>
							<input
							class="form-control form-control-lg"
							id="selectAvatar3" name="selectAvatar3"
							type="file"
							/>
						</div>
					</div>
				  <button type="button" class="btn btn-success" onclick="adddata();">Upload</button>
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
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetAlert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>
const input1 = document.getElementById("selectAvatar1");
const input2 = document.getElementById("selectAvatar2");
const input3 = document.getElementById("selectAvatar3");
var Img1="";
var Img2="";
var Img3="";
var Img1Ext="";
var Img2Ext="";
var Img3Ext="";
input1.addEventListener("change", (e) => {
    uploadImage1(e);
	var name = document.getElementById('selectAvatar1'); 
	var hdnIDimg = name.files.item(0).name;
	Img1Ext = hdnIDimg.split('.').pop().toLowerCase();
	
});
input2.addEventListener("change", (e) => {
    uploadImage2(e);
	var name = document.getElementById('selectAvatar2'); 
	var hdnIDimg = name.files.item(0).name;
	Img2Ext = hdnIDimg.split('.').pop().toLowerCase();
});
input3.addEventListener("change", (e) => {
    uploadImage3(e);
	var name = document.getElementById('selectAvatar3'); 
	var hdnIDimg = name.files.item(0).name;
	Img3Ext = hdnIDimg.split('.').pop().toLowerCase();
});

const uploadImage1 = async (event) => {
    const file = event.target.files[0];
    const extension = event.target.files[0];
	
    const base641 = await convertBase64(file);
	if(Img1Ext=="pdf"){
		Img1 = base641.replace(/^data:application\/[a-z]+;base64,/, "");
	}
	else{
		Img1 = base641.replace(/^data:image\/[a-z]+;base64,/, "");
	}
	
	console.log(Img1);
};
const uploadImage2 = async (event) => {
    const file = event.target.files[0];    // avatar.src = base641;

    const base642 = await convertBase64(file);
	if(Img2Ext=="pdf"){
		Img2 = base642.replace(/^data:application\/[a-z]+;base64,/, "");
	}
	else{
		Img2 = base642.replace(/^data:image\/[a-z]+;base64,/, "");
	}
	console.log(Img2);
	
};
const uploadImage3 = async (event) => {
    const file = event.target.files[0];
    const base643 = await convertBase64(file);
	if(Img3Ext=="pdf"){
		Img3 = base643.replace(/^data:application\/[a-z]+;base64,/, "");
	}
	else{
		Img3 = base643.replace(/^data:image\/[a-z]+;base64,/, "");
	}
	console.log(Img3);
}; 

const convertBase64 = (file) => {
    return new Promise((resolve, reject) => {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);

        fileReader.onload = () => {
            resolve(fileReader.result);
        };

        fileReader.onerror = (error) => {
            reject(error);
        };
    });
};
function adddata(){
	$.ajax({
	type:"POST",
	url:"apiUploadSaveDocumentData.php",
	data:{Img1:Img1,Img2:Img2,Img3:Img3,Img1Ext:Img1Ext,Img2Ext:Img2Ext,Img3Ext:Img3Ext},
	success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Data Added Successfully","success","pgDemoImage.php");
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	});    


} 
</script>
</body>
</html>