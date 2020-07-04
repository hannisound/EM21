<div class="centerButton">
<h2>Wer wird Torschützenkönig?
<p class="lead">Für den richtigen Torschützenkönig gibt es 10 Punkte</p></h2>
	<?php
		$sql 		= "SELECT spielerID FROM ".prefix."_torkoenig WHERE userID = '".$_SESSION['user_id']."'";
		$result 	= $mysqli->query($sql);
		$torKoenig 	= mysqli_fetch_array($result);
		if (time() <= wmStart($mysqli)) {
			if (mysqli_num_rows($result) === 0) {
				echo "<input class='form-control' type='text' id='tor_Koenig' field='".$_SESSION['user_id']."' placeholder='Geben Sie bitte Ihren Torschützenkönig ein'>";
			}
			else {
				echo "<input class='form-control' type='text' id='tor_Koenig' field='".$_SESSION['user_id']."' placeholder='Geben Sie bitte Ihren Torschützenkönig ein' value='".torKoenig($torKoenig['spielerID'], $mysqli)."'>";
			}
		}
		else {
			echo "<input class='form-control' type='text' id='tor_Koenig' field='".$_SESSION['user_id']."' placeholder='Geben Sie bitte Ihren Torschützenkönig ein' value='".torKoenig($torKoenig['spielerID'], $mysqli)."' disabled>";
		}
	?>
<div id="message"></div>
<button type="submit" class="btn btn-green" id="torKoenig" <?php echo disabled($mysqli); ?> >Speichern</button>
</div>

<script>

</script>
