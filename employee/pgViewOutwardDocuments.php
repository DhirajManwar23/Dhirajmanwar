<?php 
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
    $outward_id=$_REQUEST["id"];
    $type=$_REQUEST["type"];
	
	$qryi="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_tax_invoice WHERE outward_id='".$outward_id."'
		)tbl";
	
	$retVali = $sign->SelectF($qryi,"sum(tbl.EachTableCount)");
	$qryii="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_outward_documents WHERE type='Eway' and outward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_outward_eway WHERE outward_id='".$outward_id."'
		)tbl";
	 $retValii = $sign->SelectF($qryii,"sum(tbl.EachTableCount)");
	$qryiii="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_outward_documents WHERE type='WBS' and outward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_outward_wbs WHERE outward_id='".$outward_id."'
		)tbl";
	$retValiii = $sign->SelectF($qryiii,"sum(tbl.EachTableCount)");
	$qryiv="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_inward_documents WHERE type='GRN' and inward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_inward_grn WHERE inward_id='".$outward_id."'
		)tbl";
	$retValiv = $sign->SelectF($qryiv,"sum(tbl.EachTableCount)");
	$qryv="select sum(tbl.EachTableCount)from(
		select count(*) as EachTableCount from tw_material_inward_documents WHERE type='QC' and inward_id='".$outward_id."'
		UNION ALL
		select count(*) as EachTableCount from tw_material_inward_qc WHERE inward_id='".$outward_id."'
		)tbl";
		$retValv = $sign->SelectF($qryv,"sum(tbl.EachTableCount)");
	$qryvi="select count(*) as cnt from tw_material_outward_documents WHERE type='Photo' and outward_id='".$outward_id."'";
	$retValvi = $sign->Select($qryvi);
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Material Outward Documents </title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
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
					<h4 class="card-title"><i class="ti-dashboard menu-icon"></i> Eway (<?php echo $retValii; ?>)<?php if($type=="In Process"){ ?><div class="float-right"><a href="pgMaterialOutwardAttachDocument.php?type=Eway&id=<?php echo $outward_id; ?>"><small><i class="ti-clip"></i> Attach</small></a> | <a style="pointer-events: none;color:#6c757d" href="pgMaterialOutwardEway.php?id=<?php echo $outward_id; ?>"><small><i class="ti-plus"></i> Generate</small></a></div><?php } ?></h4>
					<?php
					
						$query = "select id,document from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Eway' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Eway' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$document = $decodedJSON2->response[$count]->document;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="../assets/images/Documents/Employee/Outward/<?php echo$document; ?>" target="_blank">Download Eway<a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					<?php
					
						$query = "select id from tw_material_outward_eway where outward_id = '".$outward_id."' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_outward_eway where outward_id = '".$outward_id."' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="pgeWayBill.php?id=<?php echo$id; ?>&outward_id=<?php echo$outward_id; ?>" target="_blank">Eway Bill Number : <?php echo $id; ?><a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
             <div class="card">
				<div class="card-body">
					<h4 class="card-title"><i class="ti-dashboard menu-icon"></i> Invoice (<?php echo $retVali; ?>)<?php if($type=="In Process"){ ?> <div class="float-right"><a href="pgMaterialOutwardAttachDocument.php?type=Invoice&id=<?php echo $outward_id; ?>"><small><i class="ti-clip"></i> Attach</small></a> | <a href="pgTaxInvoice.php?id=<?php echo $outward_id; ?>" ><small><i class="ti-plus"></i> Generate</small></a></div><?php } ?></h4>

					<?php
					
						$query = "select id,document from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Invoice' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Invoice' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$document = $decodedJSON2->response[$count]->document;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="../assets/images/Documents/Employee/Outward/<?php echo$document; ?>" target="_blank">Download Invoice<a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					<?php
					
						$query = "select id,invoice_number from tw_tax_invoice where outward_id = '".$outward_id."' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_tax_invoice where outward_id = '".$outward_id."' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$invoice_number = $decodedJSON2->response[$count]->invoice_number;
							$count=$count+1;	
							$i=$i+1;
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="pgTaxInvoiceDocuments.php?id=<?php echo$id; ?>&voutward_id=<?php echo $outward_id; ?>" target="_blank">Invoice Number : <?php echo $invoice_number; ?><a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					
					
					
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
             <div class="card">
				<div class="card-body">
					<h4 class="card-title"><i class="ti-dashboard menu-icon"></i> Weigh Bridge Slip (<?php echo $retValiii; ?>)<?php if($type=="In Process"){ ?><div class="float-right"><a href="pgMaterialOutwardAttachDocument.php?type=WBS&id=<?php echo $outward_id; ?>"><small><i class="ti-clip"></i> Attach</small></a> | <a href="pgMaterialOutwardwbs.php?id=<?php echo $outward_id; ?>" ><small><i class="ti-plus"></i> Generate</small></a></div><?php } ?></h4>
					<?php
					
						$query = "select id,document from tw_material_outward_documents where outward_id = '".$outward_id."' and type='WBS' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='WBS' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$document = $decodedJSON2->response[$count]->document;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="../assets/images/Documents/Employee/Outward/<?php echo$document; ?>" target="_blank">Download WBS<a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					<?php
					
						$query = "select id from tw_material_outward_wbs where outward_id = '".$outward_id."' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_outward_wbs where outward_id = '".$outward_id."' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="pgWaybillslip.php?id=<?php echo$id; ?>&outward_id=<?php echo$outward_id; ?>" target="_blank">SR.No : <?php echo $id; ?><a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					
                </div>
              </div>
            </div>
          </div>
		   <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
             <div class="card">
				<div class="card-body">
					<h4 class="card-title"><i class="ti-dashboard menu-icon"></i> Photos (<?php echo $retValvi; ?>)<?php if($type=="In Process"){ ?><div class="float-right"><a href="pgMaterialOutwardAttachDocument.php?type=Photo&id=<?php echo $outward_id; ?>"><small><i class="ti-clip"></i> Attach</small></a></div><?php } ?></h4>
					<?php
					
						$query = "select id,document from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Photo' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Photo' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$document = $decodedJSON2->response[$count]->document;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="../assets/images/Documents/Employee/Outward/<?php echo$document; ?>" target="_blank">Download Photos<a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					
                </div>
              </div>
            </div>
          </div>
		  <?php //if($type=="Approved"){ ?>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
             <div class="card">
				<div class="card-body">
					<h4 class="card-title"><i class="ti-dashboard menu-icon"></i> Goods Received Note (<?php echo $retValiv; ?>)</h4>

					<?php
					
						$query = "select id,document from tw_material_inward_documents where inward_id = '".$outward_id."' and type='GRN' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$outward_id."' and type='GRN' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$document = $decodedJSON2->response[$count]->document;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="../assets/images/Documents/Employee/Outward/<?php echo$document; ?>" target="_blank">Download GRN<a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					<?php
					
						$query = "select id from tw_material_inward_grn where inward_id = '".$outward_id."' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_inward_grn where inward_id = '".$outward_id."' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						  <div class="wrapper text-center">
							<p class="card-description"><a href="pgGRN.php?id=<?php echo$id; ?>&inward_id=<?php echo$outward_id; ?>" target="_blank">GRN Number : <?php echo $id; ?><a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
             <div class="card">
				<div class="card-body">

					<h4 class="card-title"><i class="ti-dashboard menu-icon"></i> Quality Check (<?php echo $retValv; ?>)</h4>
					<?php
					
						$query = "select id,document from tw_material_inward_documents where inward_id = '".$outward_id."' and type='QC' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$outward_id."' and type='QC' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$document = $decodedJSON2->response[$count]->document;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						
						  <div class="wrapper text-center">
							<p class="card-description"><a href="../assets/images/Documents/Employee/Outward/<?php echo$document; ?>" target="_blank">Download QC<a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php } ?>
					<?php
					
						$query = "select id from tw_material_inward_qc where inward_id = '".$outward_id."' Order by id Desc";
						$retVal = $sign->FunctionJSON($query);
						
						$qry1="Select count(*) as cnt from tw_material_inward_qc where inward_id = '".$outward_id."' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
						$decodedJSON2 = json_decode($retVal);
						$count = 0;
						$i = 1;
						$x=$retVal1;
						$it=1;
						while($x>=$i){
		
							$id = $decodedJSON2->response[$count]->id;
							$count=$count+1;
							$i=$i+1;
												
					?>
					<div class="row">
					  <div class="col-md-4 col-sm-6 d-flex justify-content-center">
						<div class="card-body">
						
						  <div class="wrapper text-center">
							<p class="card-description"><a href="pgQCDocument.php?id=<?php echo$id; ?>&inward_id=<?php echo$outward_id; ?>" target="_blank">Download QC<a></p>
						  </div>
						</div>
					  </div>
					</div>
					<?php// } ?>
					
                </div>
              </div>
            </div>
          </div>
		  <?php } ?>
		  
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
  <script src="../assets/css/jquery/jquery.min.js"></script>
  <script src="../assets/js/custom/sweetAlert.js"></script>
  <script src="../assets/js/custom/sweetAlert2.min.js"></script>

   <script type='text/javascript'>

$(document).ready(function(){
	
});

</script>
</body>

</html>