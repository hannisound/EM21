<section id="adm_user">
  <div class="container-md">
    <div class="row">
      <div class="col-lg-10 mx-auto">
  <?php
    if (empty($_SESSION['status']) AND $_SESSION['status'] <= 1)
    {
      echo "<p class='error'>Sie haben keine Berechtigung diese Seite zu öffnen</p>";
    }
    else
    {
  ?>
      <form method="POST" action="" accept-charset="UTF-8">
        <table class="table table-striped sm-table text-center">
          <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Username</th>
            <th class="text-center">E-Mail</th>
            <th class="text-center">Status</th>
            <th class="text-center">Akt.</th>
            <th class="text-center">Newsletter</th>
            <th class="text-center">Profilbild</th>
            <th class="text-center">Bearbeiten</th>
          </tr>
          <?php
            // Daten aus der Datenbank auslesen
            $sql    = "SELECT ID, username, email, status, active, newsletter, profilbild FROM ".prefix."_users";
            $result = $mysqli->query($sql);
            while ($user = mysqli_fetch_array($result))
            {
                // Status definieren
                if ($user['status'] == 0) {
                    $status = "User";
                }
                else {
                  $status = "Admin";
                }

                // Aktivierungs in Bild umwandeln
                if ($user['active'] == 1) {
                  $active = "<a class='btn btn-success btn-sm' role='button' href='index.php?site=adm_user&delete=".$user['ID']."'>
                              <i class='fa fa-check'></i></a>";
                }
                else
                {
                  $active = "<a class='btn btn-danger btn-sm' role='button' href='index.php?site=adm_user&delete=".$user['ID']."'>
                                            <i class='fa fa-minus'></i></span></a>";
                }

                // Profilbild in Bild umwandeln
                if (!empty($user['profilbild'])) {
                  $profilbild = "<img src='images/profilbild/".$user['profilbild']."' height='20%' width='20%'>";
                }
                else {
                  $profilbild = "<img src='images/profilbild/nopic/Nopic.png' height='15%' width='15%'>";
                }

                // Newsletter in Switch umwandeln
                if ($user['newsletter'] == 1) {
                  $check = "checked disabled";
                }
                else {
                  $check = "disabled";
                }

            ?>
            <tr>
              <td data-label="ID"><?php echo $user['ID'];?></td>
              <td data-label="User"><?php echo $user['username'];?></td>
              <td data-label="E-Mail"><?php echo $user['email'];?></td>
              <td data-label="Status"><?php echo $status;?></td>
              <td data-label="Akt."><?php echo $active?></td>
              <td data-label="Newsletter">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="newsletter_<?php echo $user['ID']; ?>" name="newsletter" <?php echo $check; ?>>
                  <label class="custom-control-label" id="label_newsletter" for="newsletter_<?php echo $user['ID']; ?>"></label>
                </div></td>
              <td data-label="Profilbild"><?php echo $profilbild;?></td>
              <td data-label="Bearbeiten"><?php echo "<a class='btn btn-primary btn-sm text-white' role='button' href='' data-remote='sites/modal/modal_admUser.php?userID=".$user['ID']."' data-toggle='modal' data-target='#modal_admUser' data-id='Test'>
                              <i class='fa fa-cog'></i></a>";?></td>
              </tr>
            <?php
              }
            ?>
          </table>
        </form>
        <?php
          }
    	?>

    	<!-- Modal -->
  		<div class="modal fade" id="modal_admUser" tabindex="-1" role="dialog" aria-labelledby="modal_admUser" aria-hidden="true">
  		  <div class="modal-dialog" role="document">
  		    <div class="modal-content">
  		    </div>
  		  </div>
  		</div>
      <!-- /.modal -->
      </div>
    </div>
  </div>
</section>
