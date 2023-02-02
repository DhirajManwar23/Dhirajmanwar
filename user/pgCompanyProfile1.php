<?php 
session_start();
	if(!isset($_SESSION["companyusername"])){
		header("Location:pgLogin.php");
	}
	include("commonFunctions.php");
	$commonfunction=new Common();
	$ip_address= $commonfunction->getIPAddress();
	
	$userid = $_SESSION["companyusername"];
	$twcompany_id = $_REQUEST["id"];

	date_default_timezone_set("Asia/Kolkata");
	$cur_date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["company_id"];
	
	// Include class definition
	require "function.php";
	$sign=new Signup();
	$qry = "SELECT cd.CompanyName, ctm.company_type,cd.AddressLine1,cd.AddressLine2 ,cd.Location ,cd.Pincode,cd.City ,cd.State,cd.Country,cd.Company_Logo,cd.Description FROM tw_company_details cd LEFT JOIN tw_company_type_master ctm ON cd.CompanyType = ctm.id WHERE cd.ID = '".$twcompany_id."' ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$CompanyName = $decodedJSON->response[0]->CompanyName;
	$CompanyType = $decodedJSON->response[1]->company_type; 
	$AddressLine1 = $decodedJSON->response[2]->AddressLine1;
	if($AddressLine1==""){
		$AddressLine1="---";
	}
	$AddressLine2 = $decodedJSON->response[3]->AddressLine2; 
	if($AddressLine2==""){
		$AddressLine2="---";
	}
	$Location = $decodedJSON->response[4]->Location; 
	if($Location==""){
		$Location="---";
	}
	$Pincode = $decodedJSON->response[5]->Pincode; 
	if($Pincode=="" || $Pincode==0){
		$Pincode="---";
	}
	$City = $decodedJSON->response[6]->City; 
	if($City==""){
		$City="---";
	}
	$State = $decodedJSON->response[7]->State; 
	if($State==""){
		$State="---";
	}
	$Country = $decodedJSON->response[8]->Country;
	if($Country==""){
		$Country="---";
	}
	$Company_Logo = $decodedJSON->response[9]->Company_Logo;
	$Description = $decodedJSON->response[10]->Description;
	$qry1 = "SELECT value,status,id,count(*) as cnt FROM tw_company_contact WHERE company_id = '".$twcompany_id."' and ( contact_field='1' or contact_field='3' )";
	$retVal1 = $sign->FunctionJSON($qry1);	

	
	 

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | My Profile</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/jquery-toast-plugin/jquery.toast.min.css">
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
	   <!-- ==============MODAL START ================= -->
	  <div class="modal fade" id="modalEditLogo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel"><i class="ti-lock"></i> Company Logo</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
				<span aria-hidden="true">×</span>
				</button>
				
				
			</div>
			<div class="modal-body">
				 <div class="input-group">
					 <div class="input-group">
						<div class="form-group row">
						  <div class="col-sm-12">
							<input type="file" name="Document_Proof" id="Document_Proof" onchange="showname();" />				
						  </div>
							
						</div>
                     
					  </div>
                    </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal();">Close</button>
				<button type="button" class="btn btn-primary" onclick="adddata();">Update</button>
			</div>
			</div>
		</div>
	</div>
	  <!-- ==============MODAL END ================= -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-4 col-md-4 grid-margin">
              <div class="card">
                <div class="card-body">
					<div style="text-align:center;">
						<input type="file" accept=".png, .jpg, .jpeg" style="display:none" id="Document_Proof" onchange="showname();"/> 
						<img src="../assets/images/Documents/Verification/<?php if($Company_Logo==""){echo "ic_company_logo.png"; }else{ echo $userid."/".$Company_Logo;}?>" class="img-lg rounded-circle mb-3" />
						<br><br>
						<h1 class="display-4"><?php echo $CompanyName; ?></h1>
						<h5><small class="text-muted"><?php echo $CompanyType; ?></small></h5>
						
				
					</div>
                </div>
              </div>
			  <br>
			  <div class="card">
                <div class="card-body">
					<!--<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
							 <i class="ti-email"></i>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
							<b>Primary Email</b><br>
							<p class="card-description"><?php echo $primarymail; ?><br>
								<?php// if($primarymailstatus=="Pending"){ ?>
								<small><a href="#">Verify</a></small>
								<?php// }else{?>
								<small><a href="pgAddContactDetails.php?type=edit&id=<?php //echo $id;?>&contactfield=">Change</a></small>
								<?php //}?>
							</p>
							 
						</div>
					</div>-->
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
							 <i class="ti-mobile"></i>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
							
							<?php
								$qry2 = "SELECT cc.value, cc.status,cc.id,cc.contact_field ,cfm.contact_type FROM tw_company_contact cc LEFT JOIN tw_contact_field_master cfm ON cc.contact_field = cfm.id WHERE cc.company_id = '".$twcompany_id."' and ( cc.contact_field='1' or cc.contact_field='3' )";
								$retVal2 = $sign->FunctionJSON($qry2);
								$decodedJSON2 = json_decode($retVal2);
								
								$qry3="Select count(*) as cnt from tw_company_contact where company_id = '".$twcompany_id."' and ( contact_field='1' or contact_field='3' )";
								$retVal3 = $sign->Select($qry3);
								
								$count = 0;
								$i = 1;
								$x=$retVal3;
								while($x>=$i){
										
										$value = $decodedJSON2->response[$count]->value;
										$count=$count+1;
										$status = $decodedJSON2->response[$count]->status;
										$count=$count+1;
										$id = $decodedJSON2->response[$count]->id;
										$count=$count+1;
										$contact_field  = $decodedJSON2->response[$count]->contact_field ;
										$count=$count+1;
										$contact_type  = $decodedJSON2->response[$count]->contact_type ;
										$count=$count+1;
										
									
							?>
							<b><?php echo $contact_type; ?></b><br>
							
							<p class="card-description"><?php echo $value; ?><br>
								<?php if($status=="Pending"){ ?>
								
								<?php }else{?>
								
								<?php }?>
								
							</p>
					<hr>
					 <?php 
								$i=$i+1;
						}
						if($i==2){?>
							<b>Primary Contact</b><br>
							
							<p class="card-description">---<br>
							</p>
						<?php }

					?>
					
					
						</div>
					</div>
                </div>
              </div>  
			  <br>
			  <div class="card">
                <div class="card-body">
					<h4 class="card-title">Team Members</h4>
					<?php
								$qry2 = "SELECT cc.employee_name, cc.employee_photo ,cfm.department_name,td.designation_value  FROM tw_employee_registration cc LEFT JOIN tw_department_master cfm ON cc.employee_department = cfm.id LEFT JOIN tw_designation_master td
