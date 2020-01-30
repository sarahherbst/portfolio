<?php
	$page = 'index';

	include 'header.php';

	$prj_sql 	= sql_select_where('all', 'projects', 'prj_status', '1', '', '');
	$prj_num	= mysqli_num_rows($prj_sql);

	error_reporting(E_ALL);
	
?>

<div class="content-wide">

	<div class="inner-content">
		<!-- Begrüßung -->
		<div class="hello">
			<img src="img/hello.svg" alt="welcome" title="welcome">
			<p><?php echo $intro; ?></p>
		</div>
	</div>

	<!-- Projekte -->
	<div class="inner-content projects">
		<?php while($prj_row = $prj_sql->fetch_assoc()){ ?>
			<div class="project <?php echo $prj_row['prj_pos']; ?>">
				<a href="project.php?prj_id=<?php echo $prj_row['prj_id']; ?>" style="--bg-color: #<?php echo $prj_row['prj_color']; ?>; --bg-color-hover: #<?php echo $prj_row['prj_color_2']; ?>;">
					<img src="img/thumb/<?php echo $prj_row['prj_img']; ?>">
					<h2><?php echo $prj_row['prj_name']; ?></h2>
				</a>
			</div>
		<?php } ?>
	</div>

	<!-- Hintergrund -->
	<div class="box box-big"></div>
	<div class="box box-small"></div>
	<div class="box box-footer"></div>


<?php include 'footer.php'; ?>