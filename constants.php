<?php
  require_once('msgs.php');

  define('PAUSE_BEGIN', mktime(18,0,0,1,1,2014));
  define('REG_ALLOWED', mktime(20,0,0,1,1,2014));
  define('PAUSE_END',   mktime(20,0,0,1,8,2014));
  define('PAUSE_TEXT', $MESSAGES[MSG_GENERAL]['m013']);

  define("ATTACKDENYHOURS",36);
  define('BEGIN_USER_BONUS',mktime(00,0,0,2,1,2014));
  
  define('KEEP_SEEN_FIGHT_REPORTS_FOR_DAYS', 14);
  define('KEEP_UNSEEN_FIGHT_REPORTS_FOR_DAYS', 14);
  define('KEEP_SEEN_BUILD_REPORTS_FOR_DAYS', 14);
  define('KEEP_UNSEEN_BUILD_REPORTS_FOR_DAYS', 14);
  define('KEEP_BLACK_BOARD_MSGS_FOR_DAYS', 31);
  define('KEEP_SESSION_DATA_FOR_DAYS', 31);

  define('MAX_CONTINENT', 2);
  define('MAX_COUNTRY', 200);
  define('CITY_DISTANCE', 50);
  define('CITY_BASE_DISTANCE', 200);
  define('COUNTRY_DISTANCE', 80);
  define('COUNTRY_BASE_DISTANCE', 1000);
  define('CONTINENT_DISTANCE', 10000);
  define('MAX_MARKS_BUILD', 5);
  define('MAX_MARKS_TECH', 3);

  define('TURRETS_PER_LEVEL', 15);
  define('PLANES_PER_LEVEL', 10);
  
  // Alliancetown
  define('USER_PER_LEVEL', 5);
  define('CHANGE_BND_TIME', 48);
  define('CHANGE_WING_TIME', 48);
  define('PRODUKTIONSHALLE_BONUS', 2);
  define('SILO_KAPA', 180000);
  define('SILO_KAPA_PER_LEVEL', 50);
  define('HANGAR_BAUZEIT_MAX', 10);
  define('HANGAR_BAUZEIT_REDUCTION', 0.5);
  define('PLANES_PER_LEVEL', 10);
  define('VS_PER_LEVEL', 50);
  define('TIME_REDUCTION_PER_LEVEL', 5);
  define('MAX_TIME_REDUCTION_LEVEL', 10);
  
  // Views per Login
  define('VIEWS', 5);
  define('VIEWS_MAX_DAY', 50);
/*****************/

  define("T_SPEED",0);
  define("T_POWER",1);
  define("T_TECH1",2);
  define("T_TECH2",3);
  define("T_BUILD1",4);
  define("T_BUILD2",5);

/*****************/

  define("ANZAHL_ROHSTOFFE",4);

  define("IRIDIUM",0);
  define("HOLZIUM",1);
  define("WATER",2);
  define("OXYGEN",3);


  define("ANZAHL_GEBAEUDE",13);

  define("NOBUILD",-1);
  define("IR_MINE",0);
  define("HZ_PLANTAGE",1);
  define("WA_DERRICK",2);
  define("OX_REACTOR",3);
  define("DEPOT",4);
  define("OX_DEPOT",5);
  define("HANGAR",6);
  define("AIRPORT",7);
  define("WORK_BOARD",8);
  define("TECH_CENTER",9);
  define("COMM_CENTER",10);
  define("TRADE_CENTER",11);
  define("DEF_CENTER",12);
//  define("SHIELD",13);


  define("ANZAHL_TECHNOLOGIEN",12);

  define("NOTECH",-1);
  define("O_DRIVE",0);
  define("H_DRIVE",1);
  define("A_DRIVE",2);
  define("E_WEAPONS",3);
  define("P_WEAPONS",4);
  define("N_WEAPONS",5);
  define("CONSUMPTION",6);
  define("PLANE_SIZE",7);
  define("COMP_MANAGEMENT",8);
  define("DEPOT_MANAGEMENT",9);
  define("COMPRESSION",10);
  define("MINING",11);
//  define("SHIELD_TECH",12);
//  define("EW_WEAPONS",13);
//  define("PW_WEAPONS",14);
//  define("NW_WEAPONS",15);
  define("EW_WEAPONS",12);
  define("PW_WEAPONS",13);
  define("NW_WEAPONS",14);

  define("ANZAHL_FLUGZEUGE",14);
  define("ANZAHL_KAMPF_FLUGZEUGE",11);
//  define("ANZAHL_FLUGZEUGE",15);
//  define("ANZAHL_KAMPF_FLUGZEUGE",12);

  define("SPARROW",0);
  define("BLACKBIRD",1);
  define("RAVEN",2);
  define("EAGLE",3);
  define("FALCON",4);
  define("NIGHTINGALE",5);
  define("RAVAGER",6);
  define("DESTROYER",7);
  define("ESPIONAGE_PROBE",8);
  define("SETTLER",9);
  define("SCARECROW",10);
//  define("BOMBER",11);
//  define("SMALL_TRANSPORTER",12);
//  define("MEDIUM_TRANSPORTER",13);
//  define("BIG_TRANSPORTER",14);
  define("SMALL_TRANSPORTER",11);
  define("MEDIUM_TRANSPORTER",12);
  define("BIG_TRANSPORTER",13);


  define("ANZAHL_DEFENSIVE",6);

  define("E_WOOFER",0);
  define("P_WOOFER",1);
  define("N_WOOFER",2);
  define("E_SEQUENZER",3);
  define("P_SEQUENZER",4);
  define("N_SEQUENZER",5);

  // distance between a city and the general depot (km)
  define("TC_DISTANCE", "600");
  // factor to adjust the stock level in trading center
  define("TC_LEVEL", "240");
  // tax on buying planes from the trading center
  define("TC_TAX", "0.1");
  define("TC_MIN_SLOW_COEFF", "0.0001");
  define("TC_MIN_FAST_COEFF", "0.0005");
  define("TC_BP_COEFF", "0.0003");
//  // factor to adjust the speed of sudden plane cost changes
//  define("TC_DYN_COEFF", "0.0005");
//  // factor to adjust the speed of steady plane cost changes if cost factor is > 0.9
//  define("TC_MIN_COEFF", "0.0001");
//  // factor to adjust the speed of steady plane cost changes if cost factor is <= 0.9
//  define("TC_BP_COEFF", "0.0003");
  // specifies the convergence of the pseudo avarage stock value
  define("TC_STOCK_CONVERGENCE", "0.9");
  // specifies the convergence of the pseudo avarage sales/acquisition values
  define("TC_VOLUME_CONVERGENCE", "0.95");
  // implicit stock of every resource to cap trading ratio explosion due to a near-zero stock
  define("TC_MIN_STOCK", "100000000");
  // scaling factor for estimating the target stock amount - the lower the higher the target stock
  define("TC_TARGET_SCALE", "100000");
  
  
  // Paramter für die Datei medals.php
  define("MAX_IMG_SIZE", 500);
  define("TUTORIAL_MAX", 7);
  define("MEDAL_STD_TEXT", "Medaille für erreichen ");
  define("MEDAL_TOPIC", "Neue Medaille erhalten");
  define("MEDAL_HALLO", "Hallo ");
  define("MEDAL_TEXT", "\n\nDu hast folgende Auszeichnung erhalten:\n");
  $medaillen = null;
  
  //User Economy
  $medaillen[TUTORIAL] = "tutorial";
  $medaillen[LOGIN] = "login";
  $medaillen[VOTE] = "vote";
  $medaillen[POINTS] = "points";
  $medaillen[TECH] = "tech";
  $medaillen[KOLO] = "kolo";
  $medaillen[PRODUCTION] = "production";
  $medaillen[BBT] = "bbt";
  $medaillen[WK] = "wk";
  $medaillen[BZ] = "bz";
  $medaillen[DEFENCE] = "defence";
  $medaillen[TRADE] = "trade";
  
  //User War
  $medaillen[FLEET] = "fleet";
  //$medaillen[HANGAR] = "hangar";
  $medaillen[ATTACK] = "attack";
  $medaillen[DEFENCE2] = "defence2";
  $medaillen[PLUNDER] = "plunder";
  $medaillen[WEAPON] = "weapon";
  $medaillen[GEAR] = "gear";
  //gear == Antrieb
  
  define("MEDAL_WAR_1", $medaillen[FLEET]);
  
  //Alliances
  $medaillen[SCARE] = "scare";
  //$medaillen[WAR] = "war";
  
  define("MEDAL_ALLIANCE_1", $medaillen[SCARE]);
  
  $medal_values = null;
  $medal_values[$medaillen[TUTORIAL]] = array(TUTORIAL_MAX);
  $medal_values[$medaillen[LOGIN]] = array(1,3,7,14,21,30,60,90,120,150);
  $medal_values[$medaillen[VOTE]] = array(1,3,7,14,21,30,60,90,120,150);
  $medal_values[$medaillen[POINTS]] = array(25,50,100,250,500,1000,2500,5000,7500,10000);
  $medal_values[$medaillen[TECH]] = array(1,5,10,20,50,75,100,200,500,700);
  $medal_values[$medaillen[KOLO]] = array(1,2,5,7,10);
  $medal_values[$medaillen[PRODUCTION]] = array(1,2);
  $medal_values[$medaillen[BBT]] = array(1,5,10,30,50);
  $medal_values[$medaillen[WK]] = array(1,5,10,30,50);
  $medal_values[$medaillen[BZ]] = array(6,10,20,50,100);
  $medal_values[$medaillen[DEFENCE]] = array(1,5,10,50,100);
  $medal_values[$medaillen[TRADE]] = array(1,5,10,30,50);
  $medal_values[$medaillen[FLEET]] = array(5,25,50,100,200,300,500,600,800,1000);
  //$medal_values[$medaillen[HANGAR]] = array(1,5,10,50,100);
  $medal_values[$medaillen[ATTACK]] = array(1,30,100,300,1000,3000,10000,30000,100000,500000);
  $medal_values[$medaillen[DEFENCE2]] = array(1,30,100,300,1000,3000,10000,30000,100000,500000);
  $medal_values[$medaillen[PLUNDER]] = array(1000, 10000, 100000, 1000000, 10000000, 100000000);
  $medal_values[$medaillen[WEAPON]] = array(1,5,10,50,100);
  $medal_values[$medaillen[GEAR]] = array(1,5,10,50,100);
  $medal_values[$medaillen[SCARE]] = array(1,5,10,20,50);
  //$medal_values[$medaillen[WAR]] = array(1,2,3,4,5);
  
  
  $medal_text = null;
  $medal_text[$medaillen[TUTORIAL]] = "Medaille für erfolgreiches Abschließen des Tutorials";
  $medal_text[$medaillen[LOGIN]] = "Medaille für täglichen Login - Stufe ";
  $medal_text[$medaillen[VOTE]] = "Medaille für regelmäßiges Voten - Stufe ";
  $medal_text[$medaillen[POINTS]] = "Medaille für das Erreichen vieler Punkte - Stufe ";
  $medal_text[$medaillen[TECH]] = "Medaille für das Erreichen vieler Technologiepunkte - Stufe ";
  $medal_text[$medaillen[KOLO]] = "Medaille für das Gründen von Kolonien - Stufe ";
  $medal_text[$medaillen[PRODUCTION]] = "Medaille für Gesamtproduktion - Stufe ";
  $medal_text[$medaillen[BBT]] = "Medaille für die Forschung der Bergbautechnik - Stufe ";
  $medal_text[$medaillen[WK]] = "Medaille für die Forschung der Wasserkompression - Stufe ";
  $medal_text[$medaillen[BZ]] = "Medaille für den Ausbau des Bauzentrums - Stufe ";
  $medal_text[$medaillen[DEFENCE]] = "Medaille für den Ausbau des Verteidigungszentrums - Stufe ";
  $medal_text[$medaillen[TRADE]] = "Medaille für den Ausbau des Handelszentrums - Stufe ";
  $medal_text[$medaillen[FLEET]] = "Medaille für den Ausbau der Flottengröße - Stufe ";
  //$medal_text[$medaillen[HANGAR]] = "Medaille für den Ausbau der maximalen Hangarkapazität - Stufe ";
  $medal_text[$medaillen[ATTACK]] = "Medaille für die Zerstörung von gegnerischen Einheiten bei Angriffen - Stufe ";
  $medal_text[$medaillen[DEFENCE2]] = "Medaille für die Zerstörung von gegnerischen Einheiten bei Verteidigung - Stufe ";
  $medal_text[$medaillen[PLUNDER]] = "Medaille für das Plündern von Rohstoffen - Stufe ";
  $medal_text[$medaillen[WEAPON]] = "Medaille für das Erforschen von Waffentechnologien - Stufe ";
  $medal_text[$medaillen[GEAR]] = "Medaille für das Erforschen von Antriebstechnologien - Stufe ";
  $medal_text[$medaillen[SCARE]] = "Medaille für das erfolgreiche Erobern von Kolonien - Stufe ";
  //$medal_text[$medaillen[WAR]] = "Medaille für gewonnene Kriege";

  $p_hz_index = null;
  $p_hz_index[SPARROW] = 300;
  $p_hz_index[BLACKBIRD] = 300;
  $p_hz_index[RAVEN] = 300;
  $p_hz_index[EAGLE] = 250;
  $p_hz_index[FALCON] = 250;
  $p_hz_index[NIGHTINGALE] = 250;
  $p_hz_index[RAVAGER] = 200;
  $p_hz_index[DESTROYER] = 200;
  $p_hz_index[ESPIONAGE_PROBE] = 30;
  $p_hz_index[SETTLER] = 1;
  $p_hz_index[SCARECROW] = 20;
//  $p_hz_index[BOMBER] = 20;
  $p_hz_index[SMALL_TRANSPORTER] = 25;
  $p_hz_index[MEDIUM_TRANSPORTER] = 25;
  $p_hz_index[BIG_TRANSPORTER] = 25;


/*****************/

  $b_name = null;
  $b_name[IR_MINE] = "Iridium-Mine";
  $b_name[HZ_PLANTAGE] = "Holzium-Plantage";
  $b_name[WA_DERRICK] = "Wasser-Bohrturm";
  $b_name[OX_REACTOR] = "Sauerstoff-Reaktor";
  $b_name[DEPOT] = "Lager";
  $b_name[OX_DEPOT] = "Tank";
  $b_name[HANGAR] = "Hangar";
  $b_name[AIRPORT] = "Flughafen";
  $b_name[WORK_BOARD] = "Bauzentrum";
  $b_name[TECH_CENTER] = "Technologiezentrum";
  $b_name[COMM_CENTER] = "Kommunikationszentrum";
  $b_name[TRADE_CENTER] = "Handelszentrum";
  $b_name[DEF_CENTER] = "Verteidigungszentrum";
