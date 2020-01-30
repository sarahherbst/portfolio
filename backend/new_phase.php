<?php
	$page = 'new_phase';
	require_once('../connection.inc.php');
	require_once('../function.inc.php');

	require('header.php');

	$alert 		= '';
	$fehler 	= 0;

	$pha_startdate 					= '';
	$pha_enddate 					= '';
	$pha_prize 						= '';
	$pha_url 						= '';
	$pha_img 						= '';


	// submit
	if (isset($_POST['submit'])) {
		// Daten einlesen
		$pha_startdate 				= mysqli_escape_string($db, $_POST['pha_startdate']);
		$pha_enddate 				= mysqli_escape_string($db, $_POST['pha_enddate']);
		$pha_prize 					= mysqli_escape_string($db, $_POST['pha_prize']);
		$pha_url 					= mysqli_escape_string($db, $_POST['pha_url']);
		$pha_img					= mysqli_escape_string($db, $_POST['pha_img']);

		// Daten eintragen
		$pha_insert = sql_insert('phase', array('pha_institut', 'pha_startdate', 'pha_enddate', 'pha_prize', 'pha_url', 'new_user', 'chg_user'), array($institut_id, $pha_startdate, $pha_enddate, $pha_prize, $pha_url, $user_email, $user_email));
		if ($pha_insert == true) {
			$alert .= '<div class="alert alert-success" role="alert">Die Gewinnspielphase wurde erfolgreich hinzugefügt.</div>';
			$pha_startdate 				= '';
			$pha_enddate 				= '';
			$pha_prize 					= '';
			$pha_url		 			= '';
		} else {
			$fehler++;
			$alert .= '<div class="alert alert-danger" role="alert"><b>Fehler!</b> Die Gewinnspielphase konnte nicht hinzugefügt werden. Bitte kontaktieren Sie den Websiteadministrator!</div>';
		}

		// Bild zum Video
		if ($fehler == 0) {
			$alert_upload = '';

			// Profilbild upload
			if (is_uploaded_file($_FILES['videoposter']['tmp_name']) && $_FILES['videoposter']['error'] === 0) {
				// Das Upload-Verzeichnis
				$upload_folder 		= '../img/videoposter/'; //Das Upload-Verzeichnis
				$large_folder 		= '../img/videoposter/large/';
				$thumbnail_folder 	= '../img/videoposter/thumb/';
				// prüfen, ob Verzeichnis vorhanden ist, ansonsten erstellen
				if (!is_dir($upload_folder)) {
					if (!mkdir($upload_folder, 0777, true)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Bild zum Video konnte nicht erstellt werden.</div>';
					}
				}
				if (!is_dir($large_folder)) {
					if (!mkdir($large_folder, 0777, true)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Bild zum Video konnte nicht erstellt werden.</div>';
					}
				}
				if (!is_dir($thumbnail_folder)) {
					if (!mkdir($thumbnail_folder, 0777, true)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Bild zum Video konnte nicht erstellt werden.</div>';
					}
				}

				// Dateiname und -endung
				$filename 	= pathinfo($_FILES['videoposter']['name'], PATHINFO_FILENAME);
				$extension 	= strtolower(pathinfo($_FILES['videoposter']['name'], PATHINFO_EXTENSION));

				//Überprüfung der Dateiendung
				$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
				if (!in_array($extension, $allowed_extensions)) {
					$fehler++;
					$alert .= '<div class="alert alert-danger" role="alert">Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt.</div>';
				}

				// Überprüfung der Dateigröße
				$max_size = 4000*1024; // max. Dateigröße: 4000 KB
				if ($_FILES['videoposter']['size'] > $max_size) {
					$fehler++;
					$alert .= '<div class="alert alert-danger" role="alert">Bitte keine Dateien größer als 4 mb hochladen</div>';
				}

				// Überprüfung, dass das Bild keine Fehler enthält
				if (function_exists('exif_imagetype')) { // Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
					$allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_JPEG2000, IMAGETYPE_JPC, IMAGETYPE_JP2, IMAGETYPE_GIF);
					$detected_type = exif_imagetype($_FILES['videoposter']['tmp_name']);

					if (!in_array($detected_type, $allowed_types)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Nur der Upload von Bilddateien (png, jpg, jpeg und gif-Dateien) ist gestattet. Stellen Sie sicher, dass Ihre Bilddatei nicht beschädigt ist.</div>';
					}
				}

				// Überprüfung, ob Dateiname bereits existiert
				// Pfad zum Upload
				$current_path 	= $upload_folder.$filename.'.'.$extension;
				$new_path 		= $large_folder.$filename.'.'.$extension;

				// Name in Variable speichern
				$filename_final	= $filename.'.'.$extension;

				// Falls Datei existiert, hänge eine Zahl an den Dateinamen
				if (file_exists($new_path)) { 
					$img_id = 1;
					do {
						$current_path 	= $upload_folder.$filename.'_'.$img_id.'.'.$extension;
						$new_path 		= $large_folder.$filename.'_'.$img_id.'.'.$extension;

						$filename_final	= $filename.'_'.$img_id.'.'.$extension;

						$img_id++;
					}
					while(file_exists($new_path));
				}

				if ($fehler == 0) {
					// vorübergehendes Bild verschieben
					move_uploaded_file($_FILES['videoposter']['tmp_name'], $current_path);
					$videoposter_motiv = $current_path;

					if ($videoposter_motiv !== '') {
						// Galeriebild hochladen
						$videoposter = fc_imgresize($videoposter_motiv, $large_folder);
						if ($videoposter == '' || $videoposter == false) {
							$fehler++;
							$alert_upload .= 'Das Bild '.$_FILES['videoposter']['name'].' konnte nicht aktualisiert werden. ';
						} else {
							$videoposter 		= str_replace('../', '', $videoposter);
							$alert_upload .= 'Das Bild '.$_FILES['videoposter']['name'].' wurde erfolgreich hochgeladen. ';
						}
					} else {
						$fehler++;
						$alert_upload .= 'Das Bild '.$_FILES['videoposter']['name'].' konnte nicht hochgeladen werden. ';
					}

					// Thumbnail erstellen
					if ($fehler == 0) {
						$videoposter_thumb = fc_imgthumbnail($videoposter_motiv, $thumbnail_folder);
						if ($videoposter_thumb == '' || $videoposter_thumb == false ) {
							$fehler++;
							$alert_upload .= 'Das Thumbnail zum Bild '.$_FILES['videoposter']['name'].' konnte nicht erstellt werden. ';
						} else {
							$videoposter_thumb 	= str_replace('../', '', $videoposter_thumb);
							$alert_upload .= 'Das Thumbnail zum Bild '.$_FILES['videoposter']['name'].' wurde erfolgreich erstellt. ';
						}
					}
				}

				if ($fehler !== 0) {
					$alert .= '<div class="alert alert-danger" role="alert"><b>Bild zum Video: </b>'.$alert_upload.'</div>';
				} else {
					$alert .= '<div class="alert alert-success" role="alert"><b>Bild zum Video: </b>'.$alert_upload.'</div>';
				}

				// Profilbild in Datenbank eintragen
				if ($fehler == 0) {
					$bild_insert = sql_update('phase', 'pha_img', $filename_final, 'pha_id', $pha_id);

					if ($bild_insert == false) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert"><b>Fehler!</b> Das Bild zum Video konnte leider nicht in die Datenbank eingetragen werden. Bitte kontaktieren Sie den Websiteadministrator!</div>';
					}
				}

				// vorübergehendes Bild löschen
				unlink($videoposter_motiv);
			}
		}
	}
