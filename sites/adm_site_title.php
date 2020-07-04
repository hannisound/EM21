<section id="adm_site_title">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 mx-auto text-center">
            <?php
            if (empty($_SESSION['status']) AND $_SESSION['status'] <= 2)
            {
                echo "<p class='error'>Sie haben keine Berechtigung diese Seite zu öffnen</p>";
            }
            else
            {
            ?>
                <form id="add_site_title" method="POST" action="">
                    <div class="form-row text-center">
                        <div class="col">
                            <input type="hidden" id="ID_edit" name="ID_site">
                            <input placeholder="Seitenname eintragen (richtige Schreibweise achten)" class="form-control" name="site" id="site" required>
                        </div>
                        <div class="col">
                            <input placeholder="Titelnamen eintragen" class="form-control" name="title" ID="title" required>
                        </div>
                        <div class="col">
                            <input placeholder="Untertitel eintragen" class="form-control" name="subtitle" id="subtitle">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-green" id="add_siteTitle" >Seite eintragen</button>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-6" id="meldung"></div>
                    </div>
                </form>
                
            <?php
                // Ab hier kann es mit dem Code für den Seitentitelseite losgehen
                // Header für Übersicht erstellen
                /* <button class='btn btn-lg btn-green' id='add_site'><i class='fas fa-plus'></i> Neue Seite hinzufügen</button>
                    <div id='addSite'></div>*/
                echo "
                    <div class='row'>
                        <div class='col-2 font-weight-bold'>
                            Seite
                        </div>
                        <div class='col-2 font-weight-bold'>
                            Titel
                        </div>
                        <div class='col-6 font-weight-bold'>
                            Untertitel
                        </div>
                        <div class='col-2 font-weight-bold'>
                            <i class='fas fa-cog'></i>
                        </div>
                    </div>";
                // Alle Seitentitle auslesen
                $sql = "SELECT ID, site, title, subtitle FROM ".prefix."_site_title";
                $result = $mysqli->query($sql);
                while($row = $result->fetch_array()) {
                    echo "
                    <div class='row'>
                        <div class='col-2'>
                            <span id='site_".$row['ID']."'>".$row['site']."</span>
                        </div>
                        <div class='col-2'>
                            <span id='title_".$row['ID']."'>".$row['title']."</span>
                        </div>
                        <div class='col-6'>
                            <span id='subtitle_".$row['ID']."'>".$row['subtitle']."</span>
                        </div>
                        <div class='col-2'>   
                            <button class='btn btn-green btn-sm' id='edit_siteTitle' name='".$row['ID']."'><i class='far fa-edit'></i></button>
                            <button class='btn btn-danger btn-sm' id='del_siteTitle' name='".$row['ID']."'><i class='far fa-trash-alt'></i></i></button>
                        </div>
                    </div>
                    <hr>";
                }
            }
            ?>
            </div>
        </div>
    </div>
</section>
