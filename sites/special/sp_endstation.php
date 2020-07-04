<h2>Wo ist für Deutschland Endstation?
<p class="lead">Für die richtige Endstation von Deutschland gibt es 5 Punkte</p></h2>
<div class="row text-center">
<?php
// GruppenID auslesen
$sql 		= "SELECT name, grpID FROM ".prefix."_grp LIMIT 5,13";
$result 	= $mysqli->query($sql);
while ($grp = mysqli_fetch_array($result)) {
  if ($grp['name'] == "F") {
    $round = "vorrunde";
  }
  else {
    $round = strtolower($grp['name']);
  }

  // EndstationsID auslesen
  $sql2 		= "SELECT endStationID FROM ".prefix."_endstation WHERE userID = '".$_SESSION['user_id']."'";
  $result2 	= $mysqli->query($sql2);
  $endStation = mysqli_fetch_array($result2);

  if (time() <= wmStart($mysqli)) {
    if ($grp['grpID'] == 12) {
      // Nichts tun - da Spiel um Platz 3 keine richtige Endrunde ist
    }
    else {
      if ($grp['grpID'] == $endStation['endStationID']) {
        echo "<div class='col'><a href='index.php?site=special' id='Endstation' name='".$grp['grpID']."' field='".$_SESSION['user_id']."' '><img id='".$round."' src='images/runden/".$round.".png' class='img-fluid img-rounded'></a></div>";
      }
      else {
        echo "<div class='col'><a href='index.php?site=special' id='Endstation' name='".$grp['grpID']."' field='".$_SESSION['user_id']."' '><img id='".$round."' src='images/runden/".$round."_kreuz.png' class='img-fluid img-rounded'></a></div>";
      }
    }
  }
  else {
    if ($grp['grpID'] == 12) {
      // Nichts tun - da Spiel um Platz 3 keine richtige Endrunde ist
    }
    else {
      if ($grp['grpID'] == $endStation['endStationID']) {
        echo "<div class='col'><img src='images/runden/".$round.".png' class='img-fluid img-rounded'></div>";
      }
      else {
        echo "<div class='col'><img src='images/runden/".$round."_kreuz.png' class='img-fluid img-rounded'></div>";
      }
    }
  }
}
?>
<div id="meldung" class="text-center"></div>
</div>
