<?php
	$page = "new_user";
	require("header.php");

	$fehlerangabe = "";

	$vorname 	= "";
	$nachname 	= "";
	$position 	= "";
	$strasse 	= "";
	$plz 		= "";
	$ort 		= "";
	$tel 		= "";
	$email 		= "";

	if (isset($_POST['erstellen'])) {
		//einlesen der im Formular angegebenen Werte*/
		$vorname 	= mysqli_real_escape_string($db, $_POST['vorname']);
		$nachname 	= mysqli_real_escape_string($db, $_POST['nachname']);
		$position 	= mysqli_real_escape_string($db, $_POST['position']);
		$strasse 	= mysqli_real_escape_string($db, $_POST['strasse']);
		$plz 		= mysqli_real_escape_string($db, $_POST['plz']);
		$ort 		= mysqli_real_escape_string($db, $_POST['ort']);
		$tel 		= mysqli_real_escape_string($db, $_POST['tel']);
		$email 		= mysqli_real_escape_string($db, $_POST['email']);
		$access 	= mysqli_real_escape_string($db, $_POST['user_access']);
		//Registrierungscode erstellen
		$regcode 	= zufallsstring(10);

		if ($access == 'Editor') {
			$access = 'editor';
		} elseif ($access == 'Admin') {
			$access = 'admin';
		}

		//Variablen für Fehlerprüfung
		$fehler     = 0;

		$email_sql = mysqli_query($db, "SELECT * FROM user WHERE use_email = '$email'");
		if (mysqli_num_rows($email_sql) == 1) {
			$fehler = 1;
			$fehlerangabe   .= "<div class='alert alert-danger alert-dismissible' role='alert'><button class='close' type='button' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>×</span></button>Ein Benutzer mit dieser E-Mail existiert bereits.</div>";
		} else {
			//Benutzer in die Datenbank eintragen
			$use_eintrag 	= "INSERT INTO user";
			$use_eintrag 	.= "(use_vorname, use_nachname, use_position, use_strasse, use_plz, use_ort, use_tel, use_email, use_access, use_regcode, new_user, new_time, new_date, chg_user, chg_time, chg_date)";
			$use_eintrag 	.= " VALUES ('$vorname', '$nachname', '$position', '$strasse', '$plz', '$ort', '$tel', '$email', '$access', '$regcode', '$user_email', curtime(), curdate(), '$user_email', curtime(), curdate())";
			$use_query 		= mysqli_query($db, $use_eintrag) or die(mysqli_error($db));
			if ($use_query == true) {
				$fehlerangabe .= "<div class='alert alert-success alert-dismissible' role='alert'><button class='close' type='button' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>×</span></button>Der Benutzer wurde erfolgreich erstellt.</div>";

				//User-ID abfragen für Registrierungsmail
				$use_sql = "SELECT * FROM user WHERE use_vorname = '$vorname' AND use_nachname = '$nachname'";
				$use_res = mysqli_query($db, $use_sql) or die(mysqli_error($db));
				$use_row = mysqli_fetch_object($use_res);
				$user_id = $use_row->use_id;

				//Mail-Footer abfragen
				$sql_mailfooter 	= "SELECT * FROM texte WHERE txt_institut = '$institut_id' AND txt_schluessel = 'mailfooter'";
				$result_mailfooter 	= mysqli_query($db, $sql_mailfooter) or die(mysqli_error($db));
				$row_mailfooter 	= mysqli_fetch_object($result_mailfooter);
				/*Variablen vergeben*/
				$mailfooter 		= $row_mailfooter->txt_text;

				//Registrierungsmail mit Aktivierungslink versenden
				// Include PHPMailer class
				require("phpmailer/PHPMailerAutoload.php");

				//Setup PHPMailer
				$mail 				= new PHPMailer;
				$mail->setLanguage("de", "phpmailer/language/");
				$mail->CharSet 		="UTF-8";
				//$mail ->SMTPDebug = 3; 					// Enable verbose debug output
				$mail->isSMTP(); 						// Set mailer to use SMTP
				$mail->Host 		= $smtp_server; 	// Specify main and backup SMTP servers
				$mail->SMTPOptions 	= array(
					"ssl" => array(
						"verify_peer" => false,
						"verify_peer_name" => false,
						"allow_self_signed" => true
					)
				);
				$mail->SMTPAuth 	= true; 			// Enable SMTP authentication
				$mail->Username 	= $smtp_user; 		// SMTP username
				$mail->Password 	= $smtp_passwort; 	// SMTP password
				$mail->SMTPSecure 	= "ssl"; 			// Enable TLS encryption, `ssl` also accepted
				$mail->Port 		= $smtp_port; 		// TCP port to connect to
				$mail->isHTML(true);					// Set email format to html

				//Absender
				$mail->SetFrom($email_von, $institut);
				$mail->Sender 		= ($email_von);
				$mail->addReplyTo($email_zu, $institut);

				//Empfänger
				$name_empfaenger 	= $vorname." ".$nachname;
				$mail->addAddress($email, $name_empfaenger);

				//Betreff
				$mail->Subject 		= "Ihre Registrierung für das Backend ".$sitetitel."!";

				//Nachricht
				$mail->Body    		= "<p style='color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;'>Sehr geehrte(r) ".$vorname." ".$nachname.", </p>";
				$mail->Body 		.= "<p style='color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;'>";
				$mail->Body    		.= "Sie wurden für das Backend ".$sitetitel." unseres Institus registriert. Um die Registrierung vollständig abzuschließen, klicken Sie bitte <a href='https://".$path_parts['dirname']."/activation.php?id=".$user_id."&regcode=".$regcode."' alt='Registrierungscode' title='Registrierungscode'>diesen Link</a> zur Erstellung Ihres Passwortes und zur Aktivierung Ihres Accounts.</p><br><br><br>";
				$mail->Body 		.= "<p style='color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;'>".$mailfooter."</p>";

				$mail->AltBody 		= "Sehr geehrte(r) ".$vorname." ".$nachname.", \r\n Sie wurden für das Backend ".$sitetitel." unserer Seite registriert. Um die Registrierung vollständig abzuschließen, klicken Sie bitte folgenden Link zur Erstellung Ihres Passwortes und zur Aktivierung Ihres Accounts.\r\n \r\n Aktivierungslink: https://".$path_parts['dirname']."/activation.php?id=".$user_id."&regcode=".$regcode."\r\n \r\n";
				$mail->AltBody 		.= "_______________________\r\n";
				$mailfooter = str_replace('<br />', '\r\n', $mailfooter);
				$mailfooter = str_replace('<hr>', '', $mailfooter);
				$mail->AltBody 		.= $mailfooter;
				
				//E-Mail versenden
				if( !$mail->Send() ) {
					$fehler 		= "1";
					$fehlerangabe  .= "<div class='alert alert-danger alert-dismissible' role='alert'><button class='close' type='button' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>×</span></button>Fehler! Es konnte leider keine Registrierungsmail versendet werden. Bitte kontaktieren Sie den Websiteadministrator! <br>".$mail->ErrorInfo."</div>";
				} else {
					$fehlerangabe  .= "<div class='alert alert-success alert-dismissible' role='alert'><button class='close' type='button' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>×</span></button>Es wurde eine Aktivierungsmail an den neuen Benutzer gesandt.</div>";
				}
			} else {
				$fehler = 1;
				$fehlerangabe .= "<div class='alert alert-danger alert-dismissible' role='alert'><button class='close' type='button' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>×</span></button>Fehler! Der Benutzer konnte leider nicht erstellt werden.</div>";
			} //Eintragung in die Datenbank

		} // Prüfung Mail & Eintragung in die Datenbank + Registrierungsmail
	} // Klick Btn "Benutzer erstellen"

