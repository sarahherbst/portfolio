<?php
	$page = 'edit_general';

	require('header.php');

	$intro 						= $general_row['gen_intro'];
	$intro 						= str_replace('<br />', '', $intro);
	$about 						= $general_row['gen_about'];
	$about 						= str_replace('<br />', '', $about);

	$fehlerangabe 	= '';

	// General ändern
	if (isset($_POST['submit'])) {
		// einlesen der im Formular angegebenen Werte
		$sitetitel 			= mysqli_real_escape_string($db, $_POST['sitetitel']);
		$microsite_url 		= mysqli_real_escape_string($db, $_POST['microsite_url']);
		$keywords 			= mysqli_real_escape_string($db, $_POST['keywords']);
		$welcome 			= mysqli_real_escape_string($db, $_POST['welcome']);
		$welcome 			= nl2br($welcome);
		$intro 				= mysqli_real_escape_string($db, $_POST['intro']);
		$intro 				= nl2br($intro);
		$about 				= mysqli_real_escape_string($db, $_POST['about']);
		$about	 			= nl2br($about);
		$email_von 			= mysqli_real_escape_string($db, $_POST['email_von']);
		$email_zu 			= mysqli_real_escape_string($db, $_POST['email_zu']);
		$smtp_server 		= mysqli_real_escape_string($db, $_POST['smtp_server']);
		$smtp_user 			= mysqli_real_escape_string($db, $_POST['smtp_user']);
		$smtp_passwort 		= mysqli_real_escape_string($db, $_POST['smtp_passwort']);
		$smtp_port 			= mysqli_real_escape_string($db, $_POST['smtp_port']);
		$recaptcha_sitekey 	= mysqli_real_escape_string($db, $_POST['recaptcha_sitekey']);


		// Variablen für Fehlerprüfung – Wenn kein Fehler vorhanden ist: fehler = 0
		$fehler 	= 0;

		$gen_sql 	= sql_update('general', array('gen_sitetitel', 'gen_microsite_url', 'gen_keywords', 'gen_welcome', 'gen_intro', 'gen_about', 'gen_email_von', 'gen_email_zu', 'gen_smtp_server', 'gen_smtp_user', 'gen_smtp_passwort', 'gen_smtp_port', 'gen_recaptcha_sitekey', 'chg_user'), array($sitetitel, $microsite_url, $keywords, $welcome, $intro, $about, $email_von, $email_zu, $smtp_server, $smtp_user, $smtp_passwort, $smtp_port, $recaptcha_sitekey, $user_email), 'gen_id', $general_id);
		if ($gen_sql == true) {
			$welcome 	= str_replace('<br />', '', $welcome);
			$welcome 	= $_POST['welcome'];
			$intro 		= str_replace('<br />', '', $intro);
			$intro 		= $_POST['intro'];
			$about 		= str_replace('<br />', '', $about);
			$about 		= $_POST['about'];

			$fehlerangabe .= '<div class="alert alert-success" role="alert">Die Daten wurden erfolgreich geändert.</div>';
		} else {
			$fehler++;
			$fehlerangabe .= '<div class="alert alert-danger alert-dismissible" role="alert"><b>Fehler!</b> Die Daten konnten leider nicht geändert werden.</div>';
		}
	}
?>

<!-- Einleitung -->
<div class="jumbotron">
	<h1>Allgemeines</h1>
	<p class="lead">Hier kannst du die allgemeinen Website-Daten ändern.</p>
</div><!-- /.jumbotron -->

<!-- Eingabe-Formular -->
<?php echo $fehlerangabe; ?>
<form action="" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-12">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-cog" aria-hidden="true"></i> &nbsp; Allgemein
				</div>
				<div class="card-body">
					<div class="form-group">
						<label for="sitetitel">Seitentitel:</label>
						<input type="text" class="form-control" name="sitetitel" id="sitetitel" value="<?php echo $sitetitel; ?>" required>
					</div>
					<div class="form-group">
						<label for="microsite_url">URL zur Microsite:</label>
						<input type="text" class="form-control" name="microsite_url" id="microsite_url" value="<?php echo $microsite_url; ?>" required>
					</div>
					<div class="form-group">
						<label for="keywords">Keywords:</label>
						<input type="text" class="form-control" name="keywords" id="keywords" value="<?php echo $keywords; ?>">
					</div>
					<div class="form-group">
						<label for="recaptcha_sitekey">Google ReCaptcha Sitekey:</label>
						<input type="text" class="form-control" name="recaptcha_sitekey" id="recaptcha_sitekey" value="<?php echo $recaptcha_sitekey; ?>" required>
						<small class="form-text text-muted">Erstellen Sie auf <a href="https://www.google.com/recaptcha/admin#list" target="_blank">dieser Seite</a> ein unsichtbares reCaptcha und geben Sie den Sitekey an.</small>
					</div>
				</div>
			</div>

			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-cog" aria-hidden="true"></i> &nbsp; Texte
				</div>
				<div class="card-body">
					<div class="form-group">
						<label for="welcome">Begrüßungswort:</label>
						<input type="text" class="form-control" name="welcome" id="welcome" value="<?php echo $welcome; ?>" required>
					</div>
					<div class="form-group">
						<label for="intro">Begrüßungstext:</label>
						<textarea class="form-control" rows="10" name="intro" id="intro" required><?php echo $intro; ?></textarea>
					</div>
					<div class="form-group">
						<label for="about">About:</label>
						<textarea class="form-control" rows="10" name="about" id="about" required><?php echo $about; ?></textarea>
					</div>
				</div>
			</div>

			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-envelope" aria-hidden="true"></i> &nbsp; E-Mail
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="email_zu">Posteingangs-E-Mail:</label>
								<input type="email" class="form-control" name="email_zu" id="email_zu" value="<?php echo $email_zu; ?>" required>
							</div>
							<div class="form-group">
								<label for="email_von">Postausgangs-E-Mail:</label>
								<input type="email" class="form-control" name="email_von" id="email_von" value="<?php echo $email_von; ?>" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="form-group col-md-2">
									<label for="smtp_port">Port:</label>
									<input type="text" class="form-control" name="smtp_port" id="smtp_port" value="<?php echo $smtp_port; ?>" required>
								</div>
								<div class="form-group col-md-10">
									<label for="smtp_server">SMTP Server:</label>
									<input type="text" class="form-control" name="smtp_server" id="smtp_server" value="<?php echo $smtp_server; ?>" required>
								</div>
							</div>
							<div class="form-group">
								<label for="smtp_user">SMTP User:</label>
								<input type="text" class="form-control" name="smtp_user" id="smtp_user" value="<?php echo $smtp_user; ?>" required>
							</div>
							<div class="form-group">
								<label for="smtp_passwort">SMTP Passwort:</label>
								<input type="password" class="form-control" name="smtp_passwort" id="smtp_passwort" value="<?php echo $smtp_passwort; ?>" required>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12 pb-3">
			<button type="submit" class="btn btn-success btn-lg" name="submit" value="submit">Speichern!</button>
		</div>
	</div>
</form>

<?php
	include('footer.php');
?>
