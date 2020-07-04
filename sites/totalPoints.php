<table class="table table-striped table-responsive table-sm text-center">
  <tbody>
    <tr>
      <th class="text-center">Platz</th>
      <th class="text-center" colspan="3" width="20%">User</th>
      <th class="text-center"><span class="d-sm-block d-md-none">V</span><span class="d-none d-sm-none d-md-block">Vorrunde</span></th>
      <th class="text-center"><span class="d-sm-block d-md-none">F</span><span class="d-none d-sm-none d-md-block">Finalrunde</span></th>
      <th class="text-center"><span class="d-sm-block d-md-none">S</span><span class="d-none d-sm-none d-md-block">Spezial</span></th>
      <th class="text-center"><span class="d-sm-block d-md-none">G</span><span class="d-none d-sm-none d-md-block">Gesamt</span></th>
    </tr>

    <?php
        $place          = 1;
        $lastTotal      = 0;
        $lastPlace      = 1;
      $sql            = "SELECT p.username AS userID, p.ID, top.preRound, top.finRound, top.special, top.total, top.lastPlace FROM `".prefix."_toplist` top INNER JOIN `".prefix."_users` p ON (top.userID = p.ID ) ORDER BY top.total DESC";
      $result = $mysqli->query($sql);
      while ($toplist = mysqli_fetch_array($result)) {
            $sql1       = "SELECT profilbild FROM ".prefix."_users WHERE username = '".$toplist['userID']."'";
            $result2    = $mysqli->query($sql1);
            $pic        = mysqli_fetch_array($result2);

            if ($pic['profilbild'] == "") {
                $picUrl = "nopic/Nopic.png";
            }
            else {
                $picUrl = $pic['profilbild'];
            }

            if ($toplist['userID'] == $_SESSION['username']) {
                $info = "bg-green";
            }
            else {
                $info = "";
            }
    ?>
    <tr class="<?php echo $info; ?>">
      <td style="vertical-align: middle;"><?php // Prüfen wenn Punkte gleich sind und die Platzierung anpassen
            if ($lastTotal != $toplist['total']) {
                echo $place.".";
                // Gefixt:FIXME - LastPlace Function ergänzen/implementieren
                echo lastPlace($place, $toplist['lastPlace']);
                $lastPlace = $place;
                $place++;
            }
            else {
              echo $place.".";
                echo lastPlace($lastPlace, $toplist['lastPlace']);
                $place++;
            }
            ?>
        </td>
      <td width="100px" style="vertical-align: middle;"><?php echo "<img src='images/profilbild/".$picUrl."' width='30px' heigth='auto'>";?></td>
        <td class="text-left" style="vertical-align: middle;"><?php echo $toplist['userID'];?></td>
        <td style="vertical-align: middle;">
            <button data-remote="sites/modal/modal_tipps.php?id=<?php echo $toplist['ID'];?>" data-toggle="modal" data-target="#meinModal" data-id="<?php echo $toplist['ID'];?>" class="btn btn-green btn-sm" role="button">
            <i class="fa fa-search"></i>
            </button>
        </td>
      <td style="vertical-align: middle;"><?php echo $toplist['preRound'];?></td>
      <td style="vertical-align: middle;"><?php echo $toplist['finRound'];?></td>
      <td style="vertical-align: middle;"><?php echo $toplist['special'];?></td>
      <td style="vertical-align: middle;"><?php echo $toplist['total'];?></td>
    </tr>
    <?php
        $lastTotal = $toplist['total'];
        }
    ?>
  </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="meinModal" tabindex="-1" role="dialog" aria-labelledby="meinModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
    </div>
  </div>
</div>
<!-- /.modal -->
