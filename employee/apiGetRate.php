<?php 
$selectSubCategory=$_POST['SELECTID'];

include_once "function.php";	
$sign=new Signup();

$productQry="SELECT amount_per_unit FROM  tw_product_management where id='".$selectSubCategory."' ";
$product =$sign->SelectF($productQry,"amount_per_unit");

$UNITQry="SELECT uom FROM  tw_product_management where id='".$selectSubCategory."' ";
$UNITNO =$sign->SelectF($UNITQry,"uom");

$DescriptionQry="SELECT description FROM `tw_unit_of_measurement` where id='".$UNITNO."'";
$Description=$sign->SelectF($DescriptionQry,"description");

$SubCategory=array();
 array_push($SubCategory,$product,$UNITNO,$Description);
 
 echo json_encode($SubCategory);

?>