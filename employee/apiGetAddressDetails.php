<?php 

include_once "function.php";	
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$ID=$_POST['id'];

$qryADD="select id,CONCAT(address_line1,' ',address_line2,' ',location,' ',pincode,' ',city,' ',state)as address,address_type,country From tw_company_address where company_id='".$ID."'";	
$retVal = $sign->FunctionJSON($qryADD);

$qryCnt="Select count(*) as cnt from tw_company_address  where company_id='".$ID."'";
	$retVal1 = $sign->Select($qryCnt);
	$decodedJSON = json_decode($retVal);
	//var_dump($decodedJSON);
	 $count = 0;
	 $i = 1;
	 $x=$retVal1;
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
		 $qry4="SELECT address_icon FROM tw_address_type_master where id='".$address_type."'";
		 $address_icon= $sign->SelectF($qry4,"address_icon");
		 
		 $qry5="SELECT address_type_value FROM tw_address_type_master where id='".$address_type."'";
		 $address_type_value= $sign->SelectF($qry5,"address_type_value");
		 $addPass='"'.$address.'"';
		 $returnADD.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'><div class='card'><div class='card-body'><h4 class='card-title'><a href='javascript:void(0)' onclick='saveAdd(".$address_id.",".$addPass.")'> <input type='radio' id='radAddress' class='radAddress' name='radAddress' value=".$address_id." ></a> <i class=".$address_icon."></i> ".$address_type_value."</h4><p>".$address."</p></div></div></div>";
		 $i=$i+1;
}
 $CompanyDetails=array();
 array_push($CompanyDetails,$returnADD);
 echo json_encode($CompanyDetails);
?>