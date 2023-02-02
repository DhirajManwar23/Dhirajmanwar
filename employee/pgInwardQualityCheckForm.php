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
$request_id = $_REQUEST["id"];
$inward_id = $_REQUEST["inward_id"];
$_SESSION["requestid"] = $request_id; 

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");
$created_by=$_SESSION["employee_id"];

$company_id = $_SESSION["company_id"];             
$party_id = ""; 
$party_bill_no = "";
$vehicle_no = "";
$remark = "";
$Status = "";
$DocNo=" ";

$qryQC="SELECT COUNT(*) as cnt from tw_material_inward_documents WHERE type='QC' and inward_id='".$inward_id."' ORDER BY inward_id ASC";
$retValQC = $sign->SelectF($qryQC,"cnt");

$qry1QC="SELECT COUNT(*) as cnt from tw_material_inward_qc WHERE inward_id='".$inward_id."' ORDER BY inward_id ASC";
$retVal1QC = $sign->SelectF($qry1QC,"cnt");

if($retValQC>0 || $retVal1QC>0){
	$disabledQC="disabled";
}
else{
	$disabledQC="";
}


$qry3 = "select CompanyName from tw_company_details where ID = '".$company_id."'";
$retVal3 = $sign->SelectF($qry3,'CompanyName');

$qryDocCnt="Select  count(*) as cnt from  tw_material_outward_documents WHERE outward_id='".$inward_id."' and type='invoice' ";
$DocCnt = $sign->Select($qryDocCnt);
if($DocCnt==1){
	$qryDocno = "SELECT document_value FROM `tw_material_outward_documents` WHERE outward_id='".$inward_id."'";
	$DocNo = $sign->SelectF($qryDocno,'document_value'); 
}
else{
	$qryDocCnt1="Select count(*) as cnt from  tw_tax_invoice WHERE outward_id='".$inward_id."'";
	$DocCnt1 = $sign->Select($qryDocCnt1);
	if($DocCnt1==1){
		$qryDocno = "SELECT invoice_number FROM `tw_tax_invoice` WHERE outward_id='".$inward_id."'";
		$DocNo = $sign->SelectF($qryDocno,'invoice_number'); 
	}
	
	
}


$moqry = "SELECT cd.ID,cd.CompanyName,mo.vehicle_id ,mo.po_id FROM tw_company_details cd INNER JOIN tw_material_outward mo on mo.company_id=cd.ID where mo.id='".$inward_id."' ";
$retValmo = $sign->FunctionJSON($moqry);
$decodedJSON = json_decode($retValmo);
$party_id = $decodedJSON->response[0]->ID;
$PartyName = $decodedJSON->response[1]->CompanyName;
$vehicle_id = $decodedJSON->response[2]->vehicle_id;
$po_id = $decodedJSON->response[3]->po_id;


$VechicleQry="SELECT vehicle_number FROM tw_vehicle_details_master where id='".$vehicle_id."'";
$Vechicle = $sign->SelectF($VechicleQry,'vehicle_number');

