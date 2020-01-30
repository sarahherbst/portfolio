<?php
	$page = 'phase';
	require_once('../connection.inc.php');
	require_once('../function.inc.php');

	require('header.php');

	if (isset($_POST['loeschen'])) {
		$pha_id 	= $_POST['pha_id'];
		$pha_delete = sql_delete('phase', 'pha_id', $pha_id);
	}

	if (isset($_POST['deaktivieren'])) {
		$pha_id 	= $_POST['pha_id'];
		$pha_update = sql_update('phase', array('pha_status', 'chg_user'), array('9', $user_email), 'pha_id', $pha_id);
	}

	if (isset($_POST['aktivieren'])) {
		$pha_id 	= $_POST['pha_id'];
		$pha_update = sql_update('phase', array('pha_status', 'chg_user'), array('1', $user_email), 'pha_id', $pha_id);
	}
?>

<!-- Einleitung -->
<div class="jumbotron">
	<h1>Gewinnspielphasen</h1>
	<p class="lead">Hier finden Sie eine Übersicht aller Phasen des Gewinnspiels. 
	Sie können diese bearbeiten, löschen oder auch de-/aktivieren.</p>
</div>

<?php $pha_sql = sql_select_where('all', 'phase', 'pha_status', '1', '', ''); ?>
<?php if (mysqli_num_rows($pha_sql) == true && mysqli_num_rows($pha_sql) >= 1) { ?>
	<div class="card mb-3">
		<div class="card-header">
			<i class="fas fa-table"></i>
			Aktive Phasen
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
							<th>Start</th>
							<th>Ende</th>
							<th>Gewinn</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>#</th>
							<th>Auswahl</th>
							<th></th>
							<th></th>
							<th>Start</th>
							<th>Ende</th>
							<th>Gewinn</th>
						</tr>
					</tfoot>
					<tbody>
						<?php while ($pha_row = mysqli_fetch_assoc($pha_sql)) { ?>
							<tr>
								<th scope="row"><?php echo $pha_row['pha_id']; ?></th>
								<td>
									<form action="" class="form-inline" method="post" enctype="multipart/form-data">
										<input type="hidden" name="pha_id" value="<?php echo $pha_row['pha_id']; ?>">
										<button type="submit" name="deaktivieren" class="btn btn-default btn-sm">deaktivieren</button>
									</form>
								</td>
								<td>
									<a href="edit_phase.php?pha_id=<?php echo $pha_row['pha_id']; ?>" title="bearbeiten" class="btn btn-info btn-sm">bearbeiten</a>
								</td>
								<td>
									<form action="" method="post" enctype="multipart/form-data">
										<input type="hidden" name="pha_id" value="<?php echo $pha_row['pha_id']; ?>">
										<button type="submit" name="loeschen" class="btn btn-danger btn-sm">löschen</button>
									</form>
								</td>
								<td><?php echo date('d.m.Y', strtotime($pha_row['pha_startdate'])); ?></td>
								<td><?php echo date('d.m.Y', strtotime($pha_row['pha_enddate'])); ?></td>
								<td><?php echo $pha_row['pha_prize']; ?></td>
							</tr>
						<?php }	?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php }	?>

<?php $pha_sql = sql_select_where('all', 'phase', 'pha_status', '9', '', ''); ?>
<?php if (mysqli_num_rows($pha_sql) == true && mysqli_num_rows($pha_sql) >= 1) { ?>
	<div class="card mb-3">
		<div class="card-header">
			<i class="fas fa-table"></i>
			Inaktive Phasen
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
							<th>Start</th>
							<th>Ende</th>
							<th>Gewinn</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>#</th>
							<th>Auswahl</th>
							<th></th>
							<th></th>
							<th>Start</th>
							<th>Ende</th>
							<th>Gewinn</th>
						</tr>
					</tfoot>
					<tbody>
						<?php while ($pha_row = mysqli_fetch_assoc($pha_sql)) { ?>
							<tr>
								<th scope="row"><?php echo $pha_row['pha_id']; ?></th>
								<td>
									<form action="" class="form-inline" method="post" enctype="multipart/form-data">
										<input type="hidden" name="pha_id" value="<?php echo $pha_row['pha_id']; ?>">
										<button type="submit" name="aktivieren" class="btn btn-default btn-sm">aktivieren</button>
									</form>
								</td>
								<td>
									<a href="edit_phase.php?pha_id=<?php echo $pha_row['pha_id']; ?>" title="bearbeiten" class="btn btn-info btn-sm">bearbeiten</a>
								</td>
								<td>
									<form action="" method="post" enctype="multipart/form-data">
										<input type="hidden" name="pha_id" value="<?php echo $pha_row['pha_id']; ?>">
										<button type="submit" name="loeschen" class="btn btn-danger btn-sm">löschen</button>
									</form>
								</td>
								<td><?php echo date('d.m.Y', strtotime($pha_row['pha_startdate'])); ?></td>
								<td><?php echo date('d.m.Y', strtotime($pha_row['pha_enddate'])); ?>></td>
								<td><?php echo $pha_row['pha_prize']; ?></td>
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