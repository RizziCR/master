<?php
require_once ("config_general.php");

require_once ('include/class_Lager.php');
require_once ('include/class_User.php');
require_once ('include/class_Party.php');
require_once ('constants.php');
//
/*function isIphone($user_agent=NULL) {
    if(!isset($user_agent)) {
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
    return (strpos($user_agent, 'iPhone') !== FALSE);
}*/

function smtp_mail($adress, $subject, $message) {
    require_once ("class.phpmailer.php");
 
    /* Auf keinen Fall die E-Mail-Adresse in der naechsten Zeile loeschen/aendern! */
    #    $MailboxPointer = imap_open("{imap.dd24.net:143/imap/notls}", 'noreply@escape-to-space.de', "fidfhhrujd");
    #    imap_close($MailboxPointer);


    $mail = new PHPMailer ( );

    $mail->IsSMTP();
    $mail->Username = $GLOBALS [noreplyEmail];
    $mail->Password = 'u/m1ohZ4t.eiri\"eH0e$Yu5taiqu';
    $mail->Host = "twix.ws";
    $mail->SMTPSecure = 'tls';
    $mail->Helo = "escape-to-space.de";
    $mail->SMTPAuth = true;


    $mail->From = $GLOBALS [noreplyEmail];
    $mail->FromName = "Escape To Space";
    $mail->AddAddress ( $adress );
    $mail->AddReplyTo ( $GLOBALS [supportEmail], "Escape To Space" );

    $mail->WordWrap = 80;
    $mail->IsHTML ( true );

    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = strip_tags ( $message );

    if (! $mail->Send ()) {
        echo "Message could not be sent. <p>";
        echo "Mailer Error: " . $mail->ErrorInfo;
        exit ();
    }
}

/**
 * transcodes the ip to a hash value which is not (easy) backtranscodeable
 *
 * @param string $ip
 * @return string
 */
function transcodeIp($ip) {
    return md5 ( 'abnjb12k3as' . $ip );
}

/**
 * anonymizes the given ip by replacing the last octet by 'xxx'.
 *
 * @param string $ip
 * @return string
 */
function anonIp($ip) {
    return substr($ip,0,strrpos($ip,".")).".xxx";
}

// Gebäude-Preise
function Price($start, $step, $type) {
    switch ( $type) {
        // Gebäude allgemein
        case "G_LIN" :
            {
                $erg = floor ( $start * (1 + pow ( $step, 1.9 )) );
                break;
            }

        // Kommunikationszentrum
        case "G_EXP_KZ_IR" :
            {
                $erg = floor( pow( $step,4.2 )/4 + $step*500 + $start );
                break;
            }
        case "G_EXP_KZ_HZ" :
            {
                $erg = floor( pow( $step,4 )/4 + $step*350 + $start );
                break;
            }

        // Schutzschild
        case "G_SS_IR" :
            {
                $erg = floor( $step*5000 + 5000*pow(1.6,pow($step,0.65)) );
                break;
            }
        case "G_SS_HZ" :
            {
                $erg = floor( $step*5000 + 5000*pow(1.65,pow($step,0.65)) );
                break;
            }

        // Technologien allgemein
        case "T_LIN" :
            {
                $erg = floor ( $start * (1 + pow ( $step, 1.4 )) );
                break;
            }

        // Wasserkompression
        /*
       * wertanpassung ETS8
       * Holzium: ABRUNDEN (((Stufe-1)/1,3)^4,3+(Stufe-1)*500+1000)
         O²: ABRUNDEN (((Stufe-1)/1,6)^5,5+(Stufe-1)*500+500)
       */
        case "T_EXP_WK_HZ" :
            {
                //$erg = floor(pow($step,3.8) + $step*($start/2) + $start);
                $erg = floor ( pow ( $step / 1.3, 4.3 ) + $step * 500 + 1000 );
                break;
            }
        case "T_EXP_WK_OX" :
            {
                $erg = floor ( pow ( $step / 1.6, 5.5 ) + $step * 500 + 500 );
                //$erg = floor(pow($step,4.8) + $step*($start) + $start);
                break;
            }

        // Bergbautechnik
        case "T_EXP_BBT_HZ" :
            {
                $erg = floor( ($start/2 + $step*1000 + pow($step, 4*pow(1.002,$step))) );
                break;
            }
        case "T_EXP_BBT_OX" :
            {
                $erg = floor( ($start/2 + $step*2250 + pow($step, 4.15*pow(1.002,$step))) );
                break;
            }

        // Flugzeugkapazitätsverwaltung
        case "T_EXP_PS_HZ" :
            {
                $erg = floor ( pow ( $step, 3.7 ) + $step * ($start / 2) + $start );
                break;
            }
        case "T_EXP_PS_OX" :
            {
                $erg = floor ( pow ( $step, 4.5 ) + $step * ($start / 2) + $start );
                break;
            }
    }
    return $erg;
}

// Gebäude-Dauer
function Duration($start, $step, $div, $item = null, $dep = null) {
//    switch ( $item) { // $item only set if in tech mode
//        case E_WEAPONS :
//            $step += $dep [t_shield_tech] * 3;
//        break;
//        case P_WEAPONS :
//            $step += $dep [t_shield_tech] * 5;
//        break;
//        case N_WEAPONS :
//            $step += $dep [t_shield_tech] * 6;
//        break;
//        case SHIELD_TECH :
//            $step = 2 * ( $step + $dep [t_electronsequenzweapons] + $dep [t_protonsequenzweapons] + $dep [t_neutronsequenzweapons] );
//        break;
//    }
    $while = round ( $start * (1 + pow ( 1.5, $step ) / pow ( 2.5, $div )) );
    // old value
    //$while = round ( $start * (1 + pow ( 1.5, $step ) / pow ( 2, $div )) );
	
    if ($while > 31 * 24 * 3600)
        $while = 31 * 24 * 3600;

    return $while;
}

// Bauzentrumsdauer
function Duration_Work_Board($step) {
    return 6000 / (pow ( $step + 1, - 0.7 )) + 5000;
}

