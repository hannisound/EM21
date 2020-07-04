<?php
function sec_session_start() {
    $session_name = "sec_session_id";   // Set a custom session name
    $secure = false;

    // This stops JavaScript being able to access the session id.
    $httponly = true;

    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: index.php?site=error");
        exit();
    }

    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

    // Sets the session name to the one set above.
    session_name($session_name);

    session_start();            // Start the PHP session
    session_regenerate_id();    // regenerated the session, delete the old one.
}

//
// Benutzereingaben bereinigen
// 
function escape($value, $mysqli) {
	$value = $mysqli->real_escape_string($value);
	return $value;
}

//
//	Registierter Spieler wird in die Topliste eingetragen
//

function reg_eintrag_toplist($userID, $mysqli) {
	$sql 		= "SELECT userID FROM ".prefix."_toplist WHERE userID = ".$userID."";
	$einlesen 	= $mysqli->query($sql);
	$einzeln 	= mysqli_fetch_row($einlesen);
	if ($einzeln[0] == 0) {
		$eintragen = "INSERT INTO  ".prefix."_toplist (userID, preRound, finRound, special, total)
		 						VALUES (".$userID.", 0, 0, 0, 0)";
			if(!$mysqli->query($eintragen)) {
				printf("DB Error #0006: %s\n", $mysqli->error);
			}
	}
	else {
		echo "Fehler bei der Abfrage";
	}
}

//
// Sitetitle für Header auslesen und erstellen
//
function site_title($site, $mysqli) {
	$sql 		= "SELECT title, subtitle FROM ".prefix."_site_title WHERE site = '".$site."'";
	$result 	= $mysqli->query($sql);
	$result 	= mysqli_fetch_array($result);
	$site_title = "
		<div class='container text-center'>
			<h2>".$result['title']."</h2>
				<p class='lead'>".$result['subtitle']."</p>
  		</div>";
	return $site_title;
}

/* ************************* */
/* *** Benutzer anmelden *** */
/* ************************* */
function login($email, $password, $mysqli) {
	$email 		= escape($email, $mysqli);
	$password 	= escape($password, $mysqli);
    // Using prepared statements means that SQL injection is not possible.
    if ($stmt = $mysqli->prepare("SELECT id, username, password, email, active, status
				  FROM ".prefix."_users WHERE email = ? AND  active = 1 LIMIT 1"))
    {
      $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
      $stmt->execute();    // Execute the prepared query.
      $stmt->store_result();
      if ($stmt->num_rows == 0)
      {
        // Es wurden kein Eintrag gefunden und damit der Login abgebrochen
        $_SESSION['message'] = "Es wurden keine passenden Daten gefunden";
        return false;
      }
      else
      {
        // get variables from result.
        $stmt->bind_result($user_id, $username, $mysqli_password, $email, $active, $status);
        $stmt->fetch();

        if ( password_verify($password, $mysqli_password) )
        {
      	  // If the user exists we check if the account is locked
          // from too many login attempts
          if (checkbrute($user_id, $mysqli) == true)
          {
            // Account is locked
            // Send an email to user saying their account is locked
            $_SESSION['message'] = "checkbrute war negative";
            return false;
          }
          else
          {
            $_SESSION['email'] = $email;
        	  $_SESSION['active'] = $active;
            // Password is correct!
            // Get the user-agent string of the user.
            $user_browser = $_SERVER['HTTP_USER_AGENT'];

            // XSS protection as we might print this value
            $user_id = preg_replace("/[^0-9]+/", "", $user_id);
            $_SESSION['user_id'] = $user_id;

            // XSS protection as we might print this value
            //$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

            $_SESSION['username']       = $username;
            $_SESSION['login_string']   = hash('sha512', $mysqli_password . $user_browser);
            $_SESSION['logged_in']      = true;
            $_SESSION['last_active']    = time();
            $_SESSION['status']         = $status;
            $_SESSION['sonderzeichen']  = "ä";

            // Login successful.
            $_SESSION['message']        = "Alles war Erfolgreich";
            return true;
          }
        }
        else
        {
          // Password is not correct
          // We record this attempt in the database
          $now = time();
          if (!$mysqli->query("INSERT INTO ".prefix."_login_attempts(user_id, `time`) VALUES ('$user_id', '$now')"))
          {
            $_SESSION['message']       = "Fehler beim Eintragen der Fehlversuche";
            //$_SESSION['logged_in']     = false;
            return false;
          }
          else
          {
            $_SESSION['message'] = "Fehler mit dem Passwort";
            //$_SESSION['logged_in']      = false;
            return false;
          }
        }
      }
    }
    else
    {
    	$_SESSION['message'] = "Benutzerdaten wurden nicht gefunden";
    	return false;
    }
}

function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time
    $now = time();

    // All login attempts are counted from the past 2 hours.
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time
                                  FROM ".prefix."_login_attempts
                                  WHERE user_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);

        // Execute the prepared query.
        $stmt->execute();
        $stmt->store_result();

        // If there have been more than 5 failed logins
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    } else {
        // Could not create a prepared statement
        $_SESSION['message'] = "Fehler beim Checkbrute";
        header("Location: index.php?site=error");
        exit();
    }
}

function login_check($mysqli) {
    // Check if all session variables are set
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id      = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username     = $_SESSION['username'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT password
				      FROM ".prefix."_users
				      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter.
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string)
                {
                    // Prüfen wenn die Sitzung abgelaufen ist und gegebenfalls den User ausloggen oder Session verlängern
                    if (($_SESSION['last_active'] + 3600) <= $_SERVER['REQUEST_TIME'])
                    {
                      // Sitzung abgelaufen - Sitzungsvariablen löschen und Fehlermeldung eintragen
                      $_SESSION = array();
                      $_SESSION['message'] = "Sie waren längere Zeit inaktiv, daher haben wir Sie aus Sicherheitsgründen ausgeloggt.";
                      return false;
                    }
                    else
                    {
                        $_SESSION['last_active'] = time();
                        // Logged In!!!!
                        return true;
                    }
                } else {
                    // Not logged in
                    $_SESSION = array();
                    $_SESSION['message'] = "Sie haben keine Berechtigung diese Seite aufzurufen. Loggen Sie sich bitte neu ein. #1";
                    return false;
                }
            } else {
                // Not logged in
                $_SESSION = array();
                $_SESSION['message'] = "Sie haben keine Berechtigung diese Seite aufzurufen. Loggen Sie sich bitte neu ein. #2";
                return false;
            }
        } else {
            // Could not prepare statement
            $_SESSION = array();
            $_SESSION['message'] = "Sie haben keine Berechtigung diese Seite aufzurufen. Loggen Sie sich bitte neu ein. #3";
            return false;
        }
    } else {
        // Not logged in
        $_SESSION = array();
        $_SESSION['message'] = "Sie haben keine Berechtigung diese Seite aufzurufen. Loggen Sie sich bitte neu ein. #4";
        return false;
    }
}