ON 								cc.employee_designation = td.id WHERE cc.company_id = '".$twcompany_id."'";
								$retVal2 = $sign->FunctionJSON($qry2);
								$decodedJSON2 = json_decode($retVal2);
								
								$qry3="Select count(*) as cnt from tw_employee_registration where company_id = '".$twcompany_id."'";
								$retVal3 = $sign->Select($qry3);
								
								$count = 0;
								$i = 1;
								$x=$retVal3;
								while($x>=$i){
										
										$employee_name = $decodedJSON2->response[$count]->employee_name;
										$count=$count+1;
										$employee_photo = $decodedJSON2->response[$count]->employee_photo;
										$count=$count+1;
										$department_name = $decodedJSON2->response[$count]->department_name;
										$count=$count+1;
										$designation_value  = $decodedJSON2->response[$count]->designation_value ;
										$count=$count+1;
										
									
							?>
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3">
							<img src="../assets/images/Documents/Employee/Temp/<?php if($employee_photo==""){echo "ic_emp_logo.jpg"; }else{ echo $employee_photo;}?>" width="100%" />
						</div>
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 col-9">
							<b><?php echo $employee_name; ?></b><br>
							<p class="card-description"><?php echo $department_name." | ".$designation_value; ?></p>
						</div>
					</div>
					 <?php 
								$i=$i+1;
						}
						if($i==1){?>
							<small>No Data Found</small>
						<?php }?>
					
                </div>
              </div>
            </div>
			
            <div class="col-lg-8 col-md-8 grid-margin">
			<div class="card">
			<div class="card-body">
					<h4 class="card-title"><i class="ti-user"></i> Description</h4>
					<hr>
					<p><?php echo $Description; ?></p>
		 
                </div>
				</div>
				<br>
              <div class="card">
                <div class="card-body">
					<h4 class="card-title"><i class="ti-user"></i> Basic Info</h4>
					<hr>
					   <?php
						$qry5 = "SELECT ta.id,ta.location,ta.city,ta.pincode,ta.state,atm.address_type_value,atm.address_icon FROM tw_company_address ta LEFT JOIN tw_address_type_master atm ON ta.address_type  = atm.id WHERE company_id = '".$twcompany_id."'";
						$retVal5 = $sign->FunctionJSON($qry5);
						$decodedJSON5 = json_decode($retVal5);
						
						$qry5="Select count(*) as cnt from tw_company_address where company_id = '".$twcompany_id."'";
						$retVal5 = $sign->Select($qry5);
						
						$count = 0;
						$i = 1;
						$x=$retVal5;
						while($x>=$i){
								
								$id = $decodedJSON5->response[$count]->id;
								$count=$count+1;
								$location = $decodedJSON5->response[$count]->location;
								$count=$count+1;
								$city = $decodedJSON5->response[$count]->city;
								$count=$count+1;
								$pincode = $decodedJSON5->response[$count]->pincode;
								$count=$count+1;
								$state = $decodedJSON5->response[$count]->state;
								$count=$count+1;
								$address_type_value = $decodedJSON5->response[$count]->address_type_value;
								$count=$count+1;
								$address_icon = $decodedJSON5->response[$count]->address_icon;
								$count=$count+1;
							
							
							?>
					  <div class="row">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12">
								<strong><p><i class="<?php echo $address_icon;?>"></i></p></strong>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-11 col-xs-11 col-11">
							<strong><?php echo $address_type_value;?></strong><br>
							 <p><?php echo $location;?> | <?php echo $city; ?> | <?php echo $pincode;?> | <?php echo $state; ?></p>
						</div>
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-1">
						</div>
						
						
					</div>
					<hr>
					 <?php 
								$i=$i+1;
						}	
						if($i==1){?>
							<small>No Data Found</small>
						<?php }
					?>
                </div>
              </div>
			  <br>
			  <div class="card">
                <div class="card-body">
					<h4 class="card-title"><i class="ti-write"></i> Contact Details</h4>
					<hr>
					<?php
						$qry2 = "SELECT cc.id,cc.value,cc.status,ctm.contact_type,ctm.contact_icon  FROM tw_company_contact cc LEFT JOIN tw_contact_field_master ctm ON cc.contact_field = ctm.id WHERE company_id = '".$twcompany_id."' and cc.public_visible='true' and ( cc.contact_field!='1' and cc.contact_field!='3' )";
						$retVal2 = $sign->FunctionJSON($qry2);
						$decodedJSON2 = json_decode($retVal2);
						
						$qry3="Select count(*) as cnt from tw_company_contact where company_id = '".$twcompany_id."' and public_visible='true' and (contact_field!='1' and contact_field!='3' )";
						$retVal3 = $sign->Select($qry3);
						
						$count = 0;
						$i = 1;
						$x=$retVal3;
						while($x>=$i){
								
								$id = $decodedJSON2->response[$count]->id;
								$count=$count+1;
								$value = $decodedJSON2->response[$count]->value;
								$count=$count+1;
								$status = $decodedJSON2->response[$count]->status;
								$count=$count+1;
								$contact_type = $decodedJSON2->response[$count]->contact_type;
								$count=$count+1;
								$contact_icon = $decodedJSON2->response[$count]->contact_icon;
								$count=$count+1;
							
							
							?>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<strong><p><i class="<?php echo $contact_icon;?>"></i> <?php echo $contact_type;?></p></strong>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-10 col-xs-10 col-10">
								<?php echo $value;?><br>
								
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
								<a href="pgAddContactDetails.php"></a>
							</div>
							
							
						</div>
						<hr>
					
							<?php 
								$i=$i+1;
						}	
						if($i==1){?>
							<small>No Data Found</small>
						<?php }

					?>
					
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
  <script src="../assets/js/off-canvas.js"></script>
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
  <script src="../assets/js/todolist.js"></script>
  <!-- endinject -->
  <script src="../assets/css/jquery/jquery.min.js"></script>
  <script src="../assets/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>
    <script src="../assets/js/toastDemo.js"></script>
 <script type='text/javascript'>