// Förderung der Rohstoffgebäude pro Stunde
function Foerderung($resource, $building, $tech, $lagerWater = 0, $buildingWater = 0) {
    global $t_increase;
    $tore = 1.0; // Ueberbleibsel aus der WM :)

    // $alliance_give - ein Feature des Projekts "Allianzstadt"
    // $alliance_give muss ein Prozentwert UNTER 1 sein, z.B. 25% der Förderung abgeben ergibt 0.75

    $alliance_give = 1;
    $produktionshalle = 1;
    
    switch ( $resource) {
        case IRIDIUM :
            {
                $erg = (15 * pow ( $building, 1.8 ) + 2000) * pow ( $t_increase [MINING], $tech );
                # * $alliance_give * ($produktionshalle * PRODUKTIONSHALLE_BONUS);
                break;
            }
        case HOLZIUM :
            {
                $erg = (15 * pow ( $building, 1.7 ) + 2000) * pow ( $t_increase [MINING], $tech );
                # * $alliance_give * ($produktionshalle * PRODUKTIONSHALLE_BONUS);
                break;
            }
        case WATER :
            {
                $erg = (10 * pow ( $building, 2 ) + 10);
                break;
            }
        case OXYGEN :
            {
                if ($lagerWater > 0) {
                     $erg = ((20 / 7) * pow ( $building, 2 ) + 200) * pow ( $t_increase [COMPRESSION], $tech );
                     # * $alliance_give * ($produktionshalle * PRODUKTIONSHALLE_BONUS);
                }else{
              /* (Foerderung(WATER,$buildingWater,0)/3.5) => Verbrauch des Reaktors */
              		$erg = ((Foerderung ( WATER, $buildingWater, 0 ) / 3.5) + 200) * pow ( $t_increase [COMPRESSION], $tech );
              		# * $alliance_give * ($produktionshalle * PRODUKTIONSHALLE_BONUS);
                }
              	break;
            }
    }
    return $tore * $erg;
}

// Verbrauch der Rohstoffgebäude pro Stunde
function Verbrauch($resource, $building) {
    switch ( $resource) {
        case WATER :
            {
                $erg = 10 * pow ( $building, 2 );
                break;
            }
        default :
            {
                $erg = 0;
                break;
            }
    }
    return $erg;
}

// Lagergröße
function Lager($type, $building, $tech) {
    global $t_increase;

    switch ( $type) {
        case DEPOT :
            {
                //$erg = round ( 2 * (5000 * pow ( $building, 2 ) + 200000) * pow ( $t_increase [DEPOT_MANAGEMENT], $tech ) );
                $erg = Lager::size($building, $tech);
                break;
            }
        case OX_DEPOT :
            {
                //$erg = round ( 2 * (4000 * pow ( $building, 2 ) + 80000) * pow ( $t_increase [DEPOT_MANAGEMENT], $tech ) );
                $erg = Lager::sizeOxygen($building, $tech);
                break;
            }
    }
    return $erg;
}

function Silo($building) {
	$erg = round ( SILO_KAPA * (SILO_KAPA_PER_LEVEL / 100 + 100) );
}

// Städteplätze pro Land
function CountrySize($continent, $country) {
    return 5 + (($continent * $continent * $continent * 131 + $country * $country * 763) % 17);
}

// Schutzschildstärke
function Shield($allshields, $tech, $active) {
    return 2000*$active + 100*pow(max(0,($active-1)),2.8);
}

// Zeit für Regeneration einer Schutzschildstufe
function ShieldRegenTime($allshields, $active_shields) {
    // maximal regeneration time 4h
    $time = round(($active_shields+1)/$allshields*3600*4);
    return  $time;
}

// Zeit für Regeneration mehrerer Schildstufen
function ShieldRegenTimeAcc($allshields, $active_shields, $max_active_shields) {
	// für komplizierte Funktionen:
	$time = 0; for ( $i=$active_shields;$i<$max_active_shields;$i++) {
		$time += ShieldRegenTime($allshields, $i);
	} 
	// für die einfache aktuelle Funktion:
	/*$time = ($active_shields + $max_active_shields + 1)/2*
	($max_active_shields - $active_shields) / $allshields *3600*4; */
	return $time;
} 

// Newbieschutz
function NewbieDef($points) {
    if ($points >= 0 && $points < 50)
        return (int) round ( - 2000 * pow ( $points, 2 ) + 5000000 );
    else
        return 0;
}

// Anzahl möglicher Kolonien
function numberOfColonies($kz_level) {
    return floor(sqrt(2 * $kz_level + 0.25) - 0.5);
}

// Handeslzentrumkapazität
function TradeCenterCapacity($step) {
    return floor ( 50 * pow ( $step, 3.2 ) + 150 * ($step) );
}

// Anzeige der Zeit
function ETSZeit($zeit) {
    return date ( "H:i", $zeit ) . "<font class=seconds>:" . date ( "s", $zeit ) . "</font> " . date ( "d.m.Y", $zeit );
}

// Anzeige der Zeit ohne <font>-Tags
function ETSZeit_Plain($zeit) {
    return date ( "H:i:s d.m.Y", $zeit );
}
// Hallo Fehlerchen
// Allinz-Status-Anzeige
function status($var) {
    switch ( $var) {
        case "founder" :
            return "Gründer";
        break;
        case "admin" :
            return "Administrator";
        break;
        case "member" :
            return "Mitglied";
        break; 
    }
}

function AddSlash($array) {
    foreach ( $array as &$value )
        $value = addslashes ( $value );

    return $array;
}

function sonderz_messages_fwd($var) {
		$search = array ("\"","<br>");
		$replace = array ("&quot;","\n");
    $var = str_replace ( $search, $replace, $var );

    return $var;
}

