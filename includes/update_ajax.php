<?php
require_once 'db.php';
require_once 'function.inc.php';
//
// Profilbild in die Datenbank eintragen
//
if (isset($_POST['PicUpload']) && isset($_POST['usr'])) {
	// Daten hier in die DB Eintragen
	$picUrl  	= $_POST['PicUpload'];
	$usr 	   	= $_POST['usr'];
	// TODO:FIXME Am Ende auf richtigen Server Link ändern
	$picUrl  	= str_replace("http://".$_SERVER['SERVER_NAME']."/includes/uploader/../../images/profilbild/", "", $picUrl);

	$sql = "UPDATE ".prefix."_users SET profilbild = '$picUrl' WHERE username = '$usr'";
	if (!$mysqli->query($sql)) {
		printf("<p class='alert alert-danger text-center'>DB Fehler: #0008 %s\n</p>", $mysqli->error);
	}
}

//
// Profilbild aus der Datenbank löschen
//
if (isset($_POST['picDeleteID'])) {
	$picDeleteID = $_POST['picDeleteID'];

	$sql 	= "SELECT profilbild FROM ".prefix."_users WHERE ID = $picDeleteID";
	$result = $mysqli->query($sql);
	$picUrl = mysqli_fetch_array($result);

	$sql1 = "UPDATE ".prefix."_users SET `profilbild` = '' WHERE `ID` = $picDeleteID;";
	if (!$mysqli->query($sql1)) {
		printf("<p class='alert alert-danger'>DB Fehler: #0009</p>");
	}
	else {
		$ordner = "/em21/images/profilbild/";
		unlink($_SERVER['DOCUMENT_ROOT'] . $ordner . $picUrl['profilbild']);
		echo "Erfolgreich ", $_SERVER['DOCUMENT_ROOT'] . $ordner . $picUrl['profilbild'];
	}
}

//
// Newsletterstatus ändern
//
if (isset($_POST['nlStatus']) && isset($_POST['usr'])) {
	$nlStatus 	= $_POST['nlStatus'];
	$usr		= $_POST['usr'];

	if ($nlStatus == "false") {
		$nlStatus = "0";
	}
	else {
		$nlStatus = "1";
	}

	$sql = "UPDATE ".prefix."_users SET `newsletter` = '$nlStatus' WHERE username = '$usr'";
	if (!$mysqli->query($sql)) {
		printf("<span class='text-danger'>DB Fehler: #0010</span>");
	}
	else {
		echo "<span class='text-success'>Erfolgreich</span>", $nlStatus;
	}
}

//
// adm_user - Aktivierungsstatus ändern
//
if (isset($_POST['adm_ActivateStatus']) && isset($_POST['userID'])) {
	$activateStatus 	 	= $_POST['adm_ActivateStatus'];
	$usrID					= $_POST['userID'];

	if ($activateStatus == "false") {
		$activateStatus = "0";
	}
	else {
		$activateStatus = "1";
	}

	if (is_numeric($activateStatus) OR is_numeric($usrID)) {
		$sql = "UPDATE ".prefix."_users SET `active` = '$activateStatus' WHERE ID = '$usrID'";
		if (!$mysqli->query($sql)) {
			printf("<p class='alert alert-danger text-centers'>DB Fehler: #0010: %s\n</p>", $mysqli->error);
		}
		else {
			if ($activateStatus == "1") {
				reg_eintrag_toplist($usrID, $mysqli);
				printf("<p class='alert alert-success text-center'>User wurde Erfolgreich aktiviert</p>");
			}
			else {
				printf("<p class='alert alert-success text-center'>User wurde Erfolgreich deaktiviert</p>");
			}
		}
	}
	else {
		printf("<p class='alert alert-danger text-center'>#0011 Es ist ein Fehler aufgetreten.</p>");
	}
}

