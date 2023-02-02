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
	$varCondition1="";
}
else{
	$varCondition="and supplier_id='".$supplier."'";
	$varCondition1="and tpi.supplier_id='".$supplier."'";
}
$table="";
$table="<thead>
			<tr>
			  <th class='center-text'>#</th>
			  <th class='center-text'>Category</th>
			  <th class='center-text'>Assign Qty</th>
			  <th class='center-text'>Fullfilled Qty</th>
			  <th class='center-text'>Awaiting Qty</th>
			  <th class='center-text'>Pending Qty</th>
			</tr>
		  </thead>";
					  
	$qry = "select DISTINCT (category_name) from tw_temp where po_id in (select id from tw_po_info where company_id='".$company_id."' ".$varCondition.")";
	$retVal = $sign->FunctionJSON($qry);
	$qrycnt = "select COUNT(DISTINCT(category_name)) as cnt from tw_temp where po_id in (select id from tw_po_info where company_id='".$company_id."' ".$varCondition.")";
	$retValcnt = $sign->select($qrycnt);
	
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retValcnt;
	$year = date("Y");
	while($x>=$i){
		$category_name = $decodedJSON2->response[$count]->category_name;
		$count=$count+1;
		 
		 $table.="<tbody>
				<tr>
				  <td class='center-text'>".$i."</td>
				  <td class='left-text'>".$category_name."</td>";
				  
			$qry5="select id from tw_epr_category_master where epr_category_name='".$category_name."'";
			$category_id = $sign->selectF($qry5,"id");
			
			$qry1="SELECT IFNULL(SUM(replace(tt.quantity, ',', '')),0) as mcount FROM tw_po_details tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.product_id in (SELECT id from tw_epr_product_master WHERE epr_category_id='".$category_id."') and tpi.company_id='".$company_id."' ".$varCondition1."";
		
			$Totalquantity = $sign->selectF($qry1,"mcount");
			
			$qry2="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.category_name='".$category_name."' and tt.status='".$settingValueCompletedStatus."' and tpi.company_id='".$company_id."' ".$varCondition1."";
		
			$Totalquantity2 = $sign->selectF($qry2,"mcount");
			
			$qry4="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.category_name='".$category_name."' and tt.status='".$settingValueAwaitingStatus."' and tpi.company_id='".$company_id."' ".$varCondition1."";
		
			$Totalquantity4 = $sign->selectF($qry4,"mcount");
			$Totalquantity3 = $Totalquantity - ($Totalquantity2+$Totalquantity4); 
			
			$table.="<td class='right-text' >".number_format(round($Totalquantity,2),2)."</td>
					<td class='right-text' >".number_format(round($Totalquantity2,2),2)."</td>
					<td class='right-text' >".number_format(round($Totalquantity4,2),2)."</td>
					<td class='right-text' >".number_format(round($Totalquantity3,2),2)."</td>
				 
				</tr>
			 
			  </tbody>"; 
		
		$i=$i+1;
	}
$CompanyDetails=array();
array_push($CompanyDetails,$table);
echo json_encode($CompanyDetails);	
   
 ?>