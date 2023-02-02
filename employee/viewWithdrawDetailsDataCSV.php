<?php  
	session_start();
	include("db_connect.php");
	$db=new DB_Connect();
	$con=$db->connect();

	//$date = $_POST["date"];
	$type = $_POST["type"];
	$orgDate = $_POST["date"];  
	$newDate = date("d-m-Y", strtotime($orgDate));  

	$filename = 'excel/viewWithdrawrDetailsDataExport.csv';
	$fp = fopen($filename, 'w');
	
	$header = array();
	array_push($header,"ID","date","Name","Amount","Status","Details");
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
	fputcsv($fp, $header);
	
	if($type!=""){
		$subqry = " where status='".$_POST["type"]."' and created_on Like '%".$newDate."%'";
	}
	else
	{
		$subqry = "";
		
	}
	
	
	$query = "SELECT ID,created_on,userid,amount,status,rejectreason FROM ew_transactionuserwithdrawal ".$subqry." order by ID desc";
	$result = mysqli_query($con, $query);
	//echo $query;
	while($row = mysqli_fetch_row($result))
	{
		fputcsv($fp, $row);
	}
	fclose($fp);
	//echo $query;
	echo "success";
?> 