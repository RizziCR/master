<?php
  /******************************************************

  Array-Indizes => [CONST][t001]
                    |      ||
                    |      ||
                    Einsatzort
                           ||
                           ||
                           Typ: message / error
                            |
                            |
                            ID-Nummer

  *******************************************************/

  ///////////////////////////////
  // Definition der Konstanten //
  ///////////////////////////////

  define("MSG_GENERAL",0);
  define("MSG_AIRPORT",1);
  define("MSG_TRADE_CENTER",2);
  define("MSG_STARTSEITE",3);
  define("MSG_HANGAR",4);
  define("MSG_DEF_CENTER",5);
  define("MSG_SIMULATION",6);
  define("MSG_ADMINISTRATION",7);
  define("MSG_ALLIANCELIST",8);
  define("MSG_MESSAGES",9);
  define("MSG_ALLIANCES",10);
  define("MSG_SEARCH",11);
  define("MSG_REGISTER",12);
  define("MSG_IP_REGISTER",13);
  define("MSG_WORK_BOARD",14);
  define("MSG_INFORMATION",15);
  define("MSG_TECH_CENTER",16);
  define("MSG_PREMISES",17);
  define("MSG_MSGCTR",18);
  define("MSG_ADRESSBOOK",19);
  define("MSG_LOGIN_ERROR",20);
  define("MSG_CHRONICLE",21);
  define("MSG_WAR_STATUS",22);
  define("MSG_ACC_STAT",23);
  define("MSG_DESC",24);
  define("MSG_REPORT",25);
  define("MSG_TIMER",26);


  $MESSAGES = null;
  $MESSAGES = array();


  ///////////////
  // Allgemein //
  ///////////////

  $MESSAGES[MSG_GENERAL]['m000'] = "Iridium";
  $MESSAGES[MSG_GENERAL]['m001'] = "Holzium";
  $MESSAGES[MSG_GENERAL]['m002'] = "Wasser";
  $MESSAGES[MSG_GENERAL]['m003'] = "Sauerstoff";

  $MESSAGES[MSG_GENERAL]['m004'] = "BB-Code";
  $MESSAGES[MSG_GENERAL]['m005'] = "fett: [b]text[/b]&nbsp;&nbsp;&nbsp;kursiv: [i]text[/i]&nbsp;&nbsp;&nbsp;unterstrichen: [u]text[/u]<br>Link: [url=http://link]text[/url] oder [url]http://link[/url]<br>Bild: [img]http://link.zum/bild[/img]";

  $MESSAGES[MSG_GENERAL]['m006'] = "Plünderung:";
  $MESSAGES[MSG_GENERAL]['m007'] = "Punkte";
  $MESSAGES[MSG_GENERAL]['m008'] = "Überbracht:";
  $MESSAGES[MSG_GENERAL]['m009'] = "Die Flugzeuge konnten nicht überbracht werden, da der Hangar nicht genügend freie Plätze hat.";
  
  $MESSAGES[MSG_GENERAL]['m010'] = "Achtung: Diese Stadt ist Kriegsgebiet!";
  $MESSAGES[MSG_GENERAL]['m011'] = "Achtung: Der Besitzer dieser Stadt ist im Urlaub!";
  $MESSAGES[MSG_GENERAL]['m012'] = "Achtung: Diese Stadt ist im Noobschutz!";
  
  $MESSAGES[MSG_GENERAL]['m013'] = "ETS wird gerade gewartet. In wenigen Minuten geht es weiter.";
  $MESSAGES[MSG_GENERAL]['m014'] = "Im Simulator eintragen";
  
  $MESSAGES[MSG_GENERAL]['e000'] = "Die Funktion ist für Vertreter gesperrt";



  switch($use_lib)
  {
    case MSG_AIRPORT :
      $MESSAGES[MSG_AIRPORT]['m000'] = "Flughafen";
      $MESSAGES[MSG_AIRPORT]['m001'] = "Informationen";
      $MESSAGES[MSG_AIRPORT]['m002'] = "Maximale Flottengröße";
      $MESSAGES[MSG_AIRPORT]['m003'] = "Flugzeuge";
      $MESSAGES[MSG_AIRPORT]['m004'] = "Simulation";
      $MESSAGES[MSG_AIRPORT]['m005'] = "Zum Kampf-Simulator";
      $MESSAGES[MSG_AIRPORT]['m006'] = "verfügbar";
      $MESSAGES[MSG_AIRPORT]['m007'] = "Keine Flugzeuge vorhanden";
      $MESSAGES[MSG_AIRPORT]['m008'] = "Ziel";
      $MESSAGES[MSG_AIRPORT]['m009'] = "Dauer";
      $MESSAGES[MSG_AIRPORT]['m010'] = "Entfernung";
      $MESSAGES[MSG_AIRPORT]['m011'] = "Treibstoffverbrauch";
      $MESSAGES[MSG_AIRPORT]['m012'] = "Geschwindigkeit";
      $MESSAGES[MSG_AIRPORT]['m013'] = "Ladekapazität";
      $MESSAGES[MSG_AIRPORT]['m014'] = "Einheiten";
      $MESSAGES[MSG_AIRPORT]['m015'] = "Optionen";
      $MESSAGES[MSG_AIRPORT]['m016'] = "Angreifen";
      $MESSAGES[MSG_AIRPORT]['m017'] = "Plündern: Reihenfolge:";
      $MESSAGES[MSG_AIRPORT]['m018'] = "Spionieren (Spionagesonde)";
      $MESSAGES[MSG_AIRPORT]['m019'] = "Kolonisieren/Erobern (Settler/Scarecrow - maximal";  // 019 + 020
      $MESSAGES[MSG_AIRPORT]['m020'] = "Kolonien)";
      $MESSAGES[MSG_AIRPORT]['m021'] = "Rohstoffe transportieren";
      $MESSAGES[MSG_AIRPORT]['m022'] = "Flugzeuge verschenken";
      $MESSAGES[MSG_AIRPORT]['m023'] = "Flottenname (optional):";
      $MESSAGES[MSG_AIRPORT]['m024'] = "Flotte starten";
      $MESSAGES[MSG_AIRPORT]['m025'] = "Flotte erfolgreich gestartet";
      $MESSAGES[MSG_AIRPORT]['m026'] = "Zusammenfassung";
      $MESSAGES[MSG_AIRPORT]['m027'] = "Ladung";
      $MESSAGES[MSG_AIRPORT]['m028'] = "Typ";
      $MESSAGES[MSG_AIRPORT]['m029'] = "Stadt angreifen";
      $MESSAGES[MSG_AIRPORT]['m030'] = "Plündern";
      $MESSAGES[MSG_AIRPORT]['m031'] = "Spionieren";
      $MESSAGES[MSG_AIRPORT]['m032'] = "Kolonisieren/Erobern";
      $MESSAGES[MSG_AIRPORT]['m033'] = "Rohstoffe transportieren";
      $MESSAGES[MSG_AIRPORT]['m034'] = "Alle Hangar-Bauaufträge bei <i>erfolgreicher</i> Eroberung abbrechen";
      $MESSAGES[MSG_AIRPORT]['m035'] = "Alle Flotten bei <i>erfolgreicher</i> Eroberung abstürzen lassen";
      $MESSAGES[MSG_AIRPORT]['m036'] = "Flottenname beim Empfänger anzeigen";
      $MESSAGES[MSG_AIRPORT]['m037'] = "Flugzeuge verschenken";
      $MESSAGES[MSG_AIRPORT]['m038'] = "Koordinaten nicht belegt";
      $MESSAGES[MSG_AIRPORT]['m039'] = "Alle Transport-Flugzeuge im Hangar bei <i>erfolgreicher</i> Eroberung eliminieren";
      $MESSAGES[MSG_AIRPORT]['m040'] = "Noch startbare Flugzeuge";
      $MESSAGES[MSG_AIRPORT]['m041'] = "Durch drücken des Buttons 'A' wird das Flugzeug bis zur maximalen Menge gefüllt.";

      $MESSAGES[MSG_AIRPORT]['e000'] = "Du musst erst einen Flughafen bauen, um diese Funktion nutzen zu können";
      $MESSAGES[MSG_AIRPORT]['e001'] = "Bitte wähle eine Aktion aus";
      $MESSAGES[MSG_AIRPORT]['e002'] = "Bitte wähle ein gültiges Ziel aus";
      $MESSAGES[MSG_AIRPORT]['e003'] = "Der Besitzer der Stadt befindet sich im Urlaub oder ist gesperrt";
      $MESSAGES[MSG_AIRPORT]['e004'] = "Bitte gebe eine eindeutige Plünderreihenfolge an";
      $MESSAGES[MSG_AIRPORT]['e005'] = "Dein Kommunikationszentrum kann nicht so viele Kolonien verwalten";
      $MESSAGES[MSG_AIRPORT]['e006'] = "Du hast nicht genügend Flugzeuge";
      $MESSAGES[MSG_AIRPORT]['e007'] = "Cheat-Versuche werden bestraft!";
      $MESSAGES[MSG_AIRPORT]['e008'] = "Du hast nicht genügend Flugzeuge";
      $MESSAGES[MSG_AIRPORT]['e009'] = "Du kannst nicht soviele Flugzeuge verschicken";
      $MESSAGES[MSG_AIRPORT]['e010'] = "Du hast nicht genügend Rohstoffe";
      $MESSAGES[MSG_AIRPORT]['e011'] = "Deine Flugzeuge haben nicht genügend Kapazität";
      $MESSAGES[MSG_AIRPORT]['e012'] = "Du hast nicht genügend Treibstoff";
/*      $MESSAGES[MSG_AIRPORT]['e013'] => im Flughafen bestimmt */
      $MESSAGES[MSG_AIRPORT]['e014'] = "Als Sitter können Sie keine Transportflotten zu anderen Spielern schicken.";
      $MESSAGES[MSG_AIRPORT]['e015'] = "Vertreter dürfen nicht mit den eigenen Städten interagieren";
      $MESSAGES[MSG_AIRPORT]['e016'] = "Vertreter dürfen nicht mit den Städten, die sie verwalten, interagieren";
      $MESSAGES[MSG_AIRPORT]['e017'] = "Vertreter dürfen keine Sonden verschicken";
      $MESSAGES[MSG_AIRPORT]['e018'] = "Hauptstädte unter 100 Punkten dürfen keine Transportflotten starten";
      $MESSAGES[MSG_AIRPORT]['e019'] = "Die Zielstadt hat noch keinen Hangar und ist somit unangreifbar";
      $MESSAGES[MSG_AIRPORT]['e020'] = "Transportflugzeuge können nicht in Angriffsflotten mitfliegen";
	  $MESSAGES[MSG_AIRPORT]['e021'] = "Angriffe sind erst ab 50 Stadtpunkten möglich";
	  $MESSAGES[MSG_AIRPORT]['e022'] = "Du kannst nicht so weit weg Kolonisieren/Erobern";
      break;

    case MSG_TRADE_CENTER :
      $MESSAGES[MSG_TRADE_CENTER]['m000'] = "Handelszentrum";
      $MESSAGES[MSG_TRADE_CENTER]['m001'] = "Informationen";
      $MESSAGES[MSG_TRADE_CENTER]['m002'] = "Im Hauptlager vorhanden";
      $MESSAGES[MSG_TRADE_CENTER]['m003'] = "Du kannst mit Waren im Wert von maximal";  // 003 + 004
      $MESSAGES[MSG_TRADE_CENTER]['m004'] = "Rohstoff-Einheiten handeln.";
//      $MESSAGES[MSG_TRADE_CENTER]['m005'] = "<a href=\"http://www.playbid.de\" target=_blank><img src=\"http://ba014ged.edis.at/Auktion/banner/mini-b-1.gif\" border=0><br>Rohstoffe & Flotten<br>ersteigern & versteigern</a>";
      $MESSAGES[MSG_TRADE_CENTER]['m006'] = "Du gibst";
      $MESSAGES[MSG_TRADE_CENTER]['m008'] = "Du bekommst";
      $MESSAGES[MSG_TRADE_CENTER]['m010'] = "Handelskurs";
      $MESSAGES[MSG_TRADE_CENTER]['m011'] = "verfügbar";
      $MESSAGES[MSG_TRADE_CENTER]['m012'] = "Platz für";
      $MESSAGES[MSG_TRADE_CENTER]['m013'] = "Treibstoffverbrauch";
      $MESSAGES[MSG_TRADE_CENTER]['m014'] = "Dauer";
      $MESSAGES[MSG_TRADE_CENTER]['m015'] = "Handeln";
      $MESSAGES[MSG_TRADE_CENTER]['m016'] = "Handelskurse";
      $MESSAGES[MSG_TRADE_CENTER]['m017'] = "Du musst erst ein Handelszentrum bauen, bevor du hier handeln kannst";
      $MESSAGES[MSG_TRADE_CENTER]['m018'] = "Transporter sind nur nötig für reinen Rohstoffhandel";
      $MESSAGES[MSG_TRADE_CENTER]['m019'] = "Kurse anzeigen:";
      $MESSAGES[MSG_TRADE_CENTER]['tradeButton'] = "Handel abschließen";

      $MESSAGES[MSG_TRADE_CENTER]['e000'] = "Du hast in dieser Stadt kein Handelszentrum gebaut";
      $MESSAGES[MSG_TRADE_CENTER]['e001'] = "Du hast nicht genügend Flugzeuge";
      $MESSAGES[MSG_TRADE_CENTER]['e002'] = "Du musst eine Warenmenge grösser als 0 angeben";
      $MESSAGES[MSG_TRADE_CENTER]['e003'] = "Bitte wähle 2 verschiedene Waren";
      $MESSAGES[MSG_TRADE_CENTER]['e004'] = "In Deinen Flugzeugen ist nicht genug Platz";
      $MESSAGES[MSG_TRADE_CENTER]['e005'] = "Du kannst nicht mit so vielen Waren handeln";
      $MESSAGES[MSG_TRADE_CENTER]['e006'] = "Im Hauptlager sind nicht genügend Rohstoffe";
      $MESSAGES[MSG_TRADE_CENTER]['e007'] = "Du hast nicht genügend Rohstoffe";
      $MESSAGES[MSG_TRADE_CENTER]['e008'] = "Du hast nicht genügend Treibstoff";
      $MESSAGES[MSG_TRADE_CENTER]['e009'] = "Dein Lager ist nicht gross genug";
      $MESSAGES[MSG_TRADE_CENTER]['e010'] = "Im Hauptlager sind nicht genügend Flugzeuge";
      $MESSAGES[MSG_TRADE_CENTER]['e011'] = "In Deinem Hangar ist nicht genügend Platz";
      $MESSAGES[MSG_TRADE_CENTER]['e012'] = "Es ist ein Fehler aufgetreten";
      $MESSAGES[MSG_TRADE_CENTER]['e013'] = "Dein Tank ist nicht gross genug";
      $MESSAGES[MSG_TRADE_CENTER]['e014'] = "Der Gegenwert dafür wäre nach aktuellem Kurs 0";
      $MESSAGES[MSG_TRADE_CENTER]['noTransports'] = "Du brauchst Transportflugzeuge für den Rohstoffhandel";
      $MESSAGES[MSG_TRADE_CENTER]['invalidTransports'] = "Gib eine Transporteranzahl grösser als 0 an";
      break;

    case MSG_HANGAR :
      $MESSAGES[MSG_HANGAR]['m000'] = "Hangar";
      $MESSAGES[MSG_HANGAR]['m001'] = "Bauen";
      $MESSAGES[MSG_HANGAR]['m002'] = "Abbrechen";
      $MESSAGES[MSG_HANGAR]['m003'] = "Verschrotten";
      $MESSAGES[MSG_HANGAR]['m004'] = "Informationen";
      $MESSAGES[MSG_HANGAR]['m005'] = "Stationierte Flugzeuge in";
      $MESSAGES[MSG_HANGAR]['m006'] = "Flugzeuge in Bau";
      $MESSAGES[MSG_HANGAR]['m007'] = "Flugzeuge unterwegs";
      $MESSAGES[MSG_HANGAR]['m008'] = "Flugzeuge gesamt";
      $MESSAGES[MSG_HANGAR]['m009'] = "Maximale Anzahl der Flugzeuge";
      $MESSAGES[MSG_HANGAR]['m010'] = "Aktuelle Aufträge";
      $MESSAGES[MSG_HANGAR]['m011'] = "Auftrag";
      $MESSAGES[MSG_HANGAR]['m012'] = "Fertig in";
      $MESSAGES[MSG_HANGAR]['m013'] = "Ja";
      $MESSAGES[MSG_HANGAR]['m014'] = "Kampf-Flugzeuge";
      $MESSAGES[MSG_HANGAR]['m015'] = "Handels-Flugzeuge";
      $MESSAGES[MSG_HANGAR]['m016'] = "Bedarf";
      $MESSAGES[MSG_HANGAR]['m017'] = "Bauzeit";
      $MESSAGES[MSG_HANGAR]['m018'] = "Möchtest du diese Flugzeuge wirklich verschrotten?";
      $MESSAGES[MSG_HANGAR]['m019'] = "Spezielle Kampf-Flugzeuge";
      $MESSAGES[MSG_HANGAR]['m020'] = "Alle Abbrechen";
      $MESSAGES[MSG_HANGAR]['m021'] = "Möchtest du wirklich alle im Bau befindlichen Flugzeuge abbrechen?";

      $MESSAGES[MSG_HANGAR]['e000'] = "Du musst erst einen Hangar bauen, um diese Funktion nutzen zu können";
      $MESSAGES[MSG_HANGAR]['e001'] = "Du besitzt nicht den ausreichenden Forschungsstand, um diese(n) Flugzeugtype(n) bauen zu können";
      $MESSAGES[MSG_HANGAR]['e002'] = "Dein Hangar bietet nicht genug Platz für soviele Flugzeuge";
      $MESSAGES[MSG_HANGAR]['e003'] = "Du hast nicht genügend Rohstoffe";
      $MESSAGES[MSG_HANGAR]['e004'] = "Ungültige Menge";

      break;

    case MSG_DEF_CENTER :
      $MESSAGES[MSG_DEF_CENTER]['m000'] = "Verteidigungszentrum";
      $MESSAGES[MSG_DEF_CENTER]['m001'] = "Bauen";
      $MESSAGES[MSG_DEF_CENTER]['m002'] = "Abbrechen";
      $MESSAGES[MSG_DEF_CENTER]['m003'] = "Abreissen";
      $MESSAGES[MSG_DEF_CENTER]['m004'] = "Informationen";
      $MESSAGES[MSG_DEF_CENTER]['m005'] = "Vorhandene Defensivanlagen in";
      $MESSAGES[MSG_DEF_CENTER]['m006'] = "Maximale Anzahl der Defensivanlagen";
      $MESSAGES[MSG_DEF_CENTER]['m007'] = "Aktuelle Aufträge";
      $MESSAGES[MSG_DEF_CENTER]['m008'] = "Auftrag";
      $MESSAGES[MSG_DEF_CENTER]['m009'] = "Fertig in";
      $MESSAGES[MSG_DEF_CENTER]['m010'] = "Ja";
      $MESSAGES[MSG_DEF_CENTER]['m011'] = "Woofer";
      $MESSAGES[MSG_DEF_CENTER]['m012'] = "Sequenzer";
      $MESSAGES[MSG_DEF_CENTER]['m013'] = "Bedarf";
      $MESSAGES[MSG_DEF_CENTER]['m014'] = "Bauzeit";
      $MESSAGES[MSG_DEF_CENTER]['m015'] = "Defensivanlagen in Bau";
      $MESSAGES[MSG_DEF_CENTER]['m016'] = "Defensivanlagen gesamt";
      $MESSAGES[MSG_DEF_CENTER]['m017'] = "Möchten Sie diese Defensivanlagen wirklich abreißen?";
      $MESSAGES[MSG_DEF_CENTER]['m018'] = "Alle abbrechen";
      $MESSAGES[MSG_DEF_CENTER]['m019'] = "Möchten Sie wirklich alle im Bau befindlichen Defensivanlagen abbrechen?";

      $MESSAGES[MSG_DEF_CENTER]['e000'] = "Du musst erst ein Verteidigungszentrum bauen, um diese Funktion nutzen zu können";
      $MESSAGES[MSG_DEF_CENTER]['e001'] = "Sie besitzen nicht einen ausreichenden Forschungsstand, um diese Verteidigungsanlage(n) bauen zu können";
      $MESSAGES[MSG_DEF_CENTER]['e002'] = "Ihr Verteidigungszentrum bietet nicht genug Platz für soviele Verteidigungsanlagen";
      $MESSAGES[MSG_DEF_CENTER]['e003'] = "Sie haben nicht genügend Rohstoffe";
      $MESSAGES[MSG_DEF_CENTER]['e004'] = "Sie besitzen kein ausreichend ausgebautes Verteidigungszentrum, um diese Verteidigungsanlage(n) bauen zu können";
      $MESSAGES[MSG_DEF_CENTER]['e005'] = "Ungültige Menge";

      break;

    case MSG_SIMULATION :
      $MESSAGES[MSG_SIMULATION]['m000'] = "Kampf-Simulator";
      $MESSAGES[MSG_SIMULATION]['m001'] = "Angreifer";
      $MESSAGES[MSG_SIMULATION]['m002'] = "Verteidiger";
      $MESSAGES[MSG_SIMULATION]['m003'] = "Ergebnis";
      $MESSAGES[MSG_SIMULATION]['m004'] = "Kampfstärke";
      $MESSAGES[MSG_SIMULATION]['m005'] = "Siegeschancen (pro Kampf-Einheit)";
      $MESSAGES[MSG_SIMULATION]['m006'] = "Flugzeuge";
      $MESSAGES[MSG_SIMULATION]['m007'] = "Defensivanlagen";
      $MESSAGES[MSG_SIMULATION]['m008'] = "Technologien";
      $MESSAGES[MSG_SIMULATION]['m009'] = "Sonstiges";
      $MESSAGES[MSG_SIMULATION]['m010'] = "Punkte";
      $MESSAGES[MSG_SIMULATION]['m011'] = "Schutzschild";
      $MESSAGES[MSG_SIMULATION]['m012'] = "Berechnen";
      $MESSAGES[MSG_SIMULATION]['m013'] = "Zurücksetzen";
      $MESSAGES[MSG_SIMULATION]['m014'] = "Zurück zum Flughafen";

      break;

    case MSG_ADMINISTRATION :
      $MESSAGES[MSG_ADMINISTRATION]['m000'] = "Verwaltung";
      $MESSAGES[MSG_ADMINISTRATION]['m001'] = "Die Daten wurden gespeichert";
      $MESSAGES[MSG_ADMINISTRATION]['m002'] = "Einstellungen";
      $MESSAGES[MSG_ADMINISTRATION]['m003'] = "Stadt-Name";
      $MESSAGES[MSG_ADMINISTRATION]['m004'] = "Info-Text";
      $MESSAGES[MSG_ADMINISTRATION]['m005'] = "Bild";
      $MESSAGES[MSG_ADMINISTRATION]['m006'] = "Speichern";

      $MESSAGES[MSG_ADMINISTRATION]['e000'] = "Bitte geben Sie einen Namen an";
      $MESSAGES[MSG_ADMINISTRATION]['e001'] = "&quot; und ' können in Stadtnamen nicht verwendet werden";

      break;

    case MSG_ALLIANCELIST :
      $MESSAGES[MSG_ALLIANCELIST]['m000'] = "Allianzliste";
      $MESSAGES[MSG_ALLIANCELIST]['m001'] = "Durchschnitt";
      $MESSAGES[MSG_ALLIANCELIST]['m002'] = "Allianz";
      $MESSAGES[MSG_ALLIANCELIST]['m003'] = "Grösse";
      $MESSAGES[MSG_ALLIANCELIST]['m004'] = "Mitglieder";
      $MESSAGES[MSG_ALLIANCELIST]['m005'] = "Mitgliederliste";
      $MESSAGES[MSG_ALLIANCELIST]['m006'] = "zeigen";
      $MESSAGES[MSG_ALLIANCELIST]['m007'] = "Siedler";
      $MESSAGES[MSG_ALLIANCELIST]['m008'] = "Stadt";
      $MESSAGES[MSG_ALLIANCELIST]['m009'] = "Grösse";

      $MESSAGES[MSG_ALLIANCELIST]['e000'] = "Bitte wählen Sie eine Allianz";

      break;

    case MSG_MESSAGES :
      $MESSAGES[MSG_MESSAGES]['m000'] = "Messaging-Center öffnen";
      $MESSAGES[MSG_MESSAGES]['m001'] = "Nachrichten";
      $MESSAGES[MSG_MESSAGES]['m002'] = "Neue Nachricht verfassen";
      $MESSAGES[MSG_MESSAGES]['m003'] = "";
      $MESSAGES[MSG_MESSAGES]['m004'] = "Berichte";
      $MESSAGES[MSG_MESSAGES]['m005'] = "Ereignisse";
      $MESSAGES[MSG_MESSAGES]['m006'] = "";
      $MESSAGES[MSG_MESSAGES]['m007'] = "";
      $MESSAGES[MSG_MESSAGES]['m008'] = "";
      $MESSAGES[MSG_MESSAGES]['m009'] = "";
      $MESSAGES[MSG_MESSAGES]['m010'] = "";
      $MESSAGES[MSG_MESSAGES]['m011'] = "";
      $MESSAGES[MSG_MESSAGES]['m012'] = "";
      $MESSAGES[MSG_MESSAGES]['m013'] = "Menü";
      $MESSAGES[MSG_MESSAGES]['m014'] = "";
      $MESSAGES[MSG_MESSAGES]['m015'] = "";
      $MESSAGES[MSG_MESSAGES]['m016'] = "";
      $MESSAGES[MSG_MESSAGES]['m017'] = "";
      $MESSAGES[MSG_MESSAGES]['m018'] = "";
      $MESSAGES[MSG_MESSAGES]['m019'] = "Keine Nachrichten vorhanden";
      $MESSAGES[MSG_MESSAGES]['m020'] = "";
      $MESSAGES[MSG_MESSAGES]['m021'] = "";
      $MESSAGES[MSG_MESSAGES]['m022'] = "";
      $MESSAGES[MSG_MESSAGES]['m023'] = "Löschen";
      $MESSAGES[MSG_MESSAGES]['m024'] = "Zeit";
      $MESSAGES[MSG_MESSAGES]['m025'] = "Angriff";
      $MESSAGES[MSG_MESSAGES]['m026'] = "Verteidigung";
      $MESSAGES[MSG_MESSAGES]['m027'] = "Handel Eingang";
      $MESSAGES[MSG_MESSAGES]['m028'] = "Handel Ausgang";
      $MESSAGES[MSG_MESSAGES]['m029'] = "Handelszentrum";
      $MESSAGES[MSG_MESSAGES]['m030'] = "Scan";
      $MESSAGES[MSG_MESSAGES]['m031'] = "Stadt wählen:";
      $MESSAGES[MSG_MESSAGES]['m032'] = "";
      $MESSAGES[MSG_MESSAGES]['m033'] = "Keine Berichte vorhanden";
      $MESSAGES[MSG_MESSAGES]['m034'] = "Als E-Mail an mich versenden";
      $MESSAGES[MSG_MESSAGES]['m035'] = "Dies ist eine automatisch generierte E-Mail- Bitte antworten Sie nicht darauf.";
      $MESSAGES[MSG_MESSAGES]['m036'] = "";
      $MESSAGES[MSG_MESSAGES]['m037'] = "";
      $MESSAGES[MSG_MESSAGES]['m038'] = "";
      $MESSAGES[MSG_MESSAGES]['m039'] = "";
      $MESSAGES[MSG_MESSAGES]['m040'] = "";

      $MESSAGES[MSG_MESSAGES]['e000'] = "";
      $MESSAGES[MSG_MESSAGES]['e001'] = "";
      $MESSAGES[MSG_MESSAGES]['e002'] = "";
      $MESSAGES[MSG_MESSAGES]['e003'] = "";
      $MESSAGES[MSG_MESSAGES]['e004'] = "";
      $MESSAGES[MSG_MESSAGES]['e005'] = "";

      break;

    case MSG_ALLIANCES :
      $MESSAGES[MSG_ALLIANCES]['m000'] = "Allianzen";
      $MESSAGES[MSG_ALLIANCES]['m001'] = "Produktionsabgabe an die Allianzstadt*:";
      $MESSAGES[MSG_ALLIANCES]['m002'] = "*Bestimme einen Prozentsatz, welcher regelt wieviel von Ihrer Produktion deine Mitglieder an die Allianzstadt abführen. Aber sei Vorsichtig: Der Prozentsatz kann nur einmal täglich geändert werden!";

      $MESSAGES[MSG_ALLIANCES]['e000'] = "Sonderzeichen im TAG sind nicht erlaubt";
      $MESSAGES[MSG_ALLIANCES]['e001'] = "Bitte geben Sie eine TAG an";
      $MESSAGES[MSG_ALLIANCES]['e002'] = "Der TAG-Name ist bereits vorhanden";
      $MESSAGES[MSG_ALLIANCES]['e003'] = "Diesen TAG gibt es nicht";
      $MESSAGES[MSG_ALLIANCES]['e004'] = "Die maximale Anzahl der Allianz-Mitglieder wurde erreicht";
      $MESSAGES[MSG_ALLIANCES]['e005'] = "Sie sind nicht berechtigt eine Nachricht zu schreiben";
      $MESSAGES[MSG_ALLIANCES]['e006'] = "Bitte geben Sie eine Nachricht und einen Betreff an";
      $MESSAGES[MSG_ALLIANCES]['e007'] = "Bitte geben Sie eine Frage und mindestens zwei Antwortmöglichkeiten an";
      $MESSAGES[MSG_ALLIANCES]['e008'] = "Zu dieser Aktion sind Sie nicht berechtigt";
      $MESSAGES[MSG_ALLIANCES]['e009'] = "Status ungültig";
      $MESSAGES[MSG_ALLIANCES]['e010'] = "Der TAG ist zu lang";
      $MESSAGES[MSG_ALLIANCES]['e011'] = "Sie können Ihre Allianz nicht verlassen (Gründer-Status)";
      $MESSAGES[MSG_ALLIANCES]['e012'] = "Der Unterschied der Kampfwerte ist zu groß für eine Kriegserklärung";
      $MESSAGES[MSG_ALLIANCES]['e013'] = "Der Unterschied der Allianz-Mitglieder ist zu groß für eine Kriegserklärung";
      $MESSAGES[MSG_ALLIANCES]['e014'] = "Sie führen bereits Krieg gegen diese Allianz";
      $MESSAGES[MSG_ALLIANCES]['e015'] = "Es können keine weiteren Bündnisse eingetragen werden";
      $MESSAGES[MSG_ALLIANCES]['e016'] = "Die maximale Anzahl Wings wurde erreicht";

      break;

    case MSG_SEARCH :
      $MESSAGES[MSG_SEARCH]['m000'] = "Suche";

      $MESSAGES[MSG_SEARCH]['e000'] = "Bitte geben Sie einen gültigen Suchbegriff ein";

      break;

    case MSG_REGISTER :
      $MESSAGES[MSG_REGISTER]['title'] = "Willkommen - Registrierung";	
    	
      $MESSAGES[MSG_REGISTER]['m000'] = "Registrieren";
      $MESSAGES[MSG_REGISTER]['m014'] = "Um deinen Zugang zu aktivieren, senden wir dir an diese Anschrift eine E-Mail. Diese E-Mail enthält einen Link, der innerhalb von 48 Stunden betätigt werden muss.";
      $MESSAGES[MSG_REGISTER]['m003'] = "E-Mail-Anschrift *";
      $MESSAGES[MSG_REGISTER]['m022'] = "Dein Kennwort kann zwischen 8 und 32 Zeichen lang sein.";
      $MESSAGES[MSG_REGISTER]['m015'] = "Sollen wir dir ein relativ sicheres Kennwort generieren?";
      $MESSAGES[MSG_REGISTER]['m005'] = "Kennwort *";
      $MESSAGES[MSG_REGISTER]['m004'] = "wiederholen *";
      $MESSAGES[MSG_REGISTER]['m013'] = "Unter welchem Namen willst du auf Erde II bekannt sein? Wähle gut, du kannst ihn später nicht ändern. Insgesamt stehen dir 15 Buchstaben und Ziffern zur Verfügung.";
      $MESSAGES[MSG_REGISTER]['m002'] = "Spielername *";
      $MESSAGES[MSG_REGISTER]['m016'] = "Wie wird deine erste Siedlung heissen? Den Namen deiner Hauptstadt kannst du später beliebig wechseln.";
      $MESSAGES[MSG_REGISTER]['m006'] = "Stadtname *";
      $MESSAGES[MSG_REGISTER]['m020'] = "Du hast unsere <a href=\"./agb.php\" alt=\"Lies jetzt die AGB\" target=\"_blank\">Allgemeinen Geschäftsbedingungen</a> gelesen und bist damit einverstanden.";

      $MESSAGES[MSG_REGISTER]['m021'] = "Escape to Space: Zugangsaktivierung";
      $MESSAGES[MSG_REGISTER]['m022'] = "Hallo";
      $MESSAGES[MSG_REGISTER]['m023'] = "willkommen zurück zu <b>Escape to Space</b>, schön dich wieder zu sehen!";
      $MESSAGES[MSG_REGISTER]['m024'] = "Log dich schnell ein und starte voll durch.";
      $MESSAGES[MSG_REGISTER]['m025'] = "Um deinen Zugang zu aktivieren, benutze nun den folgenden Link:";
      $MESSAGES[MSG_REGISTER]['m026'] = "Eine nicht aktivierte Anmeldung verfällt nach 48 Stunden automatisch.";
      
      $MESSAGES[MSG_REGISTER]['m027'] = "Verwaltungsrat";
      $MESSAGES[MSG_REGISTER]['m028'] = "Willkommen auf Erde II";
      
      
      $MESSAGES[MSG_REGISTER]['precodeInfoLabel'] = "Falls du diesen Namen reserviert hast, gibst du nun den Sicherheitscode ein, den du per E-Mail erhalten hast.";
      $MESSAGES[MSG_REGISTER]['precodeLabel'] = "Sicherheitscode";
      $MESSAGES[MSG_REGISTER]['privacyInfoLabel'] = "Du bist damit einverstanden, dass wir deine Daten entsprechend der <a href=\"./dataSecurity.php\" alt=\"Lies jetzt die Datenschutzerklärung\" target=\"_blank\">Datenschutzerklärung</a> verwenden.";
      $MESSAGES[MSG_REGISTER]['privacyCheckLabel'] = "Datenschutz *";
      $MESSAGES[MSG_REGISTER]['newsLetterInfoLabel'] = "Wenn du über Neuerungen per E-Mail informiert werden möchtest, kannst du diese Nachrichten abonnieren.";
      $MESSAGES[MSG_REGISTER]['newsLetterCheckLabel'] = "Rundbrief";

      $MESSAGES[MSG_REGISTER]['submitButton'] = "Siedlungsschiff betreten";
      $MESSAGES[MSG_REGISTER]['moreInfoButton'] = "Mehr erfahren - Empfohlen";
      $MESSAGES[MSG_REGISTER]['cancelButton'] = "Dateneingabe verweigern";

      $MESSAGES[MSG_REGISTER]['e000'] = "Bitte fülle alle Felder aus, die mit einem Stern markiert sind.";
      $MESSAGES[MSG_REGISTER]['e001'] = "Bitte akzeptiere unsere Geschäftsbedingungen.";
      $MESSAGES[MSG_REGISTER]['e002'] = "Die angegebenen E-Mail-Anschriften sind verschieden.";
      $MESSAGES[MSG_REGISTER]['e003'] = "Die angegebene E-Mail-Anschrift ist ungültig.";
      $MESSAGES[MSG_REGISTER]['e004'] = "Dieser Spielername wurde von jemandem reserviert."; // Voranmeldung
      $MESSAGES[MSG_REGISTER]['e005'] = "Dein Name ist zu lang, er kann maximal 15 Zeichen enthalten.";
      $MESSAGES[MSG_REGISTER]['e006'] = "Dein Name darf keine Sonderzeichen enthalten.";
      $MESSAGES[MSG_REGISTER]['e007'] = "Die Kennwörter sind verschieden.";
      $MESSAGES[MSG_REGISTER]['e008'] = "Das Kennwort ist zu kurz, es muss mindestens 8 Zeichen enthalten.";
      $MESSAGES[MSG_REGISTER]['e009'] = "Dieser Spielername ist bereits vergeben.";
      $MESSAGES[MSG_REGISTER]['e010'] = "Die E-Mail-Anschrift wurde bereits einem anderen Benutzerkonto zugeordnet.";
      $MESSAGES[MSG_REGISTER]['e011'] = "Der eingegebene Code ist nicht richtig.";
      $MESSAGES[MSG_REGISTER]['e012'] = "Der gewählte Username darf nicht verwendet werden. Bitte wähle einen anderen.";
      $MESSAGES[MSG_REGISTER]['registerSuccess'] = "Sehr gut, der Papierkram ist so gut wie erledigt. Bitte betätige nun noch innerhalb der nächsten 48 Stunden den Link, den wir dir gerade per E-Mail an deine zuvor angegebene Adresse gesendet haben.";
      $MESSAGES[MSG_REGISTER]['privacyError'] = "Bitte akzeptiere unserer Datenschutzerklärung.";

      $MESSAGES[MSG_REGISTER]['confirmSuccess'] = "Du hast es geschafft, Siedler. Willkommen auf Erde II. Begebe dich am Besten sofort in dein Hauptquartier. Die Zugangskontrolle, auch <a href=\"./login.php\" alt=\"Jetzt anmelden\">'Erde II betreten'</a> genannt, findest du im linken Menü.";
      $MESSAGES[MSG_REGISTER]['confirmFalse'] = "Tut uns leid, Siedler, aber mit deinen Papieren stimmt etwas nicht. Ist das der richtige Aktivierungslink gewesen? Falls du nicht weiter weißt, wende dich doch bitte an unsere <a href=\"./support.php\" alt=\"Hier findest du Hilfe\">Spielbetreuung</a>.";
      $MESSAGES[MSG_REGISTER]['confirmError'] = "Tut uns leid Siedler, aber eventuell sind deine Papiere nicht vollständig. Prüfe bitte ob der zugesendete Link nun vollständig in der Adresszeile steht. Falls du nicht weiter weißt, wende dich doch bitte an unseren <a href=\"./support.php\" alt=\"Hier findest du Hilfe\">Support</a>.";

      break;

    case MSG_IP_REGISTER :
      $MESSAGES[MSG_IP_REGISTER]['e000'] = "Bitte fülle alle Felder aus!";
      $MESSAGES[MSG_IP_REGISTER]['e001'] = "Der angegebene Benutzer existiert nicht.";

      break;

    case MSG_WORK_BOARD :
      $MESSAGES[MSG_WORK_BOARD]['m000'] = "Bauzentrum";
      $MESSAGES[MSG_WORK_BOARD]['m001'] = "Info";
      $MESSAGES[MSG_WORK_BOARD]['m002'] = "Beim Abbrechen eines Gebäudes werden entsprechend der Zeit, die das Gebäude schon gebaut wurde, die Rohstoffe zurückerstattet - maximal aber 80%. Beim Vormerken werden die Rohstoffe sofort abgezogen, werden aber im Falle des Abbruchs nicht zurückerstattet. Bricht man ein im Bau befindliches Gebäude ab, so wird automatisch die Vormerkung aufgehoben.";
      $MESSAGES[MSG_WORK_BOARD]['m003'] = "Rohstoff-Gebäude";
      $MESSAGES[MSG_WORK_BOARD]['m004'] = "Depots";
      $MESSAGES[MSG_WORK_BOARD]['m005'] = "Flugzeug-Gebäude";
      $MESSAGES[MSG_WORK_BOARD]['m006'] = "Zentren";
      $MESSAGES[MSG_WORK_BOARD]['m007'] = "Verteidigung";
      $MESSAGES[MSG_WORK_BOARD]['m008'] = "Bedarf";
      $MESSAGES[MSG_WORK_BOARD]['m009'] = "Bauzeit";
      $MESSAGES[MSG_WORK_BOARD]['m010'] = "Bauen";
      $MESSAGES[MSG_WORK_BOARD]['m011'] = "Vormerken";
      $MESSAGES[MSG_WORK_BOARD]['m012'] = "Abbrechen";
      $MESSAGES[MSG_WORK_BOARD]['m013'] = "Aufheben";

      $MESSAGES[MSG_WORK_BOARD]['e000'] = "Es wird zur Zeit gebaut";
      $MESSAGES[MSG_WORK_BOARD]['e001'] = "Sie haben nicht genügend Rohstoffe";
      $MESSAGES[MSG_WORK_BOARD]['e002'] = "Es ist bereits ein Gebäude vorgemerkt";
      $MESSAGES[MSG_WORK_BOARD]['e003'] = "Zur Zeit wird nichts gebaut";
      $MESSAGES[MSG_WORK_BOARD]['e004'] = "Zur Zeit ist nichts vorgemerkt";
      $MESSAGES[MSG_WORK_BOARD]['e005'] = "Es befindet sich kein Gebäude im Bau";
      $MESSAGES[MSG_WORK_BOARD]['e006'] = "Sie erfüllen nicht die nötigen Voraussetzungen zum Bau des Gebäudes";

      break;

    case MSG_INFORMATION :
      $MESSAGES[MSG_INFORMATION]['m000'] = "Informationen";
      $MESSAGES[MSG_INFORMATION]['m001'] = "Stadt";
      $MESSAGES[MSG_INFORMATION]['m002'] = "Name";
      $MESSAGES[MSG_INFORMATION]['m003'] = "Punkte";
      $MESSAGES[MSG_INFORMATION]['m004'] = "Allianz";
      $MESSAGES[MSG_INFORMATION]['m005'] = "Text";
      $MESSAGES[MSG_INFORMATION]['m006'] = "Aktionen";
      $MESSAGES[MSG_INFORMATION]['m007'] = "Position";
      $MESSAGES[MSG_INFORMATION]['m008'] = "Besitzer";
      $MESSAGES[MSG_INFORMATION]['m009'] = "TAG";
      $MESSAGES[MSG_INFORMATION]['m010'] = "Mitglieder";
      $MESSAGES[MSG_INFORMATION]['m011'] = "Homepage";
      $MESSAGES[MSG_INFORMATION]['m012'] = "Militär-Bündnisse";
      $MESSAGES[MSG_INFORMATION]['m013'] = "Handels-Bündnisse";
      $MESSAGES[MSG_INFORMATION]['m014'] = "NAPs";
      $MESSAGES[MSG_INFORMATION]['m015'] = "Feinde";
      $MESSAGES[MSG_INFORMATION]['m016'] = "Besitzerwechsel";

      $MESSAGES[MSG_INFORMATION]['e000'] = "Diesen Siedler gibt es nicht";
      $MESSAGES[MSG_INFORMATION]['e001'] = "Diese Stadt gibt es nicht";
      $MESSAGES[MSG_INFORMATION]['e002'] = "Diese Allianz gibt es nicht";
      $MESSAGES[MSG_INFORMATION]['e003'] = "Falsche oder fehlende Parameter";

      break;

    case MSG_TECH_CENTER :
      $MESSAGES[MSG_TECH_CENTER]['m000'] = "Technologie-Zentrum";
      $MESSAGES[MSG_TECH_CENTER]['m001'] = "Erforschen";
      $MESSAGES[MSG_TECH_CENTER]['m002'] = "Abbrechen";
      $MESSAGES[MSG_TECH_CENTER]['m003'] = "Antriebstechnologien";
      $MESSAGES[MSG_TECH_CENTER]['m004'] = "Waffentechnologien";
      $MESSAGES[MSG_TECH_CENTER]['m005'] = "Flugzeugtechnologien";
      $MESSAGES[MSG_TECH_CENTER]['m006'] = "Gebäudetechnologien";
      $MESSAGES[MSG_TECH_CENTER]['m007'] = "Bedarf";
      $MESSAGES[MSG_TECH_CENTER]['m008'] = "Bauzeit";
      $MESSAGES[MSG_TECH_CENTER]['m009'] = "Vormerken";
      $MESSAGES[MSG_TECH_CENTER]['m010'] = "Aufheben";

      $MESSAGES[MSG_TECH_CENTER]['e000'] = "Du musst erst ein Technologie-Zentrum bauen, um diese Funktion nutzen zu können";
      $MESSAGES[MSG_TECH_CENTER]['e001'] = "Es wird zur Zeit gebaut";
      $MESSAGES[MSG_TECH_CENTER]['e002'] = "Du hast nicht genügend Rohstoffe";
      $MESSAGES[MSG_TECH_CENTER]['e003'] = "Zur Zeit wird nichts gebaut";
      $MESSAGES[MSG_TECH_CENTER]['e004'] = "Du kannst Forschungen nur in der Stadt abbrechen, in der du den Auftrag gegeben hast";
      $MESSAGES[MSG_TECH_CENTER]['e005'] = "Du erfüllst nicht die nötigen Voraussetzungen zum Erforschen der Technologie";
      $MESSAGES[MSG_TECH_CENTER]['e006'] = "Zur Zeit ist nichts vorgemerkt";
      $MESSAGES[MSG_TECH_CENTER]['e007'] = "Es ist bereits eine Forschung vorgemerkt";
      
      

      break;

    case MSG_MSGCTR :
      $MESSAGES[MSG_MSGCTR]['m000'] = "Nachricht schreiben";
      $MESSAGES[MSG_MSGCTR]['m001'] = "Posteingang";
      $MESSAGES[MSG_MSGCTR]['m002'] = "Postausgang";
      $MESSAGES[MSG_MSGCTR]['m003'] = "Papierkorb";
      $MESSAGES[MSG_MSGCTR]['m004'] = "Berichte";
      $MESSAGES[MSG_MSGCTR]['m005'] = "Ereignisse";
      $MESSAGES[MSG_MSGCTR]['m006'] = "Nachricht anzeigen";
      $MESSAGES[MSG_MSGCTR]['m007'] = "Zurück";
      $MESSAGES[MSG_MSGCTR]['m008'] = "Adressbuch";
      $MESSAGES[MSG_MSGCTR]['m009'] = "Nachricht schreiben";
      $MESSAGES[MSG_MSGCTR]['m010'] = "Empfänger *";
      $MESSAGES[MSG_MSGCTR]['m011'] = "Betreff";
      $MESSAGES[MSG_MSGCTR]['m012'] = "Nachricht";
      $MESSAGES[MSG_MSGCTR]['m013'] = "Menü";
      $MESSAGES[MSG_MSGCTR]['m014'] = "Senden";
      $MESSAGES[MSG_MSGCTR]['m015'] = "Antworten mit Zitat";
      $MESSAGES[MSG_MSGCTR]['m016'] = "\n\n\n------------------------------------------\n";
      $MESSAGES[MSG_MSGCTR]['m017'] = "alle";
      $MESSAGES[MSG_MSGCTR]['m018'] = "keine";
      $MESSAGES[MSG_MSGCTR]['m019'] = "Keine Nachrichten vorhanden";
      $MESSAGES[MSG_MSGCTR]['m020'] = "Keine Kontakte vorhanden";
      $MESSAGES[MSG_MSGCTR]['m021'] = "Nachricht erfolgreich versandt";
      $MESSAGES[MSG_MSGCTR]['m022'] = "Sender";
      $MESSAGES[MSG_MSGCTR]['m023'] = "löschen";
      $MESSAGES[MSG_MSGCTR]['m024'] = "Zeit";
      $MESSAGES[MSG_MSGCTR]['m025'] = "Antworten";
      $MESSAGES[MSG_MSGCTR]['m026'] = "Siedler";
      $MESSAGES[MSG_MSGCTR]['m027'] = "Aktion";
      $MESSAGES[MSG_MSGCTR]['m028'] = "Neu";
      $MESSAGES[MSG_MSGCTR]['m029'] = "Löschen";
      $MESSAGES[MSG_MSGCTR]['m030'] = "Antworten auf";
      $MESSAGES[MSG_MSGCTR]['m031'] = "Stadt wählen:";
      $MESSAGES[MSG_MSGCTR]['m032'] = "Weiterleiten";
      $MESSAGES[MSG_MSGCTR]['m033'] = "Keine Berichte vorhanden";
      $MESSAGES[MSG_MSGCTR]['m034'] = "Als E-Mail an mich versenden";
      $MESSAGES[MSG_MSGCTR]['m035'] = "Dies ist eine automatisch generierte E-Mail - Bitte antworten Sie nicht darauf.";
      $MESSAGES[MSG_MSGCTR]['m036'] = "\n\n\n----- Ursprüngliche Nachricht -----\nSender: ";
      $MESSAGES[MSG_MSGCTR]['m037'] = "\nEmpfänger: ";
      $MESSAGES[MSG_MSGCTR]['m038'] = "\nZeit: ";
      $MESSAGES[MSG_MSGCTR]['m039'] = "\nBetreff: ";
      $MESSAGES[MSG_MSGCTR]['m040'] = "\n\n";
      $MESSAGES[MSG_MSGCTR]['m041'] = "als gelesen markieren";
      $MESSAGES[MSG_MSGCTR]['m042'] = "als ungelesen markieren";
      $MESSAGES[MSG_MSGCTR]['m043'] = "verschieben";
      $MESSAGES[MSG_MSGCTR]['m044'] = "Kein Betreff";
      $MESSAGES[MSG_MSGCTR]['m045'] = "Ordnerverwaltung";
      $MESSAGES[MSG_MSGCTR]['m046'] = "Ordner";
      $MESSAGES[MSG_MSGCTR]['m047'] = "Keine Ordner vorhanden";
      $MESSAGES[MSG_MSGCTR]['m048'] = "anlegen";
      $MESSAGES[MSG_MSGCTR]['m049'] = "vorhandene Nachrichten:";
      $MESSAGES[MSG_MSGCTR]['m050'] = "auswählen:";
      $MESSAGES[MSG_MSGCTR]['m051'] = "markierte:";
      $MESSAGES[MSG_MSGCTR]['m052'] = "[Fenster schließen]";
      $MESSAGES[MSG_MSGCTR]['m053'] = "Archiv";
      $MESSAGES[MSG_MSGCTR]['m054'] = "archivieren";
      $MESSAGES[MSG_MSGCTR]['m055'] = "wiederherstellen";
      $MESSAGES[MSG_MSGCTR]['m056'] = "Zur Kontaktliste hinzufügen";
      $MESSAGES[MSG_MSGCTR]['m057'] = "Mehrere Empfänger durch Komma getrennt";
      $MESSAGES[MSG_MSGCTR]['m058'] = "- ganze Gruppe auswählen -";
      $MESSAGES[MSG_MSGCTR]['m059'] = "- einzelnen Empfänger auswählen -";
      $MESSAGES[MSG_MSGCTR]['m060'] = "Lesebestätigung anfordern";
      $MESSAGES[MSG_MSGCTR]['m061'] = "Gelesen: ";
      $MESSAGES[MSG_MSGCTR]['m062'] = "Der Empfänger hat deine Nachricht \"";
      $MESSAGES[MSG_MSGCTR]['m063'] = "\" von ";
      $MESSAGES[MSG_MSGCTR]['m064'] = " gelesen.";
      $MESSAGES[MSG_MSGCTR]['m065'] = "Der Sender hat eine Lesebestätigung angefordert. Möchtest du diese senden?";
      $MESSAGES[MSG_MSGCTR]['m066'] = "Vorschau";
      $MESSAGES[MSG_MSGCTR]['m067'] = " schrieb um ";
      $MESSAGES[MSG_MSGCTR]['m068'] = "Enthält die Nachricht Inhalte, die gegen die AGB §3 Abs.4 verstoßen? Dies sind z.B. pornografische, rassistische, beleidigende oder gegen geltendes Recht verstoßende Äußerungen, Links, Bilder, etc. Wenn ja, hast du die möglichkeit die Nachricht zur Überprüfung an die Spielbetreuer zu senden, sodass alle notwendigen Maßnahmen in die Wege geleitet werden können:";
      $MESSAGES[MSG_MSGCTR]['m069'] = "AGB-Verstoß melden";
      $MESSAGES[MSG_MSGCTR]['m070'] = "Hiermit übermitteln Sie die Daten an den Administrator";
      $MESSAGES[MSG_MSGCTR]['m071'] = "<<< vorherige";
      $MESSAGES[MSG_MSGCTR]['m072'] = "nächste >>>";
      $MESSAGES[MSG_MSGCTR]['m073'] = "";
      $MESSAGES[MSG_MSGCTR]['m074'] = "Signatur";
      $MESSAGES[MSG_MSGCTR]['m075'] = "Hier kannst du die Signatur für deine Nachrichten ändern. Bitte beachte, dass die Länge auf 500 Zeichen begrenzt ist.";
      $MESSAGES[MSG_MSGCTR]['m076'] = "Speichern";
      $MESSAGES[MSG_MSGCTR]['m077'] = "Sie verwenden ";
      $MESSAGES[MSG_MSGCTR]['m078'] = " Zeichen";
      $MESSAGES[MSG_MSGCTR]['m079'] = "Allianz";
      $MESSAGES[MSG_MSGCTR]['m080'] = "Liste:";
      $MESSAGES[MSG_MSGCTR]['m081'] = "Allianz-Rundmail";
      $MESSAGES[MSG_MSGCTR]['m082'] = "Loeschen";
      $MESSAGES[MSG_MSGCTR]['m083'] = "Suchen";
      $MESSAGES[MSG_MSGCTR]['m084'] = "Posteingang leeren";

      $MESSAGES[MSG_MSGCTR]['e000'] = "Diesen Siedler gibt es nicht";
      $MESSAGES[MSG_MSGCTR]['e001'] = "Du gehörst zu keiner Allianz";
      $MESSAGES[MSG_MSGCTR]['e002'] = "Bitte schreibe eine Nachricht";
      $MESSAGES[MSG_MSGCTR]['e003'] = "Du kannst keine weitere Nachricht verschicken, bitte gedulde dich einen Moment";
      $MESSAGES[MSG_MSGCTR]['e004'] = "Sie haben keine Empfänger bestimmt";
      $MESSAGES[MSG_MSGCTR]['e005'] = "Diese Nachricht gibt es nicht";
      $MESSAGES[MSG_MSGCTR]['e006'] = "Diese Gruppe gibt es nicht";
      $MESSAGES[MSG_MSGCTR]['e007'] = "Es befinden sich keine Namen in der angegebenen Gruppe";
      $MESSAGES[MSG_MSGCTR]['e008'] = "Du bist nicht berechtigt Allianz-Rundschreiben zu verschicken";
      $MESSAGES[MSG_MSGCTR]['e009'] = "In deiner Allianz befinden sich keine Siedler";
      //XXX duplicate? e001 - check usage
      $MESSAGES[MSG_MSGCTR]['e010'] = "Du gehörst zu keiner Allianz";

      break;

    case MSG_ADRESSBOOK :
      $MESSAGES[MSG_ADRESSBOOK]['m000'] = "Adressbuch";
      $MESSAGES[MSG_ADRESSBOOK]['m001'] = "Liste";
      $MESSAGES[MSG_ADRESSBOOK]['m002'] = "Keine Einträge vorhanden";
      $MESSAGES[MSG_ADRESSBOOK]['m003'] = "Neuer Eintrag";
      $MESSAGES[MSG_ADRESSBOOK]['m004'] = "Gruppe hinzufügen";
      $MESSAGES[MSG_ADRESSBOOK]['m005'] = "Namen hinzufügen";
      $MESSAGES[MSG_ADRESSBOOK]['m006'] = "Zurück";
      $MESSAGES[MSG_ADRESSBOOK]['m007'] = "Gruppen";
      $MESSAGES[MSG_ADRESSBOOK]['m008'] = "löschen";
      $MESSAGES[MSG_ADRESSBOOK]['m009'] = "entfernen";
      $MESSAGES[MSG_ADRESSBOOK]['m010'] = "ohne Zuordnung";

      $MESSAGES[MSG_ADRESSBOOK]['e000'] = "Diesen Siedler gibt es nicht";
      $MESSAGES[MSG_ADRESSBOOK]['e001'] = "Es ist bereits ein Eintrag in dieser Gruppe vorhanden";
      $MESSAGES[MSG_ADRESSBOOK]['e002'] = "Diese Gruppe existiert bereits";
      $MESSAGES[MSG_ADRESSBOOK]['e003'] = "Diese Gruppe existiert nicht";

      break;

    case MSG_LOGIN_ERROR :
        $MESSAGES[MSG_LOGIN_ERROR]['m000'] = "Der Sicherheitscode wurde nicht korrekt eingegeben.";
        $MESSAGES[MSG_LOGIN_ERROR]['m001'] = "Deine E-Mail-Adresse oder dein Kennwort stimmte nicht.";


      break;
    case MSG_CHRONICLE:
      $MESSAGES[MSG_CHRONICLE]['title'] = "Chronik von Erde II";
      $MESSAGES[MSG_CHRONICLE]['events'] = "Weltweite Ereignisse";
      break;
      
    case MSG_WAR_STATUS:
      $MESSAGES[MSG_WAR_STATUS]['title'] = "Stand des Krieges";
      $MESSAGES[MSG_WAR_STATUS]['no_id'] = "Kein Krieg ausgewählt";
      $MESSAGES[MSG_WAR_STATUS]['invalid_id'] = "Solch einen Krieg hat es nie gegeben";
      $MESSAGES[MSG_WAR_STATUS]['no_such_war'] = "Seit Eröffnung der Chronik wurde kein solcher Krieg erklärt";
      $MESSAGES[MSG_WAR_STATUS]['censored'] = "Dieser Krieg wurde nicht in die Chronik von Erde II aufgenommen";
      $MESSAGES[MSG_WAR_STATUS]['x'] = "";
      break;
      
    case MSG_ACC_STAT:
      $MESSAGES[MSG_ACC_STAT]['m001'] = "Account-Statistik";
      $MESSAGES[MSG_ACC_STAT]['m002'] = "Ressourcen";
      $MESSAGES[MSG_ACC_STAT]['m003'] = "zurück zur vorherigen Seite";  
      break;
      
    case MSG_DESC:
      $MESSAGES[MSG_DESC]['m000'] = "Ausbaustufen außerhalb Bereich";
      $MESSAGES[MSG_DESC]['m001'] = "Fördermenge";
      $MESSAGES[MSG_DESC]['m002'] = "Kapazität";
      $MESSAGES[MSG_DESC]['m003'] = "maximale Flugzeuganzahl";
      $MESSAGES[MSG_DESC]['m004'] = "maximale Flottengröße";
      $MESSAGES[MSG_DESC]['m005'] = "maximale Kolonien";
      $MESSAGES[MSG_DESC]['m006'] = "maximale Handelsmenge";
      $MESSAGES[MSG_DESC]['m007'] = "maximale Defensivanlagen";
      $MESSAGES[MSG_DESC]['m008'] = "Kampfwert";
      $MESSAGES[MSG_DESC]['m009'] = "Nicht kampffähig";
      $MESSAGES[MSG_DESC]['m010'] = "Ausbaustufe";
      $MESSAGES[MSG_DESC]['m011'] = "Eigenschaft";
      $MESSAGES[MSG_DESC]['m012'] = "Wert";
      $MESSAGES[MSG_DESC]['m013'] = "Geschwindigkeit in km/h";
      $MESSAGES[MSG_DESC]['m014'] = "Verbrauch je 1000 km (Hin- und Zurück)";
      $MESSAGES[MSG_DESC]['m015'] = "Ladekapazität";
      $MESSAGES[MSG_DESC]['m016'] = "Maximalbauzeit";
      
      $MESSAGES[MSG_DESC]['e000'] = "Fehler";
      $MESSAGES[MSG_DESC]['e001'] = "Kein Objekt gewählt";
      break;
      
    case MSG_REPORT:
      $MESSAGES[MSG_REPORT]['m000'] = "Bericht";
      $MESSAGES[MSG_REPORT]['m001'] = "Zeit";
      $MESSAGES[MSG_REPORT]['m002'] = "Betreff";
      $MESSAGES[MSG_REPORT]['m003'] = "Nachricht";
      $MESSAGES[MSG_REPORT]['m004'] = "Angreifer";
      $MESSAGES[MSG_REPORT]['m005'] = "Gesamt";
      $MESSAGES[MSG_REPORT]['m006'] = "Verluste";
      $MESSAGES[MSG_REPORT]['m007'] = "Verteidiger";
      $MESSAGES[MSG_REPORT]['m008'] = "Flottentext";
      $MESSAGES[MSG_REPORT]['m009'] = "Sie brachte folgende Rohstoffe mit";
      $MESSAGES[MSG_REPORT]['m010'] = "Als E-Mail an mich versenden";
      $MESSAGES[MSG_REPORT]['m011'] = "Bericht forentauglich machen";
      $MESSAGES[MSG_REPORT]['m012'] = "Fenster schließen";
      $MESSAGES[MSG_REPORT]['m013'] = "ACHTUNG: Der Spielerschutz griff. Weiteres dazu in Wiki.";
      
      $MESSAGES[MSG_REPORT]['e000'] = "Diesen Bericht gibt es nicht";
      $MESSAGES[MSG_REPORT]['e001'] = "Kein Bericht ausgewählt";
      break;
     
    case MSG_TIMER:
      $MESSAGES[MSG_TIMER]['m000'] = "An dieser Stelle möchten wir Taba danken das er uns seinen Flugzeitrechner zur Verfügung stellt. Danke.";
      $MESSAGES[MSG_TIMER]['m001'] = "Flugzeitrechner";
      $MESSAGES[MSG_TIMER]['m002'] = "Rückflugrechner";
      $MESSAGES[MSG_TIMER]['m003'] = "Rückrufrechner";
      
    
  }
?>