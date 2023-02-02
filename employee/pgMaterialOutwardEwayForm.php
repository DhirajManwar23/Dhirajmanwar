<?php 
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$id = $_REQUEST["id"];
$outwardid = $_REQUEST["outwardid"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
	
$sender_company_id	 = "";
$receiver_company_id = ""; 
$valid_upto = ""; 
$mode_transportation = "";
$approx_distance = "";
$eway_bill_type = "";
$transaction_type = "";
$address_id_sender = "";
$address_id_receiver = "";
$transporter_id = "";
$vehicle_id = "";
$SendAdd="";
$ReceiveAdd="";
$transName="";
if($requesttype=="edit"){
	$qry = "SELECT sender_company_id,receiver_company_id,valid_upto,mode_transportation,approx_distance,eway_bill_type,transaction_type,address_id_sender,address_id_receiver,transporter_id,vehicle_id from  tw_material_outward_eway WHERE id = '".$id."' ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	
	$sender_company_id= $decodedJSON->response[0]->sender_company_id;
	$receiver_company_id = $decodedJSON->response[1]->receiver_company_id; 
	$valid_upto = $decodedJSON->response[2]->valid_upto; 
	$mode_transportation = $decodedJSON->response[3]->mode_transportation;
	$approx_distance = $decodedJSON->response[4]->approx_distance;
	$eway_bill_type = $decodedJSON->response[5]->eway_bill_type;
	$transaction_type = $decodedJSON->response[6]->transaction_type;
	$address_id_sender = $decodedJSON->response[7]->address_id_sender;
	$address_id_receiver = $decodedJSON->response[8]->address_id_receiver;
	$transporter_id = $decodedJSON->response[9]->transporter_id;
	$vehicle_id = $decodedJSON->response[10]->vehicle_id;  
	
	$qry4 = "SELECT ID,CompanyName FROM tw_company_details where ID!='".$company_id."' ORDER by id  ASC";
	$retVal4 = $sign->FunctionOption($qry4,$receiver_company_id,'CompanyName','ID');
	
	$qrySendAdd="select concat(ca.address_line1,' ',ca.address_line2,' ',ca.location,' ',ca.city,' ',ca.state,' ',ca.country) as sender_address,ca.ID FROM tw_company_address ca INNER JOIN tw_material_outward_eway moe on moe.address_id_sender=ca.id";
	$SendAdd=$sign->SelectF($qrySendAdd,'sender_address');
	
	$qryReceiveAdd="select concat(ca.address_line1,' ',ca.address_line2,' ',ca.location,' ',ca.city,' ',ca.state,' ',ca.country) as receiver_address,ca.ID FROM tw_company_address ca INNER JOIN tw_material_outward_eway moe on moe.address_id_receiver=ca.id";
	$ReceiveAdd=$sign->SelectF($qryReceiveAdd,'receiver_address');
	
	$qrytrans="SELECT transporter_name FROM `tw_transport_master` where id='".$transporter_id."'";
	$transName=$sign->SelectF($qrytrans,'transporter_name');
	


}

$qry1 = "SELECT CompanyName from  tw_company_details WHERE ID = '".$company_id."' ";
$retVal1 = $sign->SelectF($qry1,"CompanyName");

$qry2 = "SELECT id,transporter_name from tw_transport_master Order by id desc";
$retVal2 = $sign->FunctionOption($qry2,'','transporter_name','id');

$qry3 = "SELECT id,vehicle_id from tw_vehicle_details_master Order by id desc";
$retVal3 = $sign->FunctionOption($qry3,'','vehicle_id','id');



$qry4="SELECT cd.CompanyName FROM tw_company_details cd INNER join tw_material_outward mo on mo.customer_id=cd.id where mo.id='".$outwardid."' AND mo.company_id='".$company_id."' GROUP by cd.CompanyName";
//$qry4 = "SELECT ID,CompanyName FROM tw_company_details where ID!='".$company_id."' ORDER by id  ASC";
$retVal4 = $sign->SelectF($qry4,"CompanyName");

$qrycid="SELECT customer_id FROM `tw_material_outward` where id='".$outwardid."'";
$cid=$sign->SelectF($qrycid,"customer_id");

$qry5="SELECT id, concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country)as address,address_type FROM tw_company_address where company_id='".$company_id."'";
$retVal5 = $sign->FunctionJSON($qry5);

 $qryCnt="Select count(*) as cnt from tw_company_address  where company_id='".$company_id."'";
	$retValcnt = $sign->Select($qryCnt);
	$decodedJSON = json_decode($retVal5);
	//var_dump($decodedJSON);
	 $count = 0;
	 $i = 1;
	 $x=$retValcnt;
	$returnADD="";
	 while($x>=$i){
		 $address_id=$decodedJSON->response[$count]->id;
		 $count=$count+1;
		 $address=$decodedJSON->response[$count]->address;
		 $count=$count+1;
		 $address_type = $decodedJSON->response[$count]->address_type;
		 $count=$count+1;
		  $i=$i+1;
	 } 

$qry6="SELECT address_icon FROM tw_address_type_master where id='".$address_type."'";
		 $address_icon= $sign->SelectF($qry6,"address_icon");
		 
 $qry7="SELECT address_type_value FROM tw_address_type_master where id='".$address_type."'";
 $address_type_value= $sign->SelectF($qry7,"address_type_value");

$addPass='"'.$address.'"';
		 $returnADD.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'><div class='card'><div class='card-body'><h4 class='card-title'><a href='javascript:void(0)' onclick='saveSendToAddress(".$address_id.",".$addPass.")'> <input type='radio' id='radAddress' class='radAddress' name='radAddress' value=".$address_id." ></a> <i class=".$address_icon."></i> ".$address_type_value."</h4><p>".$address."</p></div></div></div>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Eway</title>
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
	
  <!--=========================== MODAL1 START ==============================-->
  <div class="modal" id="ModalSenderAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pick up address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" >
          <?php echo $returnADD; ?>
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" onclick="SubmitADD()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================--> 
  
   <!--=========================== MODAL2 START ==============================-->
  <div class="modal" id="ModalBillAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bill to address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" id="Main">
           
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" onclick="SubmitADD()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================--> 
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Eway</h4>
                 
                  <div class="forms-sample">
                    <div class="form-group">
                        <label for="txtSender">Sender Name<code>*</code></label>
						<input type="text" class="form-control"disabled id="txtSender" maxlength="100" value="<?php echo $retVal1; ?>" placeholder="Sender Name" />
					</div>
					<div class="form-group">
                        <label for="txtReceiver">Receiver Name<code>*</code></label>
						<input id="txtReceiver" class="form-control form-control-sm"disabled value="<?php echo $retVal4; ?>" />
							
					</div>
					<div class="form-group">
                        <label for="txtValid_Upto">Valid Upto<code>*</code></label>
						<input type="date" class="form-control" id="txtValid_Upto" maxlength="100" value="<?php echo $valid_upto; ?>" placeholder="Valid upto" />
					</div>
					<div class="form-group">
                        <label for="txtMode_Transportation">Mode Transportation<code>*</code></label>
						<input type="text" class="form-control" id="txtMode_Transportation" maxlength="100" value="<?php echo $mode_transportation; ?>" placeholder="Mode Transportation" />
					</div>
					<div class="form-group">
                        <label for="txtApprox_Distance">Approx Distance<code>*</code></label>
						<input type="text" class="form-control" id="txtApprox_Distance" maxlength="100" value="<?php echo $approx_distance; ?>" placeholder="Approx Distance" />
					</div>
					<div class="form-group">
                        <label for="txtEway_Bill_Type">Eway Bill Type<code>*</code></label>
						<input type="text" class="form-control" id="txtEway_Bill_Type" maxlength="100" value="<?php echo $eway_bill_type; ?>" placeholder="Eway Bill Type" />
					</div>
                    <div class="form-group">
                        <label for="txtTransaction_Type">Transaction Type<code>*</code></label>
						<input type="text" class="form-control" id="txtTransaction_Type" maxlength="100" value="<?php echo $transaction_type; ?>" placeholder="Transaction Type" />
					</div>
                    <div class="form-group">
                        <label for="txtSender_Address">Sender Address <a href="#" class="primary" onclick="showModal();" > pick up address</a><code>*</code></label>
						<input type="text" class="form-control" id="txtSender_Address" maxlength="100" value="<?php echo $SendAdd ?>" placeholder="Sender Address" >
						</input>
						
						
						<input type="hidden" class="form-control" id="txtSender_Addressid" maxlength="100" value="<?php echo $address_id_sender ?>" placeholder="Sender Address" />
					</div> 
					<div class="form-group">
                        <label for="txtReceiver_Address">Receiver Address<a href="#" class="primary" onclick="showReceiverModal();" > pick up address</a><code>*</code></label>
						<input type="text" class="form-control" id="txtReceiver_Address" maxlength="100" value="<?php echo $ReceiveAdd; ?>" placeholder="Receiver Address" />
						<input type="hidden" class="form-control" id="txtReceiver_Addressid" maxlength="100" value="<?php echo $address_id_receiver ?>" placeholder="Sender Address" />
					</div>
                    <div class="form-group">
                        <label for="txtTransporter_Name">Transporter Name<code>*</code></label>
						<select name="txtTransporter_Name" id="txtTransporter_Name" class="form-control" onchange="vehicalFun()" >
						<option value="">Select Transporter</option>
						
						 <?php  echo $retVal2;?>
						</select>
					</div>
                    <div class="form-group">
                        <label for="txtVehicle_Name">Vehicle Name<code>*</code></label>
						<select name="txtVehicle_Name" id="txtVehicle_Name" class="form-control" >
						<option value="">Vehicle Name</option>
						
						</select>
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
 $(document).ready(function(){
myFunction();


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
var sendid='';
function saveSendToAddress(id,address){
	sendid=id;
	
	$("#txtSender_Address").val(address);
	$("#txtSender_Addressid").val(sendid);
	$("#txtSender_Address").attr('readonly', true);
   $("#txtSender_Addressid").attr('readonly', true);
	
}

function vehicalFun(){
	var val = $('#txtTransporter_Name').val();
	
	$.ajax({
			type:"POST",
			url:"apiGetVehical.php",
			data:{id:val},
			dataType: 'JSON',
			success:function(response){
			//console.log(response);
			console.log(response[0]);
			
			
		
	       $("#txtVehicle_Name").html(response[0]);
		  
		
			}
		});
	
}

function myFunction(){

	var val = "<?php echo $cid; ?>";
	$.ajax({
			type:"POST",
			url:"apiGetAddressDetails.php",
			data:{id:val},
			dataType: 'JSON',
			success:function(response){
			//const myArray = response.split("-");
			//console.log(response);
			console.log(response[0]);
			
			
			
	      
	       $("#Main").html(response[0]);
		  
		
			}
		});
}
var Receiver_id='';
function saveAdd(id,address){
	Receiver_id=id;
$("#txtReceiver_Address").val(address);
$("#txtReceiver_AddressID").val(id);
$("#txtReceiver_Address").attr('readonly', true);
$("#txtReceiver_AddressID").attr('readonly', true);
}

var valrequesttype = "<?php echo $requesttype;?>";

function addrecord(){
		  if(!validateBlank($("#txtSender").val())){
			setErrorOnBlur("txtSender");
		  }
		  else if(!validateBlank($("#txtReceiver").val())){
			setErrorOnBlur("txtReceiver");
		  } 
		  else if(!validateBlank($("#txtValid_Upto").val())){
			setErrorOnBlur("txtValid_Upto");
		  }
		  else if(!validateBlank($("#txtMode_Transportation").val())){
			setErrorOnBlur("txtMode_Transportation");
		  } 
		  else if(!validateBlank($("#txtApprox_Distance").val())){
			setErrorOnBlur("txtApprox_Distance");
		  }
		  else if(!validateBlank($("#txtEway_Bill_Type").val())){
			setErrorOnBlur("txtEway_Bill_Type");
		  }
		  else if(!validateBlank($("#txtTransaction_Type").val())){
			setErrorOnBlur("txtTransaction_Type");
		  }
		  else if(!validateBlank($("#txtSender_Address").val())){
			setErrorOnBlur("txtSender_Address");
		  }
		  else if(!validateBlank($("#txtReceiver_Address").val())){
			setErrorOnBlur("txtReceiver_Address");
		  }
		  else if(!validateBlank($("#txtTransporter_Name").val())){
			setErrorOnBlur("txtTransporter_Name");
		  }
		  else if(!validateBlank($("#txtVehicle_Name").val())){
			setErrorOnBlur("txtVehicle_Name");
		  }
		  else{
			var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valoutwardid = "<?php echo $outwardid;?>";
			var valcompany_id = "<?php echo $company_id;?>";
			if(valrequesttype=="add"){
				var valquery = "insert into tw_material_outward_eway(sender_company_id,receiver_company_id,outward_id,valid_upto,mode_transportation,approx_distance,eway_bill_type,transaction_type,address_id_sender,address_id_receiver,transporter_id,vehicle_id,created_by,created_on,created_ip)values('"+$("#txtSender").val()+"','"+$("#txtReceiver").val()+"','"+valoutwardid+"','"+$("#txtValid_Upto").val()+"','"+$("#txtMode_Transportation").val()+"','"+$("#txtApprox_Distance").val()+"','"+$("#txtEway_Bill_Type").val()+"','"+$("#txtTransaction_Type").val()+"','"+sendid+"','"+Receiver_id+"','"+$("#txtTransporter_Name").val()+"','"+$("#txtVehicle_Name").val()+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				console.log(valquery);
				var valquerycount = "select count(*) as cnt from tw_material_outward_eway where sender_company_id = '"+valcompany_id+"'";
			}
			else{
				var valrequestid = "<?php echo $id;?>";
				var valquerycount = "select count(*) as cnt from tw_material_outward_eway where sender_company_id = '"+valcompany_id+"'";;
				var valquery = "Update tw_material_outward_eway set sender_company_id = '"+$("#txtSender").val()+"' , receiver_company_id = '"+$("#txtReceiver").val()+"', valid_upto = '"+$("#txtValid_Upto").val()+"', mode_transportation = '"+$("#txtMode_Transportation").val()+"', approx_distance = '"+$("#txtApprox_Distance").val()+"', eway_bill_type = '"+$("#txtEway_Bill_Type").val()+"', transaction_type = '"+$("#txtTransaction_Type").val()+"', address_id_sender = '"+$("#txtSender_Addressid").val()+"', address_id_receiver = '"+$("#txtReceiver_Addressid").val()+"', transporter_id = '"+$("#txtTransporter_Name").val()+"', vehicle_id = '"+$("#txtVehicle_Name").val()+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
			
			}
			//console.log(valquery);
			var buttonHtml = $('#btnAddrecord').html();
			
			$('#btnAddrecord').attr("disabled","true");
			$('#btnAddrecord').removeClass('btn-success');
			$('#btnAddrecord').addClass('btn-secondary');//secondary;
			$('#btnAddrecord').html('<i class="ti-timer"></i> Processing...');
			 
			
			$.ajax({
				type:"POST",
				url:"apiEmployeeProfile.php",
				data:{valquery:valquery,valquerycount:valquerycount},
				success:function(response){
					console.log(response);
					$('#btnAddrecord').removeAttr('disabled');
					$('#btnAddrecord').removeClass('btn-secondary');
					$('#btnAddrecord').addClass('btn-success');
					if($.trim(response)=="Success"){
						if(valrequesttype=="add"){
							showAlertRedirect("Success","Data Added Successfully","success","pgMaterialOutwardEway.php?id="+valoutwardid);
						}
						else{
							showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialOutwardEway.php?id="+valoutwardid);
						}
					}
					else if($.trim(response)=="Exist"){
						showAlert("warning","Value already exist","warning");
						$("#txtValue").focus();
					}else{
						//showAlert("error","Something Went Wrong. Please Try After Sometime","error");
						if(valrequesttype=="add"){
							showAlertRedirect("Success","Data Added Successfully","success","pgMaterialOutwardEway.php?id="+valoutwardid);
						}
						else{
							showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialOutwardEway.php?id="+valoutwardid);
						}
					}
					
					$('#btnAddrecord').html(buttonHtml);
				}
			}); 
		  }
}

function showModal(){
	jQuery.noConflict();
	$("#ModalSenderAddress").modal("show");
}
function showReceiverModal(){
	jQuery.noConflict();
	$("#ModalBillAddress").modal("show");
}
// function myFunction(){
	// var val = $('#txtReceiver').val();
	// alert(val);
	// $.ajax({
			// type:"POST",
			// url:"apiGetAddressDetails.php",
			// data:{id:val},
			// dataType: 'JSON',
			// success:function(response){
			// console.log(response);
			// //const myArray = response.split("-");
			// console.log(response[0]);
			
	       // $("#Main").html(response[0]);
		  
		
			// }
		// });


// }
</script>	
</body>

</html>