/* ****************************** */
/* *** WM Start auslesen   **** */
/* ****************************** */

function wmStart($mysqli) {
	$sql 		= "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmStart'";
	$result 	= $mysqli->query($sql);
	$start 		= mysqli_fetch_array($result);
	$wmStart 	= $start['wert'];
	return $wmStart;
}

/* ****************************** */
/* *** WM Ende auslesen   **** */
/* ****************************** */

function wmEnd($mysqli) {
	$sql 		= "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmEnd'";
	$result 	= $mysqli->query($sql);
	$end 		= mysqli_fetch_array($result);
	$wmEnd 		= $end['wert'];
	return $wmEnd;
}

//
// Benutzer auslesen
//

function username($userID, $mysqli) {
	$sql 	= "SELECT username FROM ".prefix."_users WHERE ID = '".$userID."'";
	$result = $mysqli->query($sql);
	$user 	= mysqli_fetch_array($result);
	$username = $user['username'];
	return $username;
}

/* ****************************** */
/* *** Flaggencode erstellen *** */
/* ***************************** */
function flag($land, $size, $transparenz){
	$class = "";
	$start = array(
		"belgien" 		=> "Belgien",
		"deutschland" 	=> "Deutschland",
        "danemark"    	=> "Dänemark",
		"england" 		=> "England",
		"frankreich" 	=> "Frankreich",
		"island" 	  	=> "Island",
		"kroatien" 		=> "Kroatien",
		"polen" 	 	=> "Polen",
		"portugal" 		=> "Portugal",
		"russland" 		=> "Russland",
		"schweden"  	=> "Schweden",
		"schweiz" 		=> "Schweiz",
		"spanien" 		=> "Spanien",
		"finland"		=> "Finnland",
		"tuerkei"		=> "Türkei",
		"niederlande"	=> "Niederlande",
		"italien"		=> "Italien",
		"oesterreich"	=> "Österreich",
		"tschechien"	=> "Tschechien",
		"ukraine"		=> "Ukraine",
		"wales"			=> "Wales",
		"ausstehend"	=> "Playoff-Sieger D/A",
		"ausstehend"	=> "Playoff-Sieger A/D",
		"ausstehend"	=> "Playoff-Sieger C",
		"ausstehend"	=> "Playoff-Sieger B",
		"ausstehend"	=> "Ausstehend");
	$search = array_search($land, $start);
	if ($transparenz == 1) {
		if ($search == "ausstehend") {
			$class = "noBorder";
		}
		else {
			$class = "";
		}
		$flag = "<img src='images/flags/".$search.".png' width='".$size."px' height='auto' class='transparenz100 ".$class."'>";
	} else {
		$flag = "<img src='images/flags/".$search.".png' width='".$size."px' height='auto' class='transparenz30 ".$class."'>";
	}
	return $flag;
}

/* ******************************** */
/* *** MannschaftID auslesen   **** */
/* ******************************** */

function teamID($mysqli, $name) {
	$sql 	= "SELECT ID FROM ".prefix."_team WHERE name = '".$name."'";
	$result = $mysqli->query($sql);
	$ID 	= mysqli_fetch_array($result);
	$teamID = $ID['ID'];
	return $teamID;
}

//
// Open Grouptipps
//
function open_group_tipps($userID, $mysqli) {
  // Offene Tipps zusammenzählen
  $time = time();
  // Gefixt: BETWEEN anpassen an EM21 (6 Gruppen)
	$sql5 			= "SELECT `ID`, `time` FROM ".prefix."_games WHERE `grp` BETWEEN '1' AND '6'";
	$result5 		= $mysqli->query($sql5);
	$tippsCounter	= $result5->num_rows;
	while ($game = $result5->fetch_array()) {
		$sql6 			= "SELECT tipp1, tipp2 FROM ".prefix."_tipp WHERE userID = '$userID' AND gameID = ".$game['ID']."";
		$result6 		= $mysqli->query($sql6);
		if ($result6->num_rows == 0) {
			$tippsCounter;
		}
		else {
			while ($tipps = $result6->fetch_array()) {
				if($tipps['tipp1'] != NULL || $tipps['tipp2'] != NULL) {
					$tippsCounter--;
				}
				else {
					if ($game['time'] < $time) {
						$tippsCounter--;
					}
					else {
						$tippsCounter;

					}
				}
			}
		}
	}
  if ($tippsCounter == 0) {
    echo "<p class='text-success font-weight-bold'>Sie haben alle Gruppenspiele getippt.</p>";
  }
  else {
    echo "<p class='text-danger font-weight-bold'>Sie haben noch $tippsCounter Gruppenspiele nicht getippt.</p>";
  }
}

//
// Open Grouptipps Final
//
function open_final_tipps($userID, $mysqli) {
  // Offene Tipps zusammenzählen
  $time = time();
	// GEFIXT: grp BETWEEN anpassen an EM21
	$sql5 			= "SELECT `ID`, `time` FROM ".prefix."_games WHERE `grp` BETWEEN '7' AND '13'";
	$result5 		= $mysqli->query($sql5);
	$tippsCounter	= $result5->num_rows;
	while ($game = $result5->fetch_array()) {
		$sql6 			= "SELECT tipp1, tipp2 FROM ".prefix."_tipp WHERE userID = '$userID' AND gameID = ".$game['ID']."";
		$result6 		= $mysqli->query($sql6);
		if ($result6->num_rows == 0) {
			$tippsCounter;
		}
		else {
			while ($tipps = $result6->fetch_array()) {
				if($tipps['tipp1'] != NULL || $tipps['tipp2'] != NULL) {
					$tippsCounter--;
				}
				else {
					if ($game['time'] < $time) {
						$tippsCounter--;
					}
					else {
						$tippsCounter;

					}
				}
			}
		}
	}
  if ($tippsCounter == 0) {
    echo "<p class='text-success font-weight-bold'>Sie haben alle Finalspiele getippt.</p>";
  }
  else {
    echo "<p class='text-danger font-weight-bold'>Sie haben noch $tippsCounter Finalspiele nicht getippt.</p>";
  }
}

