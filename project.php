<?php
	$page = 'project';

	include 'header.php';

	$prj_id		= $_GET['prj_id'];

	// Prev and Next-Links definieren
	if($prj_id == '1') {
		$prev_link 	= $microsite_url;
		$prev_name 	= 'back home';
	} else {
		$prev_id	= $prj_id - 1;
		$sql_prev  	= sql_select_where('prj_id', 'projects', array('prj_id', 'prj_status'), array($prev_id, '1'), '','');

		while(mysqli_num_rows($sql_prev) < 1){
			$prev_id--;
			$sql_prev  	= sql_select_where('prj_id', 'projects', array('prj_id', 'prj_status'), array($prev_id, '1'), '','');
		}

		$prev_link 	= $microsite_url.'/project.php?prj_id='.$prev_id;
		$prev_name 	= 'previous project';
	}

	$prj_sql_max = sql_select_max('prj_id', 'projects');
	$prj_row_max = mysqli_fetch_assoc($prj_sql_max);

	if($prj_row_max['MAX(prj_id)'] <= $prj_id) {
		$next_link 	= $microsite_url;
		$next_name 	= 'back home';
	} else {
		$next_id	= $prj_id + 1;
		$sql_next  	= sql_select_where('prj_id', 'projects', array('prj_id', 'prj_status'), array($next_id, '1'), '','');
		
		while(mysqli_num_rows($sql_next) < 1){
			$next_id++;
			$sql_next  	= sql_select_where('prj_id', 'projects', array('prj_id', 'prj_status'), array($next_id, '1'), '','');
		}

		$next_link 	= $microsite_url.'/project.php?prj_id='.$next_id;
		$next_name 	= 'next project';
	}

	// Projektinfos auslesen
	$prj_sql 	= sql_select_where('all', 'projects', 'prj_id', $prj_id, '', '');
	$prj_row	= mysqli_fetch_assoc($prj_sql);

	$img_sql 	= sql_select_where('all', 'images', array('img_prj', 'img_status'), array($prj_id, '1'), '', '');	
?>

<a href="<?php echo $microsite_url; ?>" title="back" class="back"><img src="img/arrow-back.svg" title="Back Home" alt="Back Home"></a>

<div class="header" style="background-image: url('img/large/<?php echo $prj_row['prj_img']; ?>'); --bg-color: #<?php echo $prj_row['prj_color']; ?>;"></div>

<div class="content project-content">
	<div class="inner-content">
		<div class="col-4">
			<h2><?php echo $prj_row['prj_name']; ?></h2>
			<small><?php echo $prj_row['prj_task']; ?></small>
		</div>
		<div class="col-8">
			<p><?php echo nl2br($prj_row['prj_desc']); ?></p>
		</div>
	</div>

	<?php if(mysqli_num_rows($img_sql) >= 1) { ?>
		<div class="images">
			<?php $i = 0; while(($img_row = mysqli_fetch_assoc($img_sql)) && ($i <= 1)) { ?>
				<?php if($img_row['img_func'] == 'video') { ?>
					<video autoplay="" muted="" loop="" playsinline="" width="100%" height="auto">
						<source src="video/<?php echo $img_row['img_name']; ?>" type="video/mp4">
						Your browser does not support the video tag.
					</video>
				<?php } else { ?>
					<img src="img/large/<?php echo $img_row['img_name']; ?>" alt="<?php echo $img_row['img_desc']; ?>"  alt="<?php echo $img_row['img_desc']; ?>">
				<?php } ?>
			<?php $i++; } ?>
		</div>
	<?php } ?>

	<div class="inner-content mt-2">
		<div class="col-8">
			<p><?php echo nl2br($prj_row['prj_desc2']); ?></p>
		</div>
	</div>

	<?php if(mysqli_num_rows($img_sql) >= 1) { ?>
		<div class="images">
			<?php $i = 0; while($img_row = mysqli_fetch_assoc($img_sql)) { ?>
				<?php if($img_row['img_func'] == 'video') { ?>
					<video autoplay="" muted="" loop="" playsinline="" width="100%" height="auto">
						<source src="video/<?php echo $img_row['img_name']; ?>" type="video/mp4">
						Your browser does not support the video tag.
					</video>
				<?php } else { ?>
					<img src="img/large/<?php echo $img_row['img_name']; ?>" alt="<?php echo $img_row['img_desc']; ?>"  alt="<?php echo $img_row['img_desc']; ?>">
				<?php } ?>
			<?php } ?>
		</div>
	<?php } ?>

	<div class="nav-prev-next">
		<a href="<?php echo $prev_link; ?>" title="previous project" class="prev">
			<img src="img/arrow.svg" alt="previous project" title="previous project"><small><?php echo $prev_name; ?></small>
		</a>
		<a href="<?php echo $next_link; ?>" title="next project" class="next">
			<small><?php echo $next_name; ?></small><img src="img/arrow.svg" alt="previous project" title="previous project">
		</a>
	</div>


<?php include 'footer.php'; ?>