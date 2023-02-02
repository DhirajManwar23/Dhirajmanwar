<?php
 
include_once "function.php";	
$sign=new Signup();
$valvaendorid=$_POST['valvaendorid'];

$poQry="SELECT id,po_number FROM  tw_po_info where supplier_id='".$valvaendorid."' ";
echo $poid = $sign->FunctionOption($poQry,"",'po_number',"id");
/* 


$poinfo=array();
array_push($poinfo,$poid);
echo json_encode($poinfo);
 */
?>