function open_special_tipps($userID, $mysqli) {
  $time 		= time();
  // Offene Spezialtipps GruppenSieger & Gruppenzweite auslesen
  $sql 		= "SELECT ID, grpSieger, grpZweiter FROM ".prefix."_grpSieger WHERE userID = '$userID'";
  $result 	= $mysqli->query($sql);
  $grpSieger 	= 12;
  while ($group = mysqli_fetch_array($result)) {
    if ($group['grpSieger'] != NULL) {
      $grpSieger--;
    }
    if ($group['grpZweiter'] != NULL) {
      $grpSieger--;
    }
  }

  // Offene Spezialtipps Endstation auslesen
  $sql2 		= "SELECT ID FROM ".prefix."_meister WHERE userID = '$userID'";
  $result2 		= $mysqli->query($sql2);
  if (mysqli_num_rows($result2) === 0) {
    $endstation = 1;
  }
  else {
    $endstation = 0;
  }

  // Offene Spezialtipps Torschützenkönig auslesen
  $sql3 		= "SELECT ID FROM ".prefix."_torkoenig WHERE userID = '$userID'";
  $result3 	= $mysqli->query($sql3);
  if (mysqli_num_rows($result3) === 0) {
    $torkoenig = 1;
  }
  else {
    $torkoenig = 0;
  }

  // Offene Spezialtipps EURO Meister auslesen
  $sql4		 = "SELECT ID FROM ".prefix."_meister WHERE userID = '$userID'";
  $result4	 = $mysqli->query($sql4);
  if (mysqli_num_rows($result4) === 0) {
    $meister = 1;
  }
  else {
    $meister = 0;
  }

  // Offene Spezialtipps zusammenzählen
  $spezial 	= $torkoenig + $meister + $endstation + $grpSieger;
  if ($spezial == 0) {
	  echo "<p class='text-success font-weight-bold'>Sie haben alle Spezialtipps getippt.</p>";
  }
  else {
	  // Spezialtipps nicht fix eintragen!
	  echo "<p class='text-danger font-weight-bold'>Es stehen noch $spezial von 15 Spezialtipps aus.</p>";
  }
}

//
//  Tipps eintragen
//

function saveTipp($gameID, $tipp1, $tipp2, $mysqli) {
		$gameID		= $gameID;
	  	$tipp1		= $tipp1;
		$tipp2		= $tipp2;
		$userID		= $_SESSION['user_id'];
		$echtzeit	= time();

		// Prüfen wenn Variable (Tipp1 & Tipp2) leer sind
		if ($tipp1 == "") {
			$tipp1 = NULL;
		}
		if ($tipp2 == "") {
			$tipp2 = NULL;		
		}

		$sql 		= "SELECT time FROM ".prefix."_games WHERE ID = $gameID";
		$result 	= $mysqli->query($sql);
		$anpiff		= mysqli_fetch_array($result);
		// Abfrage wenn ein Ändern noch Erlaubt ist (Spielanpiff)
		if ( $anpiff['time'] <= $echtzeit )
		{
			// Wenn Spiel angepiffen keine Eintragung
			echo "<div class='alert alert-danger text-center'>Leider ist die Zeit zum Tippen eines Spiels abgelaufen</div>";
		}
		else
		{
			// Überprüfen wenn ein Eintrag vorhanden ist
			$stmt = $mysqli->prepare("SELECT ID FROM ".prefix."_tipp WHERE `gameID` = ? AND `userID` = ?");
			$stmt->bind_param("ii", $gameID, $userID);
			$stmt->execute();
			$result = $stmt->get_result();
			$count = $result->num_rows;
			if($count == 1)
			{
				// Daten Updaten wenn ein Eintrag vorhanden ist
				// ID des Eintrages auslesen
			  	$sql 	= "SELECT `ID` FROM ".prefix."_tipp WHERE `gameID` = $gameID AND `userID` = $userID";
				$result = $mysqli->query($sql);
        		$ID 	= mysqli_fetch_array($result);
				$sql1 	= "UPDATE ".prefix."_tipp SET tipp1 = ?, tipp2 = ? WHERE `ID` = ?";
				$stmt 	= $mysqli->prepare($sql1);
        		$stmt->bind_param("iii", $tipp1, $tipp2, $ID['ID']);
				if (!$stmt->execute()) {
				echo "<div class='alert alert-danger'>".$mysqli->error."</div>";
				}
			}
			else
			{
				// Daten neu Eintragen wenn kein Eintrag vorhanden ist
				$stmt1 = $mysqli->prepare("INSERT INTO ".prefix."_tipp (`gameID`, `userID`, `tipp1`, `tipp2`) VALUES (?, ?, ?, ?)");
				$stmt1->bind_param("iiii", $gameID, $userID, $tipp1, $tipp2);
				if (!$stmt1->execute()) {
				echo "<div class='alert alert-danger'>".$mysqli->error."</div>";
				}
			}
		}
}

//
// Ergnisse der Spiele speichern
function saveErgebnis($gameID, $ergebnis1, $ergebnis2, $mysqli, $hze) {
		$gameID			   	= $gameID;
	  	$ergebnis1		 	= $ergebnis1;
		$ergebnis2		 	= $ergebnis2;
		$echtzeit		   	= time();

		// Abfrage wenn der angemeldete User das Ergebnis ändern darf
		if ($_SESSION['status'] <= 2)
		{
			// Wenn Spiel angepiffen keine Eintragung
			echo "<p class='error'>Ihnen ist leider nicht erlaubt Ergebnisse einzutragen</p>";

			// Logeintrag? Darüber nachdenken wenn man Logs überhaupt erstellen will/muss/brauch
		}
		else
		{
			// Prüfen wenn Halbzeitergebnis gesetzt ist und das Halbzeitergebnis eintragen
			if ($hze == "1") {
				// Wenn HZE gesetzt ist Halbzeitergebnis eintragen
				$sql 	= "UPDATE ".prefix."_games SET h_ergebnis_1 = '$ergebnis1', h_ergebnis_2 = '$ergebnis2' WHERE `ID` = '$gameID'";
				if(!$mysqli->query($sql)) {
					printf("DB Fehler: %s\n", $mysqli->error);
				}
			}
			else {
				if ($ergebnis1 == "") {
					// Es brauch nichts eingetragen zu werden wenn das Ergebnis leer ist
				}
				else {
					// Wenn HZE nicht gesetzt ist Ergenis eintragen
					$sql 	= "UPDATE ".prefix."_games SET ergebnis_1 = '$ergebnis1', ergebnis_2 = '$ergebnis2' WHERE `ID` = '$gameID'";
					if(!$mysqli->query($sql)) {
						printf("DB Fehler2: %s\n", $mysqli->error);
					}
				}
			}
		}
}
		

