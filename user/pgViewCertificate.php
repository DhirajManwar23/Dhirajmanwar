<?php
session_start();
include_once "commonFunctions.php";
include_once "Qrfunction.php";
$commonfunction=new Common();
$QrCodefunction=new TwQr();
include_once("function.php");
$sign=new Signup();


$po_id=$_REQUEST["po_id"];
$state=$_REQUEST["state"];
$SelectDate=$_REQUEST["date"];
$monthname=explode("-",$SelectDate);
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePrimaryContact= $commonfunction->getSettingValue("Primary Contact");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$Podetailsqry="SELECT supplier_id,employee_id,supplier_address_id,total_quantity FROM `tw_po_info` where id='".$po_id."'";
$retVal = $sign->FunctionJSON($Podetailsqry);
$decodedJSON = json_decode($retVal);
$supplier_id = $decodedJSON->response[0]->supplier_id;
$employee_id = $decodedJSON->response[1]->employee_id;
$supplier_address_id = $decodedJSON->response[2]->supplier_address_id;
$total_quantity = $decodedJSON->response[3]->total_quantity;

$query="Select ID,Company_Logo,CompanyName from tw_company_details where ID='".$supplier_id."'";
$DocVal = $sign->FunctionJSON($query);
$decodedJSON = json_decode($DocVal);
$ID = $decodedJSON->response[0]->ID; 
$Company_Logo = $decodedJSON->response[1]->Company_Logo; 
$CompanyName = $decodedJSON->response[2]->CompanyName;

$CMPEMAILQRY="select value from  tw_employee_contact where employee_id='".$employee_id."' and contact_field='".$settingValuePemail."'";
$EMAIL= $sign->SelectF($CMPEMAILQRY,"value");

$MobileQRY="select value from  tw_employee_contact where employee_id='".$employee_id."' and contact_field='".$settingValuePrimaryContact."'";
$Mobile= $sign->SelectF($MobileQRY,"value");

$EmpnameQry="SELECT employee_name FROM `tw_employee_registration` where id='".$employee_id."'";
$Empname=$sign->SelectF($EmpnameQry,"employee_name");
$SupplyTypeQry="SELECT DISTINCT supply_type FROM `tw_temp` where po_id='".$po_id."'";
$SupplyType=$sign->SelectF($SupplyTypeQry,"supply_type");

if($SupplyType=="Trading"){
$SupplyType="Recycling certificate";
}
else if($SupplyType=="self"){
$SupplyType="EPR certificate";
}
else{
$SupplyType="-";
}

$companyAddQry="SELECT concat(address_line1,', ',address_line2,', ',location,', ',pincode,', ',city) as Address,state,country FROM `tw_company_address` where id='".$supplier_address_id."'";
$retValAdd = $sign->FunctionJSON($companyAddQry);
$decodedJSON9 = json_decode($retValAdd);
$companyAdd = $decodedJSON9->response[0]->Address;
$companystate = $decodedJSON9->response[1]->state;
$companycountry = $decodedJSON9->response[2]->country;


$StateNameQry="SELECT state_name FROM `tw_state_master` where id='".$state."'";
$StateName= $sign->SelectF($StateNameQry,"state_name");

$qry1="select IFNULL (sum(replace(plant_quantity, ',', '')), 0) as mcount FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$StateName."' and status='".$settingValueCompletedStatus."' and plant_wbs_date like '".$SelectDate."%'";

/* $qry1="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.dispatched_state='".$StateName."'  and tpi.supplier_id='".$supplier_id."' and tt.po_id='".$po_id."' and tt.status='".$settingValueCompletedStatus."'and plant_wbs_date like '".$SelectDate."%' GROUP by aggeragator_name"; */
$Totalquantity = $sign->selectF($qry1,"mcount");

$dateQry="SELECT DISTINCT accepted_date FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$StateName."'";
$FetchDate=$sign->SelectF($dateQry,"accepted_date");

$date=date_create($SelectDate);
$month=$monthname[1];
$month_name = date("F", mktime(0, 0, 0, $monthname[1], 10));
$monthWord=date_format($date,"M");
$year1=date_format($date,"Y");
$year2 = substr( $year1, -2);
if($month>=04){
	$year=$year1+1;
}
else{
	$year=$year1-1;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Purchase Order</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>


	<table class="printtbl" id="tblData" border='2px' >
	<tr>
	<th class="center-text" colspan='11'>ANNUAL REPORT_ CPCB Format - FY <?php echo $year1; ?>-<?php echo $year; ?></th>
	</tr>
	
	<tr>
        <th>Sr. No.</th>
        <th>Date</th>
        <th>Name of Plastic Waste Processing Facility (PWPF)</th>
		<th>Type of PWPF</th>
		<th colspan="4">Contact Details</th>
		<th>Qty of Waste(Tonnes)</th>
		<th rowspan="2">Supporting Document Description</th>
		<th  rowspan="2">Supporting Document Annexure No. (CPC)</th>
    </tr>
	<tr>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th>Address of PWPF</th>
		<th>Name of Authorised Person</th>
		<th>Email</th>
		<th>Phone No</th>
		<th></th>
	</tr>	
	<tr>
		<th>1</th>
		<th><?php echo  date("d-m-Y",strtotime($FetchDate)) ; ?></th>
		<th><?php echo $CompanyName; ?></th>
		<th>Recycling</th>
		<th><?php echo  $companyAdd ; ?> <?php $companystate ?><?php $companycountry ?></th>
		<th><?php echo $Empname; ?></th>
		<th><?php echo $EMAIL; ?></th>
		<th><?php echo $Mobile; ?></th>
		<th><?php echo $Totalquantity; ?></th>
		<th><?php echo $SupplyType;  ?> </th>
		<th>Annexure_01 <?php echo $StateName; ?> <?php echo $month_name ; ?> <?php echo $year2 ; ?></th>

	</tr>

	</table>

</table>
									
</div>
<br>
<div class="center-text"><button type="submit" onclick="exportTableToExcel('tblData', 'CPCB_Certificate')" style="background: #149ddd;border: 0; padding: 10px 24px; color: #fff; transition: 0.4s; border-radius: 4px;">Download to Excel</button></div>
<script>
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
</script>

</body>

</html>