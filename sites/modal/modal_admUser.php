		<?php
			header("Content-Type: text/html; charset=utf-8");
			require("../../includes/db.php");
		  require("../../includes/function.inc.php");
		?>
		<div class="modal-header">
		  <h5 class="modal-title" id="modal_admUser"><?php echo username($_GET['userID'], $mysqli); ?> bearbeiten</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  	<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">

		<?php

		  	sec_session_start();

		  	if ($_SESSION['status'] < 2) {
		  		echo "Sie haben keine Berechtigung um diese Seite zu benutzen";
		  	}
		  	else {
				$sql = "SELECT ID, username, email, status, active, newsletter, profilbild FROM ".prefix."_users WHERE ID = '".$_GET['userID']."'";
				$result = $mysqli->query($sql);
				$user = $result->fetch_array(MYSQLI_ASSOC);

				// Status ermitteln
				if($user['status'] >= "3") {
					$status = "Admin"	;
				}
				else {
					$status = "User";
				}

				// Aktiviertstatus vorbereiten
				if($user['active'] == 1) {
					$active = "checked";
				}
				else {
					$active = "";
				}

				// Newsletter/Benachrichtigung vorbereiten
				if($user['newsletter'] == 1) {
					$check = "checked";
				}
				else {
					$check = "";
				}

				// Profilbild in Bild umwandeln
			    if (!empty($user['profilbild'])) {
			    	$profilbild = "<img id='profilbild' src='images/profilbild/".$user['profilbild']."' height='50px' width='50px'><br><button id='picDelete' data-id='".$user['ID']."' class='btn btn-sm btn-danger'><i class='fa fa-times'></i> Profilbild löschen</button>";
			    }
			    else {
			        $profilbild = "<img src='images/profilbild/nopic/Nopic.png' height='50px' width='50px'>";
			    }
			?>
				<div class="row">
					<div class="col">Username:</div>
					<div class="col"><?php echo $user['username']; ?></div>
				</div>
				<div class="row">
					<div class="col">E-Mail:</div>
					<div class="col"><?php echo $user['email']; ?></div>
				</div>
				<div class="row">
					<div class="col">Status:</div>
					<div class="col"><?php echo $status; ?></div>
				</div>
				<div class="row">
					<div class="col">Aktiviert:</div>
					<div class="col">
						<div class="custom-control custom-switch">
                  			<input type="checkbox" class="custom-control-input"  data-id="<?php echo $user['ID']; ?>" id="activate" name="activate" <?php echo $active; ?>>
                  			<label class="custom-control-label" id="label_activate" for="activate">akt./deak.</label>
                		</div>
					</div>
					<div class="col-12" id="activateMeldung"></div>
				</div>
				<div class="row">
					<div class="col">Benachrichtigungen:</div>
					<div class="col">
						<div class="custom-control custom-switch">
                  			<input type="checkbox" class="custom-control-input" data-id="<?php echo $user['ID']; ?>" id="newsletter" name="newsletter" <?php echo $check; ?>>
                  			<label class="custom-control-label" id="label_newsletter" for="newsletter">akt./deak.</label>
                		</div>
					</div>
					<div class="col-12" id="newsletterMeldung"></div>		
				</div>
				<div class="row">
					<div class="col">Profilbild:</div>
					<div class="col"><?php echo $profilbild; ?></div>
					<div class="col-12" id="profilbildMeldung"></div>
				</div>
				<div class="row">
					<div class="col">Passwort zurücksetzen:</div>
					<div class="col"><button id="PwReset" data-id="<?php echo $user['ID']?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Zurücksetzen</button></div>
					<div col="col-12" id="PwResetMeldung"></div>
				</div>
				<div class="row">
					<div class="col">Account löschen:</div>
					<div class="col"><button id="AccDelete" data-id="<?php echo $user['ID']?>" class="btn btn-danger btn-sm"><i class="fa fa-user-times"></i> Acc. löschen</button></div>
					<div class="col-12" id="AccDeleteMeldung"></div>
				</div>
			</div>
			<?php
			}
			?>
			<div class="modal-footer">
			  <button type="button" class="reload btn btn-default" data-dismiss="modal">Schließen</button>
			</div>
	</body>
</html>

<script type="text/javascript">
  	//
  	// loaction.reload() nach data-miss
  	//
 	$('.reload').click(function() {
    	location.reload();
  	});

	//
	// adm_user - Profilbild entfernen
	//
	$("button#picDelete").click(function(){
		userID	= $(this).attr("data-id");
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: "adm_picDeleteID=" + userID,
			success: function(msg) {
				$("#profilbild").attr("src", "images/profilbild/nopic/Nopic.png");
				$("#profilbildMeldung").html(msg).fadeIn(500).delay(3000).toggle(500);
			},
			error: function(msg) {

			}
		});
	});

	//
	// adm_user - Aktivierung ändern
	//

	$("#activate").change(function(){
		var status  	= $(this).prop('checked');
		userID			= $(this).attr('data-id');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: "adm_ActivateStatus=" + status + "&userID=" + userID,
			success: function(msg) {

				$("#activateMeldung").html(msg).fadeIn(500).delay(3000).toggle(500);
			},
			error: function(msg) {

			}
		});
	});

	//
	// adm_user - Benachrichtigung ändern
	//

	$("#newsletter").change(function(){
		adm_userID			= $(this).data("id");
		var status  		= $(this).prop('checked');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: "adm_NewsletterStatus=" + status + "&userID=" + adm_userID,
			success: function(msg) {

				$("#newsletterMeldung").html(msg).fadeIn(500).delay(3000).toggle(500);
			},
			error: function(msg) {

			}
		});
	});

	//
	// adm_user - Account löschen
	//
	$("#AccDelete").click(function(){
		userID			= $(this).attr('data-id');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: "adm_AccDelete=" + userID,
			success: function(msg) {

				$("#AccDeleteMeldung").html(msg).fadeIn(500).delay(3000).toggle(500);
			},
			error: function(msg) {

			}
		});
	});

	//
	// adm_user - Passwortz zurücksetzen
	//
	$("#PwReset").click(function(){
		userID			= $(this).attr('data-id');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: "adm_PwReset=" + userID,
			success: function(msg) {

				$("#PwResetMeldung").html(msg)/*.fadeIn(500).delay(3000).toggle(500)*/;
			},
			error: function(msg) {

			}
		});
	});
</script>
