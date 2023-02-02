<?php 
session_start();
	if(!isset($_SESSION["username"])){
		header("Location:pgLogin.php");
	}

$CompanyName = "";
$Status = "";
$id = $_REQUEST["id"];

//===SESSION FOR DB FUNCTION===
$_SESSION["requestid"]=$id;
//======

	// Include class definition
	require "function.php";
	$sign=new Signup();
	$qry = "SELECT cd.CompanyName, cd.Status, cc.value FROM tw_company_details cd LEFT JOIN tw_company_contact cc ON cd.ID = cc.company_id WHERE cd.ID = ".$id." ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$CompanyName = $decodedJSON->response[0]->CompanyName;
	$Status = $decodedJSON->response[1]->Status; 
	$email = $decodedJSON->response[2]->value; 
	
	$qry2 = "select id,document_status from tw_document_verification_status_master where visibility = 'true' ";
	$retVal2 = $sign->FunctionOption($qry2,$Status,'document_status','id');
	//echo $decodedJSON2 = json_decode($retVal2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Company Details</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
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
                  <h4 class="card-title">Company Details</h4>
                 
                  <div class="forms-sample">
                    <div class="form-group">
                      <label for="txtDocumentStatus">Company Name <code>*</code></label>
                      <input type="text" class="form-control" id="txtCompanyName" maxlength="100" value="<?php echo $CompanyName?>" placeholder="Company Name" />
                    </div>
					<div class="form-group">
                      <label for="txtEmail">Email <code>*</code></label>
                      <input type="text" class="form-control" id="txtEmail" maxlength="100" value="<?php echo $email?>" placeholder="Email" />
                    </div>
					<div class="form-group">
					<label for="selStatus">Status <code>*</code></label><br>	
                     <select id="selStatus" class="form-control">
						<?php echo $retVal2; ?>
					</select>
                    </div>
                 
                    <button type="button" class="btn btn-success" onclick="updaterecord()">Update</button>
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
     <script type='text/javascript'>
$('input, textarea').blur(function()
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
function updaterecord(){
		$.ajax({
			type:"POST",
			url:"apiEditCompanyDetails.php",
			data:{companyname:$("#txtCompanyName").val(),email:$("#txtEmail").val(),status:$("#selStatus").val()},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					alertBox('Record Updated Successfully');
					$("#txtCompanyName").val("");
					$("#txtEmail").val("");
					window.location.href="pgViewCompanyList.php";
				}
				else if($.trim(response)=="Exist"){
					alertBox('Email already exist');
					$("#txtEmail").focus();
				}
				else{
					alertBox('Something Went Wrong. Please Try After Sometime');
				}
			}
		});
	 
}

function alertBox(value){
	alert(value);
}
	
</script>	
</body>

</html>