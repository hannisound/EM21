<section id="adm_final">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10">
		<?php
			if ($_SESSION['status'] == 3) {
				$sql = "SELECT ID, name FROM ".prefix."_grp LIMIT 6,5";
				$result = $mysqli->query($sql);
				while ($grp = $result->fetch_array()) {
					$sql2 = "SELECT g.ID, t.name AS geg_1, t2.name AS geg_2, `time` FROM ".prefix."_games g
									INNER JOIN ".prefix."_team t ON (g.geg_1 = t.id)
									INNER JOIN ".prefix."_team t2 ON (g.geg_2 = t2.id)
									WHERE g.grp = '".$grp['ID']."' ORDER BY `time`";
					echo '<h2>'.$grp['name'].'</h2>';
					$result2 = $mysqli->query($sql2);
					while ($games = $result2->fetch_array()) {
						$sql3 = "SELECT ID, name FROM ".prefix."_team ORDER BY ID asc";
						$result3 = $mysqli->query($sql3);

						$gametime = date('d.m.Y H:i', $games['time']);
						echo ''.$gametime.' Uhr <br>';
						//echo '<form class="form-inline" role="form" style="width:300px;">';
						echo '<div class="form-row">';
						echo '<div class="col">';
						echo '<select id="geg_1" data-gameID="'.$games['ID'].'" class="form-control">';
						while ($team = $result3->fetch_array()) {
							if ($team['name'] == $games['geg_1']) {
								$select = "selected";
							}
							else {
								$select = "";
							}
							echo '<option '.$select.' value="'.$team['ID'].'">'.$team['name'].'</option>';
						}
						echo '</select>';
						echo '</div>';

						echo '<div class="col-2 text-center">';
						echo ' : ';
						echo '</div>';

						$sql4 = "SELECT ID, name FROM ".prefix."_team ORDER BY ID asc";
						$result4 = $mysqli->query($sql4);
						echo '<div class="col">';
						echo '<select id="geg_2" data-gameID="'.$games['ID'].'" class="form-control">';
						while ($team2 = $result4->fetch_array()) {
							if ($team2['name'] == $games['geg_2']) {
								$select = "selected";
							}
							else {
								$select = "";
							}
							echo '<option '.$select.' value="'.$team2['ID'].'">'.$team2['name'].'</option>';
						}
						echo '</select>';
						echo '</div>';
						echo '</div>';
						echo '<div id="meldung_'.$games['ID'].'"></div>';
						//echo '</form>';
					}
				}
			}
			else {
				echo '<div class="alert alert-danger">Kein korrekter Status</div>';
			}
		?>
			</div>
		</div>
	</div>
</section>
