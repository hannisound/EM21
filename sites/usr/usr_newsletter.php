<?php
	$sql 	= "SELECT newsletter FROM ".prefix."_users WHERE username = '".$_SESSION['username']."'";
	$result = $mysqli->query($sql);
	$nl 	= mysqli_fetch_array($result);

	if ($nl['newsletter'] == "1") {
        $check = "checked";
        $label = "Sie erhalten derzeit Benachrichtigungen";
	}
	else {
        $check = "";
        $label = "Sie erhalten derzeit keine Benachrichtigungen";
	}
?>
    <h2>Benachrichtigungseinstellung ändern</h2><br>
    <div class=field-wrap">
		<div class="custom-control custom-switch">
			<input type="checkbox" class="custom-control-input" id="newsletter" name="newsletter" <?php echo $check; ?>>
			<label class="custom-control-label" id="label_newsletter" for="newsletter"><?php echo $label; ?></label>
		</div>
	</div>
   	<div id="message"></div>
    <br>
    <div class="card">
        <h5 class="card-header">Hinweise</h5>
        <div class="card-body">
            <ul class="list-group-flush">
                <li class="list-group-item">Die Benachrichtigung informiert dich über wichtige neue Informationen zum Tippspiel</li>
                <li class="list-group-item">Falls noch einige Spiele nicht getippt sind, wirst du rechtzeitig vorher informiert, das noch Tipps fehlen</li>
                <li class="list-group-item">Falls einige Spezialtipps noch offen sind, wirst du rechtzeitig daran erinnert diese noch einzutragen</li>
                <li class="list-group-item">Für Zukünftige Europa- und Weltmeisterschaften unseres Tippsspiel wirst du exklusive Einladungen bekommen</li>
            </ul>
        </div>
    </div>

<script>
  $(function() {
    $('#newsletter').change(function() {
        var status          = $(this).prop('checked');
    	var status2 		= "nlStatus=" + $(this).prop('checked');
    	var username 	    = "&usr=<?php echo $_SESSION['username']; ?>";
    	$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: status2 + username,
			success: function (msg) {
                if (status == false) {
                    $('#message').addClass('alert');
                    $('#message').addClass('alert-success');
                    $('#label_newsletter').text('Sie erhalten derzeit keine Benachrichtigungen');
                    $('#message').html('Dei Benachrichtigung wurde Erfolgreich abgemeldet').fadeIn(500).delay(3000).toggle(500);
                }
                else {
                    $('#message').addClass('alert');
                    $('#message').addClass('alert-success');
                    $('#label_newsletter').text('Sie erhalten derzeit Benachrichtigungen');
                    $('#message').html('Die Benachrichtigung wurde Erfolgreich abonniert').fadeIn(500).delay(3000).toggle(500);
                }	
			},
			error: function() {
                $('#message').addClass('alert');
                $('#message').addClass('alert-danger');
				$('#message').html('Es ist leider ein Fehler aufgetreten, versuchen Sie es bitte erneut').fadeIn(500).delay(3000).toggle(500);	
			}
		});
    })
  })
</script>