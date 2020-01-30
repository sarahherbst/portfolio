<?php
	$fehler 			= 0;
	$alert 				= '';
	$sendmessage		= false;

	// Formular wird übertragen
	if (isset($_POST['submit'])) {

		$sendmessage = true;

		// Daten einlesen
		$kd_mail 		= mysqli_escape_string($db, $_POST['email']);
		$kd_message 	= mysqli_escape_string($db, $_POST['message']);
		
		// E-Mail versenden
		if ($fehler == 0) {
			// Include PHPMailer class
			require_once('phpmailer/PHPMailerAutoload.php');

			// Template abrufen
			$message 			= $kd_message;

			// E-Mail Text
			$email_betreff 		= 'Neue Mitteilung!';

			//Setup PHPMailer
			$mail 				= new PHPMailer;
			$mail->setLanguage('de', 'phpmailer/language/');
			$mail->CharSet 		='UTF-8';
			//$mail ->SMTPDebug = 2; 				// Enable verbose debug output
			$mail->isSMTP(); 						// Set mailer to use SMTP
			$mail->Host 		= $smtp_server; 	// Specify main and backup SMTP servers
			$mail->SMTPOptions 	= array(
				'ssl' => array(
					'verify_peer' 		=> false,
					'verify_peer_name' 	=> false,
					'allow_self_signed' => true
				)
			);
			$mail->SMTPAuth 	= true; 			// Enable SMTP authentication
			$mail->Username 	= $smtp_user; 		// SMTP username
			$mail->Password 	= $smtp_passwort; 	// SMTP password
			$mail->SMTPSecure 	= 'ssl'; 			// Enable TLS encryption, `ssl` also accepted
			$mail->Port 		= $smtp_port; 		// TCP port to connect to

			//Absender
			$mail->SetFrom($email_von);
			$mail->Sender 		= ($kd_mail);
			$mail->addReplyTo($kd_mail);

			//Empfänger
			$email_empfaenger 	= $email_von;
			$teilnehmername 	= 'Sarah Herbst';
			$mail->addAddress($email_empfaenger, $teilnehmername);

			//Betreff
			$mail->Subject 		= $email_betreff;

			$mail->MsgHTML($message);
			if( !$mail->Send() ) {
				$fehler++;
				$alert .= 'Unfortunately there was a technical problem and your message could not be sent. Please try again later.';
			} else {
				$alert .= '<h3>Thanks.</h3><p>Your message has successfully been sent. I will get back to you soon.</p>';
			}
		}
	}

?>