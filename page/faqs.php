<?php
  require("functions.php");
  require("database.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/faq.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  function urlize($arg1=null) {
    $urlparts = array();
    foreach($arg1 as $urlpart) {
        foreach($urlpart as $id => $name) {
            $umlaute = array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
            $replace = array("ae","oe","ue","Ae","Oe","Ue","ss");
            $tmp = preg_replace($umlaute, $replace, $name);
            $tmp = preg_replace('/(\W+)/', '-', $tmp);
            $urlparts[] = trim($tmp,'-').'-'.$id;
        }
    }
    return implode('/', $urlparts).'.html';
  }

  // set page title
  $template->set('pageTitle', 'Fragen zu ETS');

  $pfuschOutput = "";
  $pfuschOutput .= '  <a name="top"></a><h1>Fragen zu ETS</h1>

      <table border="0" cellpadding="3" cellspacing="3">
      <tr valign="top" align="left"><td><b>Themenbereiche</b><br><br></td></tr>
      <tr><td align="left">';

  $get_cats = sql_query("SELECT * FROM admin_faq_cat ORDER BY name");
  while ($cats = sql_fetch_array($get_cats))
    $pfuschOutput .= '    <a href="/page/faq/'.urlize(array(array($cats[id]=>$cats[name]))).'">'.$cats[name].'</a><br>';

  $pfuschOutput .= '    </td></tr>';

  if ($_GET[cat])
  {
    $category = $_GET[cat];
    if(!is_numeric($category)) {
        list($category) = explode('.', $category);
        $category = explode('-', $category);
        $category = array_pop($category);
    }

    $pfuschOutput .= '
      <tr><td align="left"><br><br></td></tr>
      <tr><td align="left"><b>Fragen</b><br><br></td></tr>
      <tr><td align="left">';

    $get_questions = sql_query("SELECT admin_faq.id,cat,name,question FROM admin_faq,admin_faq_cat WHERE cat=admin_faq_cat.id AND cat='".addslashes($category)."' ORDER BY sorting ASC");
    while ($questions = sql_fetch_array($get_questions)) {
      $tmp = array(array($questions[cat]=>$questions[name]), array($questions[id]=>$questions[question]));
      $pfuschOutput .= '  <a href="/page/faq/'.urlize($tmp).'">'.$questions[question].'</a><br>';
    }

    $pfuschOutput .= '  </td></tr>';

    if ($_GET[id])
    {
      $question = $_GET[id];
      if(!is_numeric($question)) {
          list($question) = explode('.', $question);
          $question = explode('-', $question);
          $question = array_pop($question);
      }

      $get_answer = sql_query("SELECT * FROM admin_faq WHERE id='".addslashes($question)."'");
      $answer = sql_fetch_array($get_answer);

      $template->set('pageTitle', stripslashes($answer[title]));
      $template->set('descriptionContent', stripslashes($answer[description]));
      $template->set('keywordsContent', stripslashes($answer[keywords]));

      $pfuschOutput .= '
        <tr><td align="left"><br><br></td></tr>
        <tr><td align="left"><b>'.stripslashes($answer[question]).'</b></td></tr>
        <tr><td align="left">'. stripslashes($answer[answer]) .'</td></tr>';
    }
  }

  $pfuschOutput .= '  </table>';



  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
