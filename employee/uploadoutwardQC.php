<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();
/* $company_id = $_SESSION["employee_id"];
$query = "select value from tw_employee_contact where company_id = '".$company_id."' and contact_field='1'";
$retVal = $sign->SelectF($query,'value'); */

//upload.php
 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_ProofQC']["tmp_name"]))
{
 //$test = explode('.', $_FILES["Document_ProofQC"]["name"]);
 $name = ($_FILES["Document_ProofQC"]["name"]);
 //$ext = end($test);
// $name = date("dmYhis"). '.' . $ext;
 $location = '../assets/images/Documents/Employee/Outward/' . $name;  
 move_uploaded_file($_FILES["Document_ProofQC"]["tmp_name"], $location);
 echo $name; 

}else{
	echo "not found";
} 
?>
