<?php 
  $use_lib = 26; // MSG_MESSAGES

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('messages.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Flugzeitrechner');
  $pfuschOutput = "";

    if (ErrorMessage(0))
  {
    $errorMessage .= "  <tr>
          <td colspan=3 align=center>";
    $errorMessage .= ErrorMessage();
    $errorMessage .= "    </td>
        </tr>";

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
  }  
$pfuschOutput .= '
<script type="text/javascript"> 
<!--
function twist(erstes) 
{
var zweites = erstes-1;
var n = document.getElementsByName("n["+erstes+"]")[0].value;
var an = document.getElementsByName("ankunft["+erstes+"]")[0].value;
var z = document.getElementsByName("zeit["+erstes+"]")[0].value;
var ga = document.getElementsByName("gankunft["+erstes+"]")[0].value;
document.getElementsByName("n["+erstes+"]")[0].value = document.getElementsByName("n["+zweites+"]")[0].value;
document.getElementsByName("ankunft["+erstes+"]")[0].value = document.getElementsByName("ankunft["+zweites+"]")[0].value;
document.getElementsByName("zeit["+erstes+"]")[0].value = document.getElementsByName("zeit["+zweites+"]")[0].value;
document.getElementsByName("gankunft["+erstes+"]")[0].value = document.getElementsByName("gankunft["+zweites+"]")[0].value;

document.getElementsByName("n["+zweites+"]")[0].value = n;
document.getElementsByName("ankunft["+zweites+"]")[0].value = an;
document.getElementsByName("zeit["+zweites+"]")[0].value = z;
document.getElementsByName("gankunft["+zweites+"]")[0].value = ga;
}
function funk(e) 
{
if(e.value.substr(e.value.length-1,1)==" ") 
{ 
e.value = e.value.substr(0,e.value.length-1)+":"; 
} 
} 
//--> 
</script>
<!-- An dieser Stelle möchten wir Taba danken das er uns seinen Flugzeitrechner zur Verfügung stellt. Danke.-->
<center>
<a href="flugtimer.php">Flugzeitrechner</a>&nbsp;|&nbsp;<a href="flugtimer_flug.php">R&uuml;ckflugrechner</a>&nbsp;|&nbsp;<a href="flugtimer_ruf.php">R&uuml;ckrufrechner</a><br><br>
<font size="1">Eingabe im Format hh:mm:ss. Leerzeichen werden bei der Eingabe durch : ersetzt, wodurch eine schnellere Eingabe möglich ist (Dazu wird JavaScript benötigt).</font><br><br>
<font size=2 color=RED>Dieser Flugzeit-Rechner dient zur Berechnung der Zeit, zu der die Flotte zur Start-Stadt zur&uuml;ckkehrt</font><br><br>
<form name="form1" method="post" action="flugtimer_ruf.php">
<table>
 <tr>
  <td valign="middel"><p>Notiz:</p></td>
  <td valign="middel" colspan="1"><p>Ankunftszeit:</p></td>
  <td valign="middel" colspan="1"><p>Gesamte Flugzeit:</p></td>
  <td valign="middel" colspan="1"><p>Rückkehrzeit:</p></td>
 </tr>';
$felder = $_POST['felder'];
if($felder != "") 
{
	$delete = sql_query("DELETE FROM flugtimer WHERE user = '$_SESSION[user]' AND town = '$_SESSION[city]'");
	$doit = 1;
}
else
{
	$select = sql_query("SELECT ID, ankunftszeit, flugzeit, rueckzeit, notizen FROM flugtimer WHERE art = '3' AND user = '$_SESSION[user]' AND town = '$_SESSION[city]' ORDER BY `ID` ASC");
	$y=0;
	while($row = sql_fetch_array($select))
	{
		$n[$y] = $row['notizen'];
		$zeit[$y] = $row['flugzeit'];
		$ankunft[$y] = $row['ankunftszeit'];
		$gankunft[$y] = $row['rueckzeit'];
		$y++;
		if($row['notizen'] == "" && $row['flugzeit'] == "" && $row['ankunftszeit'] == "" && $row['rueckzeit'] == "") $delete = sql_query("DELETE FROM flugtimer WHERE user = '$_SESSION[user]' AND art = '3' AND town = '$_SESSION[city]' && ID = '$y'");
	}
}
$x = 0;
if ($felder == "") { $felder = "5"; }
if ($felder > "50") { $felder = "50"; $pfuschOutput .= "<p>Maximal 50 Felder erlaubt</p><br>"; }
while( $x < $felder)
{
	if($doit == 1)
	{
		$n = $_POST['n'];
		$zeit = $_POST['zeit'];
		$ankunft = $_POST['ankunft'];
		$gankunft = $row['gankunft'];
	}
	
$xx = $x + 1;

if ( $zeit == "" ) { $zeit = "00:00:00"; }
$pfuschOutput .= "<tr>
  <td><input value='$n[$x]' type='text' id='input' name='n[$x]' size='20'></td>
  <td><input value='$ankunft[$x]' type='text' id='input' name='ankunft[$x]' size='6' onkeyup='funk(this);'></td>
  <td><input value='$zeit[$x]' type='text' id='input' name='zeit[$x]' size='6' onkeyup='funk(this);'></td>
  <td><input value='$gankunft[$x]' type='text' id='input' name='gankunft[$x]' size='6' onkeyup='funk(this);'></td>";
if(0<$x && $x != $felder - 1){

$pfuschOutput .= '<td>
<table cellpadding=0 cellspacing=1><tr><td><a href="#" onclick="twist(\''.$x.'\')"><img src="pics/timer_up.gif"></a></td></tr><tr><td><a href="#" onclick="twist(\''.$xx.'\')"><img src="pics/timer_down.gif"></a></td></tr></table></td></tr>';
}
if($x == "0"){
$pfuschOutput .= '<td>
<table cellpadding=0 cellspacing=1><tr><td></td></tr><tr><td><a href="#" onclick="twist(\''.$xx.'\')"><img src="pics/timer_down.gif"></a></td></tr></table></td></tr>';
}
if($x == $felder - 1){

$pfuschOutput .= '<td>
<table cellpadding=0 cellspacing=1><tr><td><a href="#" onclick="twist(\''.$x.'\')"><img src="pics/timer_up.gif"></a></td></tr><tr><td></td></tr></table></td></tr>';
}
$x++;
}

