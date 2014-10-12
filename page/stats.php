<?php

    include("database.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");
    $template = new PHPTAL('guest/theme_blue_line_guest.html');
    $template->setTranslator(new PHPTAL_EtsTranslator());
    $template->setEncoding('ISO-8859-1');
    $template->set('contentMacroName','stats.html/content');
    $template->set('pageTitle', 'Statistik');

    // insert specific page logic here
    require_once("include/TemplateSettingsCommonGuest.php");

    $get_users = sql_fetch_array(sql_query("SELECT COUNT(*) AS sum FROM usarios"));
    $get_cities = sql_fetch_array(sql_query("SELECT COUNT(*) AS sum FROM city"));
    $get_alliances = sql_fetch_array(sql_query("SELECT COUNT(*) AS sum FROM alliances"));

    /*
    $res = sql_query("SELECT Count(*) AS anzahl FROM multi_sessions WHERE login_time>UNIX_TIMESTAMP()-300");
    $show = sql_fetch_array($res);
    $loginLast5 = $show[anzahl];
    $res = sql_query("SELECT Count(*) AS anzahl FROM multi_sessions WHERE login_time>UNIX_TIMESTAMP()-3600");
    while ($show = sql_fetch_array($res))
    $loginLast60 = $show[anzahl];
    $res = sql_query("SELECT Count(*) AS anzahl FROM multi_sessions WHERE login_time>UNIX_TIMESTAMP()-86400");
    while ($show = sql_fetch_array($res))
    $loginLast24 = $show[anzahl];
    */
    // User
    $userCount = number_format($get_users[sum],0,",",".");
    // St&auml;dte
    $cityCount = number_format($get_cities[sum],0,",",".");
    // Allianzen
    $allyCount = number_format($get_alliances[sum],0,",",".");
    $res = sql_query("SELECT sum(points) AS pts,sum(tech_points) AS tpts,sum(points + tech_points) AS sumpts FROM usarios");
    $show = sql_fetch_array($res);
    // Gesamtpunkte
    $allPoints = number_format($show[sumpts],0,",",".");
    // St&auml;dtepunkte
    $cityPoints = number_format($show[pts],0,",",".");
    //Technologiepunkte
    $techPoints = number_format($show[tpts],0,",",".");

    $res = sql_query("SELECT Count(logged_in) AS online FROM usarios WHERE logged_in='YES'");
    while ($show = sql_fetch_array($res))
    $online = $show[online];
    $template->set('online', $online);

    $res = sql_query("SELECT Count(logged_in) AS online FROM usarios WHERE last_action>UNIX_TIMESTAMP()-3600");
    while ($show = sql_fetch_array($res))
    $online60 = $show[online];
    $template->set('online60', $online60);

    $res = sql_query("SELECT Count(logged_in) AS online FROM usarios WHERE last_action>UNIX_TIMESTAMP()-86400");
    while ($show = sql_fetch_array($res))
    $online24 = $show[online];
    $template->set('online24', $online24);

    $template->set('loginLast5', $loginLast5);
    $template->set('loginLast60', $loginLast60);
    $template->set('loginLast24', $loginLast24);
    $template->set('userCount', $userCount);
    $template->set('cityCount', $cityCount);
    $template->set('allyCount', $allyCount);
    $template->set('allPoints', $allPoints);
    $template->set('cityPoints', $cityPoints);
    $template->set('techPoints', $techPoints);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
