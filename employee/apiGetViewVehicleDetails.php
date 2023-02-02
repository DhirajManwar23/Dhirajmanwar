<?php
	session_start();
	// Include class definition
	include_once "function.php";
	include_once "commonFunctions.php";
	$sign=new Signup();
	$commonfunction=new Common();
	$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");	
	$settingValueEmployeePrimaryEmail = $commonfunction->getSettingValue("Primary Email");	
	
	$requestid = $_POST["id"];

    $employee_id=$_SESSION["employee_id"] ;
	$qry="select id,vehicle_type,vehicle_manufacturer,vehicle_number,vehicle_image from tw_vehicle_details_master  where transporter_id='".$requestid."' order by id Desc";
	
	$retVal = $sign->FunctionJSON($qry);

   $EmailQry="SELECT value FROM `tw_employee_contact` where employee_id='".$employee_id."' AND contact_field='".$settingValueEmployeePrimaryEmail."'";
   $Email=$sign->SelectF($EmailQry,"value");

	$qry1="Select count(*) as cnt from tw_vehicle_details_master where transporter_id='".$requestid."'  order by id Desc";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>SR.NO</th><th>Vehicle Type</th><th>Vehicle Manufacturer</th><th>Vehicle Number</th><th>Vehicle Image</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	/*$vehicle_id = $decodedJSON2->response[$count]->vehicle_id;
	$count=$count+1;*/
	$vehicle_type  = $decodedJSON2->response[$count]->vehicle_type;
	$count=$count+1;
	$vehicle_manufacturer = $decodedJSON2->response[$count]->vehicle_manufacturer;
	$count=$count+1;
	$vehicle_number  = $decodedJSON2->response[$count]->vehicle_number;
	$count=$count+1;
	$vehicle_image  = $decodedJSON2->response[$count]->vehicle_image;
	$count=$count+1;
	
	$qry3="SELECT name FROM tw_vehicle_type_master WHERE id= '".$vehicle_type."';";
	$vehicle_type_name=$sign->SelectF($qry3,"name"); 
	
	
	if(!empty($vehicle_image)){
		$vehicle_image = "<a href='".$settingValueEmployeeImagePathVerification.$Email.'/'.$vehicle_image."'; target='_blank'> View <a/>";
	}
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		//$table.="<td>".$vehicle_id."</td>";
		$table.="<td>".$vehicle_type_name."</td>";
		$table.="<td>".$vehicle_manufacturer."</td>";
		$table.="<td>".$vehicle_number."</td>";
		$table.="<td>".$vehicle_image."</td>";
		
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	