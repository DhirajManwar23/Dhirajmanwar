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
	$ip_address= $commonfunction->getIPAddress();
	date_default_timezone_set("Asia/Kolkata");
	$cur_date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["employee_id"];
	
	$qry3 = "select company_id from tw_employee_registration where id = '".$_SESSION["employee_id"]."'";
	$retVal3 = $sign->SelectF($qry3,'company_id');
	$_SESSION["company_id"]=$retVal3;
	$type = $_REQUEST["type"];
	$po_id = $_REQUEST["po_id"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Material Outward</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
   <link rel="stylesheet" href="../assets/css/custom/style.css">
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
		<!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalViewReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Reason of Rejection</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body">
					<div class="form-group row">
						<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<div class="form-group row">	
								<div class="card-body" >
									<label class="col-sm-12">Rejection reason</label>
                                    <textarea class="form-control" id="txtViewReason" maxlength="5000"  placeholder="Reason of Rejection"></textarea>
								</div>
							</div>
									  
						</div>
					</div>
					
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalViewReason();">Close</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= --> <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="ViewInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Material Info</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body modal-body">
					
							<div class="form-group row">	
								<div class="card-body" id="MaterialInfo">
								
								
																	  
						</div>
					</div>
					
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalViewReason();">Close</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->   
      <!-- partial -->
	   <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <h4 class="card-title">Material Outward <?php echo $type; ?></h4>
          </div>
		  <br>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
				<table id="tableData" class="table">
				</table>
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script type='text/javascript'>
var valType = "<?php echo $type; ?>";
var valpo_id = "<?php echo $po_id; ?>";
var hdnIDimg="";
var hdnIDsize="";
var hdnIDDocType="";
$(document).ready(function(){
	showData(valType);
});
function showData(valType){
	if(valType=="In Process"){
			$.ajax({
				type:"POST",
				url:"apiGetViewMaterialOutwardInprocess.php",
				data:{valType:valType,po_id:valpo_id},
				success:function(response){
					console.log(response);
					$("#tableData").html(response);
					$('#ainfo').css({'cursor': 'pointer'});

					$('#tableData').DataTable({
						"responsive":true,
						"destroy":true,
						"bPaginate":true,
						"bLengthChange":true,
						"bFilter":true,
						"bSort":true,
						"bInfo":true,
						"retrieve": true,
						"bAutoWidth":false,
						"scrollXInner":true
		});
				}
			});
	}
	else if(valType=="Approved"){
		$.ajax({
				type:"POST",
				url:"apiGetViewMaterialOutwardApproved.php",
				data:{valType:valType,po_id:valpo_id},
				success:function(response){
					console.log(response);
					$("#tableData").html(response);
		
					$('#tableData').DataTable({
						"responsive":true,
						"destroy":true,
						"bPaginate":true,
						"bLengthChange":true,
						"bFilter":true,
						"bSort":true,
						"bInfo":true,
						"retrieve": true,
						"bAutoWidth":false,
						"scrollXInner":true
		});
				}
			});
	}
	else if(valType=="Rejected"){
		$.ajax({
				type:"POST",
				url:"apiGetViewMaterialOutwardRejected.php",
				data:{valType:valType,po_id:valpo_id},
				success:function(response){
					console.log(response);
					$("#tableData").html(response);
		
					$('#tableData').DataTable({
						"responsive":true,
						"destroy":true,
						"bPaginate":true,
						"bLengthChange":true,
						"bFilter":true,
						"bSort":true,
						"bInfo":true,
						"retrieve": true,
						"bAutoWidth":false,
						"scrollXInner":true
		});
				}
			});
	}
		
}

function editRecord(id){
	window.location.href = "pgMaterialOutwardFormEdit.php?type=edit&id="+id+"&po_id="+valpo_id;
}
function DocumentRecord(id){
	// window.location.href = "pgMaterialOutwardDocument.php?id="+id;
		window.location.href = "pgViewOutwardDocuments.php?id="+id+"&type="+valType;

}
function ViewReason(id){
	getReason(id);
}
function EwayRecordupload(id){
	window.location.href = "pgMaterialOutwardAttachDocument.php?type=Eway&id="+id;
	/* hdnIDDocType="Eway";
	showname(id); */
}
function InvoiceRecordupload(id){
	window.location.href = "pgMaterialOutwardAttachDocument.php?type=Invoice&id="+id;
	/* hdnIDDocType="Invoice";
	shownameInvoice(id); */
}
function WBSRecordupload(id){
	window.location.href = "pgMaterialOutwardAttachDocument.php?type=WBS&id="+id;
	/* hdnIDDocType="WBS";
	shownameWBS(id); */
}
function PhotosRecordupload(id){
	hdnIDDocType="Photo";
	shownamePhoto(id);
	//window.location.href = "pgMaterialOutwardAttachDocument.php?type=Photo&id="+id;
}
function DeleteRecordupload(modid){
	deleteRecordUpload(modid);
}
function DeleteRecordInvoice(modid){
	deleteRecordInvoice(modid);
}
function DeleteRecordWBS(modid){
	deleteRecordWBS(modid);
}

function getReason(id) {
	$.ajax({
	  type:"POST",
	  url:"apiInwardViewReject.php",
	  data:{id:id},
	  success:function(response){
		  console.log(response);
		  showModalViewReason();
		  $("#txtViewReason").val(response);
	  }
  }); 
}
function showModalViewReason()
{	
	jQuery.noConflict();
	$("#modalViewReason").modal("show");
	
}
function closeModalViewReason(){
	
  $("#modalViewReason").modal("hide");
  location.reload();
}
function deleteRecordInvoice(id){
	// var ans= confirm("are you sure to delete this record?");
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_tax_invoice";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:id,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showAlertRedirect("Success","Record Deleted Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
						}
						else{
							showAlert("Warning","Something Went Wrong. Please Try After Sometime","warning");
						}
						
					}
				});
		}
	});
}
function deleteRecordWBS(id){
	// var ans= confirm("are you sure to delete this record?");
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_material_outward_wbs";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:id,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showAlertRedirect("Success","Record Deleted Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
						}
						else{
							showAlert("Warning","Something Went Wrong. Please Try After Sometime","warning");
						}
						
					}
				});
		}
	});
}
function deleteRecord(id){
	// var ans= confirm("are you sure to delete this record?");
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_material_outward";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:id,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showData();
							showAlert("Success","Record Deleted Successfully","","success");
						}
						else{
							showAlert("Warning","Something Went Wrong. Please Try After Sometime","warning");
						}
						
					}
				});
		}
	});
}
function deleteRecordUpload(modid){
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_material_outward_documents";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:modid,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showAlertRedirect("Success","Record Deleted Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
						}
						else{
							showAlert("Warning","Something Went Wrong. Please Try After Sometime","warning");
						}
						
					}
				});
		}
	});
}
function checkFileExist(urlToFile) {
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();
     
    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
}