/* ************************************ */
/* *** Punktberechnung Mannschaften *** */
/* ************************************ */
function updateGroup($mysqli)
{
	// Alle gespielten Gruppenspiele auslesen und danach Punkte addieren je nach Sieg oder Unentschieden
	// Gefixt: BETWEEN anpassen an die EM21 (6 Gruppen)
	$sql 		= "SELECT geg_1, geg_2, ergebnis_1, ergebnis_2 FROM ".prefix."_games WHERE grp BETWEEN '1' AND '6' AND ergebnis_1 IS NOT NULL AND ergebnis_2 IS NOT NULL";
	$result		= $mysqli->query($sql);
	while ($row = mysqli_fetch_array($result)) {
		if($row['ergebnis_1'] == $row['ergebnis_2']) {
			$sql2 	= "UPDATE ".prefix."_team SET punkte = `punkte`+1 WHERE `ID` = '".$row['geg_1']."'";
			$sql3 	= "UPDATE ".prefix."_team SET punkte = `punkte`+1 WHERE `ID` = '".$row['geg_2']."'";

			// Gegner 2 einen Punkt beim Unentschieden geben
			if(!$mysqli->query($sql3)) {
				printf("Es ist ein Fehler beim Updaten der Gruppenpunkte aufgetreten");
			}
		}
		elseif ($row['ergebnis_1'] > $row['ergebnis_2']) {
			$sql2 	= "UPDATE ".prefix."_team SET punkte = `punkte`+3 WHERE `ID` = '".$row['geg_1']."'";
		}
		elseif ($row['ergebnis_1'] < $row['ergebnis_2']) {
			$sql2 	= "UPDATE ".prefix."_team SET punkte = `punkte`+3 WHERE `ID` = '".$row['geg_2']."'";
		}

		if(!$mysqli->query($sql2)) {
				printf("Es ist ein Fehler beim Updaten der Gruppenpunkte aufgetreten");
		}
		else {
		}
	}
}


/* ************************************** */
/* *** Anzahl Spiele der Mannschaften *** */
/* ************************************** */

function updateGames($mysqli) {
	// Scriptlaufzeit berechnen
	$beginn = microtime(true);
	// Alle Mannschaften auslesen und danach gespielte Spiele per COUNT zusammenrechnen und in DB Eintragen 
	// Gefixt: LIMIT auf EM21 anpassen (24)
	$sql = "SELECT ID FROM ".prefix."_team LIMIT 24";
	$result = $mysqli->query($sql);
	while ($row = mysqli_fetch_array($result)) {
		// Gefixt: BETWEEN anpassen an die EM21 (6 Gruppen)
		$sql2 = "SELECT COUNT(ID) AS Anzahl FROM ".prefix."_games WHERE (geg_1 = ".$row['ID']." OR geg_2 = ".$row['ID'].") AND (ergebnis_1 IS NOT NULL AND ergebnis_2 IS NOT NULL) AND grp BETWEEN '1' AND '6'";
		$result2 = $mysqli->query($sql2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$sql = "UPDATE ".prefix."_team SET games = ".$row2['Anzahl']." WHERE ID = '".$row['ID']."'";
			if (!$mysqli->query($sql)) {
				printf("Es ist ein Fehler beim Updaten aufgetreten");
			}
		}
	}
	// Scriptlaufzeit auswerten
	$dauer = microtime(true) - $beginn; 
	$ergebnis = "Verarbeitung des Skripts UpdatesGames_2: $dauer Sek.<br>";
	return $ergebnis;
}

function updateSUN($mysqli) {
	// Mannschaften aus der Vorrunde auslesen
	// Gefixt: BETWEEN anpassen an die EM21 (6 Gruppen)
	$sql		= "SELECT id FROM ".prefix."_team WHERE grp BETWEEN '1' AND '6'";
	$result		= $mysqli->query($sql);
	while ($teams = mysqli_fetch_array($result))
	{
		$team 	= $teams['id'];
		$s		= 0;
		$u		= 0;
		$n		= 0;
		$sql2 	= "SELECT ergebnis_1, ergebnis_2, geg_1, geg_2 FROM ".prefix."_games WHERE (geg_1 = $team OR geg_2 = $team) AND (ergebnis_1 IS NOT NULL AND ergebnis_2 IS NOT NULL)";
		$result2 = $mysqli->query($sql2);
		while($sun = mysqli_fetch_array($result2))
		{
			if ($sun['geg_1'] == $team)
			{
				// S/U/N erhöhen je nach Ausgang des Spieles
				// Bei Sieg $s erhöhen
				if ($sun['ergebnis_1'] > $sun['ergebnis_2'])
				{
					$s++;
				}
				// Bei Niederlage $n erhöhen
				elseif ( $sun['ergebnis_1'] < $sun['ergebnis_2'])
				{
					$n++;
				}
				// Bei Unentschieden bei Bedarf hochzählen
				elseif ( $sun['ergebnis_1'] == $sun['ergebnis_2'])
				{
					// Bei Unentschieden $u hochzählen
					$u++;
				}
			}
			else
			{
				// S/U/N erhöhen je nach Ausgang des Spieles
				// Bei Sieg $s erhöhen
				if ($sun['ergebnis_2'] > $sun['ergebnis_1'])
				{
					$s++;
				}
				// Bei Niederlage $n erhöhen
				elseif ( $sun['ergebnis_2'] < $sun['ergebnis_1'])
				{
					// Bei Niederlage $n hochzählen
					$n++;
				}
				// Bei Unentschieden $u hochzählen
				elseif ( $sun['ergebnis_1'] == $sun['ergebnis_2'])
				{
					// Bei Unentschieden $u hochzählen
					$u++;
				}
			}
			$verhaeltniss = "$s/$u/$n";
		}
		$sql = "UPDATE ".prefix."_team SET `sun` = '$verhaeltniss' WHERE ID = $team";
		if (!$mysqli->query($sql)) {
			printf("Es ist ein Fehler beim Speichern von SUN aufgetreten");
		}
	}
}

