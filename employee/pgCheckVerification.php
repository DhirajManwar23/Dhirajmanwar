<?php
include("commonFunctions.php");
include("function.php");
$commonfunction=new Common();
$_REQUEST["u1"];
$dec_email= $commonfunction->CommonDec($_REQUEST["u1"]);
urldecode($dec_email);
$dec_token= $commonfunction->CommonDec($_REQUEST["v2"]);

$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueExpiredStatus= $commonfunction->getSettingValue("Expired");
 //echo $enc_email;
 //echo $dec_token;
$qry="SELECT count(*) as cnt FROM tw_verify_email where email='".$dec_email."' and token='".$dec_token."' and  user_type='Employee' and status='".$settingValueExpiredStatus."' ";
$sign=new Signup();
$retVal = $sign->Select($qry);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
</head>
<body>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/sweetAlert.js"></script><!-- TW written code -->
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script>
 $(document).ready(function(){
	var valretVal = "<?php echo $retVal; ?>";
	var valsettingValueVerifiedStatus = "<?php echo $settingValueVerifiedStatus; ?>";
	var valdec_email = "<?php echo $dec_email; ?>";
	var valdec_Toekn = "<?php echo $dec_token; ?>";
	if(valretVal>0){
		console.log(valdec_Toekn);
		var url = "pgExpireLink.php";
		$(location).attr('href',url);
		
	}
	else{
		var valquery="update tw_employee_contact SET status='"+valsettingValueVerifiedStatus+"' WHERE  value='"+valdec_email+"'";
		
		
		$.ajax({
		type:"POST",
		url:"apiCommonQuerySingle.php",
		data:{valquery:valquery},
		success:function(response){
			if($.trim(response)=="Success"){
				var url = "pgEmailVerifed.php?Email="+valdec_email+"&&Token="+valdec_Toekn;
				$(location).attr('href',url);
			}else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	});

	}
		 
  }); 
</script>
</body>
</html>