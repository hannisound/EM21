<?php
	error_reporting(E_ALL);
$error = "";
if (isset($_GET['site']))
{
	$whiteList = array(	'home',
						'login',
						'reg',
						'verify',
						'faq',
						'spende',
						'contact',
						'impressum',
						'logout',
						'start',
						'reset',
						'chat',
						'gtipps',
						'ftipps',
						'config',
						'points',
						'special',
						'adm_create',
						'adm_user',
						'adm_faq_edit',
						'adm_news',
						'adm_specials',
						'adm_final',
						'adm_site_title',
						'datenschutz',
						'mailedit',
						'forgot',
						'error',
						);
	if( in_array($_GET['site'],$whiteList))
	{
		$file = "sites/".$_GET['site'].".php";
		if (file_exists($file)) // Prüfen ob Datei existiert
		{
			// Wenn Whitelist + Dateiüberprüfung erfolgreich war auf Seite weiterleiten
			$site = $_GET['site'];
			// Title für Header festlegen
			$start = array(	"Startseite" 					=> "home",
							"Login" 						=> "login",
							"Registierung" 					=> "reg",
							"Aktivierung" 					=> "verify",
							"FAQ" 							=> "faq",
							"Spendenpott" 					=> "spende",
							"Kontakt" 						=> "contact",
							"Impressum" 					=> "impressum",
							"Passwort vergessen?" 			=> "forgot",
							"Willkommen" 					=> "start",
							"Gruppentipps" 					=> "gtipps",
							"Finaltipps"					=> "ftipps",
							"Einstellungen"					=> "config",
							"Punkte"						=> "points",
							"Spezialtipps" 					=> "special",
							"Spiel erstellen" 				=> "adm_create",
							"Logout" 						=> "logout",
							"FAQ bearbeiten" 				=> "adm_faq_edit",
							"News bearbeiten"				=> "adm_news",
							"User bearbeiten"				=> "adm_user",
							"Specials Tipp eintragen" 		=> "adm_specials",
							"Finalrunde bearbeiten"			=> "adm_final",
							"Seitentitel bearbeiten"		=> "adm_site_title",
							"Datenschutz" 					=> "datenschutz",
							"E-Mail Adresse ändern" 		=> "mailedit",
							"Fehler"						=> "error",
							"WM Chat"						=> "chat",
							"Passwort zurücksetzen"			=> "reset"
							);
			$title = array_search("$site", $start);
			$title 	= "".$title."";

		}
		else
		{
			// Wenn Überprüfung der Datei FALSE ist auf Home leiten und Fehlermeldung ausgeben
			$site = "error";
			$title = "Fehler";
			$_SESSION['message'] = "<strong>Die von Ihnen aufgerufene Seite existiert leider nicht. Wenden Sie sich bitte an den Administrator wenn das Problem weiterhin auftreten sollte. Sie wurden auf die Startseite umgeleitet.</strong>";
		}
	}
	else
	{
		//Wenn $_GET['site'] ausgeführt wurde, aber nicht auf der Whitelist steht auf Home Leiten + Fehlermeldung
		$site = "error";
		$title = "Fehler";
		$_SESSION['message'] = "<strong>Die von Ihnen aufgerufene Seite existiert leider nicht. Wenden Sie sich bitte an den Administrator wenn das Problem weiterhin auftreten sollte. Sie wurden auf die Startseite umgeleitet.</strong>";
	}
}
else
{
	// Wenn $_GET['site'] nicht gesetzt wurde auf Home leiten
	$title = "Startseite";
	$site = "home";
}

// Überprüfung wenn Gruppe / Finalrunde vorhanden ist
if (isset($_GET['grp']))
{
	$grpwhitelist = array('A', 'B', 'C', 'D', 'E', 'F', 'Achtelfinale', 'Viertelfinale', 'Halbfinale', 'Finale', 'special');
	if( in_array($_GET['grp'],$grpwhitelist))
	{
		$finalrunde	= array( 	"Achtelfinale" 	=> "Achtelfinale",
								"Viertelfinale" => "Viertelfinale",
								"Halbfinale" 	=> "Halbfinale",
								"Finale" 		=> "Finale",
								"Spezialtipps" 	=> "special",
								"Gruppe A" 			=> "A",
								"Gruppe B" 			=> "B",
								"Gruppe C" 			=> "C",
								"Gruppe D" 			=> "D",
								"Gruppe E" 			=> "E",
								"Gruppe F" 			=> "F");
		$title_zusatz = array_search("".$_GET['grp']."", $finalrunde);
		$title  	= "".$title." ".$title_zusatz."";
	}
	else
	{
		$title 	= "Tippübersicht";
		$site	= "error";
		$error 	= "<div class='alert alert-danger text-center'>Die von Ihnen gewählte Gruppe oder Finalrunde gibt es leider nicht. Wenden Sie sich bitte an den Administrator wenn das Problem weiterhin auftreten sollte. Sie wurden auf die Tippübersicht geleitet.</div>";
	}
}
?>
