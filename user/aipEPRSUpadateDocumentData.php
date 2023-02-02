<?php
	session_start();
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";
	$commonfunction=new Common();
	date_default_timezone_set("Asia/Kolkata");
	$settingValueUserImagePathEPRServicesDocument=$commonfunction->getSettingValue("UserImagePathEPRSDocument");
	$settingValueOngoingStatus=$commonfunction->getSettingValue("Ongoing Status");
	$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
	$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
	$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
	$settingValueRejectedByAuditor= $commonfunction->getSettingValue("RejectedByAuditor");

	$cur_date=date("d-m-Y h:i:sa");
	$created_by=$_SESSION["company_id"];
	$ip_address= $commonfunction->getIPAddress();

	$id=$_POST["po_id"];
	$valstatusvalue=$_POST["valstatusvalue"];
	$image1=$_POST["Img1"];
	$image2=$_POST["Img2"];
	$image3=$_POST["Img3"];
	$image4=$_POST["Img4"];
	$image5=$_POST["Img5"];
	$image6=$_POST["Img6"];
	$image7=$_POST["Img7"];
	$Img1Ext=$_POST["Img1Ext"];
	$Img2Ext=$_POST["Img2Ext"];
	$Img3Ext=$_POST["Img3Ext"];
	$Img4Ext=$_POST["Img4Ext"];
	$Img5Ext=$_POST["Img5Ext"];
	$Img6Ext=$_POST["Img6Ext"];
	$Img7Ext=$_POST["Img7Ext"];
	$aggeragator_name=$_POST["aggeragator_name"];
	$gst=$_POST["gst"];
	$grn_number=$_POST["grn_number"];
	$type_of_submission=$_POST["type_of_submission"];
	$purchase_invoice_number=$_POST["purchase_invoice_number"];
	$purchase_invoice_date=$_POST["purchase_invoice_date"];
	$dispatched_state=$_POST["dispatched_state"];
	$dispatched_place=$_POST["dispatched_place"];
	$invoice_quantity=$_POST["invoice_quantity"];
	$plant_quantity=$_POST["plant_quantity"];
	$aggregator_wbs_number=$_POST["aggregator_wbs_number"];
	$aggregator_wbs_date=$_POST["aggregator_wbs_date"];
	$plant_wbs_number=$_POST["plant_wbs_number"];
	$plant_wbs_date=$_POST["plant_wbs_date"];
	$vehicle_number=$_POST["vehicle_number"];
	$eway_bill_number=$_POST["eway_bill_number"];
	$lr_number=$_POST["lr_number"];
	$lr_date=$_POST["lr_date"];
	$category_name=$_POST["category_name"];
	$material_name=$_POST["material_name"];


	$image_no1=$id."-GRN-".time();//or Anything You Need
	$image_no2=$id."-Invoice-".time();//or Anything You Need
	$image_no3=$id."-WBS-".time();//or Anything You Need
	$image_no4=$id."-PWBS-".time();//or Anything You Need
	$image_no5=$id."-Vehicle-".time();//or Anything You Need
	$image_no6=$id."-Eway-".time();//or Anything You Need
	$image_no7=$id."-LR-".time();//or Anything You Need
	
	$path1 = $settingValueUserImagePathEPRServicesDocument.$image_no1.".".$Img1Ext;
	$path2 = $settingValueUserImagePathEPRServicesDocument.$image_no2.".".$Img2Ext;
	$path3 = $settingValueUserImagePathEPRServicesDocument.$image_no3.".".$Img3Ext;
	$path4 = $settingValueUserImagePathEPRServicesDocument.$image_no4.".".$Img4Ext;
	$path5 = $settingValueUserImagePathEPRServicesDocument.$image_no5.".".$Img5Ext;
	$path6 = $settingValueUserImagePathEPRServicesDocument.$image_no6.".".$Img6Ext;
	$path7 = $settingValueUserImagePathEPRServicesDocument.$image_no7.".".$Img7Ext;
	
	$Imgname1 = "";
	$Imgname2 = "";
	$Imgname3 = "";
	$Imgname4 = "";
	$Imgname5 = "";
	$Imgname6 = "";
	$Imgname7 = "";
	if($image1!=""){
		$status1 = file_put_contents($path1,base64_decode($image1));
		$Imgname1 = $image_no1.".".$Img1Ext;
		if($status1){
			//echo $image_no1.".jpg\n";
			$qry1="Update tw_temp set grnfile = '".$Imgname1."' where id='".$id."'";
			$retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1!="Success"){
				echo "error";
			}
		}
		else{
			echo "error1\n";
		}  
	}
	if($image2!=""){
		$status2 = file_put_contents($path2,base64_decode($image2));
		$Imgname2 = $image_no2.".".$Img2Ext;
		if($status2){
			//echo $image_no2.".jpg\n";
			$qry2="Update tw_temp set invoicefile = '".$Imgname2."' where id='".$id."'";
			$retVal2 = $sign->FunctionQuery($qry2);
			if($retVal2!="Success"){
				echo "error";
			}
		}
		else{
			echo "error2\n";
		}  
	}
	if($image3!=""){
		$status3 = file_put_contents($path3,base64_decode($image3));
		$Imgname3 = $image_no3.".".$Img3Ext;
		if($status3){
			//echo $image_no3.".jpg\n";
			$qry3="Update tw_temp set wbsfile = '".$Imgname3."' where id='".$id."'";
			$retVal3 = $sign->FunctionQuery($qry3);
			if($retVal3!="Success"){
				echo "error";
			}
		}
		else{
			echo "error3\n";
		}  
	}
	if($image4!=""){
		$status4 = file_put_contents($path4,base64_decode($image4));
		$Imgname4 = $image_no4.".".$Img4Ext;
		if($status4){
			//echo $image_no4.".jpg\n";
			$qry4="Update tw_temp set pwbsfile = '".$Imgname4."' where id='".$id."'";
			$retVal4 = $sign->FunctionQuery($qry4);
			if($retVal4!="Success"){
				echo "error";
			}
		}
		else{
			echo "error4\n";
		}  
	}
	if($image5!=""){
		$status5 = file_put_contents($path5,base64_decode($image5));
		$Imgname5 = $image_no5.".".$Img5Ext;
		if($status5){
			//echo $image_no4.".jpg\n";
			$qry5="Update tw_temp set vehiclefile = '".$Imgname5."' where id='".$id."'";
			$retVal5 = $sign->FunctionQuery($qry5);
			if($retVal5!="Success"){
				echo "error";
			}
		}
		else{
			echo "error4\n";
		}  
	}
	if($image6!=""){
		$status6 = file_put_contents($path6,base64_decode($image6));
		$Imgname6 = $image_no6.".".$Img6Ext;
		if($status6){
			//echo $image_no5.".jpg\n";
			$qry6="Update tw_temp set ewayfile = '".$Imgname6."' where id='".$id."'";
			$retVal6 = $sign->FunctionQuery($qry6);
			if($retVal6!="Success"){
				echo "error";
			}
		}
		else{
			echo "error5\n";
		} 
	}
	if($image7!=""){
		$status7 = file_put_contents($path7,base64_decode($image7));
		$Imgname7 = $image_no7.".".$Img7Ext;
		if($status7){
			//echo $image_no7.".jpg\n";
			$qry7="Update tw_temp set lrfile = '".$Imgname7."' where id='".$id."'";
			$retVal7 = $sign->FunctionQuery($qry7);
			if($retVal7!="Success"){
				echo "error";
			}
		}
		else{
			echo "error7\n";
		}   
	}
	if($valstatusvalue==$settingValueOngoingStatus){
		$status = $settingValueOngoingStatus;
	}
	else if($valstatusvalue==$settingValueRejectedStatus){
		$qrystatus="select rejected_by from tw_temp where id='".$id."'";
		$retValStatus = $sign->selectF($qrystatus,"rejected_by");
		if($retValStatus==$settingValueRejectedByAuditor){
			$status = $settingValueOngoingStatus;
		}
		else{
			$status = $settingValueAwaitingStatus;
		}
	}
	else{
		$status = $settingValueAwaitingStatus;
	}
	$qry="Update tw_temp set aggeragator_name = '".$aggeragator_name."' , gst = '".$gst."', grn_number = '".$grn_number."', type_of_submission = '".$type_of_submission."', purchase_invoice_number = '".$purchase_invoice_number."', purchase_invoice_date = '".$purchase_invoice_date."', dispatched_state = '".$dispatched_state."', dispatched_place = '".$dispatched_place."', invoice_quantity = '".$invoice_quantity."', plant_quantity = '".$plant_quantity."', aggregator_wbs_number = '".$aggregator_wbs_number."', aggregator_wbs_date = '".$aggregator_wbs_date."', plant_wbs_number = '".$plant_wbs_number."', plant_wbs_date = '".$plant_wbs_date."', vehicle_number = '".$vehicle_number."',eway_bill_number = '".$eway_bill_number."',lr_number = '".$lr_number."',lr_date = '".$lr_date."',category_name = '".$category_name."',material_name = '".$material_name."',status = '".$status."',modified_by='".$created_by."',modified_on='".$cur_date."',modified_ip='".$ip_address."' where id = '".$id."'";
	
	$retVal = $sign->FunctionQuery($qry);
	if($retVal=="Success"){
		echo "Success";
	}
	else{
		echo "error";
	}
	
?>