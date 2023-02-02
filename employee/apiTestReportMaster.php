
<?php
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	
	$company_id = $_SESSION["company_id"];
	
	$qry="select id,description,norms,priority,visibility from tw_test_report_designation_master where company_id='".$company_id."' order by id Desc";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON2 = json_decode($retVal);

	$qry1="Select count(*) as cnt from tw_test_report_designation_master where company_id='".$company_id."' order by id Desc";
	$retVal1 = $sign->Select($qry1);

	
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>SR.NO</th><th>Description</th><th>Norms</th><th>Priority</th><th>Visibility</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$description = $decodedJSON2->response[$count]->description;
	$count=$count+1;
	$norms  = $decodedJSON2->response[$count]->norms ;
	$count=$count+1;
	$priority = $decodedJSON2->response[$count]->priority;
	$count=$count+1;
	$visibility = $decodedJSON2->response[$count]->visibility;
	$count=$count+1;
	
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$description."</td>";
		$table.="<td>".$norms."</td>";
		$table.="<td>".$priority."</td>";
		$table.="<td>".$visibility."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	



