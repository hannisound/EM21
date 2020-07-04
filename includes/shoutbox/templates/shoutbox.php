
		<div id="shoutbox_container">
			<form id="shoutbox_form" action="includes/shoutbox/includes/shoutbox_request.php" method="GET">

				<input type="hidden" class="" name="username" id="username" placeholder="Name" value="<?php echo $_SESSION['username']; ?>">
				<textarea type="text" class="form-control" name="message" id="message" placeholder="Nachricht"></textarea>

				<button type="submit" class="btn btn-green">Senden</button>
			</form>

			<div id="shoutbox_content" data-simplebar data-simplebar-auto-hide="false">


				<?php if(isset($data) && is_array($data)) :?>
					<?php foreach($data as $dat) : ?>
  					<div id="shoutbox_entry_<?php echo $dat['ID'] ?>" class="entry" data-date="<?php echo $dat['DateRaw'] ?>">
					<div class="header"><?php echo $dat['Username'] ?> am <?php echo $dat['Date'] ?></div>
					<div class="text">
						<?php echo $dat['Message'] ?>
					</div>
				</div>
				<?php endforeach; ?>
				<?php else: ?>
					<div class="no_data"> Bisher sind noch keine Einträge vorhanden. </div>
				<?php endif; ?>


			</div>
		</div>
