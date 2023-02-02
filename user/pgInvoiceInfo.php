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
$company_id = $_SESSION["company_id"];
$invoice_information="";
$qryII="SELECT invoice_information FROM tw_invoice_info WHERE company_id = '".$company_id."'";
$retValII = $sign->FunctionJSON($qryII);
$decodedJSONII = json_decode($retValII);
if(isset($decodedJSONII->response[0]->invoice_information))
{
	$invoice_information = $decodedJSONII->response[0]->invoice_information;
}
else{
	$invoice_information="";
}
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Invoice Information</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
<!-- endinject -->
<!-- inject:css -->
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
                  <h4 class="card-title">Invoice Information (Optional)</h4>
                  <div class="forms-sample">
					<div id="txtInvoice"><?php echo $invoice_information; ?></div>
					<br>
				
                    <button type="submit" class="btn btn-success mr-2" id="btncreate" onclick="update()">Update</button>
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script>
let editor;
ClassicEditor
	.create( document.querySelector( '#txtInvoice' ) )
	.then( newEditor => {
		editor = newEditor;
	} )
	.catch( error => {
		console.log( error );
	} );
			
$('input').blur(function()
{
		
	var valplaceholder = $(this).attr("placeholder");
	var vallblid = $(this).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $(this).val();
	if(check == '')
	{
		$(this).parent().addClass('has-danger');
		$(this).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');	
	}
	else
	{
		$(this).parent().removeClass('has-danger');  
		$("#"+valid+"").fadeOut(); 
	}
});

function update(){
	   disableButton('#btncreate','Processing...');
		
	 	$.ajax({
			type:"POST",
			url:"apiUpdateInvoiceInfo.php",
			data:{invoice_information:editor.getData()},
			success:function(response){
				console.log(response);
				enableButton('#btncreate','Update');
				if($.trim(response)=="Success"){
					showAlert("Success","Data Updated Successfully","success")
				}else{
					showAlert("Something Went Wrong. Please Try After Sometime","success");
				}
			}
		});   
}  
 
</script>
</body>
</html>