//
// adm_user - Newsletterstatus ändern
//
if (isset($_POST['adm_NewsletterStatus']) && isset($_POST['userID'])) {
	$NewsletterStatus 	 	= $_POST['adm_NewsletterStatus'];
	$usrID					= $_POST['userID'];

	if ($NewsletterStatus == "false") {
		$NewsletterStatus = "0";
	}
	else {
		$NewsletterStatus = "1";
	}

	if (is_numeric($NewsletterStatus) AND is_numeric($usrID)) {
		$sql = "UPDATE ".prefix."_users SET `newsletter` = '$NewsletterStatus' WHERE ID = '$usrID'";
		if (!$mysqli->query($sql)) {
			printf("<p class='alert alert-danger text-centers'>DB Fehler: #0012: %s\n</p>", $mysqli->error);
		}
		else {
			if ($NewsletterStatus == "1") {
				printf("<p class='alert alert-success text-center'>Benachrichtigung wurde Erfolgreich aktiviert</p>");
			}
    		else {
      			printf("<p class='alert alert-success text-center'>Benachrichtigung wurde Erfolgreich deaktiviert</p>");
    		}
		}
	}
	else {
		printf("<p class='alert alert-danger text-center'>#0013 Es ist ein Fehler aufgetreten.</p>");
	}
}
// FIXME: Tabellen und Abfragen prüfen
//
// adm_user - Account Löschen
//
if (isset($_POST['adm_AccDelete'])) {
	$userID	= $_POST['adm_AccDelete'];

	$sql[] = "DELETE FROM ".prefix."_users WHERE `ID` = '".$userID."'";
	$sql[] = "DELETE FROM ".prefix."_toplist WHERE `userID` = $userID";
	$sql[] = "DELETE FROM ".prefix."_meister WHERE `userID` = $userID";
	$sql[] = "DELETE FROM ".prefix."_endstation WHERE `userID` = $userID";
	$sql[] = "DELETE FROM ".prefix."_grpSieger WHERE `userID` = $userID";
	$sql[] = "DELETE FROM ".prefix."_torkoenig WHERE `userID` = $userID";
	$sql[] = "DELETE FROM ".prefix."_tipp WHERE `userID` = $userID";
	$sql[] = "DELETE FROM ".prefix."_userGrpMember WHERE `userID` = $userID";
	foreach ($sql as $abfrage) {
		if (!$mysqli->query($abfrage)) {
			echo "<p class'alert alert-danger text-center'>FB Fehler: %s\n</p>". $mysqli->error;
		}

	}
	echo "<p class='alert alert-success text-center'>Der Account wurde Erfolgreich gelöscht.</p>";

}

// FIXME: PWReset Function einfügen und Regtime ändern - da nicht mehr existent in der DB
//
// adm_user - Passwort reseten
//
if (isset($_POST['adm_PwReset'])) {
	$userID = $_POST['adm_PwReset'];
	PwReset($mysqli, $userID);
}

//
// adm_user - Profilbild als Admin aus der Datenbank löschen
//
if (isset($_POST['adm_picDeleteID'])) {
	// Daten bereinigen
	$userID = $_POST['adm_picDeleteID'];

	$sql 	= "SELECT profilbild FROM ".prefix."_users WHERE ID = $userID";
	$result = $mysqli->query($sql);
	$picUrl = $result->fetch_array();

	$sql1 = "UPDATE ".prefix."_users SET `profilbild` = '' WHERE `ID` = $userID";
	if (!$mysqli->query($sql1)) {
		printf("<p class='alert alert-danger text-center'>DB Fehler: #0009: %s\n</p>". $mysqli->error);
	}
	else {
		$ordner = "/em21/images/profilbild/";
		unlink($_SERVER['DOCUMENT_ROOT'] . $ordner . $picUrl['profilbild']);
		echo "<p class='alert alert-success text-center'>Profilbild wurde Erfolgreich gelöscht</p>"; // , $_SERVER['DOCUMENT_ROOT'] . $ordner . $picUrl['profilbild']
	}
}

//
// adm_News Eintragung von neuen News Einträgen
//
if (isset($_POST['title']) && isset($_POST['news']) && isset($_POST['user']))
{
	// Übergebene Werte in Variablen schreiben
	$title 		= $_POST['title'];
	$news 		= $_POST['news'];
	$user 		= $_POST['user'];

	// Übergebene Werte valdieren
	$title 		= $title;
	$news 	 	= nl2br($news);
	$user 		= $user;

	// Benutzernamen ID Auslesen
	$sql 	= "SELECT ID FROM ".prefix."_users WHERE username = '$user'";
	$result = $mysqli->query($sql);
	while($id = mysqli_fetch_array($result))
	{
		$id = $id['ID'];
		// Daten an die DB übergeben
		$sql = "INSERT INTO ".prefix."_news ( `ID`, `title`, `news`, `autor_id` ) VALUES ( '' , '$title' , '$news', '$id' )";
		if(!$mysqli->query($sql)) {
			echo "<span class='text-danger'>Es ist leider ein Fehler beim Eintragen aufgetreten.</span>";
		}
		else {
			echo "<span class='text-success'>Erfolgreich</span>";
		}
	}
}

//
// adm_Löschen eines News Eintrages
//
if (isset($_POST['news_delete']))
{
		$sql = "DELETE FROM ".prefix."_news WHERE ID = ".$_POST['news_delete']."";
		if(!$mysqli->query($sql)) {
			echo "<span class='text-danger'>Es ist ein Fehler beim Löschen aufgetreten</span>";
		}
		else {
			echo "<span class='text-success'>Der Datensatz wurde Erfolgreich gelöscht</span>";
		}
}

