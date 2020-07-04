<section id="adm_faq">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 mx-auto">
    	<?php
    	if (empty($_SESSION['status']) AND $_SESSION['status'] <= 2)
    	{
    		echo "<p class='error'>Sie haben keine Berechtigung diese Seite zu öffnen</p>";
    	}
    	else
    	{
	    ?>
	      <div class="row">
		    	<div class="col">
		    		<?php include ("news/adm_new_news.php"); ?>
		    	</div>
		    	<div class="col">
		        <?php include ("news/adm_edit_news.html"); ?>
		       </div>
	    	</div>

			<ul id="news_sort">
	    	<?php
	  		// News aus der Datenbank auslesen
			$sql = "SELECT ID, title, news, autor_id, time FROM ".prefix."_news ORDER BY time ASC";
			$result	= $mysqli->query($sql);
			while($news = mysqli_fetch_array($result))
			{
				echo "	<li class='news_edit' id='news_".$news['ID']."'>
				<a style='position:relative; right: -91%;' id='news_edit' name='".$news['ID']."' href='#' class='btn btn-green btn-sm'><i class='fa fa-pencil'></i></a>
				<a style='position:relative; right: -91%;' id='news_delete' name='".$news['ID']."' href='#' class='btn btn-green btn-sm'><i class='fa fa-times'></i></a>
				<b style='position:relative; right: 60px;'><span id='news_title_".$news['ID']."'>".$news['title']."</span></b><br /><br />
				<span id='news_news_".$news['ID']."'>".nl2br($news['news'])."</span>
				</li>
				";
			}
		}
		?>
		</ul>
      </div>
    </div>
  </div>
</section>
