<?php
$use_lib = 10; // MSG_ALLIANCES

require_once ("msgs.php");
require_once ("database.php");
require_once ("constants.php");
require_once ("functions.php");
require_once ("do_loop.php");

require_once ('class_Krieg.php');

// define phptal template
require_once ("PHPTAL.php");
require_once ("include/PHPTAL_EtsTranslator.php");
$template = new PHPTAL( 'theme_blue_line.html' );
$template->setTranslator( new PHPTAL_EtsTranslator( ) );
$template->setEncoding( 'ISO-8859-1' );
$template->set('contentMacroName','war_admin.html/content');

// set page title
$template->set( 'pageTitle', 'Verwaltung - Kriege' );

// insert specific page logic here

if ($_SESSION['sitt_login'])
    ErrorMessage( MSG_GENERAL, e000 ); // Die Funktion ist für Sitter gesperrt

$get_user_infos = sql_query("SELECT alliance,alliance_status FROM usarios WHERE ID='$_SESSION[user]'" );
$user_infos = sql_fetch_array( $get_user_infos );
if( $user_infos['alliance_status'] == 'member' )
    ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt

$krieg = new Krieg($user_infos[alliance]);

if ( !empty($_POST[id]) && !$krieg->load($_POST[id]) )
    ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt

if (ErrorMessage( 0 )) {
    $errorMessage .= "  <h1>{$MESSAGES[MSG_ALLIANCES][m000]}</h1>";
    $errorMessage .= ErrorMessage();

    // add error output
    $template->set( 'errorMessage', $errorMessage );

    // include common template settings
    require_once ("include/JavaScriptCommon.php");
    require_once ("include/TemplateSettingsCommon.php");

    // save resource changes (ToDo: Is this necessary on every page?)
    $timefixed_depot->save();
    // create html page
    try {
        echo $template->execute();
    } catch( Exception $e ) {
        echo $e->getMessage();
    }

    die();
}

// Fix for Konqueror (and others?) who do not include unchecked checkboxes in data array
// * take a empty complete war structure and copy the values of the form into it.
// * then set it again for further processing.
if(!empty($_POST[config])) {
    $tmp_war = $krieg->getWars(Krieg::TYPE_EMPTY, true);
    merge_arrays($tmp_war, array($_POST));
    $_POST = $tmp_war[0];
    unset($tmp_war);
}

switch ( $_POST[action]) {

    case 'Krieg anzetteln':
        if(!$krieg->start($_POST[config]))
            $errorMessage .= implode('<br />', $krieg->getErrors());
        else
            // unset values from POST because the data is now accessable by getWars()
            unset($_POST);
    break;

    case 'Geändertes Angebot absenden':
        if(!$krieg->modify($_POST[config]))
            $errorMessage .= implode('<br />', $krieg->getErrors());
        else
            // unset values from POST because the data is now accessable by getWars()
            unset($_POST);
    break;

    case 'Krieg annehmen':
         if(!$krieg->accept())
            $errorMessage .= implode('<br />', $krieg->getErrors());
        else
            // unset values from POST because the data is now accessable by getWars()
            unset($_POST);
    break;

    case 'Verhandlung ablehnen':
        if(!$krieg->deny())
            $errorMessage .= implode('<br />', $krieg->getErrors());
        else
            // unset values from POST because the data is obsolete now and disturbs
            // the correct rendering of the new-war-tab
            unset($_POST);
    break;

    case 'Verhandlung abbrechen':
        if(!$krieg->cancel())
            $errorMessage .= implode('<br />', $krieg->getErrors());
        else
            // unset values from POST because the data is obsolete now and disturbs
            // the correct rendering of the new-war-tab
            unset($_POST);
    break;

    case 'Kapitulieren':
        if(!$krieg->surrender())
            $errorMessage .= implode('<br />', $krieg->getErrors());
    break;
    
    case 'Remis anbieten':
    	if(!$krieg->remis())
    		$errorMessage .= implode('<br />', $krieg->getErrors());
    break;
    
    case 'Remis annehmen':
    	if(!$krieg->remis_accept())
    		$errorMessage .= implode('<br />', $krieg->getErrors());
    break;
}

if(!empty($errorMessage))
    $template->set( 'errorList', $errorMessage);

$new_war = $krieg->getWars(Krieg::TYPE_EMPTY, true);
if(!empty($_POST[config])) {
    // prepare oppenents and allies for redisplay in form
    $_POST[side] = 'A';
    $_POST[opponent]   = !empty($_POST[config][war][opponents]) ? explode(',', $_POST[config][war][opponents]) : array();
    $_POST[challenger] = !empty($_POST[config][war][allies]) ? explode(',', $_POST[config][war][allies]) : array();
    merge_arrays($new_war, array($_POST));
}

$negotiations = array_merge($krieg->getWars(Krieg::TYPE_NEGO, true), $new_war );

foreach($negotiations as &$neg) {
    if(is_array($neg[opponent])) // Strip own tag from list
        if(!(($t = array_search($user_infos[alliance], $neg[opponent])) === FALSE))
            unset($neg[opponent][$t]);

    if(is_array($neg[challenger])) // Strip own tag from list
        if(!(($t = array_search($user_infos[alliance], $neg[challenger])) === FALSE))
            unset($neg[challenger][$t]);

    if($neg[side]=='A') {
        $neg[_opponents] = is_array($neg[opponent]) ? implode(',', $neg[opponent]) : '';
        $neg[_allies] = is_array($neg[challenger]) ? implode(',', $neg[challenger]) : '';
    }
    else {
        $neg[_allies] = is_array($neg[opponent]) ? implode(',', $neg[opponent]) : '';
        $neg[_opponents] = is_array($neg[challenger]) ? implode(',', $neg[challenger]) : '';
    }
}

$template->set( 'negotiations', $negotiations);
$template->set( 'wars', $krieg->getWars(Krieg::TYPE_OPEN, true));

$template->set( 'inWar', $krieg->inWar());

$template->set( 'user_path', $_SESSION['user_path'] );

// end specific page logic


// include common template settings
require_once ("include/JavaScriptCommon.php");
require_once ("include/TemplateSettingsCommon.php");

// save resource changes (ToDo: Is this necessary on every page?)
$timefixed_depot->save();

// create html page
try {
    echo $template->execute();
} catch( Exception $e ) {
    echo $e->getMessage();
    echo $e->getTraceAsString();
}
?>
