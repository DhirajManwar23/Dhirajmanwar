<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["username"];
$AdminImagePathFlag = $commonfunction->getSettingValue("AdminImagePathFlag");

$country_name = "";
$symbol = "";
$flag = "";
$currency = "";
$country_code = "";
$priority = "";
$visibility = "";
	
if($requesttype=="edit")
{
	$qry="select country_name,symbol,flag,currency,country_code,priority,visibility from tw_country_master where ID='".$requestid."'";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$country_name = $decodedJSON->response[0]->country_name; 
	$symbol = $decodedJSON->response[1]->symbol; 
	$flag = $decodedJSON->response[2]->flag; 
	$currency = $decodedJSON->response[3]->currency; 
	$country_code = $decodedJSON->response[4]->country_code; 
	$priority = $decodedJSON->response[5]->priority; 
	$visibility = $decodedJSON->response[6]->visibility;

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |Country Master</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
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
                  <h4 class="card-title">Country Master</h4>
					<div class="forms-sample">
						<div class="form-group">
							<label for="txtcountryname">Country Name <code>*</code></label>
							<input type="text" class="form-control" id="txtcountryname" maxlength="20" placeholder="Country Name" value="<?php echo $country_name; ?>">
						</div>
						
						<div class="form-group">
							<label for="txtsymbol">Symbol <code>*</code></label>
							<input type="text" class="form-control" id="txtsymbol" maxlength="20" placeholder="Symbol" value="<?php echo $symbol; ?>">
						</div>
						
						<div class="form-group">
                      <label for="Document_Proof">Flag<code></code></label>

                      <input accept="image/*" type="file" class="form-control" onchange="showname();" id="Document_Proof" maxlength="100" placeholder="Flag" value="<?php echo $flag; ?>">
					  
					  <?php if($requesttype=="edit"){ 
								if($flag!=""){?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $AdminImagePathFlag.$flag;?>" target="_blank">View<a/>
									</div>
					  <?php } }?>
					</div>
						<div class="form-group">
							<label for="txtcurrency">Currency <code>*</code></label>
							<input type="text" class="form-control" id="txtcurrency" maxlength="10" placeholder="Currency" value="<?php echo $currency;?>">
						</div>
						<div class="form-group">
							<label for="txtcountrycode">Country Code <code>*</code></label>
							<input type="text" class="form-control" id="txtcountrycode" maxlength="10" placeholder="Country Code" value="<?php echo $country_code;?>">
						</div>
						
						<div class="form-group">
							<label for="txtPriority">Priority <code>*</code></label>
							<input type="number" class="form-control" id="txtPriority" maxlength="3" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" value="<?php echo $priority; ?>" placeholder="Priority" />
						</div>
						
						<div class="form-group">
							<label for="chkVisibility">Visibility</label><br>
							<label class="switch">
							<input type="checkbox" id="chkVisibility" <?php if ($visibility=="true") { echo "checked"; } ?> />
							<span class="slider round"></span>
							</label>
						</div>				        
							<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata()"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script>
var hdnIDimg="";	 
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
function showname() {
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	 
	 var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['pdf','png','jpg','jpeg']) == -1) 
	  {
		$('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
		  showAlert("warning","Image File Size is very big","warning");
	  
	  }
	  else
	  {
		form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);
	   
	   $.ajax({
		url:"uploadFlag.php",
		method:"POST",
		data: form_data2,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend:function(){
		
		},   
		success:function(data)
		
		{
			console.log(data);
			hdnIDimg=data;
			varimg=data;
		}
	   });
	  }
		  
		 
};
$("#txtPriority").blur(function()
{
	removeError(txtPriority);
	if ($("#txtPriority").val()!="")
	{
		if(!isNumber($("#txtPriority").val())){
			setError(txtPriority);
		}
		else
		{
			removeError(txtPriority);
		}
	}
});

function adddata(){
	if(!validateBlank($("#txtcountryname").val())){
		setErrorOnBlur("txtcountryname");
	}
	else if(!validateBlank($("#txtsymbol").val())){
		setErrorOnBlur("txtsymbol");
	} 
	
	else if(!validateBlank($("#txtcurrency").val())){
		setErrorOnBlur("txtcurrency");
	} 
	else if(!validateBlank($("#txtcountrycode").val())){
		setErrorOnBlur("txtcountrycode");
	} 
	else if(!validateBlank($("#txtPriority").val())){
		setErrorOnBlur("txtPriority");
	} 
	else if(!isNumber($("#txtPriority").val())){
		setError(txtPriority);
	}
	else{
		disableButton('#btnAddrecord','Processing...');

		var valcreated_by="<?php echo $created_by;?>";
	    var valcreated_on="<?php echo $cur_date;?>";
		var valcreated_ip="<?php echo $ip_address;?>";
		var valrequesttype="<?php echo $requesttype;?>";
		
		if(valrequesttype=="add"){
			var valquery = "insert into tw_country_master(country_name,symbol,flag,currency,country_code,priority,visibility,created_by,created_on,created_ip) values('"+$("#txtcountryname").val()+"','"+$("#txtsymbol").val()+"','"+hdnIDimg+"','"+$("#txtcurrency").val()+"','"+$("#txtcountrycode").val()+"','"+$("#txtPriority").val()+"','"+$('#chkVisibility').prop('checked')+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			var valquerycount = "select count(*) as cnt from  tw_country_master where country_name='"+$("#txtcountryname").val()+"'";
		}
		else{
			var valrequestid = "<?php echo $requestid;?>";
			if(hdnIDimg!=""){
				var valquery = "Update tw_country_master set country_name='"+$("#txtcountryname").val()+"',symbol='"+$("#txtsymbol").val()+"',flag='"+hdnIDimg+"',currency='"+$("#txtcurrency").val()+"',country_code='"+$("#txtcountrycode").val()+"',priority='"+$("#txtPriority").val()+"',visibility='"+$('#chkVisibility').prop('checked')+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
			}
			else{
				var valquery = "Update tw_country_master set country_name='"+$("#txtcountryname").val()+"',symbol='"+$("#txtsymbol").val()+"',currency='"+$("#txtcurrency").val()+"',country_code='"+$("#txtcountrycode").val()+"',priority='"+$("#txtPriority").val()+"',visibility='"+$('#chkVisibility').prop('checked')+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";

			}
		
			var valquerycount = "select count(*) as cnt from tw_country_master where country_name='"+$("#txtcountry_name").val()+"' and id!='"+valrequestid+"'";
            console.log(valquerycount);		
		}
		
			
		$.ajax({
			type:"POST",
			url:"apiCommonQuery.php",
			data:{valquery:valquery,valquerycount:valquerycount},
			success:function(response){
				if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
				}
				else
				{
					enableButton('#btnAddrecord','Update Record');
				}
				if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Record Added Successfully","success","pgCountryMaster.php");
					}
					else{
						showAlertRedirect("Success","Record Updated Successfully","success","pgCountryMaster.php");
					}
				}
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Record already exist","warning");
				}else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
				}
			}
		});   
	} 
}
</script>
</body>
</html>