function sonderz($var) {
    $var = str_replace ( "â‚¬", "&euro;", $var );
    $var = str_replace ( "€", "&euro;", $var );

    $var = str_replace ( "\\'", "'", $var );
    $var = str_replace ( "\\\"", "\\\"", $var );
    $var = str_replace ( "\\'", "'", $var );

    $var = str_replace ( "Â´", "´", $var );
    $var = str_replace ( "`", "&acute;", $var );
    $var = str_replace ( "^", "&circ;", $var );

    $var = str_replace ( "Ã¤", "&auml;", $var );
    $var = str_replace ( "Ã„", "&Auml;", $var );
    $var = str_replace ( "Ã¶", "&ouml;", $var );
    $var = str_replace ( "Ã–", "&Ouml;", $var );
    $var = str_replace ( "Ã¼", "&uuml;", $var );
    $var = str_replace ( "Ãœ", "&Uuml;", $var );
    $var = str_replace ( "ÃŸ", "&szlig;", $var );

    $var = str_replace ( "ä", "&auml;", $var );
    $var = str_replace ( "Ä", "&Auml;", $var );
    $var = str_replace ( "ö", "&ouml;", $var );
    $var = str_replace ( "Ö", "&Ouml;", $var );
    $var = str_replace ( "ü", "&uuml;", $var );
    $var = str_replace ( "Ü", "&Uuml;", $var );
    $var = str_replace ( "ß", "&szlig;", $var );

    $var = str_replace ( "Ã¡", "&agrave;", $var );
    $var = str_replace ( "Ã ", "&aacute;", $var );
    $var = str_replace ( "Ã¢", "&acirc;", $var );
    $var = str_replace ( "Ã", "&Agrave;", $var );
    $var = str_replace ( "Ã€", "&Aacute;", $var );
    $var = str_replace ( "Ã‚", "&Acirc;", $var );

    $var = str_replace ( "á", "&agrave;", $var );
    $var = str_replace ( "à", "&aacute;", $var );
    $var = str_replace ( "â", "&acirc;", $var );
    $var = str_replace ( "Á", "&Agrave;", $var );
    $var = str_replace ( "À", "&Aacute;", $var );
    $var = str_replace ( "Â", "&Acirc;", $var );

    $var = str_replace ( "Ã©", "&egrave;", $var );
    $var = str_replace ( "Ã¨", "&eacute;", $var );
    $var = str_replace ( "Ãª", "&ecirc;", $var );
    $var = str_replace ( "Ã‰", "&Egrave;", $var );
    $var = str_replace ( "Ãˆ", "&Eacute;", $var );
    $var = str_replace ( "ÃŠ", "&Ecirc;", $var );

    $var = str_replace ( "é", "&egrave;", $var );
    $var = str_replace ( "è", "&eacute;", $var );
    $var = str_replace ( "ê", "&ecirc;", $var );
    $var = str_replace ( "É", "&Egrave;", $var );
    $var = str_replace ( "È", "&Eacute;", $var );
    $var = str_replace ( "Ê", "&Ecirc;", $var );

    $var = str_replace ( "Ã­", "&igrave;", $var );
    $var = str_replace ( "Ã¬", "&iacute;", $var );
    $var = str_replace ( "Ã®", "&icirc;", $var );
    $var = str_replace ( "Ã", "&Igrave;", $var );
    $var = str_replace ( "ÃŒ", "&Iacute;", $var );
    $var = str_replace ( "ÃŽ", "&Icirc;", $var );

    $var = str_replace ( "í", "&igrave;", $var );
    $var = str_replace ( "ì", "&iacute;", $var );
    $var = str_replace ( "î", "&icirc;", $var );
    $var = str_replace ( "Í", "&Igrave;", $var );
    $var = str_replace ( "Ì", "&Iacute;", $var );
    $var = str_replace ( "Î", "&Icirc;", $var );

    $var = str_replace ( "Ã³", "&ograve;", $var );
    $var = str_replace ( "Ã²", "&oacute;", $var );
    $var = str_replace ( "Ã´", "&ocirc;", $var );
    $var = str_replace ( "Ã“", "&Ograve;", $var );
    $var = str_replace ( "Ã’", "&Oacute;", $var );
    $var = str_replace ( "Ã”", "&Ocirc;", $var );

    $var = str_replace ( "ó", "&ograve;", $var );
    $var = str_replace ( "ò", "&oacute;", $var );
    $var = str_replace ( "ô", "&ocirc;", $var );
    $var = str_replace ( "Ó", "&Ograve;", $var );
    $var = str_replace ( "Ò", "&Oacute;", $var );
    $var = str_replace ( "Ô", "&Ocirc;", $var );

    $var = str_replace ( "Ãº", "&ugrave;", $var );
    $var = str_replace ( "Ã¹", "&uacute;", $var );
    $var = str_replace ( "Ã»", "&ucirc;", $var );
    $var = str_replace ( "Ã", "&Ugrave;", $var );
    $var = str_replace ( "Ã™", "&Uacute;", $var );
    $var = str_replace ( "Ã›", "&Ucirc;", $var );

    $var = str_replace ( "ú", "&ugrave;", $var );
    $var = str_replace ( "ù", "&uacute;", $var );
    $var = str_replace ( "û", "&ucirc;", $var );
    $var = str_replace ( "Ú", "&Ugrave;", $var );
    $var = str_replace ( "Ù", "&Uacute;", $var );
    $var = str_replace ( "Û", "&Ucirc;", $var );

    $var = str_replace ( ">", "&gt;", $var );
    $var = str_replace ( "<", "&lt;", $var );

    $var = str_replace ( "\n", "<br>", $var );
    $var = str_replace ( "\\n", "<br>", $var );

    $var = str_replace ( "&lt;br&gt;", "<br>", $var );

    return $var;
}
/*function sonderz($var) {
    $array = array(
			"â‚¬" => "&euro;", 
			"€" => "&euro;",
			"\\'" => "'",
			"\\\"" => "\\\"",
			"\\'" => "'",
			"Â´" => "´",
			"`" => "&acute;",
			"^" => "&circ;",
			"Ã¤" => "&auml;",
			"Ã„" => "&Auml;",
			"Ã¶" => "&ouml;",
			"Ã–" => "&Ouml;",
			"Ã¼" => "&uuml;",
			"Ãœ" => "&Uuml;",
			"ÃŸ" => "&szlig;",
			"ä" => "&auml;",
			"Ä" => "&Auml;",
			"ö" => "&ouml;",
			"Ö" => "&Ouml;",
			"ü" => "&uuml;",
			"Ü" => "&Uuml;",
			"ß" => "&szlig;",
			"Ã¡" => "&agrave;",
			"Ã " => "&aacute;",
			"Ã¢" => "&acirc;",
			"Ã" => "&Agrave;",
			"Ã€" => "&Aacute;",
			"Ã‚" => "&Acirc;",
			"á" => "&agrave;",
			"à" => "&aacute;",
			"â" => "&acirc;",
			"Á" => "&Agrave;",
			"À" => "&Aacute;",
			"Â" => "&Acirc;",
			"Ã©" => "&egrave;",
			"Ã¨" => "&eacute;",
			"Ãª" => "&ecirc;",
			"Ã‰" => "&Egrave;",
			"Ãˆ" => "&Eacute;",
			"ÃŠ" => "&Ecirc;",
			"é" => "&egrave;",
			"è" => "&eacute;",
			"ê" => "&ecirc;",
			"É" => "&Egrave;",
			"È" => "&Eacute;",
			"Ê" => "&Ecirc;",
			"Ã­" => "&igrave;",
			"Ã¬" => "&iacute;",
			"Ã®" => "&icirc;",
			"Ã" => "&Igrave;",
			"ÃŒ" => "&Iacute;",
			"ÃŽ" => "&Icirc;",
			"í" => "&igrave;",
			"ì" => "&iacute;",
			"î" => "&icirc;",
			"Í" => "&Igrave;",
			"Ì" => "&Iacute;",
			"Î" => "&Icirc;",
			"Ã³" => "&ograve;",
			"Ã²" => "&oacute;",
			"Ã´" => "&ocirc;",
			"Ã“" => "&Ograve;",
			"Ã’" => "&Oacute;",
			"Ã”" => "&Ocirc;",
			"ó" => "&ograve;",
			"ò" => "&oacute;",
			"ô" => "&ocirc;",
			"Ó" => "&Ograve;",
			"Ò" => "&Oacute;",
			"Ô" => "&Ocirc;",
			"Ãº" => "&ugrave;",
			"Ã¹" => "&uacute;",
			"Ã»" => "&ucirc;",
			"Ã" => "&Ugrave;",
			"Ã™" => "&Uacute;",
			"Ã›" => "&Ucirc;",
			"ú" => "&ugrave;",
			"ù" => "&uacute;",
			"û" => "&ucirc;",
			"Ú" => "&Ugrave;",
			"Ù" => "&Uacute;",
			"Û" => "&Ucirc;",
			">" => "&gt;",
			"<" => "&lt;",
			"\n" => "<br>",
			"\\n" => "<br>",
			"&lt;br&gt;" => "<br>",
		);

    return str_replace(array_keys($array),$array,$var);
}*/
function BBCode($input) {
    $input = stripslashes ( $input );
    $input = rtrim ( $input );

    require_once ('include/stringparser.class.php');
    require_once ('include/stringparser_bbcode.class.php');
    require_once ('include/stringparser.functions.php');

    static $bbcode = false;
    if (! $bbcode) {
        $bbcode = new Stringparser_BBcode ( );
//        $bbcode->setRootParagraphHandling ( true );
        $bbcode->addFilter ( STRINGPARSER_FILTER_PRE, 'convertlinebreaks' );
        $bbcode->addParser ( array ('block', 'inline', 'link', 'listitem' ), 'myHtmlspecialchars' );
        $bbcode->addParser ( array ('block', 'inline', 'link', 'listitem' ), 'nl2br' );
        $bbcode->addParser ( 'list', 'bbcode_stripcontents' );
        $bbcode->addCode ( 'p', 'simple_replace', null, array ('start_tag' => '<p>', 'end_tag' => '</p>' ), 'inline', array ('listitem', 'block' ), array ( ) );
        $bbcode->addCode ( 'b', 'simple_replace', null, array ('start_tag' => '<b>', 'end_tag' => '</b>' ), 'inline', array ('listitem', 'block', 'inline', 'link' ), array ( ) );
        $bbcode->addCode ( 'u', 'simple_replace', null, array ('start_tag' => '<u>', 'end_tag' => '</u>' ), 'inline', array ('listitem', 'block', 'inline', 'link' ), array ( ) );
        $bbcode->addCode ( 'i', 'simple_replace', null, array ('start_tag' => '<i>', 'end_tag' => '</i>' ), 'inline', array ('listitem', 'block', 'inline', 'link' ), array ( ) );
        $bbcode->addCode ( 'quote', 'simple_replace', null, array ('start_tag' => '<blockquote>', 'end_tag' => '</blockquote>' ), 'inline', array ('block', 'inline' ), array ( ) );
        $bbcode->addCode ( 'list', 'simple_replace', null, array ('start_tag' => '<ul>', 'end_tag' => '</ul>' ), 'list', array ('listitem', 'block' ), array ( ) );
        $bbcode->addCode ( '*', 'simple_replace', null, array ('start_tag' => '<li>', 'end_tag' => '</li>' ), 'listitem', array ('list' ), array ( ) );
        $bbcode->addCode ( 'code', 'usecontent', 'do_bbcode_code', array ( ), 'inline', array ('block', 'inline' ), array ( ) );
        $bbcode->addCode ( 'img', 'usecontent', 'do_bbcode_img', array ( ), 'image', array ('listitem', 'block', 'inline', 'link' ), array ( ) );
        $bbcode->addCode ( 'url', 'usecontent?', 'do_bbcode_url', array ('usecontent_param' => 'default' ), 'link', array ('listitem', 'block', 'inline' ), array ('link' ) );
        $bbcode->addCode ( 'color', 'callback_replace', 'do_bbcode_color', array ('usecontent_param' => array ('default' ) ), 'inline', array ('listitem', 'block', 'inline' ), array ('link' ) );
        $bbcode->setCodeFlag ( '*', 'closetag', BBCODE_CLOSETAG_OPTIONAL );
        $bbcode->setCodeFlag ( '*', 'paragraphs', true );
        $bbcode->setCodeFlag ( 'list', 'paragraph_type', BBCODE_PARAGRAPH_BLOCK_ELEMENT );
        $bbcode->setCodeFlag ( 'list', 'opentag.before.newline', BBCODE_NEWLINE_DROP );
        $bbcode->setCodeFlag ( 'list', 'closetag.before.newline', BBCODE_NEWLINE_DROP );
    $bbcode->setCodeFlag ( 'color', 'closetag', BBCODE_CLOSETAG_MUSTEXIST );
    }
    return $bbcode->parse ( $input);
}

