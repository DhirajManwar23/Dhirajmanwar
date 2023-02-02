<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
 $qry="select mm.id,mm.module_name,pm.panel,mm.module_icon,mm.url,mm.priority,mm.visibility from tw_module_master mm INNER JOIN tw_panel_master pm ON mm.panel WHERE mm.panel=pm.id order by mm.id desc ";
$format = "HTML";
$tableName = "tw_module_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>	


	
	