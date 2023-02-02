<?php
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	
	$company_id = $_SESSION["company_id"];
	
	$qry="select id,company_id,employee_id,form_type,approved_by,prepared_by,for_company from tw_digital_signature  where company_id='".$company_id."' order by id desc";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON2 = json_decode($retVal);

	$qry1="Select count(*) as cnt from tw_digital_signature where company_id='".$company_id."'";
	$retVal1 = $sign->Select($qry1);

	
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>#</th><th>Form Type</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$company_id = $decodedJSON2->response[$count]->company_id;
		$count=$count+1;
		$employee_id  = $decodedJSON2->response[$count]->employee_id ;
		$count=$count+1;
		$form_type = $decodedJSON2->response[$count]->form_type;
		$count=$count+1;
		$approved_by = $decodedJSON2->response[$count]->approved_by;
		$count=$count+1;
		$prepared_by = $decodedJSON2->response[$count]->prepared_by;
		$count=$count+1;
		$for_company = $decodedJSON2->response[$count]->for_company;
		$count=$count+1;
		
		$companyQry="SELECT CompanyName FROM tw_company_details where id='".$company_id."'";
		$companyname=$sign->SelectF($companyQry,"CompanyName");	
		
		$formQry="SELECT printable_document_value FROM tw_printable_document_master  where id='".$form_type."'";
		$form=$sign->SelectF($formQry,"printable_document_value");
		
			$table.="<tr>";
			$table.="<td>".$it."</td>";
			$table.="<td>".$form."</td>";
			$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
			$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
			$it++;
			$table.="</tr>";
			

		$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	



