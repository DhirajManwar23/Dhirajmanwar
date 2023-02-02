<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();
$id = $_POST["id"];
 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_ProofPhotos'.$id]["tmp_name"]))
{
	 $name = ($_FILES["Document_ProofPhotos".$id]["name"]);

	 $location = '../assets/images/Documents/Employee/Outward/'. $name;  
	 move_uploaded_file($_FILES["Document_ProofPhotos".$id]["tmp_name"], $location);
	 echo $name; 

}else{
	echo "not found";
} 
?>
