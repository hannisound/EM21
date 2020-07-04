<div class="text-right" style="padding: 5px 0px;">
<?php
  $meldung = "";
  // Wenn User kein Admin ist - Gruppe verlassen Button erstellen
  if ($tab['adminID'] != $_SESSION['user_id']) {
    // Gruppe Verlassen Button erstellen
    echo '<button class="btn btn-danger" data-toggle="collapse" data-target="#leaveGrp'.$tab['ID'].'" aria-expanded="false" aria-controls="leaveGrp'.$tab['ID'].'><i class="fa fa-times"></i> Gruppe verlassen</button>';

    // Gruppe Verlassen Fenster erstellen
    echo '<div class="collapse" id="leaveGrp'.$tab['ID'].'" style="padding-top: 5px;">
            <div class="well"><h4>Wollen Sie die Gruppe wirklich verlassen?</h4>
            <button class="btn btn-danger" id="leaveGrp" data-id="'.$tab['ID'].'" data-usrID="'.$_SESSION['user_id'].'">Gruppe verlassen</button>
            </div>
          </div>';
  }
  else {
    // Wenn User Admin der Gruppe ist, das Adminpanel vorbereiten
    
    // User Group - Remove Member
    if(isset($_POST['removeMember'])) {
      if (empty($_POST['member'])) {
        echo '<div class="alert alert-warning">Sie haben keinen Spieler ausgewählt.</div>';
      }
      else {
        $member = $_POST['member'];
        $grpID  = $_POST['grpID'];
        // Alle Checkboxen durchlaufen und alle gecheckten Löschen
        foreach ($member as $key) {
          if(is_array($member)){
            // gecheckter Eintrag löschen
            removeMember($key, $grpID, $mysqli);
          }
          else {
            $meldung = "<div class='alert alert-danger'>Fehler beim Zusammenstellen der Anfrage</div>";
          }
        }
          $meldung = "<div class='alert alert-success'>Der/Die Spieler wurden Erfolgreich aus der Gruppe entfernt</div>";
      }
    }

    // Mitglieder hinzufügen
    echo '<div class="btn-group">
          <button class="btn btn-success" data-toggle="collapse" data-target="#newMember'.$tab['ID'].'" aria-expanded="false" aria-controls="newMember'.$tab['ID'].'" title="Mitglieder hinzufügen" data-collapse-group="myDivs"><i class="fa fa-user-plus"></i> Mitglieder hinzufügen</button>';
    // Mitglieder entfernen
    echo '<button class="btn btn-warning" data-toggle="collapse" data-target="#removeMember'.$tab['ID'].'" aria-expanded="false" aria-controls="removeMember'.$tab['ID'].'" title="Mitglied entfernen" data-collapse-group="myDivs"><i class="fa fa-user-times"></i> Mitglied entfernen</button>';
    // Gruppe löschen
    echo '<button class="btn btn-danger" data-toggle="collapse" data-target="#grpDelete'.$tab['ID'].'" aria-expanded="false" aria-controls="grpDelete'.$tab['ID'].'" title="Gruppe löschen" data-collapse-group="myDivs"><i class="fa fa-trash-o"></i> Gruppe löschen</button>
          </div>';

    // Member hinzufügen Fenster erstellen
    echo '<div class="collapse" id="newMember'.$tab['ID'].'" style="padding-top: 5px;">
            <div class="well">
              <div class="col-md-12"><h4>Wen wollen Sie hinzufügen?</h4></div>
              <div class="row">
                <div class="col-md-10">
                  <input id="addNewMember_'.$tab['ID'].'" class="form-control autocomplete" placeholder="Geben Sie den gewünschten Usernamen ein">
                </div>
                <div class="col-md-2">
                  <button class="btn btn-success addMember" id="addMember" data-grpID="'.$tab['ID'].'"><i class="fa fa-plus"></i> Hinzufügen</button>
                </div>
                <div id="meldung_'.$tab['ID'].'" style="display:none;"></div>
              </div>
            </div>
          </div>';
    // Leave Fenster erstellen
    echo '<div class="collapse" id="removeMember'.$tab['ID'].'" style="padding-top: 5px;">
            <div class="well">
              <h4>Wer soll entfernt werden?</h4>';
    echo '    <form id="memberRemove" method="POST" action="index.php?site=points&tab='.$tab['ID'].'">';
              $sql4 = "SELECT u.username AS userID FROM ".prefix."_userGrpMember uGM INNER JOIN ".prefix."_users u ON (uGM.userID = u.ID) WHERE userGrpID = '".$tab['ID']."'";
              $result4 = $mysqli->query($sql4);
              while ($member = mysqli_fetch_array($result4)) {
                $sql5 = "SELECT ID FROM ".prefix."_users WHERE username = '".$member['userID']."'";
                $result5 = $mysqli->query($sql5);
                $userID = mysqli_fetch_array($result5);
                if ($tab['adminID'] == $userID['ID']) {
                  // Wenn der User Gruppen Admin ist erhält dieser kein Verlassen button
                }
                else {
                  echo "".$member['userID']." <input type='checkbox' value='".$member['userID']."' name='member[]'><br>";
                }
              }
    echo '
              <input type="hidden" name="grpID" value="'.$tab['ID'].'">
              <button class="btn btn-warning" type="submit" name="removeMember" id="removeMember"><i class="fa fa-user-times"></i> Mitglied entfernen</button>
              </form>
            </div>
          </div>';
    // Delete Fenster erstellen
    echo '<div class="collapse" id="grpDelete'.$tab['ID'].'" style="padding-top: 5px;">
            <div class="well"><h4>Wollen Sie die Gruppe wirklich löschen?</h4>
            <button class="btn btn-danger" id="userGrpDelete" data-id="'.$tab['ID'].'" data-admID="'.$_SESSION['user_id'].'"><i class="fa fa-trash-o"></i> Gruppe löschen</button>
            </div>
          </div>';
    
    // Ausgabe für Meldung
    echo $meldung;
  }
  echo '</div>';
  echo '<h2 class="text-left">'.htmlentities(html_entity_decode($tab['name'])).'</h2>';
