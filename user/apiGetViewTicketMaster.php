<?php
session_start();
// Include class definition
require "function.php";
$sign=new Signup();
$status=$sign->escapeString($_POST["status"]);

if (empty($status)){
	$qry="select id,subject,ticket_date,status from tw_ticket_system order by id Desc";
	$qry1="select count(*) as cnt from tw_ticket_system";
}else {
	$qry="select id,subject,ticket_date,status from tw_ticket_system where status = '".$status."' order by id Desc";
	$qry1="select count(*) as cnt from tw_ticket_system where status ='".$status."'";
}

$retVal = $sign->FunctionJSON($qry);

$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Subject</th><th>Ticket date</th><th>Status</th><th>View</th></tr></thead><tbody>";

while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$subject = $decodedJSON2->response[$count]->subject;
	$count=$count+1;
	$ticket_date = $decodedJSON2->response[$count]->ticket_date;
	$count=$count+1;
	$status = $decodedJSON2->response[$count]->status;
	$count=$count+1;
	$qry2="select verification_status from tw_verification_status_master where id='".$status."' order by id Desc";
	$retVal2 = $sign->SelectF($qry2,"verification_status");


		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$subject."</td>";
		$table.="<td>".date("d-m-Y H:i:s",strtotime($ticket_date))."</td>";
		$table.="<td>".$retVal2."</td>";
		
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'><i class='ti-eye'></a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	