<?php
  require_once("database.php");
  $unset_all_cookies = "YES";
  require_once("do_loop.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/handylogin.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Handy-Anmeldung');

  /*generate the captcha text */
  require_once('include/captcha_text.php');
  $captcha = new captcha();
  $_SESSION['captchaString'] = $captcha->fetchString();
  $_SESSION['captchaTime'] = time();
  $_SESSION['captchaSeen']   = false;

  if(isset($_COOKIE['keeplogindata'])) {
      $keepLoginData = ' checked="checked" ';
      $data = base64_decode($_COOKIE['keeplogindata']);
      $data = split("###",$data);
      if(count($data) == 2) {
          $identity = $data[0];
          $credential = $data[1];
          $keepLoginData = ' checked="checked" ';
      }
  }

  $pfuschOutput = "";

  $pfuschOutput .= "  <h1>Erde II betreten - Handy-Anmeldung</h1>

      <form action=\"../start.php\" method=post>
      <table align=center border=0 cellpadding=2 cellspacing=2>
      <tr>
        <td colspan=2 bgcolor=\"#444444\" align=center>
          News
        </td>
      </tr>
      <tr>
        <td align=left colspan=2>
          <ul>";

  $get_admin_login_msgs = sql_query("SELECT color,text,time FROM admin_login_msgs WHERE toshow='Y' ORDER BY time DESC LIMIT 3");
  while ($admin_login_msgs =sql_fetch_array($get_admin_login_msgs))
    $pfuschOutput .= "<li style=\"color:$admin_login_msgs[color]\">[". date("d.m.Y",$admin_login_msgs[time]) ."] $admin_login_msgs[text]</li>";

  $pfuschOutput .= "        <li style=\"color:#00FF00\"><a href=\"$etsAddress/page/history.php\">News-Archiv</a></li>
          </ul>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan=2 bgcolor=\"#444444\" align=center>
          Erde II betreten
        </td>
      </tr>
      <tr valign=top>
        <td>
          <br />
          <table border=0 cellpadding=0 cellspacing=0>
          <tr>
            <td align=right>
              E-Mail-Adresse:&nbsp;
            </td>
            <td align=right>
              <input type=text name=email class=button value=".$identity.">
            </td>
          </tr>
          <tr>
            <td align=right>
              Kennwort:&nbsp;
            </td>
            <td align=right>
              <input type=password name=spwd class=button value=".$credential.">
            </td>
          </tr>


          <tr>
            <td>&nbsp;</td>
            <td align=right>
                <br/>
               <img id=captchacode src=$etsAddress/page/captcha.php?handy=1&x=".time()." border=1 style=\"border-color:white\"/>
            </td>
          </tr>

          <tr style=\"margin-top:20px;height:50px;\">
            <td align=right>
                Sicherheitscode:&nbsp;

            </td>
            <td align=right>
            <input type=hidden name=handy value=1/>
            <input type=text name=captcha class=button>
            </td>
          </tr>
          <tr>
              <td align=right>
                  Logindaten merken:&nbsp;
              </td>
              <td align=right>
                  <input type=checkbox name=keeplogindata value=1 ".$keepLoginData."/>
              </td>
          </tr>
          <tr>
            <td colspan=2 align=right>
              <input class=button type=submit value=\"Jetzt betreten!\">
            </td>
          </tr>
          </table>
        </td>
        <td align=left>
          <ul style=\"color:#FF0000;font-size:8pt\">
            <li>Die Betreiber von »ETS« fragen nie nach deinem Kennwort.</li>
            <li>Spielbetreuung gibt es nie innerhalb des Spiels und ausschlie&szlig;lich &uuml;ber die Seiten von $etsName</li>
            <li>Anmeldungen &uuml;ber Fremdportale sind nicht sicher!</li>
          </ul>
          <br/>

        </td>
      </tr>
      </form>
      <tr>
        <td colspan=2 align=center>
          <a href=javascript:regenerateCaptcha();>Sicherheitscode nicht lesbar?</a>&nbsp;&nbsp;&nbsp;
          <a href=\"$dir/page/password.php\">Kennwort vergessen?</a>&nbsp;&nbsp;&nbsp;
          <a href=\"$dir/page/code.php\">Freischaltcode nicht erhalten?</a>&nbsp;&nbsp;&nbsp;
          <a href=\"$etsAddress/page/login.php\">normale Anmeldung (alternativer Sicherheitscode)</a>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan=2 bgcolor=\"#444444\" align=center>
          Projekte der Gemeinschaft
        </td>
      </tr>
      <tr>
        <td align=left>
          Planungen und Berichte der<br /><a style=\"font-size:130%\" href=\"http://forum.escape-to-space.de/viewforum.php?f=48\">ETS-Sommercamps</a>
          <br /><br />
          Bilder vom<br /><a style=\"font-size:130%\" href=\"http://goldobert.net/ets-hh/index.php?cat=7\">ETS-Sommercamp 2009</a>
        </td>
        <td align=right>
          <!--a href=\"http://forum.escape-to-space.de/viewforum.php?f=49\"><img src=\"$etsAddress/pics/gewinnspiel.jpg\" width=\"200pt\" alt=\"ETS-Gewinnspiel\" title=\"Hier kann jeder gewinnen\" /></a-->
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan=2 bgcolor=\"#444444\" align=center>
          &raquo;Erfworld: Die Schlacht um Gobwin Knob&laquo;
        </td>
      </tr>
      <tr>
        <td colspan=2 align=left>
          <br />
	    &raquo;Ende des Spiels? Ja.&laquo;<br />
	    &raquo;Ende des Traumes? Nein.&laquo;<br />
	    &raquo;Immer wenn ich fluche, erinnerst du mich daran: Du kontrollierst mich.&laquo;<br />
	    &raquo;Also wer hat das hier getan? Du? Oder ich?&laquo;<br />
	    &raquo;Ich werde keine Spielfigur sein, hörst du mich?&laquo;<br />
	    &raquo;Ich bin ein Spieler!&laquo;<br />
          <br><br>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=right>
          Rob Balder und Jamie Noguchi &raquo;Erfworld: The Battle for Gobwin Knob&laquo;
        </td>
      </tr>
      </table>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
