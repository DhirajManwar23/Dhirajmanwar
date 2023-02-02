<?php
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$po_id=$_POST['po_id'];

$qry="SELECT state,district,product_id FROM tw_po_details where po_id='".$po_id."'";
$retval= $sign->FunctionJSON($qry);
$decodedJSON2 = json_decode($retval);
$Getstate=$decodedJSON2->response[0]->state;
$district=$decodedJSON2->response[1]->district;
$productGet=$decodedJSON2->response[2]->product_id;



$StateQry="SELECT state_name FROM tw_state_master where id='".$Getstate."'";
$Fetchstate=$sign->SelectF($StateQry,"state_name");
$CityQry="SELECT city_name FROM tw_city_master where state_id='".$Getstate."'";
$FetchCity=$sign->SelectF($CityQry,"city_name");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValuealloted= $commonfunction->getSettingValue("Alloted");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNotAllotted=$commonfunction->getSettingValue("NotAllotted");
$settingValueFree=$commonfunction->getSettingValue("default_value");


$MaterialQry="SELECT  moi.id as moi_id,mo.po_id,mo.company_id,mo.status,mo.company_address,moi.quantity,ca.state,ca.city,moi.material_id,pm.product_name,cd.CompanyName FROM tw_material_outward mo INNER JOIN tw_company_address ca ON mo.company_address=ca.id INNER JOIN tw_material_outward_individual moi ON mo.id=moi.material_outward_id INNER JOIN tw_product_management pm ON moi.material_id=pm.id INNER JOIN tw_company_details cd ON mo.company_id=cd.ID where (ca.state LIKE '%".$Fetchstate."%' or ca.city Like '%".$FetchCity."%') And mo.status='".$settingValueApprovedStatus."' AND moi.material_id='".$productGet."' AND moi.assign_status!='".$settingValuealloted."' AND (moi.assign_status='".$settingValueNotAllotted."' or moi.assign_status='".$settingValueFree."' )";


$qry2="SELECT count(*)as cnt  FROM tw_material_outward mo INNER JOIN tw_company_address ca ON mo.company_address=ca.id INNER JOIN tw_material_outward_individual moi ON mo.id=moi.material_outward_id INNER JOIN tw_product_management pm ON moi.material_id=pm.id INNER JOIN tw_company_details cd ON mo.company_id=cd.ID  where (ca.state LIKE '%".$Fetchstate."%' or ca.city Like '%".$FetchCity."%') And mo.status='".$settingValueApprovedStatus."' AND moi.material_id='".$productGet."' AND moi.assign_status!='".$settingValuealloted."' AND (moi.assign_status='".$settingValueNotAllotted."' or moi.assign_status='".$settingValueFree."' )";

$MaterialRetval = $sign->FunctionJSON($MaterialQry);
$retVal2 = $sign->Select($qry2);
$table="";		
$NAME= "For ".$Fetchstate."/".$FetchCity;
 
 $SubCategory=array();
if($retVal2==0)	{
	$table.="
				<div class='card'>
				  
					<div class='card-body text-center'>
							<img src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
						</div>
					</div>
					
				  </div><br>";	
				 
               array_push($SubCategory,$table,$NAME);
 
                echo json_encode($SubCategory);
				 
	
}	
else{
		$decodedJSON2 = json_decode($MaterialRetval);
		$count = 0;
		$i = 1;
		$x=$retVal2;
		$it=0;
		
		$retVal4="";
		while($x>=$i){
			$moi_id = $decodedJSON2->response[$count]->moi_id;
			$count=$count+1;
			$po_id = $decodedJSON2->response[$count]->po_id;
			$count=$count+1;
			$company_id = $decodedJSON2->response[$count]->company_id;
			$count=$count+1;
			$status = $decodedJSON2->response[$count]->status;
			$count=$count+1;
			$company_address = $decodedJSON2->response[$count]->company_address;
			$count=$count+1;
			$total_quantity = $decodedJSON2->response[$count]->quantity;
			$count=$count+1;
			$state = $decodedJSON2->response[$count]->state;
			$count=$count+1;
			$city = $decodedJSON2->response[$count]->city;
			$count=$count+1;
			$material_id = $decodedJSON2->response[$count]->material_id;
			$count=$count+1;
			$product_name = $decodedJSON2->response[$count]->product_name;
			$count=$count+1;
			$CompanyName = $decodedJSON2->response[$count]->CompanyName;
			$count=$count+1;
					
			
			$table.="
			<div class='row'>
			    <div class='card-body p-3'>
						<div class='card-body card rounded border mb-2'>
						<div class='content'>
								<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12'>
								<input class='cbCheck' type='checkbox' value='".$moi_id."/".$total_quantity."/".$company_id."/".$material_id."/".$Getstate."/".$district."' id='check-".$moi_id."' /><i class='input-helper'></i>
									&nbsp; &nbsp;<label class='mb-1' id='companyname' value=".$company_id." id='Company_Name'>Company Name:<strong> ".$CompanyName."</strong></label>
                                     <br>									
									&nbsp; &nbsp; &nbsp; &nbsp;<label class='mb-1'>Product Name:<strong> ".$product_name."</strong></label> 
									<br>									
									&nbsp; &nbsp; &nbsp; &nbsp;<label class='mb-1'>Quantity:<strong> ".$total_quantity."</strong></label>
									&nbsp; &nbsp;<a id='ainfo' onclick='ViewInfo(".$moi_id.")' class='text-info pointer-cursor'>View info</a> 								
									</div>
								</div>
						</div>
												
				</div>
			</div>
			<br>";
						$i=$i+1;
		}
		  array_push($SubCategory,$table,$NAME);
 
                echo json_encode($SubCategory);
}


?>