function maketime($sekunden) {
    $sekunden = round ( $sekunden );
		$stunden = 0;
		$minuten = 0;
		$tage = 0;
		$retval = "";
    if ($sekunden < 0)
        return "0:00:00";

    if ($sekunden >= 60) {
        $minuten = floor ( $sekunden / 60 );
        $sekunden -= 60 * $minuten;
    }
    if ($minuten >= 60) {
        $stunden = floor ( $minuten / 60 );
        $minuten -= 60 * $stunden;
    }
    if ($stunden >= 24) {
        $tage = floor ( $stunden / 24 );
        $stunden -= 24 * $tage;
    }
    if ($tage)
        $retval = ($tage > 1) ? $retval = "$tage Tage " : "$tage Tag ";

    $retval .= sprintf("%02d",$stunden).":".sprintf("%02d",$minuten).":".sprintf("%02d",$sekunden);

    return $retval;
}

/**
 * Merge two arrays. Overwrite the values in array $a using the values in array $b.
 * This function recurses into subarrays.
 * This function is very useful to overwrite a template array with special values
 * from a user request.
 *
 * @param array template array
 * @param array value array
 */
function merge_arrays(&$a, $b) {
    $a = $b+$a;
}

function translate($var) {
    switch ( $var) {
        case "iridium" :
            $retval = "Iridium";
        break;
        case "holzium" :
            $retval = "Holzium";
        break;
        case "water" :
            $retval = "Wasser";
        break;
        case "oxygen" :
            $retval = "Sauerstoff";
        break;
        case "r_iridium" :
            $retval = "Iridium";
        break;
        case "r_holzium" :
            $retval = "Holzium";
        break;
        case "r_water" :
            $retval = "Wasser";
        break;
        case "r_oxygen" :
            $retval = "Sauerstoff";
        break;
    }
    return $retval;
}

