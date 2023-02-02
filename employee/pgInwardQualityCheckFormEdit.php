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
$requesttype = $_REQUEST["type"];
$id = $_REQUEST["id"];
$inward_id = $_REQUEST["inward_id"];
$_SESSION["requestid"] = $id; 

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];

$company_id = $_SESSION["company_id"];             
$party_id = ""; 
$party_bill_no = "";
$vehicle_no = "";
$remark = "";
$Status = "";

$qry3 = "select CompanyName from tw_company_details where ID = '".$company_id."'";
$retVal3 = $sign->SelectF($qry3,'CompanyName');  

if($requesttype=="edit"){
	$qry="SELECT mi.id,cd.CompanyName,mi.party_id,mi.party_bill_no,mi.date,mi.vehicle_no,mi.remark FROM tw_material_inward_qc mi INNER JOIN tw_company_details cd ON mi.company_id=cd.ID WHERE mi.id='".$id."'";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	
	$id = $decodedJSON->response[0]->id;
	$CompanyName = $decodedJSON->response[1]->CompanyName;
	$party_id = $decodedJSON->response[2]->party_id;
	$party_bill_no = $decodedJSON->response[3]->party_bill_no; 
	$date = $decodedJSON->response[4]->date;
	$vehicle_no = $decodedJSON->response[5]->vehicle_no;
	$remark = $decodedJSON->response[6]->remark;
	
}
$qry2 = "SELECT id,description FROM tw_test_report_designation_master WHERE company_id='".$company_id."' and visibility='true' ORDER by id  ASC";
$retVal2 = $sign->FunctionOption($qry2,$Status,'description','id');
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Material Inward Quality Check</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<!-- endinject -->
<!-- tw-css:start -->
<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
                  <h4 class="card-title">Quality Certificate</h4>
                 
                  <div class="forms-sample">
                    <div class="form-group">
                      <label for="txtCompanyName">Company Name<code>*</code></label>                      
						<input type="text" readonly class="form-control" id="txtCompanyName" maxlength="100" value="<?php echo $retVal3; ?>" placeholder="Party Name" />                                                  
					</div>
                   <div class="form-group">
                      <label for="txtPartyName">Party Name<code>*</code></label>      							       
					<input type="text" class="form-control" id="txtPartyName" maxlength="100" value="<?php echo $party_id; ?>" placeholder="Party Name" />                                      
					</div>
					<div class="form-group">
                      <label for="txtPartyBillNo">Party Bill Number<code>*</code></label>
						<input type="text" class="form-control" id="txtPartyBillNo" maxlength="100" value="<?php echo $party_bill_no; ?>" placeholder="Party Bill Number" />
					</div>
					
                   <div class="form-group">
                      <label for="txtDate">Date<code>*</code></label>
                      <input type="Date" class="form-control" id="txtDate"  placeholder="Date" value='<?php if(!empty($date)){echo date("Y-m-d",strtotime($date));}?>' />
                    </div> 
					<div class="form-group">
                      <label for="txtVehicleNumber">Vehicle Number<code>*</code></label>
						<input type="text" class="form-control" id="txtVehicleNumber" maxlength="100" value="<?php echo $vehicle_no; ?>" placeholder="Vehicle Number" />
				   </div>
					<hr>
					 <div class="form-group row">
                       <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
							<label for="selDescription">Description<code>*</code></label>
							<select id="selDescription" placeholder="Description" class="form-control form-control-sm">
								<option value="">Select Description</option>
								<?php echo $retVal2; ?>
							</select>
                       </div>
					   <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12">
							<label for="txtNorms">Norms<code>*</code></label>
							<input type="text" readonly id="txtNorms" placeholder="Norms" class="form-control" />
                       </div>
					</div>
					<div class="form-group row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtFirst">1st<code>*</code></label>
							<input type="number" id="txtFirst" placeholder="1st" class="form-control" />
                       </div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtSecond">2nd<code>*</code></label>
							<input type="number" id="txtSecond" placeholder="2nd" class="form-control" />
                       </div>
					   
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtThird">3rd<code>*</code></label>
							<input type="number" id="txtThird" placeholder="3rd" class="form-control" />
                       </div>
					</div>
					<div class="form-group row">
					   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<label for="txtTotal">Total<code>*</code></label>
							<input type="number" readonly id="txtTotal" placeholder="Total" class="form-control" />
                       </div>
					   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<label for="txtAverage">Average<code>*</code></label>
							<input type="number" readonly id="txtAverage" placeholder="3rd" class="form-control" />
                       </div>
					</div>
					
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12" style="text-align:right;">
							<i class="ti-plus"  onclick="funcaddrow();" style="cursor:pointer;"> Add</i>
						</div>
					</div>
					<div class="table-responsive">
						<table id="tableData" class="table">
						 
						</table>
					</div>
					<br>
					<hr>
					<div class="form-group">
                      <label for="txtRemark">Remark<code>*</code></label>
                      <textarea class="form-control" id="txtRemark" maxlength="5000"  placeholder="Remark"><?php echo $remark; ?></textarea>
                    </div>
                    <button type="button" id="btnAddrecord"  class="btn btn-success" onclick="addrecord()"><?php if($requesttype=="add"){ ?>Add Record<?php }else{ ?>Update Record<?php } ?></button>
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>
var valTotal = 0;
var valFirst = 0;
var valSecond = 0;
var valThird = 0;
var valAverage = 0;
var hdnOrderID=<?php echo $id; ?>;

 $(document).ready(function(){
	
	funcremoverowload();
});
$("#selDescription").on('change keyup paste', function () {
	var selectedId = $(this).children("option:selected").val();  
	$.ajax({
	  type:"POST",
	  url:"apiGetNormsInfo.php",
	  data:{selDescription:selectedId},
	  dataType: 'JSON',
	  success:function(response){
		  console.log(response);
		  
		  $("#txtNorms").val(response[0]);
	  }
  }); 
}); 
$("#txtFirst").on('change keyup paste', function () {
	valTotal=0;
	valAverage=0;
	valFirst = Number($("#txtFirst").val());
	valSecond = Number($("#txtSecond").val());
	valThird = Number($("#txtThird").val());
	valTotal = valTotal+(valFirst+valSecond+valThird);
	valAverage = valTotal/3;
	$("#txtTotal").val(valTotal);
	$("#txtAverage").val(valAverage);
}); 
$("#txtSecond").on('change keyup paste', function () {
	valTotal=0;
	valAverage=0;
	valFirst = Number($("#txtFirst").val());
	valSecond = Number($("#txtSecond").val());
	valThird = Number($("#txtThird").val());
	valTotal = valTotal+(valFirst+valSecond+valThird);
	valAverage = valTotal/3;
	$("#txtTotal").val(valTotal);
	$("#txtAverage").val(valAverage);
}); 
$("#txtThird").on('change keyup paste', function () {
	valTotal=0;
	valAverage=0;
	valFirst = Number($("#txtFirst").val());
	valSecond = Number($("#txtSecond").val());
	valThird = Number($("#txtThird").val());
	valTotal = valTotal+(valFirst+valSecond+valThird);
	valAverage = valTotal/3;
	$("#txtTotal").val(valTotal);
	$("#txtAverage").val(valAverage);
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
var valrequesttype = "<?php echo $requesttype;?>";

function addrecord(){
		  if(!validateBlank($("#txtCompanyName").val())){
			setErrorOnBlur("txtCompanyName");
		  }
		  else if(!validateBlank($("#txtPartyName").val())){
			setErrorOnBlur("txtPartyName");
		  } 
		  else if(!validateBlank($("#txtPartyBillNo").val())){
			setErrorOnBlur("txtPartyBillNo");
		  }
		  else if(!validateBlank($("#txtDate").val())){
			setErrorOnBlur("txtDate");
		  } 
		  else if(!validateBlank($("#txtVehicleNumber").val())){
			setErrorOnBlur("txtVehicleNumber");
		  } 
		  else if(!validateBlank($("#txtRemark").val())){
			setErrorOnBlur("txtRemark");
		  }
		  else{
		  
			/* var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valcompany_id = "<?php echo $company_id;?>";
			var valinward_id = "<?php echo $inward_id;?>"; 
			if(valrequesttype=="add"){
				
			var valquery = "insert into tw_material_inward_qc (inward_id,company_id,party_id,party_bill_no,date,vehicle_no,remark,created_by,created_on,created_ip)values('"+valinward_id+"','"+valcompany_id+"','"+$("#txtPartyName").val()+"','"+$("#txtPartyBillNo").val()+"','"+$("#txtDate").val()+"','"+$("#txtVehicleNumber").val()+"','"+$("#txtRemark").val()+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"') ";
			
			var valquerycount = "select count(*) as cnt from tw_material_inward_qc where party_bill_no = '"+$("#txtPartyBillNo").val()+"'"; 
			
			}
			else{
			var valrequestid = "<?php echo $id;?>";
				var valquerycount = "select count(*) as cnt from tw_material_inward_qc where party_bill_no = '"+$("#txtPartyBillNo").val()+"' and id != '"+valrequestid+"'";
				alert(valquerycount);
				var valquery = "Update tw_material_inward_qc set inward_id = '"+valinward_id+"' ,company_id = '"+valcompany_id+"' , party_id = '"+$("#txtPartyName").val()+"', party_bill_no = '"+$("#txtPartyBillNo").val()+"', date = '"+$("#txtDate").val()+"', vehicle_no = '"+$("#txtVehicleNumber").val()+"', remark = '"+$("#txtRemark").val()+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"'";
			 
			}
			
			var buttonHtml = $('#btnAddrecord').html();
			
			disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
			
			$.ajax({         
				type:"POST",
				url:"apiEmployeeProfile.php",                
				data:{valquery:valquery,valquerycount:valquerycount},
				success:function(response){
					console.log(response);
					if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
					}
					else{
						enableButton('#btnAddrecord','Update Record');
					}	
					if($.trim(response)=="Success"){
						if(valrequesttype=="add"){
							showAlertRedirect("Success","Data Added Successfully","success","pgInwardQualityCheck.php?inward_id="+valinward_id);
						}
						else{
							showAlertRedirect("Success","Data Updated Successfully","success","pgInwardQualityCheck.php?inward_id="+valinward_id);
						}
					}
					else if($.trim(response)=="Exist"){
						showAlert("warning","Value already exist","warning");
						$("#txtValue").focus();
					}else{
						showAlert("error","Something Went Wrong. Please Try After Sometime","error");
					}
					
					$('#btnAddrecord').html(buttonHtml);
				}
			});  */
			
			//---
		var buttonHtml = $('#btnAddrecord').html();
		var varinward_id = "<?php echo $inward_id; ?>";
		var valrequesttype="<?php echo $requesttype;?>";
		var valcompany_id = "<?php echo $company_id;?>";

			disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');

		
		$.ajax({
			type:"POST",
			url:"apiAddMaterialInwardQC.php",
			data:{company_id:valcompany_id,txtPartyName:$("#txtPartyName").val(),txtPartyBillNo:$("#txtPartyBillNo").val(),txtDate:$("#txtDate").val(),
			txtVehicleNumber:$("#txtVehicleNumber").val(),txtRemark:$("#txtRemark").val(),inward_id:varinward_id},
			success:function(response){
				console.log(response);
				if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
				}
				else
				{
					enableButton('#btnAddrecord','Update Record');
				}
				if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Data Added Successfully","success","pgInwardQualityCheck.php?inward_id="+varinward_id);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgInwardQualityCheck.php?inward_id="+varinward_id);
					}
				}
				else if($.trim(response)=="Blank"){
					showAlert("warning","Please Add Description","warning");
					$("#selDescription").focus();
				}else{
					showAlert("error","Something Went Wrong. Please Try After Sometime","error");
				}
				
				$('#btnAddrecord').html(buttonHtml);
			}
		}); 
		//---
		
		  }
}
function funcaddrow(){
   if(!validateBlank($("#selDescription").val())){
		setErrorOnBlur("selDescription");
   }
   else if(!validateBlank($("#txtNorms").val())){
		setErrorOnBlur("txtNorms");
   }
   else if(!validateBlank($("#txtFirst").val())){
		setErrorOnBlur("txtFirst");
   }
   else if(!validateBlank($("#txtSecond").val())){
		setErrorOnBlur("txtSecond");
   }
   else if(!validateBlank($("#txtThird").val())){
		setErrorOnBlur("txtThird");
   }
   else if(!validateBlank($("#txtTotal").val())){
		setErrorOnBlur("txtTotal");
   }
   else if(!validateBlank($("#txtAverage").val())){
		setErrorOnBlur("txtAverage");
   }
  else{
	  $.ajax({
          type:"POST",
          url:"apiAddTempMaterialInwardQC.php",
          data:{selDescription:$("#selDescription").val(),txtNorms:$("#txtNorms").val(),txtFirst:$("#txtFirst").val(),txtSecond:$("#txtSecond").val(),txtThird:$("#txtThird").val(),txtTotal:$("#txtTotal").val(),txtAverage:$("#txtAverage").val()},
          success:function(response){
			  console.log(response);
			$("#tableData").html(response);
			$("#selDescription").val("");
			$("#txtNorms").val("");
			$("#txtFirst").val("");
			$("#txtSecond").val("");
			$("#txtThird").val(""); 
			$("#txtTotal").val(""); 
			$("#txtAverage").val(""); 
          }
      });
  }
}
function funcremoverowload(){
	
	   $.ajax({
          type:"POST",
          url:"apiDeleteTempMaterialInwardQC.php",
          data:{requestidid:''},
          success:function(response){
			$('#tableData').html(response);
			$("#txtDescription").val("");
			$("#txtQuantity").val("");
			$("#txtUOM").val("");
			$("#txtRate").val("");
			showdata();
          }
      }); 
}
function funcremoverow(id){
	   $.ajax({
          type:"POST",
          url:"apiDeleteTempMaterialInwardQC.php",
          data:{requestidid:id},
          success:function(response){
			$('#tableData').html(response);
			var resp=response;
			var array = resp.split("</tbody>,");
			$("#txtFinalTotalAmount").val(array[1]);
          }
      }); 
}
function showdata(){
	
	  $.ajax({
          type:"POST",
          url:"apiGetTempTableDetailsQC.php",
          data:{hdnOrderID:hdnOrderID},
          success:function(response){
            console.log(response);
			$("#tableData").html(response);
			var resp=response;
			var array = resp.split("</tbody>,");
			$("#txtFinalTotalAmount").val(array[1]);
		   
          }
      });
  }
</script>	
</body>

</html>