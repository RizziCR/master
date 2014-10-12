<?php
  $use_lib = 7; // MSG_ADMINISTRATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL( 'theme_blue_line.html' );
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','administration.html/content');

  // set page title
  $template->set('pageTitle', 'Verwaltung - Städte');


 // insert specific page logic here
  session_start();

  if ($_SESSION['sitt_login'])
    ErrorMessage(MSG_GENERAL,e000);  // Die Funktion ist für Sitter gesperrt

  if (ErrorMessage(0))
  {
    $errorMessage .= "  <h1>{$MESSAGES[MSG_ADMINISTRATION][m000]}</h1>";
    $errorMessage .= ErrorMessage();

    // include common template settings
    require_once("include/JavaScriptCommon.php");
    require_once("include/TemplateSettingsCommon.php");

    // add error output
    $template->set('errorMessage', $errorMessage);
    // save resource changes (ToDo: Is this necessary on every page?)
    $timefixed_depot->save();
    // create html page
    try {
      echo $template->execute();
    }
    catch (Exception $e) { echo $e->getMessage(); }

    die();
  }

  $ERR_MSG = '';

  if ( isset($_GET[up]) ) {
      $o = array();
      $tmp = sql_query("SELECT city,pos FROM city WHERE user='$_SESSION[user]' ORDER BY pos ASC");
    while(list($c, $p)=sql_fetch_row($tmp))
        $o[$p] = $c;
    if( ($tmp = array_search($_GET[city], $o)) != 1) { // nicht, wenn Element schon ganz oben
        $o[$tmp] = $o[$tmp-1];
        $o[$tmp-1] = $_GET[city];
        foreach($o as $p => $c)
            sql_query("UPDATE city SET pos=".$p." WHERE city='".addslashes($c)."' AND user='$_SESSION[user]'");
    }
  }
  elseif (isset($_GET[down]) ) {
      $o = array();
      $tmp = sql_query("SELECT city,pos FROM city WHERE user='$_SESSION[user]' ORDER BY pos ASC");
    while(list($c, $p)=sql_fetch_row($tmp))
        $o[$p] = $c;
    if( ($tmp = array_search($_GET[city], $o)) != count($o)) { // nicht, wenn Element schon ganz unten
        $o[$tmp] = $o[$tmp+1];
        $o[$tmp+1] = $_GET[city];
        foreach($o as $p => $c)
            sql_query("UPDATE city SET pos=".$p." WHERE city='".addslashes($c)."' AND user='$_SESSION[user]'");
    }
  }
  elseif (isset($_GET[xedit]) ) {

    $get_admin_details = sql_query("SELECT city,city_name,text,pic FROM city WHERE city='".addslashes($_GET[city])."' AND user='$_SESSION[user]'");
    while($admin_details = sql_fetch_array($get_admin_details)) {
        $tmp['city'] = $admin_details['city'];
        $tmp['city_name'] = $admin_details[city_name];
        $tmp['city_text'] = str_replace("<br>","\n",stripslashes($admin_details[text]));
        $tmp['city_pic'] = ($admin_details[pic] == '' ? 'http://' : $admin_details[pic] );
        $tmp['set_open'] = 1;
    }
      try {
        // include common template settings
        require_once("include/JavaScriptCommon.php");
        require_once("include/TemplateSettingsCommon.php");

          $template->set('city', $tmp);
          $template->setSource('<block tal:omit-tag="" metal:use-macro="administration.html/city_edit"/>', __FILE__);
        echo $template->execute();
      } catch(Exception $e) { echo $e->getMessage(); }
    exit;
  }

  if ($_POST[action] == "save")
  {
    $pos = 1;
    foreach($_POST[city] as $k => $v) {
        sql_query("UPDATE city SET pos=".($pos++)." WHERE city='".addslashes($k)."' AND user='$_SESSION[user]'");
    }
    foreach($_POST[city] as $k => $v) {
        if(!isset($_POST[name][$k]) && !isset($_POST[city_text][$k]) && !isset($_POST[city_pic][$k]))
            continue;

        $city_text = str_replace("<","&lt;",strip_magic_slashes($_POST[city_text][$k]));
        $city_text = str_replace(">","&gt;",$city_text);
        //$city_text = str_replace("\n","<br>",$city_text); // <br> ist nicht bb code konform
        $city_text = trim($city_text);

        $name = str_replace("<","&lt;",strip_magic_slashes($_POST[name][$k]));
        $name = str_replace(">","&gt;",$name);

        if (substr_count($name,"\"") || substr_count($name,"'")) {
          $ERR_MSG .= $k.': '.$GLOBALS[MESSAGES][MSG_ADMINISTRATION][e001]."\n";  // " und ' können in Stadtnamen nicht verwendet werden
          continue;
        }

        if (strlen($_POST[city_pic][$k]) == 7) $city_pic = "";
        else $city_pic = strip_magic_slashes($_POST[city_pic][$k]);

        sql_query("UPDATE city SET city_name='". addslashes($name) ."',text='". addslashes($city_text) ."',pic='". addslashes($city_pic) ."'".
            " WHERE city='".addslashes($k)."' AND user='$_SESSION[user]'");
    }
  } // </save>

  $cities = $tmp = array();
  $get_admin_details = sql_query("SELECT city,home,city_name,text,pic FROM city WHERE user='$_SESSION[user]' ORDER BY pos ASC");
  while($admin_details = sql_fetch_array($get_admin_details)) {
      $tmp['city'] = $admin_details['city'];
      if ($admin_details[home] == "YES")
          $tmp['home'] = "capital";
      else
          $tmp['home'] = "city";
    $tmp['city_name'] = $admin_details[city_name];
    $tmp['city_text'] = str_replace("<br>","\n",stripslashes($admin_details[text]));
    $tmp['city_pic'] = ( $admin_details[pic] == '' ? 'http://' : $admin_details[pic] );
    $tmp['set_open'] = ( isset($_GET[edit]) && $admin_details['city'] == $_GET['city'] );
    $cities[] = $tmp;
  }

  $template->set('cities', $cities);
  $template->set('ERR_MSG', $ERR_MSG);

 // end specific page logic


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

