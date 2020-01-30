<?php
	$page = 'edit_datenschutz';
	require('header.php');

	$fehler = 0;
	$alert 	= '';

	$schluessel = 'datenschutz';

	// Tabelle auslesen
	$txt_sql = sql_select_where('all', 'text', 'txt_schluessel', $schluessel, '', '');

	if ( mysqli_num_rows($txt_sql) == 1 ) {
		$txt_row = mysqli_fetch_assoc($txt_sql);

		// Variablen vergeben
		$txt_beitrag 	= $txt_row['txt_text'];
		$txt_beitrag 	= str_replace('<br />', '', $txt_beitrag);
	} else {
		$txt_titel 		= '';
		$txt_beitrag 	= '';
	}

	// speichern
	if (isset($_POST['submit'])) {
		/*einlesen der im Formular angegebenen Werte*/
		$txt_beitrag 	= $_POST['txt_beitrag'];
		$txt_beitrag 	= nl2br($txt_beitrag);

		// Variablen für Fehlerprüfung – Wenn kein Fehler vorhanden ist: fehler=0
		if ( mysqli_num_rows($txt_sql) == 1 ) {
			$sql_insert 	= sql_update('text', 'txt_text', $txt_beitrag, 'txt_schluessel', $schluessel);
		} else {
			$sql_insert 	= sql_insert('text', array('txt_schluessel', 'txt_text'), array($schluessel, $txt_beitrag));
		}
		if ($sql_insert == true) {
			$alert .= "<div class='alert alert-success' role='alert'>Das datenschutz wurde erfolgreich geändert.</div>";
			$txt_beitrag = str_replace('<br />', '', $txt_beitrag);
			$txt_beitrag = $_POST['txt_beitrag'];
		}
		else {
			$fehler++;
			$alert   .= "<div class='alert alert-danger alert-dismissible fade in' role='alert'><b>Fehler!</b> Das datenschutz konnte nicht gespeichert werden.</div>";
		}
	}
?>

<div class="jumbotron">
	<h1>datenschutz bearbeiten</h1>
	<p class="lead">Hier haben Sie die Möglichkeit das datenschutz zu bearbeiten.</p>
</div><!-- /.jumbotron -->

<form action="" method="post">
	<div class="row">
		<div class="col-md-12">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-file" aria-hidden="true"></i> &nbsp; datenschutz
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<?php echo $alert; ?>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_beitrag">Text:</label>
								<textarea class="form-control" rows="10" name="txt_beitrag" id="txt_beitrag"><?php echo $txt_beitrag; ?></textarea>
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