?>
<table class="table table-striped text-center" id="test">
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
      $sql3 		= "SELECT u.username AS userID, u.ID, top.preRound, top.finRound, top.special, top.total, top.lastPlace FROM ".prefix."_userGrpMember uGM INNER JOIN ".prefix."_users u ON (uGM.userID = u.ID)
                      INNER JOIN `".prefix."_toplist` top ON (uGM.userID = top.userID) WHERE uGM.userGrpID = '".$tab['ID']."' ORDER BY top.total DESC";
      $result3 		= $mysqli->query($sql3);
      while ($toplist = mysqli_fetch_array($result3)) {
        $sql1       = "SELECT profilbild FROM ".prefix."_users WHERE username = '".$toplist['userID']."'";
        $result2    = $mysqli->query($sql1);
        $pic        = mysqli_fetch_array($result2);

        if ($pic['profilbild'] == "") {
          $picUrl = "nopic/Nopic.png";
        }
        else {
          $picUrl = $pic['profilbild'];
        }

        if ($toplist['userID'] == $_SESSION['username'])
        {
          $info = "bg-green";
        }
        else {
          $info = "";
        }

?>
  <tr class="<?php echo $info;?>">
    <td><?php // PrÃ¼fen wenn Punkte gleich sind und die Platzierung anpassen
            if ($lastTotal != $toplist['total']) {
                echo $place. ".";
                $place++;
            }
            else {
                $place++;
            }
            ?></td>
    <td width="100px"><?php echo "<img src='images/profilbild/".$picUrl."' width='40px' heigth='auto'>";?></td>
    <td class="text-left"><?php echo $toplist['userID'];?></td>
    <td>
      <button data-remote="sites/modal/modal_tipps.php?id=<?php echo $toplist['ID'];?>" data-toggle="modal" data-target="#meinModal2" data-id="<?php echo $toplist['ID'];?>" class="btn btn-green btn-sm text-white" role="button">
      <i class="fa fa-search"></i>
    </button>
    </td>
    <td><?php echo $toplist['preRound'];?></td>
    <td><?php echo $toplist['finRound'];?></td>
    <td><?php echo $toplist['special'];?></td>
    <td><?php echo $toplist['total'];?></td>
  </tr>
<?php
        $lastTotal = $toplist['total'];
        }
    ?>
</table>

<script>
  $("[data-collapse-group='myDivs']").click(function () {
    var $this = $(this);
    $("[data-collapse-group='myDivs']:not([data-target='" + $this.data("target") + "'])").each(function () {
        $($(this).data("target")).removeClass("in").addClass('collapseing');
    });
});
</script>
