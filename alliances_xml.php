<?php
$use_lib = 10; // MSG_ALLIANCES

require_once ("msgs.php");
require_once ("database.php");
require_once ("constants.php");
require_once ("functions.php");
require_once ("do_loop.php");



if ($_SESSION['sitt_login'])
    ErrorMessage( MSG_GENERAL, e000 ); // Die Funktion ist für Sitter gesperrt


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

$get_user_infos = sql_query(
    "SELECT alliance FROM usarios WHERE user='$_SESSION[user]'" );
$user_infos = sql_fetch_array( $get_user_infos );


// create dom-object with doctype
$dom = new DomDocument("1.0");
// display document in browser as plain text for readability purposes
header("Content-Type: text/xml");

// create root element
$root = $dom->createElement("alliance");
$dom->appendChild($root);

// add alliance name as first element
$name     = $dom->createElement("name");
$nametext = $dom->createTextNode($user_infos[0]);
$name->appendChild($nametext);
$dom->documentElement->appendChild($name);

// create member list
$members  = $dom->createElement("members");

$get_members = sql_query(
    "SELECT user,home,x_pos,y_pos,z_pos,city_name,points FROM city WHERE alliance='". $user_infos[0] ."' ORDER BY user");

$currentMember = "";
while ($member_row = sql_fetch_array($get_members)) {

    // if new member, create new member element with member name
    if ($currentMember != $member_row[0]) {
        $member     = $dom->createElement("member");
        $memberName = $dom->createElement("name");
        $nametext   = $dom->createTextNode($member_row[0]);
        $memberName->appendChild($nametext);
        $member->appendChild($memberName);
        $members->appendChild($member);
    }

    // create city element
    $city = $dom->createElement("city");

    // add name to city element
    $name = $dom->createElement("name");
    $text   = $dom->createTextNode($member_row[5]);
    $name->appendChild($text);
    $city->appendChild($name);

    // add city points to city element
    $points = $dom->createElement("points");
    $pointstext   = $dom->createTextNode($member_row[6]);
    $points->appendChild($pointstext);
    $city->appendChild($points);

    if ($member_row[1] == 'YES') {
        $capital = $dom->createElement("capital");
        $text    = $dom->createTextNode("yes");
        $capital->appendChild($text);
        $city->appendChild($capital);
    }

    // coordinates
    $coords = $dom->createElement("coordinates");

        // add continent to coordinates element
        $continent = $dom->createElement("continent");
        $conttext   = $dom->createTextNode($member_row[2]);
        $continent->appendChild($conttext);
        $coords->appendChild($continent);

        // add land to coordinates element
        $land = $dom->createElement("land");
        $landtext   = $dom->createTextNode($member_row[3]);
        $land->appendChild($landtext);
        $coords->appendChild($land);

        // add town to coordinates element
        $town = $dom->createElement("town");
        $towntext   = $dom->createTextNode($member_row[4]);
        $town->appendChild($towntext);
        $coords->appendChild($town);

    $city->appendChild($coords);

    // add city element to member element
    $member->appendChild($city);

    $currentMember = $member_row[0];
}

$dom->documentElement->appendChild($members);

// save resource changes (ToDo: Is this necessary on every page?)
$timefixed_depot->save();

// create html page
try {
    echo $dom->saveXML(); 
} catch( Exception $e ) {
    echo $e->getMessage();
}
?>