function updateTore($mysqli) {
	// Mannschaften aus der Vorrunde auslesen
	// Gefixt: BETWEEN anpassen an EM21 (6 Gruppen)
	$sql		= "SELECT ID FROM ".prefix."_team WHERE grp BETWEEN '1' AND '6'";
	$result		= $mysqli->query($sql);
	while ($torv = mysqli_fetch_array($result)) {
		$team 	= $torv['ID'];
		$tor1 	= 0;
		$tor2 	= 0;
		$sql2 	= "SELECT ergebnis_1, ergebnis_2, geg_1, geg_2 FROM ".prefix."_games WHERE (geg_1 = $team OR geg_2 = $team) AND (ergebnis_1 IS NOT NULL AND ergebnis_2 IS NOT NULL)";
		$result2 = $mysqli->query($sql2);
		while($tore = mysqli_fetch_array($result2))
		{
			// Torverhältniss berechnen
			if ($tore['geg_1'] == $team)
			{
				$tor1 = $tor1+$tore['ergebnis_1'];
				$tor2 = $tor2+$tore['ergebnis_2'];
			}
			elseif ($tore['geg_2'] == $team)
			{
				$tor1 = $tor1+$tore['ergebnis_2'];
				$tor2 = $tor2+$tore['ergebnis_1'];
			}
			$tor = "$tor1:$tor2";
		}
		$sql = "UPDATE ".prefix."_team SET `tore` = '$tor' WHERE ID = $team";
		if (!$mysqli->query($sql)) {
			printf("Es ist ein Fehler beim Eintragen der Tore passiert");
		}
	}
}

function updateDifferenz($mysqli) {
	// Mannschaften aus der Datenbank auslesen
	// Gefixt: BETWEEN anpassen an EM21 (6 Gruppen)
	$sql 		= "SELECT ID FROM ".prefix."_team WHERE grp BETWEEN '1' AND '6'";
	$result		= $mysqli->query($sql);
	while ($calc = mysqli_fetch_array($result))
	{
		$differenz 	= 0;
		$diffe		= 0;
		$sql2 		= "SELECT ergebnis_1, ergebnis_2, geg_1, geg_2 FROM ".prefix."_games WHERE (geg_1 = ".$calc['ID']." OR geg_2 = ".$calc['ID'].") AND (ergebnis_1 IS NOT NULL AND ergebnis_2 IS NOT NULL)";
		$result2 	= $mysqli->query($sql2);
		while($diff = mysqli_fetch_array($result2))
		{
			// Differenz berechnen
			if ($diff['geg_1'] == $calc['ID'])
			{
				$differenz = $diff['ergebnis_1']-$diff['ergebnis_2'];
			}
			else
			{
				$differenz = $diff['ergebnis_2']-$diff['ergebnis_1'];
			}
			$diffe = $diffe+$differenz;
		}
		$sql = "UPDATE ".prefix."_team SET `differenz` = $diffe WHERE ID = ".$calc['ID']."";
		if (!$mysqli->query($sql)) {
			printf("Es ist leider ein Fehler beim Eintragen der Differenz aufgetreten");
		}
	}
}

//
// Speichern des letzten Platzes
//
function saveLastPlace($mysqli) {
	$place 		= 1;
	$lastPoints	= 0;
	$lastPlace  = 0;
	$sql 		= "SELECT total, userID FROM ".prefix."_toplist ORDER BY total DESC";
	$result 	= $mysqli->query($sql);
	while ($row = mysqli_fetch_array($result)) {
		if ($lastPoints == $row['total']) {
			// Last Place nicht ändern
			$sql 	= "UPDATE ".prefix."_toplist SET lastPlace = '$lastPlace' WHERE userID = '".$row['userID']."'";
		}
		else {
			// LastPoints ändern/speichern
			$lastPoints = $row['total'];
			$lastPlace++;
			$sql 		= "UPDATE ".prefix."_toplist SET lastPlace = '$place' WHERE userID = '".$row['userID']."'";
			$lastPlace 	= $place;
		}

		if (!$mysqli->query($sql)) {
			printf("Es ist ein Fehler beim speichern des Last Place aufgetreten");
		}
		else {
			$place++;
		}
	}
}

//
// Zur Anzeige des vorherigen Platzes
//
function lastPlace($place, $lastPlace) {
	if ($lastPlace == 0) {
	 	$ergebnis  = "<div class='text-muted font-weight-light'><i class='fa fa-minus'></i> 0</div>";
	}
	elseif ($place > $lastPlace) {
		$differenz = $lastPlace - $place;
		$ergebnis  = "<div class='text-danger font-weight-light'><i class='fa fa-arrow-down'></i> $differenz</div>";
	}
	elseif ($place < $lastPlace) {
		$differenz = $lastPlace - $place;
		$ergebnis  = "<div class='text-success font-weight-light'><i class='fa fa-arrow-up'></i>+$differenz</div>";
	}
	elseif ($place == $lastPlace) {
		$ergebnis  = "<div class='text-muted font-weight-light'><i class='fa fa-minus'></i> 0</div>";
	}
	return $ergebnis;
}

function userPointsGames($mysqli) {
	$starttime			= microtime(true);	
	$sql 				= "SELECT userID, special FROM ".prefix."_toplist";
	$result 			= $mysqli->query($sql);
	while ($row = mysqli_fetch_array($result)) {
		$sql2			= "SELECT gameID, tipp1, tipp2 FROM ".prefix."_tipp WHERE userID = '".$row['userID']."' AND tipp1 IS NOT NULL AND tipp2 IS NOT NULL";
		$result2 		= $mysqli->query($sql2);
		$preRound 		= 0;
		$finRound		= 0;
		while ($tipp = mysqli_fetch_array($result2)) {
			$sql3 		= "SELECT grp, ergebnis_1, ergebnis_2 FROM ".prefix."_games WHERE ID = '".$tipp['gameID']."' AND ergebnis_1 IS NOT NULL AND ergebnis_2 IS NOT NULL";		// AND `time` <= ".$time."
			$result3 	= $mysqli->query($sql3);
			while ($game = mysqli_fetch_array($result3)) {
				if ($game['grp'] <= 8) {
					if ($tipp['tipp1'] == $game['ergebnis_1'] AND $tipp['tipp2'] == $game['ergebnis_2']) {
						$preRound = $preRound+3;
					}
					elseif ($game['ergebnis_1'] < $game['ergebnis_2'] AND $tipp['tipp1'] < $tipp['tipp2']) {
						$preRound = $preRound+1;
					}
					elseif ($game['ergebnis_1'] > $game['ergebnis_2'] AND $tipp['tipp1'] > $tipp['tipp2']) {
						$preRound = $preRound+1;
					}
					elseif ($game['ergebnis_1'] == $game['ergebnis_2'] AND $tipp['tipp1'] == $tipp['tipp2']) {
						$preRound = $preRound+1;
					}
				}
				elseif ($game['grp'] >= 9) {
					if ($tipp['tipp1'] == $game['ergebnis_1'] AND $tipp['tipp2'] == $game['ergebnis_2']) {
						$finRound = $finRound+3;
					}
					elseif ($game['ergebnis_1'] < $game['ergebnis_2'] AND $tipp['tipp1'] < $tipp['tipp2']) {
						$finRound = $finRound+1;
					}
					elseif ($game['ergebnis_1'] > $game['ergebnis_2'] AND $tipp['tipp1'] > $tipp['tipp2']) {
						$finRound = $finRound+1;
					}
					elseif ($game['ergebnis_1'] == $game['ergebnis_2'] AND $tipp['tipp1'] == $tipp['tipp2']) {
						$finRound = $finRound+1;
					}
				}

				// Wenn Punkte der Vorrunde und der Finalrunde berechnet sind in DB eintragen
				$total = $preRound + $finRound + $row['special'];
				$sql = "UPDATE ".prefix."_toplist SET preRound = '$preRound', finRound = '$finRound', total = '$total' WHERE userID = '".$row['userID']."'";
				if (!$mysqli->query($sql)) {
					printf("Beim Eintragen der Vorrundepunkte ist ein Fehler passiert");
				}
			}
		}
	}
	$useTime			= microtime(true)-$starttime;
	return $useTime;
}

