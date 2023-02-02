<?php
$employee_id = $_SESSION["employee_id"];
$company_id = $_SESSION["company_id"];
include_once "function.php";
include_once "commonFunctions.php";
$signNav=new Signup();
$commonfunction=new Common();
$qryMenu="";
$qryCnt="";
$settingValueEmployeePanel=$commonfunction->getSettingValue("EmployeePanel");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");

$responsearray=array();
$queryselforcepass="Select forced_password_change from tw_employee_login where employee_id='".$employee_id."'";
$forcepass= $sign->SelectF($queryselforcepass,'forced_password_change');

$qryMenuQueryCnt="SELECT count(*) as cnt FROM tw_po_info pi INNER JOIN tw_temp tt ON tt.po_id=pi.id WHERE pi.company_id='".$company_id."' and tt.status='".$settingValueRejectedStatus."'";
$retQueryCount = $sign->Select($qryMenuQueryCnt);

$qryMenu="select id,module_name,module_icon,url from tw_module_master where id in (select rights_id from tw_company_rights_management where rights_type='Module' and company_id='".$company_id."' and role_id=(select employee_role from tw_employee_registration where id='".$employee_id."')) and visibility='true' and panel='".$settingValueEmployeePanel."' order by priority";
$qryMenuCnt="select count(*) as cnt from tw_module_master where id in (select rights_id from tw_company_rights_management where rights_type='Module' and company_id='".$company_id."' and role_id=(select employee_role from tw_employee_registration where id='".$employee_id."')) and visibility='true' and panel='".$settingValueEmployeePanel."'";



$valModules = $signNav->FunctionJSON($qryMenu);

$retModulesCount = $signNav->Select($qryMenuCnt);

$decodedJSON = json_decode($valModules);
$count = 0;
$i = 1;
$x=$retModulesCount;
$menu="";
$disabled="";
while($x>=$i){
	$url = "";
	$navClass = "";
	
	$module_id = $decodedJSON->response[$count]->id;
	$count=$count+1;
	$module_name = $decodedJSON->response[$count]->module_name;
	$count=$count+1;
	$module_icon = $decodedJSON->response[$count]->module_icon;
	$count=$count+1;
	
	if($module_name=="Queries"){
		$module_name=$module_name."[".$retQueryCount."]";
	}
	
	if($forcepass=='true'){
		$url='#';
		$count=$count+1;
		$navClass = "nav-link-disable";
	}
	else{
		$url = $decodedJSON->response[$count]->url;
		$count=$count+1;
		$navClass = "nav-link";
	}
	
	 
	array_push($responsearray,$url);

	$qrySubMenuCnt="";
	
	$qrySubMenuCnt="Select count(*) as cnt from tw_submodule_master where id in (select rights_id from tw_company_rights_management where rights_type='Sub Module' and company_id='".$company_id."' and role_id=(select employee_role from tw_employee_registration where id='".$employee_id."')) and visibility='true' and module='".$module_id."'";
	
	$retSubModulesCount = $signNav->Select($qrySubMenuCnt);
	if ($retSubModulesCount==0)
	{
		$menu.="<li class='nav-item'>";
		$menu.="<a class='".$navClass."'  href='".$url."'><i class='".$module_icon." menu-icon'></i><span class='menu-title'>".$module_name."</span></a>";
		$menu.="</li>";
	}
	else
	{
		$qrySubMenu="select submodule_name,url from tw_submodule_master where id in (select rights_id from tw_company_rights_management where rights_type='Sub Module' and company_id='".$company_id."' and role_id=(select employee_role from tw_employee_registration where id='".$employee_id."')) and visibility='true' and module='".$module_id."' order by priority";
		
		$valSubModules = $signNav->FunctionJSON($qrySubMenu);
		$decodedJSONSM = json_decode($valSubModules);
		$countSM = 0;
		$iSM = 1;
		$xSM=$retSubModulesCount;
		$menu.="<li class='nav-item'>";
		$menu.="<a class='nav-link' data-toggle='collapse' href='#".$url."' aria-expanded='false' aria-controls='".$url."'><i class='".$module_icon." menu-icon'></i><span class='menu-title'>".$module_name."</span><i class='menu-arrow'></i></a>";
		while($xSM>=$iSM){
			$submodule_name = $decodedJSONSM->response[$countSM]->submodule_name;
			$countSM=$countSM+1;
			$sub_url = $decodedJSONSM->response[$countSM]->url;
			$countSM=$countSM+1;
			
			$menu.="<div class='collapse' id='".$url."'>";
			$menu.="<ul class='nav flex-column sub-menu'><li class='nav-item'> <a class='nav-link' href='".$sub_url."'>".$submodule_name."</a></li></ul>";
			$menu.="</div>";
			
			$iSM=$iSM+1;
		}
		$menu.="</li>";
	}
	$i=$i+1;
}
//}
 $_SESSION["responsearray"] = $responsearray;
 // print_r($_SESSION["responsearray"]);


?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
	<?php echo $menu; ?>
	
  </ul>
</nav>