//  $b_name[SHIELD] = "Schutzschild";

  $b_db_name = null;
  $b_db_name[IR_MINE] = "iridium_mine";
  $b_db_name[HZ_PLANTAGE] = "holzium_plantage";
  $b_db_name[WA_DERRICK] = "water_derrick";
  $b_db_name[OX_REACTOR] = "oxygen_reactor";
  $b_db_name[DEPOT] = "depot";
  $b_db_name[OX_DEPOT] = "oxygen_depot";
  $b_db_name[HANGAR] = "hangar";
  $b_db_name[AIRPORT] = "airport";
  $b_db_name[WORK_BOARD] = "work_board";
  $b_db_name[TECH_CENTER] = "technologie_center";
  $b_db_name[COMM_CENTER] = "communication_center";
  $b_db_name[TRADE_CENTER] = "trade_center";
  $b_db_name[DEF_CENTER] = "defense_center";
//  $b_db_name[SHIELD] = "shield";

  $b_category = null;
  $b_category[IR_MINE] = $MESSAGES[MSG_WORK_BOARD]['m003']; // Rohstoff-Gebäude
  $b_category[HZ_PLANTAGE] = $MESSAGES[MSG_WORK_BOARD]['m003'];
  $b_category[WA_DERRICK] = $MESSAGES[MSG_WORK_BOARD]['m003'];
  $b_category[OX_REACTOR] = $MESSAGES[MSG_WORK_BOARD]['m003'];
  $b_category[DEPOT] = $MESSAGES[MSG_WORK_BOARD]['m004']; // Lager
  $b_category[OX_DEPOT] = $MESSAGES[MSG_WORK_BOARD]['m004'];
  $b_category[HANGAR] = $MESSAGES[MSG_WORK_BOARD]['m005']; // Flugzeug-Gebäude
  $b_category[AIRPORT] = $MESSAGES[MSG_WORK_BOARD]['m005'];
  $b_category[WORK_BOARD] = $MESSAGES[MSG_WORK_BOARD]['m006']; // Zentren
  $b_category[TECH_CENTER] = $MESSAGES[MSG_WORK_BOARD]['m006'];
  $b_category[COMM_CENTER] = $MESSAGES[MSG_WORK_BOARD]['m006'];
  $b_category[TRADE_CENTER] = $MESSAGES[MSG_WORK_BOARD]['m006'];
  $b_category[DEF_CENTER] = $MESSAGES[MSG_WORK_BOARD]['m006'];
//  $b_category[SHIELD] = $MESSAGES[MSG_WORK_BOARD]['m007']; // Schutzschild

  $b_pricing_iridium = null;
  $b_pricing_iridium[IR_MINE] = "G_LIN";
  $b_pricing_iridium[HZ_PLANTAGE] = "G_LIN";
  $b_pricing_iridium[WA_DERRICK] = "G_LIN";
  $b_pricing_iridium[OX_REACTOR] = "G_LIN";
  $b_pricing_iridium[DEPOT] = "G_LIN";
  $b_pricing_iridium[OX_DEPOT] = "G_LIN";
  $b_pricing_iridium[HANGAR] = "G_LIN";
  $b_pricing_iridium[AIRPORT] = "G_LIN";
  $b_pricing_iridium[WORK_BOARD] = "G_LIN";
  $b_pricing_iridium[TECH_CENTER] = "G_LIN";
  $b_pricing_iridium[COMM_CENTER] = "G_EXP_KZ_IR";
  $b_pricing_iridium[TRADE_CENTER] = "G_LIN";
  $b_pricing_iridium[DEF_CENTER] = "G_LIN";
//  $b_pricing_iridium[SHIELD] = "G_SS_IR";

  $b_pricing_holzium = null;
  $b_pricing_holzium[IR_MINE] = "G_LIN";
  $b_pricing_holzium[HZ_PLANTAGE] = "G_LIN";
  $b_pricing_holzium[WA_DERRICK] = "G_LIN";
  $b_pricing_holzium[OX_REACTOR] = "G_LIN";
  $b_pricing_holzium[DEPOT] = "G_LIN";
  $b_pricing_holzium[OX_DEPOT] = "G_LIN";
  $b_pricing_holzium[HANGAR] = "G_LIN";
  $b_pricing_holzium[AIRPORT] = "G_LIN";
  $b_pricing_holzium[WORK_BOARD] = "G_LIN";
  $b_pricing_holzium[TECH_CENTER] = "G_LIN";
  $b_pricing_holzium[COMM_CENTER] = "G_EXP_KZ_HZ";
  $b_pricing_holzium[TRADE_CENTER] = "G_LIN";
  $b_pricing_holzium[DEF_CENTER] = "G_LIN";
//  $b_pricing_holzium[SHIELD] = "G_SS_HZ";

  $b_iridium = null;
  $b_iridium[IR_MINE] = 250;
  $b_iridium[HZ_PLANTAGE] = 200;
  $b_iridium[WA_DERRICK] = 200;
  $b_iridium[OX_REACTOR] = 300;
  $b_iridium[DEPOT] = 500;
  $b_iridium[OX_DEPOT] = 500;
  $b_iridium[HANGAR] = 700;
  $b_iridium[AIRPORT] = 300;
  $b_iridium[WORK_BOARD] = 500;
  $b_iridium[TECH_CENTER] = 550;
  $b_iridium[COMM_CENTER] = 3000;
  $b_iridium[TRADE_CENTER] = 600;
  $b_iridium[DEF_CENTER] = 300;
//  $b_iridium[SHIELD] = 3700;

  $b_holzium = null;
  $b_holzium[IR_MINE] = 50;
  $b_holzium[HZ_PLANTAGE] = 75;
  $b_holzium[WA_DERRICK] = 100;
  $b_holzium[OX_REACTOR] = 200;
  $b_holzium[DEPOT] = 400;
  $b_holzium[OX_DEPOT] = 400;
  $b_holzium[HANGAR] = 600;
  $b_holzium[AIRPORT] = 500;
  $b_holzium[WORK_BOARD] = 400;
  $b_holzium[TECH_CENTER] = 450;
  $b_holzium[COMM_CENTER] = 2100;
  $b_holzium[TRADE_CENTER] = 500;
  $b_holzium[DEF_CENTER] = 500;
//  $b_holzium[SHIELD] = 5200;

  $b_duration = null;
  $b_duration[IR_MINE] = 2500;
  $b_duration[HZ_PLANTAGE] = 3000;
  $b_duration[WA_DERRICK] = 2000;
  $b_duration[OX_REACTOR] = 4000;
  $b_duration[DEPOT] = 5000;
  $b_duration[OX_DEPOT] = 3500;
  $b_duration[HANGAR] = 3000;
  $b_duration[AIRPORT] = 5000;
  $b_duration[WORK_BOARD] = 0; /*************************************/
  $b_duration[TECH_CENTER] = 9000;
  $b_duration[COMM_CENTER] = 6*3600;
  $b_duration[TRADE_CENTER] = 5000;
  $b_duration[DEF_CENTER] = 4000;
//  $b_duration[SHIELD] = 36000;

  $b_premise = null;
  $b_premise[IR_MINE] = NOBUILD;
  $b_premise[HZ_PLANTAGE] = NOBUILD;
  $b_premise[WA_DERRICK] = NOBUILD;
  $b_premise[OX_REACTOR] = NOBUILD;
  $b_premise[DEPOT] = NOBUILD;
  $b_premise[OX_DEPOT] = OX_REACTOR;
  $b_premise[HANGAR] = WORK_BOARD;
  $b_premise[AIRPORT] = HANGAR;
  $b_premise[WORK_BOARD] = NOBUILD;
  $b_premise[TECH_CENTER] = NOBUILD;
  $b_premise[COMM_CENTER] = NOBUILD;
  $b_premise[TRADE_CENTER] = HANGAR;
  $b_premise[DEF_CENTER] = NOBUILD;
//  $b_premise[SHIELD] = DEF_CENTER;

  $b_need = null;
  $b_need[IR_MINE][OX_REACTOR] = 0;
  $b_need[HZ_PLANTAGE][OX_REACTOR] = 0;
  $b_need[WA_DERRICK][OX_REACTOR] = 0;
  $b_need[OX_REACTOR][OX_REACTOR] = 0;
  $b_need[DEPOT][OX_REACTOR] = 0;
  $b_need[OX_DEPOT][OX_REACTOR] = 1;
  $b_need[HANGAR][OX_REACTOR] = 0;
  $b_need[AIRPORT][OX_REACTOR] = 0;
  $b_need[WORK_BOARD][OX_REACTOR] = 0;
  $b_need[TECH_CENTER][OX_REACTOR] = 0;
  $b_need[COMM_CENTER][OX_REACTOR] = 0;
  $b_need[TRADE_CENTER][OX_REACTOR] = 0;
  $b_need[DEF_CENTER][OX_REACTOR] = 0;
//  $b_need[SHIELD][OX_REACTOR] = 0;

  $b_need[IR_MINE][WORK_BOARD] = 0;
  $b_need[HZ_PLANTAGE][WORK_BOARD] = 0;
  $b_need[WA_DERRICK][WORK_BOARD] = 0;
  $b_need[OX_REACTOR][WORK_BOARD] = 0;
  $b_need[DEPOT][WORK_BOARD] = 0;
  $b_need[OX_DEPOT][WORK_BOARD] = 0;
  $b_need[HANGAR][WORK_BOARD] = 10;
  $b_need[AIRPORT][WORK_BOARD] = 0;
  $b_need[WORK_BOARD][WORK_BOARD] = 0;
  $b_need[TECH_CENTER][WORK_BOARD] = 0;
  $b_need[COMM_CENTER][WORK_BOARD] = 0;
  $b_need[TRADE_CENTER][WORK_BOARD] = 0;
  $b_need[DEF_CENTER][WORK_BOARD] = 0;
//  $b_need[SHIELD][WORK_BOARD] = 0;



  $b_need[IR_MINE][HANGAR] = 0;
  $b_need[HZ_PLANTAGE][HANGAR] = 0;
  $b_need[WA_DERRICK][HANGAR] = 0;
  $b_need[OX_REACTOR][HANGAR] = 0;
  $b_need[DEPOT][HANGAR] = 0;
  $b_need[OX_DEPOT][HANGAR] = 0;
  $b_need[HANGAR][HANGAR] = 0;
  $b_need[AIRPORT][HANGAR] = 1;
  $b_need[WORK_BOARD][HANGAR] = 0;
  $b_need[TECH_CENTER][HANGAR] = 0;
  $b_need[COMM_CENTER][HANGAR] = 0;
  $b_need[TRADE_CENTER][HANGAR] = 6;
  $b_need[DEF_CENTER][HANGAR] = 0;
//  $b_need[SHIELD][HANGAR] = 0;

  $b_need[IR_MINE][AIRPORT] = 0;
  $b_need[HZ_PLANTAGE][AIRPORT] = 0;
  $b_need[WA_DERRICK][AIRPORT] = 0;
  $b_need[OX_REACTOR][AIRPORT] = 0;
  $b_need[DEPOT][AIRPORT] = 0;
  $b_need[OX_DEPOT][AIRPORT] = 0;
  $b_need[HANGAR][AIRPORT] = 0;
  $b_need[AIRPORT][AIRPORT] = 0;
  $b_need[WORK_BOARD][AIRPORT] = 0;
  $b_need[TECH_CENTER][AIRPORT] = 0;
  $b_need[COMM_CENTER][AIRPORT] = 0;
  $b_need[TRADE_CENTER][AIRPORT] = 0;
  $b_need[DEF_CENTER][AIRPORT] = 0;
//  $b_need[SHIELD][AIRPORT] = 0;

  $b_need[IR_MINE][DEF_CENTER] = 0;
  $b_need[HZ_PLANTAGE][DEF_CENTER] = 0;
  $b_need[WA_DERRICK][DEF_CENTER] = 0;
  $b_need[OX_REACTOR][DEF_CENTER] = 0;
  $b_need[DEPOT][DEF_CENTER] = 0;
  $b_need[OX_DEPOT][DEF_CENTER] = 0;
  $b_need[HANGAR][DEF_CENTER] = 0;
  $b_need[AIRPORT][DEF_CENTER] = 0;
  $b_need[WORK_BOARD][DEF_CENTER] = 0;
  $b_need[TECH_CENTER][DEF_CENTER] = 0;
  $b_need[COMM_CENTER][DEF_CENTER] = 0;
  $b_need[TRADE_CENTER][DEF_CENTER] = 0;
  $b_need[DEF_CENTER][DEF_CENTER] = 0;
//  $b_need[SHIELD][DEF_CENTER] = 10;


  $b_description = null;
  $b_description[IR_MINE] = "Die Iridium-Mine fördert den wichtigsten Rohstoff für Deine Stadt. Alles, was du baust, benötigt Iridium. Somit garantiert eine hohe Ausbaustufe wirtschaftlichen und militärischen Erfolg.";
  $b_description[HZ_PLANTAGE] = "Genauso wichtig wie das Iridium ist auch das Holzium. Es ist die Ergänzung zum Iridium und hat besonders in der Forschung einen sehr hohen Stellenwert.";
  $b_description[WA_DERRICK] = "Das Wasser ist die Grundlage für die Sauerstoffgewinnung.";
  $b_description[OX_REACTOR] = "Der Sauerstoffreaktor benötigt für seinen Betrieb Wasser. Wassermangel führt zu einer Drosselung der Sauerstoffgewinnung. Durch Sauerstoff werden die Flugzeuge und Forschungsanlagen betrieben.";
  $b_description[DEPOT] = "Lager für Iridium, Holzium und Wasser. Ein Teil des Lagerinhalts (die Iridium- und Holzium-Kosten der nächsten Stufe) kann bei einem Angriff nicht geplündert werden.";
  $b_description[OX_DEPOT] = "Tank für Sauerstoff. Dieser bietet keinerlei Verstecke bei Angriffen.";
  $b_description[HANGAR] = "Der Hangar bietet die Möglichkeit, Flugzeuge zu bauen. Die Bauzeit der Flugzeuge ist von der Hangarstufe abhängig, ebenso die verfügbaren Abstellplätze.";
  $b_description[AIRPORT] = "Der Flughafen ist eines der wichtigsten strategischen Gebäude. Hier werden die Flotten beauftragt, Rohstoffe und Spionage-Sonden verschickt.";
  $b_description[WORK_BOARD] = "Das Bauzentrum verkürzt die Bauzeit der Gebäude. Es ist ein wichtiges Gebäude für eine florierende Stadt.";
  $b_description[TECH_CENTER] = "Das Technologiezentrum bietet die Möglichkeit, Technologien zu entwickeln, um neue Flugzeuge und Verteidigungsanlagen freizuschalten. Auch die Optimierung der Flottenstärke, Flugzeuge, Lager und Produktion wird mit Technologien erreicht. Man kann dieses Gebäude auf jeder Stadt bauen - aber jeweils nur in einer Stadt gleichzeitig forschen. ";
  $b_description[COMM_CENTER] = "Dieses Gebäude ist für die Kommunikation unter den einzelnen Städten und deren Flotten verantwortlich. Wenn in einer Stadt das Kommunikationszentrum auf Stufe 5 oder höher ausgebaut ist, ist es möglich von derjenigen Stadt gestartete Flotten über das Flottenmenü zurückzurufen. Um eine Kolonie zu gründen oder zu erobern, muss die Anzahl der maximalen Kolonien (auf der Stadt von der der Settler/Scarecrow starten soll) um mindestens 1 höher sein, als die Anzahl der im Account bereits vorhandene Kolonien. für eine Kolonie benötigt man Ausbaustufe 1. für 2 Kolonien benötigt man 2 weitere Ausbaustufen, sprich Stufe 3. für 3 Kolonien benötigt man 3 weitere Stufen, sprich Stufe 6, usw.<br><br><b>Achtung: Pro Ausbaustufe steigert sich die maximale Entfernung um 1 Land. Erst ab Ausbaustufe 50 kann frei gegründet werden!</b>";  
  $b_description[TRADE_CENTER] = "Das Handelszentrum bietet die Möglichkeit, mit Rohstoffen und Flugzeugen zu handeln. Die Tauschverhältnisse werden je nach enthaltenen Rohstoffen ermittelt.";
  $b_description[DEF_CENTER] = "Das Verteidigungszentrum bietet die Möglichkeit, Verteidigungsanlagen zu bauen.";
