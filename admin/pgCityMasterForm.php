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

$city_name = "";
$state_name = "";
$symbol = "";
$tier = "";
$visibility = "";
$state_id = "";
	
if($requesttype=="edit")
{
	$qry="select city_name,symbol,tier,visibility,state_id from tw_city_master where ID='".$requestid."'";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$city_name = $decodedJSON->response[0]->city_name; 
	$symbol = $decodedJSON->response[1]->symbol; 
	$tier = $decodedJSON->response[2]->tier; 
	$visibility = $decodedJSON->response[3]->visibility;
	$state_id = $decodedJSON->response[4]->state_id;
}
	
$qry1 = "select sm.id as id,concat(sm.state_name, ' [', cm.country_name, ']') as state_name from tw_state_master sm, tw_country_master cm where sm.country_id=cm.id and sm.visibility='true'";
$retVal1 = $sign->FunctionOption($qry1,$state_id,'state_name','id');
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |City Master</title>
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
                  <h4 class="card-title">CITY Master</h4>
					<div class="forms-sample">
					
					<div class="form-group">
							<label for="txtstatename">State Name <code>*</code></label>
							<select id="txtstatename" class="form-control" >
								<?php echo $retVal1;?>
							</select>
						</div>
						<div class="form-group">
							<label for="txtcityname">City Name <code>*</code></label>
							<input type="text" class="form-control" id="txtcityname" maxlength="30" placeholder="City Name" value="<?php echo $city_name; ?>">
						</div>
						<div class="form-group">
							<label for="txtsymbol">Symbol <code>*</code></label>
							<input type="text" class="form-control" id="txtsymbol" maxlength="30" placeholder="Symbol" value="<?php echo $symbol; ?>">
						</div>
						<div class="form-group" >
							<label for="seltier">Tier <code>*</code></label>
							<select name="tier" id="seltier" class="form-control" >
								<option value="Tier1" <?php  if($tier=="tier 1"){ echo "selected";} ?>>Tier1</option>
								<option value="Tier2" <?php  if($tier=="tier 2"){ echo "selected";} ?>>Tier2</option>
								<option value="Tier3"  <?php  if($tier=="tier 4"){ echo "selected";} ?>>Tier3</option>
							</select>
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

function adddata(){
	if(!validateBlank($("#txtcityname").val())){
		setErrorOnBlur("txtcityname");
	}
	else if(!validateBlank($("#txtstatename").val())){
		setErrorOnBlur("txtstatename");
	}
	else if(!validateBlank($("#txtsymbol").val())){
		setErrorOnBlur("txtsymbol");
	} 
	else if(!validateBlank($("#seltier").val())){
		setErrorOnBlur("seltier");
	} 	
	else{
		disableButton('#btnAddrecord','Processing...');

		var valcreated_by="<?php echo $created_by;?>";
	    var valcreated_on="<?php echo $cur_date;?>";
		var valcreated_ip="<?php echo $ip_address;?>";
		var valrequesttype="<?php echo $requesttype;?>";
		
		if(valrequesttype=="add"){
			var valquery = "insert into tw_city_master(city_name,state_id,symbol,tier,visibility,created_by,created_on,created_ip) values('"+$("#txtcityname").val()+"','"+$("#txtstatename").val()+"','"+$("#txtsymbol").val()+"','"+$("#seltier").val()+"','"+$('#chkVisibility').prop('checked')+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			var valquerycount = "select count(*) as cnt from tw_city_master where city_name='"+$("#txtcityname").val()+"' and state_id='"+$("#txtstatename").val()+"'";
		}
		else{
			var valrequestid = "<?php echo $requestid;?>";
			var valquery = "Update tw_city_master set city_name='"+$("#txtcityname").val()+"',state_id='"+$("#txtstatename").val()+"',symbol='"+$("#txtsymbol").val()+"',tier='"+$("#seltier").val()+"',visibility='"+$('#chkVisibility').prop('checked')+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
			var valquerycount = "select count(*) as cnt from tw_city_master where city_name='"+$("#txtcityname").val()+"' and ID!='"+valrequestid+"'";
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
						showAlertRedirect("Success","Record Added Successfully","success","pgCityMaster.php");
					}
					else{
						showAlertRedirect("Success","Record Updated Successfully","success","pgCityMaster.php");
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