<?php

include_once "function.php";
include_once "commonFunctions.php";
$signNav=new Signup();
$commonfunction=new Common();

$employee_id=$_SESSION["employee_id"];
$company_id = $_SESSION["company_id"];
$responsearray=array();

$query="SELECT er.employee_photo,el.forced_password_change FROM (tw_employee_registration er INNER JOIN tw_employee_login el ON er.id=el.employee_id) WHERE er.id='".$employee_id."'";
$retVal = $sign->FunctionJSON($query);
$decodedJSON = json_decode($retVal);
$employee_photo = $decodedJSON->response[0]->employee_photo; 
$forced_password_change = $decodedJSON->response[1]->forced_password_change; 
 
$settingValueEmployeeImagePathVerification= $commonfunction->getSettingValue("EmployeeImagePathVerification");
$settingValuePrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueEmployeeImagePathOther= $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueEmployeeImage= $commonfunction->getSettingValue("Employee Image");
$settingValueEmployeePanel=$commonfunction->getSettingValue("EmployeePanel");

$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$MainLogo=$commonfunction->getCommonDataValue("MainLogo");
$MiniLogo=$commonfunction->getCommonDataValue("MiniLogo");
$Partner=$commonfunction->getCommonDataValue("Partner");


//----karuna start
$qryMenu="select id,module_name,module_icon,url from tw_module_master where id in (select rights_id from tw_company_rights_management where rights_type='Module' and company_id='".$company_id."' and role_id=(select employee_role from tw_employee_registration where id='".$employee_id."')) and visibility='true' and panel='".$settingValueEmployeePanel."' order by priority";
$qryMenuCnt="select count(*) as cnt from tw_module_master where id in (select rights_id from tw_company_rights_management where rights_type='Module' and company_id='".$company_id."' and role_id=(select employee_role from tw_employee_registration where id='".$employee_id."')) and visibility='true' and panel='".$settingValueEmployeePanel."'";


$valModules = $signNav->FunctionJSON($qryMenu);

$retModulesCount = $signNav->Select($qryMenuCnt);

$decodedJSON = json_decode($valModules);
$count = 0;
$i = 1;
$x=$retModulesCount;
$menu="";
while($x>=$i){
	$module_id = $decodedJSON->response[$count]->id;
	$count=$count+1;
	$module_name = $decodedJSON->response[$count]->module_name;
	$count=$count+1;
	$module_icon = $decodedJSON->response[$count]->module_icon;
	$count=$count+1;
	$url = $decodedJSON->response[$count]->url;
	$count=$count+1;
	
	array_push($responsearray,$url);

	$i=$i+1;
}
$DesignationQry="SELECT employee_designation FROM `tw_employee_registration` WHERE id='".$employee_id."'";
$Designation = $sign->SelectF($DesignationQry,'employee_designation'); 
$_SESSION["responsearray"] = $responsearray;
//----karuna end

//print_r($_SESSION["responsearray"]);
$pageName = basename($_SERVER['PHP_SELF']);
/*  if (in_array($pageName, $_SESSION["responsearray"])) {
   // echo "Value exists";
} else {
    //echo "Value doesn't exists";
	header("Location:pgError.php");

 } */
 $qryEmail = "select value from tw_employee_contact where employee_id='".$employee_id."' and contact_field='".$settingValuePrimaryEmail."'";
 $username = $signNav->SelectF($qryEmail,"value");
?>

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
	<a class="navbar-brand brand-logo mr-5" href="pgEmployeeDashboard.php"><img src="<?php echo $CommonDataValueCommonImagePath.$MainLogo;?>" class="mr-2" alt="logo"/></a>
	<a class="navbar-brand brand-logo-mini" href="pgEmployeeDashboard.php"><img src="<?php echo $CommonDataValueCommonImagePath.$MiniLogo;?>" alt="logo"/></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
	<button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
	  <span class="icon-menu"></span>
	</button>
	<!--<ul class="navbar-nav mr-lg-2">
	  <li class="nav-item nav-search d-none d-lg-block">
		<div class="input-group">
		  <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
			<span class="input-group-text" id="search">
			  <i class="icon-search"></i>
			</span>
		  </div>
		  <input type="text" autocomplete="off" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search" >
		</div>
	  </li>
	</ul>-->
	<ul class="navbar-nav navbar-nav-right">
	  <li class="nav-item dropdown">
		<!--<a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
		  <i class="icon-bell mx-0"></i>
		  <span class="count"></span>
		</a>-->
		<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
		  <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
		  <a class="dropdown-item preview-item">
			<div class="preview-thumbnail">
			  <div class="preview-icon bg-success">
				<i class="ti-info-alt mx-0"></i>
			  </div>
			</div>
			<div class="preview-item-content">
			  <h6 class="preview-subject font-weight-normal">New company approved</h6>
			  <p class="font-weight-light small-text mb-0 text-muted">
				Just now
			  </p>
			</div>
		  </a>
		  <a class="dropdown-item preview-item">
			<div class="preview-thumbnail">
			  <div class="preview-icon bg-warning">
				<i class="ti-settings mx-0"></i>
			  </div>
			</div>
			<div class="preview-item-content">
			  <h6 class="preview-subject font-weight-normal">Demo message</h6>
			  <p class="font-weight-light small-text mb-0 text-muted">
				System message
			  </p>
			</div>
		  </a>
		  <a class="dropdown-item preview-item">
			<div class="preview-thumbnail">
			  <div class="preview-icon bg-info">
				<i class="ti-user mx-0"></i>
			  </div>
			</div>
			<div class="preview-item-content">
			  <h6 class="preview-subject font-weight-normal">New company registration</h6>
			  <p class="font-weight-light small-text mb-0 text-muted">
				2 days ago
			  </p>
			</div>
		  </a>
		</div>
	  </li>
	  <li class="nav-item nav-profile dropdown">
		<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
		<?php if($Designation==$Partner ) { ?>
		<img loading="lazy" src="../assets/images/tw/wnm_logo.jpg" alt="profile"/>
		</a>
		<?php } else{ ?>		
		  <img loading="lazy" src="<?php if($employee_photo==""){echo $settingValueEmployeeImagePathOther.$settingValueEmployeeImage; }else{ echo $settingValueEmployeeImagePathVerification.$username."/".$employee_photo;}?>" alt="profile"/>
		</a>
		<?php } ?>
		<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
		  
		  <?php if($forced_password_change=="true")
		  {?>
			<div class="dropdown-item no-drop-cursor" href="#">
				<i class="ti-user text-primary"></i>
				My Profile
			</div>
		  <?php } 
		  else {?> 
             <a class="dropdown-item" href="pgEmployeeProfile.php">
				<i class="ti-user text-primary"></i>
				My Profile
			</a>			 
		  <?php }?>
			
		  <hr>
		  <a class="dropdown-item" href="pgChangePassword.php">
			<i class="ti-lock text-primary"></i>
			Change Password
		  </a>
		  <a class="dropdown-item" href="pgLogOut.php">
			<i class="ti-power-off text-primary"></i>
			Logout
		  </a>
		</div>
	  </li>
	  <!--<li class="nav-item nav-settings d-none d-lg-flex">
		<a class="nav-link" href="#">
		  <i class="icon-ellipsis"></i>
		</a>
	  </li>-->
	</ul>
	<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
	  <span class="icon-menu"></span>
	</button>
  </div>
</nav>