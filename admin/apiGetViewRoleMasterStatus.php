<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="SELECT rm.id,rm.role_name,pm.panel,rm.priority,rm.visibility FROM tw_role_master rm INNER JOIN tw_panel_master pm ON rm.panel = pm.id order by rm.id Desc";
$format = "HTML";
$tableName = "tw_role_master";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;
?>