function translate_technologies($var) {
    global $t_db_name, $t_name;

    for($i = 0; $i < count($t_db_name); $i ++)
        if ($var == "t_" . $t_db_name [$i])
            return $t_name [$i];
}

function translate_buildings($var) {
    global $b_db_name, $b_name;

    for($i = 0; $i < count($b_db_name); $i ++)
        if ($var == "b_" . $b_db_name [$i])
            return $b_name [$i];
}

function translate_defense($var) {
    global $d_db_name, $d_name;

    for($i = 0; $i < count($d_db_name); $i ++)
        if ($var == "d_" . $d_db_name [$i])
            return $d_name [$i];
}

function translate_planes($var) {
    global $p_db_name_wus, $p_name;

    for($i = 0; $i < count($p_db_name_wus); $i ++)
        if ($var == "p_" . $p_db_name_wus [$i])
            return $p_name [$i];
}

/**
 * Die Funktion initialisiert die Erstellung einer Fehlerseite.
 * Dabei wird zwischen Login- und Sessionfehler unterschieden.
 * Ein Loginfehler umfasst die Eingabe: einer falsche eMail-Adresse
 * bzw. eines falschen Kennwortes oder einen falschen Sicherheitscode.
 * Sessionfehler sind Ereignisse die die Session unerwartet beenden.
 * Hinweise zu den Sessionfehlern werden per String uebergeben.
 *
 * @param string $param0 Ohne Parameter wird die Meldung "E-Mail oder
 *                       Kennwort falsch" an das Template weitergegeben
 * @param string $param1 Enthält die konkrete Fehlermeldung die an das
 *                       Template weitergereicht wird oder das Stichwort:
 *                       captcha, das eine Fehlermeldung zum fehlerhaften
 *                       Sicherheitscode durchreicht
 */
function LoginError() {
    global $etsAddress, $supportEmail;

    //unset what is not needed anymore
    @session_start();
    @session_destroy();

    $use_lib = 20; // MSG_LOGIN_ERROR
    require ("msgs.php");

    // define phptal template
    require_once ("PHPTAL.php");
    require_once ("include/PHPTAL_EtsTranslator.php");
    $template = new PHPTAL ( 'guest/loginError.html' );
    $template->setTranslator ( new PHPTAL_EtsTranslator ( ) );
    $template->setEncoding ( 'ISO-8859-1' );

    // common setting
    require ("config_general.php");
    require_once ("include/TemplateSettingsCommonGuest.php");

    if (func_num_args () == 0 || (func_num_args () == 1 && func_get_arg ( 0 ) == 'captcha')) {
        // set page title for login error
        $template->set ( 'pageTitle', 'Anmeldung - Fehler' );
        if (func_num_args () == 1 && func_get_arg ( 0 ) == 'captcha') {
            $captchaMessage = $MESSAGES [MSG_LOGIN_ERROR] ['m000']; // Sicherheitscode
            $template->set ( 'captchaMessage', $captchaMessage );
        } else {
            $loginMessage = $MESSAGES [MSG_LOGIN_ERROR] ['m001']; // eMail bzw. Kennwort
            $template->set ( 'loginMessage', $loginMessage );
        }
        $template->set ( 'loginError', true );
        $template->set ( 'notLoggedIn', true );
        // $template->set ( 'currentUser', $_SESSION [user] ); // we havn't this information now
    } else {
        // set page title for session error
        $template->set ( 'pageTitle', 'Besuchszeit - Ende' );
        $sessionError = func_get_arg ( 0 );
        $template->set ( 'sessionError', $sessionError );
    }

    // create html page
    try {
        echo $template->execute ();
    } catch ( Exception $e ) {
        echo $e->getMessage ();
    }
}

function strip_magic_slashes($str) {
    return get_magic_quotes_gpc () ? stripslashes ( $str ) : $str;
}

/**
 * Encryption (or decryption) of a single character.
 * Within the given range the character is shifted with the supplied offset.
 *
 * @param   int     Ordinal of input character
 * @param   int     Start of range
 * @param   int     End of range
 * @param   int     Offset
 * @return  string      encoded/decoded version of character
 */
function encryptCharcode($n, $start, $end, $offset) {
    $n = $n + $offset;
    if ($offset > 0 && $n > $end) {
        $n = $start + ($n - $end - 1);
    } else if ($offset < 0 && $n < $start) {
        $n = $end - ($start - $n - 1);
    }
    return chr ( $n );
}

/**
 * Encryption of email addresses for <A>-tags.
 *
 * @param   string      Input string to en/decode: "blabla@bla.com"
 * @param   boolean     If set, the process is reversed, effectively decoding, not encoding.
 * @return  string      encoded/decoded version of $string
 */
function encryptEmail($string, $back = 0) {
    $out = '';
    $len = strlen ( $string );
    $offset = intval ( 6 * ($back ? - 1 : 1) );
    for($i = 0; $i < $len; $i ++) {
        $charValue = ord ( $string {$i} );
        if ($charValue >= 0x2B && $charValue <= 0x3A) { // 0-9 . , - + / :
            $out .= encryptCharcode ( $charValue, 0x2B, 0x3A, $offset );
        } elseif ($charValue >= 0x40 && $charValue <= 0x5A) { // A-Z @
            $out .= encryptCharcode ( $charValue, 0x40, 0x5A, $offset );
        } else if ($charValue >= 0x61 && $charValue <= 0x7A) { // a-z
            $out .= encryptCharcode ( $charValue, 0x61, 0x7A, $offset );
        } else {
            $out .= $string {$i};
        }
    }
    return $out;
}

/**
 */
function emailObfuscate($email) {
    $tmp = explode ( '.', $email );
    $email = join ( '<span>.</span>', $tmp );
    $tmp = explode ( '@', $email );
    $email = join ( '<span>&#x0040;</span>', $tmp );
    return $email;
}

function getEmailLink($email) {
    return '<a href="javascript:linkTo_UnCryptMailto(\'' . encryptEmail ( $email ) . '\');">' . emailObfuscate ( $email ) . '</a>';
}

///////////////////
// Fehlerroutine //
///////////////////


