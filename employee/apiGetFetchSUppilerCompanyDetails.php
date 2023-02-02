<?php 

include_once "function.php";	
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$ID=$_POST['val'];
$address_id="";
$google_map="";
$qryADD="select id,address_line1,address_line2,location,pincode,city,state,address_type,country,google_map From tw_company_address where (company_id='".$ID."' and public_visible='true' and default_address='true')";	
$retVal = $sign->FunctionJSON($qryADD);

$qryCnt="Select count(*) as cnt from tw_company_address  where (company_id='".$ID."' and public_visible='true' and default_address='true')";
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
	 $address_line1=$decodedJSON->response[$count]->address_line1;
	 $count=$count+1;
	 $address_line2=$decodedJSON->response[$count]->address_line2;
	 $count=$count+1;
	 $location=$decodedJSON->response[$count]->location;
	 $count=$count+1;
	 $pincode=$decodedJSON->response[$count]->pincode;
	 $count=$count+1;
	 $city=$decodedJSON->response[$count]->city;
	 $count=$count+1;
	 $state=$decodedJSON->response[$count]->state;
	 $count=$count+1;
	 $address_type = $decodedJSON->response[$count]->address_type;
	 $count=$count+1;
	 $country = $decodedJSON->response[$count]->country;
	 $count=$count+1;
	 $google_map = $decodedJSON->response[$count]->google_map;
	 $count=$count+1;
	 $qry4="SELECT address_icon FROM tw_address_type_master where id='".$address_type."'";
	 $address_icon= $sign->SelectF($qry4,"address_icon");
	 //$address_icon="ti-home";
	 
	$qry5="SELECT address_type_value FROM tw_address_type_master where id='".$address_type."'";
	$address_type_value= $sign->SelectF($qry5,"address_type_value");
	$address=$address_line1.",<br>".$address_line2.",<br>".$location.",<br>".$pincode." ".$city." ".$state;

	$returnADD.="<div class=''><p>".$address."</p></div>";
	//$returnADD.=$i;
	 $i=$i+1;
}
 $CompanyDetails=array();
 array_push($CompanyDetails,$returnADD,$google_map,$address_id);
 //array_push($CompanyDetails,$Pan, $GST, $Value);
 echo json_encode($CompanyDetails);
?>