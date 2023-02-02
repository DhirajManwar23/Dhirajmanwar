<?php 

include_once "function.php";	
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$suppliername=$_POST['requestidid'];

$IDqry="SELECT ID FROM `tw_company_details` WHERE CompanyName='".$suppliername."' ";
$ID= $sign->SelectF($IDqry,"ID");
$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");

$DefaultAddqry = "SELECT id,address_line1,address_line2,location,pincode,city,state FROM tw_company_address WHERE company_id='".$ID."' and public_visible='true' and default_address='true' ";
		$DefaultAdd = $sign->FunctionJSON($DefaultAddqry);
		$decodedJSON6 = json_decode($DefaultAdd);
		$defaulfbillingid = $decodedJSON6->response[0]->id;
		$address_line1 = $decodedJSON6->response[1]->address_line1;
		$address_line2 = $decodedJSON6->response[2]->address_line2;
		$location = $decodedJSON6->response[3]->location;
		$Mainpincode = $decodedJSON6->response[4]->pincode;
		$city = $decodedJSON6->response[5]->city;
		$mainstate = $decodedJSON6->response[6]->state;
		$defaulfsupplieraddress=$address_line1.$address_line2." ". $location." ". $Mainpincode." ".$city." ".$mainstate;
		
		
$DocGstQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$ID."' AND document_type='".$settingValueGSTDocuments."'  ORDER by document_type ASC ";

$GST = $sign->SelectF($DocGstQry,"document_number");


$qryADD="select id,CONCAT(address_line1,' ',address_line2,' ',location,' ',pincode,' ',city,' ',state)as address,address_type,country,google_map,default_address,pincode,state From tw_company_address where company_id='".$ID."' AND public_visible='true'";	
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
 $pincode = $decodedJSON->response[$count]->pincode;
 $count=$count+1;
 $state = $decodedJSON->response[$count]->state;
 $count=$count+1;

 $is_checked="";

 if ($default_address=="true")
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

$returnADD.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'><div class='card'><div class='card-body'><h4 class='card-title'><a href='javascript:void(0)' onclick='saveBill_Address(".$pincode.",".$addPass .")'> <input type='radio' id='radAddress' class='radAddress' name='radAddress' value=".$address_id." ".$is_checked." ></a> <i class=".$address_icon."></i> ".$address_type_value."</h4><p>".$address."</p></div></div></div>";
//$returnADD.=$i;
 $i=$i+1;
}
$countryUC=Ucwords($country);
$currencyQry="SELECT currency FROM tw_country_master where country_name='".$countryUC."'";
$currency= $sign->SelectF($currencyQry,"currency");

 $CompanyDetails=array();
 array_push($CompanyDetails,$returnADD,$defaulfsupplieraddress,$Mainpincode,$mainstate,$GST,$ID);
 //array_push($CompanyDetails,$Pan, $GST, $Value);
 echo json_encode($CompanyDetails);
?>