//
// adm_news Ändern von News Einträgen
//
if (isset($_POST['edit_title']) && isset($_POST['edit_news']) && isset($_POST['news_ID_edit']))
{
	// Übergebene Werte in Variablen schreiben
	$id				= $_POST['news_ID_edit'];
	$title 		= $_POST['edit_title'];
	$news 		= $_POST['edit_news'];

	// Übergebene Werte valdieren
	$id				= $id;
	$title		= $title;
	$news 	 	= nl2br($news);

	// Daten an die DB übergeben
	$sql = "UPDATE ".prefix."_news SET news = '$news', title = '$title' WHERE ID = '$id'";
	if(!$mysqli->query($sql)) {
		echo "<span class='text-danger'>Es ist ein Fehler beim ändern des Beitrages aufgetreten</span>";
	}
	else {
		echo "<span class='text-success'>Erfolgreich</span>";
	}
}

//
// adm_faq Hier wird die Reihenfolge von der FAQ_edit.php ausgelesen und neu in die Datenbank geschrieben
//
if (isset($_GET['faq'])) {
	foreach($_GET['faq'] as $position => $id)
	{
		echo "Position: ".$position." || ID: ".$id." /n";
		$sql = "UPDATE ".prefix."_faq SET `sort_id` = " . $position . " WHERE `ID` = '" . $id . "'";
		if(!$mysqli->query($sql)) {
			echo "<span class='text-danger'>Fehler beim ändern der Reihenfolge</span>";
		}
		echo "<span class='text-success'>Erfolgreich</span>";
	}
}

//
// adm_faq Eintragung von neuen FAQ Einträgen
//
if (isset($_POST['frage']) && isset($_POST['antwort']))
{
	echo "Erfolgreich angekommen";
	// Auslesen der letzten Sort_ID
	$sql = "SELECT sort_id FROM ".prefix."_faq";
	$result = $mysqli->query($sql);
	$new_sort_id = mysqli_num_rows($result);
	echo $new_sort_id;

	// Übergebene Werte in Variablen schreiben
	$frage 		= $_POST['frage'];
	$antwort 	= $_POST['antwort'];

	// Übergebene Werte valdieren
	$frage 		= $frage;
	$antwort 	= nl2br($antwort);

	// Daten an die DB übergeben
	$sql = "INSERT INTO ".prefix."_faq ( `ID`, `sort_id`, `frage`, `antwort` ) VALUES ( '', '$new_sort_id' , '$frage' , '$antwort' )";
	if(!$mysqli->query($sql)) {
		echo "<span class='text-danger'>Es ist ein Fehler beim Eintragen aufgetreten</span>";
	}
	else {
		echo "<span class='text-success'>Erfolgreich</span>";
	}
}

//
// adm_faq Löschen eines FAQ Eintrages
//
if (isset($_POST['faq_delete']))
{
		$sql = "DELETE FROM ".prefix."_faq WHERE ID = ".$_POST['faq_delete']."";
		if(!$mysqli->query($sql)) {
			echo "<span class='text-danger'>Es ist ein Fehler beim Löschen aufgetreten.</span>";
		}
		else {
			echo "<span class='text-success'>Der Datensatz wurde Erfolgreich gelöscht</span>";
		}
}

//
// adm_faq Ändern von FAQ Einträgen
//
if (isset($_POST['edit_frage']) && isset($_POST['edit_antwort']) && isset($_POST['ID_edit']))
{
	// Übergebene Werte in Variablen schreiben
	$id			= $_POST['ID_edit'];
	$frage 		= $_POST['edit_frage'];
	$antwort 	= $_POST['edit_antwort'];

	// Übergebene Werte valdieren
	$id				= $id;
	$frage 		= $frage;
	$antwort 	= nl2br($antwort);

	// Daten an die DB übergeben
	$sql = "UPDATE ".prefix."_faq SET antwort = '$antwort', frage = '$frage' WHERE ID = '$id'";
	if(!$mysqli->query($sql)) {
		echo "<span class='text-danger'>Es ist ein Fehler beim ändern aufgetreten.</span>";
	}
	else {
		echo "<span class='text-success'>Erfolgreich</span>";
	}
}

