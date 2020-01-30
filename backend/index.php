<?php
	$page = 'index';
	require('header.php');

	// Phrase, wenn Passwort zurückgesetzt wurde
	if ( isset($_SESSION["first"]) ) {
		echo $_SESSION["first"];
	}
?>

<div class='row'>
	<div class="col-12">
		Hier kommt noch eine Übersicht zum Backend hin.
	</div>
</div>

<?php
	require('footer.php');
?>