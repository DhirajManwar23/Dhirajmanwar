<?php
	session_start();
	if(!isset($_SESSION["companyusername"])){
		header("Location:pgLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";
	$commonfunction=new Common();
	$company_id = $_SESSION["company_id"];

	$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");

	$qryPODetails="select id,po_number from tw_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."' order by id asc";
	
	$retValPODetails = $sign->FunctionJSON($qryPODetails);

	$qryPOCnt="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."'";
	$retValPOCnt = $sign->Select($qryPOCnt);

	$decodedJSON2 = json_decode($retValPODetails);
	$count = 0;
	$i = 1;
	$x=$retValPOCnt;
	$table="";
	$it=1;
	$table.="<thead><tr><th>#</th><th>PO</th><th>State</th><th>Start Date</th><th>Delivery Date</th><th>Report </th></tr></thead><tbody>";
	
	while($x>=$i){

	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$po_number = $decodedJSON2->response[$count]->po_number;
	$count=$count+1;
	
	
	$qry2="select CompanyName from tw_company_details where ID='".$company_id."'";
	$retVal2 = $sign->SelectF($qry2,"CompanyName");
	$qry3 = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$id."')";
	$statename = $sign->SelectF($qry3,'state');
	
	
	$queryPODates = "select DISTINCT start_date,delivery_date from tw_po_details where po_id='".$id."' order by id asc";
	$PODates = $sign->FunctionJSON($queryPODates);
	$decodedJSON = json_decode($PODates);
	$start_date = $decodedJSON->response[0]->start_date;
	$delivery_date = $decodedJSON->response[1]->delivery_date;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$po_number."</td>"; 
	//$table.="<td></td>";
	$table.="<td>".$statename."</td>";
	$table.="<td>".date("d-m-Y", strtotime($start_date))."</td>";
	$table.="<td>".date("d-m-Y", strtotime($delivery_date))."</td>";
	$table.="<td><a href='pgEPRSGenerateDocuments.php?po_id=".$id."'><i class='ti-file'></i></a></td>";
	//$table.="<td><a href='pgEPRSGenerateDocuments.php?po_id=".$id."'><i class='ti-file'></i></a></td>";
	
	$it++;
	$table.="</tr>";
	

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>



