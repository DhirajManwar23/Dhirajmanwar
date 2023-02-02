<?php
session_start();
$supplier=$_POST["val"];
$company_id=$_SESSION["company_id"];
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
if($supplier=="0"){
	$varCondition="";
}
else{
	$varCondition="and pi.company_id='".$supplier."'";
}
$table="";
$table="<thead>
			<tr>
			<th class='text-center'>#</th>
			<th class='text-center'>Vendor</th>
			<th class='text-center'>Assign Qty</th>
			<th class='text-center'>Fullfilled Qty</th>
			<th class='text-center'>Awaiting Qty</th>
			<th class='text-center'>Pending Qty</th>
			<th class='text-center'></th>
			</tr>
		  </thead><tbody>";			  
	$qry = "SELECT DISTINCT pi.company_id,cd.CompanyName FROM tw_po_info pi INNER JOIN tw_company_details cd ON pi.company_id=cd.ID where pi.supplier_id='".$company_id."' ".$varCondition."";
	$retVal = $sign->FunctionJSON($qry);
	$qrycnt = "select COUNT(DISTINCT pi.company_id) as cnt FROM tw_po_info pi INNER JOIN tw_company_details cd ON pi.company_id=cd.ID where pi.supplier_id='".$company_id."' ".$varCondition."";
	$retValcnt = $sign->select($qrycnt);
	
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retValcnt;
	while($x>=$i){
		$pocompany_id = $decodedJSON2->response[$count]->company_id;
		$count=$count+1;
		$CompanyName = $decodedJSON2->response[$count]->CompanyName;
		$count=$count+1;
		
		$qry1="SELECT IFNULL(SUM(replace(tt.quantity, ',', '')),0) as mcount FROM tw_po_details tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tpi.company_id='".$pocompany_id."' and tpi.supplier_id='".$company_id."'";
		
		$Totalquantity = $sign->selectF($qry1,"mcount");
		
		$qry2="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tpi.company_id='".$pocompany_id."' and tt.status='".$settingValueCompletedStatus."' and tpi.supplier_id='".$company_id."'";
	
		$Totalquantity2 = $sign->selectF($qry2,"mcount");
		
		$qry4="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tpi.company_id='".$pocompany_id."' and tt.status='".$settingValueAwaitingStatus."' and tpi.supplier_id='".$company_id."'";
	
		$Totalquantity4 = $sign->selectF($qry4,"mcount");
		$Totalquantity3 = $Totalquantity - ($Totalquantity2+$Totalquantity4);
		
		 $table.="
				<tr>
				<td>".$i."</td>
				<td>".$CompanyName."</td>
				<td class='right-text' >".number_format(round($Totalquantity,2),2)."</td>
				<td class='right-text'>".number_format(round($Totalquantity2,2),2)."</td>
				<td class='right-text'>".number_format(round($Totalquantity4,2),2)."</td>
				<td class='right-text'>".number_format(round($Totalquantity3,2),2)."</td>
				<td id='flip'><a href='javascript:void(0)' onclick='hideRow(".$pocompany_id.")'><i class='ti-angle-down center-text'></i></a></td></tr>";
			  
		$qry1 = "select DISTINCT (category_name) from tw_temp where po_id in (select id from tw_po_info where supplier_id='".$company_id."' and company_id='".$pocompany_id."')";
		$retVal1 = $sign->FunctionJSON($qry1);
		$qrycnt1 = "select COUNT(DISTINCT(category_name)) as cnt from tw_temp where po_id in (select id from tw_po_info where supplier_id='".$company_id."' and company_id='".$pocompany_id."')";
		$retValcnt1 = $sign->select($qrycnt1);
		$decodedJSON1 = json_decode($retVal1);
		$count1 = 0;
		$i1 = 1;
		$x1=$retValcnt1;
		$year = date("Y");
		while($x1>=$i1){
			$category_name = $decodedJSON1->response[$count1]->category_name;
			$count1=$count1+1;
			//--Start code
			$table.="
				<tr class='tw-light-bg tw-toggle-hidden' id='idtr-".$pocompany_id."'>
				<td class='right-text' colspan='2'>".$category_name."</td>";
				  
			$qry5="select id from tw_epr_category_master where epr_category_name='".$category_name."'";
			$category_id = $sign->selectF($qry5,"id");
			
			$qry1="SELECT IFNULL(SUM(replace(tt.quantity, ',', '')),0) as mcount FROM tw_po_details tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.product_id in (SELECT id from tw_epr_product_master WHERE epr_category_id='".$category_id."') and tpi.company_id='".$pocompany_id."' and tpi.supplier_id='".$company_id."'";
		
			$Totalquantity = $sign->selectF($qry1,"mcount");
			
			$qry2="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.category_name='".$category_name."' and tt.status='".$settingValueCompletedStatus."' and tpi.company_id='".$pocompany_id."' and tpi.supplier_id='".$company_id."'";
		
			$Totalquantity2 = $sign->selectF($qry2,"mcount");
			
			$qry4="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.category_name='".$category_name."' and tt.status='".$settingValueAwaitingStatus."' and tpi.company_id='".$pocompany_id."' and tpi.supplier_id='".$company_id."'";
		
			$Totalquantity4 = $sign->selectF($qry4,"mcount");
			$Totalquantity3 = $Totalquantity - ($Totalquantity2+$Totalquantity4); 
			
			$table.="<td class='right-text' >".number_format(round($Totalquantity,2),2)."</td>
					<td class='right-text' >".number_format(round($Totalquantity2,2),2)."</td>
					<td class='right-text' >".number_format(round($Totalquantity4,2),2)."</td>
					<td class='right-text' >".number_format(round($Totalquantity3,2),2)."</td>
					<td></td>
				 
				</tr>
			 "; 
			//--End code
			$i1=$i1+1;
		}
		$i=$i+1;
	} 
	if ($count>0)
	{
		 $table.="</tbody>";
	}
$CompanyDetails=array();
 array_push($CompanyDetails,$table);
 echo json_encode($CompanyDetails);	
   
 ?>