<?php 
define('FORM_HANDLER', 'f');
require_once './form_functions.php';
require_once './PHPMailer/PHPMailerAutoload.php';

/*
* Fill data
*/

$fromEmail = 'info@site999.ru';
// $toEmail = '460881@mail.ru';
$toEmail = 'it@reshenie.website';




$antispam_fields = array(
    'surname' => 'smitt', 
    'operation' => '',
    'website' => 'yahoo.com',
    'uid' => 'ghqwtydsyJf6##',
);
$whitelist = array_merge(array_flip($antispam_fields),array('name','tel','message','email','agree','submit','product'));




$fromName = 'site999';
$subject = 'Заявка с сайта | site999';
$message = '<h3>Заявка с сайта | site999</h3><p>Параметры заявки:</p>';
$CSS = '<style>table tr td:first-child{font-weight:bold}</style>';




$fields = array(
  'Имя'  =>  isset($_POST['name'])?  clean_input($_POST['name']) : '',
  'Телефон'   =>  isset($_POST['tel'])?   clean_input($_POST['tel']) : '',
  'Email' =>  isset($_POST['email'])? clean_input($_POST['email']) : '',
  'Услуга' =>  isset($_POST['product'])? clean_input($_POST['product']) : '',
);
$fields['Сообщение'].=isset($_POST['message'])?  clean_input($_POST['message']) : '';



function sendEmail($to, $subject, $message, $attachment = false, $attachmentName = false){
	global $fromEmail, $fromName;
	$mail = new PHPMailer;
	$mail->CharSet = "UTF-8";
	$mail->setFrom($fromEmail, $fromName);
	$mail->addAddress($to);
	//$mail->addAddress($adminEmail);
	$mail->Subject = $subject;
	$mail->msgHTML($message);
	// if ($attachment) $mail->addAttachment($attachment, $attachmentName);
	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    // echo 'Mailer Error: ' . $mail->ErrorInfo;
	    return false;
	} else {
	    echo 'ok';
	    return true;
	}
}


/*
* Anti-spam check
*/
if (!validate_form($antispam_fields)) die('Invalid data: a');
if (!whitelist_filelds($whitelist)) die('Invalid data: f');

/*
* Process request
*/
$message .= '<table>';
foreach ($fields as $key => $value) {
	if ($value){
		$message .= '<tr><td>'.$key.': </td><td>'.$value.'</td></tr>';
	}
}
$message .= '</table>';
$message .= $CSS;


if (sendEmail($toEmail, $subject, $message)){
} else echo 'не работает';

