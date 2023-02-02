<?php
include_once "function.php";
$sign=new Signup();
$id=$_POST['id'];


$qry="Delete from tw_outward_data_entry where id='".$id."'  ";
	$retVal = $sign->FunctionQuery($qry);
	echo $retVal;
?>