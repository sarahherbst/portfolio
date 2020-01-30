<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	
	// Sessions
	if ($_SESSION['login'] !== 'ok') {
		header('Location: login.php');
	}
	$user_email 	= $_SESSION['user_email'];
	$user_access 	= $_SESSION['user_access'];
	$user_id 		= $_SESSION['user_id'];

	//Datenbank einlesen
	require_once('../connection.inc.php');
	require_once('../function.inc.php');
	require_once('../data.inc.php');
	
?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<meta name="description" content="<?php echo $sitetitel; ?>">
		<meta name="author" content="Sarah Herbst">
		<title>Sarah Herbst | Backend</title>

		<!-- Bootstrap core CSS-->
		<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom fonts for this template-->
		<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<!-- Page level plugin CSS-->
		<link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
		<!-- Custom styles for this template-->
		<link href="css/sb-admin.min.css" rel="stylesheet">

		<link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="../favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
		<link rel="manifest" href="../favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="../favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">

		<!--Load the AJAX API-->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	</head>

	<body id="page-top">

		<nav class="navbar navbar-expand navbar-dark bg-dark static-top">
			<a class="navbar-brand mr-1" href="index.php"><?php echo $sitetitel; ?> &ndash; Backend</a>
			<button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
				<i class="fas fa-bars"></i>
			</button>

			<!-- Navbar -->
			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown no-arrow">
					<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-user-circle fa-fw"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
						<a class="dropdown-item" href="edit_user.php?use_id=<?php echo $user_id; ?>">Profil bearbeiten</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
					</div>
				</li>
			</ul>
		</nav>

		<div id="wrapper">

			<!-- Sidebar -->
			<ul class="sidebar navbar-nav">
				<li class="nav-item <?php echo ($page  == 'index' ? 'active' : ''); ?>">
					<a class="nav-link" href="index.php">
						<i class="fas fa-fw fa-tachometer-alt"></i>
						<span>Dashboard</span>
					</a>
				</li>

				<li class="nav-item <?php echo ($page  == 'edit_general' ? 'active' : ''); ?>">
					<a class="nav-link" href="edit_general.php">
						<i class="fas fa-fw fa-university"></i>
						<span>Allgemeines</span>
					</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" href="user.php">Benutzer</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" href="projects.php">Projekte</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" href="images.php">Bilder</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" href="edit_impressum.php">Impressum</a>
					<a class="nav-link" href="edit_datenschutz.php">Datenschutz</a>
				</li>
			</ul>

			<div id="content-wrapper">

				<div class="container-fluid">