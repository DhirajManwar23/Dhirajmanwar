<?php
 
include_once "function.php";	
$sign=new Signup();
$selectCategory=$_POST['SELECTID'];

$CityQry="SELECT id,city_name FROM  tw_city_master where state_id='".$selectCategory."' ";
$City = $sign->FunctionOption($CityQry,"",'city_name',"id");



$SubCategory=array();
 array_push($SubCategory,$City);
 
 echo json_encode($SubCategory);

?>