function updateSpecialPoints($mysqli) {
	// Special Points auf 0 setzen für weitere korrekte berechnung
	$sql = "UPDATE ".prefix."_toplist SET special = '0'";
	if (!$mysqli->query($sql)) {
		print_r("Es ist ein Fehler beim Zurücksetzen der Specialpunkte aufgetreten.");
	}
	else {
		$specialPoints = 0;
		$sql 		= "SELECT userID, special FROM ".prefix."_toplist";
		$result 	= $mysqli->query($sql);
		while ($user = mysqli_fetch_array($result)) {
			// Meistertipp prüfen
			$sql2 = "SELECT meisterID FROM ".prefix."_meister WHERE userID = '".$user['userID']."'";
			$result2 = $mysqli->query($sql2);
			$meisterTipp = mysqli_fetch_array($result2);
			$meisterTipp = $meisterTipp['meisterID'];

			$sql3 = "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmMeister'";
			$result3 = $mysqli->query($sql3);
			$meister = mysqli_fetch_array($result3);
			$meister = $meister['wert'];

			if (empty($meisterTipp)) {
				# code...
			}
			else {
				if ($meister == $meisterTipp) {
					$specialPoints = $specialPoints+10;
				}
			}

			// Torschützenkönigtipp prüfen
			$sql2 = "SELECT spielerID FROM ".prefix."_torkoenig WHERE userID = '".$user['userID']."'";
			$result2 = $mysqli->query($sql2);
			$torkoenigTipp = mysqli_fetch_array($result2);
			$torkoenigTipp = $torkoenigTipp['spielerID'];

			$sql3 = "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmTorkoenig'";
			$result3 = $mysqli->query($sql3);
			$torkoenig = mysqli_fetch_array($result3);
			$torkoenig = $torkoenig['wert'];

			if (empty($torkoenigTipp)) {
				# code...
			}
			else {
				if ($torkoenig == $torkoenigTipp) {
					$specialPoints = $specialPoints+10;
				}
			}

			// Endstation prüfen
			$sql2 = "SELECT endStationID FROM ".prefix."_endstation WHERE userID = '".$user['userID']."'";
			$result2 = $mysqli->query($sql2);
			$endStationTipp = mysqli_fetch_array($result2);
			$endStationTipp = $endStationTipp['endStationID'];

			$sql3 = "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmEndstation'";
			$result3 = $mysqli->query($sql3);
			$endstation = mysqli_fetch_array($result3);
			$endstation = $endstation['wert'];

			if (empty($endStationTipp)) {
				# code...
			}
			else {
				if ($endstation == $endStationTipp) {
					$specialPoints = $specialPoints+5;
				}
			}

			// Gruppensieger/Zweitertipp prüfen
			$sql2 = "SELECT grpSieger, grpZweiter, grpID FROM ".prefix."_grpSieger WHERE userID = '".$user['userID']."'";
			$result2 = $mysqli->query($sql2);
			while ($grpSiegerTipp = mysqli_fetch_array($result2)) {
				$sql3 = "SELECT name FROM ".prefix."_grp WHERE ID = '".$grpSiegerTipp['grpID']."'";
				$result3 = $mysqli->query($sql3);
				$grpName = mysqli_fetch_array($result3);
				$grpName = $grpName['name'];

				$sql4 = "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'grpSieger_$grpName'";
				$result4 = $mysqli->query($sql4);
				$grpSieger = mysqli_fetch_array($result4);
				$grpSieger = $grpSieger['wert'];

				if (empty($grpSiegerTipp['grpSieger'])) {
					# code...
				}
				else {
					if ($grpSieger == $grpSiegerTipp['grpSieger']) {
						$specialPoints = $specialPoints+3;
					}
				}

				$sql5 = "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'grpZweiter_$grpName'";
				$result5 = $mysqli->query($sql5);
				$grpZweiter = mysqli_fetch_array($result5);
				$grpZweiter = $grpZweiter['wert'];

				if (empty($grpSiegerTipp['grpZweiter'])) {
					# code...
				}
				else {
					if ($grpZweiter == $grpSiegerTipp['grpZweiter']) {
						$specialPoints = $specialPoints+3;
					}
				}
			}
			// Specialpoints für den User Eintragen
			$sql6 = "UPDATE ".prefix."_toplist SET special = '$specialPoints', total = `preRound`+`finRound`+$specialPoints WHERE userID = '".$user['userID']."'";
				if (!$mysqli->query($sql6)) {
					print_r("Es ist ein Fehler beim Eintragen der Specialpunkte aufgetreten!");
				}
				else {
					$specialPoints = 0;
				}
		} // Erstes While
		echo "<span class='text-success'>Special Ergebniss wurde Erfolgreich eingetragen und alle Punkte aktualisiert</span>";
	}
}

