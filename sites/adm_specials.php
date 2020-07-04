<section id="adm_specials">
	<div class="container">
    <div class="row">
      <div class="col-lg-12 mx-auto">
        <h2>Gruppensieger und Zweite eintragen</h2>
				<?php
					$sql 	= "SELECT grpID, name FROM ".prefix."_grp LIMIT 8";
					$result = $mysqli->query($sql);
					while ($grpName = mysqli_fetch_array($result)) {
						echo "<div class='row justify-content-center'>";
						echo "<label class='col-sm-2 control-label grpSieger' for='grpSieger'>Sieger Gruppe ".$grpName['name'].":</label>";
						echo "<div class='col-sm-3'>";
						echo "<select class='form-control' id='specialGrpSieger' field='".$grpName['name']."'>";
							echo "<option></option>";
							$sql2 		= "SELECT ID, name FROM ".prefix."_team WHERE grp = '".$grpName['grpID']."'";
							$result2 	= $mysqli->query($sql2);
							while ($team = mysqli_fetch_array($result2)) {
								$sql3 		= "SELECT eigenschaft, wert FROM ".prefix."_config WHERE eigenschaft = 'grpSieger_".$grpName['name']."'";
								$result3 	= $mysqli->query($sql3);
								$grpSieger 	= mysqli_fetch_array($result3);
								if ($grpSieger['wert'] == $team['ID']) {
									$selected = "selected";
								}
								else {
									$selected = "";
								}
								echo "<option id='".$team['ID']."' class='grpSieger".$grpName['grpID']."' value='".$team['name']."' $selected>".$team['name']."</option>";
							}

						echo "</select><div id='meldung_".$grpName['name']."'></div></div>";

						// Gruppenzweiter Pulldown
						echo "<label class='col-sm-2 control-label grpSieger' for='grpZweiter'>Zweiter Gruppe ".$grpName['name'].":</label>";
						echo "<div class='col-sm-3 text-center'>";
						echo "<select class='form-control' id='specialGrpZweiter' field='".$grpName['name']."'>";
							echo "<option></option>";
							$sql2 		= "SELECT ID, name FROM ".prefix."_team WHERE grp = '".$grpName['grpID']."'";
							$result2 	= $mysqli->query($sql2);
							while ($team = mysqli_fetch_array($result2)) {
								$sql3 			= "SELECT eigenschaft, wert FROM ".prefix."_config WHERE eigenschaft = 'grpZweiter_".$grpName['name']."'";
								$result3 		= $mysqli->query($sql3);
								$grpZweiter 	= mysqli_fetch_array($result3);
								if ($grpZweiter['wert'] == $team['ID']) {
									$selected = "selected";
								}
								else {
									$selected = "";
								}
								echo "<option id='".$team['ID']."' class='grpZweiter".$grpName['grpID']."' value='".$team['name']."' $selected data-zweiter='".$grpZweiter['grpZweiter']."'>".$team['name']."</option>";
							}
						echo "</select><div id='meldung2_".$grpName['name']."'></div></div></div>";
					}
			?>
			</form>
				<hr />
        <h2>Torschützenkönig eintragen</h2>
        	<?php
        		$sql = "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmTorkoenig'";
        		$result = $mysqli->query($sql);
        		if($result->num_rows == 0) {
        			echo "<input class='form-control' type='text' id='adm_tor_Koenig' placeholder='Geben Sie bitte Ihren Torschützenkönig ein'>";
        		}
        		else {
        			$wmTorkoenig = mysqli_fetch_array($result);
        			echo "<input class='form-control' type='text' id='adm_tor_Koenig' placeholder='Geben Sie bitte Ihren Torschützenkönig ein' value='".torKoenig($wmTorkoenig['wert'], $mysqli)."'>";
        		}
        	?>

        	<div id="message"></div>
					<button type="submit" class="btn btn-green" id="adm_torKoenig" >Speichern</button>

        <hr />
        <h2>Endstation für Deutschland eintragen</h2>

        	<select id="adm_Endstation" class="form-control">
        		<option value=""></option>
        <?php
        	$sql 		= "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmEndstation'";
        	$result 	= $mysqli->query($sql);
        	$endstation = mysqli_fetch_array($result);

        	$sql2 		= "SELECT grpID, name FROM ".prefix."_grp LIMIT 7,13";
        	$result2 	= $mysqli->query($sql2);
        	while ($grp = mysqli_fetch_array($result2)) {
        		// Prüfen wenn die Endstation gesetzt ist und selected hinzufügen
        		if ($grp['grpID'] == $endstation['wert']) {
        			$selected = 'selected="selected"';
        		}
        		else {
        			$selected = "";
        		}

        		// Prüfen wenn der Gruppenname H ist und in Vorrunde umwandeln
        		if ($grp['name'] == "H") {
        			echo '<option value="'.$grp['grpID'].'" '.$selected.'>Vorrunde</option>';
        		}
						elseif ($grp['grpID'] == 12) {
							//Nichts tun, da  Platz 3 nicht erfasst wird
						}
        		else {
        			echo '<option value="'.$grp['grpID'].'" '.$selected.'>'.$grp['name'].'</option>';
        		}
        	}
        ?>
        	</select>
        	<div id="EndStationMeldung"></div>
				<hr />

        <h2>UEFA EURO Meister eintragen</h2>


        <select id="adm_Meister" class="form-control">
        	<option value=""></option>

        <?php
        	// Aktuellen Meister auslesen
        	$sql 		= "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmMeister'";
        	$result 	= $mysqli->query($sql);
        	$meister 	= mysqli_fetch_array($result);

        	$sql2 		= "SELECT ID, name FROM ".prefix."_team ORDER BY `ID` ASC LIMIT 24";
        	$result2 	= $mysqli->query($sql2);
        	while ($team = mysqli_fetch_array($result2)) {
        		if ($team['ID'] == $meister['wert']) {
        			$selected = 'selected="selected"';
        		}
        		else {
        			$selected = '';
        		}

        		echo '<option value="'.$team['ID'].'" '.$selected.'>'.$team['name'].'</option>';
        	}
        ?>
        </select>
        <div id="MeisterMeldung" style="display:none;"></div>
			</div>
		</div>
	</div>
</section>
