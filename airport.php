<?php
  $use_lib = 1; // MSG_AIRPORT

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");
  include("tutorial.php");


  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('airport.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Stadt - Flughafen');

  $pfuschOutput = "";


 // insert specific page logic here


  $get_buildings = sql_query("SELECT b_airport,b_communication_center FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
  $buildings = sql_fetch_array($get_buildings);

  /*if ($buildings[b_airport] <= 0)
  {
    $errorMessage .= "  <h1>{$MESSAGES[MSG_AIRPORT][m000]}</h1>";
    $errorMessage .= ErrorMessage(MSG_AIRPORT,e000);
    // Sie m&uuml;ssen erst einen Flughafen bauen, um diese Funktion nutzen zu k&ouml;nnen<br>";

    $errorMessage .= ErrorMessage();

    // add error output
  $template->set('errorMessage', $errorMessage);

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
    die();
  }*/

  $get_planes = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) .", city FROM city WHERE ID='$_SESSION[city]'");
  $p_count = sql_fetch_array($get_planes);

  $get_techs = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs = sql_fetch_array($get_techs);


  $position = split(":",$p_count['city']);

  $my_cities_select = '<option value="0:99:99">An eigene Stadt:</option>';
  $my_cities = sql_query('SELECT city, city_name FROM city WHERE user=\''.$_SESSION[user].'\' AND ID!="'.$_SESSION[city].'" ORDER BY pos ASC');
  while($my_city = sql_fetch_assoc($my_cities)) {
    $my_cities_select .= '<option value="'.$my_city[city].'">'.$my_city[city_name].' ('.$my_city[city].')</option>';
  }

  $pfuschOutput .= "  <script language=javascript>
      function calculate()
      {
        if (document.formular.continent.value >= 1 && document.formular.country.value >= 1 && document.formular.aimed_city.value >= 1 && Math.round(document.formular.continent.value) == document.formular.continent.value && Math.round(document.formular.country.value) == document.formular.country.value && Math.round(document.formular.aimed_city.value) == document.formular.aimed_city.value)
        {
          if (document.formular.continent.value != $position[0])
          {
".
//            ndist = Math.abs(parseInt(document.formular.continent.value) - $position[0]);
//            ndist = Math.min(ndist, ".MAX_CONTINENT." - ndist);
//            dist = Math.round(10000 * ndist);
"              dist = ".CONTINENT_DISTANCE.";
          }
          else
            if (parseInt(document.formular.country.value) != $position[1])
            {
              if (parseInt(document.formular.country.value) > $position[1])
                ndist = (".MAX_COUNTRY." - parseInt(document.formular.country.value)) + $position[1];
              else
                ndist = (".MAX_COUNTRY." - $position[1]) + parseInt(document.formular.country.value);

              if (Math.abs(document.formular.country.value - $position[1]) < ndist)
                dist = ".COUNTRY_BASE_DISTANCE." + Math.abs(parseInt(document.formular.country.value) - $position[1])*".COUNTRY_DISTANCE.";
              else
                dist = ".COUNTRY_BASE_DISTANCE." + Math.abs(ndist)*".COUNTRY_DISTANCE.";
            }
            else
              if (document.formular.aimed_city.value != $position[2])
                dist = ".CITY_BASE_DISTANCE." + Math.abs(document.formular.aimed_city.value - $position[2])*".CITY_DISTANCE.";
              else
                dist = \"\";

          document.formular.distance.value = dist;
        }

        p_speed = new Array();
        p_tech = new Array();
        t_increase = new Array();
        user_techs = new Array();
        p_consumption = new Array();
        p_capacity = new Array();";


  for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
    $pfuschOutput .= "  p_speed[$i] = $p_speed[$i];
        p_tech[$i] = {$p_tech[$i][T_SPEED]};
        p_consumption[$i] = $p_consumption[$i];
        p_capacity[$i] = $p_capacity[$i];\n";

  $pfuschOutput .= "t_increase[-1] = ". $t_increase[NOTECH] .";";

  for ($i=0;$i<ANZAHL_TECHNOLOGIEN;$i++)
    $pfuschOutput .= "  t_increase[$i] = $t_increase[$i];\n";

  $pfuschOutput .= "    user_techs[". O_DRIVE ."] = {$user_techs[O_DRIVE]};
        user_techs[". H_DRIVE ."] = {$user_techs[H_DRIVE]};
        user_techs[". A_DRIVE ."] = {$user_techs[A_DRIVE]};
        user_techs[". CONSUMPTION ."] = {$user_techs[CONSUMPTION]};
        user_techs[". COMP_MANAGEMENT ."] = {$user_techs[COMP_MANAGEMENT]};
        user_techs[". PLANE_SIZE ."] = {$user_techs[PLANE_SIZE]};
        user_techs[". NOTECH ."] = 0;

        var speed = 0;
        var consumption = 0;
        var capacity = 0;
        var flugzeuge_anzahl = 0;
        var speed_start_flag = true;
        var curr_speed = 0;
        var fleetkapa = {$buildings[b_airport]}*5 + {$user_techs[COMP_MANAGEMENT]}*3;

        for (i=0;i<". ANZAHL_FLUGZEUGE .";i++)
        {
          document.formular.elements[\"p_fleet[\"+i+\"]\"].value = Math.round(document.formular.elements[\"p_fleet[\"+i+\"]\"].value);

          if (document.formular.elements[\"p_fleet[\"+i+\"]\"].value > 0)
          {
            curr_speed = p_speed[i] + t_increase[p_tech[i]] * user_techs[p_tech[i]];

            if ((curr_speed < speed) || speed_start_flag)
            {
              speed_start_flag = false;

              speed = curr_speed;
            }

            consumption += Math.round(dist / 1000 * p_consumption[i] * Math.pow({$t_increase[CONSUMPTION]},{$user_techs[CONSUMPTION]}) * document.formular.elements[\"p_fleet[\"+i+\"]\"].value);
            capacity += Math.floor(document.formular.elements[\"p_fleet[\"+i+\"]\"].value * p_capacity[i] * Math.pow({$t_increase[PLANE_SIZE]},{$user_techs[PLANE_SIZE]}));
//            flugzeuge_anzahl += document.formular.elements[\"p_fleet[\"+i+\"]\"].value;
            fleetkapa = fleetkapa - document.formular.elements[\"p_fleet[\"+i+\"]\"].value;
          }
        }

        capacity -= document.formular.transport_iridium.value*1 + document.formular.transport_holzium.value*1 + document.formular.transport_water.value*1 + document.formular.transport_oxygen.value*1;

        document.formular.speed.value = speed;
		document.formular.fleetkapa.value = fleetkapa;

        if (document.formular.give.checked)
          document.formular.consumption.value = Math.ceil(consumption / 2);
        else
          document.formular.consumption.value = Math.ceil(consumption);

        document.formular.capacity.value = Math.floor(capacity);


        if (document.formular.distance.value > 0 && document.formular.speed.value > 0)
        {
          h = document.formular.distance.value / document.formular.speed.value;
          hours = Math.floor(h);
          m = (h - hours) * 60;
          minutes = Math.floor(m);
          s = (m - minutes) * 60;
          seconds = Math.floor(s);

          if (minutes < 10)
            minutes = '0' + minutes;
          if (seconds < 10)
            seconds = '0' + seconds;

          document.formular.flytime.value = hours + ':' + minutes + ':' + seconds;
        }
        else
        {
          document.formular.flytime.value = '';
        }
        $('#war_warning').load('/war_query.php?city='+document.formular.continent.value+':'+document.formular.country.value+':'+document.formular.aimed_city.value);
      }
      function checkSubmit() {
        return true;

        var attack = false;
        for(i=0;i<document.formular.what.length;i++) {
            if(document.formular.what[i].checked==true) {
                attack = true;
            }
        }

        if(attack) {
            if(
                document.formular.elements[\"p_fleet[\"+".SMALL_TRANSPORTER."+\"]\"].value > 0 ||
                document.formular.elements[\"p_fleet[\"+".MEDIUM_TRANSPORTER."+\"]\"].value > 0 ||
                document.formular.elements[\"p_fleet[\"+".BIG_TRANSPORTER."+\"]\"].value > 0) {
                    alert('".$MESSAGES[MSG_AIRPORT][e020]."');
                    return false;
                }
        }
        return true;
      }
      function setcity(select) {
          tmp = select.value.split(':');
        document.formular.continent.value = tmp[0];
        document.formular.country.value = tmp[1];
        document.formular.aimed_city.value = tmp[2];
        calculate();
      }
      
      function doIri() {
		
		var gotIri = document.getElementById('count_iridium');
		var a=gotIri.innerHTML;
		var a = a.replace(/\./g, '');
		a= parseInt(a);
		var restkapa = parseInt(document.formular.transport_iridium.value) || 0;
		restkapa += parseInt(document.formular.capacity.value) || 0;
		
		if(a < restkapa) {
			document.formular.transport_iridium.value = a;
		}else {
			document.formular.transport_iridium.value = restkapa;
		}
		document.formular.what[1].checked=true;
		calculate();
	}
	
	function doHolzi() {
		var gotHolz = document.getElementById('count_holzium');
		var a=gotHolz.innerHTML;
		var a = a.replace(/\./g, '');
		a= parseInt(a);
		var restkapa = parseInt(document.formular.transport_holzium.value) || 0; 
		restkapa += parseInt(document.formular.capacity.value) || 0;
		
		if(a < restkapa) {
			document.formular.transport_holzium.value = a;
		}else {
			document.formular.transport_holzium.value = restkapa;
		}
		document.formular.what[1].checked=true;
		calculate();
	}
	
	function doWat() {
		var gotWat = document.getElementById('count_water');
		var a=gotWat.innerHTML;
		var a = a.replace(/\./g, '');
		a= parseInt(a);
		var restkapa = parseInt(document.formular.transport_water.value) || 0; 
		restkapa += parseInt(document.formular.capacity.value) || 0;
		
		if(a < restkapa) {
			document.formular.transport_water.value = a;
		}else {
			document.formular.transport_water.value = restkapa;
		}
		document.formular.what[1].checked=true;
		calculate();
	}
	
	function doOxi() {
		var gotOxi = document.getElementById('count_oxygen');
		var a=gotOxi.innerHTML;
		var a = a.replace(/\./g, '');
		a= parseInt(a);
		a-= document.formular.consumption.value;
		a-= 2;
		
		
		var restkapa = parseInt(document.formular.transport_oxygen.value) || 0;
		restkapa += parseInt(document.formular.capacity.value) || 0;
	
		
		if(a<0) {
			alert('" . $MESSAGES[MSG_AIRPORT][e012] . "');
			return;
		}
		if(a < restkapa) {
			document.formular.transport_oxygen.value = a;
		}else {
			document.formular.transport_oxygen.value = restkapa;
		}
		document.formular.what[1].checked=true;
		calculate();
	}
	
	function fillFleet(aircraft_id) {
		var remaining_fleetkapa = parseInt(document.formular.fleetkapa.value);
		remaining_fleetkapa += parseInt(document.formular.elements[\"p_fleet[\"+aircraft_id+\"]\"].value) || 0;
		var aircrafts = parseInt(document.formular.elements[\"aircraft[\"+aircraft_id+\"]\"].value) || 0;
		
		if (remaining_fleetkapa < aircrafts) {
			document.formular.elements[\"p_fleet[\"+aircraft_id+\"]\"].value = remaining_fleetkapa;
		} else {
			document.formular.elements[\"p_fleet[\"+aircraft_id+\"]\"].value = aircrafts;
		}
	
		calculate();
	}
      </script>

      <h1>{$MESSAGES[MSG_AIRPORT][m000]}</h1>";
  $pfuschOutput .= "

      <form action=attack.php method=post name=formular onsubmit=\"return checkSubmit();\">
      <table border=0 cellpadding=3 cellspacing=3>
      <tr>
        <td colspan=2 align=center class=table_head>
          {$MESSAGES[MSG_AIRPORT][m001]}
        </td>
      </tr>
      <tr>
        <td width='50%'>
          {$MESSAGES[MSG_AIRPORT][m002]}
        </td>
        <td width='50%'>";
          if($buildings["b_{$b_db_name[AIRPORT]}"] == 0) $pfuschOutput .= "0";
          else $pfuschOutput .= ($buildings["b_{$b_db_name[AIRPORT]}"]*5 + $user_techs[COMP_MANAGEMENT]*3);
          $pfuschOutput .= " {$MESSAGES[MSG_AIRPORT][m003]}
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m004]}
        </td>
        <td>
          <a href=simulation.php>Zum Kampf-Simulator</a>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br><br>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center class=table_head>
          {$MESSAGES[MSG_AIRPORT][m003]}
        </td>
      </tr>";


    $anzeige_geschehen_flag = false;
    for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
    {
      if ($p_count[$i] > 0)
      {
        if (!$anzeige_geschehen_flag)
          $anzeige_geschehen_flag = true;

        $pfuschOutput .= "  <tr>
              <td>
                <a href=\"$dir/description.php?show=$i&t=p\">$p_name[$i]</a> ($p_count[$i] {$MESSAGES[MSG_AIRPORT][m006]})
              </td>
              <td>
                <input class=button type=text name=\"p_fleet[$i]\" size=2 onkeyup=calculate()>
                <input class=button type=button name=\"p_button[$i]\" value=\"auff&uuml;llen\" onclick=\"fillFleet($i)\">
                <input type=hidden name=\"aircraft[$i]\" value=$p_count[$i]>
              </td>
            </tr>";
      }
      else
        $pfuschOutput .= "<input type=hidden name=\"p_fleet[$i]\" value=0>";
    }

    if (!$anzeige_geschehen_flag)
    {
      $pfuschOutput .= "  <tr>
            <td colspan=2 align=center>
              {$MESSAGES[MSG_AIRPORT][m007]}
            </td>
          </tr>
          </table>
          </form>";

      // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
      die();
    }

  $pfuschOutput .= "  <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center class=table_head>
          {$MESSAGES[MSG_AIRPORT][m008]}
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m008]}
        </td>
        <td>
          <input class=button size=3 type=text onkeyup=calculate() name=continent value=\"". (($_GET[x]) ? ((int)$_GET[x]) : $position[0]) ."\" maxlength=2>:
          <input class=button size=3 type=text onkeyup=calculate() name=country value=\"". (($_GET[y]) ? ((int)$_GET[y]) : $position[1]) ."\" maxlength=3>:
          <input class=button size=3 type=text onkeyup=calculate() name=aimed_city value=\"". (($_GET[z]) ? ((int)$_GET[z]) : $position[2]) ."\" maxlength=3>
          <select size='1' onchange='setcity(this)' class='button'>".$my_cities_select."</select>
        </td>
        <tr>
          <td></td>
          <td id='war_warning'></td>
        </tr>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m009]}
        </td>
        <td>
          <input type=text class=readonly readonly name=flytime size=13>
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m010]}
        </td>
        <td>
          <input type=text class=readonly readonly name=distance size=13> km
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m011]}
        </td>
        <td>
          <input type=text class=readonly readonly name=consumption size=13> Sauerstoff
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m012]}
        </td>
        <td>
          <input type=text class=readonly readonly name=speed size=13> km/h
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m013]}
        </td>
        <td>
          <input type=text class=readonly readonly name=capacity size=13> {$MESSAGES[MSG_AIRPORT][m014]}
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m040]}
        </td>
        <td>
          <input type=text class=readonly readonly name=fleetkapa size=13 value=" . ($buildings["b_{$b_db_name[AIRPORT]}"]*5 + $user_techs[COMP_MANAGEMENT]*3) . "> {$MESSAGES[MSG_AIRPORT][m003]}
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br><br>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center class=table_head>
          {$MESSAGES[MSG_AIRPORT][m015]}
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <input type=radio name=what value=attack checked>&nbsp;{$MESSAGES[MSG_AIRPORT][m016]}
        </td>
      </tr>
      <tr>
        <td colspan=2>
          &nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=plunder value=YES onclick=\"document.formular.what[0].checked=true\">&nbsp;{$MESSAGES[MSG_AIRPORT][m017]}";
          
          $plunder = sql_query("SELECT plunder_iridium, plunder_holzium, plunder_water, plunder_oxygen FROM userdata WHERE ID='$_SESSION[user]'");
          $plunder = sql_fetch_array($plunder);
          $plundern = array("empty", "plunder_first", "plunder_second", "plunder_third", "plunder_fourth");
          for($x=0;$x<=5;$x++)
          {
          	$pfuschOutput .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          	if($plunder['plunder_iridium'] == $x)
          	{
          		$pfuschOutput .= "
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=iridium checked> {$MESSAGES[MSG_GENERAL][m000]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=holzium> {$MESSAGES[MSG_GENERAL][m001]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=water> {$MESSAGES[MSG_GENERAL][m002]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=oxygen> {$MESSAGES[MSG_GENERAL][m003]}";
          	}
          	if($plunder['plunder_holzium'] == $x)
          	{
          		$pfuschOutput .= "
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=iridium> {$MESSAGES[MSG_GENERAL][m000]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=holzium checked> {$MESSAGES[MSG_GENERAL][m001]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=water> {$MESSAGES[MSG_GENERAL][m002]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=oxygen> {$MESSAGES[MSG_GENERAL][m003]}";
          	}
          	if($plunder['plunder_water'] == $x)
          	{
          		$pfuschOutput .= "
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=iridium> {$MESSAGES[MSG_GENERAL][m000]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=holzium> {$MESSAGES[MSG_GENERAL][m001]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=water checked> {$MESSAGES[MSG_GENERAL][m002]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=oxygen> {$MESSAGES[MSG_GENERAL][m003]}";
          	}
          	if($plunder['plunder_oxygen'] == $x)
          	{
          		$pfuschOutput .= "
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=iridium> {$MESSAGES[MSG_GENERAL][m000]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=holzium> {$MESSAGES[MSG_GENERAL][m001]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=water> {$MESSAGES[MSG_GENERAL][m002]}&nbsp;
          			<input type=radio name=$plundern[$x] onclick=\"document.formular.what[0].checked=true\" value=oxygen checked> {$MESSAGES[MSG_GENERAL][m003]}";
          	}
          }
          $pfuschOutput .= "
        </td>
      </tr>
      <tr>
        <td colspan=2>
          &nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=spy value=YES onclick=\"document.formular.what[0].checked=true\">&nbsp;{$MESSAGES[MSG_AIRPORT][m018]}
        </td>
      </tr>
      <tr >
        <td colspan=2>
          &nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=colonize value=YES onclick=\"document.formular.what[0].checked=true\">&nbsp;{$MESSAGES[MSG_AIRPORT][m019]} ". numberOfColonies($buildings["b_{$b_db_name[COMM_CENTER]}"]) ." {$MESSAGES[MSG_AIRPORT][m020]}
          <br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=colonize_jobs value=YES onclick=\"document.formular.what[0].checked=true\">&nbsp;{$MESSAGES[MSG_AIRPORT][m034]}
          <br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=colonize_fleet value=YES onclick=\"document.formular.what[0].checked=true\">&nbsp;{$MESSAGES[MSG_AIRPORT][m035]}
          <br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=colonize_hangar value=YES onclick=\"document.formular.what[0].checked=true\">&nbsp;{$MESSAGES[MSG_AIRPORT][m039]}
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <hr size=1 color=#FFFFFF>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <input type=radio name=what value=transport>&nbsp;{$MESSAGES[MSG_AIRPORT][m021]}
        </td>
      </tr>
      <tr>
        <td valign=top colspan=2>
		<table>
			<tr><td width=\"200\">&nbsp;&nbsp;&nbsp;&nbsp;<input class=button type=text name=transport_iridium size=8 onkeyup=calculate() onfocus=\"document.formular.what[1].checked=true\"> {$MESSAGES[MSG_GENERAL][m000]} </td><td align=left><input class=button type=button name=\"button_fill_iridium\" value=\"auff&uuml;llen\" onmouseover=\"{$MESSAGES[MSG_AIRPORT][m041]}\" onclick=\"doIri()\"></td></tr>
			<tr><td width=\"200\">&nbsp;&nbsp;&nbsp;&nbsp;<input class=button type=text name=transport_holzium size=8 onkeyup=calculate() onfocus=\"document.formular.what[1].checked=true\"> {$MESSAGES[MSG_GENERAL][m001]} </td><td><input class=button type=button name=\"button_fill_holzium\" value=\"auff&uuml;llen\" onmouseover=\"{$MESSAGES[MSG_AIRPORT][m041]}\" onclick=\"doHolzi()\"></td></tr>
			<tr><td width=\"200\">&nbsp;&nbsp;&nbsp;&nbsp;<input class=button type=text name=transport_water size=8 onkeyup=calculate() onfocus=\"document.formular.what[1].checked=true\"> {$MESSAGES[MSG_GENERAL][m002]} </td><td><input class=button type=button name=\"button_fill_water\" value=\"auff&uuml;llen\" onmouseover=\"{$MESSAGES[MSG_AIRPORT][m041]}\" onclick=\"doWat()\"></td></tr>
			<tr><td width=\"200\">&nbsp;&nbsp;&nbsp;&nbsp;<input class=button type=text name=transport_oxygen size=8 onkeyup=calculate() onfocus=\"document.formular.what[1].checked=true\"> {$MESSAGES[MSG_GENERAL][m003]} </td><td><input class=button type=button name=\"button_fill_oxygen\" value=\"auff&uuml;llen\"  onmouseover=\"{$MESSAGES[MSG_AIRPORT][m041]}\" onclick=\"doOxi()\"></td></tr>
		</table>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          &nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=give value=YES onclick=\"calculate();document.formular.what[1].checked=true\">&nbsp;{$MESSAGES[MSG_AIRPORT][m022]}
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          {$MESSAGES[MSG_AIRPORT][m023]} <input class=button type=text name=f_name maxlength=240 size=50><br>
          <input type=checkbox name=f_name_show value=YES> {$MESSAGES[MSG_AIRPORT][m036]}
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center>
          <br>
          <input type=hidden name=action value=attack>
          <input name=uhrzeit ondblclick=\"javascript:disabled=true\" value=\"{$MESSAGES[MSG_AIRPORT][m024]}\" type=submit class=button>
        </td>
      </tr>
      </table>
      </form>";

 // end specific page logic


  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