/* ******************** */
/* ** 3-4 Next Games ** */
/* ******************** */
// TODO: Code optimieren? Noch irgend eine Idee? Gruppen Abfrage (if(<6)) dymanischer gestalten für Zukunft?
// TODO: Mobile Ansicht muss noch optimiert werden
function NextGames($mysqli, $userID) 
{
	$time = time();
	// Prüfen wenn das Tippspiel schon gestartet ist
	if($time > wmEnd($mysqli)) {
		// Tippspiel ist schon beendet, es gibt keine Next Games mehr - damit wird keine Anzeige/Ausgabe mehr benötigt
		$container = '';
	}
	else {
		// Next Games Anzeige ab hier aufbauen
		// Header/Panel Konstrukt vorbereiten
		$container = '<div class="card">
      					<div class="card-body">
      						<h3>Die nächsten Spiele:</h3>
      						<table class="table table-sm sm-table text-center" style="margin-bottom: 0px; !important">
      						<tr>
								<th>Zeit</th>
								<th>Spiel</th>
								<th>Tipp</th>
							</tr>';

		// SQL Abfrage zusammenstellen (Nächsten 4 Spiele auslesen)
		$sql = "SELECT g.ID, t.name AS geg_1, t2.name AS geg_2, t.short_name AS short_name1, t2.short_name AS short_name2, g.time, g.grp FROM ".prefix."_games g
      				INNER JOIN ".prefix."_team t ON (g.geg_1 = t.id)
      				INNER JOIN ".prefix."_team t2 ON (g.geg_2 = t2.id)
			  	WHERE `time` >= '".$time."' ORDER BY `g`.`time` ASC LIMIT 4";
		if(!$result = $mysqli->query($sql)) {
			echo $mysqli->errno;
		}
		
		// Die nächsten 4 Spiele ausgeben
		while ($game = $result->fetch_array()) {
			// Die entsprechenden Tipps zu den Spielen auslesen
			$sql2 = "SELECT tipp1, tipp2 FROM ".prefix."_tipp WHERE gameID = '".$game['ID']."' AND userID = '$userID'";
			if(!$result2 = $mysqli->query($sql2)) {
				echo $mysqli->error;
			}

			// Prüfen wenn "Bearbeiten" Knopf da sein darf
			// Ergebnis entsprechend aufbereiten - Wenn Spiel schon begonnen hat keinen Button mehr anzeigen
			if ($game['time'] <= $time) {
				$pencil = '';
			}
			else {
				// Prüfen in welcher Gruppe das Spiel ist und den Link entsprechend anpassen (gtipps/ftipps)
				if ($game['grp'] >= 6) {
					// Edit Button für ftipps vorbereiten
					$pencil = '<a href="index.php?site=ftipps#'.$game['ID'].'" class="btn btn-green btn-sm"><i class="fa fa-pencil-square-o"></i></a>';
				}
				else {
					// Edit Button für gtipps vorbereiten
					$pencil = '<a href="index.php?site=gtipps#'.$game['ID'].'" class="btn btn-green btn-sm"><i class="fa fa-pencil-square-o"></i></a>';
				}
			}

			// Prüfen wenn Ergebnis leer ist 
			if ($result2->num_rows != 0) {
				while ($tipp = $result2->fetch_array()) {
					// Tipps verarbeiten - Wenn Tipps leer sind die Ausgabe vorbereiten bzw. den Tipp aufbereiten wenn getippt
					if ($tipp['tipp1'] == NULL || $tipp['tipp2'] == NULL) {
						$tipp = '<span class="text-danger">Noch nicht getippt!</span>';
					}
					else {
						$tipp = ''.$tipp['tipp1'].' : '.$tipp['tipp2'].'';
					}

					// Ausgabe vorbereiten
					$container .= '	
					<tr>
						<td data-label="Uhrzeit">
							'.date('d.m.Y H:i', $game['time']).' Uhr
						</td>
						<td data-label="Spiel">
							<span class="left">'.flag($game['geg_1'], 30, 1).'</span>
							<span class="long_name">'.$game['geg_1'].'</span> 
							<span class="short_name">'.$game['short_name1'].'</span> : 
							<span class="short_name">'.$game['short_name2'].'</span> 
							<span class="long_name">'.$game['geg_2'].'</span>
							<span class="right">'.flag($game['geg_2'], 30, 1).'</span>
						</td>
						<td data-label="Tipp">
							'.$tipp.' '.$pencil.'
						</td>
					</tr>';
				}
			}
			else {
				// Da kein Eintrag vorhanden ist, gibt es keinen Tipp
				$tipp = '<span class="text-danger">Noch nicht getippt!</span>';

				// Ausgabe vorbereiten
				$container .= '	
				<tr>
					<td data-label="Uhrzeit">
						'.date('d.m.Y H:i', $game['time']).' Uhr
					</td>
					<td data-label="Spiel">
						<span class="left">'.flag($game['geg_1'], 30, 1).'</span>
						<span class="long_name">'.$game['geg_1'].'</span> 
						<span class="short_name">'.$game['short_name1'].'</span> : 
						<span class="short_name">'.$game['short_name2'].'</span> 
						<span class="long_name">'.$game['geg_2'].'</span>
						<span class="right">'.flag($game['geg_2'], 30, 1).'</span>
					</td>
					<td data-label="Tipp">
						'.$tipp.' '.$pencil.'
					</td>
				</tr>';
			}
		}
		$container .= '</table></div></div>';
	}
	return $container;
}

//
// Startseite gtipps/ftipps für das Card-Deck (Tipps)
//
function gftipps($mysqli) {
	$sql = "SELECT time FROM ".prefix."_games WHERE grp = 6 ORDER BY time DESC LIMIT 0,1";
	$result = $mysqli->query($sql);
	$time = mysqli_fetch_array($result);
	$dbTime = $time['time'];
	if( time() > $dbTime) {
		$tipp = "ftipps";
	}
	else {
		$tipp = "gtipps";
	}
	return $tipp;
}

/*******************************/
/* User Group - Remove Member  */
/*******************************/
function removeMember($member, $grpID, $mysqli) {
	$sql 	= "SELECT ID FROM ".prefix."_users WHERE username = '$member'";
	$result = $mysqli->query($sql);
	$userID = mysqli_fetch_array($result);
	$userID = $userID['ID'];

	$sql2 	= "DELETE FROM ".prefix."_userGrpMember WHERE userID = '$userID' AND userGrpID = '$grpID'";
	if (!$mysqli->query($sql2)) {
		echo "<p class='alert alert-danger'>Es ist ein Fehler beim Entfernen aufgetreten</p>";
	}
}

/* ***************************************** */
/* *** Torschützenkönignamen auslesen   **** */
/* ***************************************** */

