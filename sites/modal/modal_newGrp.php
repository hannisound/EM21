<?php
  header("Content-Type: text/html; charset=utf-8");
  require("../../includes/db.php");
  require("../../includes/function.inc.php");
?>

<div class="modal-header">
  <h5 class="modal-title" id="modal_admUser">Neue eigene Gruppe erstellen</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="form">
<form accept-charset="UTF-8" action="#newGrp" method="POST" id="newUserGrp">
  <div class="field-wrap">
		<label>
			Gruppenname:<span class="req">*</span>
		</label>
		<input class="form-control" type="text" required autocomplete="off" name="grpName" id="grpName"/>
	</div>
  <div class="field-wrap">
		<label>
			Kurzschreibweise / Tag / Shotcut<span class="req">*</span>
		</label>
		<input class="form-control" type="text" required autocomplete="off" name="shortcut" id="shortcut"/>
	</div>
    <input type="hidden" name="adminID" value="<?php echo $_GET['userID']; ?>"></input>
  </form>
  <div class="card bg-light">
    <div class="card-header">
        <h3 class="card-title">Was bringen und sollen Gruppen?</h3>
      </div>
      <div class="card-body">
        <ul>
          <li>Die Gruppenfunkion soll dir ermöglichen dich mit deinen Freunden direkt messen zu können.</li>
          <li>Du kannst deine Freunde, Gilde oder Clan einladen damit Ihr gemeinsam Mitfiebern und Tippen könnt</li>
          <li>In der Gruppe werden nur deine persönlich eingeladenen Mitspieler angezeigt</li>
          <li>Mit Freunden macht so ein Tippspiel viel mehr Spass als wenn man es alleine spielt - Nutze die Chance und lade Freunde ein</li>
        </ul>
      </div>
   </div>
 </div>
</div>
<div class="modal-footer">
  <button type="button" class="reload btn btn-secondary" data-dismiss="modal">Schließen</button>
  <button type="button" class="btn btn-green" id="newGrp">Gruppe erstellen</button>
  <div id="message"></div>
</div>

<script type="text/javascript">
  //
  // loaction.reload() nach data-miss
  //
  $('.reload').click(function() {
    location.reload();
  });

  //
  // Inputfelder Text nach unten schieben
  //
  $('.form').find('input, textarea').on('keyup blur focus', function (e) {

    var $this = $(this),
        label = $this.prev('label');
        small = $(this).next('small');

  	  if (e.type === 'keyup') {
  			if ($this.val() === '') {
            label.removeClass('active highlight');
            small.removeClass('inactive');
          } else {
            label.addClass('active highlight');
            small.addClass('inactive');
          }
      } else if (e.type === 'blur') {
      	if( $this.val() === '' ) {
      		label.removeClass('active highlight');
  			} else {
  		    label.removeClass('highlight');
  			}
      } else if (e.type === 'focus') {

        if( $this.val() === '' ) {
      		label.removeClass('highlight');
  			}
        else if( $this.val() !== '' ) {
  		    label.addClass('highlight');
  			}
      }

  });


  //
  // Neue userGruppe erstellen
  //
  $('button#newGrp').click(function(){
    if ($("#grpName").val() === "") {
      var fehler = false;
      $("#formGrpName").addClass("is-invalid");
      $("#grpName").addClass("is-invalid");
      $("#grpNameError").removeClass("hidden");
      $("#grpNameError").addClass("show");
    }
    else {
      $("#formGrpName").removeClass("is-invalid");
      $("#grpName").removeClass("is-invalid");
      $("#grpNameError").removeClass("show");
      $("#grpNameError").addClass("hidden");
    }
    if ($("#shortcut").val() === "" || $("#shortcut").val().length > 5) {
      var fehler = false;
      $("#formShortcut").addClass("is-invalid");
      $("#shortcut").addClass("is-invalid");
      $("#shortcutError").removeClass("hidden");
      $("#shortcutError").addClass("show");
    }
    else {
      $("#formShortcut").removeClass("is-invalid");
      $("#shortcut").removeClass("is-invalid");
      $("#shortcutError").removeClass("show");
      $("#shortcutError").addClass("hidden");
    }
    if (fehler == false) {
      return false
    }
    else {
      var data = $('#newUserGrp').serialize();
      $.ajax({
      type: "POST",
      url: "includes/update_ajax.php",
      data: data,
      success: function (msg) {
        $('#message').html(msg);
        $('#meinModal').modal('hide');
      },
      error: function(msg) {
        $('#message').html(msg);
      }
    })
    }
  });
</script>