if($requesttype=="edit"){
	$qry="SELECT mi.id,cd.CompanyName,mi.party_id,mi.party_bill_no,mi.date,mi.vehicle_no,mi.remark FROM tw_material_inward_qc mi INNER JOIN tw_company_details cd ON mi.company_id=cd.ID WHERE mi.id='".$request_id."'";
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
$qry2 = "SELECT description FROM tw_test_report_designation_master WHERE company_id='".$company_id."' and visibility='true' ORDER by id  ASC";
$retVal2 = $sign->FunctionJSON($qry2);

$qry4="Select  count(*) as cnt from  tw_test_report_designation_master WHERE company_id='".$company_id."' and visibility='true' ORDER by id  ASC ";
$retVal4 = $sign->Select($qry4);

$decodedJSON2 = json_decode($retVal2);
$x=$retVal4;
$it=1;
$count=0;
$i = 1;
$description="";
while($x>=$i){
$description = $decodedJSON2->response[$count]->description;
$count=$count+1;
$i=$i+1;
}
 $description;

$dateqry="SELECT po_date FROM `tw_temp_po_info` where id='".$po_id."'";
$fetchDate=$sign->SelectF($dateqry,"po_date");
$datetime = $fetchDate;
$podate = date('Y-m-d', strtotime($datetime));

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
						<div class="row">
					   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<label for="txtCompanyName">Company Name<code>*</code></label>                      
							<input type="text" readonly class="form-control" id="txtCompanyName" maxlength="100" value="<?php echo $retVal3; ?>" placeholder="Party Name" />  
                       </div>
					   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<label for="txtPartyName">Vendor Name<code>*</code></label>      							       
							<input type="text" class="form-control" readonly id="txtPartyName" maxlength="100" value="<?php echo $PartyName; ?>" placeholder="Vendor Name" />  
                       </div>
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtPartyBillNo">Vendor Bill Number<code>*</code></label>
							<input type="text" class="form-control" readonly  id="txtPartyBillNo" maxlength="100" value="<?php echo $DocNo; ?>" placeholder="Vendor Bill Number" /> 
                       </div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtDate">Date<code>*</code></label>
							<input type="Date" class="form-control" id="txtDate" min="<?php echo $podate; ?>" max="<?php echo $cur_date; ?>" placeholder="Date" value='<?php if(!empty($date)){echo date("Y-m-d",strtotime($date));}else{echo date("Y-m-d",strtotime($cur_date));}?>' /> 
                       </div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtVehicleNumber">Vehicle Number<code>*</code></label>
							<input type="text" class="form-control" id="txtVehicleNumber" readonly maxlength="100" value="<?php echo $Vechicle; ?>" placeholder="Vehicle Number" /> 
                       </div>
                       </div>
					</div>
					<hr>
					<div class="table-responsive">
				   <table border="1"  id="tableData" class="table" width="100%">
				  
					
				   </table>
					</div>
					<hr>
					<div class="form-group">
                      <label for="txtRemark">Remark<code>*</code></label>
                      <textarea class="form-control" id="txtRemark" maxlength="5000"  placeholder="Remark"><?php echo $remark; ?></textarea>
                    </div>
					
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
						<?php if($requesttype=="add"){ ?> 
                    <button type="button" id="btnAddrecord" <?php echo $disabledQC;?> class="btn btn-success" onclick="addrecord();">
					Add Record </button><?php }  else { ?> 
					
					
					<button type="button" id="btnAddrecord"  class="btn btn-success" onclick="Updaterecord();">Update Record </button>
					<?php } ?>
						</div>
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
var valpo_id='<?php echo $po_id; ?>';
var qc_id="";
$(document).ready(function(){
	showData(); //Show QC table
});
var valrequesttype = "<?php echo $requesttype;?>";
var inward_id = "<?php echo $inward_id;?>";
var request_id = "<?php echo $request_id ;?>";
function showData(){
	
		$.ajax({
			type:"POST",
			url:"apiGetViewQualityCheck.php",
			data:{request_id:request_id,valrequesttype:valrequesttype,inward_id:inward_id},
			success:function(response){
				console.log(response);
				 $("#tableData").html(response);
			}
		});
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
	
					//-----------------------------LOOP TABLE START---------------------------
		var tblLang = document.getElementById("tableData");
		var tblrows = tblLang.rows;
		var insertQueryValue="insert into tw_material_inward_qc_individual(material_inward_qc_id,employee_id,description,norms,first,second,third,total,average,created_by,created_on,created_ip) values";
		for(var i=1; i<tblrows.length; i++){
		var tblcells = tblrows[i].cells;
		//Description ID from 2nd Column
		var dbID = tblcells[1].id;
		//For 3rd Column
		var norm = tblcells[2].innerHTML;
		//For 4th Column
		var temp3 = tblcells[3].innerHTML;
		var indexOfTxt3 = temp3.indexOf("txt");
		var indexOfDQ3 = temp3.indexOf("\"",indexOfTxt3);
		var substringTxt3 = temp3.substring(indexOfTxt3,indexOfDQ3);
		var varFirst = $("#" + substringTxt3).val();
		//For 5th Column
		var temp4 = tblcells[4].innerHTML;
		var indexOfTxt4 = temp4.indexOf("txt");
		var indexOfDQ4 = temp4.indexOf("\"",indexOfTxt4);
		var substringTxt4 = temp4.substring(indexOfTxt4,indexOfDQ4);
		var varSecond = $("#" + substringTxt4).val();
		//For 6th Column
		var temp5 = tblcells[5].innerHTML;
		var indexOfTxt5 = temp5.indexOf("txt");
		var indexOfDQ5 = temp5.indexOf("\"",indexOfTxt5);
		var substringTxt5 = temp5.substring(indexOfTxt5,indexOfDQ5);
		var varThird = $("#" + substringTxt5).val();
		//For 7th Column
		var temp6 = tblcells[6].innerHTML;
		var indexOfTxt6 = temp6.indexOf("txt");
		var indexOfDQ6 = temp6.indexOf("\"",indexOfTxt6);
		var substringTxt6 = temp6.substring(indexOfTxt6,indexOfDQ6);
		var varTotal = $("#" + substringTxt6).val();
		//For 8th Column
		var temp7 = tblcells[7].innerHTML;
		var indexOfTxt7 = temp7.indexOf("txt");
		var indexOfDQ7 = temp7.indexOf("\"",indexOfTxt7);
		var substringTxt7 = temp7.substring(indexOfTxt7,indexOfDQ7);
		var varAvg = $("#" + substringTxt7).val();

		insertQueryValue=insertQueryValue+"('QCID','EMPID','" + dbID + "','" + norm + "','" + varFirst + "','" + varSecond + "','" + varThird + "','" + varTotal + "','" + varAvg + "','EMPID','CREATEDON','CREATEDIP'),";
		}
		insertQueryValue=insertQueryValue.slice(0, -1);
					//-----------------------------LOOP TABLE END---------------------------
					
					//---
		var buttonHtml = $('#btnAddrecord').html();
		var varinward_id = "<?php echo $inward_id; ?>";
		var valrequesttype="<?php echo $requesttype;?>";
		var valcompany_id = "<?php echo $company_id;?>";
		var party_id = "<?php echo $party_id;?>";

			disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');

		
		$.ajax({
			type:"POST",
			url:"apiAddMaterialInwardQC.php",
			data:{txtPartyName:party_id,txtPartyBillNo:$("#txtPartyBillNo").val(),txtDate:$("#txtDate").val(),txtVehicleNumber:$("#txtVehicleNumber").val(),txtRemark:$("#txtRemark").val(),inward_id:varinward_id,party_id:party_id,valrequesttype:valrequesttype,insertQueryValue:insertQueryValue,request_id:'',valcompany_id:valcompany_id},
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
						showAlertRedirect("Success","Data Added Successfully","success","pgMaterialInward.php?type=In Process&po_id="+valpo_id);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialInward.php?type=In Process&po_id="+valpo_id);
					}
				}
				else if($.trim(response)=="Blank"){
					showAlert("warning","Please Add Data","warning");
					$("#selDescription").focus();
				} 
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Entry already exist","warning");
				}
				else if($.trim(response)=="Status"){
					showAlert("Warning","Outward has been completed you can not upload this document","warning");
				}
				else{
					showAlert("error","Something Went Wrong. Please Try After Sometime","error");
				}
				
				$('#btnAddrecord').html(buttonHtml);
			}
		}); 
		//---
		
		  }
}
function Updaterecord(){
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
		  
				qc_id = "<?php echo $request_id;?>";
				var tblLang = document.getElementById("tableData");
var tblrows = tblLang.rows;
var insertQueryValue="";
for(var i=1; i<tblrows.length; i++){
var tblcells = tblrows[i].cells;
//For 3rd Column
var norm = tblcells[2].innerHTML;
//For 4th Column
var temp3 = tblcells[3].innerHTML;
var indexOfTxt3 = temp3.indexOf("txt");
var indexOfDQ3 = temp3.indexOf("\"",indexOfTxt3);
var substringTxt3 = temp3.substring(indexOfTxt3,indexOfDQ3);
var varFirst = $("#" + substringTxt3).val();
//For 5th Column
var temp4 = tblcells[4].innerHTML;
var indexOfTxt4 = temp4.indexOf("txt");
var indexOfDQ4 = temp4.indexOf("\"",indexOfTxt4);
var substringTxt4 = temp4.substring(indexOfTxt4,indexOfDQ4);
var varSecond = $("#" + substringTxt4).val();
//For 6th Column
var temp5 = tblcells[5].innerHTML;
var indexOfTxt5 = temp5.indexOf("txt");
var indexOfDQ5 = temp5.indexOf("\"",indexOfTxt5);
var substringTxt5 = temp5.substring(indexOfTxt5,indexOfDQ5);
var varThird = $("#" + substringTxt5).val();
//For 7th Column
var temp6 = tblcells[6].innerHTML;
var indexOfTxt6 = temp6.indexOf("txt");
var indexOfDQ6 = temp6.indexOf("\"",indexOfTxt6);
var substringTxt6 = temp6.substring(indexOfTxt6,indexOfDQ6);
var varTotal = $("#" + substringTxt6).val();
//For 8th Column
var temp7 = tblcells[7].innerHTML;
var indexOfTxt7 = temp7.indexOf("txt");
var indexOfDQ7 = temp7.indexOf("\"",indexOfTxt7);
var substringTxt7 = temp7.substring(indexOfTxt7,indexOfDQ7);
var varAvg = $("#" + substringTxt7).val();
//Description id
var descID=substringTxt3.charAt(substringTxt3.length-1);
insertQueryValue=insertQueryValue+"Update tw_material_inward_qc_individual SET first='" + varFirst + "', second='" + varSecond + "', third='" + varThird + "', total='" + varTotal + "', average='" + varAvg + "', modified_by='EMPID', modified_on='MODDATE', modified_ip='MODIP' where material_inward_qc_id='" + qc_id + "' and description='" + descID + "'N;N";
}

			//-----------------------------LOOP TABLE END---------------------------
			
			//---
		var buttonHtml = $('#btnAddrecord').html();
		var varinward_id = "<?php echo $inward_id; ?>";
		var valrequesttype="<?php echo $requesttype;?>";
		var valcompany_id = "<?php echo $company_id;?>";
		var party_id = "<?php echo $party_id;?>";

			disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');

		
		$.ajax({
			type:"POST",
			url:"apiAddMaterialInwardQC.php",
			data:{txtPartyName:party_id,txtPartyBillNo:$("#txtPartyBillNo").val(),txtDate:$("#txtDate").val(),txtVehicleNumber:$("#txtVehicleNumber").val(),txtRemark:$("#txtRemark").val(),inward_id:varinward_id,valrequesttype:valrequesttype,insertQueryValue:insertQueryValue,request_id:qc_id,valcompany_id:valcompany_id},
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
						showAlertRedirect("Success","Data Added Successfully","success","pgMaterialInward.php?type=In Process&po_id="+valpo_id);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialInward.php?type=In Process&po_id="+valpo_id);
					}
				}
				else if($.trim(response)=="Blank"){
					showAlert("warning","Please Add Data","warning");
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
var Workid="";

function work(id){
workid=id;
var valTotal = 0;
var valFirst = 0;
var valSecond = 0;
var valThird = 0;
var valAverage = 0;
		valFirst = Number($("#txtFirst" + id).val());
		valSecond = Number($("#txtSecond" + id).val());
		valThird = Number($("#txtThird" + id).val());
		valTotal = valTotal+(valFirst+valSecond+valThird);
		valAverage =  Math.round(valTotal/3);
		$("#txtTotal" + id).val(valTotal);
		$("#txtAverage" + id).val(valAverage);

}
</script>	
</body>

</html>