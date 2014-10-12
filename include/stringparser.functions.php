<?php
/**
 * sollte eventuell nochmal angepasst werden / deckt nur das gröbste ab :\
 *
 */
define('URL_REGEX','/^([http|https|ftp|mailto|irc]+[:\/\/]+){0,1}[A-Za-z0-9\-_]+[A-Za-z0-9\.\/%&=\?\-_~,]+$/');

function do_bbcode_url ($action, $attributes, $content, $params, $node_object) {
    if ($action == 'validate') {
        if(isset($attributes['default'])) {
            $result = preg_match(URL_REGEX,html_entity_decode($attributes['default']));
        } else {
            $result = preg_match(URL_REGEX,html_entity_decode($content));
        }
        return $result;
    }
    
    if (!isset ($attributes['default'])) {
        return '<a target="_blank" href="'.htmlspecialchars(html_entity_decode($content)).'">'.$content.'</a>';
    }
    
    return '<a target="_blank" href="'.htmlspecialchars(html_entity_decode($attributes['default'])).'">'.$content.'</a>';
}

// Funktion zum Einbinden von Bildern
function do_bbcode_img ($action, $attributes, $content, $params, $node_object) {
    if ($action == 'validate') {
        return preg_match(URL_REGEX,$content);
    }
    return '<img border="0" src="'.htmlspecialchars(html_entity_decode($content)).'" alt="Bild" />';
}

function do_bbcode_color ($action, $attributes, $content, $params, $node_object) {
    if ($action == 'validate') {
        return true;
    }
    return '<span style="color: '.$attributes['default'].'">'.$content.'</span>';
}

// Function to return code
function do_bbcode_code ($action, $attributes, $content, $params, $node_object) {
	if ($action == 'validate') {
		return true;
	}
	$temp_str = $content;
	$temp_str = str_replace( '<br />', chr(10), $temp_str );
	$temp_str = str_replace( chr(10).chr(10), chr(10), $temp_str );
	$temp_str = str_replace( chr(32), '&nbsp;', $temp_str );
	$temp_str = htmlspecialchars(html_entity_decode($temp_str));

	return '<pre>'.$temp_str.'</pre>';
}

// Zeilenumbrüche verschiedener Betriebsysteme vereinheitlichen
function convertlinebreaks ($text) {
	return preg_replace ('/\015\012|\015|\012|<br>|<br\s*\/>/', "\n", $text);
}

// Alles bis auf Neuezeile-Zeichen entfernen
function bbcode_stripcontents ($text) {
	return preg_replace ("/[^\n]/", '', $text);
}

// Eigene Methode, um den Default-Wert utf-8 fuer das Encoding zu umgehen.
function myHtmlspecialchars($text) {
	return '<!-- '.$text.' -->'.htmlspecialchars($text, ENT_COMPAT | ENT_HTML401 , 'ISO-8859-15');
}
