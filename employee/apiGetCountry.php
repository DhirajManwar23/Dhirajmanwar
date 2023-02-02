<?php 
include_once "function.php";	
$sign=new Signup();
$selectCategory=$_POST['SELECTID'];

$countryQry="SELECT id,country_name FROM  tw_country_master where id='".$selectCategory."' ";
$country = $sign->FunctionOption($countryQry,"",'country_name',"id");



$SubCategory=array();
 array_push($SubCategory,$country);
 
 echo json_encode($SubCategory);


?>