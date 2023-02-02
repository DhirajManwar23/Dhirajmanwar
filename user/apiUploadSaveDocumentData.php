<?php

	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";
	$commonfunction=new Common();
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	$settingValueUserImagePathEPRServicesDocument=$commonfunction->getSettingValue("UserImagePathEPRSDocument");
	$settingValueOngoingStatus=$commonfunction->getSettingValue("Ongoing Status");
	$settingValueAwaitingStatus=$commonfunction->getSettingValue("Awaiting Status");

	$id=$_POST["tempid"];
	$valpoid=$_POST["valpoid"];
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
		}
		else{
			echo "error7\n";
		}   
	}
	
	$qry2="select count(*) as cnt from tw_auditor_po_details where po_id='".$valpoid."'";
	$auditor_idcnt = $sign->select($qry2);
	if($auditor_idcnt!=0){
		$qry1="select auditor_id from tw_auditor_po_details where po_id='".$valpoid."'";
		$auditor_id = $sign->selectF($qry1,"auditor_id");
		
		if($auditor_id=="-1"){
			$qry="Update tw_temp set grnfile = '".$Imgname1."',invoicefile = '".$Imgname2."',wbsfile = '".$Imgname3."',pwbsfile = '".$Imgname4."',vehiclefile = '".$Imgname5."',ewayfile = '".$Imgname6."',lrfile = '".$Imgname7."',Status = '".$settingValueAwaitingStatus."' where id='".$id."'";
		}
		else{
			$qry="Update tw_temp set grnfile = '".$Imgname1."',invoicefile = '".$Imgname2."',wbsfile = '".$Imgname3."',pwbsfile = '".$Imgname4."',vehiclefile = '".$Imgname5."',ewayfile = '".$Imgname6."',lrfile = '".$Imgname7."',Status = '".$settingValueOngoingStatus."' where id='".$id."'";
		}
		$retVal = $sign->FunctionQuery($qry);
		if($retVal=="Success"){
			echo "Success";
		}
		else{
			echo "error";
		}
	}
	else{
		echo "setauditor";
	}
	
	
	
?>