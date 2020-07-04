<?php
  if (login($_POST['email'], $_POST['password'], $mysqli))
  {
      // Login war Erfolgreich - Weiterleitung auf die Eingeloggte Startseite
      header("location: index.php?site=start");
  }
  else
  {
      // Login war Fehlerhaft - Fehlermeldung ausgeben
      echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
  }
?>