function getFilePhotos(id) {	
  document.getElementById("Document_ProofPhotos"+id).click();
}

function showname(id){
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	  var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg','pdf']) == -1) 
	  {
		$('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  
	  var path = "../assets/images/Documents/Employee/Outward"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		   alert("Image File Size is very big");
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

						   $.ajax({
							url:"uploadoutward.php",
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
								console.log(data);
								hdnIDimg=data;
								adddata(id);
							}
						   });
					}
					
				});
		} 
	  else
	  {
			form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

		   $.ajax({
			url:"uploadoutward.php",
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
				console.log(data);
				hdnIDimg=data;
				hdnIDsize=fsize;
				adddata(id);
			}
		   });
	  }
		  
		 
};

function shownameInvoice(id){
	  var name = document.getElementById('Document_ProofInvoice'); 
	  hdnIDimg = name.files.item(0).name;
	  var name = document.getElementById("Document_ProofInvoice").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg','pdf']) == -1) 
	  {
		$('#Document_ProofInvoice').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_ProofInvoice").files[0]);
	  var f = document.getElementById("Document_ProofInvoice").files[0];
	  var fsize = f.size||f.fileSize;
	  
	  var path = "../assets/images/Documents/Employee/Outward"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		   alert("Image File Size is very big");
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_ProofInvoice", document.getElementById('Document_ProofInvoice').files[0]);

						   $.ajax({
							url:"uploadoutwardInvoice.php",
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
								console.log(data);
								hdnIDimg=data;
								adddata(id);
							}
						   });
					}
					
				});
		} 
	  else
	  {
			form_data2.append("Document_ProofInvoice", document.getElementById('Document_ProofInvoice').files[0]);

		   $.ajax({
			url:"uploadoutwardInvoice.php",
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
				console.log(data);
				hdnIDimg=data;
				hdnIDsize=fsize;
				adddata(id);
			}
		   });
	  }
		  
		 
};

