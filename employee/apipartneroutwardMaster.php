<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";

$commonfunction=new Common();
$sign=new Signup();

$employee_id = $_SESSION["employee_id"];
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");	
$qry="select id,name,converted_material,logo from tw_partner_outward_master order by id Desc";
$retVal = $sign->FunctionJSON($qry);

$EmailQry = "select value from tw_employee_contact where employee_id = '".$employee_id."' and contact_field='".$settingValuePemail."'";
$EMAIL = $sign->SelectF($EmailQry,'value'); 

$qry1="Select count(*) as cnt from tw_partner_outward_master";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>#</th><th>Customer Logo</th><th>Company Name</th><th>Converted Material</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$name = $decodedJSON2->response[$count]->name;
$count=$count+1;
$converted_material =$decodedJSON2->response[$count]->converted_material;
$count=$count+1;
$logo =$decodedJSON2->response[$count]->logo;
$count=$count+1;

$logo_url = $settingValueEmployeeImagePathVerification . $EMAIL . "/" . $logo;

	$table.="<tr>";
	$table.="<td>".$it."</td>";
	$table.="<td><img alt='Logo' src=".$logo_url." /></td>";
	$table.="<td>".$name."</td>";
	$table.="<td>".$converted_material."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$it++;
	$table.="</tr>";
$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>

	