//  $b_description[SHIELD] = "Der Schutzschild kann in Verbindung mit der Schutzschildtechnologie effektiven Schutz für die Stadt bieten. Mit einem Bomber kann der Schutzschild um eine Stufe deaktiviert werden, sofern mindestens ein angreifender Bomber den Kampf übersteht. Dieser Schutzschild regeneriert sich danach wieder (unterschiedlich schnell, abhängig von der Anzahl deaktivierter und der Gesamtanzahl Schilde).";

/*****************/

  $t_increase = null;
  $t_increase[NOTECH] = 0;
  $t_increase[O_DRIVE] = 30;
  $t_increase[H_DRIVE] = 25;
  $t_increase[A_DRIVE] = 22;
  $t_increase[E_WEAPONS] = 10;
  $t_increase[P_WEAPONS] = 24;
  $t_increase[N_WEAPONS] = 43;
  $t_increase[CONSUMPTION] = 0.95;
  $t_increase[PLANE_SIZE] = 1.05;
  $t_increase[COMP_MANAGEMENT] = 3;
  $t_increase[DEPOT_MANAGEMENT] = 1.05;
  $t_increase[COMPRESSION] = 1.05;
  $t_increase[MINING] = 1.05;
//  $t_increase[SHIELD_TECH] = 1.05;
  $t_increase[EW_WEAPONS] = 2;
  $t_increase[PW_WEAPONS] = 4;
  $t_increase[NW_WEAPONS] = 8;

  $t_name = null;
  $t_name[O_DRIVE] = "Oxidationsantrieb";
  $t_name[H_DRIVE] = "Hoverantrieb";
  $t_name[A_DRIVE] = "Antigravitationsantrieb";
  $t_name[E_WEAPONS] = "Elektronensequenzwaffen";
  $t_name[P_WEAPONS] = "Protonensequenzwaffen";
  $t_name[N_WEAPONS] = "Neutronensequenzwaffen";
  $t_name[CONSUMPTION] = "Treibstoffverbrauch-Reduktion";
  $t_name[PLANE_SIZE] = "Flugzeugkapazitätsverwaltung";
  $t_name[COMP_MANAGEMENT] = "Computermanagement";
  $t_name[DEPOT_MANAGEMENT] = "Lagerverwaltung";
  $t_name[COMPRESSION] = "Wasserkompression";
  $t_name[MINING] = "Bergbautechnik";
//  $t_name[SHIELD_TECH] = "Schutzschild-Technologie";

  $t_db_name = null;
  $t_db_name[O_DRIVE] = "oxidationsdrive";
  $t_db_name[H_DRIVE] = "hoverdrive";
  $t_db_name[A_DRIVE] = "antigravitydrive";
  $t_db_name[E_WEAPONS] = "electronsequenzweapons";
  $t_db_name[P_WEAPONS] = "protonsequenzweapons";
  $t_db_name[N_WEAPONS] = "neutronsequenzweapons";
  $t_db_name[CONSUMPTION] = "consumption_reduction";
  $t_db_name[PLANE_SIZE] = "plane_size";
  $t_db_name[COMP_MANAGEMENT] = "computer_management";
  $t_db_name[DEPOT_MANAGEMENT] = "depot_management";
  $t_db_name[COMPRESSION] = "water_compression";
  $t_db_name[MINING] = "mining";
//  $t_db_name[SHIELD_TECH] = "shield_tech";
  $t_db_name[EW_WEAPONS] = "electronsequenzweapons AS ew";
  $t_db_name[PW_WEAPONS] = "protonsequenzweapons AS pw";
  $t_db_name[NW_WEAPONS] = "neutronsequenzweapons AS nw";

  $t_category = null;
  $t_category[O_DRIVE] = $MESSAGES[MSG_TECH_CENTER]['m003']; // Antriebstechnologien
  $t_category[H_DRIVE] = $MESSAGES[MSG_TECH_CENTER]['m003'];
  $t_category[A_DRIVE] = $MESSAGES[MSG_TECH_CENTER]['m003'];
  $t_category[E_WEAPONS] = $MESSAGES[MSG_TECH_CENTER]['m004']; // Waffentechnologien
  $t_category[P_WEAPONS] = $MESSAGES[MSG_TECH_CENTER]['m004'];
  $t_category[N_WEAPONS] = $MESSAGES[MSG_TECH_CENTER]['m004'];
  $t_category[CONSUMPTION] = $MESSAGES[MSG_TECH_CENTER]['m005']; // Weitere Flugzeugtechnologien
  $t_category[PLANE_SIZE] = $MESSAGES[MSG_TECH_CENTER]['m005'];
  $t_category[COMP_MANAGEMENT] = $MESSAGES[MSG_TECH_CENTER]['m005'];
  $t_category[DEPOT_MANAGEMENT] = $MESSAGES[MSG_TECH_CENTER]['m006'];
  $t_category[COMPRESSION] = $MESSAGES[MSG_TECH_CENTER]['m006'];
  $t_category[MINING] = $MESSAGES[MSG_TECH_CENTER]['m006'];
//  $t_category[SHIELD_TECH] = $MESSAGES[MSG_TECH_CENTER]['m006']; // Gebäude-Technologien

  $t_pricing_holzium = null;
  $t_pricing_holzium[O_DRIVE] = "T_LIN";
  $t_pricing_holzium[H_DRIVE] = "T_LIN";
  $t_pricing_holzium[A_DRIVE] = "T_LIN";
  $t_pricing_holzium[E_WEAPONS] = "T_LIN";
  $t_pricing_holzium[P_WEAPONS] = "T_LIN";
  $t_pricing_holzium[N_WEAPONS] = "T_LIN";
  $t_pricing_holzium[CONSUMPTION] = "T_LIN";
  $t_pricing_holzium[PLANE_SIZE] = "T_EXP_PS_HZ";
  $t_pricing_holzium[COMP_MANAGEMENT] = "T_LIN";
  $t_pricing_holzium[DEPOT_MANAGEMENT] = "T_LIN";
  $t_pricing_holzium[COMPRESSION] = "T_EXP_WK_HZ";
  $t_pricing_holzium[MINING] = "T_EXP_BBT_HZ";
//  $t_pricing_holzium[SHIELD_TECH] = "T_LIN";

  $t_pricing_oxygen = null;
  $t_pricing_oxygen[O_DRIVE] = "T_LIN";
  $t_pricing_oxygen[H_DRIVE] = "T_LIN";
  $t_pricing_oxygen[A_DRIVE] = "T_LIN";
  $t_pricing_oxygen[E_WEAPONS] = "T_LIN";
  $t_pricing_oxygen[P_WEAPONS] = "T_LIN";
  $t_pricing_oxygen[N_WEAPONS] = "T_LIN";
  $t_pricing_oxygen[CONSUMPTION] = "T_LIN";
  $t_pricing_oxygen[PLANE_SIZE] = "T_EXP_PS_OX";
  $t_pricing_oxygen[COMP_MANAGEMENT] = "T_LIN";
  $t_pricing_oxygen[DEPOT_MANAGEMENT] = "T_LIN";
  $t_pricing_oxygen[COMPRESSION] = "T_EXP_WK_OX";
  $t_pricing_oxygen[MINING] = "T_EXP_BBT_OX";
//  $t_pricing_oxygen[SHIELD_TECH] = "T_LIN";

  $t_holzium = null;
  $t_holzium[O_DRIVE] = 1500;
  $t_holzium[H_DRIVE] = 12000;
  $t_holzium[A_DRIVE] = 35000;
  $t_holzium[E_WEAPONS] = 2500;
  $t_holzium[P_WEAPONS] = 14000;
  $t_holzium[N_WEAPONS] = 50000;
  $t_holzium[CONSUMPTION] = 1500;
  $t_holzium[PLANE_SIZE] = 2500;
  $t_holzium[COMP_MANAGEMENT] = 3000;
  $t_holzium[DEPOT_MANAGEMENT] = 1500;
  $t_holzium[COMPRESSION] = 1000;
  $t_holzium[MINING] = 6000;
//  $t_holzium[SHIELD_TECH] = 75000;

  $t_oxygen = null;
  $t_oxygen[O_DRIVE] = 1000;
  $t_oxygen[H_DRIVE] = 10000;
  $t_oxygen[A_DRIVE] = 27000;
  $t_oxygen[E_WEAPONS] = 2000;
  $t_oxygen[P_WEAPONS] = 10000;
  $t_oxygen[N_WEAPONS] = 29000;
  $t_oxygen[CONSUMPTION] = 1200;
  $t_oxygen[PLANE_SIZE] = 3700;
  $t_oxygen[COMP_MANAGEMENT] = 4000;
  $t_oxygen[DEPOT_MANAGEMENT] = 3000;
  $t_oxygen[COMPRESSION] = 4000;
  $t_oxygen[MINING] = 10000;
//  $t_oxygen[SHIELD_TECH] = 44000;

  $t_duration = null;
  $t_duration[O_DRIVE] = 25000;
  $t_duration[H_DRIVE] = 20000;
  $t_duration[A_DRIVE] = 17500;
  $t_duration[E_WEAPONS] = 4*3600;
  $t_duration[P_WEAPONS] = 6*3600;
  $t_duration[N_WEAPONS] = 8*3600;
  $t_duration[CONSUMPTION] = 12000;
  $t_duration[PLANE_SIZE] = 13000;
  $t_duration[COMP_MANAGEMENT] = 14000;
  $t_duration[DEPOT_MANAGEMENT] = 15000;
  $t_duration[COMPRESSION] = 22200;
  $t_duration[MINING] = 29400;
//  $t_duration[SHIELD_TECH] = 75000;


  $t_tech = null;
  $t_tech[O_DRIVE][T_TECH1] = NOTECH;
  $t_tech[H_DRIVE][T_TECH1] = O_DRIVE;
  $t_tech[A_DRIVE][T_TECH1] = O_DRIVE;
  $t_tech[E_WEAPONS][T_TECH1] = NOTECH;
  $t_tech[P_WEAPONS][T_TECH1] = E_WEAPONS;
  $t_tech[N_WEAPONS][T_TECH1] = P_WEAPONS;
  $t_tech[CONSUMPTION][T_TECH1] = NOTECH;
  $t_tech[PLANE_SIZE][T_TECH1] = DEPOT_MANAGEMENT;
  $t_tech[COMP_MANAGEMENT][T_TECH1] = NOTECH;
  $t_tech[DEPOT_MANAGEMENT][T_TECH1] = NOTECH;
  $t_tech[COMPRESSION][T_TECH1] = NOTECH;
  $t_tech[MINING][T_TECH1] = NOTECH;
//  $t_tech[SHIELD_TECH][T_TECH1] = NOTECH;

  $t_tech[O_DRIVE][T_TECH2] = NOTECH;
  $t_tech[H_DRIVE][T_TECH2] = NOTECH;
  $t_tech[A_DRIVE][T_TECH2] = H_DRIVE;
  $t_tech[E_WEAPONS][T_TECH2] = NOTECH;
  $t_tech[P_WEAPONS][T_TECH2] = NOTECH;
  $t_tech[N_WEAPONS][T_TECH2] = NOTECH;
  $t_tech[CONSUMPTION][T_TECH2] = NOTECH;
  $t_tech[PLANE_SIZE][T_TECH2] = NOTECH;
  $t_tech[COMP_MANAGEMENT][T_TECH2] = NOTECH;
  $t_tech[DEPOT_MANAGEMENT][T_TECH2] = NOTECH;
  $t_tech[COMPRESSION][T_TECH2] = NOTECH;
  $t_tech[MINING][T_TECH2] = NOTECH;
//  $t_tech[SHIELD_TECH][T_TECH2] = NOTECH;

  $t_tech[O_DRIVE][T_BUILD1] = NOBUILD;
  $t_tech[H_DRIVE][T_BUILD1] = NOBUILD;
  $t_tech[A_DRIVE][T_BUILD1] = NOBUILD;
  $t_tech[E_WEAPONS][T_BUILD1] = NOBUILD;
  $t_tech[P_WEAPONS][T_BUILD1] = TECH_CENTER;
  $t_tech[N_WEAPONS][T_BUILD1] = TECH_CENTER;
  $t_tech[CONSUMPTION][T_BUILD1] = NOBUILD;
  $t_tech[PLANE_SIZE][T_BUILD1] = NOBUILD;
  $t_tech[COMP_MANAGEMENT][T_BUILD1] = HANGAR;
  $t_tech[DEPOT_MANAGEMENT][T_BUILD1] = NOBUILD;
  $t_tech[COMPRESSION][T_BUILD1] = OX_REACTOR;
  $t_tech[MINING][T_BUILD1] = IR_MINE;
//  $t_tech[SHIELD_TECH][T_BUILD1] = SHIELD;

  $t_tech[O_DRIVE][T_BUILD2] = NOBUILD;
  $t_tech[H_DRIVE][T_BUILD2] = NOBUILD;
  $t_tech[A_DRIVE][T_BUILD2] = NOBUILD;
  $t_tech[E_WEAPONS][T_BUILD2] = NOBUILD;
  $t_tech[P_WEAPONS][T_BUILD2] = NOBUILD;
  $t_tech[N_WEAPONS][T_BUILD2] = NOBUILD;
  $t_tech[CONSUMPTION][T_BUILD2] = NOBUILD;
  $t_tech[PLANE_SIZE][T_BUILD2] = NOBUILD;
  $t_tech[COMP_MANAGEMENT][T_BUILD2] = NOBUILD;
  $t_tech[DEPOT_MANAGEMENT][T_BUILD2] = NOBUILD;
  $t_tech[COMPRESSION][T_BUILD2] = NOBUILD;
  $t_tech[MINING][T_BUILD2] = HZ_PLANTAGE;