function shownameWBS(id){
	  var name = document.getElementById('Document_ProofWBS'); 
	  hdnIDimg = name.files.item(0).name;
	  var name = document.getElementById("Document_ProofWBS").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg','pdf']) == -1) 
	  {
		$('#Document_ProofWBS').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_ProofWBS").files[0]);
	  var f = document.getElementById("Document_ProofWBS").files[0];
	  var fsize = f.size||f.fileSize;
	  
	  var path = "../assets/images/Documents/Employee/Outward"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		   alert("Image File Size is very big");
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_ProofWBS", document.getElementById('Document_ProofWBS').files[0]);

						   $.ajax({
							url:"uploadoutwardWBS.php",
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
								console.log(data);
								hdnIDimg=data;
								adddata(id);
							}
						   });
					}
					
				});
		} 
	  else
	  {
			form_data2.append("Document_ProofWBS", document.getElementById('Document_ProofWBS').files[0]);

		   $.ajax({
			url:"uploadoutwardWBS.php",
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
				console.log(data);
				hdnIDimg=data;
				hdnIDsize=fsize;
				adddata(id);
			}
		   });
	  }
		  
		 
};
function adddata(id){
		var valcreated_by = '<?php echo $created_by; ?>';
		var valcur_date = '<?php echo $cur_date; ?>';
		var valip_address = '<?php echo $ip_address; ?>';
		var valquery = "insert into tw_material_outward_documents(outward_id,type,document,size,document_value,created_by,created_on,created_ip) values('"+id+"','"+hdnIDDocType+"','"+hdnIDimg+"','"+hdnIDsize+"','','"+valcreated_by+"','"+valcur_date+"','"+valip_address+"')";
		$.ajax({
		type:"POST",
		url:"apiCommonQuerySingle.php",
		data:{valquery:valquery},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Data Saved Successfully","success","pgMaterialOutward.php?type=In Process"+"&po_id="+valpo_id);
				
			}else{
				showAlertRedirect("Error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	});     
}
function shownamePhoto(id){
	  var name = document.getElementById('Document_ProofPhotos'+id); 
	  hdnIDimg = name.files.item(0).name;
	  var name = document.getElementById("Document_ProofPhotos"+id).files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg','pdf']) == -1) 
	  {
		$('#Document_ProofPhotos'+id).val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_ProofPhotos"+id).files[0]);
	  var f = document.getElementById("Document_ProofPhotos"+id).files[0];
	  var fsize = f.size||f.fileSize;
	  
	  var path = "../assets/images/Documents/Employee/Outward"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		   alert("Image File Size is very big");
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_ProofPhotos"+id, document.getElementById('Document_ProofPhotos'+id).files[0]);
							form_data2.append("id",id);
						    $.ajax({
							url:"uploadoutwardPhoto.php",
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
								console.log(data);
								hdnIDimg=data;
								adddata(id);
							}
						   });
					}
					
				});
		} 
	  else
	  {
			form_data2.append("Document_ProofPhotos"+id, document.getElementById('Document_ProofPhotos'+id).files[0]);
			form_data2.append("id",id);
		    $.ajax({
			url:"uploadoutwardPhoto.php",
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
				console.log(data);
				hdnIDimg=data;
				hdnIDsize=fsize;
				adddata(id);
			}
		   });
	  }
		  
		 
};
function ViewInfo(id){
	$.ajax({
			type:"POST",
			url:"apiGetPOinfo.php",
			data:{id:id},
			success:function(response){
				console.log(response);
				if(response!=""){
					$("#MaterialInfo").html(response);	
					showModalViewInfo();
				}
			}	
		}); 
}
function showModalViewInfo()
{	
	jQuery.noConflict();
	$("#ViewInfo").modal("show");
	
}
function closeModalViewReason() {
	
  $("#ViewInfo").modal("hide");
  location.reload();
}
</script>
</body>

</html>