function ErrorMessage() {
    global $MESSAGES;
    static $ERROR;

    switch ( func_num_args ()) {
        case 0 :
            if (strlen ( $ERROR ) > 0)
                return "<ul>\n" . $ERROR . "</ul>\n";
        break;
        case 1 :
            if ($ERROR)
                return true;
        break;
        case 2 :
            $ERROR .= "    <li>" . $MESSAGES [func_get_arg ( 0 )] [func_get_arg ( 1 )] . "</li>\n";
        break;
    }
}

function ErrorMessageException() {
    global $MESSAGES;
    throw new Exception ( $MESSAGES [func_get_arg ( 0 )] [func_get_arg ( 1 )] );
}

/**
 * returns random code.
 *
 */
function getConfirmCode() {
    /* should be improved? ... */
    return md5 ( time () . '-+-' . rand ( 0, 1000 ) );
}

function techCheck($productType, $userTechs, $techNumber, $requirements) {
    for($i = 0; $i < $techNumber; $i ++) {
        //echo "C$i . ".$userTechs[$i] ." . " .$requirements[$productType][$i];
        if ($userTechs [$i] < $requirements [$productType] [$i])
            return FALSE;
    }

    return TRUE;
}


//XXX correct and improve the old doc
// try to estimate the power a user could muster in a military conflict
function computeUserPower($userName) {
    global $p_tech, $d_tech, $t_increase, $p_power, $d_power, $p_duration, $t_db_name, $b_db_name, $p_need, $d_need_techs, $d_need_builds, $p_speed;
    // compute for a town:
    // extraction_per_hour - the resources a town produces per hour (XXX normalize value by trading
    //                       center trade ratio)
    // attack_value        - the attack power of the maximal (theoretically possible) fleet which
    //                       could be started
    // defense_value       - the maximal possible combined defense power of shield and turrets
    // plane_power_regain  - the attack power which could be regained per hour when producing the
    //                       the most yieldingly plane type


//    $log = fopen("/tmp/ets_power.log", "w");
    $get_cities = sql_query ( "SELECT city.city FROM city INNER JOIN userdata ON city.user = userdata.ID WHERE userdata.user='$userName' order by city.b_".$b_db_name[AIRPORT]." DESC" );
    $totalExtraction = 0;
    //$totalHangarSize = 0;
    $totalHangars = 0;
    $totalDef = array();
    $totalAtt = array();
    $totalHangarTimeRatio = array();
    $fighters = array (SPARROW, BLACKBIRD, RAVEN, EAGLE, FALCON, NIGHTINGALE, RAVAGER, DESTROYER );
    $attackValues = array();
    $speeds = array();
    $power = 0;
    $weights = array (1.0, 0.7, 0.3, 0);
    $weight = $weights[0];
    $min_hangar_ratio = 0.1 / $p_duration[DESTROYER];
    $min_speed = 1;
    $cities = 0;
    $unaidedCities = 0;

//    fwrite($log, "name: ".$userName."\n");
    // is that necessary or do I get 0 as default anyway?
    for ($i = 0; $i < sizeof ($fighters); $i++) {
      $totalDef[$fighters[$i]] = 0;
      $totalAtt[$fighters[$i]] = 0;
      $speeds[$fighters[$i]] = $min_speed;
      $totalHangarTimeRatio[$fighters[$i]] = 0;
      $attackValues[$fighters[$i]] = Party::getPlaneKW($p_tech [$fighters [$i]] [T_POWER], $p_power [$fighters [$i]], $t_increase [$p_tech [$fighters [$i]] [T_POWER]], $user_techs ["t_{$t_db_name[$p_tech[$fighters[$i]][T_POWER]]}"]);
//      fwrite($log, "attack values: ".$attackValues[$fighters[$i]]."\n");
    }

    // fetching all technology values of the user
    $get_techs = sql_query ( "SELECT usarios.t_" . implode ( ",usarios.t_", $t_db_name ) . " FROM usarios INNER JOIN userdata ON usarios.ID = userdata.ID WHERE userdata.user='$userName'" );
    $user_techs = sql_fetch_array ( $get_techs );

    while ( $city = sql_fetch_array ( $get_cities ) ) {
      $cities++;
      // ignore defense of cities under newby protection completely
      $unaided = NewbieDef($city[points]) > 0;
      if ($unaided)
      $unaidedCities++;
//      fwrite($log, "*** $city[city] ***\n");
            // fetching all necessary buildings of the town
            $get_buildings = sql_query ( "SELECT b_iridium_mine,b_holzium_plantage,b_water_derrick,b_oxygen_reactor, b_hangar, b_airport, b_shield, c_active_shields, b_defense_center FROM city INNER JOIN userdata ON city.user = userdata.ID WHERE city='$city[city]' && userdata.user='$userName'" );
//            $get_buildings = sql_query ( "SELECT b_iridium_mine,b_holzium_plantage,b_water_derrick,b_oxygen_reactor, b_hangar, b_airport, b_defense_center FROM city WHERE city='$city[city]' && user='$userName'" );
            $buildings = sql_fetch_array ( $get_buildings );

            $extraction = computeExtraction($city, $buildings, $user_techs);
//      fwrite($log, "extraction: $extraction\n");
            $totalExtraction += $extraction;

            //******* evaluating the max fleet attack value
            // determining the maximal fleet size
            $fleet_size = min ( $buildings [b_hangar] * PLANES_PER_LEVEL, $buildings [b_airport] * 5 + $user_techs [t_computer_management] * 3 );
//      fwrite($log, "fleet size: $fleet_size\n");
            // without hangar the planes do not play any role
            if ($buildings [b_hangar]) {
                //$totalHangarSize += $buildings[b_hangar];
                $totalHangars++;
                for($i = 0; $i < sizeof ( $fighters ); $i ++) {
                    // ignore plane type if not available (missing tech)
                    if (techCheck ( $fighters [$i], $user_techs, ANZAHL_TECHNOLOGIEN, $p_need ))
                    {
                      $att = $fleet_size * $attackValues[$fighters [$i]] * $weight;
//      fwrite($log, "att: $att\n");
                      $totalAtt[$fighters [$i]] += $att;
                      if ($weigth > 0)
              $weight = $weights[$cities];
              if ($unaided) {
                $def = $buildings [b_hangar] * PLANES_PER_LEVEL * $attackValues[$fighters[$i]];
//      fwrite($log, "fleet def: $def\n");
                $totalDef[$fighters [$i]] += $def;
              }
                    }
                }
            }

        if ($unaided) {
        //******* evaluating the defense value
        // determining the maximal shield defense value
//        $shield = Shield ( $buildings [b_shield], $user_techs [t_shield_tech], $buildings[c_active_shields]);
        // counting the maximal possible number of turrets
        $turret_number = $buildings [b_defense_center] * TURRETS_PER_LEVEL;
        // determining the most powerful turret type
        $maxDef = 0;
    //      fwrite($log, "shield: $shield\n");
    //      fwrite($log, "turrets: $turret_number\n");
        if ($buildings [b_defense_center]) {
            for($i = 0; $i < ANZAHL_DEFENSIVE; $i ++) {
            // ignore turret type if not available (missing tech)
            if (techCheck ( $i, $user_techs, ANZAHL_TECHNOLOGIEN, $d_need_techs ) && $buildings [b_defense_center] >= $d_need_builds [$i] [DEF_CENTER])
                $maxDef = max ( $maxDef, $d_power [$i] + $t_increase [$d_tech [$i] [T_POWER]] * $user_techs ["t_{$t_db_name[$d_tech[$i][T_POWER]]}"] );
            }
        }
        // multiply the defense value of the most powerful turret by turret number and add shield
    //      fwrite($log, "max turret def: $maxDef\n");
        $defonly = $maxDef * $turret_number;
//        $defonly = $shield + $maxDef * $turret_number;
    //      fwrite($log, "defonly: $defonly\n");
        for($i = 0; $i < sizeof ( $fighters ); $i ++) {
            $totalDef[$fighters [$i]] += $defonly;
    //      fwrite($log, "totaldefonly: ".$totalDef[$fighters [$i]]."\n");
                }
        }
    }
//      fwrite($log, "\n");
    $evenedExtraction = $totalExtraction/$cities;
    $evenedExtraction += $evenedExtraction / 10 * ($cities - 1);
    $extractionFactor = log($evenedExtraction/2000 + 2.5) * 31.25;
//      fwrite($log, "extractionFac: $extractionFactor\n");
    for ($i = 0; $i < sizeof ($fighters); $i++) {
      //$totalHangarTimeRatio[$fighters [$i]] = max($min_hangar_ratio, $totalHangarSize / $p_duration [$fighters [$i]]);
      $totalHangarTimeRatio[$fighters [$i]] = max($min_hangar_ratio, $totalHangars / $p_duration [$fighters [$i]]);
//      fwrite($log, "hangarTime: " . $totalHangarTimeRatio[$fighters [$i]] . "\n");
      if (techCheck ( $fighters [$i], $user_techs, ANZAHL_TECHNOLOGIEN, $p_need ))
      {
        $base_speed = $t_increase[$p_tech[$fighters [$i]][T_SPEED]];
        $tech_speed = $user_techs ["t_{$t_db_name[$p_tech[$fighters[$i]][T_SPEED]]}"];
        $speeds[$fighters [$i]] = $p_speed[$fighters [$i]] +  $base_speed * $tech_speed;
      }
      //$evenedHangarTimeRatio = $totalHangarTimeRatio[$fighters [$i]]/$cities;
      //$evenedHangarTimeRatio += $evenedHangarTimeRatio / 10 * ($cities - 1);
      $evenedHangarTimeRatio = $totalHangarTimeRatio[$fighters [$i]];
//      fwrite($log, "speed: ".$speeds[$fighters [$i]]."\n");
//      fwrite($log, "xatt: ".$totalAtt[$fighters [$i]]."\n");
//      fwrite($log, "xdef: ".$totalDef[$fighters [$i]]."\n");
      $xpower=($totalAtt[$fighters [$i]] + $totalDef[$fighters [$i]] / $unaidedCities * 0.2)
        * pow($evenedHangarTimeRatio, 0.5)
        * $extractionFactor
        * pow($speeds[$fighters [$i]], 0.5);
//      fwrite($log, "xpower: $xpower\n");
      $power = max($power, $xpower);
//      fwrite($log, "maxxpower: $power\n");
    }
    $power = pow($power, 1/3);

//      fwrite($log, "power: $power\n");
//      fwrite($log, "power: ".round($power)."\n");
//    fclose($log);
    return ceil($power);
}

