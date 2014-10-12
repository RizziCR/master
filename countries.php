<?php
  // $use_lib = ?; // MSG_ADMINISTRATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");
  include("tutorial.php");

    // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('countries.html');
  $template = new PHPTAL('theme_blue_line.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName', 'countries.html/content');

  // set page title
  $template->set('pageTitle', 'Übersicht - Länder');

  $template->set('pfuschOutput', $pfuschOutput);
  
  $select = sql_query("SELECT city FROM city WHERE ID = '$_SESSION[city]'");
  $select = sql_fetch_array($select);

 // insert specific page logic here
  $con = $_GET[con];
  $cou = $_GET[cou];
  $continent = intval($_GET[continent]);
  $country   = intval($_GET[country]);

  if (!$continent || !$country || $continent < 1 || $continent > MAX_CONTINENT || $country < 1 || $country > MAX_COUNTRY)
  {
    $get_home = split(":",$select[city]);
    $continent = $get_home[0];
    $country = $get_home[1];
  }

  if ($con == 'prev' && $continent > 0) {
    $continent--;
    if($continent == 0) $continent = MAX_CONTINENT;
  }

  if ($con == 'next' && $continent <= MAX_CONTINENT) {
    $continent++;
    if($continent == MAX_CONTINENT+1) $continent = 1;
  }

  if ($cou == 'prev' && $country > 0) {
    $country--;
    if($country == 0) $country = MAX_COUNTRY;
  }

  if ($cou == 'next' && $country <= MAX_COUNTRY) {
    $country++;
    if($country == MAX_COUNTRY+1) $country = 1;
  }

  // ID für Spionagesonde generieren
  $spy_id = rand(10000, 99999);
  $_SESSION[spy_id] = $spy_id;

  // Staedteinfos zusammentragen
  $get_cities = sql_query("SELECT ID,user,city,home,city_name,alliance,points,x_pos,y_pos,z_pos,b_hangar,b_technologie_center FROM city WHERE x_pos='$continent' && y_pos='$country' ORDER BY z_pos");
  $anzahl = CountrySize($continent, $country);

  $t_cities = array();
  for($i=0; $i<$anzahl; $i++)
  {
    $t_cities[$i]['name'] = '';
  }
  while($city = sql_fetch_array($get_cities))
  {
  	
  	$select = sql_query("SELECT tag FROM alliances WHERE ID = '$city[alliance]'");
  	$select = sql_fetch_array($select);
  	$city['alliance'] = $select['tag'];
  	
    $pos = $city['z_pos']-1;
    $sUser = new User($city['user']);
    $t_cities[$pos]['name'] = $city['city_name'];
    $t_cities[$pos]['user'] = $sUser->getName();
    $t_cities[$pos]['user_affix'] = $sUser->getAffix();
    $t_cities[$pos]['ally'] = $city['alliance'];
    $t_cities[$pos]['points'] = $city['points'];

    // Bestimmen, ob Bewahrer/Hauptstadt
    $t_cities[$pos]['isKeeper'] = 0;
    $t_cities[$pos]['isPlayer'] = 0;
    $t_cities[$pos]['isCapital'] = 0;
    if ($_SESSION['user'] == $city['user']) {
		$t_cities[$pos]['isPlayer'] = 1;
    } else {
	$getKeeper = sql_query("SELECT user FROM donations WHERE user='". $city['user'] ."'");
	if (sql_num_rows($getKeeper) > 0)
	$t_cities[$pos]['isKeeper'] = 1;
    }
    if ($city['home'] == "YES")
		$t_cities[$pos]['isCapital'] = 1;

    // Angriffsstatus bestimmen
    /* no hangar protection in ETS9
    $t_cities[$pos]['isAttackable'] = 0;
    if (( $city['home']=='YES' && $city['b_hangar'] ) ||
        ( $city['home']=='NO' && ( $city['b_hangar'] || $city['b_technologie_center'] ) ))
    { */
      $t_cities[$pos]['isAttackable'] = 1;
    /* } */
  }

  $get_airport = sql_query("SELECT b_airport FROM city WHERE user='". $_SESSION['user'] ."' && ID='". $_SESSION['city'] ."'");
  $airport = sql_fetch_row($get_airport);
 // end specific page logic


  // add pfusch output
  $template->set('curConti', $continent);
  $template->set('curCountry', $country);
  $template->set('isSitting', $_SESSION['sitt_login']);
  $template->set('hasAirport', (($airport[0] > 0) ? 1 : 0));
  $template->set('cities', $t_cities);
  $template->set('id', $spy_id);

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
