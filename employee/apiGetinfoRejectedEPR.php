<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$po_id=$_POST["id"];
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$table="";
$qry=" SELECT id,outward_id,material_id,quantity,reason,created_on,alloted_company_id FROM `tw_rejected_material` where po_id='".$po_id."' order BY id DESC";

$qrycnt="SELECT COUNT(*) as cnt FROM tw_rejected_material where po_id='".$po_id."'";
$Cnt = $sign->Select($qrycnt);
if($Cnt==0)	{
	$table.="
				<div class='card'>
				  
					<div class='card-body text-center'>
							<img src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
						</div>
					</div>
					
				  </div><br>";	
				 
              
                echo $table;
				 
	
}
else{

$retVal11 = $sign->FunctionJSON($qry);
$decodedJSON3 = json_decode($retVal11);
$i = 1;
$it1=1;
$count = 0;
$x=$Cnt;
$table="<table class='printtbl'>
	  <th>#</th>
	  <th>Company Name</th>
	  <th>Quantity</th>
	  <th>Product</th>
	  <th>Rejection Date</th>
	  <th>Reason</th>";

while($x>=$i){
 $tableID= $decodedJSON3->response[$count]->id;
 $count=$count+1;
 $outward_id= $decodedJSON3->response[$count]->outward_id;
 $count=$count+1;
 $material_id= $decodedJSON3->response[$count]->material_id;
 $count=$count+1;
 $quantity= $decodedJSON3->response[$count]->quantity;
 $count=$count+1;
 $reason= $decodedJSON3->response[$count]->reason;
 $count=$count+1;
 $created_on= $decodedJSON3->response[$count]->created_on;
 $count=$count+1;
 $alloted_company_id= $decodedJSON3->response[$count]->alloted_company_id;
 $count=$count+1;

$reasonQry="SELECT reason FROM tw_rejected_reason_master where id='".$reason."'";
$reasonFetch=$sign->SelectF($reasonQry,"reason");
 
 $settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");

$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");


$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");


$qry2="Select CompanyName,Status,Company_Logo from tw_company_details where ID='".$alloted_company_id."'";
$retVal1 = $sign->FunctionJSON($qry2);
$decodedJSON = json_decode($retVal1);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$companyStatus = $decodedJSON->response[1]->Status;
$Company_Logo = $decodedJSON->response[2]->Company_Logo;



$retVal5 = ""; 
		

$qry3="Select value from tw_company_contact where company_id='".$alloted_company_id."'";
$retVal3 = $sign->SelectF($qry3,"value");
$verifyStatus=$commonfunction->getSettingValue("Verified Status");
$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

$productNameQry="SELECT product_name FROM `tw_product_management` where id='".$material_id."'";
$productName=$sign->SelectF($productNameQry,"product_name");

if(empty($Company_Logo)){
			$Company_Logo=$UserImagePathOther.$settingValueCompanyImage;
		} 
		else{
			$Company_Logo=$settingValueUserImagePathVerification.$retVal3."/".$Company_Logo;
		}
		$img="";
		if ($companyStatus==$verifyStatus) { 
			$img = "<img src='".$UserImagePathOther."".$VerifiedImage."'/>";
		}
		else{
			$img="";
		}
		
	$table.="
	  
	  <tr>
				<td class='center-text'>".$it1."</td>
				<td class='center-text'><img src=".$Company_Logo." width='50px' > <strong>".$CompanyName." ".$img."</strong></td>
				<th class='center-text'>".number_format($quantity,2)."</th>
				<td class='center-text'>". $productName."</td>
				<td class='center-text'>".date("d-m-Y", strtotime($created_on))."</td>
				<td class='center-text'><p class='text-danger'>".$reasonFetch."</p></td>
	  </tr> 
	 "; 


				  $i=$i+1;
				  $it1=$it1+1;
}

$table.="</table>";
echo $table;
}
?>