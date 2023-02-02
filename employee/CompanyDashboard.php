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
    <body>
       <section>
           
        <div class="container-fluid">
			<div class="col-md-12 stretch-card ">
				<div class="card position-relative">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 col-6" >
								<img src="Images/Logo.png" class="img-fluid" alt="responsive image">
							</div>
							
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
							
								<h1 style="font-size:2vw; color: #2eb32e;">Bisleri International Private Limited</h1>
									<span class="states">
										Jammu & Kashmir, Andhra Pradesh, Arunachal Pradesh, 
										Assam, Chhattisgarh, Goa, Kerala, Maharashtra, 
										Nagaland, Sikkim, Tripura, Uttarakhand
									</span>
							
								<div class="line" style="widht:12px"></div>
			
									<span class="category" style="color: #2eb32e;">
										Category name
									</span>
							</div>
							
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
								
									<div>
										<img src="Images/Top_Images.png"  class="img-fluid float-right">
									</div>
								
							</div>	
							
							</div>
							
							<div class="row tw-float-right">	
								<div class="justify-content-end d-flex" >
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 grid-margin stretch-card download-upload-card">
										<div class="card">
											<div class="card-body ">
												<div class="row  download">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6" >
														<h6 class="download_text">Download Template</h6>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 col-3 downloadpng" >
														<img src="Images/Download_button.png" >
													</div>
													<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 col-3" >
														 <div class="circle"><img src="Images/download.png" class="picload"></div> 
													</div>
												</div>
										
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 grid-margin stretch-card ">
										<div class="card ">
											<div class="card-body ">
												<div class="row upload">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6" >
														<h6 class="download_text">Upload CSV</h6>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 col-3 uploadpng" >
														<img src="Images/upload_button.png">
													</div>
													<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 col-3" >
														 <div class="circle"><img src="Images/upload.png"  class="picload"></div> 
													</div>
												</div>
											</div>
										</div>	
									</div>
								</div> 
							</div> 
					</div> 
				</div> 
            </div> 
			
			
			
			
			<div class="col-md-12 stretch-card">
				<div class="card company-dashboard-inclusion-card  position-relative">
				<div class="card-body ">
              
                    <div class=" row col d-flex justify-content-center">
						<div class="col-lg-2 col-md-2 col-sm-12 col-12 stretch-card">
						<div class="card company-dashboard-card">
							<div class="card-body">
                            
                                <div class="pending">
                                    <img src="Images/Veiw_pending.png">
                                    <span>View Pending</span>
                                </div>
								
							</div>
						</div>	
					</div>	

					<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
						<div class="card company-dashboard-card">
							<div class="card-body">
                                <div class="nav_box">
                                   <img src="Images/Veiw_awaiting.png">
                                   <span>View Awaiting </span>
                                </div>
							</div>
						</div>	
					</div>		
								
					<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
						<div class="card company-dashboard-card">
							<div class="card-body">
                                <div class="nav_box">
                                    <img src="Images/View_fullfilled.png">
                                    <span>View Fulfilled </span>
                                </div>
							    </div>
							</div>
						</div>	
							
								
					<div class="col-lg-2 col-md-2 col-sm-12 col-12 stretch-card">
						<div class="card company-dashboard-card">
							<div class="card-body recycle-card">			
                                <div class="nav_box">
                                    <img src="Images/Veiw_queries.png">
                                    <span>View Queires </span>
                                </div>
								 </div>
							</div>
						</div>	
						<div class="col-lg-2 col-md-2 col-sm-12 col-12 stretch-card">
						<div class="card company-dashboard-card">
							<div class="card-body recycle-card">	
                                <div class="nav_box">
                                    <img src="Images/Recyclebin.png">
                                    <span>Recycle</span>
                                </div>
                            </div> 
							</div>
						</div>
								
                        
                     
					 
					 
                    </div>
					
					</div>
                    </div>
                </div>	
					
					
				<br>	
				
				
	<div class="col-md-12 stretch-card">
				<div class="card  position-relative">
				<div class="card-body ">			
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
						   
                    </div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2 filter tw-float-right">
						<div class="box"><img src="Images/Filter.png"></div>
						<div class="filter_text">Filter</div>   
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 md-12 sm-12 xs-12s flex-row titlelist" >
                        <span class="list_text">#</span>
                        <span class="list_text">Aggregator Name</span>
                        <span class="list_text">GST</span>
                        <span class="list_text">Material Name</spans>
                    </div><br>

                    <div class="col-lg-12 md-12 sm-12 xs-12s flex-row list1">
                        
                    </div>  
                </div>

				</div>  
                </div>  
				</div>            

                   <!--<ul class="col-lg-10 list-group list-group-horizontal position-relative overflow-auto w-75 slick-initialized slick-slider slick-dotted r1">
                            <li class="list-group-item m1">
                                <img src="Images/Veiw_pending.png">
                                <br>
                                View Pending
                            </li>
            
                            <li class="list-group-item m2">
                                <img src="Images/Veiw_awaiting.png">
                                <br>
                                View Awaiting
                            </li>

                            <li class="list-group-item m2">
                                <img src="Images/View_fullfilled.png">
                                <br>
                                View Fulfilled
                            </li>

                            <li class="list-group-item m2">
                                <img src="Images/Veiw_queries.png">
                                <br>
                                View Queires
                            </li>

                            <li class="list-group-item m2">
                                <img src="Images/Recyclebin.png">
                                <br>
                                Recycle
                            </li>
                        </ul>-->



                <!--<div class="row">
                    <ul class="row PO">
                        <li class="polist">
                            #
                        </li>
                        <li class="polist">
                            Aggregator Name
                        </li>
                        <li class="polist">
                            GST
                        </li>
                        <li class="polist">
                            Category Name
                        </li>
                        <li class="polist">
                            Material Name
                        </li>
                    </ul>

                </div>
                <div class="row">
                    <div class="col-lg-12 md-12 sm-12 xs-12s flex-row" >
                        <span>abc</span>
                    </div><br>

                    <div class="col-lg-12 md-12 sm-12 xs-12s flex-row list1">
                        <span>shubham</span>
                    </div>  
                </div>-->
           
            </div>
        </section>        
 </body>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/script.js"></script>

</head>
<body>
    
</body>
</html>