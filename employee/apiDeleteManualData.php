<?php
include_once "function.php";
$sign=new Signup();
$name=$_POST['name'];
$date=$_POST['date'];

$qry="Delete from tw_mixwaste_manual_entry where entry_date='".$date."' and name='".$name."' ";
	$retVal = $sign->FunctionQuery($qry);
	echo $retVal;
?>