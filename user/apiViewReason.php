<?php
include_once "function.php";
$sign=new Signup();
$type=$_POST['type'];
$requested_id=$_POST['id'];

$RejectedReason="";


$qry4="select reason from tw_rejected_reason_master where panel='Bank Details' and visibility='true'";
$RejectedReason=$sign->SelectF($qry4,"reason");

$responsearray=array();
array_push($responsearray,$RejectedReason);
echo json_encode($responsearray);


?>