$pfuschOutput .= "
  <tr>
    <td>Anzahl Felder:</td>
    <td><input value='$felder' type='text' name='felder' size='1'></td>
  </tr></table>
  <p>
    <input type='submit' name='submit' value='Startzeit Berechnen'>
  </p>
</form>
<table border='1' Frame='void' bordercolor='#D26900' style='border-collapse: collapse'>
 <tr>
  <td valign='middel' width=50><p>Notiz:</p></td>
  <td valign='middel' ><p>Ankunftszeit</p></td>
  <td valign='middel' ><p>R&uuml;ckrufzeit</p></td>
 </tr>";

$x=0;
while(isset( $_POST['n'][$x] )){
$n = $_POST['n'][$x];
$zeit = $_POST['zeit'][$x];
$ankunft = $_POST['ankunft'][$x];	
$gankunft = $_POST['gankunft'][$x];

$ankunftz = explode(':',$ankunft);
$st = $ankunftz[0];
$mi = $ankunftz[1];
$sek = $ankunftz[2];

$zeitz = explode(':',$zeit);
$stz = $zeitz[0];
$miz = $zeitz[1];
$sekz = $zeitz[2];

$gankunftz = explode(':',$gankunft);
$stg = $gankunftz[0];
$mig = $gankunftz[1];
$sekg = $gankunftz[2];


 $sts = $st - $stz;
 
 $ms = $mi - $miz;
 
 $ss = $sek - $sekz;
 
 if( $ss < "0") { $ss = $ss + 60; $ms = $ms - 1; }
 if( $ms < "0") { $ms = $ms + 60; $sts = $sts - 1; }
 while( $sts < "0") { $sts = $sts + 24; }
 
 $stz = $stg - $sts;
 
 $mz = $mig - $ms;
 
 $sz = $sekg - $ss;
 
 if( $sz < "0") { $sz = $sz + 60; $mz = $mz - 1; }
 if( $mz < "0") { $mz = $mz + 60; $stz = $stz - 1; }
 while( $stz < "0") { $stz = $stz + 24; }

$sta = $stz / 2;
$explodestringst = explode('.',$sta);
$stz = $explodestringst[0];
$stzk = $explodestringst[1];

$mia = $mz / 2;
$explodestringm = explode('.',$mia);
$miz = $explodestringm[0];
$mizk = $explodestringm[1];

$seka = $sz / 2;
$explodestrings = explode('.',$seka);
$sekz = $explodestrings[0];
$sekzk = $explodestrings[1];

if ( $stzk != "" ) { $miz = $miz + ($stzk * 6); }
if ( $mizk != "" ) { $sekz = $sekz + ($mizk * 6); }
if ( $sekzk != "" ) { $Warnung = "<font color=RED>Ungenau</font>";} else { $Warnung = "<font color=green>Genau</font>";}

 $st = $stz + $sts;
 
 $m = $miz + $ms;
 
 $s = $sekz + $ss;

 if( $s >= "60") { $s = $s - 60; $m = $m + 1; }
 if( $m >= "60") { $m = $m - 60; $st = $st + 1; }
  while( $st < "0") { $st = $st + 24; }

 
if ( $st < "10" ) { $st = '0'.$st; }
if ( $m < "10" ) { $m = '0'.$m; }
if ( $s < "10" ) { $s = '0'.$s; }

# $warning = 1 --> Ungenau
# $warning = 2 --> Genau


	$y = $x+1;
	### Save new data
	$save = sql_query("INSERT INTO `flugtimer` (`ID`, `user`, `town`, `art`, `ankunftszeit`, `flugzeit`, `rueckzeit`, `notizen`) VALUES ($y, '$_SESSION[user]', '$_SESSION[city]', '3', '" . addslashes( $ankunft ) . "', '" . addslashes( $zeit ) . "', '" . addslashes( $gankunft ) . "', '" . addslashes( $n ) . "')");

$pfuschOutput .= "
 <tr>
  <td>$n</td>
  <td align='center'>$ankunft</td>
  <td align='center'>$st:$m:$s</td>
  <td>$Warnung</td>
 </tr>";

 $st = "";
 
$x++;
}
  $pfuschOutput .= "</table></center>";
 
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