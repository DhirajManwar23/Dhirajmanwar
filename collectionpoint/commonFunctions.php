<?php
class Common {
	
	public function CommonEnc($pass){
    $cipKey=$this->getSettingValue("cipKey");
    $eKey=$this->getSettingValue("eKey");
    $options = 0;
    $eIV=$this->getSettingValue("eIV");
    $varEnc=openssl_encrypt($pass, $cipKey, $eKey, $options, $eIV);
    return $varEnc; 

    }
    // return $encryption;
    public function CommonDec($pass){
    
        $dIv = $this->getSettingValue("eIV");
        $dKey = $this->getSettingValue("eKey");
        $cipKey=$this->getSettingValue("cipKey");
        $options=0;
        $varDec = openssl_decrypt($pass, $cipKey, $dKey, $options, $dIv);
        return $varDec;
    }
	public function getIPAddress(){
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		//whether ip is from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		//whether ip is from remote address
		else{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		date_default_timezone_set("Asia/Kolkata");
		$date=date("Y-m-d h:i:sa");
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		//whether ip is from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		//whether ip is from remote address
		else{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		return $ip_address;
 }
 public function getSettingValue($pass){
		
	$myfile = fopen("../assets/js/custom/Settings.txt", "r") or die("Unable to open file!");
	//$search = "name";
	// Read from file
	$lines = file('../assets/js/custom/Settings.txt');
	foreach($lines as $line)
	{
	  // Check if the line contains the string we're looking for, and print if it does
	  if(strpos($line, $pass) !== false){
		$str_arr = explode ("=", $line); 
		return trim($str_arr[1]);
		break;
	  }
	  
	} 
	 
	fclose($myfile);
 }
public function amountInWords($amt)
{
	$inwords="";
	$number=$amt;
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
			$plural = (($counter = count($str)) && $number > 9) ? '' : null;
			$hundred = ($counter == 1 && $str[0]) ? '  ' : null;
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
	return $inwords;
}


public function sendGCM($title, $message, $id) {

	$fcmtoken=$this->getSettingValue('fcmtoken');
	
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*$fields = array (
            'registration_ids' => array (
                    $id
            ),
            'data' => array (
                    "message" => $message
            )
    );*/
	
	$fields = array (
            'to' => $id
			,
            'notification' => array (
                    "body" => $message,
					"title" => $title
            )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . $fcmtoken,
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
   // echo $result;
    curl_close ( $ch );
}
public function getCommonDataValue($pass){
	return $this->getSettingValue($pass);
 }
 public function getArrayColors($pass){
	 $background_colors = array('','#f6e84e', '#f96868', '#00FF00', '#57c7d4', '#58d8a3', '#f2a654', '#aab2bd', '#E91E63', '#57B657', '#FFC100');
	return $background_colors[$pass];
}
}
?>