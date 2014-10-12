<?php
include_once("../session.php");
    require_once("database.php");
    require_once("functions.php");
    require_once("constants.php");
	echo 	"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
			<html>
			<head>
			<title>Flotten eines Users</title>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
			</head>
			<body>
			<h2>Rundenstatistik</h2>";

    for ($i=0;$i<ANZAHL_GEBAEUDE;$i++)
    {
        echo "<b>$b_name[$i]:</b><br>";

        $get_res = sql_query("SELECT b_$b_db_name[$i] AS $b_db_name[$i] FROM city ORDER BY b_$b_db_name[$i] DESC LIMIT 10");
        while ($res = sql_fetch_array($get_res))
            $daten[0][$i][] = $res[$b_db_name[$i]];

        echo implode(" - ",$daten[0][$i]) ."<br><br>";
    }

    echo "<br><br>";

    for ($i=0;$i<ANZAHL_TECHNOLOGIEN;$i++)
    {
        echo "<b>$t_name[$i]:</b><br>";

        $get_res = sql_query("SELECT t_$t_db_name[$i] AS $t_db_name[$i] FROM usarios ORDER BY t_$t_db_name[$i] DESC LIMIT 10");
        while ($res = sql_fetch_array($get_res))
            $daten[1][$i][] = $res[$t_db_name[$i]];

        echo implode(" - ",$daten[1][$i]) ."<br><br>";
    }

    echo "<br><br>";

    echo "<b>Größte Flotte:</b><br>";

    $get_res = sql_query("SELECT p_gesamt_flugzeuge FROM city ORDER BY p_gesamt_flugzeuge DESC LIMIT 10");
    while ($res = sql_fetch_array($get_res))
        $daten[2][$i][] = $res[p_gesamt_flugzeuge];

    echo implode(" - ",$daten[2][$i]) ."<br><br>";

    echo "<br><br>";

    echo "<b>Größte Defensive:</b><br>";

    $get_res = sql_query("SELECT d_electronwoofer+d_protonwoofer+d_neutronwoofer+d_electronsequenzer+d_protonsequenzer+d_neutronsequenzer as def FROM city ORDER BY def DESC LIMIT 10");
    while ($res = sql_fetch_array($get_res))
        $daten[3][$i][] = $res[def];

    echo implode(" - ",$daten[3][$i]) ."<br><br>";


    echo "<br><br>";

    echo "<b>Gesamtzahl:</b><br>";

    $get_res = sql_query("SELECT Sum( p_gesamt_flugzeuge ) AS Flugzeuge, SUM(d_electronwoofer+d_protonwoofer+d_neutronwoofer+d_electronsequenzer+d_protonsequenzer+d_neutronsequenzer) AS gesamt_deff FROM city ");
    $res = sql_fetch_array($get_res);

    echo "Flg: ". $res[Flugzeuge] . " Deff: ". $res[gesamt_deff];



    echo "<br><br>";

    echo "	<b>Toplisten:</b><br>
            <table width=750 border=0 cellpadding=0 cellspacing=0>";

    echo "	<tr>
                <td colspan=4 class=table_head align=center>
                    Top 50 St&auml;dte
                </td>
            </tr>
            <tr align=center>
                <td width=30>
                    <b>Platz</b>
                </td>
                <td>
                    <b>Stadt</b>
                </td>
                <td>
                    <b>User (Allianz)</b>
                </td>
                <td>
                    <b>Punkte</b>
                </td>
            </tr>";

    $show = 0;

    $get_cities = sql_query("SELECT city,city_name,user,points,alliance FROM city ORDER BY points DESC LIMIT 50");
    while ($top = sql_fetch_array($get_cities))
    {
        $show++;

        echo "	<tr align=center>
                    <td align=right width=30>
                        $show
                    </td>
                    <td>
                        $top[city_name]
                    </td>
                    <td>
                        $top[user] ($top[alliance])
                    </td>
                    <td>
                        $top[points]
                    </td>
                </tr>";
    }


    echo "	<tr>
                <td colspan=4 class=table_head align=center>
                    Top 50 User
                </td>
            </tr>
            <tr align=center>
                <td width=30>
                    <b>Platz</b>
                </td>
                <td colspan=2>
                    <b>User (Allianz)</b>
                </td>
                <td>
                    <b>Punkte</b>
                </td>
            </tr>";

    $show = 0;

    $get_cities = sql_query("SELECT user,points,alliance FROM usarios ORDER BY points DESC LIMIT 50");
    while ($top = sql_fetch_array($get_cities))
    {
        $show++;

        echo "	<tr align=center>
                    <td align=right width=30>
                        $show
                    </td>
                    <td colspan=2>
                        $top[user] ($top[alliance])
                    </td>
                    <td>
                        $top[points]
                    </td>
                </tr>";
    }

    echo "	<tr>
                <td colspan=4 class=table_head align=center>
                    Top 50 Allianzen
                </td>
            </tr>
            <tr align=center valign=top>
                <td width=30>
                    <b>Platz</b>
                </td>
                <td>
                    <b>Allianz</b>
                </td>
                <td>
                    <b>Mitglieder</b>
                </td>
                <td>
                    <b>Punkte (Durchschnitt je Mitglied)</b>
                </td>
            </tr>";

    $show = 0;

    $get_alliances = sql_query("SELECT tag,members,points FROM alliances ORDER BY points DESC LIMIT 50");
    while ($top = sql_fetch_array($get_alliances))
    {
        $show++;

        echo "	<tr align=center>
                    <td align=right width=30>
                        $show
                    </td>
                    <td>
                        $top[tag]
                    </td>
                    <td>
                        $top[members]
                    </td>
                    <td>
                        $top[points] (". round($top[points]/$top[members]) .")
                    </td>
                </tr>";
    }

    echo "	</table>
	</body>
</html>";
?>