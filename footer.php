
			<!-- About -->
			<div class="layover-box" id="about-box">
				<div class="layover-content">
					<div class="column">
						<p><?php echo $about; ?></p>
					</div>
					<div class="column column-central">
						<div class="close" data-target="about-box">x</div>
					</div>
				</div>
			</div>

			<!-- Contact -->
			<div class="layover-box <?php if($sendmessage == true) { echo 'open'; } ?>" id="contact-box">
				<div class="layover-content">
					<div class="column">
						<form action="" method="post" id="contactform">
							<?php if($sendmessage !== true) { ?>
								<h3>Message me.</h3>

								<label for="email"></label>
								<input type="email" name="email" id="email" placeholder="Your E-Mail" required>

								<label for="message"></label>
								<textarea name="message" id="message" rows="5" placeholder="Tell me what I can do for you." required></textarea>

								<button type="submit" name="submit" id="submit"></button>
							<?php } else { echo $alert; } ?>
						</form>
					</div>
					<div class="column column-central">
						<div class="close" data-target="contact-box">x</div>
					</div>
				</div>
			</div>

			<!-- Impressum -->
			<div class="layover-box" id="impressum-box">
				<div class="layover-content">
					<div class="column">
						<?php echo $impressum; ?>
					</div>
					<div class="column column-central">
						<div class="close" data-target="impressum-box">x</div>
					</div>
				</div>
			</div>

		</div>
				
		<footer>
			<div>
				<button class="layover-link" title="Impressum – Legal notice" data-target="impressum-box">Legal notice</button>&#160;
			</div>
			<div>
				© Sarah Herbst <?php echo date('Y'); ?>
			</div>
		</footer>

		<script type="text/javascript" src="js/scripts.js" defer></script>
	</body>
</html>