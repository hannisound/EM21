 <?php 

	if (login_check($mysqli) === true) 
	{
		// Login war Erfolgreich und es kann weitergemacht werden
	}
	else
	{
		//$_SESSION['message'] = "Es ist ein Fehler beim Einloggen aufgetreten. Bitte versuchen Sie es erneut.";
		header("Location: index.php?site=error");
	}
?>