<?php
	$page = 'edit_projects';
	require_once('../connection.inc.php');
	require_once('../function.inc.php');

	require('header.php');

	$alert 		= '';
	$fehler 	= 0;

	$prj_id 					= $_GET['prj_id'];
	$prj_sql 					= sql_select_where('all', 'projects', 'prj_id', $prj_id, '', '');
	$prj_row 					= mysqli_fetch_assoc($prj_sql);
	$prj_name 					= $prj_row['prj_name'];
	$prj_task 					= $prj_row['prj_task'];
	$prj_pos 					= $prj_row['prj_pos'];
	$prj_desc 					= $prj_row['prj_desc'];
	$prj_desc 					= str_replace('<br />', '', $prj_desc);
	$prj_desc2 					= $prj_row['prj_desc2'];
	$prj_desc2 					= str_replace('<br />', '', $prj_desc2);
	$prj_img 					= $prj_row['prj_img'];
	$prj_color 					= $prj_row['prj_color'];
	$prj_color_2 				= $prj_row['prj_color_2'];


	// submit
	if (isset($_POST['submit'])) {
		// Daten einlesen
		$prj_name 					= mysqli_escape_string($db, $_POST['prj_name']);
		$prj_task 					= mysqli_escape_string($db, $_POST['prj_task']);
		$prj_pos 					= mysqli_escape_string($db, $_POST['prj_pos']);
		$prj_color 					= mysqli_escape_string($db, $_POST['prj_color']);
		$prj_color_2 				= mysqli_escape_string($db, $_POST['prj_color_2']);
		$prj_desc 					= mysqli_escape_string($db, $_POST['prj_desc']);
		$prj_desc 					= nl2br($prj_desc);
		$prj_desc2 					= mysqli_escape_string($db, $_POST['prj_desc2']);
		$prj_desc2 					= nl2br($prj_desc2);

		// Daten eintragen
		$prj_update = sql_update('projects', array('prj_name', 'prj_task', 'prj_desc', 'prj_desc2', 'prj_pos', 'prj_color', 'prj_color_2', 'chg_user'), array($prj_name, $prj_task, $prj_desc, $prj_desc2, $prj_pos, $prj_color, $prj_color_2, $user_email), 'prj_id', $prj_id);
		if ($prj_update == true) {
			$prj_desc 	= str_replace('<br />', '', $prj_desc);
			$prj_desc 	= $_POST['prj_desc'];
			$alert .= '<div class="alert alert-success" role="alert">Das Projekt wurde erfolgreich bearbeitet.</div>';
		} else {
			$fehler++;
			$alert .= '<div class="alert alert-danger" role="alert"><b>Fehler!</b> Das Projekt konnte nicht bearbeitet werden. Bitte kontaktieren Sie den Websiteadministrator!</div>';
		}

		// Projektbild
		if ($fehler == 0) {
			$alert_upload = '';

			// Profilbild upload
			if (is_uploaded_file($_FILES['projectimage']['tmp_name']) && $_FILES['projectimage']['error'] === 0) {
				// Das Upload-Verzeichnis
				$upload_folder 		= '../img/'; //Das Upload-Verzeichnis
				$large_folder 		= '../img/large/';
				$thumbnail_folder 	= '../img/thumb/';
				// prüfen, ob Verzeichnis vorhanden ist, ansonsten erstellen
				if (!is_dir($upload_folder)) {
					if (!mkdir($upload_folder, 0777, true)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Projektbild konnte nicht erstellt werden.</div>';
					}
				}
				if (!is_dir($large_folder)) {
					if (!mkdir($large_folder, 0777, true)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Projektbild konnte nicht erstellt werden.</div>';
					}
				}
				if (!is_dir($thumbnail_folder)) {
					if (!mkdir($thumbnail_folder, 0777, true)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Projektbild konnte nicht erstellt werden.</div>';
					}
				}

				// Dateiname und -endung
				$filename 	= pathinfo($_FILES['projectimage']['name'], PATHINFO_FILENAME);
				$extension 	= strtolower(pathinfo($_FILES['projectimage']['name'], PATHINFO_EXTENSION));

				//Überprüfung der Dateiendung
				$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
				if (!in_array($extension, $allowed_extensions)) {
					$fehler++;
					$alert .= '<div class="alert alert-danger" role="alert">Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt.</div>';
				}

				// Überprüfung der Dateigröße
				$max_size = 4000*1024; // max. Dateigröße: 4000 KB
				if ($_FILES['projectimage']['size'] > $max_size) {
					$fehler++;
					$alert .= '<div class="alert alert-danger" role="alert">Bitte keine Dateien größer als 4 mb hochladen</div>';
				}

				// Überprüfung, dass das Bild keine Fehler enthält
				if (function_exists('exif_imagetype')) { // Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
					$allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_JPEG2000, IMAGETYPE_JPC, IMAGETYPE_JP2, IMAGETYPE_GIF);
					$detected_type = exif_imagetype($_FILES['projectimage']['tmp_name']);

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
					move_uploaded_file($_FILES['projectimage']['tmp_name'], $current_path);
					$projectimage_motiv = $current_path;

					if ($projectimage_motiv !== '') {
						// Galeriebild hochladen
						$projectimage = fc_imgresize($projectimage_motiv, $large_folder);
						if ($projectimage == '' || $projectimage == false) {
							$fehler++;
							$alert_upload .= 'Das Bild '.$_FILES['projectimage']['name'].' konnte nicht aktualisiert werden. ';
						} else {
							$projectimage 		= str_replace('../', '', $projectimage);
							$alert_upload .= 'Das Bild '.$_FILES['projectimage']['name'].' wurde erfolgreich hochgeladen. ';
						}
					} else {
						$fehler++;
						$alert_upload .= 'Das Bild '.$_FILES['projectimage']['name'].' konnte nicht hochgeladen werden. ';
					}

					// Thumbnail erstellen
					if ($fehler == 0) {
						$projectimage_thumb = fc_imgthumbnail($projectimage_motiv, $thumbnail_folder);
						if ($projectimage_thumb == '' || $projectimage_thumb == false ) {
							$fehler++;
							$alert_upload .= 'Das Thumbnail zum Bild '.$_FILES['projectimage']['name'].' konnte nicht erstellt werden. ';
						} else {
							$projectimage_thumb 	= str_replace('../', '', $projectimage_thumb);
							$alert_upload .= 'Das Thumbnail zum Bild '.$_FILES['projectimage']['name'].' wurde erfolgreich erstellt. ';
						}
					}
				}

				if ($fehler !== 0) {
					$alert .= '<div class="alert alert-danger" role="alert"><b>Projektbild: </b>'.$alert_upload.'</div>';
				} else {
					$alert .= '<div class="alert alert-success" role="alert"><b>Projektbild: </b>'.$alert_upload.'</div>';
				}

				// Profilbild in Datenbank eintragen
				if ($fehler == 0) {
					$bild_insert = sql_update('projects', 'prj_img', $filename_final, 'prj_id', $prj_id);

					if ($bild_insert == false) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert"><b>Fehler!</b> Das Projektbild konnte leider nicht in die Datenbank eingetragen werden. Bitte kontaktieren Sie den Websiteadministrator!</div>';
					}
				}

				// vorübergehendes Bild löschen
				unlink($projectimage_motiv);
			}
		}
	}