?>

<div class='jumbotron'>
	<h1>Benutzer hinzufügen</h1>
	<p class='lead'>Hier können Sie neue Benutzer anlegen, um ihnen Zugriff auf das Backend zu gewähren.</p>
</div><!-- /.jumbotron -->


<?php
	echo "<form action='' method='post'>";
		echo "<div class='row'>";
			echo "<div class='col-md-12'>";
				echo "<div class='card mb-3'>";
					echo "<div class='card-header'>";
						echo "<i class='fa fa-user' aria-hidden='true'></i> &nbsp; Einzelner Benutzer";
					echo "</div>";
					echo "<div class='card-body'>";
						echo "<div class='row'>";
							echo $fehlerangabe;
							echo "<div class='col-md-6'>";
								echo "<div class='form-group'>";
									echo "<label for='user_access'>Zugriffrolle:</label>";
									echo "<select class='form-control' name='user_access'>";
										echo "<option value=0 disabled selected>Bitte wählen Sie die Zugriffsrolle</option>";
											echo "<option value='editor'>Editor</option>";
											echo "<option value='admin'>Admin</option>";
											if ($_SESSION["access"] == "superadmin") {
												echo "<option value='superadmin'>Superadmin</option>";
											}
									echo "</select>";
								echo "</div>";

								echo "<div class='row'>";
									echo "<div class='col-md-6'>";
										echo "<div class='form-group'>";
											echo "<label for='vorname'>Vorname:</label>";
											echo "<input type='text' class='form-control' name='vorname' id='vorname' placeholder='Vorname' value='$vorname' required>";
										echo "</div>";
									echo "</div>";
									echo "<div class='col-md-6'>";
										echo "<div class='form-group'>";
											echo "<label for='nachname'>Nachname:</label>";
											echo "<input type='text' class='form-control' name='nachname' id='nachname' placeholder='Nachname' value='$nachname' required>";
										echo "</div>";
									echo "</div>";
								echo "</div>";

								echo "<div class='form-group'>";
									echo "<label for='position'>Position:</label>";
									echo "<input type='text' class='form-control' name='position' id='position' placeholder='Position' value='$position' required>";
								echo "</div>";

								echo "<div class='form-group'>";
									echo "<label for='email'>E-Mail:</label>";
									echo "<input type='email' class='form-control' name='email' id='email' placeholder='E-Mail' value='$email' required>";
								echo "</div>";
							echo "</div>";

							echo "<div class='col-md-6'>";
								echo "<div class='form-group'>";
									echo "<label for='strasse'>Straße:</label>";
									echo "<input type='text' class='form-control' name='strasse' id='strasse' placeholder='Straße' value='$strasse' required>";
								echo "</div>";

								echo "<div class='row'>";
									echo "<div class='col-md-4'>";
										echo "<div class='form-group'>";
											echo "<label for='plz'>PLZ:</label>";
											echo "<input type='text' class='form-control' name='plz' id='plz' placeholder='PLZ' value='$plz' required>";
										echo "</div>";
									echo "</div>";
									echo "<div class='col-md-8'>";
										echo "<div class='form-group'>";
											echo "<label for='ort'>Ort:</label>";
											echo "<input type='text' class='form-control' name='ort' id='ort' placeholder='Ort' value='$ort' required>";
										echo "</div>";
									echo "</div>";
								echo "</div>";

								echo "<div class='form-group'>";
									echo "<label for='tel'>Telefon:</label>";
									echo "<input type='text' class='form-control' name='tel' id='tel' placeholder='Telefonnummer' value='$tel' required>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
						echo "<div class='text-right'>";
							echo "<button type='submit' class='btn btn-success btn-lg' name='erstellen' value='erstellen'>Hinzufügen!</button>";	
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</form>";
?>

<?php
	include("footer.php");
?>
