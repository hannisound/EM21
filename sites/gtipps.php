<section id="gtipps">
	<div class="container-lg clearfix">
    	<div class="row clearfix">
        	<div class="col mx-auto">
            <?php
              //echo nl2br(print_r($_SESSION,true));
							if (isset($_GET['grp'])) {
								if ($_GET['grp'] == "A" || $_GET['grp'] == "B" || $_GET['grp'] == "C" || $_GET['grp'] == "D" ||
										$_GET['grp'] == "E" || $_GET['grp'] == "F" || $_GET['grp'] == "G" || $_GET['grp'] == "H") {

								}
								else {
									$_SESSION['message'] = "Die Ihnen gewählte Gruppe gibt es nicht. Bitte wählen Sie eine der vorgegebenen Gruppen aus";
									echo "<div class='alert alert-danger'>".$_SESSION['message']."</div>";
								}
								$active = "";
							}
							else {
								$active = "active";
							}

							// Prüfen wenn SaveTipp abgesetzt wurde
							if (isset($_POST['SaveTipp'])) {
								// Arrays zusammenführen
								$tipps = array_map(null, $_POST['gameID'], $_POST['geg1'], $_POST['geg2']);

								// Alle Tipps durchlaufen und prüfen
								foreach ($tipps as $key) {
										if(is_array($key)){
											// Tipps einragen
											saveTipp($key[0], $key[1], $key[2], $mysqli);
										}
										else
										{
											echo "<div class='alert alert-danger text-center'><strong>Fehler beim Zusammenstellen der Anfrage</strong></div>";
										}
									}
									echo "<div class='alert alert-success text-center'>Ihre Tipps wurden Erfolgreich eingetragen</div>";
							}

							//Prüfen wenn ErgebnisSend abgesetzt wurden
							if (isset($_POST['ErgebnisSend'])) {
								// Arrays zusammenführen
							$ergebnis1 = array_map(null, $_POST['gameID1'], $_POST['ergebnis1'], $_POST['ergebnis2'], $_POST['hze']);
								// Punkte der Teams auf 0 setzen
								$sql = "UPDATE ".prefix."_team SET punkte = 0, games = 0";
								if (!$mysqli->query($sql)) {
									printf("Fehler beim zurücksetzen der Punkte");
								}

							// Alle Ergebnisse durchlaufen und prüfen
							foreach ($ergebnis1 as $key) {
									// Tipps einragen
									saveErgebnis($key[0], $key[1], $key[2], $mysqli, $key[3]);	// Keine Optimierung gesehen?
								}
									// Absolvierte Spiele eintragen
										updateGames($mysqli); 		// Optimiert
									// Punkte der Mannschaften Updaten
										updateGroup($mysqli); 		// Optimiert
									// SUN Eintragen
										updateSUN($mysqli); 		// Optimiert
									// Tore Eintragen
										updateTore($mysqli); 		// Optimiert
									// Differenz Eintragen
										updateDifferenz($mysqli); 	// Optimiert
									// Last Place speichern
										saveLastPlace($mysqli); 	// Keine Optimierung gesehen?
									// User Punkte Vorrunde Eintragen
										userPointsGames($mysqli);	// Optimiert
									echo '<br><div class="alert alert-success text-center"><strong>Ihre Ergebnisse wurden Erfolgreich übernommen</strong></div>';
							}

            ?>

						<ul class="nav nav-pills nav-fill">
							<li class="nav-item">
								<a class="nav-link <?php echo $active; ?>" href="index.php?site=gtipps">Alle</a>
							</li>

						<?php
							$active1 = "";
							$sql = "SELECT grpID, name FROM ".prefix."_grp WHERE grpID <= '8'";
							$result = $mysqli->query($sql);
							while ($grp = $result->fetch_array()) {
								// Für Aktive Gruppe Variable setzen
								if (isset($_GET['grp'])) {
									if ($_GET['grp'] == $grp['name']) {
										$active1 = "active";
									}
									else {
										$active1 = "";
									}
								}
								echo "<li class='nav-item'>
								  <a class='nav-link $active1' href='index.php?site=gtipps&grp=".$grp['name']."'>Gruppe ".$grp['name']."</a>
								</li>";
							}
							?>

						</ul>
						<form action="" method="POST" accept-charset="UTF-8" class="form-inline">

						<!-- Neues Dynamisches Layout !-->

						<div class="container-lg">

						<!-- TODO: Kann das entfernt werden?
							<div class="row">
								<div class="col">Spielzeit</div>
								<div class="col-md-auto">F + Land 1 : Land 2 + F</div>
								<div class="col">Ergebnis</div>
								<div class="col">Halbzeit</div>
								<div class="col">Tipp</div>
							</div>!-->

							<?php
							// Prüfen wenn $_GET['grp'] gesetzt ist und die Abfrage anpassen
							// Gefixt: Gruppen anpassen an EM21 (gibt nur 6 Gruppen)
							if (isset($_GET['grp']) && $_GET['site'] == 'gtipps') {
								$gruppen = $_GET['grp'];
								$gruppe = array("1" => "A",
												"2" => "B",
												"3" => "C",
												"4" => "D",
												"5" => "E",
												"6" => "F");
								$grp = array_search("$gruppen", $gruppe);

								// SQL Abfrage für Spezielle Gruppen
								$sql = "SELECT g.ID, t.name AS geg_1, t2.name AS geg_2, t.short_name AS short_name1, t2.short_name AS short_name2, g.ergebnis_1, g.ergebnis_2, g.h_ergebnis_1, g.h_ergebnis_2, g.time, g.grp FROM `".prefix."_games` g
			    				INNER JOIN ".prefix."_team t ON (g.geg_1 = t.id) INNER JOIN ".prefix."_team t2 ON (g.geg_2 = t2.id) WHERE g.grp = '$grp' ORDER BY time ASC";
							}
							else {
								// SQL Abfrage für alle Spiele
								$sql = "SELECT g.ID, t.name AS geg_1, t2.name AS geg_2, t.short_name AS short_name1, t2.short_name AS short_name2, g.ergebnis_1, g.ergebnis_2, g.h_ergebnis_1, g.h_ergebnis_2, g.time, g.grp FROM `".prefix."_games` g
												INNER JOIN ".prefix."_team t ON (g.geg_1 = t.id) 
												INNER JOIN ".prefix."_team t2 ON (g.geg_2 = t2.id)
												WHERE g.grp <= 6 ORDER BY time ASC";
							}

						$result = $mysqli->query($sql);
				    	while ($game = $result->fetch_array()) {
				    		// Spielübersicht erstellen
							echo "<div id='".$game['ID']."' class='row justify-content-md-center'>";
							echo "<div class='col-md-auto col-sm text-center'>", date('d.m.Y H:i', $game['time']), " Uhr</div>";
							echo "<div class='col-md text-center'>
									<div class='row justify-content-center text-center'>
										<div class='col col-md text-center'><span class='left'>".flag($game['geg_1'], 40, 1)."</span></div>
										<div class='col col-md-auto col-sm-auto text-center'><span class='long_name'>".$game['geg_1']."</span> <span class='short_name'>".$game['short_name1']."</span> : <span class='short_name'>".$game['short_name2']."</span> <span class='long_name'>".$game['geg_2']."</span></div>
										<div class='col col-md text-center'><span class='right'>".flag($game['geg_2'], 40, 1)."</span></div>
									</div>
								  </div>";
							// Prüfen wenn Admin Eingeloggt ist
							if ($_SESSION['status'] == 3) {
								// Ergebnis für Admin vorbereiten
								echo "<div class='col-md-auto col-sm-12 text-center'>
										<input type='hidden' name='gameID1[]' value='".$game['ID']."'>
										<input type='number' value='".$game['ergebnis_1']."' name='ergebnis1[]' class='form-control tipp'> : <input type='number' value='".$game['ergebnis_2']."' name='ergebnis2[]' class='form-control tipp'>
									</div>";
								echo "<div class='col-md-auto col-sm-12 text-center'>
										(", $game['h_ergebnis_1'], " : ", $game['h_ergebnis_2'], ")
										<input type='hidden' name='hze[".$game['ID']."]' value='0'>
										<input type='checkbox' name='hze[".$game['ID']."]' value='1'>
									</div>";

								// Speichernbutton für Ergebnisse erstellen
			    				$saveErgebnis = "<button name='ErgebnisSend' class='btn btn-green'>Ergebnis speichern</button>";

							}
							else {
								// User hat keinen Admin-Status - keine Eingabemöglichkeit für Ergebnisse
								echo "<div class='col-md-auto col-sm-12 text-center'>". $game['ergebnis_1']. " : ". $game['ergebnis_2']. "</div>";
								echo "<div class='col-md-auto col-sm-12 text-center'>(". $game['h_ergebnis_1']. " : ". $game['h_ergebnis_2']. ")</div>";
								$saveErgebnis = "";
							}


							// Ergebnis vom User auslesen
							$sql1 = "SELECT `tipp1`, `tipp2` FROM `".prefix."_tipp` WHERE `userID` = '".$_SESSION['user_id']."' AND `gameID` = '".$game['ID']."'";
							$result1 	= $mysqli->query($sql1);
							$row 		= mysqli_fetch_array($result1);
							$realtime = time();

							if ($game['time'] <= $realtime) {
								// Ausgabe des Tipps ohne Eingabemöglichkeit (Spiel hat begonnen)
								echo "<div class='col-md-2 col-sm text-center'>".$row['tipp1']." : ".$row['tipp2']."</div></div>";
							}
							else{
								// Ausgabe des Tipps mit Eingabemöglichkeit (Spiel hat noch NICHT begonnen)
								echo "<div class='col-md-2 col-sm-12 text-center'>
				                    	<label class='sr-only' for='Heimmannschaft'>Heimmannschaft</label>
				                      	<input type='hidden' name='gameID[]' value='".$game['ID']."'>
				                      	<input type='number' name='geg1[]' value='".$row['tipp1']."' id='tippID_".$game['ID']."' class='form-control tipp'/>
				                      	:
				                    	<label class='sr-only' for='Auswärtsmannschaft'>Auswärtsmannschaft</label>
				                      	<input type='number' name='geg2[]' value='".$row['tipp2']."' id='tipp2ID_".$game['ID']."' class='form-control tipp' />
				    				</div>";
							}
							echo "</div>";
						}
						?>
							<div class="col-md-auto-12 col-sm-12 text-center btn-group" role="group" aria-label="Speichern Gruppe">
							<?php
								// Button zum Speichern der Ergebnisse
								echo $saveErgebnis;?>
								<!-- Button zum Speichern der Tipps -->
								<button class="btn btn-green" name="SaveTipp">Tipps Speichern</button>
							</div>
						</div>
					</form>
        	</div>
        </div>
    </div>
</section>
