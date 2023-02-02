<?php 
session_start();
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
$settingValueUserImagePathOther =$commonfunction->getSettingValue("UserImagePathOther");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status"); 
$po_id = $_SESSION["EPRServicePOId"];
date_default_timezone_set("Asia/Kolkata");
$date=date("d-m-Y h:i:sa");
$created_by=$_SESSION["company_id"];
$ip_address= $commonfunction->getIPAddress();

if(isset($_FILES['file']['name'])){
   // file name
   $filename = $_FILES['file']['name'];

   // Location
   $location = $settingValueUserImagePathOther.$filename;

   // file extension
   $file_extension = pathinfo($location, PATHINFO_EXTENSION);
   $file_extension = strtolower($file_extension);

   // Valid extensions
   $valid_ext = array("pdf","doc","docx","jpg","png","jpeg","csv","xlsx");

   $response = 0;
   $eprpurchase_invoice_date = "";
   $epraggregator_wbs_date = "";
   $eprplant_wbs_date = "";
   $eprlr_date = "";
   if(in_array($file_extension,$valid_ext)){
      // Upload file
      if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
         $response = 1;
			$file = fopen($location, "r");
			$count = 1;                                        
			while (($emapData = fgetcsv($file)) !== FALSE)
			{
				
				if($count>1){    
					
				   if($emapData[5]!=""){
					  $eprpurchase_invoice_date=date("Y-m-d H:i:s", strtotime($emapData[5]));
				   }
				   else{
					   $eprpurchase_invoice_date="";
				   }
				   if($emapData[11]!=""){
					   $epraggregator_wbs_date=date("Y-m-d H:i:s", strtotime($emapData[11]));
				   }
				   else{
					   $epraggregator_wbs_date="";
				   }
				   
				   if($emapData[13]!=""){
					   $eprplant_wbs_date=date("Y-m-d H:i:s", strtotime($emapData[13]));
				   }
				   else{
					   $eprplant_wbs_date="";
				   }
				   if($emapData[17]!=""){
					   $eprlr_date=date("Y-m-d H:i:s", strtotime($emapData[17]));
				   }
				   else{
					   $eprlr_date="";
				   }
				   $date=date("Y-m-d H:i:s", strtotime($date));
				   if($emapData[0]=="" && $emapData[1]=="" && $emapData[2]=="" && $emapData[3]=="" && $emapData[5]=="" && $emapData[6]=="" && $emapData[7]=="" && $emapData[8]=="" && $emapData[9]=="" && $emapData[11]=="" && $emapData[13]=="" &&  $emapData[14]=="" &&  $emapData[15]==""&&  $emapData[16]==""&&  $emapData[17]==""&&  $emapData[18]==""){
					   
				   }
				   else{
					  $sql = "INSERT into tw_temp(aggeragator_name,gst,grn_number, type_of_submission,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,plant_wbs_number,plant_wbs_date,vehicle_number,eway_bill_number,lr_number,lr_date,po_id,status,category_name,material_name,supply_type,trader_name,trader_add,trader_gst,trader_contact_person,trader_contact,created_by,created_on,created_ip) values ('".$emapData[0]."','".$emapData[1]."','".$emapData[2]."','".$emapData[3]."','".$emapData[4]."','".$eprpurchase_invoice_date."','".$emapData[6]."','".$emapData[7]."','".$emapData[8]."','".$emapData[9]."','".$emapData[10]."','".$epraggregator_wbs_date."','".$emapData[12]."','".$eprplant_wbs_date."','".$emapData[14]."','".$emapData[15]."','".$emapData[16]."','".$eprlr_date."','".$po_id."','".$settingValuePendingStatus."','".$emapData[18]."','".$emapData[19]."','".$emapData[20]."','".$emapData[21]."','".$emapData[22]."','".$emapData[23]."','".$emapData[24]."','".$emapData[25]."','".$created_by."','".$date."','".$ip_address."')";
					   $retVal1 = $sign-> FunctionQuery($sql,true);
				   }
				}  
				$count++;
							
			} 
      } 
	  
   }


   echo $response;
   exit;
}
?>