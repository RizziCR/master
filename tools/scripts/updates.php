<?php
include_once("../session.php");
echo 	"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
		<html>
		<head>
		<title>Benutzer bestrafen</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
		</head>
		<body>
	<h1>Flugzeuge reparieren</h1>
	W&auml;hle eine Stadt, f&uuml;r die eine Aktion ausgef&uuml;hrt werden soll.<br/>
    <form action=\"{$_SERVER[PHP_SELF]}\" method=post>
	<label id=\"KoordinatenText\" for=\"Koordinaten\">Koordinaten:</label>
    <input type=\"text\" class=\"textinput\" size=\"8\" maxlength=\"9\"
    name=\"Koordinaten\" value=\"".($_POST[Koordinaten]?$_POST[Koordinaten]:"")."\">

    <br>
    Statt einer Stadtkoordinate kannst du auch 'ALLE' eingeben, um die Aktion f&uuml;r ALLE St&auml;dte auf Erde II auszuf&uuml;hren. Vorsicht damit!
    <br>
    <br>
    <h2>Flugzeuge durchz&auml;hlen</h2>
    Flugzeuge in der angegebenen Stadt neu durchz&auml;hlen lassen (gegen negative Flottengr&ouml;sse,
	&uuml;berf&uuml;llten Hangar, o.&auml;.)
    <br>
    <input type=submit value=\"durchz&auml;hlen\" name=send>
    <br>
    <br>
    <h2>Handelszentrums-Flugzeuge abschiessen</h2>
    Flugzeuge der angegebenen Stadt von und zum Handelszentrum-Zentrallager abschiessen, falls sie noch
l&auml;nger als 72 Stunden unterwegs sind (Flugzeuge werden danach auch neu durchgez&auml;hlt)
    <br>
    <input type=submit value=\"abschiessen\" name=send>
    <br>
    <br>";
    /*<h2>Terror-Flugzeuge umkehren lassen</h2>
    (nicht mehr n&ouml;tig - wird jetzt st&uuml;ndlich automatisch erledigt) angreifende Flugzeuge der angegebenen Stadt umkehren lassen ohne Angriff, falls sie noch l&auml;nger als
1 Tag zum Ziel unterwegs sind
    <br>
    <input type=submit value=\"umkehren lassen\" name=send>
    <br>";*/
    echo "<h2>Alle Terror-Angriffe auflisten</h2>
    Angreifende Flugzeuge werden automatisch zur Umkehr gezwungen, falls sie l&auml;nger als
1 Tag zum Ziel unterwegs w&auml;ren. Die Liste aller solcher abgebrochenen Angriffe anzeigen
    <br>
    <input type=submit value=\"Umkehrer anzeigen\" name=send>
    <br>
    </form>
    <hr>";

function printAttackFleet($fleet) {
	$start = strftime('%d.%m.%y %H:%M', $fleet['f_start']);
	$ankunft = strftime('%d.%m.%y %H:%M', $fleet['f_arrival']);
	echo "<tr><td>".$fleet['user']."</td><td>".$fleet['f_target_user']."</td><td>$start</td><td>$ankunft</td><td>".$fleet['city']."</td><td>".$fleet['f_target']."</td><td>".$fleet['f_name']."</td></tr>";
}

if ($_POST[send])
{
    require_once("database.php");
    if ($_POST[send]=='Umkehrer anzeigen') {
	?>

	<table border="1">
	    <tr>
		<th align='left' width='100'>Angreifer</th>
		<th align='left' width='100'>Opfer</th>
		<th align='left' width='100'>Start</th>
		<th align='left' width='100'>Ankunft</th>
		<th align='left' width='100'>Von</th>
		<th align='left' width='100'>Nach</th>
		<th align='left'>Text</th>
	    </tr>
	    <?php
	    $fleets = sql_query('SELECT * FROM long_term_flights');
	    while($fleet_action = sql_fetch_assoc($fleets)) {
		printAttackFleet($fleet_action);
	    }
	?>
	</table>

    <?php
    } else {

	$selected = "";
	$not_empty_selected = " WHERE city!=''";
	$the_city ="";
	$valid = true;
	if (strcasecmp($_POST[Koordinaten], 'Alle') != 0) {
	    $selected = " WHERE city='".addslashes($_POST[Koordinaten])."'";
	    $not_empty_selected = $selected;
	    $cities_get = sql_query("SELECT ID,count(*) from city" . $selected);
	    list( $cities ) = sql_fetch_row($cities_get);
		$the_city = "city='$cities[0]' && ";
	    if ($cities != 1) {
		echo "Stadt nicht gefunden!<br>";
		$valid = false;
	    }
	}
    }
}

if ($valid && $_POST[send]=='abschiessen') {
	$res = sql_query("delete from actions where " . $the_city . "(f_arrival - UNIX_TIMESTAMP() > 24*60*60) && (f_action LIKE 'plane_%')");

	echo "Flotten abgeschossen: " . sql_affected_rows() ."<br>";
}

