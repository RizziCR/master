<?php
  require_once("database.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/ets.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Spielgeschichte');

  $pfuschOutput = "";

    $pfuschOutput .= "<h1>Spielgeschichte</h1>

            <table align=center border=0 cellpadding=3 cellspacing=3>
            <tr>
                <td align=left>
                    Die Geschichte von ETS ist eine dunkle ihrer Art. Der
                    Mensch hatte es tats&auml;chlich geschafft binnen weniger
                    Jahrzehnte seine Umwelt zu zerst&ouml;ren. Gedr&auml;ngt durch
                    Habgier, selbstzerst&ouml;rerischen Egoismus und das
                    Machtspiel zwischen den Regierungen einzelner Weltm&auml;chte,
                    kam es am 05.11.2017 zu einer globalen Katastrophe.
                    Nachfolgend ein Bericht aus dem Tagebuch eines Funkers
                    der 23. Kompanie Frankfurt / Hessen.<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    <u>\"07.November 2017</u><br>
                    ... Die Bunker haben die Nacht gehalten, der Feind hat
                    uns noch nicht entdeckt. Wir sind ersch&ouml;pft, werden
                    hier aber noch ein paar Monate &uuml;berstehen
                    k&ouml;nnen, die Vorr&auml;te sind ausreichend. Der
                    Kontakt zur Erdoberfl&auml;che ist abgebrochen,
                    wei&szlig; der Teufel, ob jemand den Angriff
                    &uuml;berlebt hat. Ich glaube aber nicht. Die nukleare
                    Verseuchung und die toxische Atmosph&auml;re hat allem
                    Leben sicher den Rest gegeben... was haben wir nur
                    getan?<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    <u>12.November 2017</u><br>
                    Unsere Techniker haben es tats&auml;chlich geschafft
                    andere &Uuml;berlebende &uuml;ber Erdwellenfunksignale
                    zu erreichen. Derzeit versuchen sie so schnell wie
                    m&ouml;glich das strategische Netzwerk wieder aufzubauen,
                    um unsere Satelliten zu erreichen.<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    <u>14.November 2017</u><br>
                    Das Netzwerk steht. Der Kanzler hat den Gegenschlag
                    angeordnet. Heute gegen 18:15 Uhr ist es soweit. Der
                    Angriff richtet sich gegen alle feindlichen Gebiete.
                    Mehr als 280 nukleare Sprengk&ouml;pfe stehen in Silos
                    bereit.<br>
                    Nachtrag: Aufkl&auml;rungsbilder zeigen: der
                    Angriff ist gegl&uuml;ckt. Gegnerische Funkspr&uuml;che
                    sind verstummt... wir haben es getan, so wie sie es uns
                    angetan haben.<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    <u>17.November 2017</u><br>
                    Der Befehlsstab hat eine Kommission einberufen zur
                    Kl&auml;rung der Sachlage. Im Ergebnis der Beratung
                    wurde die Evakuierung der Erde angeordnet, was innerhalb
                    eines Zeitraumes von 10 Monaten durchzuf&uuml;hren ist.
                    Als neue Heimat kommt einzig ein Planet der M-Klasse im
                    55 Cancri Sonnensystem mit dem Namen V7-L17MB in Frage.
                    Die Atmosph&auml;re ist zwar noch d&uuml;nn, aber kann
                    laut Experten binnen Jahren erd&auml;hnlich mit
                    Sauerstoff, Stickstoff und Kohlenstoffdioxid angereichert
                    werden. Obwohl der Planet um ein vielfaches
                    gr&ouml;&szlig;er ist, betr&auml;gt die Gravitationskraft
                    1,05 G und ist somit fast wie hier.<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    ...<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    <u>3. September 2018</u><br>
                    Der Konvoi startet heute. 20 000 &Uuml;berlebende werden
                    in Raumschiffen eine Reise von 3 Jahren antreten. Um die
                    psychischen Folgen so gering wie m&ouml;glich zu halten
                    werden wir in einen Tiefschlaf versetzt und eingefroren.
                    Die geplante Ankunftszeit ist der 14. Oktober 2021 um 8:12
                    Uhr Erdzeit.<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    <u>10. Oktober 2021</u><br>
                    Die ersten sind aufgewacht. Es ist ein Gef&uuml;hl als ob
                    man einen Kater hat, man f&uuml;hlt sich ganz schlapp und
                    der Kopf brummt. Schaue ich mich im Spiegel an so sehe ich
                    mich genauso wie vor der Reise, &auml;u&szlig;erlich jung,
                    aber innerlich schon sehr gealtert. In 4 Tagen erreichen
                    wir die Umlaufbahn, es gibt noch viel zu tun bis dahin.<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    <u>14. Oktober 2021</u><br>
                    Es ist einfach unbeschreiblich, ich f&uuml;hle mich wie
                    Kolumbus h&ouml;chstpers&ouml;nlich. Unser Ziel gibt mir
                    Kraft, wir sind nun Pioniere auf einem fremden Planeten der
                    unsere neue Erde werden soll. Wir nennen Ihn schon jetzt
                    Earth II.\"<br><br>
                </td>
            </tr>
            <tr>
                <td align=left>
                    Nach und nach treffen auch die anderen Raumschiffe ein,
                    jedes verf&uuml;gt &uuml;ber eine eigenst&auml;ndige
                    Kolonisationseinheit, so dass jeweils aus einem einzigen
                    Schiff eine eigene Stadt emporw&auml;chst. Anfangs
                    verbreitete sich unter den &Uuml;berlebenden der lang
                    erhoffte Glauben auf ewigen Frieden und eine bessere Zukunft,
                    doch schon jetzt ist ein Anstieg der Konkurrenz zwischen den
                    Kolonien zu verzeichnen. Die Spannungen wachsen - und es ist
                    nur eine Frage der Zeit, wann der n&auml;chste Krieg ausbricht.
                </td>
            </tr>
            </table>";

 // end specific page logic


  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
