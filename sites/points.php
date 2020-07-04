<section id="points">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-12 mx-auto">
				<div id="meldung"></div>
				<?php
					// Aktiven Tab bestimmen
					// Wenn RemoveMember gesetzt ist, muss eine Gruppe aktive sein, also  Gesamtübersicht deaktivieren
					if (isset($_GET['tab'])) {
						$Gesamtshow = "";
						$GesamtActive = "";
					} 
					else {
						$Gesamtshow = "show";
						$GesamtActive = "active";
					}
				?>
				<!-- Tabs erstellen -->
						<nav>
						  <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
						    <a class="nav-item nav-link <?php echo ".$GesamtActive."; ?>" id="nav-TotalPoints-tab" data-toggle="tab" href="#nav-TotalPoints" role="tab" aria-controls="nav-TotalPoints" aria-selected="true">Gesamtpunkte</a>
						    <a class="nav-item nav-link" id="nav-GrpPoints-tab" data-toggle="tab" href="#nav-GrpPoints" role="tab" aria-controls="nav-GrpPoints" aria-selected="false">Gruppenübersicht</a>
								<?php
								$sql = "SELECT userGrpID FROM ".prefix."_userGrpMember WHERE userID = '".$_SESSION['user_id']."'";
								$result = $mysqli->query($sql);
								if (mysqli_num_rows($result) === 0) {
								}
								else {
									while ($userGrpID = mysqli_fetch_array($result)) {
										$sql2    = "SELECT ID, name, shortcut FROM ".prefix."_userGrp WHERE ID = '".$userGrpID['userGrpID']."'";
										$result2 = $mysqli->query($sql2);
										while ($tab = mysqli_fetch_array($result2)) {
											if (isset($_GET['tab'])) {
												if ($_GET['tab'] == $tab['ID']) {
													$active = "active";
												}
												else {
													$active = "";
												}
											}
											else {
												$active = "";
											}
											echo "<a class='nav-item nav-link ".$active."' id='nav-".$tab['ID']."-tab' data-toggle='tab' href='#nav-".$tab['ID']."' role='tab' aria-controls='nav-".$tab['ID']."' aria-selected='false'>". htmlentities(html_entity_decode($tab['shortcut']))."</a>";
										}
									}
								}
							?>
							<a class="nav-item nav-link" href="" data-remote="sites/modal/modal_newGrp.php?userID=<?php echo $_SESSION['user_id'];?>" data-toggle="modal" data-target="#newGrpModal" aria-controls="nav-<?php echo $_SESSION['user_id'];?>" aria-selected="false" ><i class="fa fa-plus"></i> Eigene Gruppe erstellen</a>
						  </div>
						</nav>
						<!-- Content der Tabs laden -->
						<div class="tab-content" id="nav-tabContent">
						  	<div class="tab-pane fade <?php echo $Gesamtshow, " ",$GesamtActive; ?>" id="nav-TotalPoints" role="tabpanel" aria-labelledby="nav-TotalPoints-tab"><?php require 'totalPoints.php';?></div>
						  	<div class="tab-pane fade" id="nav-GrpPoints" role="tabpanel" aria-labelledby="nav-GrpPoints-tab"><?php require 'grpPoints.php';?></div>
							<?php
								$sql = "SELECT userGrpID FROM ".prefix."_userGrpMember WHERE userID = '".$_SESSION['user_id']."'";
								$result = $mysqli->query($sql);
								if (mysqli_num_rows($result) === 0) {
								}
								else {
									while ($userGrpID = mysqli_fetch_array($result)) {
									$sql2    = "SELECT ID, name, shortcut, adminID FROM ".prefix."_userGrp WHERE ID = '".$userGrpID['userGrpID']."'";
									$result2 = $mysqli->query($sql2);
									while ($tab = mysqli_fetch_array($result2)) {
										if (isset($_GET['tab'])) {
											if ($_GET['tab'] == $tab['ID']) {
												$show = "show";
												$active = "active";
											}
											else {
												$show = "";
												$active = "";
											}
										}
										else {
											$show = "";
											$active = "";
										}
										echo "<div role='tabpanel' class='tab-pane fade ".$show." ".$active."' role='tabpanel' aria-labelledby='nav-GrpPoints-tab' id='nav-".$tab['ID']."'>";
										include("userGrpPoints.php");
										echo "</div>";
									}
									}
								}
							?>
						</div>

						<!-- Modal -->
		        <div class="modal fade" id="newGrpModal" tabindex="-1" role="dialog" aria-labelledby="newwGrpModal" aria-hidden="true">
		          <div class="modal-dialog" role="document">
		            <div class="modal-content">
		            </div>
		          </div>
		        </div>
					<!-- /.modal -->

					<!-- Modal -->
          <div class="modal fade" id="meinModal2" tabindex="-1" role="dialog" aria-labelledby="meinModalLabel" aria-hidden="true">
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