var hdnIDimg="";
$('#OpenImgUpload').click(function(){ $('#Document_Proof').trigger('click'); });
function showModal()
{	
	jQuery.noConflict();
	$("#modalEditLogo").modal("show");
	//CopyPassword();
}

function closeModal() {
	
  $("#modalEditLogo").modal("hide");
 }
 function showname() {
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	 
	 var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg']) == -1) 
	  {
	   //alert("Invalid Image File");
		//$('#uploaded_image').html("<label class='text-warning'>Invalid Image File</label>");
		$('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
		   alert("Image File Size is very big");
	  /*  $("#swpanphotocheck").focus();
		$("#swpanphotocheck").show();

		$("#swpanphotocheck").delay(4000).fadeOut();
		//$('#uploaded_image').html("<label class='text-warning'>File too Big, please select a file less than 2mb</label>");
		$('#Bank_Account_Proof').val(""); */
	  }
	  else
	  {
		form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);
	   
	   $.ajax({
		url:"upload.php",
		method:"POST",
		data: form_data2,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend:function(){
			//$('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
		},   
		success:function(data)
		
		{
			//console.log(data);
			hdnIDimg=data;
			adddata();
		}
	   });
	  }
		  
		 
};
function adddata(){
	  
		var valcreated_by = "<?php echo $created_by;?>";
	    var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		
		if(hdnIDimg!=""){
			var valquery = "Update tw_company_details set Company_Logo = '"+hdnIDimg+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where ID = '"+valcreated_by+"' ";

		}
	 	$.ajax({
		type:"POST",
		url:"apiCompanylogo.php",
		data:{valquery:valquery},
		success:function(response){
			//console.log(response);
			if($.trim(response)=="Success"){
				
				alertBox('Data Updated Successfully');
				window.location.href="pgCompanyProfile.php";
			}
			else if($.trim(response)=="Exist"){
				alertBox('Value already exist');
				$("#txtValue").focus();
			}else{
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