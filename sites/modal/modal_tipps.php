<?php
  header("Content-Type: text/html; charset=utf-8");
  require("../../includes/db.php");
  require("../../includes/function.inc.php");
?>
<div class="modal-header">
  <h4 class="modal-title" id="meinModalLabel">Tipps von <?php echo username($_GET['id'], $mysqli); ?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <div class="row text-center">
    <div class="col-8"><strong>Spiel</strong></div>
    <div class="col-2"><strong>Ergebnis</strong></div>
    <div class="col-2"><strong>Tipp</strong></div>
  </div>
  <?php
    $userID = $_GET['id'];
    $time   = time();

    // SQL Abfrage für alle Spiele
    $sql    = "SELECT g.ID, t.name AS geg_1, t2.name AS geg_2, t.short_name AS short_name1, t2.short_name AS short_name2, g.ergebnis_1, g.ergebnis_2, g.h_ergebnis_1, g.h_ergebnis_2, g.time, g.grp FROM `".prefix."_games` g
                INNER JOIN ".prefix."_team t ON (g.geg_1 = t.id) INNER JOIN ".prefix."_team t2 ON (g.geg_2 = t2.id) WHERE `time` <= '$time' ORDER BY time ASC";
    $result = $mysqli->query($sql);
    if ($result->num_rows == 0) {
      echo '<div class="row text-center"><div class="col-sm-12 col-xs-12">Aktuell können noch keine Tipps eingesehen werden</div></div>';
    }
    else {
      while ($game = mysqli_fetch_array($result)) {
      echo "<div class='row text-center'>";
      // SQL Abfrage für die Tipps
      $sql2     = "SELECT tipp1, tipp2 FROM ".prefix."_tipp WHERE userID = '".$userID."' AND gameID = '".$game['ID']."'";
      $result2  = $mysqli->query($sql2);
      while ($tipp = mysqli_fetch_array($result2)) {
        echo "<div class='col-2'>", flag($game['geg_1'], 35, 1), "</div>";
        echo "<div class='col-4'><div class='short_name'>", $game['short_name1'], "</div><div class='long_name'>", $game['geg_1'], "</div> : <div class='short_name'>", $game['short_name2'], "</div><div class='long_name'>", $game['geg_2'], "</div></div>";
        echo "<div class='col-2'>", flag($game['geg_2'], 35, 1), "</div>";
        echo "<div class='col-2'>", $game['ergebnis_1'], " : ", $game['ergebnis_2'], "</div>";
        echo "<div class='col-2'>", $tipp['tipp1'], " : ", $tipp['tipp2'], "</div>";
      }
      echo "</div>";
    }
    }
  ?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-green" data-dismiss="modal">Schließen</button>
</div>
