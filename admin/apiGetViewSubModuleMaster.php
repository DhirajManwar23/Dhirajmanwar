<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();

$qry="select sm.id,sm.submodule_name,m.module_name,sm.sub_module_icon,sm.url,sm.priority,sm.visibility from tw_submodule_master sm LEFT JOIN tw_module_master m ON sm.module = m.id order by sm.id Desc;";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_submodule_master order by id Desc";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Sub Module Name</th><th>Module</th><th>Sub Module Icon</th><th>Url</th><th>Priority</th><th>Visibility</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$submodule_name = $decodedJSON2->response[$count]->submodule_name;
$count=$count+1;
$module_name = $decodedJSON2->response[$count]->module_name;
$count=$count+1;
$sub_module_icon = $decodedJSON2->response[$count]->sub_module_icon;
$count=$count+1;
$url = $decodedJSON2->response[$count]->url;
$count=$count+1;
$priority  = $decodedJSON2->response[$count]->priority ;
$count=$count+1;
$visibility  = $decodedJSON2->response[$count]->visibility ;
$count=$count+1;


	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$submodule_name."</td>";
	$table.="<td>".$module_name."</td>";
	$table.="<td><i class='".$sub_module_icon."'></td>";
	$table.="<td>".$url."</td>";
	$table.="<td>".$priority."</td>";
	$table.="<td>".$visibility."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$it++;
	$table.="</tr>";
	

$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>

