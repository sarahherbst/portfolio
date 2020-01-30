<?php
	$page = 'images';
	require_once('../connection.inc.php');
	require_once('../function.inc.php');

	require('header.php');

	if (isset($_POST['loeschen'])) {
		$img_id 	= $_POST['img_id'];
		$img_delete = sql_delete('images', 'img_id', $img_id);
	}

	if (isset($_POST['deaktivieren'])) {
		$img_id 	= $_POST['img_id'];
		$img_update = sql_update('images', array('img_status', 'chg_user'), array('9', $user_email), 'img_id', $img_id);
	}

	if (isset($_POST['aktivieren'])) {
		$img_id 	= $_POST['img_id'];
		$img_update = sql_update('images', array('img_status', 'chg_user'), array('1', $user_email), 'img_id', $img_id);
	}
?>

<!-- Einleitung -->
<div class="jumbotron">
	<h1>Bilder</h1>
	<p class="lead">Hier findest du eine Übersicht aller Bilder. 
	Du kannst diese bearbeiten, löschen oder auch de-/aktivieren.</p>
</div>

<div class="row mb-4">
	<div class="col-12">
		<a href="new_image.php" title="Neues Bild" class="btn btn-success btn-md w-100"><i class="fa fa-plus"></i> Neues Bild einfügen</a>
	</div>
</div>

<?php $img_sql = sql_select_where('all', 'images', 'img_status', '1', '', ''); ?>
<?php if (mysqli_num_rows($img_sql) == true && mysqli_num_rows($img_sql) >= 1) { ?>
	<div class="card mb-3">
		<div class="card-header">
			<i class="fas fa-table"></i>
			Aktive Bilder
		</div>
		
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Auswahl</th>
							<th></th>
							<th></th>
							<th></th>
							<th>Titel</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($img_row = mysqli_fetch_assoc($img_sql)) { ?>
							<tr>
								<th scope="row"><?php echo $img_row['img_id']; ?></th>
								<td><img src="../img/thumb/<?php echo $img_row['img_name']; ?>" style="max-width: 150px;"></td>
								<td>
									<form action="" class="form-inline" method="post" enctype="multipart/form-data">
										<input type="hidden" name="img_id" value="<?php echo $img_row['img_id']; ?>">
										<button type="submit" name="deaktivieren" class="btn btn-default btn-sm">deaktivieren</button>
									</form>
								</td>
								<td>
									<a href="edit_image.php?img_id=<?php echo $img_row['img_id']; ?>" title="bearbeiten" class="btn btn-info btn-sm">bearbeiten</a>
								</td>
								<td>
									<form action="" method="post" enctype="multipart/form-data">
										<input type="hidden" name="img_id" value="<?php echo $img_row['img_id']; ?>">
										<button type="submit" name="loeschen" class="btn btn-danger btn-sm">löschen</button>
									</form>
								</td>
								<td><?php echo $img_row['img_name']; ?></td>
							</tr>
						<?php }	?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php }	?>

<?php $img_sql = sql_select_where('all', 'images', 'img_status', '9', '', ''); ?>
<?php if (mysqli_num_rows($img_sql) == true && mysqli_num_rows($img_sql) >= 1) { ?>
	<div class="card mb-3">
		<div class="card-header">
			<i class="fas fa-table"></i>
			Inaktive Bilder
		</div>
		
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Auswahl</th>
							<th></th>
							<th></th>
							<th>Name</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($img_row = mysqli_fetch_assoc($img_sql)) { ?>
							<tr>
								<th scope="row"><?php echo $img_row['img_id']; ?></th>
								<td>
									<form action="" class="form-inline" method="post" enctype="multipart/form-data">
										<input type="hidden" name="img_id" value="<?php echo $img_row['img_id']; ?>">
										<button type="submit" name="aktivieren" class="btn btn-default btn-sm">aktivieren</button>
									</form>
								</td>
								<td>
									<a href="edit_image.php?img_id=<?php echo $img_row['img_id']; ?>" title="bearbeiten" class="btn btn-info btn-sm">bearbeiten</a>
								</td>
								<td>
									<form action="" method="post" enctype="multipart/form-data">
										<input type="hidden" name="img_id" value="<?php echo $img_row['img_id']; ?>">
										<button type="submit" name="loeschen" class="btn btn-danger btn-sm">löschen</button>
									</form>
								</td>
								<td><?php echo $img_row['img_name']; ?></td>
							</tr>
						<?php }	?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php }	?>

<?php
	include('footer.php');
?>