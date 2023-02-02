<?php
$agent_id = $_SESSION["agent_id"];
/* // Include class definition 
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common(); */
$qryMenu="";
$qryCnt="";
$settingValueAgentPanel=$commonfunction->getSettingValue("AgentPanel");

$qryMenu="select id,module_name,module_icon,url from tw_module_master where visibility='true' and panel='".$settingValueAgentPanel."' order by priority";
$qryMenuCnt="Select count(*) as cnt from tw_module_master where visibility='true' and panel='".$settingValueAgentPanel."'";


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
	
	$qrySubMenuCnt="";
	
		$qrySubMenuCnt="Select count(*) as cnt from tw_submodule_master where visibility='true' and module='".$module_id."'";
	
	$retSubModulesCount = $sign->Select($qrySubMenuCnt);
	if ($retSubModulesCount==0)
	{
		$menu.="<li class='nav-item'>";
		$menu.="<a class='nav-link' href='".$url."'><i class='".$module_icon." menu-icon'></i><span class='menu-title'>".$module_name."</span></a>";
		$menu.="</li>";
	}
	else
	{
		 $qrySubMenu="select submodule_name,url from tw_submodule_master where visibility='true' and module='".$module_id."' order by priority";
		
		$valSubModules = $sign->FunctionJSON($qrySubMenu);
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
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
	<?php echo $menu; ?>
  </ul>
</nav>
