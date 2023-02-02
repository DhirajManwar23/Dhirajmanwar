<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();


$ewayid=$_REQUEST["id"];

$qry = "SELECT sender_company_id,receiver_company_id,outward_id,valid_upto,mode_transportation,mode_transportation,approx_distance,eway_bill_type,transaction_type,address_id_sender,address_id_receiver,transporter_id,vehicle_id,created_on from  tw_material_outward_eway WHERE id = ".$ewayid." ";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$sender_company_id= $decodedJSON->response[0]->sender_company_id;
$receiver_company_id = $decodedJSON->response[1]->receiver_company_id; 
$outward_id = $decodedJSON->response[2]->outward_id; 
$valid_upto = $decodedJSON->response[3]->valid_upto;
$mode_transportation = $decodedJSON->response[4]->mode_transportation;
$approx_distance = $decodedJSON->response[5]->approx_distance;
$eway_bill_type = $decodedJSON->response[6]->eway_bill_type;
$transaction_type = $decodedJSON->response[7]->transaction_type;
$address_id_sender = $decodedJSON->response[8]->address_id_sender;
$address_id_receiver = $decodedJSON->response[9]->address_id_receiver;
$transporter_id = $decodedJSON->response[10]->transporter_id;
$vehicle_id = $decodedJSON->response[11]->vehicle_id;
$created_on = $decodedJSON->response[12]->created_on;

$srno = $ewayid;
	
/* // Code to convert Rs from numbers to word in php -->
$inwords="";
$number=round($amount_received);
$no = (int)floor($number);
$point = (int)round(($number - $no) * 100);
$hundred = null;
$digits_1 = strlen($no);
$i = 0;
$str = array();
$words = array('0' => '', '1' => 'One', '2' => 'Two',
	'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
	'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
	'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
	'13' => 'Thirteen', '14' => 'Fourteen',
	'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
	'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
	'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
	'60' => 'Sixty', '70' => 'Seventy',
	'80' => 'Eighty', '90' => 'Ninety');
	$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
while ($i < $digits_1) {
	$divider = ($i == 2) ? 10 : 100;
	$number = floor($no % $divider);
	$no = floor($no / $divider);
	$i += ($divider == 10) ? 1 : 2;


	if ($number) {
		$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
		$hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
		$str [] = ($number < 21) ? $words[$number] .
		" " . $digits[$counter] . $plural . " " . $hundred
		:
		$words[floor($number / 10) * 10]
		. " " . $words[$number % 10] . " "
		. $digits[$counter] . $plural . " " . $hundred;
	} else $str[] = null;
}
$str = array_reverse($str);
$result = implode('', $str);


if ($point > 20) {
	$points = ($point) ?
	"" . $words[floor($point / 10) * 10] . " " . 
	$words[$point = $point % 10] : ''; 
} else {
	$points = $words[$point];
}
if($points != ''){        
	$inwords.=$result . "Rupees  " . $points . " Paise Only";
} else {
	$inwords.=$result . "Rupees Only";
}
 */	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Trace Waste | e-Way Bill</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- crop image -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.css" />
  <!-- fomt awesome/5 -->
 <!-- fomt awesome/5 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

<script type="text/javascript" src="scripts.js"></script>
<link rel="stylesheet" href="styles.css">
  <style>
   .navbar-custom-menu, .main-header .navbar-right {
    float: right;
}
@media (min-width: 768px)
.navbar-nav {
    float: left;
    margin: 0;
}

