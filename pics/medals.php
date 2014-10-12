<?php
header("Content-Type: image/png");


//Konstanten
require_once("constants.php");
//$max_size = 500;
$offset_symbol = 200;
$offset_number = 20;

$number = "numbers/";
$medal = "medals/";
$symbol = "symbols/";

//Abfrage was für ein Bild es sein soll:
if ((intval($_GET[nr]) <= 10) && (intval($_GET[nr]) >= 1)) {
	$number .= intval($_GET[nr]) . ".png";
} else {
	$number .= "default.png";
}

/*if (isset($_GET[medal], $_GET[symbol])) {
	$medal = $_GET[medal];
	$symbol = $_GET[symbol];
} else {
	$medal = "default";
	$symbol = "default";
}*/

switch ($_GET[medal]) {
	case "war":	$medal .= "war.png";
		break;
	case "economy":	$medal .= "economy.png";
		break;
	case "alliance":	$medal .= "alliance.png";
		break;
	default:	$medal .= "default.png";
		break;
}

if (in_array($_GET[symbol], $medaillen)) {
		$symbol .= $_GET[symbol] . ".png";
	}
else {
		$symbol .= "default.png";
	}

//Medaille erstellen.
$picture_medal = imageCreateFromPNG($medal);
imageAlphaBlending($picture_medal, true);
imageSaveAlpha($picture_medal, true);

//Hier kommt nun die große Frage, ob #01 golden und #02 silbern sein soll?
if ((intval($_GET[gold]) == 1) || (intval($_GET[silver]) == 1)) {
	$picture_addon = "";
	if (intval($_GET[silver]) == 1) {
		$picture_addon = imageCreateFromPNG("medals/silver.png"); }
	if (intval($_GET[gold]) == 1) {
		$picture_addon = imageCreateFromPNG("medals/gold.png"); }
	imageCopy($picture_medal, $picture_addon, 0,0, 0,0, imagesx($picture_addon), imagesy($picture_addon));
	imageDestroy($picture_addon);
}


//Symbol hinzufügen
$picture_symbol = imageCreateFromPNG($symbol);
$position = imagesy($picture_medal) - imagesy($picture_symbol) - $offset_symbol;
imageCopy($picture_medal, $picture_symbol, round((imagesx($picture_medal)-imagesx($picture_symbol))/2), $position, 0, 0, imagesx($picture_symbol), imagesy($picture_symbol));
//Zahl hinzufügen
$picture_number = imageCreateFromPNG($number);
$position = imagesy($picture_medal) - imagesy($picture_number) - $offset_number;
imageCopy($picture_medal, $picture_number, round((imagesx($picture_medal)-imagesx($picture_number))/2), $position, 0, 0, imagesx($picture_number), imagesy($picture_number));

//Größe berechnen, wenn Eingabe
if ((intval($_GET[size])>0) && (intval($_GET[size])<=MAX_IMG_SIZE)) {
	$width = intval($_GET[size]);
	$height = round($width / imagesx($picture_medal) * imagesy($picture_medal));
	
	$final = imageCreateTrueColor($width, $height);
	imageAlphaBlending($final, false);
	$transparancy = imagecolorallocate($final, 0,0,0); //angeblich war mal eine 0 zu viel.
	imagefill($final, 0,0, $transparancy);
	imageSaveAlpha($final, true);
	imageCopyResized($final, $picture_medal, 0,0, 0,0, $width, $height, imagesx($picture_medal), imagesy($picture_medal));
	
	//imagePng($final);
	//imageDestroy($final);
} else {
	$final = $picture_medal;
}
imagePng($final);
imageDestroy($final);
imageDestroy($picture_medal);
imageDestroy($picture_symbol);
imageDestroy($picture_number);
?>