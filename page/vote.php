<?php
    require_once("database.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");
    //$template = new PHPTAL('guest/login.html');
    $template = new PHPTAL('standard.html');
    $template->setTranslator(new PHPTAL_EtsTranslator());
    $template->setEncoding('ISO-8859-1');

    // set page title
    $template->set('pageTitle', 'Stimme für ETS');
    
    // Initialisierung der Ausgabe
    $pfuschOutput = "";
    
    $x=0;
    $select = sql_query("SELECT * FROM extern_voting ORDER BY `ID` DESC LIMIT 0 , 2;");
    while($row = sql_fetch_array($select)) {
    	$mmofacts_place[$x] = $row['mmofacts_place'];
    	$gamesphere_place[$x] = $row['gamesphere_place'];
    	$gametoplist_place[$x] = $row['gametoplist_place'];
    	$mmofacts_votes[$x] = $row['mmofacts_votes'];
    	$gamesphere_votes[$x] = $row['gamesphere_votes'];
    	$gametoplist_votes[$x] = $row['gametoplist_votes'];
    	$x++;
    }
    
    $mmofacts_differenz2 = $mmofacts_place[0] - $mmofacts_place[1];
    $gamesphere_differenz2 = $gamesphere_place[0] - $gamesphere_place[1];
    $gametoplist_differenz2 = $gametoplist_place[0] - $gametoplist_place[1];
    
    if($mmofacts_differenz2 < 0) $mmofacts_differenz = "<font color='green'>$mmofacts_differenz2</font>";
    if($mmofacts_differenz2 == 0) $mmofacts_differenz = "+/- $mmofacts_differenz2";
    if($mmofacts_differenz2 > 0) $mmofacts_differenz = "<font color='red'>+ $mmofacts_differenz2</font>";
    
    if($gamesphere_differenz2 < 0) $gamesphere_differenz = "<font color='green'>$gamesphere_differenz2</font>";
    if($gamesphere_differenz2 == 0) $gamesphere_differenz = "+/- $gamesphere_differenz2";
    if($gamesphere_differenz2 > 0) $gamesphere_differenz = "<font color='red'>+ $gamesphere_differenz2</font>";
    
    if($gametoplist_differenz2 < 0) $gametoplist_differenz = "<font color='green'>$gametoplist_differenz2</font>";
    if($gametoplist_differenz2 == 0) $gametoplist_differenz = "+/- $gametoplist_differenz2";
    if($gametoplist_differenz2 > 0) $gametoplist_differenz = "<font color='red'>+ $gametoplist_differenz2</font>";
    
    

    $pfuschOutput .= "<h1>Deine Stimme für dein Spiel</h1>
					    <div id=\"blockVote\" id=\"voteButtons\" tal:condition=\"exists: enable_voteButtons\">
					    	<table>
					            <tr>
					                <th>Position</th>
					                <th>Veränderung</th>
					                <th>Stimmen</td>
					                <th>Abstimmen</th>
					                <th>Portal</th>
					            </tr>
					            <tr>
					            	<td>$mmofacts_place[0]</td>
					            	<td>$mmofacts_differenz</td>
					            	<td>$mmofacts_votes[0]</td>
					            	<td>
					            		<a href='http://de.mmofacts.com/escape-to-space-19#track' target='_blank'>
					            			<img src='$etsAddress/pics/vote_mmofacts.png'>
					            		</a>
					            	</td>
					            	<td>MMOFacts</td>
					            </tr>
					            <tr>
					            	<td>$gamesphere_place[0]</td>
					            	<td>$gamesphere_differenz</td>
					            	<td>$gamesphere_votes[0]</td>
					            	<td>
					            		<a href='http://www.gamessphere.de/vote/vote_609.html' target='_blank'>
					            			<img src='$etsAddress/pics/vote_gamessphere.gif'>
					            		</a>
					            	</td>
					            	<td>Game Sphere</td>
					            </tr>
					            <tr>
					            	<td>$gametoplist_place[0]</td>
					            	<td>$gametoplist_differenz</td>
					            	<td>$gametoplist_votes[0]</td>
					            	<td>
					            		<a href='http://game-toplist.de/vote/ETSGame.php' target='_blank'>
					            			<img src='$etsAddress/pics/vote_gametoplist.png'>
					            		</a>
					            	</td>
					            	<td>Game Toplist</td>
					            </tr>
					    	</table>
					    </div>";
    

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");
    
  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