function torKoenig($spielerID ,$mysqli) {
	$sql 		= "SELECT CONCAT (vorname, ' ',name) AS fullName FROM ".prefix."_spieler WHERE ID = '$spielerID'";
	$result 	= $mysqli->query($sql);
	$spieler	= mysqli_fetch_array($result);
	$spieler 	= $spieler['fullName'];
	return $spieler;
}

/* *********************************** */
/* *** Button disabled schalten   **** */
/* *********************************** */

function disabled($mysqli) {
	$sql 		= "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmStart'";
	$result 	= $mysqli->query($sql);
	$wmStart 	= mysqli_fetch_array($result);
	if (time() >= $wmStart['wert']) {
		$disabled = "disabled='disabled'";
	}
	else {
		$disabled = "";
	}
	return $disabled;
}

function PwReset($mysqli, $userID) {
	$neuesPw = generateRandomString(12);
	$neuerSalt = password_hash($neuesPw, PASSWORD_DEFAULT);

	$sql = "UPDATE ".prefix."_users SET password = '$neuerSalt' WHERE ID = '$userID'";
	if (!$mysqli->query($sql)) {
		echo "Es ist ein Fehler beim Passwort Reset aufgetreten";
	}
	else {
		$sql = "SELECT username, email FROM ".prefix."_users WHERE ID = $userID";
		$result = $mysqli->query($sql);
		$username = $result->fetch_array();

		// Template mit dem Mailbody laden und für den Versand vorbereiten
	    $mailbody = file_get_contents( 'email/PwResetBody.txt' );
	    // Platzhalter mit den Benutzereingaben ersetzen
	    $mailbody = str_replace( '###NAME###', $username['email'], $mailbody );
	    $mailbody = str_replace( '###PWRESET###', $neuesPw, $mailbody );

		// PHPMailer laden
		require ("PHPMailer/PHPMailerAutoload.php");
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//UTF-8 Kodierung festlegen
		$mail->CharSet  =  "UTF-8";
		//Set who the message is to be sent from
		$mail->setFrom('em21@bde-malygos.de', '[Fußball-Tippspiel WM 2018]');
		//Set an alternative reply-to address
		$mail->addReplyTo('em21@bde-malygos.de', '');
		//Set who the message is to be sent to
		$mail->addAddress($username['email'], $username['username']);
		//Set the subject line
		$mail->Subject = 'Änderung des Passwortes';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($mailbody, dirname(__FILE__));
		//Replace the plain text body with one created manually
		$mail->AltBody = 'Der Administrator hat Ihr Passwort geändert';

		//send the message, check for errors
		if (!$mail->send()) {
		    echo "<p class='text-danger text-center'>E-Mail Fehler: " . $mail->ErrorInfo, "</p>";
		} else {
		    echo "<p class='text-success text-center'>Passwort wurde Erfolgreich geändert und der User wurde per E-Mail darüber Informiert.</p>"; // Ausgabe formatieren die Optik!
		}
	}
}

/* ************************** */
/* *** Clock for EM Start *** */
/* ************************** */

function EmClock($mysqli) {
	if (time() > wmStart($mysqli)) {
		$ergebnis = '';
	}
	else {
		$ergebnis = '<div class="card card-sm text-center">';
		$ergebnis .= '<h2><u>Start der UEFA EM 2021 in:</u></h2>';
		$ergebnis .= '<h3><div id="clock2"></div></h3>';
		$ergebnis .= '</div><br />';
	}
	return $ergebnis;
}

function EndofWM($userID, $mysqli) {
	$sql = "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmMeister'";
	$result = $mysqli->query($sql);
	$emMeister = $result->fetch_array();
	if ($emMeister['wert'] != "") {
		$sql2 = "SELECT userID FROM ".prefix."_toplist ORDER BY `total` DESC";
		$result2 = $mysqli->query($sql2);
		$platz = 1;
		while ($top = $result2->fetch_array()) {
			if ($top['userID'] == $userID) {
				$container =  '<div class="alert alert-success">';
				$container .= '<h2 class="endofem text-center"><b>HERZLICHEN GLÜCKWUNSCH ZUM '.$platz.'. Platz</b></2>';
				$container .= '</div>';
			}
			else {
				$platz++;
			}
		}
	}
	else {
		$container = '';
	}
	return $container;
}

function saveLastChatID($userID, $mysqli) { // Bzw. die Anzahl der vorhandenen Beiträge
// Rework der Function
$sql = "SELECT COUNT(id) AS anzahl FROM ".prefix."_chat";
$result = $mysqli->query($sql);
$result = mysqli_fetch_array($result);
$anzahl = $result['anzahl'];

$sql2 = "UPDATE ".prefix."_users SET last_chat_message = ? WHERE id = ?";

	$stmt = $mysqli->prepare($sql2);
	$stmt->bind_param("ii", $anzahl, $userID);
	if (!$stmt->execute()) {
		echo "<div class='alert alert-danger'>".$mysqli->error."</div>";
	}
	$stmt->close();
}


function newMessages($userID, $mysqli) {
  $sql = "SELECT last_chat_message FROM ".prefix."_users WHERE id = ".$userID."";
  $result = $mysqli->query($sql);
  while ($lastMeesageUser = $result->fetch_array()) {
    $lmu = $lastMeesageUser['last_chat_message'];
	$sql2 ="SELECT COUNT(id) AS anzahl FROM ".prefix."_chat ORDER BY id DESC LIMIT 0,1";
    $result2 = $mysqli->query($sql2);
    while($lastChatID = $result2->fetch_array()) {
      $lastID = $lastChatID['anzahl'];
	  $newMessages = $lastID - $lmu;
	  $badge = '<span class="badge badge-secondary text-light">'.$newMessages.'</span>';
      return $badge;
    }

  }

}

/**
 * Generates a random string with $length characters
 *
 * @param int $length Length of the string (optional)
 * @return string
 */
function generateRandomString($length = 10) {
	return substr(str_shuffle(str_repeat(implode('', range('!','z')), $length)), 0, $length);
}

/* ************************* */
/* *** Benutzer abmelden *** */
/* ************************* */
function resetUser() {
// Setze alle Session-Werte zurück
session_unset();
$_SESSION = array();

// hole Session-Parameter
/*$params = session_get_cookie_params();

// Lösche das aktuelle Cookie.
setcookie(session_name(),
        '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]);
*/

// Vernichte die Session
session_destroy();
// Header funktioniert nur wenn keine Inhalte ausgegeben werden sollen
//header('Location: index.php?site=error');
return true;
}

?>
