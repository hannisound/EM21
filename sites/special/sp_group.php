	<h2>Wer wird Gruppensieger und Gruppenzweiter?
  <p class="lead">Für den jeweils richtigen Gruppenersten und zweiten gibt es jeweils 3 Punkte.</p>
	</h2>
	<form class="form-horizontal">
	<?php
		if (time() >= wmStart($mysqli)) {
			echo "<fieldset disabled>";
		}
		$sql 	= "SELECT grpID, name FROM ".prefix."_grp LIMIT 6";
		$result = $mysqli->query($sql);
		while ($grpName = mysqli_fetch_array($result)) {
			/*echo "<strong>Gruppe ".$grpName['name']."</strong><br>";
			echo "<div class='form-group'>";*/
			echo "<div class='row justify-content-center'>";
			echo "<label class='col-sm-2 control-label grpSieger' for='grpSieger'>Sieger Gruppe ".$grpName['name'].":</label>";
			echo "<div class='col-sm-3'>";
			echo "<select class='form-control' id='grpSieger' field='".$grpName['grpID']."' name='".$_SESSION['user_id']."'>";
				echo "<option></option>";
				$sql2 		= "SELECT ID, name FROM ".prefix."_team WHERE grp = '".$grpName['grpID']."'";
				$result2 	= $mysqli->query($sql2);
				while ($team = mysqli_fetch_array($result2)) {
					$sql3 		= "SELECT grpSieger FROM ".prefix."_grpSieger WHERE userID = '".$_SESSION['user_id']."' AND grpID = '".$grpName['grpID']."'";
					$result3 	= $mysqli->query($sql3);
					$grpSieger 	= mysqli_fetch_array($result3);
					if ($grpSieger['grpSieger'] == $team['ID']) {
						$selected = "selected";
					}
					else {
						$selected = "";
					}
					echo "<option id='".$team['ID']."' class='grpSieger".$grpName['grpID']."' value='".$team['name']."' $selected data-sieger='".$grpSieger['grpSieger']."'>".$team['name']."</option>";
				}

			echo "</select><div id='meldung_".$grpName['grpID']."' style='display:none;'></div></div>";

			// Gruppenzweiter Pulldown
			/*echo "<div class='form-group'>";*/
			echo "<label class='col-sm-2 control-label grpSieger' for='grpZweiter'>Zweiter Gruppe ".$grpName['name'].":</label>";
			echo "<div class='col-sm-3 text-center'>";
			echo "<select class='form-control' id='grpZweiter' field='".$grpName['grpID']."' name='".$_SESSION['user_id']."'>";
				echo "<option></option>";
				$sql2 		= "SELECT ID, name FROM ".prefix."_team WHERE grp = '".$grpName['grpID']."'";
				$result2 	= $mysqli->query($sql2);
				while ($team = mysqli_fetch_array($result2)) {
					$sql3 			= "SELECT grpZweiter FROM ".prefix."_grpSieger WHERE userID = '".$_SESSION['user_id']."' AND grpID = '".$grpName['grpID']."'";
					$result3 		= $mysqli->query($sql3);
					$grpZweiter 	= mysqli_fetch_array($result3);
					if ($grpZweiter['grpZweiter'] == $team['ID']) {
						$selected = "selected";
					}
					else {
						$selected = "";
					}
					echo "<option id='".$team['ID']."' class='grpZweiter".$grpName['grpID']."' value='".$team['name']."' $selected data-sieger='".$grpZweiter['grpZweiter']."'>".$team['name']."</option>";
				}
			echo "</select><div id='meldung2_".$grpName['grpID']."' style='display:none;'></div></div></div>";
		}
		if (time() >= wmStart($mysqli)) {
			echo "</fieldset>";
		}
	?>
	</form>
