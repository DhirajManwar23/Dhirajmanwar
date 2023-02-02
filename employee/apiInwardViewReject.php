<?php
 include_once "function.php";
$sign=new Signup();

$id=$_POST['id'];

$qry="select reason from tw_material_outward where id = '".$id."'";
$retVal=$sign->SelectF($qry,"reason");
echo $retVal; 
?>