//
// adm_site_title Eintragen/Updaten
//
if (isset($_POST['site']) && isset($_POST['title']) && $_POST['subtitle']) {
	$siteID 	= $_POST['ID_site'];
	$site 		= $_POST['site'];
	$title 		= $_POST['title'];
	$subtitle 	= $_POST['subtitle'];

	// Prüfen wenn ein gleicher $site Eintrag schon vorhanden ist
	$sql 		= "SELECT site FROM ".prefix."_site_title WHERE site = '".$site."'";
	$result 	= $mysqli->query($sql);
	if(mysqli_num_rows($result) === 0) {
		// Wenn kein Eintrag vorhanden einen neuen Eintragen
		$statment = $mysqli->prepare("INSERT INTO ".prefix."_site_title (`ID`, `site`, `title`, `subtitle`) VALUES ('', ?, ?, ?)");
		$statment->bind_param("sss", $site, $title, $subtitle);
		$statment->execute();
		if($statment->affected_rows === 0) {
			// Wenn kein Eintrag/Update vorgenommen wurde mit Exit abbrechen 
			echo "<p class='alert alert-danger'>Es ist ein Fehler beim Eintragen/Updaten aufgetreten.</p>";
		}
		else {
			// Wenn alles erfolgreich eingetragen wurden - Meldung machen
			echo "<p class='alert alert-success'>Eintrag wurde erfolgreich verarbeitet</p>";
		}
		$statment->close();
	}
	else {
		// Wenn ein Eintrag vorhanden Updaten
		$statment = $mysqli->prepare("UPDATE ".prefix."_site_title SET site = ?, title = ?, subtitle = ? WHERE ID= ?");
		$statment->bind_param("sssi", $site, $title, $subtitle, $siteID);
		$statment->execute();
		if($statment->affected_rows === 0) {
			// Wenn kein Eintrag/Update vorgenommen wurde - Fehler ausgeben 
			echo "<p class='alert alert-danger'>Es ist ein Fehler beim Eintragen/Updaten aufgetreten</p>";
		}
		else {
			// Wenn alles erfolgreich geupdatet wurde - Meldung machen
			echo "<p class='alert alert-success'>Eintrag wurde erfolgreich verarbeitet</p>";

		}
		$statment->close();
	}
}

//
// Seitentitel löschen
//
if (isset($_POST['del_siteTitle'])) {
	$siteID = $_POST['del_siteTitle'];

	$statment = $mysqli->prepare("DELETE FROM ".prefix."_site_title WHERE ID = ?");
	$statment->bind_param("i", $siteID);
	$statment->execute();
	if($statment->affected_rows === 0) {
		// Wenn keine Löschung vorgenommen wurde - Fehler ausgeben
		echo "<p class='alert alert-danger'>Es ist ein Fehler beim Entfernen des Eintrags aufgetreten</p>";
	}
	else {
		// Wenn alles erfolgreich geupdatet wurde - Meldung machen
		echo "<p class='alert alert-success'>Eintrag wurde erfolgreich gelöscht</p>";

	}
	$statment->close();
}

//
// Specialtipps - WM Meister
//
if (isset($_POST['wmMeister']) && isset($_POST['userID']))
{
	$meisterID 	= $_POST['wmMeister'];
	$userID 		= $_POST['userID'];
	// Pürfen ob noch ein Weltmeister eingetragen werden darf oder der Tipp geändert werden darf
	if ( time() <= wmStart($mysqli))
	{
		// Pürfen ob schon ein Eintrag vorhanden
		$sql 	= "SELECT `userID` FROM ".prefix."_meister WHERE userID = '$userID'";
		$result = $mysqli->query($sql);
		if (mysqli_num_rows($result) === 0)
		{
			// Eintragen des Weltmeister
			$sql = "INSERT INTO ".prefix."_meister ( `ID`, `userID`, `meisterID` ) VALUES ( '', '$userID' , '$meisterID')";
			if (!$mysqli->query($sql)) {
				printf("<p class='alert alert-danger text-center'>Fehler beim Eintragen Ihres Weltmeistertipps</p>");
			}
			else {
				echo "<p class='alert alert-success text-center'>Ihr Weltmeistertipp wurde eingetragen</p>";
			}
		}
		else
		{
			// Ändern des Weltmeisters
			$sql = "UPDATE ".prefix."_meister SET meisterID = '$meisterID' WHERE userID = '$userID'";
			if (!$mysqli->query($sql)) {
				printf("<p class='alert alert-danger text-center'>Es ist leider ein Fehler beim Updaten aufgetreten</p>");
			}
			else {
				echo "<p class='alert alert-success text-center'>Ihr Weltmeistertipp wurde geändert</p>";
			}
		}
	}
	else
	{
		echo "<p class='alert alert-danger text-center'>Die Fußballweltmeisterschaft hat schon begonnen. Es können leider keine Weltmeisterschaftstipps mehr abgegeben werden oder geändert werden</p>";
	}
}

//
// Specialtipps Endstation (Deutschland) eintragen/Updaten
//
if (isset($_POST['EndStation']) && isset($_POST['userID']))
{
	$endStationID 	= $_POST['EndStation'];
	$userID					= $_POST['userID'];
	// Pürfen ob noch ein Weltmeister eingetragen werden darf oder der Tipp geändert werden darf
	if ( time() <= wmStart($mysqli))
	{
		// Pürfen ob schon ein Eintrag vorhanden
		$sql = "SELECT `userID` FROM ".prefix."_endstation WHERE userID = '".$userID."'";
		$result = $mysqli->query($sql);
		if (mysqli_num_rows($result) === 0)
		{
			// Eintragen des Endstationstipps
			$sql = "INSERT INTO ".prefix."_endstation ( `ID`, `userID`, `endStationID` ) VALUES ( '', '".$userID."' , '$endStationID')";
			if (!$mysqli->query($sql)) {
				printf("<p class='alert alert-danger text-center'>Fehler beim Eintagen des Tipps</p>");
			}
			else {
				echo "<p class='alert alert-success text-center'>Ihr Endstationtipp für Deutschland wurde eingetragen</p>";
			}
		}
		else
		{
			// Ändern des Endstationstipps
			$sql = "UPDATE ".prefix."_endstation SET endStationID = '$endStationID' WHERE userID = '".$userID."'";
			if (!$mysqli->query($sql)) {
				printf("<p class='alert alert-danger text-center'>Fehler beim Ändern des Endstationstipps</p>");
			}
			else {
				echo "<p class='alert alert-success text-center'>Ihr Endstationtipp wurde geändert</p>";
			}
		}
	}
	else
	{
		echo "<p class='alert alert-danger text-center'>Die Fußballweltmeisterschaft hat schon begonnen. Es können leider keine Endstationstipps mehr abgegeben werden oder geändert werden";
	}
}