?>

<div class="jumbotron">
	<h1>Projekt bearbeiten</h1>
	<p class="lead">Hier kannst du das Projekt "<?php echo $prj_name; ?>" bearbeiten.</p>
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
							<div class="form-group">
								<label for="prj_name">Projektname:</label>
								<input type="text" class="form-control" name="prj_name" id="prj_name" placeholder="Projektname" value="<?php echo $prj_name; ?>" required>
								<small class="form-text text-muted">Gib den Namen des Projekts an.</small>
							</div>
							<div class="form-group">
								<label for="prj_task">Aufgaben:</label>
								<input type="text" class="form-control" name="prj_task" id="prj_task" placeholder="Aufgaben" value="<?php echo $prj_task; ?>" required>
								<small class="form-text text-muted">Gib hier die Aufgaben an, die du bei dem Projekt hattest (getrennt durch ein Komma und ohne Leerstellen).</small>
							</div>
							<div class="form-group">
								<div class="form-group">
									<label for="prj_desc">Projektbeschreibung:</label>
									<textarea class="form-control" rows="10" name="prj_desc" id="prj_desc" required><?php echo $prj_desc; ?></textarea>
									<small class="form-text text-muted">Beschreibe das Projekt kurz.</small>
								</div>
							</div>
							<div class="form-group">
								<div class="form-group">
									<label for="prj_desc">Projektbeschreibung Teil 2:</label>
									<textarea class="form-control" rows="10" name="prj_desc2" id="prj_desc2" required><?php echo $prj_desc2; ?></textarea>
									<small class="form-text text-muted">Beschreibe das Projekt kurz.</small>
								</div>
							</div>
							<div class="form-group">
								<label for="prj_pos">Vergeben Sie hier Klassen für das Projekt.</label>
								<input class="form-control" type="text" name="prj_pos" id="prj_pos" value="<?php echo $prj_pos; ?>" placeholder="Klassen">
								<small>Bsp: start, center, end, small, w-100</small>
							</div>
							<?php 
								if (!$prj_row['prj_img'] == '') {
							?>	
								<div class="form-group">
									<label>Aktuell eingestelltes Bild:</label><br>

									<img src="../img/thumb/<?php echo $prj_img; ?>" class='img-thumbnail' height="auto" width="200">
								</div>
							<?php } ?>
							<div class="form-group">
								<label>Projektbild</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" name="projectimage">
									<label class="custom-file-label" for="projectimage">Projektbild auswählen ...</label>
									<small class="form-text text-muted">Lade ein passendes Bild im Format .jpg/.png/.gif hoch. Beachte, dass das Bild nicht größer als 500kb sein darf.</small>
								</div>
							</div>
							<div class="form-group">
								<div class="form-row">
									<div class="col-6">
										<label for="prj_color">Hintergrundfarbe für das Projektbild</label>
										<input type="text" class="form-control jscolor" name="prj_color" id="prj_color" placeholder="Projektname" value="<?php echo $prj_color; ?>" required>
									</div>
									<div class="col-6">
										<label for="prj_color_2">Hintergrundfarbe für das Projektbild</label>
										<input type="text" class="form-control jscolor" name="prj_color_2" id="prj_color_2" placeholder="Projektname" value="<?php echo $prj_color_2; ?>" required>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Submit Button -->
				<div class="card-footer pb-0">
					<div class="form-group">
						<button type="submit" class="btn btn-lg btn-success" name="submit">speichern</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<?php
	include('footer.php');
?>