<?php
  if (isset($_POST['newpassword']) && !empty($_POST['newpassword']) AND isset($_POST['confirmpassword']) && !empty($_POST['confirmpassword'])) {
    $newpassword      = $mysqli->escape_string($_POST['newpassword']);
    $confirmpassword  = $mysqli->escape_string($_POST['confirmpassword']);
    $id               = $mysqli->escape_string($_POST['id']);

    // Passwort gleichheit prüfen
    if ($newpassword != $confirmpassword)
    {
      // Password ist ungleich
      $_SESSION['message'] = "Die beiden Passwörter stimmen nicht überein!";
      echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
      $passwordError = "is-invalid";
    }
    else {
      if (strlen($newpassword) >= 8) {
    	  if(preg_match_all('/[a-z]/', $newpassword)){
    		  if (preg_match_all('/[A-Z]/', $newpassword)) {
    			  if (preg_match_all('/[0-9]/', $newpassword)) {
              // Passwort entspricht allen Richtlinen und kann nun in die Datenbank eingetragen werden
              // Passwort muss noch verschlüsselt werden
              $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);

              // SQL Abfrage zusammenstellen
							$sql = "UPDATE `".prefix."_users` SET password = '$newpassword' WHERE `ID` = '$id'";
							if (!$mysqli->query($sql)) {
								printf("<div class='alert alert-danger text-center'>DB Fehler #0008: %s\n</div>", $mysqli->error);
							}
							else {
								$_SESSION['message'] = "Ihr Passwort wurde Erfolgreich geändert.";
                echo "<div class='alert alert-success text-center'>".$_SESSION['message']."</div>";
							}
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
    }
  }
  else {
    $_SESSION['message'] = "Entschuldigen Sie, etwas scheint schiefgegangen zu sein. Versuchen Sie es bitte erneut.";
    echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
  }
?>