//
// Specialtipp Torschützenkönig eintragen/ändern
//
if (isset($_POST['torKoenig']) AND isset($_POST['userID'])) {
	// Prüfen wenn Weltmeisterschaft schon begonnen hat oder nicht
	if (time() <= wmStart($mysqli)) {

		$torKoenig 	= $_POST['torKoenig'];
		$userID 		= $_POST['userID'];
		$torKoenig 	= html_entity_decode(strstr($torKoenig, ' ||', true));
		if ($torKoenig == FALSE) {
			echo "<p class='alert alert-danger text-center'>Benutzen Sie bitte die Suchvorschläge die Ihnen Angeboten werden - sonst können wir Ihren Spezialtipp nicht verarbeiten</p>";
			exit();
		}
		else {
			// Spieler ID auslesen
			$sql				= "SELECT ID FROM ".prefix."_spieler WHERE CONCAT (vorname, ' ',name) = '".$torKoenig."'";
			$result 		= $mysqli->query($sql);
			$tKID				= mysqli_fetch_array($result);

			// Pürfen ob Eintrag schon vorhanden ist
			$sql1 		= "SELECT spielerID FROM ".prefix."_torkoenig WHERE userID = '".$userID."'";
			$result1 	= $mysqli->query($sql1);
			if (mysqli_num_rows($result1) === 0) {

				// Eintrag neu Eintragen
				$sql3 = "INSERT INTO ".prefix."_torkoenig (ID, spielerID, userID) VALUES ('', '".$tKID['ID']."', '".$userID."')";
				if (!$mysqli->query($sql3)) {
					echo "<p class='alert alert-danger text-center'>Es ist leider ein Fehler beim Eintragen passiert - versuchen Sie es bitte erneut.</p>";
				}
				else {
					echo "<p class='alert alert-success text-center'>Ihr Torschützenkönig wurde Erfolgreich eingetragen.</p>";
				}
			}
			else {
				// Eintrag Updaten
				$sql2 = "UPDATE ".prefix."_torkoenig SET spielerID = '".$tKID['ID']."' WHERE userID = '".$userID."'";
				if (!$mysqli->query($sql2)) {
					echo "<p class='alert alert-danger text-center'>Es ist leider ein Fehler beim Updaten Ihres Torschützenkönigtipp passiert - versuchen Sie es bitte erneut.</p>";
				}
				else {
					echo "<p class='alert alert-success text-center'>Ihr Torschützenkönig wurde Erfolgreich geändert.</p>";
				}
			}
		}
	}
	else {
		echo "<p class='alert alert-danger text-center'>Die Weltmeisterschaft hat schon begonnen, damit können wir leider keinen Torschützenkönigtipp mehr entgegennehmen.</p>";
	}
}

