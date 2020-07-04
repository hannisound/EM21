<section id="content">
    <article>
    	<h2>Spiel eintragen</h2>
    	<footer></footer>
    	<?php
            // Prüfen wenn der User die entsprechende Berechtigung besitzt
            if ( $_SESSION['status'] >= 2)
            {
                if (isset($_POST['CreateGame']))
                {
                    // Variablen bereinigen
                    $geg_1          = saveColumn($db, $_POST['geg_1']);      //Fragt den eingegebenen Gegner 1 ab
                    $geg_2          = saveColumn($db, $_POST['geg_2']);      //Fragt den eingegebenen Gegner 2 ab
                    $date           = saveColumn($db, $_POST['date']);       //Fragt die eingegebene Stunde ab
                    $grp            = saveColumn($db, $_POST['grp']);      //Fragt die eingegebene Gruppe ab
                    $time           = strtotime($date);

                    // Spiel eintragen
                    $insert = "INSERT INTO `em16_games` SET `geg_1` = '$geg_1', `geg_2` = '$geg_2', `ergebnis_1` = '-',`ergebnis_2` = '-',`time` = '$time', `grp` = '$grp'";
                    $db->query($insert);

                    echo "<p class='success'>Das Spiel wurde erfolgreich in die Datenbank eingetragen</p>";
                }
                ?>
                    <div class="centerButton">
                    <form action="" method="POST" accept-charset="UTF-8" class="form-inline">
                        <input type="text" id="datetimepicker3" name="date"/><br>
                        <select name="geg_1" class="form-control">
                        <?php
                            $sql = "SELECT id, name, grp FROM em16_team";
                            $result = $db->query($sql);
                            while ($geg_1 = mysqli_fetch_array($result))
                            {
                                echo "<option value='".$geg_1['id']."'>".$geg_1['name']."</option>\n";
                            }
                        ?>
                        </select> :

                        <select name="geg_2" class="form-control">
                        <?php
                            $sql = "SELECT id, name, grp FROM em16_team";
                            $result = $db->query($sql);
                            while ($geg_2 = mysqli_fetch_array($result))
                            {
                                echo "<option value='".$geg_2['id']."'>".$geg_2['name']."</option>\n";
                            }
                        ?>
                        </select>

                        <select name="grp" class="form-control">
                        <?php
                            $sql = "SELECT grpID, name FROM em16_grp";
                            $result = $db->query($sql);
                            while ($grp = mysqli_fetch_array($result))
                            {
                                echo "<option value='".$grp['grpID']."'>".$grp['name']."</option>\n";
                            }
                        ?>
                        </select>
                        <br><br><input name="CreateGame" type="submit" class="btn btn-primary">
                    </form
                    </div>
                <?php
            }
    	?>
	</article>
</section>