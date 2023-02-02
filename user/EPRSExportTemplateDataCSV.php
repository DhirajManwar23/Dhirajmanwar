<?php  
session_start();
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$filename = 'EPRSExportTemplateDataCSV.csv';
$fp = fopen($filename, 'w');
$delimiter = ",";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
$fields = array("id","poid","company_name","aggeragator_name","gst","category_name","material_name","grn_number","type_of_submission","purchase_invoice_number","purchase_invoice_date","dispatched_state","dispatched_place","invoice_quantity","plant_quantity","aggregator_wbs_number","aggregator_wbs_date","plant_wbs_number","plant_wbs_date","vehicle_number","eway_bill_number","lr_number","lr_date","supply_type","recycler_name","recycler_add","recycler_gst","recycler_contact_person","recycler_contact");
fputcsv($fp, $fields, $delimiter);
fclose($fp);
echo "success";
?> 