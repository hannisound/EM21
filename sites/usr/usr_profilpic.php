<?php
	$sql 	= "SELECT profilbild, ID FROM ".prefix."_users WHERE username = '".$_SESSION['username']."'";
	$result = $mysqli->query($sql);
	$usr 	= mysqli_fetch_array($result);
	if ($usr['profilbild'] == "") {
		$pic = "nopic/Nopic.png";
	}
	else {
		$pic = $usr['profilbild'];
	}
?>
<h2>Profilbild hochladen</h2><br>
<!-- The fileinput-button span is used to style the file input field as button -->
    <span class="btn btn-green fileinput-button">
        <span><i class="fa fa-plus"></i> Profilbild hochladen</span>
        <!-- The file input field used as target for the file upload widget -->
				<input id="fileupload" type="file" name="files[]">
    </span>

    <button class="btn btn-danger delete" id="picDelete" name="<?php echo $usr['ID']; ?>">
    <i class="fa fa-trash-o"></i>
    <span>Profilbild löschen</span>
    </button>
    <br>
    <br>
    <img src="images/profilbild/<?php echo $pic ?>"class="img-fluid img-thumbnail">
    <br>
    <br>
    <!-- The global progress bar -->
    <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <!-- The container for the uploaded files -->
    <div id="files" class="files"></div>
    <br>
    <div class="card">
  		<h6 class="card-header">Hinweise</h6>
        <div class="card-body">
            <ul>
                <li>Die Maximale Größe des Profilbildes darf max. <strong>3 MB</strong> betragen.</li>
                <li>Es sind nur folgende Bildtypen erlaubt: (<strong>JPG, GIF, PNG</strong>)</li>
            </ul>
        </div>
    </div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/js/vendor/jquery.ui.widget.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/js/jquery.fileupload-image.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/js/jquery.fileupload-validate.js"></script>
<script>
/*jslint unparam: true, regexp: true */
/*global window, $ */
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = 'includes/uploader/',
        uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Profilbild wird hochgeladen...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abbrechen')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i, // Erlaubte Dateiformate
        maxFileSize: 3000000, // 3MB
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
            var node = $('<p/>')
                    .append($('<span/>').text(file.name));
            if (!index) {
                node
                    .append('<br>')
                    .append('<div id="picMessage" class="alert">')
                    .append(uploadButton.clone(true).data(data));
            }
            node.appendTo(data.context);
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="alert alert-danger text-center"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('Hochladen')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        $.each(data.result.files, function (index, file) {
            if (file.url) {
            	// Hier muss für Eintrag in die DB gesorgt werden
            	var link 		= file.url;
            	var username 	= "<?php echo $_SESSION['username'] ?>";
                /*  .attr('target', '_blank')
                    .prop('href', file.url);
                $(data.context.children()[index])
                    .wrap(link);*/
                    $.ajax({
						type: "POST",
						url: "includes/update_ajax.php",
						data: 'PicUpload=' + link + "&usr=" + username,
						success: function (msg)
						{
                            $('#picMessage').addClass('alert-success');
                            $('#picMessage').html('Das Profilbild wurde Erfolgreich hochgeladen').fadeIn(500).delay(3000).toggle(500);
						},
						error: function()
						{
							alert ("#001 Es ist ein Fehler aufgetreten");
						}
					});
            } else if (file.error) {
                var error = $('<span class="alert alert-danger text-center"/>').text(file.error);
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="alert alert-danger text-center"/>').text('Der Upload ist fehlgeschlagen!');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

	//
	// Profilbild löschen
	//
	$('button#picDelete').click(function() {
		var data = "picDeleteID=" + $(this).attr('name');
		$.ajax({
			type: "POST",
			url: "includes/update_ajax.php",
			data: data,
			success: function (msg) {
				location.reload();
			},
			error: function()
			{
				alert ("Fehler: #004");
			}
		});
	});
});
</script>
