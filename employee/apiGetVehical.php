<?php
include_once "function.php";	
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$ID=$_POST['id'];

$qry3 = "SELECT id,vehicle_number FROM tw_vehicle_details_master where transporter_id='".$ID."'";
$retVal3 = $sign->FunctionOption($qry3,'','vehicle_number','id');

$CompanyDetails=array();
 array_push($CompanyDetails,$retVal3);
 echo json_encode($CompanyDetails);

?>