//  $t_tech[SHIELD_TECH][T_BUILD2] = NOBUILD;


  $t_need_techs = null;
  $t_need_techs[O_DRIVE][O_DRIVE] = 0;
  $t_need_techs[H_DRIVE][O_DRIVE] = 5;
  $t_need_techs[A_DRIVE][O_DRIVE] = 10;
  $t_need_techs[E_WEAPONS][O_DRIVE] = 0;
  $t_need_techs[P_WEAPONS][O_DRIVE] = 0;
  $t_need_techs[N_WEAPONS][O_DRIVE] = 0;
  $t_need_techs[CONSUMPTION][O_DRIVE] = 0;
  $t_need_techs[PLANE_SIZE][O_DRIVE] = 0;
  $t_need_techs[COMP_MANAGEMENT][O_DRIVE] = 0;
  $t_need_techs[DEPOT_MANAGEMENT][O_DRIVE] = 0;
  $t_need_techs[COMPRESSION][O_DRIVE] = 0;
  $t_need_techs[MINING][O_DRIVE] = 0;
//  $t_need_techs[SHIELD_TECH][O_DRIVE] = 0;

  $t_need_techs[O_DRIVE][H_DRIVE] = 0;
  $t_need_techs[H_DRIVE][H_DRIVE] = 0;
  $t_need_techs[A_DRIVE][H_DRIVE] = 5;
  $t_need_techs[E_WEAPONS][H_DRIVE] = 0;
  $t_need_techs[P_WEAPONS][H_DRIVE] = 0;
  $t_need_techs[N_WEAPONS][H_DRIVE] = 0;
  $t_need_techs[CONSUMPTION][H_DRIVE] = 0;
  $t_need_techs[PLANE_SIZE][H_DRIVE] = 0;
  $t_need_techs[COMP_MANAGEMENT][H_DRIVE] = 0;
  $t_need_techs[DEPOT_MANAGEMENT][H_DRIVE] = 0;
  $t_need_techs[COMPRESSION][H_DRIVE] = 0;
  $t_need_techs[MINING][H_DRIVE] = 0;
//  $t_need_techs[SHIELD_TECH][H_DRIVE] = 0;

  $t_need_techs[O_DRIVE][E_WEAPONS] = 0;
  $t_need_techs[H_DRIVE][E_WEAPONS] = 0;
  $t_need_techs[A_DRIVE][E_WEAPONS] = 0;
  $t_need_techs[E_WEAPONS][E_WEAPONS] = 0;
  $t_need_techs[P_WEAPONS][E_WEAPONS] = 5;
  $t_need_techs[N_WEAPONS][E_WEAPONS] = 10;
  $t_need_techs[CONSUMPTION][E_WEAPONS] = 0;
  $t_need_techs[PLANE_SIZE][E_WEAPONS] = 0;
  $t_need_techs[COMP_MANAGEMENT][E_WEAPONS] = 0;
  $t_need_techs[DEPOT_MANAGEMENT][E_WEAPONS] = 0;
  $t_need_techs[COMPRESSION][E_WEAPONS] = 0;
  $t_need_techs[MINING][E_WEAPONS] = 0;
//  $t_need_techs[SHIELD_TECH][E_WEAPONS] = 0;

  $t_need_techs[O_DRIVE][P_WEAPONS] = 0;
  $t_need_techs[H_DRIVE][P_WEAPONS] = 0;
  $t_need_techs[A_DRIVE][P_WEAPONS] = 0;
  $t_need_techs[E_WEAPONS][P_WEAPONS] = 0;
  $t_need_techs[P_WEAPONS][P_WEAPONS] = 0;
  $t_need_techs[N_WEAPONS][P_WEAPONS] = 5;
  $t_need_techs[CONSUMPTION][P_WEAPONS] = 0;
  $t_need_techs[PLANE_SIZE][P_WEAPONS] = 0;
  $t_need_techs[COMP_MANAGEMENT][P_WEAPONS] = 0;
  $t_need_techs[DEPOT_MANAGEMENT][P_WEAPONS] = 0;
  $t_need_techs[COMPRESSION][P_WEAPONS] = 0;
  $t_need_techs[MINING][P_WEAPONS] = 0;
