<form id="add_new_news" action="" method="post" name="add_new_news">
<legend>Neuen News Eintrag erstellen</legend>
<input type="hidden" id="user" name="user" value="<?php echo "".$_SESSION['username']."";?>">
<label>Überschrift:</label>
<input type="text" name="title" class="form-control" id="title">

<label>Beitrag:</label>
<textarea type="text" name="news" class="form-control" id="news" rows="4" cols="250"></textarea><br>
<button type="submit" class="btn btn-primary" id="add_news">Eintragen</button> <button type="reset" class="btn btn-danger">Reset</button>
</form>