function computeExtraction($city, $buildings, $user_techs/*, $log*/)
{
  $tmpLager = new Lager ( $city [city] );

//      fwrite($log, "xcity: ".$city [city]."\n");
//      fwrite($log, "xir: ".$buildings [IR_MINE]."\n");
//      fwrite($log, "xho: ".$buildings [HZ_PLANTAGE]."\n");
//      fwrite($log, "xsa: ".$buildings [OX_REACTOR]."\n");
//      fwrite($log, "mi: ".$user_techs [t_mining]."\n");
  //******* evaluating the resource production per hour
  $extraction [IRIDIUM] = round ( Foerderung ( IRIDIUM, $buildings [IR_MINE], $user_techs [t_mining] ) );
  $extraction [HOLZIUM] = round ( Foerderung ( HOLZIUM, $buildings [HZ_PLANTAGE], $user_techs [t_mining] ) );
  // water is of no use in its own, just a traversal resource to oxygen
  //$extraction[WATER]    = round(Foerderung(WATER,$buildings[WA_DERRICK],0));
  $extraction [OXYGEN] = round ( Foerderung ( OXYGEN, $buildings [OX_REACTOR], $user_techs [t_water_compression], $tmpLager->getWater (), $buildings [WA_DERRICK] ) );
  // just adding the extractions of all types
  $extraction_per_hour = $extraction [IRIDIUM] + $extraction [HOLZIUM] + /*$extraction[WATER] +*/ $extraction [OXYGEN];
//      fwrite($log, "exi: ".$extraction [IRIDIUM]."\n");
//      fwrite($log, "exh: ".$extraction [HOLZIUM]."\n");
//      fwrite($log, "exo: ".$extraction [OXYGEN]."\n");
//      fwrite($log, "exX: $extraction_per_hour\n");

  unset ( $tmpLager );

  return $extraction_per_hour;

}

function resetCaptchaBlock($email) {
    $sql = "UPDATE userdata SET
                    user_captcha_blocked = 'no',
                    user_captcha_wrong_counter = 0,
                    user_captcha_last_try = 0
                WHERE email = '" . htmlspecialchars ( $email, ENT_QUOTES ) . "'";
    sql_query ( $sql );
}

