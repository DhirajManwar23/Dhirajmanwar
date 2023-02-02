<?php 
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$requestid =1;



$ip_address= $commonfunction->getIPAddress();

$employee_id=$_SESSION["employee_id"];
$settingValueEmployeeImagePathVerification= $commonfunction->getSettingValue("EmployeeImagePathVerification");
//$created_by = $id;
$date="";			
$PET=""; 					
$Hard_Plastics="";		
$Paper=""; 					
$Soiled_Paper=""; 			
$Hard_Plastic_Mixture=""; 	
$Soft_Plastic=""; 			
$Reject_Waste="";			
$Incoming_Waste=""; 		
$Recyclable_Waste=""; 	
$disable="";	
$Name="";	
 
 include_once "function.php";
$sign=new Signup();

$data = array();
$qry="SELECT DISTINCT name FROM tw_mixwaste_manual_entry ";
$retVal = $sign->FunctionJSON($qry);

$qryCount="SELECT COUNT(DISTINCT name) as cnt FROM tw_mixwaste_manual_entry";
$retVal1=$sign->Select($qryCount);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$responsearray=array();
while($x>=$i){
	 $name1 = $decodedJSON2->response[$count]->name;
	$count=$count+1;
	
	
	array_push($responsearray, $name1);
	$i=$i+1;
}



// echo json_encode($responsearray);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste |Data Entry </title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
   <link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
                  <h4 class="card-title">Data Entry</h4>
                 <div class="forms-sample">
				  <div class="row">
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
					 <label class='form-label'>Date<code>*</code></label>
					 
					  <input type="date" class="form-control" id="txtDate" <?php echo $disable; ?> max='<?php echo $cur_date; ?>'  value="<?php  echo date("Y-m-d",strtotime($cur_date))  ?>" placeholder=" Date" /> 
					</div>
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
					  <label for="txtSupplier">Customer Name<code>*</code></label>
						  <input type="text" <?php echo $disable; ?> class="form-control " id="Name" value="" placeholder="Name"  />
					</div>
					</div> 
					
					<div class="row">
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
					 <label class='form-label'>Material Name<code>*</code></label>
					  <input type="text" class="form-control" id="txtMaterialName" value="" placeholder="Material Name" /> 
					</div>
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
					  <label for="txtSupplier">Quantity<code>*</code></label>
						  <input type="text"  class="form-control" autocomplete="off" id="Quantity" value="" placeholder="Quantity"  />
					</div>
					</div>
				 <button type="button" id="btnAddrecord"  class="btn btn-success" onclick="addrecord();">Submit
				 </button>
					
					
				
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
 
	
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetAlert2.min.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
 <script src="../assets/js/custom/jquery-1.10.2.min.js"></script>  
 <script src="../assets/js/custom/jquery-ui.js"></script>  



<script>
var valcreated_by="<?php echo $employee_id;?>";


var employeeID="<?php echo $_SESSION['employee_id']; ?>";

var valquery="";
var valquerycount="";
var valmix_waste_lot_id='0';
var EntryDate=$("#txtDate").val();
// const  name=[ ];
// function AutoComplete(){
	// $.ajax({
		// type:"POST",
		// url:"apiAutoComplete.php",
		// data:{},
		// dataType: 'JSON',
		// success:function(response){
			
			// name.push(response);
			    // $( "#Name" ).autocomplete({  
             // source:response, 
			  // autoSelect: true,
			  // minLength:1,     
				// delay:100    
    // });  
			// console.log(response);
		
		// }
	// });
	
// }
// $('#autocomplete').autocomplete({
    // paramName: 'searchString',
    // transformResult: function(response) {
        // return {
            // suggestions: $.map(response.myData, function(dataItem) {
                // return { value: dataItem.valueField, data: dataItem.dataField };
            // })
        // };
    // }
// })


function addrecord(){
  
		
	   if(!validateBlank($("#txtDate").val())){
			setErrorOnBlur("txtDate");
		  } 
		 else if(!validateBlank($("#Name").val())){
			setErrorOnBlur("Name");
		  } 
		  else if(!validateBlank($("#txtMaterialName").val())){
			setErrorOnBlur("txtMaterialName");
		  } 
		  else if(!validateBlank($("#Quantity").val())){
			setErrorOnBlur("Quantity");
		  } 
		  else{
		 $.ajax({
			type:"POST",
			url:"apiSaveOutwardData.php",
			data:{materialtype:$("#txtMaterialName").val(),EntryDate:$("#txtDate").val(),Name:$("#Name").val(),quantityvalue:$("#Quantity").val()},
			success:function(response){
					
				console.log(response);
				if($.trim(response)=="Success"){
					showAlertRedirect("Success","Waste seggregate Successfully","success","pgDataEntry.php");
				}
				else if($.trim(response)=="error"){
					showAlert("Warning","This Data Already Exist On Same Date","warning");
				}
				
				else{
					showAlert("Warning","Something Went Wrong","warning");
				}
				
			}
		});   
		  }
}



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
	var valplaceholder = $("#"+inputComponent).attr("placeholder");
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



</script>
</body> 
</html>