<?php 
session_start();
include_once "function.php";	
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$company_id=$_SESSION["company_id"];
$ID=$_POST['val'];
$address_id="";
$google_map="";
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueRejectedByCompany= $commonfunction->getSettingValue("RejectedByCompany");
$settingValueRejectedByAuditor= $commonfunction->getSettingValue("RejectedByAuditor");

if($ID=="0"){
	
	$div="";
	$queryawaitingpocount = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as awaitingpocount FROM tw_temp where status='".$settingValueAwaitingStatus."' and po_id in (select id from tw_po_info where company_id='".$company_id."')";
	$awaitingpocount = $sign->SelectF($queryawaitingpocount,"awaitingpocount");
	if($awaitingpocount==""){
		$awaitingpocount=0.00;
	}
	
    $queryacceptedpocount = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as acceptedpo FROM tw_temp where status='".$settingValueCompletedStatus."' and po_id in (select id from tw_po_info where company_id='".$company_id."')";
	$acceptedpo = $sign->SelectF($queryacceptedpocount,"acceptedpo");
	if($acceptedpo==""){
		$acceptedpo=0.00;
	}
	
	$queryverifiedpocount = "SELECT IFNULL (sum(total_quantity), 0) as activepo FROM tw_po_info where  company_id='".$company_id."' and status='".$settingValueApprovedStatus."' ";
	$activepo = $sign->SelectF($queryverifiedpocount,"activepo");
	if($activepo==""){
		$activepo=0.00;
	}
	
	$UNFULLFILLED = $activepo - ($acceptedpo + $awaitingpocount);
	if($UNFULLFILLED==""){
		$UNFULLFILLED=0.00;
	}
	
	$queryrejectedbycompanycount = "SELECT COUNT(*) as rejectedbycompany FROM tw_temp where status='".$settingValueRejectedStatus."' and po_id in (select id from tw_po_info where company_id='".$company_id."') and rejected_by='".$settingValueRejectedByCompany."'";
	$rejectedbycompany = $sign->SelectF($queryrejectedbycompanycount,"rejectedbycompany");
	if($rejectedbycompany==""){
		$rejectedbycompany=0.00;
	}
	
	$queryrejectedbyauditorcount = "SELECT COUNT(*) as rejectedbyauditor FROM tw_temp where status='".$settingValueRejectedStatus."' and po_id in (select id from tw_po_info where company_id='".$company_id."') and rejected_by='".$settingValueRejectedByAuditor."'";
	$rejectedbyauditor = $sign->SelectF($queryrejectedbyauditorcount,"rejectedbyauditor");
	if($rejectedbyauditor==""){
		$rejectedbyauditor=0.00;
	}
	
	//----
	$qry = "select DISTINCT (category_name) from tw_temp where po_id in (select id from tw_po_info where company_id='".$company_id."')";
	$retVal = $sign->FunctionJSON($qry);
	$qrycnt = "select COUNT(DISTINCT(category_name)) as cnt from tw_temp where po_id in (select id from tw_po_info where company_id='".$company_id."')";
	$retValcnt = $sign->select($qrycnt);
	
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retValcnt;
	while($x>=$i){
		$category_name = $decodedJSON2->response[$count]->category_name;
		$count=$count+1;
		 
				  
		$qry5="select id from tw_epr_category_master where epr_category_name='".$category_name."'";
		$category_id = $sign->selectF($qry5,"id");
		
		$qry1="SELECT IFNULL(SUM(replace(tt.quantity, ',', '')),0) as mcount FROM tw_po_details tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.product_id in (SELECT id from tw_epr_product_master WHERE epr_category_id='".$category_id."') and tpi.company_id='".$company_id."'";
	
		$Totalquantity = $sign->selectF($qry1,"mcount");
		$div.="<div class='col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card'>
					  <div class='card card-light-blue'>
						<div class='card-body'>
						  <p class='mb-4'>Total Quantity<br>".$category_name."</p>
						  <p class='fs-25 mb-2'><span id='Q_Company'>".number_format($Totalquantity)."<span class='tw-small-word'>kg<span></span></p>
						</div>
					  </div>
					</div>";
		$i=$i+1;
	}
	//----
}
else{
	$div="";
	$queryverifiedpocount = "SELECT IFNULL (sum(total_quantity), 0) as activepo FROM tw_po_info where supplier_id='".$ID."' and status='".$settingValueApprovedStatus."'  and company_id='".$company_id."'";
	$activepo = $sign->SelectF($queryverifiedpocount,"activepo");
	if($activepo==""){
		$activepo=0.00;
	}
	$queryawaitingpocount = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as awaitingpocount FROM tw_temp where status='".$settingValueAwaitingStatus."'  and po_id in (select id from tw_po_info where supplier_id='".$ID."' and company_id='".$company_id."')";
	$awaitingpocount = $sign->SelectF($queryawaitingpocount,"awaitingpocount");
	if($awaitingpocount==""){
		$awaitingpocount=0.00;
	}
	
	$queryacceptedpocount = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as acceptedpo FROM tw_temp where status='".$settingValueCompletedStatus."' and po_id in (select id from tw_po_info where supplier_id='".$ID."' and company_id='".$company_id."')";	
	$acceptedpo = $sign->SelectF($queryacceptedpocount,"acceptedpo");
	if($acceptedpo==""){
		$acceptedpo=0.00;
	}
	
	$UNFULLFILLED = $activepo - ($acceptedpo + $awaitingpocount);
	if($UNFULLFILLED==""){
		$UNFULLFILLED=0.00;
	}
	
	$queryrejectedbycompanycount = "SELECT COUNT(*) as rejectedbycompany FROM tw_temp where status='".$settingValueRejectedStatus."' and po_id in (select id from tw_po_info where supplier_id='".$ID."' and company_id='".$company_id."') and rejected_by='Company'";
	$rejectedbycompany = $sign->SelectF($queryrejectedbycompanycount,"rejectedbycompany");
	if($rejectedbycompany==""){
		$rejectedbycompany=0.00;
	}
	
	$queryrejectedbyauditorcount = "SELECT COUNT(*) as rejectedbyauditor FROM tw_temp where status='".$settingValueRejectedStatus."' and po_id in (select id from tw_po_info where supplier_id='".$ID."' and company_id='".$company_id."') and rejected_by='Auditor'";
	$rejectedbyauditor = $sign->SelectF($queryrejectedbyauditorcount,"rejectedbyauditor");
	if($rejectedbyauditor==""){
		$rejectedbyauditor=0.00;
	}
	//----
	$qry = "select DISTINCT (category_name) from tw_temp where po_id in (select id from tw_po_info where company_id='".$company_id."' and supplier_id='".$ID."')";
	$retVal = $sign->FunctionJSON($qry);
	$qrycnt = "select COUNT(DISTINCT(category_name)) as cnt from tw_temp where po_id in (select id from tw_po_info where company_id='".$company_id."' and supplier_id='".$ID."')";
	$retValcnt = $sign->select($qrycnt);
	
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retValcnt;
	while($x>=$i){
		$category_name = $decodedJSON2->response[$count]->category_name;
		$count=$count+1;
		 
				  
		$qry5="select id from tw_epr_category_master where epr_category_name='".$category_name."'";
		$category_id = $sign->selectF($qry5,"id");
		
		$qry1="SELECT IFNULL(SUM(replace(tt.quantity, ',', '')),0) as mcount FROM tw_po_details tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.product_id in (SELECT id from tw_epr_product_master WHERE epr_category_id='".$category_id."') and tpi.company_id='".$company_id."' and tpi.supplier_id='".$ID."'";
	
		$Totalquantity = $sign->selectF($qry1,"mcount");
		$div.="<div class='col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card'>
					  <div class='card card-light-blue'>
						<div class='card-body'>
						  <p class='mb-4'>Total Quantity<br>".$category_name."</p>
						  <p class='fs-25 mb-2'><span id='Q_Company'>".number_format($Totalquantity)."<span class='tw-small-word'>kg<span></span></p>
						</div>
					  </div>
					</div>";
		$i=$i+1;
	}
	//----
	
}
	
 $CompanyDetails=array();
 array_push($CompanyDetails,number_format($activepo),number_format($acceptedpo),number_format($UNFULLFILLED),number_format($awaitingpocount),number_format($rejectedbycompany),number_format($rejectedbyauditor),$div);
 echo json_encode($CompanyDetails);
?>