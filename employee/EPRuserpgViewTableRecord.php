<?php
session_start();
if(!isset($_SESSION["employeeusername"])){
	header("Location:pgLogin.php");
}
include_once "function.php";	
$sign=new Signup();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/slick-theme.css">
    <link rel="stylesheet" href="css/style.css">
	
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
<link rel="stylesheet" href="../assets/css/custom/style.css" />
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

    <div class="Epr-container">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 col-12 topleft">
                <div class="row propic">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <img src="images/profile.png">
                    </div>    
                </div>
				
				<div class="forms-sample">
				   <div class="row">
						<div class="row rowgap form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 ">
								<label for="txtSupplier">GST No. :<code>*</code></label>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 ">
							 <input type="text" class="form-control" id="textGSTNumber" placeholder="GST Number"  />
							</div>
						</div>
						<div class="row rowgap form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 ">
							<label for="txtSupplier">Category Name :<code>*</code></label> 
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 ">
							 <input type="text" class="form-control" id="textCategory" placeholder="Category Name" />
							</div>
						</div>
						<div class="row rowgap form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
							<label for="txtSupplier">Material Name :<code>*</code></label>    
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
							<input type="text" class="form-control" id="textMaterial" placeholder="Material Name" />
							</div>
						</div>
						<div class="row rowgap form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
							<label for="txtSupplier">Type Of Submission :<code>*</code></label>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 ">
							<input type="text" class="form-control" id="textSubmission" placeholder="Type Of Submission" />
							</div>
						</div>
					</div>
				</div>
			</div>
		
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 col-12 topright">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-5">
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<label for="txtSupplier">Purchase Invoice Number :<code>*</code></label>   
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<input type="text" class="form-control" id="textSInvoiceNumber" placeholder="Invoice Number" />
							</div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<label for="txtSupplier">Purchase Invoice Date :<code>*</code></label>     
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
							<input type="text" class="form-control" id="textSInvoiceNumber" placeholder="Purchase Invoice Date" />
							</div>
                        </div>
                    </div>
                    <div class="midvline"></div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-5">
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<label for="txtSupplier">Dispatched State :<code>*</code></label>     
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
							<input type="text" class="form-control" id="textDispatchedState" placeholder="Dispatched State" />
							</div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<label for="txtSupplier">Dispatched Place :<code>*</code></label>     
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
							<input type="text" class="form-control" id="textDispatchedPlace" placeholder="Dispatched Place" />
							</div>
                        </div>
                    </div>
                </div>
                <div class="midline"></div>
                <div class="row ">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-6">
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<label for="txtSupplier">GRN Number :<code>*</code></label> 
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
							<input type="text" class="form-control" id="textGRNNumber" placeholder="GRN Number" />
							</div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                            
								<label for="txtSupplier">GRN File :<code>*</code></label> 
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
							
							
							<input type="file" class="form-control file-upload-info"  id="textGRNFile" placeholder="GRN Number" />
							</div>
                        </div>
                    </div>
                    <div class="midvline"></div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-5">
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<label for="txtSupplier">Invoice Quantity :<code>*</code></label> 
                                
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<input type="text" class="form-control"  id="textInvoiceQuantity" placeholder="Invoice Quantity" />
							</div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                               
								<label for="txtSupplier">Invoice File :<code>*</code></label> 
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
							<input type="file" class="form-control file-upload-info"  id="textInvoiceFile" placeholder="Invoice File" />
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-3 col-md-3col-sm-3col-xs-3col-lg-3 bottomleft">
                <div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<label for="txtSupplier">Plant Quantity :<code>*</code></label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<input type="text" class="form-control"  id="textPlantQuantity" placeholder="Plant Quantity" />
					</div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
						<label for="txtSupplier">Plant WBS No :<code>*</code></label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
					<input type="text" class="form-control"  id="textPlantWBSNo" placeholder="Plant WBS No" />
					</div>
                </div> 
				<div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<label for="txtSupplier">Plant WBS Date :<code>*</code></label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
					<input type="date" class="form-control"  id="textPlantWBSDate" placeholder="Plant WBS Date" />
					</div>
                </div> <div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<label for="txtSupplier">Plant WBS File :<code>*</code></label> 
                       
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<input type="file" class="form-control file-upload-info"  id="textWBSFile" placeholder="WBS File" />
					</div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
                <div class="row bottomcenter">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                                <div class="row form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
										<label for="txtSupplier">Aggregator WBS No :<code>*</code></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
									<input type="text" class="form-control"  id="textAggregatorWBSNo" placeholder="Aggregator WBS No" />
									</div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                                <div class="row form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
                                        
										<label for="txtSupplier">Aggregator WBS File :<code>*</code></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
										<input type="file" class="form-control file-upload-info"  id="textAggregatorWBSFile" placeholder="Aggregator WBS File" />
									</div>
                                </div>
                            </div>  
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                               
								<label for="txtSupplier">Aggregator WBS Date :<code>*</code></label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
								<input type="date" class="form-control"  id="textAggregatorWBSDate" placeholder="Aggregator WBS Date" />
								</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row bottomcenter">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                                <div class="row form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 "> 
										<label for="txtSupplier">LR Number :<code>*</code></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
									<input type="text" class="form-control"  id="textLRNumber" placeholder="LR Number" />
									</div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                                <div class="row form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
                                       
										<label for="txtSupplier">LR File :<code>*</code></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
									<input type="file" class="form-control file-upload-info"  id="textLRFile" placeholder="LR File" />
									</div>
                                </div>
                            </div>  
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<label for="txtSupplier">LR Date :<code>*</code></label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
								<input type="date" class="form-control"  id="textLRDate" placeholder="LR Date" />
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12 bottomright">
                <div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                     
						<label for="txtSupplier">Eway Bill No :<code>*</code></label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
					<input type="text" class="form-control"  id="textEwayBillNumber" placeholder="Eway Bill Number" />
					</div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                       
						<label for="txtSupplier">Eway File :<code>*</code></label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
					<input type="file" class="form-control file-upload-info"  id="textEwayFile" placeholder="Eway File" />
					</div>
                </div>
                <div class="halfline"></div>
                <div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                        
						<label for="txtSupplier">Vehicle No :<code>*</code></label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
					<input type="text" class="form-control"  id="textVehicle Number" placeholder="Vehicle Number" />
					</div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                        
						<label for="txtSupplier">Vehicle Photo :<code>*</code></label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
					<input type="file" class="form-control file-upload-info"  id="textVehiclePhoto" placeholder="Vehicle Photo" />
					</div>
                </div>
            </div>
        </div>
            
    </div>
    </div>
    
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
</body>
</html>