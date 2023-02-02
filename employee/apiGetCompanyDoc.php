<?php
session_start();
include_once "function.php";	
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$supplier_id=$_POST['id'];
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePANCard= $commonfunction->getSettingValue("PANCard");
$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");
$Pan="";
$GST="";

 $DocPanQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$supplier_id."' AND document_type='".$settingValuePANCard."'  ORDER by document_type ASC ";

$Pan = $sign->SelectF($DocPanQry,"document_number");

$DocGstQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$supplier_id."' AND document_type='".$settingValueGSTDocuments."'  ORDER by document_type ASC ";

$GST = $sign->SelectF($DocGstQry,"document_number");
// $decodedJSON = json_decode($Doc);
// $Pan=$decodedJSON->response[0]->document_number;
//$GST=$decodedJSON->response[1]->document_number;

$ValueQry="SELECT value FROM tw_company_contact where company_id='".$supplier_id."' and contact_field='".$settingValuePemail."'";
$Value=$sign->SelectF($ValueQry,"value");
$SupplierNoQry="SELECT count(supplier_no) as cnt,IFNULL(supplier_no,'NA') as supplier_no FROM tw_supplier_info where supplier_id='".$supplier_id."'";
$SupplierNo=$sign->SelectF($SupplierNoQry,"supplier_no");

$CompanyDetails=array();
 //array_push($CompanyDetails,$returnADD,$google_map);
 array_push($CompanyDetails,$Pan, $GST, $Value, $SupplierNo);
 echo json_encode($CompanyDetails);
?>