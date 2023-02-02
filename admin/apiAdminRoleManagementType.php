<?php
session_start();
// Include class definition
include_once "function.php";
include_once("commonFunctions.php");
$commonfunction=new Common();
$sign=new Signup();
$admin_id = $_SESSION["username"] ;
$role_id = $_REQUEST["role_id"];
$settingValueAdminPanel=$commonfunction->getSettingValue("AdminPanel"); 
$qry="select id,module_name from tw_module_master where panel='".$settingValueAdminPanel."'and visibility='true' order by priority asc";
$retVal = $sign->FunctionJSON($qry);
$qry1="Select count(*) as cnt from tw_module_master where panel='".$settingValueAdminPanel."' and visibility='true'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);

$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$submodule_id="";


while($x>=$i){
		
	$moduleid = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$module_name = $decodedJSON2->response[$count]->module_name;
	$count=$count+1;
	
		
	$qryModuleCheck = "select count(*) as cnt from tw_admin_rights_management where role_id='".$role_id."' and rights_id='".$moduleid."' and rights_type='Module'";
	$moduleChk = $sign->Select($qryModuleCheck);
	
	$mChecked="";
	if ($moduleChk>0)
	{
		$mChecked="checked='checked'";
	}
	else
	{
		$mChecked="";
	}
	$table.='<div class="row">
				<div class="col-md-12 grid-margin stretch-card">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 col-10">
									<h6 class="mb-1">'.$module_name.'</h6> 	
								</div>
								<div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 col-2">
									<input type="checkbox" name="chkModule" id="choose-all'.$moduleid.'" value="'.$moduleid.'" '.$mChecked. '/>
								</div>';
	$qry4="Select count(*) as cnt from tw_submodule_master where module='".$moduleid."' and visibility='true'";
	$retVal4 = $sign->Select($qry4);
	$tableSM="";
	if($retVal4>0){
		$qry3="Select id,submodule_name from tw_submodule_master where module='".$moduleid."' and visibility='true'";
		$retVal3 = $sign->FunctionJSON($qry3);
		$decodedJSON3 = json_decode($retVal3);
		$countSM = 0;
		$iSM = 1;
		$xSM=$retVal4;
		$tableSM="<hr>";
		while($xSM>=$iSM){
			$submodule_id=$decodedJSON3->response[$countSM]->id;
			$countSM=$countSM+1;
			$submodule_name = $decodedJSON3->response[$countSM]->submodule_name;
			$countSM=$countSM+1;
			
			$qrySubModuleCheck = "select count(*) as cnt from tw_admin_rights_management where role_id='".$role_id."' and rights_id='".$submodule_id."' and rights_type='Sub Module'";
			$submoduleChk = $sign->Select($qrySubModuleCheck);
			
			$smChecked="";
			if ($submoduleChk>0)
			{
				$smChecked="checked='checked'";
			}
			else
			{
				$smChecked="";
			}
			
			$tableSM.='<div class="row"><div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 col-10">
									<p class="mb-1">'.$submodule_name.'</p> 	
								</div>
								<div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 col-2">
									<input type="checkbox" name="chkSubModule" id="choose-all'.$submodule_id.'" value="'.$submodule_id.'" '.$smChecked.'/>					
								</div></div>';
			$iSM=$iSM+1; 
		}
		
	}
	
	$table.='</div>';
	$table.=$tableSM;
	$table.='</div>
					</div>
				</div>
			</div>';
	
	$i=$i+1; 
} 
echo $table;

	
?>