//
// User Specialtipps Gruppensieger/zweiter eintragen/ändern
//
if (isset($_POST['sieger']) OR isset($_POST['zweiter']) AND isset($_POST['grpID']) AND isset($_POST['userID'])) {
	// Prüfen wenn die WM schon gestartet ist oder nicht
	if (time() <= wmStart($mysqli)) {
		$grpID 		= $_POST['grpID'];
		$userID 	= $_POST['userID'];
		// Pürfen ob Sieger oder 2. gesetzt ist und Variablen saven
		if (isset($_POST['sieger'])) {
			$sieger 	= $_POST['sieger'];
			if (empty($sieger)) {
				$siegerID = "NULL";
			}
			else {
				// Sieger in ID umwandeln
				$siegerID 	= teamID($mysqli, $sieger);
			}
		}
		elseif (isset($_POST['zweiter'])) {
			$zweiter 	= $_POST['zweiter'];
			if (empty($zweiter)) {
				$zweiterID = "NULL";
			}
			else {
				// Zweiter in ID umwandeln
				$zweiterID 	= teamID($mysqli, $zweiter);
			}
		}

		// Prüfen ob schon ein Eintrag vorhanden ist
		$sql 	= "SELECT ID FROM ".prefix."_grpSieger WHERE userID = '$userID' AND grpID = '$grpID'";
		$result = $mysqli->query($sql);
		if (mysqli_num_rows($result) === 0) {
			if (isset($sieger)) {
				$sql = "INSERT INTO ".prefix."_grpSieger (ID, userID, grpSieger, grpID) VALUES (NULL, $userID, $siegerID, $grpID)";
				if (!$mysqli->query($sql)) {
					echo "<p class='alert alert-danger text-center'>Es ist leider ein Fehler beim Eintragen Ihres Gruppensiegertipps passiert - versuchen Sie es bitte erneut.</p>";
				}
				else {
					echo "<p class='alert alert-success text-center'>Ihr Gruppensiegertipp wurde Erfolgreich eingetragen.</p>";
				}
			}
			else {
				$sql = "INSERT INTO ".prefix."_grpSieger (ID, userID, grpZweiter, grpID) VALUES (NULL, $userID, $zweiterID, $grpID)";
				if (!$mysqli->query($sql)) {
					echo "<p class='alert alert-danger text-center'>Es ist leider ein Fehler beim Eintragen Ihres Gruppenzweitertipp passiert - versuchen Sie es bitte erneut.</p>";
				}
				else {
					echo "<p class='alert alert-success text-center'>Ihr Gruppenzweitertipp wurde Erfolgreich eingetragen.</p>";
				}
			}
		}
		else {
			if (isset($sieger)) {
				$sql = "UPDATE ".prefix."_grpSieger SET grpSieger = $siegerID WHERE userID = '$userID' AND grpID = '$grpID'";
				if (!$mysqli->query($sql)) {
					echo "<p class='alert alert-danger text-center'>Es ist leider ein Fehler beim Updaten Ihres Tipps passiert - versuchen Sie es bitte erneut.</p>";
				}
				else {
					echo "<p class='alert alert-success text-center'>Ihr Gruppensiegertipp wurde Erfolgreich geändert.</p>";
				}
			}
			else {
				$sql = "UPDATE ".prefix."_grpSieger SET grpZweiter = $zweiterID WHERE userID = '$userID' AND grpID = '$grpID'";
				if (!$mysqli->query($sql)) {
					echo "<p class='alert alert-danger text-center'>Es ist leider ein Fehler beim Updaten Ihres Tipps passiert - versuchen Sie es bitte erneut.</p>";
				}
				else {
					echo "<p class='alert alert-success text-center'>Ihr Gruppenzweitertipp wurde Erfolgreich geändert.</p>";
				}
			}
		}
	}
	else {
		echo "<p class='alert alert-danger text-center'>Die Weltmeisterschaft hat schon begonnen, damit können wir leider keinen Gruppensiegertipp mehr entgegennehmen.</p>";
	}
}

//
// adm_Specialtipps Gruppensieger/zweiter eintragen
//
if (isset($_POST['adm_sieger']) OR isset($_POST['adm_zweiter']) AND isset($_POST['adm_grpID'])) {
	// Prüfen wenn die EURO schon gestartet ist oder nicht
		$grpID 		= $_POST['adm_grpID'];
		// Pürfen ob Sieger oder 2. gesetzt ist und Variablen saven
		if (isset($_POST['adm_sieger'])) {
			$sieger 	= $_POST['adm_sieger'];
			if (empty($sieger)) {
				$siegerID = "NULL";
			}
			else {
				// Sieger in ID umwandeln
				$siegerID 	= teamID($mysqli, $sieger);
			}
		}
		elseif (isset($_POST['adm_zweiter'])) {
			$zweiter 	= $_POST['adm_zweiter'];
			if (empty($zweiter)) {
				$zweiterID = "NULL";
			}
			else {
				// Zweiter in ID umwandeln
				$zweiterID 	= teamID($mysqli, $zweiter);
			}
		}

		if (isset($siegerID)) {
			$sql = "UPDATE ".prefix."_config SET wert = '$siegerID' WHERE eigenschaft = 'grpSieger_$grpID'";
		}
		else {
			$sql = "UPDATE ".prefix."_config SET wert = '$zweiterID' WHERE eigenschaft = 'grpZweiter_$grpID'";
		}

		if (!$mysqli->query($sql)) {
			print_r("Es ist ein Fehler beim Eintragen des Gruppensiegers/Zweiten aufgetreten");
		}
		else {
			echo '<span class="text-success"><i class="text-success fa fa-check"></i></span>';
			// Letzte Platzierung sichern
			saveLastPlace($mysqli);
			// Special Points berechnen/eintragen
			updateSpecialPoints($mysqli);
		}

}

