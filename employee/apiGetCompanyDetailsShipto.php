<?php 

include_once "function.php";	
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$ID=$_POST['id'];
$BIll_id=$_POST['BIll_id'];

$qryADD="select id,CONCAT(address_line1,' ',address_line2,' ',location,' ',pincode,' ',city,' ',state)as address,address_type,country,google_map,default_address From tw_company_address where company_id='".$ID."' AND public_visible='true'";	
$retVal = $sign->FunctionJSON($qryADD);

$qryCnt="Select count(*) as cnt from tw_company_address  where company_id='".$ID."' AND public_visible='true'";
$retVal1 = $sign->Select($qryCnt);
$decodedJSON = json_decode($retVal);
//var_dump($decodedJSON);
 $count = 0;
 $i = 1;
 $x=$retVal1;
// $Main_address="";
$returnADD="";
 while($x>=$i){
 $address_id=$decodedJSON->response[$count]->id;
 $count=$count+1;
 $address=$decodedJSON->response[$count]->address;
 $count=$count+1;
 $address_type = $decodedJSON->response[$count]->address_type;
 $count=$count+1;
 $country = $decodedJSON->response[$count]->country;
 $count=$count+1;
 $google_map = $decodedJSON->response[$count]->google_map;
 $count=$count+1; 
 $default_address = $decodedJSON->response[$count]->default_address;
 $count=$count+1;
 
 $is_checked="";
if ($address_id==$BIll_id)
{
	$is_checked="checked='checked'";
}

 $qry4="SELECT address_icon FROM tw_address_type_master where id='".$address_type."'";
 $address_icon= $sign->SelectF($qry4,"address_icon");
 //$address_icon="ti-home";
 
$qry5="SELECT address_type_value FROM tw_address_type_master where id='".$address_type."'";
$address_type_value= $sign->SelectF($qry5,"address_type_value");
//$address_type_value="Home";
 $addPass='"'.$address.'"';

$returnADD.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'><div class='card'><div class='card-body'><h4 class='card-title'><a href='javascript:void(0)' onclick='saveAddShipto(".$address_id.",".$addPass.")'> <input type='radio' id='radshiptoAddress' class='radAddress' name='radshiptoAddress' value=".$address_id." ".$is_checked."></a> <i class=".$address_icon."></i> ".$address_type_value."</h4><p>".$address."</p></div></div></div>";
//$returnADD.=$i;
 $i=$i+1;
}
 $CompanyDetails=array();
 array_push($CompanyDetails,$returnADD,$google_map);
 //array_push($CompanyDetails,$Pan, $GST, $Value);
 echo json_encode($CompanyDetails);
?>