function markCaptchaWrongForUser($email, $password) {
    $blocked = false;
    $md5_password = $password . $email . "B3stBr0ws3rg4m33v3r";
    $md5_password = md5($md5_password);
    $md5_password = md5($md5_password);
    $md5_password = md5($md5_password);
    
    $login_info = sql_query ( "SELECT * FROM userdata WHERE email='" . addslashes(htmlspecialchars ( $email, ENT_QUOTES )) . "' && password='$md5_password'" );
    $login_detail = sql_fetch_array ( $login_info );

    if (sql_num_rows ( $login_info )) {
        //nach zehn min darf man wieder ;)
        if (time () - $login_detail ['user_captcha_last_try'] > 10 * 60) {
            resetCaptchaBlock ( $email );
            $login_info = sql_query ( "SELECT * FROM userdata WHERE email='" . addslashes(htmlspecialchars ( $email, ENT_QUOTES )) . "' && password='$md5_password'" );
            $login_detail = sql_fetch_array ( $login_info );
        }
        $tries = 1 + $login_detail['user_captcha_wrong_counter'];
        if ($tries > 5) {
            //block user
            $blocked = true;
        }

        //if blocked - stay blocked
        if ($login_detail ['user_captcha_blocked'] == 'yes') {
            $blocked = true;
        }


        $sql = "UPDATE userdata SET
                        user_captcha_blocked = '".($blocked?'yes':'no')."',
                        user_captcha_wrong_counter = $tries,
                        user_captcha_last_try = ".time()."
                        WHERE email = '".htmlspecialchars($email,ENT_QUOTES) ."'";
        sql_query ( $sql );
        return $blocked;
    } else {
        return false;
    }
}

// fame = fame_own + if alliance_member alliance_fame_own / members
function recompute_user_fame($user_name) {
  sql_query("UPDATE usarios INNER JOIN userdata ON usarios.ID = userdata.ID SET fame=fame_own+if(alliance<>'',(SELECT FLOOR(fame_own/members) FROM alliances WHERE tag=alliance),0) WHERE userdata.user='$user_name'");
}

// recompute user fame for all alliance members
function recompute_user_fame_for_alliance($alliance_tag) {
  sql_query("UPDATE usarios SET fame=fame_own+(SELECT FLOOR(fame_own/members) FROM alliances WHERE tag='$alliance_tag') WHERE alliance='$alliance_tag'");
}

// fame = fame_own + sum member_fame_own
function recompute_alliance_fame($alliance_tag) {
  sql_query("UPDATE alliances SET fame=fame_own+(SELECT sum(fame_own) FROM usarios WHERE alliance='$alliance_tag') WHERE tag='$alliance_tag'");

}

function TIMESTAMP() {
    return @filemtime('css/main.css');
}

// compute probability in percent that attacker wins the battle (0% - 100%)
function attacker_victory_probability($att_value, $def_value) {
    return 1.0 / ( 1.0 + pow($def_value/$att_value, 1.5) ) * 100;
}

/**
 * Find a free coordinate for a new city using a default parameter set for the current era.
 */
function get_new_standard_coordinates() {
    return get_new_coordinates(1, 2, 1, 2, 20, 30);
}

/**
 * Find a free coordinate in a country with low city density on one of the given continents.
 * A city will never be placed diectly besides an other one.
 * If there is no place found on the given continents the alternative continents are used. Make sure
 * there will be enough space left. Otherwise the function will not return.
 * This is used for finding the coordinate for a new settler on earth II.
 * @param int $cont_from left most continent of range
 * @param int $cont_to right most continent of range
 * @param int $alt_cont_from left most continent of alternative range
 * @param int $alt_cont_to right most continent of alternative range
 * @param int $switch_turn maximum number of search turns before switching to alternative continents
 * @param int $consider_lands consider population density of that number of lands
 * @return an array with the 3 coordinates - continent, land, city
 */
function get_new_coordinates($cont_from, $cont_to, $alt_cont_from, $alt_cont_to, $switch_turn, $consider_lands) {

    // true while searching for coordinates
    $searching_coordinates = true;
    // counter of search turns
    $turn = 0;

    while ($searching_coordinates) {
        $turn++;
        // switch to alternative continents
        if ($turn == $switch_turn) {
            $cont_from = $alt_cont_from;
            $cont_to = $alt_cont_to;
            $turn = 1;
        }
        $x = 0;
        $y = 0;

        // max density is 1 (X settlers in X city slots), min is 0 (no settlers there)
        $min_density = 1;
        for ($i = 0; $i < $consider_lands; $i++) {
            // randomly determine a continent
            $continent = rand($cont_from, $cont_to);
            // randomly determine a land
            $land = mt_rand(1,MAX_COUNTRY);
            // get number of settlements in that land
            $get_count_settlers = sql_query("SELECT count(*) FROM city WHERE x_pos='$continent' and y_pos='$land'");
            list( $count_settlers ) = sql_fetch_row($get_count_settlers);
            // compute settlers per land
            $density = $count_settlers / CountrySize($continent, $land);
//            if ($density > 0)
//                echo "density $continent:$land: $density\n";
            // record if population density is less than in previous checks
            if ($density < $min_density) {
                $min_density = $density;
                $x = $continent;
                $y = $land;
            }
        }
        if (!$x)
            continue;

        $z = get_city_slot($x, $y);
//        echo "city slot " . $z . "\n";
        if ($z != -1) {
            $searching_coordinates = false;
        }
    }
    return array($x, $y, $z);
}

/**
 * Find a free city slot in the given land on the given continent
 * @param int $x continent
 * @param int $y land
 * @return the position of the city; -1 if none is found
 */
function get_city_slot($x, $y) {
    $z = -1;
    // values range from 1 to max_slot
    $get_used_slot = sql_query("SELECT z_pos FROM city WHERE x_pos='$x' and y_pos='$y'");
    // index range from 0 to max_slot
    $used_slots = array();
    while ($used_slot = sql_fetch_row($get_used_slot)) {
        // index 0 is always false - do not access
        $used_slots[$used_slot[0]] = true;
    }
    $max_slot = CountrySize($x, $y);
//    echo "x:y:(max) - $x:$y:($max_slot)\n";
    $start_slot =  mt_rand(1,$max_slot);
    // if slot not used and not target -> choose and break
    for ($i = 0; $i < $max_slot; $i++) {
        // value range from 1 to max_slot
        $z = (($i + $start_slot -1) % ($max_slot)) + 1;
        // check if slot is free and its neighboring slots too
        if (!isset($used_slots[$z])
                && ($z > 0 ? !isset($used_slots[$z - 1]) : true)
                && ($z < $max_slot ? !isset($used_slots[$z + 1]) : true)) {
            // check for settlers on its way
            $check_settlers = sql_query("SELECT 1 FROM actions WHERE f_target='$x:$y:$z'");
            if (!sql_num_rows($check_settlers)) {
                break;
            }
        }
        $z = -1;
    }
    return $z;
}

?>
