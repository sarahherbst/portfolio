<?php
	$page = 'new_image';
	require_once('../connection.inc.php');
	require_once('../function.inc.php');

	require('header.php');

	$alert 		= '';
	$fehler 	= 0;

	$prj_sql 	= sql_select_where('all', 'projects', 'prj_status', '1', '', '');

	$img_prj 					= '';
	$img_name 					= '';
	$img_desc 					= '';

	// submit
	if (isset($_POST['submit'])) {
		// Daten einlesen
		$img_prj 		= $_POST['img_prj'];
		$img_desc 		= $_POST['img_desc'];

	
		// Bild zum Projekt
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
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Bild zum Projekt konnte nicht erstellt werden.</div>';
					}
				}
				if (!is_dir($large_folder)) {
					if (!mkdir($large_folder, 0777, true)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Bild zum Projekt konnte nicht erstellt werden.</div>';
					}
				}
				if (!is_dir($thumbnail_folder)) {
					if (!mkdir($thumbnail_folder, 0777, true)) {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert">Der Ordner für das Bild zum Projekt konnte nicht erstellt werden.</div>';
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
					$alert .= '<div class="alert alert-danger" role="alert"><b>Bild zum Projekt: </b>'.$alert_upload.'</div>';
				} else {
					$alert .= '<div class="alert alert-success" role="alert"><b>Bild zum Projekt: </b>'.$alert_upload.'</div>';
				}

				if ($fehler == 0) {
					$img_insert = sql_insert('images', array('img_prj', 'img_name', 'img_desc', 'new_user'), array($img_prj, $filename_final, $img_desc, $user_email));
					if ($img_insert == true) {
						$alert .= '<div class="alert alert-success" role="alert">Das Bild wurde erfolgreich hinzugefügt.</div>';
						$img_prj 					= '';
						$img_name 					= '';
						$img_desc 					= '';
						$img_func					= '';
					} else {
						$fehler++;
						$alert .= '<div class="alert alert-danger" role="alert"><b>Fehler!</b> Das Bild konnte nicht hinzugefügt werden. Bitte kontaktieren Sie den Websiteadministrator!</div>';
					}
				}

				// vorübergehendes Bild löschen
				unlink($projectimage_motiv);
			}
		}
	}
?>

<div class="jumbotron">
	<h1>Bild hinzufügen</h1>
	<p class="lead">Hier kannst du ein weiteres Bild hinzufügen.</p>
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
								<label></label>
								<select class="form-control" name="img_prj" title="img_prj">
									<option value="">Projekt auswählen</option>
									<?php while($prj_row = mysqli_fetch_assoc($prj_sql)) { ?>
										<option value="<?php echo $prj_row['prj_id']; ?>" <?php if($img_prj == $prj_row['prj_id']) { echo 'selected'; } ?>>
											<?php echo $prj_row['prj_name']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label for="img_desc">Bildbeschreibung</label>
								<input type="text" class="form-control" name="img_desc" id="img_desc" placeholder="Bildbeschreibung" value="<?php echo $img_desc; ?>" required>
								<small class="form-text text-muted">Beschreibe das Bild kurz.</small>
							</div>
							<div class="form-group">
								<label>Bilddatei auswählen</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" name="projectimage" required>
									<label class="custom-file-label" for="projectimage">Bild auswählen ...</label>
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