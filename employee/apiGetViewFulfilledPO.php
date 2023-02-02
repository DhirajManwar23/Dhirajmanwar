<?php
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";
	$commonfunction=new Common();
	$data = array();
	
	$requesttype = $_POST["requesttype"];
	$company_id = $_SESSION["company_id"];
	$settingValueEmployeeUrl= $commonfunction->getSettingValue("EmployeeUrl");
	$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
	$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

	$table="";

	if($requesttype=="vendor"){
		$qry="select id,po_number,status,company_id,supplier_id,date_of_po,total_quantity,final_total_amount,reason from tw_po_info where company_id='".$company_id."' and status='".$settingValueApprovedStatus."' order by id asc";
	
		$retVal = $sign->FunctionJSON($qry);

		$qry1="select count(*) as cnt from tw_po_info where company_id='".$company_id."' and status='".$settingValueApprovedStatus."'";
		$retVal1 = $sign->Select($qry1);

		$decodedJSON2 = json_decode($retVal);
		$count = 0;
		$i = 1;
		$x=$retVal1;
		$table="";
		$it=1;
		$table.="<thead><tr><th>#</th><th>PO Number</th><th>Vendor Name</th><th>State </th><th>Document</th></tr></thead><tbody>";
		
		while($x>=$i){
			$id = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			$po_number = $decodedJSON2->response[$count]->po_number;
			$count=$count+1;
			$status = $decodedJSON2->response[$count]->status;
			$count=$count+1;
			$company_id = $decodedJSON2->response[$count]->company_id;
			$count=$count+1;
			$supplier_id = $decodedJSON2->response[$count]->supplier_id;
			$count=$count+1;
			$po_date = $decodedJSON2->response[$count]->date_of_po;
			$count=$count+1;
			$total_quantity  = $decodedJSON2->response[$count]->total_quantity ;
			$count=$count+1;
			$final_total_amount  = $decodedJSON2->response[$count]->final_total_amount ;
			$count=$count+1;
			$reason  = $decodedJSON2->response[$count]->reason ;
			$count=$count+1;
			
			$qry2="select CompanyName from tw_company_details where ID='".$supplier_id."'";
			$retVal2 = $sign->SelectF($qry2,"CompanyName");
			$qry3 = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$id."')";
			$statename = $sign->SelectF($qry3,'state');

			$table.="<tr>";
			$table.="<td>".$it."</td>"; 
			$table.="<td>".$po_number."</td>"; 
			$table.="<td>".$retVal2."</td>";
			$table.="<td>".$statename."</td>";
			$table.="<td><a href='pgEPRSGenerateDocuments.php?po_id=".$id."'><i class='ti-file'></i></a></td>";
			
			$it++;
			$table.="</tr>";
			

			$i=$i+1;
		}
		$table.="</tbody>";
		
	}
	else if($requesttype=="state"){
		
		$qry = "select id,state_name from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id=twpi.id WHERE twpi.company_id='".$company_id."')";
		$retVal = $sign->FunctionJSON($qry);
		$qrycnt = "select count(*) as cnt from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id=twpi.id WHERE twpi.company_id='".$company_id."')";
		$retValcnt = $sign->select($qrycnt);
		
		$decodedJSON2 = json_decode($retVal);
		$count = 0;
		$i = 1;
		$x=$retValcnt;
		$table.="<thead><tr><th>#</th><th>State </th><th>Vendor Name</th><th>Document</th></tr></thead><tbody>";		
		while($x>=$i){
			$stateid = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			$state_name = $decodedJSON2->response[$count]->state_name;
			$count=$count+1;
			
			
			$qry3 = "SELECT GROUP_CONCAT(CompanyName) as cpnyname from tw_company_details where ID in (SELECT DISTINCT(pi.supplier_id) from tw_po_info pi INNER JOIN tw_po_details pd ON pi.id = pd.po_id WHERE pi.company_id='".$company_id."' AND state='".$stateid."')";
			$cpnyname = $sign->SelectF($qry3,'cpnyname');
			
			$table.="<tr>";
			$table.="<td>".$i."</td>"; 
			$table.="<td>".$state_name."</td>";
			$table.="<td>".$cpnyname."</td>";
			$table.="<td><a href='pgEPRSGenerateDocumentsStatewise.php?stateid=".$stateid."'><i class='ti-file'></i></a></td>";
			
			$table.="</tr>";
			
			$i=$i+1;
		}
		$table.="</tbody>";
	}
	else{
		
		$year = date("Y"); 
		$yearpuls = $year+1; 
		$start_date=$year."-04-01";
		$current_date=$yearpuls."-03-31";
		$start=(new DateTime($start_date))->modify('first day of this month');
		$end=(new DateTime($current_date))->modify('first day of next month');
		$interval=DateInterval::createFromDateString('1 month');
		$period=new DatePeriod($start, $interval, $end);
		
		$srno=1;
		$table.="<tbody><tr>";
		$table.="<th>#</th>";
		$table.="<th>Month</th>";
		//----
		$qry = "select id,state_name from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id=twpi.id WHERE twpi.company_id='".$company_id."')";
		$retVal = $sign->FunctionJSON($qry);
		$qrycnt = "select count(*) as cnt from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id=twpi.id WHERE twpi.company_id='".$company_id."')";
		$retValcnt = $sign->select($qrycnt);
		
		$decodedJSON2 = json_decode($retVal);
		$count = 0;
		$i = 1;
		$x=$retValcnt;
		while($x>=$i){
			
			$stateid = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			$state_name = $decodedJSON2->response[$count]->state_name;
			$count=$count+1;
			
			$table.="<th colspan='2' class='text-center'>".$state_name."</th>"; 
			array_push($data,$stateid);
			$i=$i+1;
		}
		$table.="<th colspan='2' class='text-center'>Total</th></tr>"; 
		$table.="<tr><td></td>"; 
		$table.="<td></td>"; 
		//----
		foreach($data as $dt) {
			
			$table.="<td class='text-center'>Consumption</td>"; 
			$table.="<td class='text-center'>EPR</td>"; 
			
		}
		$table.="<td class='text-center'>Consumption</td>"; 
		$table.="<td class='text-center'>EPR</td>"; 
		$table.="</tr>";
		foreach ($period as $dt) {
			$totalC=0.00;
			$totalEPR=0.00;
			$date="".$dt->format("Y-m");
			$table.="<tr>";
			$table.="<td>".$srno."</td>";
			$table.="<td>".$dt->format("M Y")."</td>";
			foreach($data as $dt) {
				$qrystateid="select state_name from tw_state_master where id ='".$dt."'";
				$statename=$sign->SelectF($qrystateid,"state_name");
				
				$qryplant_quantity="select IFNULL (sum(replace(plant_quantity, ',', '')), 0) as plant_quantity FROM tw_temp where po_id in(select id from tw_po_info where company_id='".$company_id."') and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."' and purchase_invoice_date like '".$date."%'";
				$plant_quantity=$sign->SelectF($qryplant_quantity,"plant_quantity");
				
				$qrycquantity="SELECT IFNULL (sum(replace(quantity, ',', '')), 0) as quantity FROM tw_consumption WHERE state_id='".$dt."' and created_on LIKE '".$date."%'";
				$cquantity=$sign->SelectF($qrycquantity,"quantity");
				
				$table.="<td class='text-center'>".number_format($cquantity,2)."</td>"; 
				$table.="<td class='text-center'>".number_format($plant_quantity,2)."</td>"; 
				$totalC=$totalC+$cquantity;
				$totalEPR=$totalEPR+$plant_quantity;
			}
			$table.="<td class='text-center'>".number_format($totalC,2)."</td>"; 
			$table.="<td class='text-center'>".number_format($totalEPR,2)."</td>"; 
			$srno++;
		}	
		$table.="</tr><tr>"; 
		$table.="<td colspan='2' class='text-center'><strong>Total Amount</strong></td>"; 
		$totalAmountC=0.00;
		$totalAmountEPR=0.00;
		foreach($data as $dt) {
			
			$qrystateid="select state_name from tw_state_master where id ='".$dt."'";
			$statename=$sign->SelectF($qrystateid,"state_name");
			
			$qryTplant_quantity="select IFNULL (sum(replace(plant_quantity, ',', '')), 0) as plant_quantity FROM tw_temp where po_id in(select id from tw_po_info where company_id='".$company_id."') and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."'";
			$Tplant_quantity=$sign->SelectF($qryTplant_quantity,"plant_quantity");
			
			$qryTcquantity="SELECT IFNULL (sum(replace(quantity, ',', '')), 0) as quantity FROM tw_consumption WHERE state_id='".$dt."'";
			$Tcquantity=$sign->SelectF($qryTcquantity,"quantity");
			$table.="<td class='text-center'><strong>".number_format($Tcquantity,2)."</strong></td>"; 
			$table.="<td class='text-center'><strong>".number_format($Tplant_quantity,2)."</strong></td>"; 
			$totalAmountC=$totalAmountC+$Tcquantity;
			$totalAmountEPR=$totalAmountEPR+$Tplant_quantity;
		}
		$table.="<td class='text-center'><strong>".number_format($totalAmountC,2)."</strong></td>"; 
		$table.="<td class='text-center'><strong>".number_format($totalAmountEPR,2)."</strong></td>"; 
		$table.="</tr>"; 
		$table.="</tbody>";
	}
	echo $table;
?>



