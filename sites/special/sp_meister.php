<?php
	echo "<h2>Tippen Sie Ihren Weltmeister <p class='lead'>Für den richtigen Weltmeistertipp gibt es 10 Punkte.</p></h2>";
	$count = 1;
	// Meister ID aus der Datenbank auslesen für MYSQL_Query
	// Gefixt: grp Abfrage anpassen
	$sql		= "SELECT `meisterID` FROM `".prefix."_meister` WHERE `userID` = '".$_SESSION['user_id']."'";
	$result		= $mysqli->query($sql);
	$meister_id	= mysqli_fetch_array($result);
	$abfrage 	= "SELECT ID FROM ".prefix."_grp LIMIT 0,6";
	$sql		= $mysqli->query($abfrage);
	while( $grp = mysqli_fetch_array($sql))
	{
		echo "<div class='row'>";
		// Liste aller Teilnehmer laden
		$abfrage 	= "SELECT ID, name FROM ".prefix."_team WHERE grp = '".$grp['ID']."'";
		$result		= $mysqli->query($abfrage);
		while($team = mysqli_fetch_array($result))
		{
			// Pürfen wenn die Euro schon gestartet ist
			if (time() <= wmStart($mysqli)) {
				if ($team['ID'] == $meister_id['meisterID']){
					echo "<div class='col-3'><a id='wm_meister' field='".$_SESSION['user_id']."' title='".$team['name']."' name='".$team['ID']."' href='#'>".flag($team['name'], 50, 1, $team['ID'])."</a><br>".$team['name']."</div>";
				}
				else {
					echo "<div class='col-3'><a id='wm_meister' field='".$_SESSION['user_id']."' title='".$team['name']."' name='".$team['ID']."' href='index.php?site=special'>".flag($team['name'], 50, 0.3, $team['ID'])."</a><br>".$team['name']."</div>";
				}
			}
			else {
				if ($team['ID'] == $meister_id['meisterID']) {
					echo "<div class='col-3'>".flag($team['name'], 50, 1, $team['ID'])."<br>".$team['name']."</div>";
				}
				else {
					echo "<div class='col-3'>".flag($team['name'], 50, 0.3, $team['ID'])."<br>".$team['name']."</div>";
				}
			}
		}
		echo "</div><br>";
	}
?>