if ($valid && ($_POST[send]=='durchz&auml;hlen' || $_POST[send]=='durchzählen' || $_POST[send]=='abschiessen')) {
    // set sum of every plane type to number currently in hangar
    sql_query("UPDATE city SET
        p_sparrow_gesamt=p_sparrow,
        p_blackbird_gesamt=p_blackbird,
        p_raven_gesamt=p_raven,
        p_eagle_gesamt=p_eagle,
        p_falcon_gesamt=p_falcon,
        p_nightingale_gesamt=p_nightingale,
        p_settler_gesamt=p_settler,
        p_scarecrow_gesamt=p_scarecrow,
        p_ravager_gesamt=p_ravager,
        p_destroyer_gesamt=p_destroyer,
        p_bomber_gesamt=p_bomber,
        p_espionage_probe_gesamt=p_espionage_probe,
        p_small_transporter_gesamt=p_small_transporter,
        p_medium_transporter_gesamt=p_medium_transporter,
        p_big_transporter_gesamt=p_big_transporter" . $selected);
    echo "Flugzeuge im Hangar gesetzt f&uuml;r: " . sql_affected_rows() ."<br>";
	// Eintrag in Support-Log
	if($_POST[send]=='durchz&auml;hlen' || $_POST[send]=='durchzählen') $support_action = "<b>Flugzeuge </b>durchz&auml;hlen: ".$_POST[Koordinaten];
	if($_POST[send]=='abschiessen') $support_action = "<b>HZ-Flugzeuge </b>abschiessen: ".$_POST[Koordinaten];
	sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
			VALUES ('$_SESSION[supporter]', 'System', '$support_action', '".time()."')");
	// Ende Eintrag
    $p_db_name = array();
    $p_db_name[] = "p_sparrow";
    $p_db_name[] = "p_blackbird";
    $p_db_name[] = "p_raven";
    $p_db_name[] = "p_eagle";
    $p_db_name[] = "p_falcon";
    $p_db_name[] = "p_nightingale";
    $p_db_name[] = "p_settler";
    $p_db_name[] = "p_scarecrow";
    $p_db_name[] = "p_ravager";
    $p_db_name[] = "p_destroyer";
    $p_db_name[] = "p_espionage_probe";
    $p_db_name[] = "p_small_transporter";
    $p_db_name[] = "p_medium_transporter";
    $p_db_name[] = "p_big_transporter";
    $p_db_name[] = "p_bomber";

    $f_db_name = array();
    $f_db_name[] = "f_sparrow";
    $f_db_name[] = "f_blackbird";
    $f_db_name[] = "f_raven";
    $f_db_name[] = "f_eagle";
    $f_db_name[] = "f_falcon";
    $f_db_name[] = "f_nightingale";
    $f_db_name[] = "f_settler";
    $f_db_name[] = "f_scarecrow";
    $f_db_name[] = "f_ravager";
    $f_db_name[] = "f_destroyer";
    $f_db_name[] = "f_espionage_probe";
    $f_db_name[] = "f_small_transporter";
    $f_db_name[] = "f_medium_transporter";
    $f_db_name[] = "f_big_transporter";
    $f_db_name[] = "f_bomber";

    // count only back flights of trading planes or resources, and of transportations;
    // in addition count planes given away
    for ($i=0;$i<count($p_db_name);$i++)
    {
        $res = sql_query("SELECT city,$f_db_name[$i] AS anzahl FROM actions" . $not_empty_selected . " && $f_db_name[$i] > 0 && ((f_action LIKE '%_back' || f_action LIKE '%_from_depot' || f_action LIKE 'plane_%') || (f_give='YES'))");
        while ($lala = sql_fetch_array($res))
            sql_query("UPDATE city SET $p_db_name[$i]_gesamt=$p_db_name[$i]_gesamt+$lala[anzahl] WHERE city='$lala[city]'");
    }

    echo "Flotten unterwegs: " . sql_affected_rows() ."<br>";

    // add planes under production
    $res = sql_query("SELECT city,current_build,Count(*) AS anzahl FROM jobs_planes" . $selected . " GROUP BY city,current_build");
    while ($lala = sql_fetch_array($res))
        sql_query("UPDATE city SET $lala[current_build]_gesamt=$lala[current_build]_gesamt+$lala[anzahl] WHERE city='$lala[city]'");

    echo "Bauauftr&auml;ge: " . sql_affected_rows() ."<br>";

    // sum up all plane types of the city
    sql_query("UPDATE city SET p_gesamt_flugzeuge=p_sparrow_gesamt+p_blackbird_gesamt+p_raven_gesamt+p_eagle_gesamt+p_falcon_gesamt+p_nightingale_gesamt+p_settler_gesamt+p_scarecrow_gesamt+p_ravager_gesamt+p_destroyer_gesamt+p_espionage_probe_gesamt+p_small_transporter_gesamt+p_medium_transporter_gesamt+p_big_transporter_gesamt+p_bomber_gesamt" . $selected);

//    echo "\n" . sql_affected_rows() ."\n";
    echo "Flieger gez&auml;hlt f&uuml;r: " . sql_affected_rows() ."<br>";
}

if ($valid && $_POST[send]=='umkehren lassen') {
	$res = sql_query("delete from actions where " . $the_city . "(f_arrival - UNIX_TIMESTAMP() > 7*24*60*60) && (f_action='attack')");

	echo "Flotten abdrehen lassen: " . sql_affected_rows() ."<br>";
	// Eintrag in Support-Log
	sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
			VALUES ('$_SESSION[supporter]', 'System', '<b>Flugzeuge </b>umkehren lassen: ".addslashes($_POST[Koordinaten])." (".sql_affected_rows().")', '".time()."')");
	// Ende Eintrag
}
echo "</body>
</html>";
?>
