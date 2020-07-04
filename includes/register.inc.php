<?php
/* Registration process, inserts user info into the database
   and sends account confirmation email message
 */

// Escape all $_POST variables to protect against SQL injections
$email              = $_POST['email'];
$username           = $_POST['username'];
$password           = $_POST['password'];
$replay_password    = $_POST['replay_password'];
if (isset($_POST['newsletter'])) {
  $newsletter         = 1;
}
else {
  $newsletter = 0;
}
$hash               = md5(rand(0,1000));

// Check if user with that email already exists
$stmt = $mysqli->prepare("SELECT email FROM ".prefix."_users WHERE email = ?");
$stmt->bind_param('s', $email);  // Bind "$email" to parameter.
$stmt->execute();    // Execute the prepared query.
$stmt->store_result();

// We know user email exists if the rows returned are more than 0
if ( $stmt->num_rows > 0 ) {
    $_SESSION['message'] = 'Benutzer mit dieser E-Mail existiert bereits!';
    echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
    $emailError = "is-invalid";
}
// Prüfen ob der Benutzername schon in der Datenbank bekannt ist
$stmt = $mysqli->prepare("SELECT username FROM ".prefix."_users WHERE username= ?");
$stmt->bind_param('s', $username);  // Bind "$username" to parameter.
$stmt->execute();    // Execute the prepared query.
$stmt->store_result();
if ($stmt->num_rows > 0 ) {
  $_SESSION['message'] = "Der Benutzername ist leider schon vergeben!";
  echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
  $usernameError = "is-invalid";
}

// Username auf mind. 3 Zeichen prüfen
if(strlen(trim($username)) < 3)
{
  // Fehlermeldung falls der Benutezrname keine 3 Zeichen lang ist
  $_SESSION['message'] = "Der Benutzername ist leider zu kurz!";
  echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
  $usernameError = "is-invalid";
}


// Passwort gleichheit prüfen
if ($password != $replay_password)
{
  // Password ist ungleich
  $_SESSION['message'] = "Die beiden Passwörter stimmen nicht überein!";
  echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
  $passwordError = "is-invalid";
}

