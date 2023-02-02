<?php
	session_start();
	// Include class definition
	include_once "function.php";
	include_once "commonFunctions.php";
	$sign=new Signup();
	$commonfunction=new Common();
	
	$transporter_id=$_REQUEST["transporter_id"];
	$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");	
	$settingValueEmployeePrimaryEmail = $commonfunction->getSettingValue("Primary Email");	
	$employee_id=$_SESSION["employee_id"] ;
	
	

	$qry="select id,document_type,document_number,document_proof from tw_transporter_kyc where transporter_id='".$transporter_id."' order by id Desc";
	$retVal = $sign->FunctionJSON($qry);

	
	$EmailQry="SELECT value FROM tw_employee_contact where employee_id='".$employee_id."' AND contact_field='".$settingValueEmployeePrimaryEmail."'";
	$Email=$sign->SelectF($EmailQry,"value");

	$qry1="Select count(*) as cnt from tw_transporter_kyc where transporter_id='".$transporter_id."'";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>SR.NO</th><th>Document Type</th><th>Document Number</th><th>Document Proof</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$document_type = $decodedJSON2->response[$count]->document_type;
	$count=$count+1;
	$document_number  = $decodedJSON2->response[$count]->document_number;
	$count=$count+1;
	$document_proof = $decodedJSON2->response[$count]->document_proof;
	$count=$count+1;
	
	if(!empty($document_proof)){

	    $document_proof = "<a href='".$settingValueEmployeeImagePathVerification.$Email.'/'.$document_proof."'; target='_blank'> View <a/>";
	 }
		
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$document_type."</td>";
		$table.="<td>".$document_number."</td>";
		$table.="<td>".$document_proof."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	