.navbar-nav {
    margin: 7.5px -15px;
}
.nav {
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
}
.navbar-custom-menu>.navbar-nav>li {
    position: relative;
}
@media (min-width: 768px)
.navbar-nav>li {
    float: left;
}
.nav>li {
    position: relative;
    display: block;
}
.navbar-custom-menu>.navbar-nav>li {
    position: relative;
}
@media (min-width: 768px)
.navbar-nav>li {
    float: left;
}
.nav>li {
    position: relative;
    display: block;
}
.dropdown, .dropup {
    position: relative;
}
 .navbar .nav>li>a {
    color: #fff;
}
@media (min-width: 768px)
.navbar-nav>li>a {
    padding-top: 15px;
    padding-bottom: 15px;
}
.navbar-nav>li>a {
    padding-top: 10px;
    padding-bottom: 10px;
    line-height: 20px;
}
.nav>li>a {
    position: relative;
    display: block;
    padding: 10px 15px;
}
 .navbar .nav>li>a>.label {
    position: absolute;
    top: 9px;
    right: 7px;
    text-align: center;
    font-size: 9px;
    padding: 2px 3px;
    line-height: .9;
}
.navbar-custom-menu>.navbar-nav>li>.dropdown-menu {
    position: absolute;
    right: 0;
    left: auto;
}
.navbar-nav>.notifications-menu>.dropdown-menu, .navbar-nav>.messages-menu>.dropdown-menu, .navbar-nav>.tasks-menu>.dropdown-menu {
    width: 280px;
    padding: 0 0 0 0;
    margin: 0;
    top: 100%;
}
.navbar-nav>li>.dropdown-menu {
    margin-top: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.open>.dropdown-menu {
    display: block;
}
.dropdown-menu {
    box-shadow: none;
    border-color: #eee;
}
.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 14px;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
}
.navbar-nav>.messages-menu>.dropdown-menu>li.header, .navbar-nav>.tasks-menu>.dropdown-menu>li.header {
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    background-color: #ffffff;
    padding: 7px 10px;
    border-bottom: 1px solid #f4f4f4;
    color: #444444;
    font-size: 14px;
}
.navbar-nav>.notifications-menu>.dropdown-menu>li, .navbar-nav>.messages-menu>.dropdown-menu>li, .navbar-nav>.tasks-menu>.dropdown-menu>li {
    position: relative;
}
.navbar-nav>.messages-menu>.dropdown-menu>li .menu, .navbar-nav>.tasks-menu>.dropdown-menu>li .menu {
    max-height: 200px;
    margin: 0;
    padding: 0;
    list-style: none;
    overflow-x: hidden;
}
.pull-left {
    float: left;
}
.pull-left {
    float: left!important;
}
.navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a {
    margin: 0;
    padding: 10px 10px;
}
.navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li>a, .navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a, .navbar-nav>.tasks-menu>.dropdown-menu>li .menu>li>a {
    display: block;
    white-space: nowrap;
    border-bottom: 1px solid #f4f4f4;
}
a {
    color: #3c8dbc;
}
a {
    color: #337ab7;
    text-decoration: none;
}
a {
    background-color: transparent;
}
.pull-left {
    float: left!important;
}
.pull-left {
    float: left;
}
.pull-left {
    float: left;
}
.navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a>h4 {
    padding: 0;
    margin: 0 0 0 45px;
    color: #444444;
    font-size: 15px;
    position: relative;
}
.navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a>h4>small {
    color: #999999;
    font-size: 10px;
    position: absolute;
    top: 0;
    right: 0;
}
.navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a>p {
    margin: 0 0 0 45px;
    font-size: 12px;
    color: #888888;
}
}

.navbar-nav>.messages-menu>.dropdown-menu>li .menu>li>a>div>img {
    margin: auto 10px auto auto;
    width: 40px;
    height: 40px;
}
.img-circle {
    border-radius: 50%;
	margin: auto 10px auto auto;
    width: 40px;
    height: 40px;
}
img {
    vertical-align: middle;
}
img {
    border: 0;
}
.footers {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 4px;
    border-bottom-left-radius: 4px;
    font-size: 12px;
    background-color: #fff;
    padding: 7px 10px;
    border-bottom: 1px solid #eeeeee;
    color: #444 !important;
    text-align: center;
}
table,th,td{
	border: 1px solid;
}