if (strlen($_POST['password']) >= 8) {
	if(preg_match_all('/[a-z]/', $_POST['password'])){
		if (preg_match_all('/[A-Z]/', $_POST['password'])) {
			if (preg_match_all('/[0-9]/', $_POST['password'])) {
			}
			else
      {
			  //Fehlermeldung falls das Passwort keine Zahlen enthält
        $_SESSION['message'] = "Das Passwort enthält keine Zahlen!";
        echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
        $passwordError = "is-invalid";
			}
		}
		else
    {
			//Fehlermeldung falls das Passwort keine Großbuchstaben enthält
      $_SESSION['message'] = "Das Passwort enthält keine Großbuchstaben!";
      echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
      $passwordError = "is-invalid";
		}
	}
	else
  {
	  //Fehlermeldung falls das Passwort keine Kleinbuchstaben enthält
    $_SESSION['message'] = "Das Passwort enthält keine Kleinbuchstaben!";
    echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
    $passwordError = "is-invalid";
	}
}
else
{
  //Fehlermeldung falls das Passwort keine 8 Zeichen lang ist
  $_SESSION['message'] = "Das Passwort ist zu kurz!";
  echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
  $passwordError = "is-invalid";
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
{
  // Die E-Mail Adresse ist keine gültige E-Mail Adresse
  $_SESSION['message'] = "Die E-Mail Adressse ist keine gültige E-Mail Adresse. Bite Prüfen Sie korrekte Schreibweise!";
  echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
  $emailError = "is-invalid";
}
/*
// Captcha Code auf Richtigkeit prüfen
if (isset($_POST['g-recaptcha-response']) && empty($_POST['g-recaptcha-response']) == FALSE)
{
  //your site secret key
    $secret 	= '6LfoeF0UAAAAAAqve414TBDkyktaHDS-wssE2LAy';
    $remoteip	= $_SERVER['REMOTE_ADDR'];
    $captcha 	= $_POST['g-recaptcha-response'];
    //get verify response data
    $rsp 		= file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captcha.'&remoteip='.$remoteip.'');
    $responseData = json_decode($rsp);
    if($responseData->success) {

    }
    else {
        // ReCapatcha ist Falsch
        $_SESSION['message'] = "Der Sicherheitscode war nicht korrekt!";
        echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
        $capatchaError = "is-invalid";
    }
}
else {
  // ReCapatcha ist Falsch
  $_SESSION['message'] = "Der Sicherheitscode war nicht korrekt!";
  echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
  $capatchaError = "is-invalid";
}*/

// Prüfen wenn keine Fehler aufgetreten sind und User in Datenbank eintragen und E-Mail verschicken
if (!empty($usernameError) OR !empty($emailError) OR !empty($passwordError) /*OR !empty($capatchaError)*/) {
  // Es sind Fehler aufgetreten - Es wurde kein Benutzer eingetragen
  // Fehlermeldung muss hier nicht ausgegeben werden da schon vorher Meldungen ausgeben wurden
  $stmt->close();
}
else
{
  // Password Hashen
  $password = password_hash($replay_password, PASSWORD_DEFAULT);
  /*$sql = "INSERT INTO ".prefix."_users (username, email, password, hash, status, newsletter, active) "
          . "VALUES ('$username', '$email', '$password', '$hash', '0', '$newsletter', '0')";*/
  $stmt->close();
  $stmt = $mysqli->prepare("INSERT INTO ".prefix."_users (username, email, password, hash, status, newsletter, active) VALUES (?, ?, ?, ?, 0, ?, 0)");
  $stmt->bind_param('ssssi', $username, $email, $password, $hash, $newsletter);  // Bind Variables to parameter.

  // Add user to the database
  if ($stmt->execute()){
    $ID = $stmt->insert_id;

    // Link für die Aktiviierung erstellen
    $link = "<a href=\"http://".$_SERVER['SERVER_NAME']."/index.php?site=verify&id=$ID&hash=$hash\"/>Klicken Sie hier um die Aktivierung abzuschließen</a>";
    $link2 = "http://".$_SERVER['SERVER_NAME']."/index.php?site=verify&id=".$ID."&hash=".$hash."";

    // Template mit dem Mailbody laden und für den Versand vorbereiten
    $mailbody = file_get_contents( 'includes/email/RegMailBody.txt' );
    // Platzhalter mit den Benutzereingaben ersetzen
    $mailbody = str_replace( '###NAME###', $username, $mailbody );
    $mailbody = str_replace( '###LINK###', $link, $mailbody );
    $mailbody = str_replace( '###LINK2###', $link2, $mailbody );

    // PHPMailer laden
    require ("includes/PHPMailer/PHPMailerAutoload.php");
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    //UTF-8 Kodierung festlegen
    $mail->CharSet  =  "UTF-8";
    //Set who the message is to be sent from
    $mail->setFrom('em21@bde-malygos.de', '[Fußball-Tippspiel WM 2018]');
    //Set an alternative reply-to address
    $mail->addReplyTo('em21@bde-malygos.de', '');
    //Set who the message is to be sent to
    $mail->addAddress($email, $username);
    //Set the subject line
    $mail->Subject = 'Registierung Abschließen';
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML($mailbody, dirname(__FILE__));
    //Replace the plain text body with one created manually
    $mail->AltBody = 'Schließen Sie die Registierung ab';

    //send the message, check for errors
    if (!$mail->send()) {
        echo "<div class='alert alert-danger text-center'>E-Mail Fehler: " . $mail->ErrorInfo, "</div>";
    } else {
    }

    $_SESSION['message'] = 'Ihr Account wurde Erfolgreich angelegt. Melden Sie sich bitte in Ihrem Postfach an und folgen Sie den Anweisungen in der E-Mail von uns.';
    echo "<div class='alert alert-success text-center'>".$_SESSION['message']."</div>";
  }
  else
  {
    $_SESSION['message'] = 'Registrierung fehlgeschlagen!';
    echo "<div class='alert alert-danger text-center'>".$_SESSION['message']." ".$stmt->error."</div>";
  }
}
