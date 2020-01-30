<?php
	
	require 'connection.inc.php';
	require 'function.inc.php';
	require 'data.inc.php';

	include 'contact.php';

?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<meta name="description" content="Webdesign, webdevelopment and graphic design based in Berlin.">
		<meta name="author" content="Sarah Herbst">
		<meta name="keywords" content="<?php echo $keywords; ?>">

		<title><?php echo $sitetitel; ?></title>

		<link rel="icon" type="image/png" sizes="48x48" href="img/favicon.png">

		<link rel="stylesheet" type="text/css" href="css/style.css">

		<link href="https://fonts.googleapis.com/css?family=Frank+Ruhl+Libre|Glegoo|Roboto+Slab|Zilla+Slab&display=swap" rel="stylesheet">

	</head>
	<body>
		<!-- Navigation -->
		<nav class="<?php if($page == 'project') { echo 'white';} ?>">
			<ul>
				<li><button title="About" class="layover-link" data-target="about-box">About</button></li>
				<li><button title="Contact me" class="layover-link" data-target="contact-box">Contact</button></li>
			</ul>
		</nav>