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

$requestid = $_REQUEST["id"];
 $requesttype = $_REQUEST["type"];

$_SESSION["requesttype"] = $requesttype;
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
$Ward="";
if($requesttype=="edit"){
$disable="readonly";
$qry = "SELECT DISTINCT entry_date,name,ward FROM `tw_mixwaste_manual_entry` where  id='".$requestid."' ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);

$date= $decodedJSON->response[0]->entry_date; 
$Name= $decodedJSON->response[1]->name; 
$Ward= $decodedJSON->response[2]->ward; 

$WardnameQry="SELECT id,ward_name FROM `tw_ward_master`";
$Wardname = $sign->FunctionOption($WardnameQry,$Ward,'ward_name',"id");
}

$WardnameQry="SELECT id,ward_name FROM `tw_ward_master`";
$Wardname = $sign->FunctionOption($WardnameQry,$Ward,'ward_name',"id");

 if($requesttype=="edit"){ 
$cur_date =$date;
 } else {
date_default_timezone_set("Asia/Kolkata");

$cur_date=date("Y-m-d h:i:sa");
 }
 
 
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
                  <h4 class="card-title">Inward Dry Waste Data Entry</h4>
                 <div class="forms-sample">
				  <div class="row">
					<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12 ">
					 <label class='form-label'>Date<code>*</code></label>
					 
					  <input type="date" class="form-control" id="txtDate" <?php echo $disable; ?> max='<?php echo $cur_date; ?>'  value="<?php  echo date("Y-m-d",strtotime($cur_date))  ?>" placeholder=" Date" /> 
					</div>
					<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
					  <label for="txtSupplier">Name<code>*</code></label>
						  <input type="text" <?php echo $disable; ?> data-provide="typeahead" onclick="AutoComplete();" class="form-control basicAutoComplete"autocomplete="off" id="Name" value="<?php echo $Name; ?>" placeholder="Name"  />
					</div>
					
					<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
					  <label for="txtWard">Ward<code>*</code></label>
					  
						  <select  class="form-control" id="txtWard"   placeholder="Select Ward">
						  <option value=""> select ward
						  </option >
						<?php echo $Wardname; ?>
					  </select>
					</div>
					</div>
				
					<div id="divData">
					
					</div>
					
				<!--	<button type="button" id="btnAddrecord" class="btn btn-success" onclick="adddata();"><?php if($requesttype=="add"){ ?>Add Record<?php }else{ ?>Update Record<?php } ?></button>  
					</div>
					-->
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
var valcreated_on="<?php echo $cur_date;?>";
var valcreated_ip="<?php echo $ip_address;?>";
var valrequesttype="<?php echo $requesttype;?>";
var employeeID="<?php echo $_SESSION['employee_id']; ?>";
var valrequestid = "<?php echo $requestid;?>";
var valquery="";
var valquerycount="";
var valmix_waste_lot_id='0';
var EntryDate=$("#txtDate").val();
const  name=[ ];
function AutoComplete(){
	$.ajax({
		type:"POST",
		url:"apiAutoComplete.php",
		data:{},
		dataType: 'JSON',
		success:function(response){
			
			name.push(response);
			    $( "#Name" ).autocomplete({  
             source:response, 
			  autoSelect: true,
			  minLength:1,     
				delay:100    
    });  
			console.log(response);
		
		}
	});
	
}
$('#autocomplete').autocomplete({
    paramName: 'searchString',
    transformResult: function(response) {
        return {
            suggestions: $.map(response.myData, function(dataItem) {
                return { value: dataItem.valueField, data: dataItem.dataField };
            })
        };
    }
})
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
$(document).ready(function(){
	showData();
});


var materialtype = [];
var quantity = [];
function showData(id){
		$.ajax({
		type:"POST",
		url:"apiManualSegregate.php",
		data:{id:id,valrequesttype:valrequesttype,valrequestid:valrequestid},
		dataType: 'JSON',
		success:function(response){
			// console.log(response);
			// console.log(response[1]);
			$("#divData").html(response[0]);
			
			//var json = JSON.parse(response[1]);
			var json = response[1];
			
			json.forEach((item) => {
				materialtype.push(item.dataMaterialType);
				quantity.push(item.dataquantity);
			});
			console.log(materialtype);
			console.log(quantity);

		}
	});
}

function addrecord(){

	var quantityvalue = [];
	
		var valTotalquantity = 0;
		for (let i = 0; i < quantity.length; i++) {
		  quantityvalue.push($("#"+quantity[i]+"").val());
		  
		  qty = parseInt($("#"+quantity[i]+"").val());
		 valTotalquantity = (valTotalquantity + qty);
	 		  
		}
	   if(!validateBlank($("#Name").val())){
			setErrorOnBlur("Name");
		  }  
		  else if(!validateBlank($("#txtWard").val())){
			setErrorOnBlur("txtWard");
		  } 
		  else{
		 $.ajax({
			type:"POST",
			url:"apiSaveManualSegregate.php",
			data:{materialtype:materialtype,EntryDate:$("#txtDate").val(),Ward:$("#txtWard").val(),Name:$("#Name").val(),quantityvalue:quantityvalue,valmix_waste_lot_id:valmix_waste_lot_id,valrequesttype:valrequesttype,valrequestid:valrequestid},
			success:function(response){
					
				console.log(response);
				if($.trim(response)=="Success"){
					showAlertRedirect("Success","Waste seggregate Successfully","success","pgPartnerDashboard.php");
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

</script>
</body> 
</html>