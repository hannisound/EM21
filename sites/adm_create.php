<section id="CreateGame">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-8 mx-auto">
            <?php
                  // Prüfen wenn der User die entsprechende Berechtigung besitzt
                  if ( $_SESSION['status'] >= 2)
                  {
                      if (isset($_POST['CreateGame']))
                      {
                          // Variablen bereinigen
                          $geg_1          = $mysqli->escape_string($_POST['geg_1']);      //Fragt den eingegebenen Gegner 1 ab
                          $geg_2          = $mysqli->escape_string($_POST['geg_2']);      //Fragt den eingegebenen Gegner 2 ab
                          $date           = $mysqli->escape_string($_POST['date']);       //Fragt die eingegebene Stunde ab
                          $grp            = $mysqli->escape_string($_POST['grp']);      //Fragt die eingegebene Gruppe ab
                          $time           = strtotime($date);

                          // Spiel eintragen
                          $insert = "INSERT INTO `".prefix."_games` SET `geg_1` = '$geg_1', `geg_2` = '$geg_2', `ergebnis_1` = '-',`ergebnis_2` = '-',`time` = '$time', `grp` = '$grp'";
                          $mysqli->query($insert);

                          echo "<div class='alert alert-success text-center'>Das Spiel wurde erfolgreich in die Datenbank eingetragen</div>";
                      }
                      ?>
                          <div class="centerButton text-center">
                          <form action="" method="POST" accept-charset="UTF-8">
                              <input type="text" id="datetimepicker3" name="date" class="form-control"/><br>
                              <div class="row">
                                <div class="col-lg-4 mx-auto">
                              <select name="geg_1" class="form-control">
                              <?php
                                  $sql = "SELECT id, name, grp FROM ".prefix."_team";
                                  $result = $mysqli->query($sql);
                                  while ($geg_1 = mysqli_fetch_array($result))
                                  {
                                      echo "<option value='".$geg_1['id']."'>".$geg_1['name']."</option>\n";
                                  }
                              ?>
                            </select></div> :
                              <div class="col-lg-4 mx-auto">
                              <select name="geg_2" class="form-control">
                              <?php
                                  $sql = "SELECT id, name, grp FROM ".prefix."_team";
                                  $result = $mysqli->query($sql);
                                  while ($geg_2 = mysqli_fetch_array($result))
                                  {
                                      echo "<option value='".$geg_2['id']."'>".$geg_2['name']."</option>\n";
                                  }
                              ?>
                            </select></div>

                              <select name="grp" class="form-control">
                              <?php
                                  $sql = "SELECT grpID, name FROM ".prefix."_grp";
                                  $result = $mysqli->query($sql);
                                  while ($grp = mysqli_fetch_array($result))
                                  {
                                      echo "<option value='".$grp['grpID']."'>".$grp['name']."</option>\n";
                                  }
                              ?>
                            </select></div>
                              <br><br><input name="CreateGame" type="submit" class="btn btn-primary">
                          </form
                          </div>
                      <?php
                  }
          	?>
        	</div>
        </div>
    </div>
</section>
