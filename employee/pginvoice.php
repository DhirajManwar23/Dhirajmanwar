<?php
session_start();



$orderid=$_REQUEST["id"];
$userid = $_REQUEST["custid"];



$numberi="Select * from Me_Order_Info where ID = '".$orderid."'";
$resulti=mysqli_query($con,$numberi);
$rowii=mysqli_fetch_array($resulti);
$numberii="Select * from Me_Customer_Info where ID = '".$userid."'";
$resultii=mysqli_query($con,$numberii);
$rowiii=mysqli_fetch_array($resultii);
$invoiceno=$rowii["invoicenumber"];

$query2="select * from Me_Order_Info where ID='".$orderid."'";
$data2=mysqli_query($con,$query2);
$setdatavendor=mysqli_fetch_array($data2);


$shipping=0;
$totalamount = $setdatavendor["Total_Amt"];
$discount=0;

// Code to convert Rs from numbers to word in php -->
$inwords="";
$number=round($totalamount);
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
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Tax Invoice/Bill of Supply/Cash Memo</title>
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
							<div class="row" style="background-color:#FCFCFC;padding:10px;border-color: #CBCBCD;">
								<div class="col-lg-6 col-md-6 col-sm-12 col-12 " >
									<div class="row mobile no-print">
										<p style="font-size: 12px;text-align: center;width: 100%;margin-bottom: 5px;"><b>Invoice/Bill of Supply/Cash Memo</b></p>
										<p style="font-size: 10px;text-align: center;width: 100%;margin-bottom: 5px;">(Original for Recipient)</p>
										<center><img src="images/logo.png" style="height: auto;width: 70%;text-align:center;"/></center>
									</div>
									<div class="row desktop yes-print">
										<img src="images/logo.png" style="height: auto;width: 25%;"/>
									</div>
									<br>
										<font style="font-size: 11px;"><b>Sold By :</b></font><br>
										
										<font style="font-size: 12px;">Mayureshwar Enterprises</font><br>

										<font style="font-size: 12px;"></font><br>
										<font style="font-size: 11px;"><b>PAN No : </b></font><font style="font-size: 12px;"></font><br>
									
									<br><br>
									<font style="font-size: 10px;"><b>Order Number : </b></font><font style="font-size: 12px;"><?php echo "O".$orderid;?></font><br>
									<font style="font-size: 10px;"><b>Order Date : </b></font><font style="font-size: 12px;"><?php echo $rowii["Order_Date"];?></font><br>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-12 desktopmobile desktopprint">
									
									<font class="desktop" style="font-size: 12px;"><b>Tax Invoice/Bill of Supply/Cash Memo</b></font><br>
									<font class="desktop" style="font-size: 10px;">(Original for Recipient)</font><br><br>
									<font style="font-size: 10px;"><b>Shipping Address : </b></font><br>
									<font style="font-size: 12px;"><?php echo $rowiii["Customer_Name"];?></font><br>
									<font style="font-size: 12px;"><?php echo $rowiii["Customer_Address"];?></font><br>
									<font style="font-size: 12px;"><?php echo $rowiii["Customer_City"]; ?></font><br>
									<font style="font-size: 10px;"><b>Invoice Number : </b></font><font style="font-size: 12px;"><?php echo $invoiceno;?></font><br>
									<font style="font-size: 10px;"><b>Invoice Date : </b></font><font style="font-size: 12px;"><?php echo $rowii["Order_Date"];?></font><br>
									
								</div>
							</div>
							<div class="row" style="background-color:#FCFCFC;padding:10px;border-color: #CBCBCD;overflow-x:auto;">
								<table style="width:100%;">
								<thead style="background-color:#CBCBCD;text-align: center;">
									<th class="SR">#</th>
									<th class="Farm_Code">Farm Code</th>
									<th class="Qty">Type</th>
									<th class="Qty">Quantity</th>
									<th class="Unit">Unit Price</th>
									<th class="TotalAmount">Total Amount</th>
								</thead>
								<?php
								
								$query3="select * from Me_Order_Details where Order_ID='".$orderid."'";
								$data3=mysqli_query($con,$query3);
								

								$div='';
								$i=1;
								$valtotalamt=0;
								while($setdatavendor3=mysqli_fetch_array($data3)){
								    
									
									
									
									$div.='<tr>';
										$div.='<td style="text-align: center;">'.$i.'</td>';
										$div.='<td><font style="font-size: 12px;">'.$setdatavendor3["Farm_Code"].'</font></td>';
										$div.='<td style="text-align: center;"><font style="font-size: 12px;">'.$setdatavendor3["Type"].' dozen</font></td>';
										$div.='<td style="text-align: center;"><font style="font-size: 12px;">'.$setdatavendor3["Quantity"].'</font></td>';
										$div.='<td style="text-align: right;"><font style="font-size: 12px;"><i class="fa fa-inr" aria-hidden="true"></i> '.number_format($setdatavendor3["Price_Per_Unit"],2).'</font></td>';
										$valtotal = $setdatavendor3["Type"]*$setdatavendor3["Quantity"]*$setdatavendor3["Price_Per_Unit"];
										$div.='<td style="text-align: right;background-color:#CBCBCD;"><font style="font-size: 12px;"><i class="fa fa-inr" aria-hidden="true"></i>'.number_format($valtotal,2).'</font></td>';
										$valtotalamt = $valtotalamt+$valtotal;
										$i++;
										$div.='</tr>';
								}
								echo $div;
								?>
								
								<tr>
									<td colspan= "5"><font style="font-size: 12px;font-weight: 700;float: right;padding-right: 5px;">Total Amount</font></td>
									
									<td style="background-color:#CBCBCD;text-align: right;"><font style="font-size: 12px;font-weight: 700;"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($valtotalamt,2);?></font></td>
								</tr>
								<!--<tr>
									<td colspan= "4"><font style="font-size: 12px;float: right;padding-right: 5px;">Advance Amount</font></td>
									
									<td style="background-color:#CBCBCD;text-align: right;"><font style="font-size: 12px;"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($rowii["Paid_Amt"],2);?></font></td>
								</tr>
								<tr>
								<?php //$numformttotal = round($totalamount);
								//$numformttotal = number_format($numformttotal,2);
								?>
									<td colspan= "4"><font style="font-size: 12px;float: right;padding-right: 5px;">Billing Total</font></td>
									
									<td style="background-color:#CBCBCD;text-align: right;"><font style="font-size: 12px;"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($rowii["Balance_Amt"],2);?></font></td>
								</tr>-->
								
								<tr>
									<td colspan= "6"><font style="font-size: 12px;"></font><font style="font-size: 12px;"><?php echo $inwords; ?></font></td>
									
								</tr>
								<tr>
									<td rowspan= "2" colspan= "2" ><font style="font-size: 12px;">Terms & Conditions :</font></td>
									<td rowspan= "2" colspan= "4" ><font style="font-size: 12px;"></font></td>
								</tr>
								<tr></tr>
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