?>

<div class="jumbotron">
	<h1>Gewinnspielphase hinzufügen</h1>
	<p class="lead">Hier können Sie eine weitere Phase hinzufügen.</p>
</div><!-- /.jumbotron -->

<form action="" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-12">
			<?php echo $alert; ?>
		</div>
		<div class="col-md-12">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-business-time" aria-hidden="true"></i> &nbsp; Angaben
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-row">
								<div class="form-group col-sm-6">
									<label for="pha_startdate">Startdatum:</label>
									<input type="date" class="form-control" name="pha_startdate" id="pha_startdate" placeholder="Startdatum" value="<?php echo $pha_startdate; ?>" min="2019-01-01" required>
									<small class="form-text text-muted">Geben Sie ein Veröffentlichungsdatum an.</small>
								</div>
								<div class="form-group col-sm-6">
									<label for="pha_enddate">Enddatum:</label>
									<input type="date" class="form-control" name="pha_enddate" id="pha_enddate" placeholder="Enddatum" value="<?php echo $pha_enddate; ?>" min="2019-01-01" required>
									<small class="form-text text-muted">Geben Sie ein Enddatum an.</small>
								</div>
							</div>
							<div class="form-group">
								<label for="pha_prize">Gewinn:</label>
								<input type="text" class="form-control" name="pha_prize" id="pha_prize" placeholder="Gewinn" value="<?php echo $pha_prize; ?>" required>
								<small class="form-text text-muted">Geben Sie einen Gewinn an.</small>
							</div>
							<div class="form-group">
								<label for="pha_url">Link zum YouTube-Video:</label>
								<input type="text" class="form-control" name="pha_url" id="pha_url" placeholder="URL" value="<?php echo $pha_url; ?>" required>
								<small class="form-text text-muted">Bitte geben Sie hier den Link zum entsprechenden YouTube-Video an.</small>
							</div>
							<?php 
								if (!$pha_img == '') {
							?>	
								<div class="form-group">
									<label>Aktuell eingestelltes Bild:</label><br>

									<img src="../img/videoposter/thumb/<?php echo $pha_row['pha_img']; ?>" class='img-thumbnail' height="auto" width="200">
								</div>
							<?php } ?>
							<div class="form-group">
								<label>Bild zum Video</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" name="videoposter" required>
									<label class="custom-file-label" for="videoposter">Bild zum Video auswählen ...</label>
									<small class="form-text text-muted">Laden Sie ein passendes Bild im Format .jpg/.png/.gif hoch. Beachten Sie, dass das Bild nicht größer als 500kb sein darf.</small>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Submit Button -->
				<div class="card-footer pb-0">
					<div class="form-group">
						<button type="submit" class="btn btn-lg btn-success" name="submit">hinzufügen</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<?php
	include('footer.php');
?>