//
// adm_special Torkönig eintragen
//
if (isset($_POST['adm_torKoenig']) && empty(!$_POST['adm_torKoenig'])) {
		$torkoenig 	= $_POST['adm_torKoenig'];
		$torKoenig 	= html_entity_decode(strstr($torkoenig, ' ||', true));

		// Spieler ID auslesen
		$sql			= "SELECT ID FROM ".prefix."_spieler WHERE CONCAT (vorname, ' ',name) = '".$torKoenig."'";
		$result 		= $mysqli->query($sql);
		$tKID			= mysqli_fetch_array($result);

		// Eintrag Updaten
		$sql2 = "UPDATE ".prefix."_config SET wert = '".$tKID['ID']."' WHERE eigenschaft = 'wmTorkoenig'";
		if (!$mysqli->query($sql2)) {
			echo "<span class='text-danger'>Es ist leider ein Fehler beim Updaten Ihres Torschützenkönigtipp passiert - versuchen Sie es bitte erneut.</span>";
		}
		else {
			// Letzte Platzierung sichern
			saveLastPlace($mysqli);
			// Special Points neu berechnen/eintragen
			updateSpecialPoints($mysqli);
		}
}

//
// adm_special Endstation eintragen
//
if (isset($_POST['adm_EndStation'])) {
	$endstation = $_POST['adm_EndStation'];

	// Endstation Eintragen
	$sql = "UPDATE ".prefix."_config SET wert = '$endstation' WHERE eigenschaft = 'wmEndstation'";
	if (!$mysqli->query($sql)) {
		echo "<span class='text-danger'>Es ist leider ein Fehler beim Updaten Ihres Torschützenkönigtipp passiert - versuchen Sie es bitte erneut.</span>";
	}
	else {
		// Letzte Platzierung sichern
		saveLastPlace($mysqli);
		// Special Points neu berechnen/eintragen
		updateSpecialPoints($mysqli);
	}
}

//
// adm_special Meister eintragen
//
if (isset($_POST['adm_Meister'])) {
	$meister = $_POST['adm_Meister'];

	// Endstation Eintragen
	$sql = "UPDATE ".prefix."_config SET wert = '$meister' WHERE eigenschaft = 'wmMeister'";
	if (!$mysqli->query($sql)) {
		echo "<span class='text-danger'>Es ist leider ein Fehler beim Updaten Ihres Torschützenkönigtipp passiert - versuchen Sie es bitte erneut.</span>";
	}
	else {
		// Letzte Platzierung sichern
		saveLastPlace($mysqli);
		// Special Points neu berechnen/eintragen
		updateSpecialPoints($mysqli);
	}
}

//
// adm_Finalrunde
//
if (isset($_POST['gameID']) && isset($_POST['geg1_teamID']) || isset($_POST['geg2_teamID'])) {
	$gameID = $_POST['gameID'];
	if (isset($_POST['geg1_teamID'])) {
		$geg1 	= $_POST['geg1_teamID'];
		$sql 	= "UPDATE ".prefix."_games SET geg_1 = '$geg1' WHERE ID = '$gameID'";
	}
	elseif (isset($_POST['geg2_teamID'])) {
		$geg2 	= $_POST['geg2_teamID'];
		$sql 	= "UPDATE ".prefix."_games SET geg_2 = '$geg2' WHERE ID = '$gameID'";
	}

	if (!$mysqli->query($sql)) {
		printf("<div class='alert alert-danger'>Es ist ein Fehler aufgetreten</div>");
	}
	else {
		printf("<div class='alert alert-success'>Der Gegner wurde Erfolgreich geändert</div>");
	}
}

//
// User Group - Erstellen
//
if (isset($_POST['grpName']) && isset($_POST['adminID']) && isset($_POST['shortcut'])) {
	// Variablen bereinigen
	$grpName 		= escape($_POST['grpName'], $mysqli);
	$shortcut 		= escape($_POST['shortcut'], $mysqli);
	$adminID 		= escape($_POST['adminID'], $mysqli);

	if (mb_strlen(html_entity_decode($shortcut)) <= 5) {
		$sql 		= "SELECT name FROM ".prefix."_userGrp WHERE name = '$grpName'";
		$result 	= $mysqli->query($sql);
		if (mysqli_num_rows($result) === 0) {
			// Gruppe eintragen
			$sql 	= "INSERT INTO ".prefix."_userGrp (ID, name, shortcut, adminID) VALUES (NULL, '$grpName', '$shortcut', '$adminID')";
			if (!$mysqli->query($sql)) {
				echo "<p class='alert alert-danger'>Es ist leider ein Fehler beim Eintragen aufgetreten. Versuchen Sie es später bitte erneut.</p>";
			}
			else {
				$sql = "INSERT INTO ".prefix."_userGrpMember (ID, userGrpID, userID) VALUES (NULL, '".$mysqli->insert_id."', $adminID)";
				if (!$mysqli->query($sql)) {
					echo "<p class='alert alert-danger'>#2 Es ist leider ein Fehler beim Eintragen aufgetreten. Versuchen Sie es später bitte erneut.</p>";
					return false;
				}
				else {
					echo "<p class='alert alert-success'>Ihre Gruppe wurde Erfolgreich eingetragen.</p>";
					return true;
				}
			}
		}
		else {
			echo "<p class='alert alert-danger'>Der Gruppenname ist leider schon vergeben. Versuchen Sie bitte einen anderen.</p>";
			return false;
		}
	}
	else {
		echo "<p class='alert alert-danger'>Der Shurtcut/Tag ist zu lang, er darf max. 5 Zeichen lang sein. Versuchen Sie bitte einen kürzeren.</p>";
		return false;
	}
}

