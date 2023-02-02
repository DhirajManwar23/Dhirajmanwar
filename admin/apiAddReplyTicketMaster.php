<?php
	session_start();
	// Include class definition
	require "function.php";
	require "commonFunctions.php";
	$sign=new Signup();
	$commonfunction=new Common();
	$ip_address= $commonfunction->getIPAddress();
	
	$reply=$sign->escapeString($_POST["reply"]);
	$ticket_id= $_POST['ticket_id'];
	$reply_attachment=$_POST["reply_attachment"];
	
	date_default_timezone_set("Asia/Kolkata");
	$date=date("d-m-Y h:i:sa");
	$reply_by="ADMIN";

	$replier_id=$_SESSION["admin_id"];
	
	$qry1="insert into tw_ticket_reply (ticket_id,reply,reply_attachment,reply_by,replier_id,created_by,created_on,created_ip) values('".$ticket_id."','".$reply."','".$reply_attachment."','".$reply_by."','".$replier_id."','".$replier_id."','".$date."','".$ip_address."')";
	//$qry1="insert into tw_ticket_reply (ticket_id,reply,created_by,created_on,created_ip) values('".$ticket_id."','".$reply."','".$created_by."','".$date."','".$ip_address."')";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
	

	
?>
