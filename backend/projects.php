<?php
	$page = 'projects';
	require_once('../connection.inc.php');
	require_once('../function.inc.php');

	require('header.php');

	if (isset($_POST['loeschen'])) {
		$prj_id 	= $_POST['prj_id'];
		$prj_delete = sql_delete('projects', 'prj_id', $prj_id);
	}

	if (isset($_POST['deaktivieren'])) {
		$prj_id 	= $_POST['prj_id'];
		$prj_update = sql_update('projects', array('prj_status', 'chg_user'), array('9', $user_email), 'prj_id', $prj_id);
	}

	if (isset($_POST['aktivieren'])) {
		$prj_id 	= $_POST['prj_id'];
		$prj_update = sql_update('projects', array('prj_status', 'chg_user'), array('1', $user_email), 'prj_id', $prj_id);
	}
?>

<!-- Einleitung -->
<div class="jumbotron">
	<h1>Projekte</h1>
	<p class="lead">Hier findest du eine Übersicht aller Projekte. 
	Du kannst diese bearbeiten, löschen oder auch de-/aktivieren.</p>
</div>

<div class="row mb-4">
	<div class="col-12">
		<a href="new_project.php" title="Neues Projekt" class="btn btn-success btn-md w-100"><i class="fa fa-plus"></i> Neues Projekt</a>
	</div>
</div>

<?php $prj_sql = sql_select_where('all', 'projects', 'prj_status', '1', '', ''); ?>
<?php if (mysqli_num_rows($prj_sql) == true && mysqli_num_rows($prj_sql) >= 1) { ?>
	<div class="card mb-3">
		<div class="card-header">
			<i class="fas fa-table"></i>
			Aktive Projekte
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Auswahl</th>
							<th></th>
							<th>Titel</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($prj_row = mysqli_fetch_assoc($prj_sql)) { ?>
							<tr>
								<th scope="row"><?php echo $prj_row['prj_id']; ?></th>
								<td>
									<form action="" class="form-inline" method="post" enctype="multipart/form-data">
										<input type="hidden" name="prj_id" value="<?php echo $prj_row['prj_id']; ?>">
										<button type="submit" name="deaktivieren" class="btn btn-default btn-sm">deaktivieren</button>
									</form>
								</td>
								<td>
									<a href="edit_project.php?prj_id=<?php echo $prj_row['prj_id']; ?>" title="bearbeiten" class="btn btn-info btn-sm">bearbeiten</a>
								</td>
								<td><?php echo $prj_row['prj_name']; ?></td>
							</tr>
						<?php }	?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php }	?>

<?php $prj_sql = sql_select_where('all', 'projects', 'prj_status', '9', '', ''); ?>
<?php if (mysqli_num_rows($prj_sql) == true && mysqli_num_rows($prj_sql) >= 1) { ?>
	<div class="card mb-3">
		<div class="card-header">
			<i class="fas fa-table"></i>
			Inaktive Projekte
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
						<?php while ($prj_row = mysqli_fetch_assoc($prj_sql)) { ?>
							<tr>
								<th scope="row"><?php echo $prj_row['prj_id']; ?></th>
								<td>
									<form action="" class="form-inline" method="post" enctype="multipart/form-data">
										<input type="hidden" name="prj_id" value="<?php echo $prj_row['prj_id']; ?>">
										<button type="submit" name="aktivieren" class="btn btn-default btn-sm">aktivieren</button>
									</form>
								</td>
								<td>
									<a href="edit_project.php?prj_id=<?php echo $prj_row['prj_id']; ?>" title="bearbeiten" class="btn btn-info btn-sm">bearbeiten</a>
								</td>
								<td>
									<form action="" method="post" enctype="multipart/form-data">
										<input type="hidden" name="prj_id" value="<?php echo $prj_row['prj_id']; ?>">
										<button type="submit" name="loeschen" class="btn btn-danger btn-sm">löschen</button>
									</form>
								</td>
								<td><?php echo $prj_row['prj_name']; ?></td>
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