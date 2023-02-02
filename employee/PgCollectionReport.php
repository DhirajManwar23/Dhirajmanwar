<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
        
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Segregation</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="../assets/css/custom/style.css">
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
			include_once("navSideBar.php"); ?>
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Collection Report</h4>
                    <div class="form-group">
			          <div class="row"> 
                        <div class="form-group col-lg-5 col-md-5 col-sm-12 col-xs-12 col-12">
				        <label for="txtNetQuantity">Start Date<code>*</code></label>
						 <input type="date" class="form-control" id="txtStartdate" max='<?php echo $cur_date; ?>'  value='' placeholder="Start Date" />
						</div> 
						<div class="form-group col-lg-5 col-md-5 col-sm-12 col-xs-12 col-12">
				        <label for="txtNetQuantity">End Date<code>*</code></label>
						 <input type="date" class="form-control" id="txtEnddate" max='<?php echo $cur_date; ?>'  value='' placeholder="end Date" />
						</div>
                      </div>
                      </div>
					<br>
					<div class="form-group col-lg-5 col-md-5 col-sm-12 col-xs-12 col-12">
				<button type="button" id="btnAddrecord"  class="btn btn-success" onclick="genrateReport();">Genrate Report</button>  
				</div>
				<div class="table-responsive">
						<table id="tableData" class="table">
						
						</table>
				</div>
				<div class="table-responsive">
						<table id="tableData1" class="table">
						<tr><td colspan='10' class='text-center'>No records found</td></tr>
						</table>
				</div>
					 <div class="center-text" id="Exl">
					 <button type="submit" onclick="exportTableToExcel()" style="background: #149ddd;border: 0; padding: 10px 24px; color: #fff; transition: 0.4s; border-radius: 4px;">Download to Excel</button>
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script src=
"../assets/js/custom/jquery.table2excel.min.js">
</script>
<script type='text/javascript'>

$(document).ready(function(){
	$('#Exl').hide();
	$('#tableData1').hide();
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

function removeError(inputComponent)
{
	var vallblid = $(inputComponent).attr("id");
	$("#" + vallblid).parent().removeClass('has-danger');
	const element = document.getElementById("errSet"+vallblid);
	if (element)
	{
		element.remove();
	}
}


function genrateReport(){
	
	 if(!validateBlank($("#txtStartdate").val())){
		setErrorOnBlur("txtStartdate");
	  }
	  else if(!validateBlank($("#txtEnddate").val())){
		setErrorOnBlur("txtEnddate");
	  } 
      else{
        var startdate=$("#txtStartdate").val();
        var enddate=$("#txtEnddate").val();
	// window.location.href = "pgCollectionDoc.php?startDate="+startdate+"&endDate="+enddate;
	
	 $.ajax({
          type:"POST",
          url:"apiCollectionDoc.php",
		//dataType: 'JSON',
          data:{startdate:startdate,enddate:enddate},
          success:function(response){
			  console.log(response);
			  if(response==""){
				$("#tableData1").show();  
			  }else{
			 $("#tableData").html(response);
			$("#Exl").show();
			$("#tableData1").hide();  
			  }
          }
      });
	 }
}

function exportTableToExcel(){
    $("#tableData").table2excel({
        filename: "Collection_Report.xls"
    });
}

</script>
</body>
</html>