//
// User Group - Löschen
//
if (isset($_POST['grpID']) && isset($_POST['admID'])) {
	$grpID 		= $_POST['grpID'];
	$admID 		= $_POST['admID'];

	$sql 		= "SELECT adminID FROM ".prefix."_userGrp WHERE ID = '$grpID'";
	$result 	= $mysqli->query($sql);
	$tmp_adm_ID = mysqli_fetch_array($result);
	$tmp_adm_ID = $tmp_adm_ID['adminID'];

	// Prüfen wenn der Eingeloggte User wirklich der Gruppenadmin ist
	if ($admID == $tmp_adm_ID) {
		// Member aus der Gruppe löschen
		$sql2 = "SELECT ID FROM ".prefix."_userGrpMember WHERE userGrpID = '$grpID'";
		$result2 = $mysqli->query($sql2);
		while ($grpMember = mysqli_fetch_array($result2)) {
			$sql3 = "DELETE FROM `".prefix."_userGrpMember` WHERE `ID` = '".$grpMember['ID']."'";
			if (!$mysqli->query($sql3)) {
				echo "<span class='alert alert-danger'>Es ist ein Fehler beim Löschen aufgetreten</span>";

			}
		}

		// Gruppe löschen nachdem alle Member rausgelöscht wurden
		$sql4 = "DELETE FROM ".prefix."_userGrp WHERE ID = '$grpID'";
		if (!$mysqli->query($sql4)) {
			echo "<span class='alert alert-danger'>Es ist ein Fehler beim Löschen der Gruppe aufgetreten</span>";
		}
		else {
			echo "<span class='alert alert-success'>Ihre Gruppe wurde Erfolgreich gelöscht.</span>";
		}
	}
	else {
		echo "<span class='alert alert-danger'>Etwas stimmt mit den Daten nicht überein. Probieren Sie es bitte erneut. Sollte der Fehler weiterhin auftreten Kontaktieren Sie bitte den Administrator.</span>";
	}
}

//
// User Gruppe - Verlassen
//
if (isset($_POST['grpID']) && isset($_POST['usrID'])) {
	$grpID = $_POST['grpID'];
	$usrID = $_POST['usrID'];

	$sql = "SELECT ID FROM ".prefix."_userGrpMember WHERE userID = '$usrID' AND userGrpID = '$grpID'";
	$result = $mysqli->query($sql);
	if (mysqli_num_rows($result) === 0) {
		echo "<span class='alert alert-danger'>Es ist ein Fehler beim Verlassen der Gruppe aufgetreten. Einige Daten stimmen nicht überein.</span>";
	}
	else {
		$sql2 = "DELETE FROM ".prefix."_userGrpMember WHERE userID = '$usrID' AND userGrpID = '$grpID'";
		if (!$mysqli->query($sql2)) {
			echo "<span class='alert alert-danger'>Es ist ein Fehler beim Verlassen der Gruppe aufgetreten</span>";
		}
		else {
			echo "<div class='alert alert-success'>Sie haben Erfolgreich die Gruppe verlassen.</div>";
		}
	}
}

//
// User Group - Add Member
//
if (isset($_POST['grpID']) && isset($_POST['user'])) {
	$grpID 	= $_POST['grpID'];
	$user 	= $_POST['user'];

	$sql 	= "SELECT ID FROM ".prefix."_users WHERE username = '$user'";
	$result = $mysqli->query($sql);
	$userID = mysqli_fetch_array($result);
	$userID = $userID['ID'];

	$sql 	= "SELECT ID FROM ".prefix."_userGrpMember WHERE userGrpID = '$grpID' AND userID ='$userID'";
	$result = $mysqli->query($sql);
	if (mysqli_num_rows($result) === 0) {
		$sql = "INSERT INTO ".prefix."_userGrpMember (ID, userGrpID, userID) VALUES (NULL, '$grpID', $userID)";
		if (!$mysqli->query($sql)) {
			echo "<p class='alert alert-danger'>Es ist leider ein Fehler beim Eintragen passiert. Versuchen Sie es bitte erneut.</p>";
		}
		else {
			echo "<p class='alert alert-success'>Der User wurde Erfolgreich der Gruppe hinzugefügt.</p>";
		}
	}
	else {
		echo "<p class='alert alert-danger'>Der Mitspieler gehört bereits zu Ihrer Gruppe.</p>";
	}
}

?>
