$(document).ready(function() {
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
	// User Special Tipps WM Meister Auswählen
	//
		$('a#wm_meister').click(function(){
		var meister_ID 	= $(this).attr('name');
		var userID 		= $(this).attr('field');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'wmMeister=' + meister_ID + '&userID=' + userID,
			success: function (msg) {
			},
			error: function(msg)
			{
			}
		});
	});

	//
	// User Special Tipps WM Endstation Auswählen
	//
		$('a#Endstation').click(function(){
		var endstation_ID 	= $(this).attr('name');
		userID 				= $(this).attr('field');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'EndStation=' + endstation_ID + "&userID=" + userID,
			success: function (msg)
			{
				$('#meldung').html(msg);
				if (endstation_ID == "8" )
				{
					$('#vorrunde').attr("src", "images/runden/vorrunde.png");
					$('#achtelfinale').attr("src", "images/runden/achtelfinale_kreuz.png");
					$('#viertelfinale').attr("src", "images/runden/viertelfinale_kreuz.png");
					$('#halbfinale').attr("src", "images/runden/halbfinale_kreuz.png");
					$('#finale').attr("src", "images/runden/finale_kreuz.png");
					$('span#endstation').addClass("hidden");
				}
				else if (endstation_ID == "9")
				{
					$('#vorrunde').attr("src", "images/runden/vorrunde_kreuz.png");
					$('#achtelfinale').attr("src", "images/runden/achtelfinale.png");
					$('#viertelfinale').attr("src", "images/runden/viertelfinale_kreuz.png");
					$('#halbfinale').attr("src", "images/runden/halbfinale_kreuz.png");
					$('#finale').attr("src", "images/runden/finale_kreuz.png");
					$('span#endstation').addClass("hidden");
				}
				else if (endstation_ID == "10")
				{
					$('#vorrunde').attr("src", "images/runden/vorrunde_kreuz.png");
					$('#achtelfinale').attr("src", "images/runden/achtelfinale_kreuz.png");
					$('#viertelfinale').attr("src", "images/runden/viertelfinale.png");
					$('#halbfinale').attr("src", "images/runden/halbfinale_kreuz.png");
					$('#finale').attr("src", "images/runden/finale_kreuz.png");
					$('span#endstation').addClass("hidden");
				}
				else if (endstation_ID == "11")
				{
					$('#vorrunde').attr("src", "images/runden/vorrunde_kreuz.png");
					$('#achtelfinale').attr("src", "images/runden/achtelfinale_kreuz.png");
					$('#viertelfinale').attr("src", "images/runden/viertelfinale_kreuz.png");
					$('#halbfinale').attr("src", "images/runden/halbfinale.png");
					$('#finale').attr("src", "images/runden/finale_kreuz.png");
					$('span#endstation').addClass("hidden");
				}
				else if (endstation_ID == "13")
				{
					$('#vorrunde').attr("src", "images/runden/vorrunde_kreuz.png");
					$('#achtelfinale').attr("src", "images/runden/achtelfinale_kreuz.png");
					$('#viertelfinale').attr("src", "images/runden/viertelfinale_kreuz.png");
					$('#halbfinale').attr("src", "images/runden/halbfinale_kreuz.png");
					$('#finale').attr("src", "images/runden/finale.png");
					$('span#endstation').addClass("hidden");
				}
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	//
	// User Specialtipps - Torschützenkönig eintragen
	//
	$("button#torKoenig").click(function() {
		var tor_koenig 		= $("#tor_Koenig").val();
		userID 				= $("#tor_Koenig").attr('field');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'torKoenig=' + tor_koenig + "&userID=" + userID,
			success: function (msg)
			{
				$('#message').html(msg);
				/*$('#message').addClass("alert alert-success text-center");*/
				$('span#torkoenig').addClass("hidden");
				//location.reload();
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	//
	// User Specialtipp - Gruppensieger
	//
	$("select#grpSieger").change(function() {
		var grpID 		= $(this).attr('field');
		var sieger 		= $(this).val();
		userID 			= $(this).attr('name');
		var oldSieger 	= $(".grpSieger"+grpID).attr('data-sieger');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'grpID=' + grpID + "&sieger=" + sieger + "&userID=" + userID,
			success: function (msg)
			{
				count = $("span#grpsieger").attr("data-count");
				if (sieger == "") {
					count = ++count;
				}
				else if (oldSieger != "") {
					count = count;
				}
				else {
					count = --count;
				}
				$(".grpSieger"+grpID).attr("data-sieger", sieger);
				$("span#grpsieger").attr("data-count", count);
				$("span#grpsieger").html(count);
				$('#meldung_' + grpID).html(msg).fadeIn(500).delay(3000).toggle(500);
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	//
	// User Specialtipp - Gruppenzweiter
	//
	$("select#grpZweiter").change(function() {
		var grpID 		= $(this).attr('field');
		var zweiter		= $(this).val();
		userID 			= $(this).attr('name');
		var oldZweiter 	= $(".grpZweiter"+grpID).attr('data-sieger');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'grpID=' + grpID + "&zweiter=" + zweiter + "&userID=" + userID,
			success: function (msg)
			{
				count = $("span#grpsieger").attr("data-count");
				if (zweiter == "") {
					count = ++count;
				}
				else if (oldZweiter != "") {
					count = count;
				}
				else {
					count = --count;
				}
				$(".grpZweiter"+grpID).attr("data-sieger", zweiter);
				$("span#grpsieger").attr("data-count", count);
				$("span#grpsieger").html(count);
				$('#meldung2_' + grpID).html(msg).fadeIn(500).delay(3000).fadeOut(500);
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

  //
	// adm_specials Specialtipp - Gruppensieger
	//
	$("select#specialGrpSieger").change(function() {
		var grpID 		= $(this).attr('field');
		var sieger 		= $(this).val();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'adm_grpID=' + grpID + "&adm_sieger=" + sieger,
			success: function (msg)
			{
				$('#meldung_' + grpID).html(msg).fadeIn(500).delay(3000).toggle(500);
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	//
	// adm_specials Specialtipp - Gruppenzweiter
	//
	$("select#specialGrpZweiter").change(function() {
		var grpID 		= $(this).attr('field');
		var zweiter		= $(this).val();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'adm_grpID=' + grpID + "&adm_zweiter=" + zweiter,
			success: function (msg)
			{
				$('#meldung2_' + grpID).html(msg).fadeIn(500).delay(3000).fadeOut(500);
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	//
	// adm_Specialtipps - Torschützenkönig eintragen
	//
	$("button#adm_torKoenig").click(function() {
		var tor_koenig 		= $("#adm_tor_Koenig").val();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'adm_torKoenig=' + tor_koenig,
			success: function (msg)
			{
				$('#message').html(msg).fadeIn(500).delay(3000).fadeOut(500);
				$('span#torkoenig').addClass("hidden");
				//location.reload();
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	//
	// adm_SpecialEM Endstation Eintragen/ändern
	//
		$('select#adm_Meister').change(function(){
		var endstation_ID 	= $(this).val();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'adm_Meister=' + endstation_ID,
			success: function (msg)
			{
				$('#MeisterMeldung').html(msg).fadeIn(500).delay(3000).fadeOut(500);
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	//
	// adm_Special Tipps EM Meister Auswählen
	//
		$('select#adm_Endstation').change(function(){
		var endstation_ID 	= $(this).val();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'adm_EndStation=' + endstation_ID,
			success: function (msg)
			{
				$('#EndStationMeldung').html(msg).fadeIn(500).delay(3000).fadeOut(500);
			},
			error: function()
			{
				alert ("Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

  //
  // Torschützenkoenig auswählen
  //
  $('#tor_Koenig').devbridgeAutocomplete({
     serviceUrl:'sites/special/sp_search.php',
     minChars:3,
     fnFormatResult: function(value, data, currentValue) {
       return '<strong>'+value.substr(0,currentValue.length)+'</strong>'+value.substr(currentValue.length); }
   });

   //
   // adm_Torschützenkoenig auswählen
   //
   $('#adm_tor_Koenig').devbridgeAutocomplete({
      serviceUrl:'sites/special/sp_search.php',
      minChars:3,
      fnFormatResult: function(value, data, currentValue) {
        return '<strong>'+value.substr(0,currentValue.length)+'</strong>'+value.substr(currentValue.length); }
    });

    $('select#geg_1').change(function(){
    var gameID 	= $(this).attr('data-gameID');
    var teamID	= $(this).val();

    $.ajax({
    type: "POST",
    url: "includes/update_ajax.php",
    data: 'gameID=' + gameID + '&geg1_teamID=' + teamID,
    success: function (msg) {
      $('#meldung_'+gameID).html(msg).fadeIn(500).delay(3000).toggle(500);
    },
    error: function(msg)
    {
    }
  });
  });

  $('select#geg_2').change(function(){
    var gameID 	= $(this).attr('data-gameID');
    var teamID	= $(this).val();

    $.ajax({
    type: "POST",
    url: "includes/update_ajax.php",
    data: 'gameID=' + gameID + '&geg2_teamID=' + teamID,
    success: function (msg) {
      $('#meldung_'+gameID).html(msg).fadeIn(500).delay(3000).toggle(500);
    },
    error: function(msg)
    {
    }
  });
  });

  //
  // Datetime Picker Config Startdatum + Zeiten
  //
  jQuery('#datetimepicker3').datetimepicker({
    format:'d.m.Y H:i',
    inline:true,
    lang:'de',
    startDate:'2021/06/11',
	  allowTimes:[
      '12:00',
  		'14:00',
      '15:00',
      '16:00',
      '17:00',
  		'18:00',
      '19:00',
  		'21:00']
  });

  $.datetimepicker.setLocale('de');

//
// User Group - Löschen
//
$("button#userGrpDelete").click(function() {
  var grpID = $(this).attr("data-id");
  var admID = $(this).attr("data-admID");
  $.ajax({
    type: "POST",
    url: "includes/update_ajax.php",
    data: "grpID=" + grpID + "&admID=" + admID,
    success: function (msg) {
      location.reload();
      $('#meldung').html(msg);
    },
    error: function(msg) {
      alert(msg);
    }
  })
});

//
// User Group - Verlassen
//
// TODO:FIXME Keine Alert Meldung und Seitenreload durchführen
$("button#leaveGrp").click(function() {
	alert("Test");
  var grpID = $(this).attr("data-id");
  var usrID = $(this).attr("data-usrID");
  $.ajax({
    type: "POST",
    url: "includes/update_ajax.php",
    data: "grpID=" + grpID + "&usrID=" + usrID,
    success: function(msg) {
      location.reload();
    },
    error: function(msg) {
      alert(msg);
    }
  })
});

//
// User Group - Member suchen
//
$('.autocomplete').devbridgeAutocomplete({
    serviceUrl:'sites/userSearch.php',
    minChars:3,
    fnFormatResult: function(value, data, currentValue) {
      return '<strong>'+value.substr(0,currentValue.length)+'</strong>'+value.substr(currentValue.length); }
});

//
// User Group - AddMember
//
$(".addMember").click(function() {
  var grpID 	= $(this).attr("data-grpID");
  var user 	= $("#addNewMember_" + grpID).val();
  $.ajax({
    type: "POST",
    url: "includes/update_ajax.php",
    data: "grpID=" + grpID + "&user=" + user,
    success: function(msg) {
		/* Test für neuladen der Seite mit richtigen Tab */
		/*$('#nav-' + grpID).empty();
		$.ajax({
			type: "POST",
			url: "sites/userGrpPoints.php",
			data: "tab"
			success: function(msg) {
				
			}
		})*/
		$('#meldung_' + grpID).html(msg).fadeIn(500).delay(3000).toggle(500);	
    },
    error: function(msg) {
      $('#meldung_' + grpID).html(msg);
    }
  })
});

// user group - Member remove
$("button#removeMember").submit(function() {
	alert ("Test");
	var grpID = $(this).attr("data-grpID");
	var data = $('#memberRemove').serialize();
	$.ajax ({
		type: "POST",
		url: "sites/update_ajax.php",
		data: data,
		success: function() {
			alert ("Test");
		}, 
		error: function(msg) {

		}
	})

});

// FAQ Fenster schließen beim Laden der Seite
	$(".portlet-content").toggle();

	// FAQ Toggeln bei Klick
	$(".portlet-toggle").click(function() {
      var icon = $( this );
      icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
      icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
    });

    // FAQ Fenster sortieren (faq_edit.php)
	$('#faq_sort').sortable(
	{
			update: function(event, ui)
			{
				$.get("includes/update_ajax.php?" + $(this).sortable('serialize'));
			}
	});

// FAQ Fenster designen (faq_edit.php)
	$(".faq")
		.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
		.find( ".faq-header" )
		.addClass( "ui-widget-header ui-corner-all" )
		.prepend( "<span class='ui-icon ui-icon-minusthick faq-toggle'></span>");

// Neuen FAQ-Eintrag an den Server schicken und Speichern
	$("#add_new_faq").submit(function(){
		var data = $('#add_new_faq').serialize();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: data,
			success: function (msg)
			{
				location.reload();
			},
			error: function()
			{
				alert ("#001 Es ist ein Fehler aufgetreten");
			}

		});
		return false;
	});

  // FAQ Eintrag an Edit Forumular übergeben
	$("a#faq_edit").click(function(){
		var ID = $(this).attr('name');
		var faq_frage = $('#faq_frage_'+ID).text();
		var faq_antwort = $('#faq_antwort_'+ID).text();
		$('#ID_edit').val(ID);
		$('#frage_edit').val(faq_frage);
		$('#antwort_edit').val(faq_antwort);
	});

  // FAQ Eintrag ändern
	$("#edit_faq").submit(function(){
		var data = $('#edit_faq').serialize();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: data,
			success: function (msg)
			{
				location.reload();
			},
			error: function()
			{
				alert ("#003 Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	// FAQ Eintrag Löschen
	$("a#faq_delete").click(function(){
		var deleteID = $(this).attr('name');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'faq_delete=' + deleteID,
			success: function (msg)
			{
				location.reload();
			},
			error: function()
			{
				alert ("#002 Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	// News Eintrag an Edit Forumular übergeben
	$("a#news_edit").click(function(){
		var ID = $(this).attr('name');
		var news_title = $('#news_title_'+ID).text();
		var news_news = $('#news_news_'+ID).text();
		$('#news_ID_edit').val(ID);
		$('#title_edit').val(news_title);
		$('#news_edit').val(news_news);
	});

	// News Eintrag ändern
	$("#edit_news").submit(function(){
		var data = $('#edit_news').serialize();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: data,
			success: function (msg)
			{
				location.reload();
			},
			error: function()
			{
				alert ("#004 Es ist ein Fehler aufgetreten");
			}

		});
		return false;
	});

  	//
	// adm_news_edit.php - Übergabe der News Beiträge ans Formular oder Löschung anweisen
	//

	// News Eintrag Löschen
	$("a#news_delete").click(function(){
		var deleteID = $(this).attr('name');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'news_delete=' + deleteID,
			success: function (msg)
			{
				location.reload();
			},
			error: function()
			{
				alert ("#005 Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	// Neuen News-Eintrag an den Server schicken und Speichern
	$("#add_new_news").submit(function(){
		var data = $('#add_new_news').serialize();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: data,
			success: function (msg)
			{
				location.reload();
			},
			error: function()
			{
				alert ("#006 Es ist ein Fehler aufgetreten");
			}

		});
		return false;
	});

	// Sitetitle an Edit Forumular übergeben
	$('button#edit_siteTitle').click(function(){
		var ID = $(this).attr('name');
		var site = $('span#site_'+ID).text();
		var title = $('span#title_'+ID).text();
		var subtitle = $('span#subtitle_'+ID).text();
		$('#ID_edit').val(ID);
		$('#site').val(site);
		$('#title').val(title);
		$('#subtitle').val(subtitle);
	});

	// Neuen Seitentitel speichern
	$("#add_site_title").submit(function() {
		var data = $("#add_site_title").serialize();
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: data,
			success: function(data) {
				$("#meldung").html(data);
			},
			error: function(){
				alert("Es ist ein Fehler beim Speichern aufgetreten");
			}
		});
		return false;

	});

	// Seitentitel Löschen
	$('button#del_siteTitle').click(function(){
		var deleteID = $(this).attr('name');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: 'del_siteTitle=' + deleteID,
			success: function (msg)
			{
				location.reload();
			},
			error: function()
			{
				alert ("#005 Es ist ein Fehler aufgetreten");
			}
		});
		return false;
	});

	// Countdown für Startseite - Datum / Uhrzeit entsprechend anpassen
	$('#clock').countdown('2021/06/11 21:00:00', {elapse: true})
  		.on('update.countdown', function(event) {
    	var $this = $(this);
    	if (event.elapsed) {
      		$this.html(event.strftime('<span>Die Fußballeuropameisterschaft läuft bereits</span>'));
    	} else {
      		$(this).html(event.strftime('%D Tagen %H Stunden %M Minuten %S Sekunden'));
    	}
	});

	// Countdown für Homeseite - Datum / Uhrzeit entsprechend anpassen
  	$('#clock2').countdown('2021/06/11 21:00:00', {elapse: true})
  		.on('update.countdown', function(event) {
    	var $this = $(this);
    	if (event.elapsed) {
      		$this.html(event.strftime('<span>Die UEFA EURO 2021 ist soeben gestartet</span>'));
    	} else {
      		$(this).html(event.strftime('%D Tagen %H Stunden %M Minuten %S Sekunden'));
    	}
	});


	//
	// Neue Gruppe Erstellen Modal öffnen - Pfad aus data-remote auslesen
	//
	$('#newGrpModal').on('show.bs.modal', function (e) {
		var button = $(e.relatedTarget);
		var modal = $(this);

		// load content from value of data-remote url
		modal.find('.modal-body').load(button.data("remote"));
	});

	//
	// Admin User Interface Modal öffnen - Pfad aus data-remote auslesen
	//
	$('#modal_admUser').on('show.bs.modal', function (e) {
		var button = $(e.relatedTarget);
		var modal = $(this);

		// load content from value of data-remote url
		modal.find('.modal-body').load(button.data("remote"));
	});

	//
	// Tipps von Usern auslesen Modal öffnen
	//
	$('body').on('click', '[data-toggle="modal"]', function(){
		$($(this).data("target")+' .modal-content').load($(this).data("remote"));
	});

	// destroy modal for reuse
	$('.modal').on('hidden.bs.modal', function() {
		$(this).removeData('bs.modal');
	});
});
