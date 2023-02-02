<?php 
include_once "commonFunctions.php";
$commonfunction=new Common();
$userid = $_SESSION["companyusername"];
$company_id = $_SESSION["company_id"];
$responsearray=array();

$company_type=$_SESSION["company_type"];
$qry = "SELECT Company_Logo FROM tw_company_details WHERE ID = '".$company_id."' ";
$retVal = $sign->SelectF($qry,'Company_Logo');
$Company_Logo = $retVal;

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValueUserCompanyImage= $commonfunction->getSettingValue("Company Image");

$settingValueCompanyPanel=$commonfunction->getSettingValue("CompanyPanel");

$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$MainLogo=$commonfunction->getCommonDataValue("MainLogo");
$MiniLogo=$commonfunction->getCommonDataValue("MiniLogo");


$qryMenu="select id,module_name,module_icon,url from tw_module_master where visibility='true' and panel='".$settingValueCompanyPanel."' order by priority";
$qryMenuCnt="Select count(*) as cnt from tw_module_master where visibility='true' and panel='".$settingValueCompanyPanel."'";


$valModules = $sign->FunctionJSON($qryMenu);

$retModulesCount = $sign->Select($qryMenuCnt);

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

$dashboard = "";
if($company_type=="EPR")
{
	$dashboard = "pgEPRServicesDashboard.php";
}
else
{
	$dashboard = "pgCompanyDashboard.php";
}
?>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
	<a class="navbar-brand brand-logo mr-5" href="<?php echo $dashboard; ?>"><img src="<?php echo $CommonDataValueCommonImagePath.$MainLogo;?>" class="mr-2" alt="logo"/></a>
	<a class="navbar-brand brand-logo-mini" href="<?php echo $dashboard; ?>"><img src="<?php echo $CommonDataValueCommonImagePath.$MiniLogo;?>"/></a>
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
		  <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
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
		  <img loading="lazy" src="<?php if($Company_Logo==""){echo $settingValueUserImagePathOther.$settingValueUserCompanyImage; }else{ echo $settingValueUserImagePathVerification.$userid."/".$Company_Logo;}?>" class="img-lg rounded-circle mb-3" />
		</a>
		<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
		  <a class="dropdown-item" href="pgCompanyProfile.php">
			<i class="ti-user text-primary"></i>
			My Profile
		  </a>
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