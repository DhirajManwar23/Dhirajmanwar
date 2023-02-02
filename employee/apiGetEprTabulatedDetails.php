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
	$varCondition="and twpi.supplier_id='".$supplier."'";
}
$table="";
$table="<thead>
			<tr>
			  <th class='center-text'>#</th>
			  <th class='center-text'>States</th>
			  <th class='center-text'>Assign Qty</th>
			  <th class='center-text'>Fullfilled Qty</th>
			  <th class='center-text'>Awaiting Qty</th>
			  <th class='center-text'>Pending Qty</th>
			</tr>
		  </thead>";
					  
	$qry = "select state_name from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id=twpi.id WHERE twpi.company_id='".$company_id."' ".$varCondition.")";
	$retVal = $sign->FunctionJSON($qry);
	$qrycnt = "select count(*) as cnt from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id=twpi.id WHERE twpi.company_id='".$company_id."' ".$varCondition.")";
	$retValcnt = $sign->select($qrycnt);
	
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retValcnt;
	$year = date("Y");
	while($x>=$i){
		$state_name = $decodedJSON2->response[$count]->state_name;
		$count=$count+1;
		 
		 $table.="<tbody>
				<tr>
				  <td class='center-text'>".$i."</td>
				  <td class='left-text'>".$state_name."</td>";
				  
			$qry5="select id from tw_state_master where state_name='".$state_name."'";
			$stateid = $sign->selectF($qry5,"id");
			
			$qry1="SELECT IFNULL(SUM(replace(tt.quantity, ',', '')),0) as mcount FROM tw_po_details tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.state='".$stateid."' and tpi.company_id='".$company_id."'";
		
			$Totalquantity = $sign->selectF($qry1,"mcount");
			
			$qry2="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.dispatched_state='".$state_name."' and tt.status='".$settingValueCompletedStatus."' and tpi.company_id='".$company_id."'";
		
			$Totalquantity2 = $sign->selectF($qry2,"mcount");
			
			
			
		
			$qry4="SELECT IFNULL(SUM(replace(tt.plant_quantity, ',', '')),0) as mcount FROM tw_temp tt INNER JOIN tw_po_info tpi ON tt.po_id=tpi.id WHERE tt.dispatched_state='".$state_name."' and tt.status='".$settingValueAwaitingStatus."' and tpi.company_id='".$company_id."'";
		
			$Totalquantity4 = $sign->selectF($qry4,"mcount");
			$Totalquantity3 = $Totalquantity - ($Totalquantity2+$Totalquantity4);
			
			$table.="<td class='right-text' >".number_format(round($Totalquantity,2),2)."</td>
				  <td class='right-text'>".number_format(round($Totalquantity2,2),2)."</td>
				  <td class='right-text'>".number_format(round($Totalquantity4,2),2)."</td>
				  <td class='right-text'>".number_format(round($Totalquantity3,2),2)."</td>
				</tr>
			 
			  </tbody>";
		
		$i=$i+1;
	}
					
	
	//}
	
$CompanyDetails=array();
 array_push($CompanyDetails,$table);
 echo json_encode($CompanyDetails);	
   
 ?>