//  $t_need_techs[SHIELD_TECH][P_WEAPONS] = 0;

  $t_need_techs[O_DRIVE][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[H_DRIVE][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[A_DRIVE][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[E_WEAPONS][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[P_WEAPONS][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[N_WEAPONS][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[CONSUMPTION][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[PLANE_SIZE][DEPOT_MANAGEMENT] = 10;
  $t_need_techs[COMP_MANAGEMENT][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[DEPOT_MANAGEMENT][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[COMPRESSION][DEPOT_MANAGEMENT] = 0;
  $t_need_techs[MINING][DEPOT_MANAGEMENT] = 0;
//  $t_need_techs[SHIELD_TECH][DEPOT_MANAGEMENT] = 0;

  $t_need_builds = null;
  $t_need_builds[O_DRIVE][IR_MINE] = 0;
  $t_need_builds[H_DRIVE][IR_MINE] = 0;
  $t_need_builds[A_DRIVE][IR_MINE] = 0;
  $t_need_builds[E_WEAPONS][IR_MINE] = 0;
  $t_need_builds[P_WEAPONS][IR_MINE] = 0;
  $t_need_builds[N_WEAPONS][IR_MINE] = 0;
  $t_need_builds[CONSUMPTION][IR_MINE] = 0;
  $t_need_builds[PLANE_SIZE][IR_MINE] = 0;
  $t_need_builds[COMP_MANAGEMENT][IR_MINE] = 0;
  $t_need_builds[DEPOT_MANAGEMENT][IR_MINE] = 0;
  $t_need_builds[COMPRESSION][IR_MINE] = 0;
  $t_need_builds[MINING][IR_MINE] = 5;
//  $t_need_builds[SHIELD_TECH][IR_MINE] = 0;

  $t_need_builds[O_DRIVE][HZ_PLANTAGE] = 0;
  $t_need_builds[H_DRIVE][HZ_PLANTAGE] = 0;
  $t_need_builds[A_DRIVE][HZ_PLANTAGE] = 0;
  $t_need_builds[E_WEAPONS][HZ_PLANTAGE] = 0;
  $t_need_builds[P_WEAPONS][HZ_PLANTAGE] = 0;
  $t_need_builds[N_WEAPONS][HZ_PLANTAGE] = 0;
  $t_need_builds[CONSUMPTION][HZ_PLANTAGE] = 0;
  $t_need_builds[MINING][HZ_PLANTAGE] = 5;
  $t_need_builds[COMP_MANAGEMENT][HZ_PLANTAGE] = 0;
  $t_need_builds[DEPOT_MANAGEMENT][HZ_PLANTAGE] = 0;
  $t_need_builds[PLANE_SIZE][HZ_PLANTAGE] = 0;
  $t_need_builds[COMPRESSION][HZ_PLANTAGE] = 0;
//  $t_need_builds[SHIELD_TECH][HZ_PLANTAGE] = 0;

  $t_need_builds[O_DRIVE][OX_REACTOR] = 0;
  $t_need_builds[H_DRIVE][OX_REACTOR] = 0;
  $t_need_builds[A_DRIVE][OX_REACTOR] = 0;
  $t_need_builds[E_WEAPONS][OX_REACTOR] = 0;
  $t_need_builds[P_WEAPONS][OX_REACTOR] = 0;
  $t_need_builds[N_WEAPONS][OX_REACTOR] = 0;
  $t_need_builds[CONSUMPTION][OX_REACTOR] = 0;
  $t_need_builds[PLANE_SIZE][OX_REACTOR] = 0;
  $t_need_builds[COMP_MANAGEMENT][OX_REACTOR] = 0;
  $t_need_builds[DEPOT_MANAGEMENT][OX_REACTOR] = 0;
  $t_need_builds[COMPRESSION][OX_REACTOR] = 10;
  $t_need_builds[MINING][OX_REACTOR] = 0;
//  $t_need_builds[SHIELD_TECH][OX_REACTOR] = 0;

  $t_need_builds[O_DRIVE][HANGAR] = 0;
  $t_need_builds[H_DRIVE][HANGAR] = 0;
  $t_need_builds[A_DRIVE][HANGAR] = 0;
  $t_need_builds[E_WEAPONS][HANGAR] = 0;
  $t_need_builds[P_WEAPONS][HANGAR] = 0;
  $t_need_builds[N_WEAPONS][HANGAR] = 0;
  $t_need_builds[CONSUMPTION][HANGAR] = 0;
  $t_need_builds[PLANE_SIZE][HANGAR] = 0;
  $t_need_builds[COMP_MANAGEMENT][HANGAR] = 5;
  $t_need_builds[DEPOT_MANAGEMENT][HANGAR] = 0;
  $t_need_builds[COMPRESSION][HANGAR] = 0;
  $t_need_builds[MINING][HANGAR] = 0;
//  $t_need_builds[SHIELD_TECH][HANGAR] = 0;

  $t_need_builds[O_DRIVE][TECH_CENTER] = 0;
  $t_need_builds[H_DRIVE][TECH_CENTER] = 0;
  $t_need_builds[A_DRIVE][TECH_CENTER] = 0;
  $t_need_builds[E_WEAPONS][TECH_CENTER] = 0;
  $t_need_builds[P_WEAPONS][TECH_CENTER] = 5;
  $t_need_builds[N_WEAPONS][TECH_CENTER] = 10;
  $t_need_builds[CONSUMPTION][TECH_CENTER] = 0;
  $t_need_builds[PLANE_SIZE][TECH_CENTER] = 0;
  $t_need_builds[COMP_MANAGEMENT][TECH_CENTER] = 5;
  $t_need_builds[DEPOT_MANAGEMENT][TECH_CENTER] = 0;
  $t_need_builds[COMPRESSION][TECH_CENTER] = 0;
  $t_need_builds[MINING][TECH_CENTER] = 0;
//  $t_need_builds[SHIELD_TECH][TECH_CENTER] = 0;

//  $t_need_builds[O_DRIVE][SHIELD] = 0;
//  $t_need_builds[H_DRIVE][SHIELD] = 0;
//  $t_need_builds[A_DRIVE][SHIELD] = 0;
//  $t_need_builds[E_WEAPONS][SHIELD] = 0;
//  $t_need_builds[P_WEAPONS][SHIELD] = 5;
//  $t_need_builds[N_WEAPONS][SHIELD] = 10;
//  $t_need_builds[CONSUMPTION][SHIELD] = 0;
//  $t_need_builds[PLANE_SIZE][SHIELD] = 0;
//  $t_need_builds[COMP_MANAGEMENT][SHIELD] = 5;
//  $t_need_builds[DEPOT_MANAGEMENT][SHIELD] = 0;
//  $t_need_builds[COMPRESSION][SHIELD] = 0;
//  $t_need_builds[MINING][SHIELD] = 0;
//  $t_need_builds[SHIELD_TECH][SHIELD] = 10;

  $t_description = null;
  $t_description[O_DRIVE] = "Verbesserter Antrieb der alten Flugzeugverbrennungsmotoren, bei denen Sauerstoff angesaugt wird. Danach wird dieser innerhalb des Triebwerkes hoch verdichtet, erhitzt und über bewegliche Düsen ausgestoßen, was zu einer Vorwärtsbewegung führt.<br/>Bonus: +<b>".$t_increase[O_DRIVE]."km/h</b> pro Stufe; wirkt auf Sparrow, Blackbird, Raven, Scarecrow und Settler.";
  $t_description[H_DRIVE] = "Nachdem die begrenzten Möglichkeiten des noch primitiven Oxidationsantriebes ausgereizt waren, gelang den Wissenschaftlern der Durchbruch auf dem Gebiet der Korpuskularteilchenforschung. Dabei wird für diese neue Technologie der Compton-Effekt ausgenutzt, durch den andere Teilchen zum Schwingen angeregt werden und somit ein auf dem von Planck definierten Wirkungsquantum einen Vortrieb hervorruft.<br/>Bonus: +<b>".$t_increase[H_DRIVE]."km/h</b> pro Stufe; wirkt auf Eagle, Falcon, Nightingale, mittleres Transportflugzeug.";
  $t_description[A_DRIVE] = "Dieser Antrieb basiert auf der entgegengesetzten Rotation zweier Scheiben, welche durch hoch verdichteten Sauerstoff angetrieben werden. Durch diese Rotation wird die Schwerkraft aufgehoben und das Flugzeug kann unter geringem Energieaufwand vorwärts bewegt werden.<br/>Bonus: +<b>".$t_increase[A_DRIVE]."km/h</b> pro Stufe; wirkt auf Ravager, Destroyer, grosses Transportflugzeug.";
  $t_description[E_WEAPONS] = "Diese Waffentechnologie basiert auf der überladung der gegnerischen Flugzeughülle mit Elektronen, wodurch die Elemente der Hülle zu einer atomaren Reaktion angeregt werden und der Gegner mit hoher Wahrscheinlichkeit in einem atomaren Glutball vergehen wird. Doch diese Technik ist sehr anfällig gegenüber Magnetfeldern, wodurch ihre Funktion teilweise stark eingeschränkt werden kann. <br/>Bonus: +<b>".$t_increase[E_WEAPONS]." KW</b> pro Stufe für Sparrow, Blackbird, Raven, Elektronensequenzer; +<b>2 KW</b> pro Stufe für Elektronenwoofer";
  $t_description[P_WEAPONS] = "Eine Waffentechnologie, welche auf dem von Hook gefundenen Quantenfeld beruht. Dabei wird innerhalb eines Objektes (abhängig von der Art), ein hochfrequentes Wirkungsfeld aufgebaut (durch Protonenüberladung), in welches Nanobomben aus einem anderen Schiff oder Turm transmittiert werden. Diese Bomben haben zwar nicht die allerhöchste Einschlagskraft, können jedoch durch hohen und frequenten Beschuss selbst Destroyer vernichten.<br/>Bonus: +<b>".$t_increase[P_WEAPONS]." KW</b> pro Stufe für Eagle, Falcon, Nightingale, Protonensequenzer; +<b>4 KW</b> pro Stufe für Protonenwoofer";
  $t_description[N_WEAPONS] = "Basierend auf den Forschungen von Alarius Kearo, bei denen dieser herausfand, dass Neutronen durch den Beschuss von Quarks zu Antineutrinos werden, entwickelte man diese Waffetechnologie. Dabei werden in einem entsprechend großen Hyperumfeldleiter extrapolierte Neutronen durch niederenergetische U-Quarks beschossen, wodurch eine Umwandlung der Neutronen zu Antineutrinos erfolgt. Diese haben die Eigenschaften von Antimaterie, d.h. sobald sie mit Materie zusammentrifft, lösen sich beide in einem grellen Blitz auf. Somit können Antineutrinos nur von einem Antimagnetfeld aufgehalten werden, was sehr große Energiekapazitäten beansprucht und somit nur in großen Flugzeugen zum Einsatz kommen kann.<br/>Bonus: +<b>".$t_increase[N_WEAPONS]." KW</b> pro Stufe für Ravager, Destroyer, Neutronensequenzer; +<b>8 KW</b> pro Stufe für Neutronenwoofer";
  $t_description[CONSUMPTION] = "Pro erforschter Stufe sinkt der Treibstoffverbrauch aller Flugzeuge um 5%";
  $t_description[PLANE_SIZE] = "Jede Stufe dieser Technologie erhöht die Ladekapazität aller Flugzeuge um 5%";
  $t_description[COMP_MANAGEMENT] = "Mit jeder Stufe dieser Verwaltungstechnik steigt die maximale Flottengrösse um 3 Flugzeuge. Diese Technologie wirkt sich auf alle Städte innerhalb des Accounts aus.";
  $t_description[DEPOT_MANAGEMENT] = "Mit jeder Stufe dieser Forschung erhöht sich das Lager- und Tankvolumen um 5%. Diese Technologie wirkt sich auf alle Städte innerhalb des Accounts aus. ";
  $t_description[COMPRESSION] = "Je erforschter Stufe wird 5% mehr Sauerstoff produziert.";
  $t_description[MINING] = "Diese Technologie erhöht die Förderung von Iridium und Holzium um 5% pro erforschter Stufe.";
//  $t_description[SHIELD_TECH] = "Mit dieser Technologie kannst du die Stärke deines Schildes erhöhen. Das Erforschen der Schutzschildtechnologie wirkt sich jedoch negativ auf <a href=\"http://forum.escape-to-space.de/viewtopic.php?p=659996#p659996\">spätere Waffentechnologieforschungen</a> aus";

/*****************/

  $p_name = null;
  $p_name[SPARROW] = "Sparrow";
  $p_name[BLACKBIRD] = "Blackbird";
  $p_name[RAVEN] = "Raven";
  $p_name[EAGLE] = "Eagle";
  $p_name[FALCON] = "Falcon";
  $p_name[NIGHTINGALE] = "Nightingale";
  $p_name[SETTLER] = "Settler";
  $p_name[SCARECROW] = "Scarecrow";
  $p_name[RAVAGER] = "Ravager";
//  $p_name[BOMBER] = "Hesse-Bomber";
  $p_name[DESTROYER] = "Destroyer";
  $p_name[ESPIONAGE_PROBE] = "Spionagesonde";
  $p_name[SMALL_TRANSPORTER] = "Kleines Transportflugzeug";
  $p_name[MEDIUM_TRANSPORTER] = "Mittleres Transportflugzeug";
  $p_name[BIG_TRANSPORTER] = "Großes Transportflugzeug";
  
  $p_id = null;
  $p_id[SPARROW] = "1";
  $p_id[BLACKBIRD] = "2";
  $p_id[RAVEN] = "3";
  $p_id[EAGLE] = "4";
  $p_id[FALCON] = "5";
  $p_id[NIGHTINGALE] = "6";
  $p_id[SETTLER] = "10";
  $p_id[SCARECROW] = "11";
  $p_id[RAVAGER] = "7";
//  $p_id[BOMBER] = "Hesse-Bomber";
  $p_id[DESTROYER] = "8";
  $p_id[ESPIONAGE_PROBE] = "9";
  $p_id[SMALL_TRANSPORTER] = "19";
  $p_id[MEDIUM_TRANSPORTER] = "20";
  $p_id[BIG_TRANSPORTER] = "21";
  

  $p_db_name = null;
  $p_db_name[SPARROW] = "_sparrow";
  $p_db_name[BLACKBIRD] = "_blackbird";
  $p_db_name[RAVEN] = "_raven";
  $p_db_name[EAGLE] = "_eagle";
  $p_db_name[FALCON] = "_falcon";
  $p_db_name[NIGHTINGALE] = "_nightingale";
  $p_db_name[RAVAGER] = "_ravager";
  $p_db_name[DESTROYER] = "_destroyer";
  $p_db_name[ESPIONAGE_PROBE] = "_espionage_probe";
  $p_db_name[SETTLER] = "_settler";
  $p_db_name[SCARECROW] = "_scarecrow";
//  $p_db_name[BOMBER] = "_bomber";
  $p_db_name[SMALL_TRANSPORTER] = "_small_transporter";
  $p_db_name[MEDIUM_TRANSPORTER] = "_medium_transporter";
  $p_db_name[BIG_TRANSPORTER] = "_big_transporter";

  // wus = without underscore

  $p_db_name_wus = null;
  $p_db_name_wus[SPARROW] = "sparrow";
  $p_db_name_wus[BLACKBIRD] = "blackbird";
  $p_db_name_wus[RAVEN] = "raven";
  $p_db_name_wus[EAGLE] = "eagle";
  $p_db_name_wus[FALCON] = "falcon";
  $p_db_name_wus[NIGHTINGALE] = "nightingale";
  $p_db_name_wus[RAVAGER] = "ravager";
  $p_db_name_wus[DESTROYER] = "destroyer";
  $p_db_name_wus[ESPIONAGE_PROBE] = "espionage_probe";
  $p_db_name_wus[SETTLER] = "settler";
  $p_db_name_wus[SCARECROW] = "scarecrow";
//  $p_db_name_wus[BOMBER] = "bomber";
  $p_db_name_wus[SMALL_TRANSPORTER] = "small_transporter";
  $p_db_name_wus[MEDIUM_TRANSPORTER] = "medium_transporter";
  $p_db_name_wus[BIG_TRANSPORTER] = "big_transporter";

  $p_category = null;
  $p_category[SPARROW] = $MESSAGES[MSG_HANGAR]['m014']; // Kampfflugzeuge
  $p_category[BLACKBIRD] = $MESSAGES[MSG_HANGAR]['m014'];
  $p_category[RAVEN] = $MESSAGES[MSG_HANGAR]['m014'];
  $p_category[EAGLE] = $MESSAGES[MSG_HANGAR]['m014'];
  $p_category[FALCON] = $MESSAGES[MSG_HANGAR]['m014'];
  $p_category[NIGHTINGALE] = $MESSAGES[MSG_HANGAR]['m014'];
  $p_category[RAVAGER] = $MESSAGES[MSG_HANGAR]['m014'];
  $p_category[DESTROYER] = $MESSAGES[MSG_HANGAR]['m014'];
  $p_category[ESPIONAGE_PROBE] = $MESSAGES[MSG_HANGAR]['m019']; // Spezielle Kampfflugzeuge
  $p_category[SETTLER] = $MESSAGES[MSG_HANGAR]['m019'];
  $p_category[SCARECROW] = $MESSAGES[MSG_HANGAR]['m019'];
//  $p_category[BOMBER] = $MESSAGES[MSG_HANGAR]['m019'];
  $p_category[SMALL_TRANSPORTER] = $MESSAGES[MSG_HANGAR]['m015']; // Transporter
  $p_category[MEDIUM_TRANSPORTER] = $MESSAGES[MSG_HANGAR]['m015'];
  $p_category[BIG_TRANSPORTER] = $MESSAGES[MSG_HANGAR]['m015'];

  $p_power = null;
  $p_power[SPARROW] = 100;
  $p_power[BLACKBIRD] = 1000;
  $p_power[RAVEN] = 350;
  $p_power[EAGLE] = 500;
  $p_power[FALCON] = 2000;
  $p_power[NIGHTINGALE] = 1500;
  $p_power[RAVAGER] = 2500;
  $p_power[DESTROYER] = 4000;
  $p_power[ESPIONAGE_PROBE] = 1;
  $p_power[SETTLER] = 1000;
  $p_power[SCARECROW] = 1200;
//  $p_power[BOMBER] = 1300;
  $p_power[SMALL_TRANSPORTER] = 0;
  $p_power[MEDIUM_TRANSPORTER] = 0;
  $p_power[BIG_TRANSPORTER] = 0;

  $p_speed = null;
  $p_speed[SPARROW] = 2000;
  $p_speed[BLACKBIRD] = 100;
  $p_speed[RAVEN] = 2300;
  $p_speed[EAGLE] = 1600;
  $p_speed[FALCON] = 50;
  $p_speed[NIGHTINGALE] = 2000;
  $p_speed[RAVAGER] = 1600;
  $p_speed[DESTROYER] = 1200;
  $p_speed[ESPIONAGE_PROBE] = 20000;
  $p_speed[SETTLER] = 1000;
  $p_speed[SCARECROW] = 1500;
//  $p_speed[BOMBER] = 1500;
  $p_speed[SMALL_TRANSPORTER] = 700;
  $p_speed[MEDIUM_TRANSPORTER] = 1300;
  $p_speed[BIG_TRANSPORTER] = 2000;

  $p_consumption = null;
  $p_consumption[SPARROW] = 70;
  $p_consumption[BLACKBIRD] = 100;
  $p_consumption[RAVEN] = 50;
  $p_consumption[EAGLE] = 75;
  $p_consumption[FALCON] = 150;
  $p_consumption[NIGHTINGALE] = 200;
  $p_consumption[RAVAGER] = 350;
  $p_consumption[DESTROYER] = 450;
  $p_consumption[ESPIONAGE_PROBE] = 8;
  $p_consumption[SETTLER] = 300;
  $p_consumption[SCARECROW] = 160;
//  $p_consumption[BOMBER] = 250;
  $p_consumption[SMALL_TRANSPORTER] = 30;
  $p_consumption[MEDIUM_TRANSPORTER] = 70;
  $p_consumption[BIG_TRANSPORTER] = 200;

  $p_capacity = null;
  $p_capacity[SPARROW] = 500;
  $p_capacity[BLACKBIRD] = 0;
  $p_capacity[RAVEN] = 1500;
  $p_capacity[EAGLE] = 2500;
  $p_capacity[FALCON] = 0;
  $p_capacity[NIGHTINGALE] = 10000;
  $p_capacity[RAVAGER] = 7500;
  $p_capacity[DESTROYER] = 12500;
  $p_capacity[ESPIONAGE_PROBE] = 0;
  $p_capacity[SETTLER] = 0;
  $p_capacity[SCARECROW] = 0;
//  $p_capacity[BOMBER] = 50000;
  $p_capacity[SMALL_TRANSPORTER] = 2500;
  $p_capacity[MEDIUM_TRANSPORTER] = 10000;
  $p_capacity[BIG_TRANSPORTER] = 100000;

  $p_iridium = null;
  $p_iridium[SPARROW] = 500;
  $p_iridium[BLACKBIRD] = 4000;
  $p_iridium[RAVEN] = 1500;
  $p_iridium[EAGLE] = 2500;
  $p_iridium[FALCON] = 6000;
  $p_iridium[NIGHTINGALE] = 7500;
  $p_iridium[RAVAGER] = 15000;
  $p_iridium[DESTROYER] = 35000;
  $p_iridium[ESPIONAGE_PROBE] = 125;
  $p_iridium[SETTLER] = 300000;
  $p_iridium[SCARECROW] = 600000;
//  $p_iridium[BOMBER] = 100000;
  $p_iridium[SMALL_TRANSPORTER] = 1700;
  $p_iridium[MEDIUM_TRANSPORTER] = 5000;
  $p_iridium[BIG_TRANSPORTER] = 30000;

  $p_holzium = null;
  $p_holzium[SPARROW] = 500;
  $p_holzium[BLACKBIRD] = 5000;
  $p_holzium[RAVEN] = 1500;
  $p_holzium[EAGLE] = 2500;
  $p_holzium[FALCON] = 7500;
  $p_holzium[NIGHTINGALE] = 12500;
  $p_holzium[RAVAGER] = 20000;
  $p_holzium[DESTROYER] = 30000;
  $p_holzium[ESPIONAGE_PROBE] = 75;
  $p_holzium[SETTLER] = 250000;
  $p_holzium[SCARECROW] = 500000;
//  $p_holzium[BOMBER] = 50000;
  $p_holzium[SMALL_TRANSPORTER] = 700;
  $p_holzium[MEDIUM_TRANSPORTER] = 4000;
  $p_holzium[BIG_TRANSPORTER] = 25000;

  $p_duration = null;
  $p_duration[SPARROW] = 10*60;
  $p_duration[BLACKBIRD] = 30*60;
  $p_duration[RAVEN] = 40*60;
  $p_duration[EAGLE] = 20*60;
  $p_duration[FALCON] = 60*60;
  $p_duration[NIGHTINGALE] = 100*60;
  $p_duration[RAVAGER] = 100*60;
  $p_duration[DESTROYER] = 150*60;
  $p_duration[ESPIONAGE_PROBE] = 40;
  $p_duration[SETTLER] = 16*3600;
  $p_duration[SCARECROW] = 36*3600;
//  $p_duration[BOMBER] = 48*3600;
  $p_duration[SMALL_TRANSPORTER] = 10*60;
  $p_duration[MEDIUM_TRANSPORTER] = 50*60;
  $p_duration[BIG_TRANSPORTER] = 180*60;

  $p_duration_min = null;
  $p_duration_min[SPARROW] = 1.5*60;
  $p_duration_min[BLACKBIRD] = 2.25*60;
  $p_duration_min[RAVEN] = 3*60;
  $p_duration_min[EAGLE] = 2.25*60;
  $p_duration_min[FALCON] = 3*60;
  $p_duration_min[NIGHTINGALE] = 4*60;
  $p_duration_min[RAVAGER] = 4.5*60;
  $p_duration_min[DESTROYER] = 6*60;
  $p_duration_min[ESPIONAGE_PROBE] = 1;
  $p_duration_min[SETTLER] = 3*3600;
  $p_duration_min[SCARECROW] = 16*3600;
//  $p_duration_min[BOMBER] = 24*3600;
  $p_duration_min[SMALL_TRANSPORTER] = 2.5*60;
  $p_duration_min[MEDIUM_TRANSPORTER] = 2.5*60;
  $p_duration_min[BIG_TRANSPORTER] = 2.5*60;
  
  $p_duration_half_hangar = 30;
  
  $p_tech = null;
  $p_tech[SPARROW][T_SPEED] = O_DRIVE;
  $p_tech[BLACKBIRD][T_SPEED] = O_DRIVE;
  $p_tech[RAVEN][T_SPEED] = O_DRIVE;
  $p_tech[EAGLE][T_SPEED] = H_DRIVE;
  $p_tech[FALCON][T_SPEED] = H_DRIVE;
  $p_tech[NIGHTINGALE][T_SPEED] = H_DRIVE;
  $p_tech[RAVAGER][T_SPEED] = A_DRIVE;
  $p_tech[DESTROYER][T_SPEED] = A_DRIVE;
  $p_tech[ESPIONAGE_PROBE][T_SPEED] = NOTECH;
  $p_tech[SETTLER][T_SPEED] = O_DRIVE;
  $p_tech[SCARECROW][T_SPEED] = O_DRIVE;
//  $p_tech[BOMBER][T_SPEED] = H_DRIVE;
  $p_tech[SMALL_TRANSPORTER][T_SPEED] = O_DRIVE;
  $p_tech[MEDIUM_TRANSPORTER][T_SPEED] = H_DRIVE;
  $p_tech[BIG_TRANSPORTER][T_SPEED] = A_DRIVE;

  $p_tech[SPARROW][T_POWER] = E_WEAPONS;
  $p_tech[BLACKBIRD][T_POWER] = E_WEAPONS;
  $p_tech[RAVEN][T_POWER] = E_WEAPONS;
  $p_tech[EAGLE][T_POWER] = P_WEAPONS;
  $p_tech[FALCON][T_POWER] = P_WEAPONS;
  $p_tech[NIGHTINGALE][T_POWER] = P_WEAPONS;
  $p_tech[RAVAGER][T_POWER] = N_WEAPONS;
  $p_tech[DESTROYER][T_POWER] = N_WEAPONS;
  $p_tech[ESPIONAGE_PROBE][T_POWER] = NOTECH;
  $p_tech[SETTLER][T_POWER] = DEPOT_MANAGEMENT;
  $p_tech[SCARECROW][T_POWER] = COMP_MANAGEMENT;
//  $p_tech[BOMBER][T_POWER] = P_WEAPONS;
  $p_tech[SMALL_TRANSPORTER][T_POWER] = NOTECH;
  $p_tech[MEDIUM_TRANSPORTER][T_POWER] = DEPOT_MANAGEMENT;
  $p_tech[BIG_TRANSPORTER][T_POWER] = DEPOT_MANAGEMENT;

  $p_need = null;
  $p_need[SPARROW][O_DRIVE] = 6;
  $p_need[BLACKBIRD][O_DRIVE] = 2;
  $p_need[RAVEN][O_DRIVE] = 10;
  $p_need[EAGLE][O_DRIVE] = 0;
  $p_need[FALCON][O_DRIVE] = 0;
  $p_need[NIGHTINGALE][O_DRIVE] = 0;
  $p_need[RAVAGER][O_DRIVE] = 0;
  $p_need[DESTROYER][O_DRIVE] = 0;
  $p_need[ESPIONAGE_PROBE][O_DRIVE] = 0;
  $p_need[SETTLER][O_DRIVE] = 5;
  $p_need[SCARECROW][O_DRIVE] = 10;
//  $p_need[BOMBER][O_DRIVE] = 0;
  $p_need[SMALL_TRANSPORTER][O_DRIVE] = 0;
  $p_need[MEDIUM_TRANSPORTER][O_DRIVE] = 0;
  $p_need[BIG_TRANSPORTER][O_DRIVE] = 0;

  $p_need[SPARROW][H_DRIVE] = 0;
  $p_need[BLACKBIRD][H_DRIVE] = 0;
  $p_need[RAVEN][H_DRIVE] = 0;
  $p_need[EAGLE][H_DRIVE] = 7;
  $p_need[FALCON][H_DRIVE] = 3;
  $p_need[NIGHTINGALE][H_DRIVE] = 10;
  $p_need[RAVAGER][H_DRIVE] = 0;
  $p_need[DESTROYER][H_DRIVE] = 0;
  $p_need[ESPIONAGE_PROBE][H_DRIVE] = 0;
  $p_need[SETTLER][H_DRIVE] = 0;
  $p_need[SCARECROW][H_DRIVE] = 0;
//  $p_need[BOMBER][H_DRIVE] = 300;
  $p_need[SMALL_TRANSPORTER][H_DRIVE] = 0;
  $p_need[MEDIUM_TRANSPORTER][H_DRIVE] = 3;
  $p_need[BIG_TRANSPORTER][H_DRIVE] = 0;

  $p_need[SPARROW][A_DRIVE] = 0;
  $p_need[BLACKBIRD][A_DRIVE] = 0;
  $p_need[RAVEN][A_DRIVE] = 0;
  $p_need[EAGLE][A_DRIVE] = 0;
  $p_need[FALCON][A_DRIVE] = 0;
  $p_need[NIGHTINGALE][A_DRIVE] = 0;
  $p_need[RAVAGER][A_DRIVE] = 3;
  $p_need[DESTROYER][A_DRIVE] = 8;
  $p_need[ESPIONAGE_PROBE][A_DRIVE] = 0;
  $p_need[SETTLER][A_DRIVE] = 0;
  $p_need[SCARECROW][A_DRIVE] = 0;
//  $p_need[BOMBER][A_DRIVE] = 0;
  $p_need[SMALL_TRANSPORTER][A_DRIVE] = 0;
  $p_need[MEDIUM_TRANSPORTER][A_DRIVE] = 0;
  $p_need[BIG_TRANSPORTER][A_DRIVE] = 2;

  $p_need[SPARROW][E_WEAPONS] = 2;
  $p_need[BLACKBIRD][E_WEAPONS] = 6;
  $p_need[RAVEN][E_WEAPONS] = 10;
  $p_need[EAGLE][E_WEAPONS] = 0;
  $p_need[FALCON][E_WEAPONS] = 0;
  $p_need[NIGHTINGALE][E_WEAPONS] = 0;
  $p_need[RAVAGER][E_WEAPONS] = 0;
  $p_need[DESTROYER][E_WEAPONS] = 0;
  $p_need[ESPIONAGE_PROBE][E_WEAPONS] = 0;
  $p_need[SETTLER][E_WEAPONS] = 0;
  $p_need[SCARECROW][E_WEAPONS] = 0;
//  $p_need[BOMBER][E_WEAPONS] = 0;
  $p_need[SMALL_TRANSPORTER][E_WEAPONS] = 0;
  $p_need[MEDIUM_TRANSPORTER][E_WEAPONS] = 0;
  $p_need[BIG_TRANSPORTER][E_WEAPONS] = 0;

  $p_need[SPARROW][P_WEAPONS] = 0;
  $p_need[BLACKBIRD][P_WEAPONS] = 0;
  $p_need[RAVEN][P_WEAPONS] = 0;
  $p_need[EAGLE][P_WEAPONS] = 3;
  $p_need[FALCON][P_WEAPONS] = 7;
  $p_need[NIGHTINGALE][P_WEAPONS] = 10;
  $p_need[RAVAGER][P_WEAPONS] = 0;
  $p_need[DESTROYER][P_WEAPONS] = 0;
  $p_need[ESPIONAGE_PROBE][P_WEAPONS] = 0;
  $p_need[SETTLER][P_WEAPONS] = 0;
  $p_need[SCARECROW][P_WEAPONS] = 0;
//  $p_need[BOMBER][P_WEAPONS] = 300;
  $p_need[SMALL_TRANSPORTER][P_WEAPONS] = 0;
  $p_need[MEDIUM_TRANSPORTER][P_WEAPONS] = 0;
  $p_need[BIG_TRANSPORTER][P_WEAPONS] = 0;

  $p_need[SPARROW][N_WEAPONS] = 0;
  $p_need[BLACKBIRD][N_WEAPONS] = 0;
  $p_need[RAVEN][N_WEAPONS] = 0;
  $p_need[EAGLE][N_WEAPONS] = 0;
  $p_need[FALCON][N_WEAPONS] = 0;
  $p_need[NIGHTINGALE][N_WEAPONS] = 0;
  $p_need[RAVAGER][N_WEAPONS] = 10;
  $p_need[DESTROYER][N_WEAPONS] = 20;
  $p_need[ESPIONAGE_PROBE][N_WEAPONS] = 0;
  $p_need[SETTLER][N_WEAPONS] = 0;
  $p_need[SCARECROW][N_WEAPONS] = 0;
//  $p_need[BOMBER][N_WEAPONS] = 0;
  $p_need[SMALL_TRANSPORTER][N_WEAPONS] = 0;
  $p_need[MEDIUM_TRANSPORTER][N_WEAPONS] = 0;
  $p_need[BIG_TRANSPORTER][N_WEAPONS] = 0;

  $p_need[SPARROW][DEPOT_MANAGEMENT] = 0;
  $p_need[BLACKBIRD][DEPOT_MANAGEMENT] = 0;
  $p_need[RAVEN][DEPOT_MANAGEMENT] = 0;
  $p_need[EAGLE][DEPOT_MANAGEMENT] = 0;
  $p_need[FALCON][DEPOT_MANAGEMENT] = 0;
  $p_need[NIGHTINGALE][DEPOT_MANAGEMENT] = 0;
  $p_need[RAVAGER][DEPOT_MANAGEMENT] = 0;
  $p_need[DESTROYER][DEPOT_MANAGEMENT] = 0;
  $p_need[ESPIONAGE_PROBE][DEPOT_MANAGEMENT] = 0;
  $p_need[SETTLER][DEPOT_MANAGEMENT] = 6;
  $p_need[SCARECROW][DEPOT_MANAGEMENT] = 0;
//  $p_need[BOMBER][DEPOT_MANAGEMENT] = 0;
  $p_need[SMALL_TRANSPORTER][DEPOT_MANAGEMENT] = 0;
  $p_need[MEDIUM_TRANSPORTER][DEPOT_MANAGEMENT] = 5;
  $p_need[BIG_TRANSPORTER][DEPOT_MANAGEMENT] = 20;

  $p_need[SPARROW][COMP_MANAGEMENT] = 0;
  $p_need[BLACKBIRD][COMP_MANAGEMENT] = 0;
  $p_need[RAVEN][COMP_MANAGEMENT] = 0;
  $p_need[EAGLE][COMP_MANAGEMENT] = 0;
  $p_need[FALCON][COMP_MANAGEMENT] = 0;
  $p_need[NIGHTINGALE][COMP_MANAGEMENT] = 0;
  $p_need[RAVAGER][COMP_MANAGEMENT] = 0;
  $p_need[DESTROYER][COMP_MANAGEMENT] = 0;
  $p_need[ESPIONAGE_PROBE][COMP_MANAGEMENT] = 0;
  $p_need[SETTLER][COMP_MANAGEMENT] = 3;
  $p_need[SCARECROW][COMP_MANAGEMENT] = 20;
//  $p_need[BOMBER][COMP_MANAGEMENT] = 0;
  $p_need[SMALL_TRANSPORTER][COMP_MANAGEMENT] = 0;
  $p_need[MEDIUM_TRANSPORTER][COMP_MANAGEMENT] = 0;
  $p_need[BIG_TRANSPORTER][COMP_MANAGEMENT] = 0;

  $p_description = null;
  $p_description[SPARROW] = "<b>Allgemeines:</b><br/> Der Sparrow gehört zur Klasse der leichten Kampfflugzeuge, die mit einem Oxidationsantrieb sowie Elektronensequenzwaffen ausgerüstet sind.  <br/><br/> <b>Empfehlung:</b><br/> Dieses schnelle Kampfflugzeug hat die niedrigsten Produktionskosten, den geringsten Treibstoffverbrauch und die kürzeste Bauzeit. Auf der anderen Seite ist der \"Spatz\" allerdings auch das schwächste Kampfflugzeug auf Erde II, besitzt aber zumindest einen kleinen Laderaum. Der Sparrow ist Geschwindigkeitsfanatikern und Liebhabern der Waffengattung \"ESW\" zu empfehlen. Vor allem im Krieg kann die geringe Bauzeit von Bedeutung sein. Wer mit dem Sparrow größere Angriffe fliegen möchte, sollte einigen Aufwand in die Weiterentwicklung aller verwendeten Technologien stecken.  <br/><br/> <b>Anekdote:</b><br/><i> Während meiner Recherchen über die Eigenschaften des Sparrows, sagte ein Techniker mit völlig ernster Miene zu mir: \"Wenn eine 
meiner Städte angegriffen wird und ich die Wahl 
habe, mich mit einem Sparrow oder dem Modellflugzeug meines Sohnes zu verteidigen, glauben Sie mir, ich würde ohne zu zögern das Modellflugzeug wählen!\"</i>";
  $p_description[BLACKBIRD] = "<b>Allgemeines:</b><br/> Der Typ Blackbird besitzt einen Oxidationsantrieb und ist mit Elektronensequenzwaffen ausgestattet. Er ist das Nachfolgemodell des Spatzen, in dem die Kampfkraft zu Lasten anderer Eigenschaften voll ausgereizt wurde.  <br/><br/> <b>Empfehlung:</b><br/> Die \"Amseln\" sind in erster Linie Kampfflugzeuge zur Unterstützung der Heimatverteidigung. für den Einsatz in Feldzügen machen sie schwache Motoren und fehlende Frachträume so gut wie ungeeignet.  <br/><br/> <b>Anekdote:</b><br/><i> Ein alter Abenteurer erzählte mir folgende Geschichte über seine erste Begegnung mit den Blackbirds: \"Beim Anflug auf eine Rohstofflagerstätte in der Nähe schaute ich gerade Alfred Hitchcocks \"Die Vögel\", während mein Zweiter die Maschine ans Ziel brachte, als sich der Himmel verdunkelte. Neugierig starrte ich durch die Frontscheibe, um zu sehen, was da vor sich ging. Ich erspähte einen großen Schwarm von 
schwarzen Vögeln, der sich plötzlich über den angepeilten 
Ländereien erhob und uns die Sicht nahm. Es war, als wäre der Film Wirklichkeit geworden! Da hat mein Zweiter gekniffen. Ich hätte trotzdem angegriffen, wäre ich nicht in Ohnmacht gefallen.\"</i>";
  $p_description[RAVEN] = "<b>Allgemeines:</b><br/> Der Raven ist das tragstärkste Kampfflugzeug der ESW/OXI-Gattung.  <br/><br/> <b>Empfehlung:</b><br/> Die \"Raben\" sind hervorragend für das Plündern geeignet und lösen die Sparrows in diesem Punkt relativ bald ab, sobald sich die höheren Forschungskosten rentieren. In Kriegszeiten ist die längere Bauzeit im Vergleich zur Basisversion allerdings von Nachteil. Zu empfehlen ist der Raven daher als Farmflugzeug nach dem Sparrow und später als Hauptbestandteil der ESW-Angriffsflotten.  <br/><br/> <br/><br/> <b>Anekdote:</b><br/><i> Ich sprach mit dem Entwickler des Ravens und befragte ihn bezüglich der Namensgebung: \"Eigentlich wollte ich das Flugzeug \"Diebische Elster\" nennen, aufgrund seiner guten Dienste für Farmer. Meinem Vorgesetzten ging das jedoch zu weit. Er sagte, man müsse an das Image der Firma denken und einen neutralen Namen wählen. Was für ein Spießer! Ich konnte es mir jedoch 
nicht verkneifen, den Namen \"Diebische Elster\" auf die 
Fallschirme, mit denen die Raven ausgerüstet werden, nähen zu lassen, hihi. Naja, ich wurde gefeuert und arbeite jetzt bei der Konkurrenz, bei PSW.\"</i>";
  $p_description[EAGLE] = "<b>Allgemeines:</b><br/> Mit dem Eagle gelang es den Wissenschaftlern von Erde II, einen Jäger mit <b>bis dato ungeahnter Kombination aus Kampfkraft, Tragfähigkeit und Geschwindigkeit zu konstruieren:</b> Es ist das erste Flugzeug der neuen Protonensequenzwaffengattung (PSW) und mit dem leistungsstarken Hoverantrieb ausgestattet.  <br/><br/> <b>Empfehlung:</b><br/> Dank der neuartigen Technologien verfügt der \"Adler\" über eine bessere Kombination aller Eigenschaften als alle Kampfflugzuge der ESW/OXI-Sparte. Trotz dieser Verbesserungen liegt der Treibstoffverbrauch nur geringfügig über dem des Ravens, wobei aber auch der Laderaum erheblich größer ist. Die einzigen Nachteile gegenüber den ESW-Flugzeugen sind die höhere Bauzeit und die gestiegenen Produktions- sowie die notwendigen Forschungskosten.  Aufgrund der oben genannten Eigenschaften ist der Eagle ein gutes Beuteflugzeug, mit dem PSWler kostengünstige, aber trotzdem 
schlagkräftige Angriffe fliegen können.  <br/><br/> 
<b>Anekdote:</b><br/><i> Mein Bekannter, ein ehemaliger ESW-Techniker, hat an der Entwicklung des Eagles mitgearbeitet: \"Mit meinem neuen Chef hab ich echt das große Los gezogen. Er nahm meinen Namensvorschlag \"Eagle\" begeistert an, als ich meinte, dass mich dieses Flugzeug an einen Adler erinnere, der sich mit messerscharfen Klauen erbarmungslos auf seine Beute niederstürzt.  Vielleicht sollte ich ins Marketing gehen...\"</i>";
  $p_description[FALCON] = "<b>Allgemeines:</b><br/> Der Falcon ist eine Aufrüstung des Eagles und verfügt ebenfalls über Protonensequenzwaffen und einen Hoverantrieb.  <br/><br/> <b>Empfehlung:</b><br/> Die Wissenschaftler konzentrierten sich bei der Entwicklung des \"Falken\" besonders auf die Verbesserung der Kampfkraft. So ist es kein Wunder, dass der Falcon weitaus ehrfurchtgebietender als der Eagle zuschlägt, jedoch ging dies vollkommen zu Lasten von Antrieb und Transportraum. Somit dient der Falke zumeist ausschließlich der Verteidigung und ist bei Tage währenden Terrorangriffen beliebt, wo es ausnahmsweise nicht auf Geschwindigkeit ankommt.  <br/><br/> <b>Anekdote:</b><br/><i> Ich sprach wiederum mit meinem Bekannten dem ehemaligen ESW-Techniker: \"Pah, ich hätte wirklich ins Marketing gehen sollen. Die Typen haben doch tatsächlich meine Idee geklaut und den Falcon nach einem Greifvogel benannt. Dabei ist der Falc gar kein Jagdflugzeug! Ich hätte diesen 
Schlachtenflieger viel eher \"Dreadnought\" 
genannt. Aber diese ideenklauenden Streber haben ja keine Ahnung von Kampfflugzeugen.\"</i";
  $p_description[NIGHTINGALE] = "<b>Allgemeines:</b><br/> Der Typ Nightingale ist das Königsflugzeug der PSW-Klasse und besitzt ebenfalls einen Hoverantrieb.  <br/><br/> <b>Empfehlung:</b><br/> Diese Neuentwicklung ist ein erstaunlich ausgewogenes Kampfflugzeug. Es bietet eine gute Feuerkraft, aber ist trotzdem nicht viel langsamer als der Eagle! Bei diesen Attributen ist es überaus erstaunlich, dass die \"Nachtigall\" zudem über einen für ein Kampfflugzeug riesigen Laderaum verfügt, was sich aber auch in erhöhtem Treibstoffverbrauch niederschlägt. Die Nachteile bestehen in höheren Produktionskosten und Bauzeiten.  Die Nachtigall ist daher uneingeschränkt dem PSW-Liebhaber in allen denkbaren Angriffssituationen zu empfehlen.  <br/><br/> <b>Anekdote:</b><br/><i> Der Besitzer einer mächtigen Nightingale-Flotte meinte begeistert zu mir: \"Nichts ist schöner als die \"lieblichen\" Töne ihrer feuernden Geschütze, die meine Gegner kopflos in die 
Flucht schlagen. Die Feuerrate ist wirklich enorm und die Ironie der 
Namensgebung ein herrlicher Witz, der meine Feinde zusätzlich verhöhnt. Meine Frau ist der Meinung, ich sei von ihnen besessen, nur weil ich ihr zum Hochzeitstag eine echte Nachtigall samt Käfig schenkte. Dabei sind diese Vögel verdammt selten und schweineteuer. Tja, das nächste Mal gibt es nur Blumen, sie ist selbst schuld.\"</i>";
  $p_description[RAVAGER] = "<b>Allgemeines:</b><br/> Der Ravager ist das Ergebnis langer und ruinös teurer Forschungsarbeit.  Die neu entwickelten Neutronensequenzwaffen und der Antigravitationsantrieb waren nun soweit fortgeschritten, dass sie auch für Kampfflugzeuge verwendet werden konnten. <br/><br/> <b>Empfehlung:</b><br/> Der \"Verwüster\" macht seinem Namen alle Ehre. Durch seine beachtliche Feuerkraft ist dieses Flugzeug das Rückgrat der meisten modernen Streitkräfte. Im großen Verbund durchdringt er fast jede Verteidigungsstellung und kann auch weiter entfernte Ziele noch in annehmbarer Zeit erreichen.  Sobald man sich eine Ravager-Flotte leisten kann, sollte der enorme Treibstoffverbrauch kaum noch stören. Die größten Nachteile sind die hohen Produktionskosten und die immense Bauzeit die benötigt wird, um dieses hoch komplexe Fluggerät zu bauen. Aus diesem Grund sollte man sich ganz genau überlegen, wann und gegen wen man sie einsetzt. 
<br/><br/> <b>Anekdote:</b><br/><i> Nach einer Legende stammt 
der Name des Ravagers, der unter der Bezeichnung N-946 entwickelt wurde, aus einer der ersten Schlachten mit dessen Beteiligung. Eine kleine Flotte verwandelte eine blühende Metropole in wenigen Minuten in einen Steinhaufen. Nachdem die Bilder den Generalstab erreichten, rief einer der Generäle unwillig auf:  \"Da ist ja nur Wüste! Legt mal den richtigen Film ein!\" Doch bei Wüste blieb es und das neue Flugzeug nannte man Verwüster.</i>";
  $p_description[DESTROYER] = "<b>Allgemeines:</b><br/> Der Destroyer ist die Krönung der Kampfflugzeuge. Ebenso wie beim Ravager basieren seine Waffen auf der Neutronensequenzwaffentechnologie, sein Antrieb auf Antigravitation.  <br/><br/> <b>Empfehlung:</b><br/> Der \"Zerstörer\" markiert den Endpunkt der Flugzeugentwicklung. Er ist eine größere Version des Ravagers. Dadurch kann er mit mehr Waffen bestückt werden, was seinen Kampfwert nochmal um einiges steigert. Auch dem Laderaum kommt diese Tatsache zu Gute , jedoch zu Lasten der Geschwindigkeit. Bedingt durch seine Größe steigen auch der Treibstoffverbrauch und die Produktionskosten noch einmal deutlich an, bleiben aber annähernd gleich, wenn man sie ins Verhältnis zum Kampfwert setzt. Die fast doppelt so lange Bauzeit für einen Destroyer trägt das übrige dazu bei, dass dieser Flugzeugtyp noch seltener und damit wertvoller für seinen Besitzer wird. <br/><br/> <b>Anekdote:</b><br/><i> 
Ich befragte einen Militärhistoriker über die Rolle des Destroyers in 
der Geschichte der Kriegsführung: \"Schon alleine die Anwesenheit dieser mächtigen Kampfmaschine lässt die Feinde vor Furcht erzittern. Vor einiger Zeit gab es einen Konflikt, da kapitulierte die eine Kriegspartei, noch bevor der erste Schuss fiel, weil sie das Gerücht vernahm, dass der Gegner über Destroyer verfüge.\"</i>";
  $p_description[ESPIONAGE_PROBE] = "<b>Allgemeines:</b><br/> Die Sonde gehört zu der Klasse der Unterstützungsflugzeuge. Ein unbemanntes Fluggerät voll gestopft mit modernster Spionagetechnik. Auf Grund des geringen Gewichtes und eines speziell für die Sonde entwickelten Antriebs kann sie enorme Geschwindigkeiten erreichen. <br/><br/> <b>Empfehlung:</b><br/> Sonden sind ideal geeignet, viele Informationen über feindliche Städte zu sammeln. Räuberische Städteherren nutzen sie oftmals zur vorhergehenden Kontrolle, ob sich denn ein überfall lohnt. Auch im Vorfeld eines Kolotakes, zur Abschätzung der möglichen Gegenwehr, werden sie gerne eingesetzt.  Zwar sind diese wieselflinken Flugmaschinen in Minutenschnelle beim anvisierten Ziel und sammeln Unmengen an Informationen, doch werden sie nach der übertragung der Informationen mit annähernd hundertprozentiger Wahrscheinlichkeit von der gegnerischen Flugabwehr zerstört. Aus diesem Grund sollte 
man immer genug Sonden auf Lager haben. <br/><br/> <b>Anekdote:</b><br/
><i> Ein guter Freund, der jahrelang als Radaroffizier den Luftraum überwachte, sagte einmal: \"Sonden sind wie Mücken - Extrem lästig, aber mehr als ein Jucken richten sie nicht an.\"</i>";
  $p_description[SETTLER] = "<b>Allgemeines:</b><br/> Der Settler gehört zur Klasse der
  Spezialflugzeuge und besitzt einen Oxidationsantrieb. Der Settler verfügt heute nicht mehr über Bordwaffen,
  denn er ist nicht dem Kampf, sondern der friedlichen Kolonisierung gewidmet worden.
  <br/><br/> <b>Empfehlung:</b><br/> Nach beharrlicher Forschung auf dem Gebiet der Lagerverwaltung
  war es nun möglich, ein Flugzeug zu bauen, das ausreichend Platz bot, um genug Material für die
  Neugründung einer Siedlung mitzuführen. Der \"Siedler\" ist aufgrund seiner Größe ein langsames
  Oxi-Flugzeug. Nach einer erfolgreichen Landung auf unbewohntem Gebiet wird in Windeseile die Grundlage einer neuen Stadt erschaffen, wobei auch das Flugzeug als Baumaterial aufgebraucht wird.  Dieses Sonderflugzeug ist zwar eines der teuersten auf Erde II, jedoch für Kommandanten die einzige Möglichkeit, neue Kolonien zu gründen. <br/><br/> <b>Anekdote:</b><br/><i> Ein Historiker erzählte mir folgende Geschichte: \"Vor einigen Jahren war es bei der Wirtschaftselite Mode, zum Zeitvertreib verschwenderische Schlachten mit gigantischen Settler-Flotten zu führen. Diese Dekadenz diente jedoch nicht nur dem Spaß, sondern war gleichzeitig auch eine Demonstration der Stärke und eine Zweckentfremdung, die auch heute noch ihresgleichen sucht.<br><br><b>Achtung: Pro Ausbaustufe steigert sich die maximale Entfernung um 1 Land. Erst ab Ausbaustufe 50 kann frei gegründet werden!</b>\"</i>";
  $p_description[SCARECROW] = "<b>Allgemeines:</b><br/> Die Scarecrow bildet die nächste Generation der Sonderflugzeuge und ist mit einem Oxidationsantrieb, aber nur einfachen Bordwaffen ausgestattet, die sich nicht verbessern lassen. Sie ermöglicht es kriegerischen Städteherren, fremde Kolonien zu erobern. <br/><br/> <b>Empfehlung:</b><br/> Die \"Vogelscheuche\" ist das am meisten gefürchtete Flugzeug auf Erde II.  Sie stellt den Destroyer jedoch nicht aufgrund ihrer Kampfkraft in den Schatten, es ist vielmehr die einzigartige Fähigkeit der Scarecrow, fremde Kolonien zu erobern. Die Scarecrow ist damit sozusagen das kriegerische Pendant zum Settler. Es ist jedem potenziellen Eroberer anzuraten, stets mindestens zwei Scarecrows in seiner Eroberungsflotte mitzuführen, falls eine beim Angriff zerstört wird. Weiterhin ist es wichtig zu wissen, dass die Scarecrow eine Kolonie nur erobern kann, wenn alle Verteidigungsanlagen zerstört wurden. <br/><br/> <b>Anekdote:</b><br/
><i> Bei seinem Auftritt in einer 
schäbigen Kneipe sagte ein Comedian: \"Was ist der Unterschied zwischen Scarecrows und Ex-Frauen? Na ist doch klar, es gibt gar keinen. Beide sind hässliche Vogelscheuchen und nehmen einem die Häuser weg!<br><br><b>Achtung: Pro Ausbaustufe steigert sich die maximale Entfernung um 1 Land. Erst ab Ausbaustufe 50 kann frei gegründet werden!</b>\"</i>";
//  $p_description[BOMBER] = "<b>Allgemeines:</b><br/>Der Hesse-Bomber ist ein handgefertigtes Mehrzweckflugzeug in limitierter Auflage, ausgerüstet mit Protonensequenzwaffen.<br/><br/> <b>Empfehlung:</b><br/>Die stabile Konstruktion dieses Fliegers trotzt selbst stärksten Stürmen, er kann sogar von verschlammten Landebahnen starten, sein grosszügiger Laderaum fasst einige Megatonnen Bier, und das Beste ist: seine Aussenhaut ist wasserdicht!
//Zur Grundausstattung gehört ein Wörterbuch Hessisch-Deutsch. Seine einzigartige Kombination von Eigenschaften macht es zum Camp-Flugzeug der Wahl. Wer am Tag der Abrechnung noch Exemplare davon besitzt, hat eine gute Chance auf einen Platz in den Geschichtsbüchern.<br/><br/> <b>Anekdote:</b><br/><i>Zwei Ureinwohner sehen mit ausdruckslosen Mienen in den Himmel.  \"Ei gucke, des zischt ab wie Abbelsaft.\" meint der eine. \"Abba wie is des aus de Schlambes enuff kumme?\" Schweigen. \"Ei, natäärlisch - volle Lotte rödeln!\"</i>";
//  $p_description[BOMBER] = "<b>Allgemeines:</b><br/> Der Bomber ist ein Sonderkampflugzeug, ausgerüstet mit Neutronensequenzwaffen. Mit seinen Bomben ist es ihm möglich, Schutzschilde für einige Stunden zu deaktivieren. <br/><br/> <b>Empfehlung:</b><br/> Die Spezialfracht dieses Flugzeuges, tonnenschwere Bomben, die mit ihrer Sprengkraft ganze Schutzschilde deaktivieren können, nimmt den Großteil des Laderaumes ein, so dass für konventionelle Waffensysteme wenig Platz übrig bleibt und der einfache Antrieb sich auch nicht weiter ausbauen lässt.
//Trotz der daraus resultierenden geringen Kampfkraft im Vergleich zum Ravager und Destroyer bezieht der Bomber sein Existenzrecht auf Erde II aus seiner einzigartigen Fähigkeit, Schilde auszuschalten so dass auch die hohe Produktionsdauer und die immensen Kosten in den Hintergrund treten, wenn seine Dienste zum Einsatz kommen. <br/><br/> <b>Anekdote:</b><br/><i> Ich fragte einen Offizier, ob ihm die Geschwindigkeit des Bombers für einen Einsatz nicht zu gering sei: \"Ach Jüngelchen. Und wenn der Bomber erst nächste Woche ankäme, bis jetzt zumindest ist noch nie ein Schutzschild davongelaufen.\"</i>";
  $p_description[SMALL_TRANSPORTER] = "<b>Allgemein</b><br>Der kleine Transporter ist, neben der Spionagesonde, das einzige Flugzeug, für das keine Technologie erforscht werden muss.<br><br><b>Empfehlung</b><br>Die \"kleine Transe\", wie sie liebevoll genannt wird, ist in den ersten Wochen und Monaten die wichtigste und praktisch einzige Möglichkeit Ressourcen effektiv zu transportieren. Vor allem der geringe Verbrauch von wertvollem Sauerstoff, aber auch der zu diesem Zeitpunkt einmalig große Laderaum machen diesen Flieger für alle Händler unentbehrlich. Aufgrund der sehr simplen und einfachen Technik, werden keine teuren Forschungsarbeiten benötigt um dieses Flugzeug zu bauen, allerdings lässt sich der Oxidationsantrieb des kleinen Transportflugzeug auch nicht weiter entwickeln und wer ihn einsetzen will, sollte genug Zeit und Geduld mit bringen.<br><br><b>Anekdote</b><br><i>Mein Schwager, ein bekannter Spediteur, wollte vor einiger Zeit Weintrauben aus einer entfernten 
Kolonie zu seiner Heimatstadt 
transportieren, um sie dort zu verkaufen. Ihm stand aber nur ein kleiner Transporter zur Verfügung. Ohne Alternative packte er die Trauben in Holzfässer und schickte sie los. Als der Transporter ankam, war aus den Trauben der wohl köstlichste Wein geworden, den Erde II je gesehen hatte und mein Schwager verdiente sich eine goldene Nase.</i>";
  $p_description[MEDIUM_TRANSPORTER] = "<b>Allgemein</b><br>Der mittlere Transporter ist mit einem teuren, aber recht schnellen Hoverantrieb ausgestattet.<br><br><b>Empfehlung</b><br>Die \"mittlere Transe\", wie sie im Fachjargon bezeichnet wird, ist der Kleinen in allen Belangen überlegen. Dank des Hoverantriebs, kann sie auch Städte auf anderen Kontinenten in einer akzeptablen Zeit erreichen und dabei auch noch ein Vielfaches der Fracht bequem transportieren. Allerdings sind dementsprechend auch die technischen Anforderungen um Einiges höher. Es wird nicht nur ein neuer Antrieb benötigt, sondern es muss auch die Lagerverwaltung weiter entwickelt werden.<br><br><b>Anekdote</b><br><i>Wenn ich eine mittlere Transe sehe, muss ich immer an meine Schulzeit zurückdenken. Jeder sollte ein Referat über einen Flugzeugtypen von Erde II halten. Ich bekam die mittlere Transe vom Lehrer zugeteilt. Das Referat war schnell gehalten. \"Im Vergleich zu den anderen Transportern ist die mittlere 
Transe, mittel schnell, mittel 
teuer, kann mittel viel transportieren und verbraucht mittel viel o2.\" Leider bekam ich dafür jedoch keine mittelgute Note!</i>";
  $p_description[BIG_TRANSPORTER] = "<b>Allgemein</b><br>Der große Transporter, ein wahres Ungetüm
  der Lüfte, besitzt einen äußerst leistungsstarken
  Antigravitationsantrieb<br><br><b>Empfehlung</b><br>Die \"große Transe\" ist ein Meisterwerk der
  Technik und nach dem Settler das zweitgrößte Flugzeug auf Erde II. Die Größe ist durch ihren
  beeindruckend großen Laderaum bedingt, der einem unerfahrenen Bürger schon mal die Sprache
  verschlagen kann. Ihrem Antigravitationsantrieb ist es zu verdanken, dass die große Transe trotz
  ihrer Größe schneller als ihre Vorgängerin ist. Dementsprechend sind auch die Forschungskosten für
  diesen Transporter immens. Die Kosten rentieren sich jedoch
  allemal.<br><br><b>Anekdote</b><br><i>Als ich meine Oma im Altenheim besuchte, um mein Erbe ... äh, mich nach ihrem werten Befinden zu erkundigen, packte mich plötzlich ein dünner Arm von der Seite am Kragen und zog mich in eine Abstellkammer. Vor mir stand ein alter Mann mit wirrem Haarschopf, der meinte: \"Hol mich hier raus, dann sage ich dir, wie man große Transen mit Neutronensequenzwaffen bestückt!\" Ich war noch völlig verdattert, da sagte er plötzlich: \"Huch, wer sind sie denn? Warum haben sie mich in diese Kammer verschleppt? Es gibt doch Kuchen in der Cafeteria!\" Vorsichtshalber ging ich am nächsten Tag ins Patentamt, doch der Beamte kam aus dem Lachen gar nicht mehr heraus.</i>";

/*****************/

  $d_name = null;
  $d_name[E_WOOFER] = "Elektronenwoofer";
  $d_name[P_WOOFER] = "Protonenwoofer";
  $d_name[N_WOOFER] = "Neutronenwoofer";
  $d_name[E_SEQUENZER] = "Elektronensequenzer";
  $d_name[P_SEQUENZER] = "Protonensequenzer";
  $d_name[N_SEQUENZER] = "Neutronensequenzer";

  $d_id = null;
  $d_id[E_WOOFER] = "13";
  $d_id[P_WOOFER] = "14";
  $d_id[N_WOOFER] = "15";
  $d_id[E_SEQUENZER] = "16";
  $d_id[P_SEQUENZER] = "17";
  $d_id[N_SEQUENZER] = "18";
  
  
  $d_db_name = null;
  $d_db_name[E_WOOFER] = "electronwoofer";
  $d_db_name[P_WOOFER] = "protonwoofer";
  $d_db_name[N_WOOFER] = "neutronwoofer";
  $d_db_name[E_SEQUENZER] = "electronsequenzer";
  $d_db_name[P_SEQUENZER] = "protonsequenzer";
  $d_db_name[N_SEQUENZER] = "neutronsequenzer";

  $d_power = null;
  $d_power[E_WOOFER] = 150;
  $d_power[P_WOOFER] = 250;
  $d_power[N_WOOFER] = 400;
  $d_power[E_SEQUENZER] = 500;
  $d_power[P_SEQUENZER] = 750;
  $d_power[N_SEQUENZER] = 1000;

  $d_iridium = null;
  $d_iridium[E_WOOFER] = 300;
  $d_iridium[P_WOOFER] = 600;
  $d_iridium[N_WOOFER] = 1000;
  $d_iridium[E_SEQUENZER] = 2000;
  $d_iridium[P_SEQUENZER] = 3500;
  $d_iridium[N_SEQUENZER] = 5000;

  $d_holzium = null;
  $d_holzium[E_WOOFER] = 100;
  $d_holzium[P_WOOFER] = 400;
  $d_holzium[N_WOOFER] = 600;
  $d_holzium[E_SEQUENZER] = 800;
  $d_holzium[P_SEQUENZER] = 2000;
  $d_holzium[N_SEQUENZER] = 3500;

  $d_tech = null;
  $d_tech[E_WOOFER][T_POWER] = EW_WEAPONS;
  $d_tech[P_WOOFER][T_POWER] = PW_WEAPONS;
  $d_tech[N_WOOFER][T_POWER] = NW_WEAPONS;
  $d_tech[E_SEQUENZER][T_POWER] = E_WEAPONS;
  $d_tech[P_SEQUENZER][T_POWER] = P_WEAPONS;
  $d_tech[N_SEQUENZER][T_POWER] = N_WEAPONS;

  $d_tech[E_WOOFER][T_BUILD1] = NOBUILD;
  $d_tech[P_WOOFER][T_BUILD1] = DEF_CENTER;
  $d_tech[N_WOOFER][T_BUILD1] = DEF_CENTER;
  $d_tech[E_SEQUENZER][T_BUILD1] = DEF_CENTER;
  $d_tech[P_SEQUENZER][T_BUILD1] = DEF_CENTER;
  $d_tech[N_SEQUENZER][T_BUILD1] = DEF_CENTER;

  $d_duration = null;
  $d_duration[E_WOOFER] = 1500;
  $d_duration[P_WOOFER] = 4000;
  $d_duration[N_WOOFER] = 7500;
  $d_duration[E_SEQUENZER] = 20000;
  $d_duration[P_SEQUENZER] = 30000;
  $d_duration[N_SEQUENZER] = 50000;


  $d_need_techs = null;
  $d_need_techs[E_WOOFER][E_WEAPONS] = 0;
  $d_need_techs[P_WOOFER][E_WEAPONS] = 0;
  $d_need_techs[N_WOOFER][E_WEAPONS] = 0;
  $d_need_techs[E_SEQUENZER][E_WEAPONS] = 1;
  $d_need_techs[P_SEQUENZER][E_WEAPONS] = 0;
  $d_need_techs[N_SEQUENZER][E_WEAPONS] = 0;

  $d_need_techs[E_WOOFER][P_WEAPONS] = 0;
  $d_need_techs[P_WOOFER][P_WEAPONS] = 0;
  $d_need_techs[N_WOOFER][P_WEAPONS] = 0;
  $d_need_techs[E_SEQUENZER][P_WEAPONS] = 0;
  $d_need_techs[P_SEQUENZER][P_WEAPONS] = 2;
  $d_need_techs[N_SEQUENZER][P_WEAPONS] = 0;

  $d_need_techs[E_WOOFER][N_WEAPONS] = 0;
  $d_need_techs[P_WOOFER][N_WEAPONS] = 0;
  $d_need_techs[N_WOOFER][N_WEAPONS] = 0;
  $d_need_techs[E_SEQUENZER][N_WEAPONS] = 0;
  $d_need_techs[P_SEQUENZER][N_WEAPONS] = 0;
  $d_need_techs[N_SEQUENZER][N_WEAPONS] = 2;

  $d_need_builds = null;
  $d_need_builds[E_WOOFER][DEF_CENTER] = 0;
  $d_need_builds[P_WOOFER][DEF_CENTER] = 2;
  $d_need_builds[N_WOOFER][DEF_CENTER] = 3;
  $d_need_builds[E_SEQUENZER][DEF_CENTER] = 5;
  $d_need_builds[P_SEQUENZER][DEF_CENTER] = 7;
  $d_need_builds[N_SEQUENZER][DEF_CENTER] = 10;

  $d_description = null;
  $d_description[E_WOOFER] = "Basierend auf den technologischen Grundsätzen der ehemaligen Erde, sind diese schwachen Defensivanlagen nur mit Treibladungen versehen, wodurch nur ein geringer Schaden verursacht werden kann.";
  $d_description[P_WOOFER] = "Ein auf dem Beschuss mittels niederenergetischer Protonen basierender Turm, der jedoch nur geringe Schäden anrichtet. Trotz seiner eher mittelmäßigen Werte kann er, wenn in großen Mengen errichtet, schlachtentscheidend sein, da sein Preis-Leistungs-Verhältnis sehr günstig ist.";
  $d_description[N_WOOFER] = "Letzter Turmneubau basierend auf den Konstruktionsplänen von Alt-Erdbewohnern. Diese Türme basieren noch auf niederfrequenten Neutronen, welche unter Dauerbeschuss eine atomare Kettenreaktion im gegnerischen Objekt hervorrufen und somit fast doppelt so leistungsstark wie Protonenwoofer sind.";
  $d_description[E_SEQUENZER] = "Dieser erste, auf der neuen Elektronensequenzertechnologie basierende, Verteidigungsturm kann durch seine neue Technologie und seine weit ausgereifte Zielführung schon im Anfangsstadium die Werte eines voll entwickelten Kampflugzeuges der selben Technologieart erreichen. Dadurch wird er besonders am Anfang unersetzlich für eine jede städtische Verteidigungsanlage.";
  $d_description[P_SEQUENZER] = "Dieser Turm ist das Rückgrat einer jeden Verteidigung. Denn dieser Sequenzer, der unter Mithilfe eines völlig neuen Technologiezentrums entwickelt wurde, besitzt einen minimalen Energieverbrauch, da er teilweise die Energie selbst dem Angreifer entzieht und sie in ihm zur Detonation bringt.";
  $d_description[N_SEQUENZER] = "Stärkster jemals errichteter Turm, der neben einer großen Biopositronie auch über ein unerschöpfliches Energiereservoir verfügt, da er seine Energie aus der heimatlichen Sonne bezieht, die gleichzeitig einen Großteil der Antineutrinos erzeugt.";

  $register_bonus = null;
  $register_bonus[] = "b_".$b_db_name[WORK_BOARD];
  $register_bonus[] = "b_".$b_db_name[TECH_CENTER];
  $register_bonus[] = "b_".$b_db_name[DEF_CENTER];
  $register_bonus[] = "b_".$b_db_name[COMM_CENTER];
  $register_bonus[] = "b_".$b_db_name[HANGAR];
  $register_bonus[] = "t_".$t_db_name[DEPOT_MANAGEMENT];
  $register_bonus[] = "t_".$t_db_name[COMPRESSION];
  $register_bonus[] = "t_".$t_db_name[MINING];
?>
