<?php
	// Variablen initialisieren
	$fehler 	= FALSE;
	$meldung 	= "";

	// Prüfen wenn Senden Button geklickt wurde
	if(isset($_POST['ChangePw'])) {
		$PwOld 	= $_POST['PwOld'];
		$PwOld2 = $_POST['PwOld2'];
		$PwNew 	= $_POST['PwNew'];

		// Prüfen wenn die alten Passwörter übereinstimmen
		if ($PwOld == $PwOld2) {
			// Prüfen wenn das Passwort mit dem DB Passwort übereinstimmt
			$sql 	= "SELECT ID, password FROM ".prefix."_users WHERE username = '".$_SESSION['username']."'";
			$result = $mysqli->query($sql);
			$pw 	= mysqli_fetch_array($result);

			if (password_verify($PwOld, $pw['password'])) {
				// DB Passwort stimmt mit dem Eingetragenen überein
				// Passwort auf Vorraussetzungen Prüfen
				if(preg_match_all('/[a-z]/', $PwNew)) {
					if (preg_match_all('/[A-Z]/', $PwNew)) {
						if (preg_match_all('/[0-9]/', $PwNew)) {
							// Neues Passwort Hashen
							$newPW = password_hash($PwNew, PASSWORD_DEFAULT);

							// SQL Abfrage zusammenstellen
							$sql = "UPDATE `".prefix."_users` SET password = '$newPW' WHERE `ID` = '".$pw['ID']."'";
							if (!$mysqli->query($sql)) {
								printf("<div class='alert alert-danger'>DB Fehler #0008: %s\n</div>", $mysqli->error);
							}
							else {
								$meldung = "<div class='alert alert-success'>Ihr Passwort wurde Erfolgreich geändert.</div>";
							}
						}
						else {
							// Fehlermeldung wenn Passowrt keine Zahlen enthält
							$meldung = "<div class='alert alert-danger'>Ihr neues Passwort entspricht nicht den mind. Vorraussetzungen</div>";
						}
					}
					else {
						//Fehlermeldung wenn Passwort keine Großbuchstaben enthält
						$meldung = "<div class='alert alert-danger'>Ihr neues Passwort entspricht nicht den mind. Vorraussetzungen</div>";
					}
				}
				else {
	    			//Fehlermeldung wenn Passwort keine Kleinbuchstaben enthält
	    			$meldung = "<div class='alert alert-danger'>Ihr neues Passwort entspricht nicht den mind. Vorraussetzungen</div>";
				}
			}
			else {
				$meldung = "<div class='alert alert-danger'>Ihr altes Passwort stimmt nicht mit dem bei uns eingetragenen Passwort überein. Prüfen Sie bitte Ihr Altes Passwort</div>";
			}
		}
		else {
			$meldung 	= "<div class='alert alert-danger'>Die alten Passwörter stimmen nicht überein. Bitte prüfen Sie Ihre Eingabe.</div>";
			$fehler 	= TRUE;
		}
	}

	echo $meldung;
?>
<h2>Passwort ändern</h2><br>
<div class="form">
	<form action="" method="POST" accept-charset="UTF-8">
		<div class="field-wrap">
			<label>
				Aktuelles Passwort<span class="req">*</span>
			</label>
			<input class="form-control" type="password" required autocomplete="off" name='PwOld'/>
		</div>
		<div class="field-wrap">
			<label>
				Aktuelles Passwort wiederholen<span class="req">*</span>
			</label>
			<input class="form-control" type="password" required autocomplete="off" name='PwOld2'/>
		</div>
		<div class="field-wrap">
			<label>
				Neue Passwort<span class="req">*</span>
			</label>
			<input class="form-control" type="password" required autocomplete="off" name='PwNew'/>
		</div>
			<div class="card">
			  <h6 class="card-header">Hinweise</h6>
			    <div class="card-body">
			      <ul>
			        <li>Ihr neues Passwort muss <strong>mind. 8 Zeichen</strong> lang sein</li>
			        <li>Das Passwort muss <strong>Groß- und Kleinbuchstaben</strong> enthalten sowie <strong>Zahlen</strong></li>
			      </ul>
			    </div>
			</div><br>
		<input type="submit" name="ChangePw" value="Passwort ändern" class="btn btn-green">
</div>
