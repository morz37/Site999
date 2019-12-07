<?php 
if( !defined( 'FORM_HANDLER' ) ) die('no direct access allowed');

function get_real_ip() {
   if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
     $ip=$_SERVER['HTTP_CLIENT_IP'];
   } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
     $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
   } else {
     $ip=$_SERVER['REMOTE_ADDR'];
   }
   return $ip;
}

function get_ip_info_2($ip){
	global $ipinfo;
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, 'http://ipgeobase.ru:7020/geo?ip='.$ip); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    $responseXml = curl_exec($curl);
    curl_close($curl);
    if (substr($responseXml, 0, 5) == '<?xml'){
        $ipinfo = new SimpleXMLElement($responseXml);
        return $ipinfo->ip;
    }
    return false;
}

function whitelist_filelds($whitelist){
            
    foreach ($_POST as $key=>$item) {
		if (!in_array($key, $whitelist)) {
			return false;
		}
    }
    return true;
}

function validate_form($antispam_fields = ''){

	$has_errors = true;
	if ($_POST['email']) {
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$has_errors = false;
		}
	}
	// if (preg_match('/[0-9]/', $_POST['form_name'])){
	// 	$has_errors = false;
	// }
	if (preg_match('/[0-9]/', $_POST['name'])){
		$has_errors = false;
	}
	foreach( $antispam_fields as $key => $value ){
    	if ($_POST[$key] != $value) $has_errors = false;
	}
	return $has_errors;
}

function clean_input($field,$length = 150){
	if (is_array($field)){
		$arr = array();
		foreach ($field as $value) {
			$arr[] = substr( 	trim( htmlspecialchars($value) )  	,0,$length);
		}
		return $arr;
	} else {
		return substr( 	trim( htmlspecialchars($field) )  	,0,$length);
	}
}