@media only screen and (max-width: 600px) {
	.tabled{text-align:center;width:1000px;}
	.SR{text-align:center;width:20px;}
	.Description{text-align:center;width:280px;}
	.HSN{text-align:center;width:50px;}
	.TaxRate{text-align:center;width:50px;}
	.Qty{text-align:center;width:50px;}
	.Unit{text-align:center;width:100px;}
	.CGST{text-align:center;width:100px;}
	.SGST{text-align:center;width:100px;}
	.IGST{text-align:center;width:100px;}
	.Net Amount{text-align:center;width:100px;}
	.TotalAmount{text-align:center;width:100px;}
	.desktopmobile{text-align: left;}
	.mobile{display: block;}
	.desktop{display: none;}
}
@media only screen and (min-width: 768px) {
	.tabled{text-align:center;width:100%;}
	.SR{text-align:center;width:auto;}
	.Description{text-align:center;width:auto;}
	.HSN{text-align:center;width:auto;}
	.TaxRate{text-align:center;width:auto;}
	.Qty{text-align:center;width:auto;}
	.Unit{text-align:center;width:auto;}
	.CGST{text-align:center;width:auto;}
	.SGST{text-align:center;width:auto;}
	.IGST{text-align:center;width:auto;}
	.Net Amount{text-align:center;width:auto;}
	.TotalAmount{text-align:center;width:auto;}
	.desktopmobile{text-align: right;}
	.mobile{display: none;}
	.desktop{display: block;}
}
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
    .yes-print, .yes-print *
    {
        display: block !important;
    }
	.desktopprint, .desktopprint *
    {
       text-align: right;
    }
	
	
}
  </style> 
  
  
  

</head>

<body>
	<main id="main">

	<!-- ======= Contact Section ======= -->
   <section id="contact" class="contact">
      <div class="container">

    
		 
		<div class="row" data-aos="fade-in">
		
		  <div class="col-lg-12">
		  
				<div class="tabbable-panel">
					<div class="tab-content">
						<div class="tab-pane active" id="tab_default_1">
						
							<div id="printableArea">
							<div class="php-email-form" style='margin:15px;'>
							
							<div class="row" style="background-color:#FCFCFC;padding:10px;border-color: #CBCBCD;overflow-x:auto;">
								<table style="width:100%;">
								
								<tr>
									<td colspan= "6" >1.E-WAY BILL Details</td>
								</tr>
								<tr>
									<td colspan="2">e-Way Bill No : <?php echo $ewayid; ?>
									<br>Mode : <?php echo $mode_transportation; ?>
									<br>Type : Outward - <?php echo $eway_bill_type; ?></td>
									<td colspan="2">Generated Date : <?php echo $created_on; ?>
									<br>Approx Distance : <?php echo $approx_distance; ?>
									<br>Document Details : Tax Invoice - <?php echo $eway_bill_type; ?></td>
									<td colspan="2">Generated By : <?php echo $srno; ?>
									<br>Valid Upto : <?php echo $valid_upto; ?></td>
									
								</tr>
								<tr>
									<td colspan= "6" >2.Address Details</td>
								</tr>
								<tr>
									<td>Gross Weight</td>
									<td><?php echo $gross_weight; ?></td>
									<td colspan= "6" ><?php echo $gross_weight_date_time; ?></td>
								</tr>
								<tr>
									<td>Tare Weight</td>
									<td><?php echo $tare_weight; ?></td>
									<td colspan= "6" ><?php echo $tare_weight_date_time; ?></td>
								</tr>
								<tr>
									<td>Net Weight</td>
									<td colspan="6"><?php echo $net_weight; ?></td>
								</tr>
								<tr>
									<td>Amount</td>
									<td colspan="6"><?php echo $amount_received; ?></td>
								</tr>
								<tr>
									<td colspan="6"><?php echo $inwords; ?></td>
								</tr>
								<tr>
									<td colspan= "6"><font style="font-size: 12px;font-weight:900;"><center>This is a computer generated invoice and does not require signature</center></font></td>
								</tr>
								
								</table>
							</div>
							
							</div><br>
							</div><br>
							<div class="text-center"><button type="submit" onclick="printDiv('printableArea')" style="background: #149ddd;border: 0; padding: 10px 24px; color: #fff; transition: 0.4s; border-radius: 4px;">Print</button></div>
						</div>
					
					</div>
			</div>
				
			
          </div>

        </div>
	   
    </section> <!--End Contact Section -->

  </main><!-- End #main -->

  <?php //include_once "footerafterlogin.php"?>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <!--<script src="assets/vendor/php-email-form/validate.js"></script>-->
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/typed.js/typed.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
	<!-- crop image -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
  
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  

  <script>
function printDiv(printableArea) {
    var printContents = document.getElementById(printableArea).innerHTML;
    var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}

</script>

</body>

</html>