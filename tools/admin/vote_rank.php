<?php
	include("../session.php");
    require_once("database.php");
    require_once("constants.php");
    require_once("functions.php");
echo
"<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
</head>
<body>";

$mmofacts_place = htmlspecialchars($_GET['mmofacts_place'],ENT_QUOTES);
$gamesphere_place = htmlspecialchars($_GET['gamesphere_place'],ENT_QUOTES);
$gametoplist_place = htmlspecialchars($_GET['gametoplist_place'],ENT_QUOTES);


$mmofacts_votes = htmlspecialchars($_GET['mmofacts_votes'],ENT_QUOTES);
$gamesphere_votes = htmlspecialchars($_GET['gamesphere_votes'],ENT_QUOTES);
$gametoplist_votes = htmlspecialchars($_GET['gametoplist_votes'],ENT_QUOTES);


if($_GET['mmofacts_place'] || $_GET['gamesphere_place'] || $_GET['gametoplist_place']) {
	$insert = "INSERT INTO extern_voting (mmofacts_place, gamesphere_place, gametoplist_place, mmofacts_votes, gamesphere_votes, gametoplist_votes) VALUES ('$mmofacts_place', '$gamesphere_place', '$gametoplist_place', '$mmofacts_votes', '$gamesphere_votes', '$gametoplist_votes')";
	sql_query($insert) or die(mysql_error());
	echo "<br><br>Stimmen eingetragen!<br><br>";
	sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
	VALUES ('$_SESSION[supporter]', 'System', '<b>Voteränge eingetragen </b>', '".time()."')");
	
}


echo "<br>
Ein manuelles Eintragen von Platzierungen ist nur f&uuml;r mmofacts und GameSphere notwendig.<br>
<form action='vote_rank.php' method='get'>
<table border=0>
	<tr>
		<td>
			Seite
		</td>
		<td>
			Platzierung:
		</td>
		<td>
			Stimmen:
		</td>
	</tr>
	<tr>
		<td>
			mmofacts:
		</td>
		<td>
			<input name='mmofacts_place' type='text' size='4' maxlength='3'>
		</td>
		<td>
			<input name='mmofacts_votes' type='text' size='4' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			GameSphere:
		</td>
		<td>
			<input name='gamesphere_place' type='text' size='4' maxlength='3'>
		</td>
		<td>
			<input name='gamesphere_votes' type='text' size='4' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Game Toplist:
		</td>
		<td>
			<input name='gametoplist_place' type='text' size='4' maxlength='3'>
		</td>
		<td>
			<input name='gametoplist_votes' type='text' size='4' maxlength='4'>
		</td>
	</tr>
</table>
<br><br>
<input type='submit' name='submit' value='Ranking aktualisieren'>
</form>
</body>
</html>";
?>