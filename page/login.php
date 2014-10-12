<?php
  require_once("database.php");
  $unset_all_cookies = "YES";
  require_once("do_loop.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/login.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Erde II betreten');

  /* generate the captcha circle */
  require_once('include/captcha.php');
  $captcha = new captcha();
  $_SESSION['captchaCircle'] = $captcha->makeRandomCircle();
  $_SESSION['captchaTime']   = time();
  $_SESSION['captchaSeen']   = false;

  $pfuschOutput = "";

  $pfuschOutput .= "<div id='fb-root'></div>
						<script>(function(d, s, id) {
  							var js, fjs = d.getElementsByTagName(s)[0];
  							if (d.getElementById(id)) return;
  							js = d.createElement(s); js.id = id;
  							js.src = '//connect.facebook.net/de_DE/sdk.js#xfbml=1&version=v2.0';
  							fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>

  		<h1>Erde II betreten</h1>

      <form name=\"loginform\" type=\"email\" action=\"../start.php\" method=\"post\" onSubmit=\"return submitLoginForm(false);\">
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
    $pfuschOutput .= "<li style=\"color:$admin_login_msgs[color]\">[". date("d.m.Y",$admin_login_msgs['time']) ."] $admin_login_msgs[text]</li>";

  $pfuschOutput .= "        <li><a href=\"$etsAddress/page/history.php\">News-Archiv</a></li>
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
              <input type=email name=email class=button>
            </td>
          </tr>
          <tr>
            <td align=right>
              Kennwort:&nbsp;
            </td>
            <td align=right>
              <input type=password name=spwd class=button>
            </td>
          </tr>

          <tr>
            <td align=right valign=top>
            <br/>
                Klicke in den geschlossenen Kreis:&nbsp;
            </td>
            <td align=right>
            <br/>";
  #$pfuschOutput .= "~~~~~ Rundenende ~~~~~";
  $pfuschOutput .= "
              <input type=\"submit\" name=\"submit\" value=\"submit\" style=\"width;0px;height:0px;border:0px;position:absolute;top:-1000px;left:-1000px;\"/>
           <input onclick=\"submitLoginForm(true);\" type=\"image\" name=\"captcha\" id=\"captchacode\" src=\"$etsAddress/page/captcha.php?x=".time()."\"/>";
  $pfuschOutput .= "           </td>
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
          <a href=javascript:regenerateCaptcha();>Kreis nicht erkennbar?</a>&nbsp;&nbsp;&nbsp;
          <a href=\"$etsAddress/page/password.php\">Kennwort vergessen?</a>&nbsp;&nbsp;&nbsp;
          <a href=\"$etsAddress/page/code.php\">Freischaltcode nicht erhalten?</a>&nbsp;&nbsp;&nbsp;
          <a href=\"$etsAddress/page/handylogin.php\">Handyanmeldung (alternativer Sicherheitscode)</a>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan=2 bgcolor=\"#444444\" align=center>
          Stimme jetzt für dein Spiel
        </td>
      </tr>
      <tr>
        <td colspan=2>
            <div id=\"blockVote\" id=\"voteButtons\" tal:condition=\"exists: enable_voteButtons\">
            	<a href='http://de.mmofacts.com/escape-to-space-19#track' target='_blank'><img 
            		src='$etsAddress/pics/vote_mmofacts.png' border='0' alt='MMOFacts'></a>
            	<a href='http://www.gamessphere.de/vote/vote_609.html' target='_blank'><img 
            		src='$etsAddress/pics/vote_gamessphere.gif' border='0' alt='GameSphere'></a>
            	<a href='http://game-toplist.de/vote/ETSGame.php' target='_blank'><img 
            		src='$etsAddress/pics/vote_gametoplist.png' border='0' alt='GameToplist'></a>
            	<br>	
            	<div class='fb-like' data-href='https://www.facebook.com/pages/Escape-to-Space/433241360114681' data-layout='button_count' data-action='like' data-show-faces='false' data-share='false'></div>
                <g:plusone></g:plusone>
            </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan=2 bgcolor=\"#444444\" align=center>
          &raquo;Erfworld: Liebe ist ein Schlachtfeld&laquo;
        </td>
      </tr>
      <tr>
        <td colspan=2 align=left>
          <br />
          Schicksal ist unausweichlich, Kriegsherr.<br />
          Doch unser Pfad zu ihm ist es nicht.<br />
          Erst müssen wir unsere Bestimmung erkennen, dann erfüllen.<br />
          Leben heißt Leiden. Unser Schicksal ist unsere einzige Befreiung.
          <br><br>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=right>
          Robert T. Balder und Xin Ye &raquo;Erfworld: Love Is A Battlefield&laquo;
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
