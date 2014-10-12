-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 06. Okt 2014 um 22:02
-- Server Version: 5.5.39
-- PHP-Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `ets`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
`id` int(11) NOT NULL,
  `city` varchar(11) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT '',
  `f_id` int(11) unsigned NOT NULL DEFAULT '0',
  `attack_deny_id` int(10) unsigned NOT NULL DEFAULT '0',
  `f_action` varchar(255) NOT NULL DEFAULT '',
  `f_plunder` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_iridium` varchar(20) NOT NULL DEFAULT '0',
  `f_holzium` varchar(20) NOT NULL DEFAULT '0',
  `f_water` varchar(20) NOT NULL DEFAULT '0',
  `f_oxygen` varchar(20) NOT NULL DEFAULT '0',
  `f_spy` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize_jobs` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize_fleets` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize_hangar` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize_nobonus` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `f_give` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_start` double NOT NULL DEFAULT '0',
  `f_arrival` double NOT NULL DEFAULT '0',
  `f_target` varchar(11) NOT NULL DEFAULT '',
  `f_target_user` varchar(20) NOT NULL DEFAULT '',
  `f_name` varchar(255) DEFAULT NULL,
  `f_name_show` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `f_sparrow` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_blackbird` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_raven` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_eagle` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_falcon` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_nightingale` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_ravager` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_destroyer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_espionage_probe` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_settler` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_scarecrow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_bomber` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_small_transporter` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_medium_transporter` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_big_transporter` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_flugzeuge_anzahl` int(11) unsigned NOT NULL DEFAULT '0',
  `msg` varchar(255) NOT NULL DEFAULT '',
  `msg_text` text NOT NULL,
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `f_volume` varchar(20) NOT NULL DEFAULT '0',
  `code` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `activity_stats`
--

CREATE TABLE IF NOT EXISTS `activity_stats` (
  `time` int(10) unsigned NOT NULL,
  `on_now` int(10) unsigned NOT NULL,
  `on_lasthour` int(10) unsigned NOT NULL,
  `on_lastday` int(10) unsigned NOT NULL,
  `total_accounts` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admin_agb_delict`
--

CREATE TABLE IF NOT EXISTS `admin_agb_delict` (
`id` int(10) unsigned NOT NULL,
  `sender` varchar(20) NOT NULL DEFAULT '',
  `recipient` varchar(20) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `topic` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `done` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admin_faq`
--

CREATE TABLE IF NOT EXISTS `admin_faq` (
`id` smallint(5) unsigned NOT NULL,
  `cat` smallint(5) unsigned NOT NULL DEFAULT '0',
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` text NOT NULL,
  `sorting` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admin_faq_cat`
--

CREATE TABLE IF NOT EXISTS `admin_faq_cat` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admin_login_msgs`
--

CREATE TABLE IF NOT EXISTS `admin_login_msgs` (
`id` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `color` enum('#FF0000','#00FF00','#FFFF00') NOT NULL DEFAULT '#FF0000',
  `text` text NOT NULL,
  `toshow` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Nachricht anzeigen'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `adressbook`
--

CREATE TABLE IF NOT EXISTS `adressbook` (
`id` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `contact` varchar(20) NOT NULL DEFAULT '',
  `gid` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `adressbook_groups`
--

CREATE TABLE IF NOT EXISTS `adressbook_groups` (
`id` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alliances`
--

CREATE TABLE IF NOT EXISTS `alliances` (
`ID` int(10) unsigned NOT NULL,
  `tag` varchar(25) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `wing` varchar(255) NOT NULL,
  `military_alliances` varchar(255) NOT NULL,
  `military_alliances2` varchar(255) NOT NULL,
  `military_alliances3` varchar(255) NOT NULL,
  `trade_alliances` text NOT NULL,
  `naps` text NOT NULL,
  `enemies` text NOT NULL,
  `beitritt` int(11) unsigned NOT NULL,
  `text` text NOT NULL,
  `admin_msgs` enum('N','Y') NOT NULL DEFAULT 'N',
  `admin_mails` enum('N','Y') NOT NULL DEFAULT 'N',
  `points` int(11) unsigned NOT NULL DEFAULT '0',
  `members` int(11) unsigned NOT NULL DEFAULT '0',
  `ads_credit` int(11) NOT NULL DEFAULT '0',
  `fame_own` int(11) unsigned NOT NULL DEFAULT '0',
  `fame` int(11) unsigned NOT NULL DEFAULT '0',
  `power` int(11) unsigned NOT NULL DEFAULT '0',
  `town_coordinates` varchar(8) NOT NULL,
  `town_depot` int(10) unsigned NOT NULL,
  `town_percent` tinyint(3) unsigned NOT NULL,
  `town_percent_change` int(10) unsigned NOT NULL,
  `town_last_memb_calc` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alliances_building`
--

CREATE TABLE IF NOT EXISTS `alliances_building` (
  `tag` int(11) NOT NULL,
  `build_id` int(11) NOT NULL,
  `stufe` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alliance_ads`
--

CREATE TABLE IF NOT EXISTS `alliance_ads` (
`id` int(11) NOT NULL,
  `tag` varchar(25) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `thumb` varchar(64) NOT NULL,
  `width` mediumint(9) NOT NULL DEFAULT '0',
  `height` mediumint(9) NOT NULL DEFAULT '0',
  `credit` int(11) NOT NULL DEFAULT '0',
  `approved` enum('Y','N') NOT NULL DEFAULT 'N',
  `denied` varchar(64) DEFAULT NULL,
  `text` tinytext,
  `link_to` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alliance_applications`
--

CREATE TABLE IF NOT EXISTS `alliance_applications` (
  `user` varchar(20) NOT NULL DEFAULT '',
  `tag` varchar(25) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `artefakte`
--

CREATE TABLE IF NOT EXISTS `artefakte` (
`ID` tinyint(3) unsigned NOT NULL,
  `start` int(10) unsigned NOT NULL,
  `duration` int(10) unsigned NOT NULL,
  `last_take` int(10) unsigned NOT NULL,
  `kw1` int(10) unsigned NOT NULL,
  `kw2` int(10) unsigned NOT NULL,
  `hoped_fleets` int(10) unsigned NOT NULL,
  `real_fleets` int(10) unsigned NOT NULL,
  `koords` varchar(11) NOT NULL,
  `started` varchar(8) NOT NULL,
  `iridium` smallint(5) unsigned NOT NULL,
  `holzium` smallint(5) unsigned NOT NULL,
  `wasser` smallint(5) unsigned NOT NULL,
  `sauerstoff` smallint(5) unsigned NOT NULL,
  `lager` smallint(5) unsigned NOT NULL,
  `tank` smallint(5) unsigned NOT NULL,
  `hangar` smallint(5) unsigned NOT NULL,
  `flughafen` smallint(5) unsigned NOT NULL,
  `bz` smallint(5) unsigned NOT NULL,
  `tz` smallint(5) unsigned NOT NULL,
  `kz` smallint(5) unsigned NOT NULL,
  `hz` smallint(5) unsigned NOT NULL,
  `vz` smallint(5) unsigned NOT NULL,
  `koth` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `artefakte_`
--

CREATE TABLE IF NOT EXISTS `artefakte_` (
  `user` varchar(30) NOT NULL,
  `time` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `asteroids`
--

CREATE TABLE IF NOT EXISTS `asteroids` (
`id` int(10) unsigned NOT NULL,
  `start` int(10) unsigned NOT NULL,
  `duration` int(10) unsigned NOT NULL,
  `points` smallint(5) unsigned NOT NULL,
  `kw1` int(10) unsigned NOT NULL,
  `hoped_fleets` int(10) unsigned NOT NULL,
  `real_fleets` int(10) unsigned NOT NULL,
  `kw2` int(10) unsigned NOT NULL,
  `koords` varchar(11) NOT NULL,
  `started` varchar(8) NOT NULL DEFAULT 'not'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `attack_denies`
--

CREATE TABLE IF NOT EXISTS `attack_denies` (
`id` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(11) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chronicle`
--

CREATE TABLE IF NOT EXISTS `chronicle` (
`id` int(10) unsigned NOT NULL,
  `war_id` int(10) unsigned DEFAULT NULL,
  `occasion` enum('declare','decline','accept','withdraw','start','end','text','asteroid') NOT NULL DEFAULT 'declare',
  `victory` enum('timeout','colonies','loss','join','vacation','leave','breach','surrender','disband','none') NOT NULL DEFAULT 'none' COMMENT 'Ursache des Kriegsendes',
  `causer` varchar(25) DEFAULT NULL,
  `arbitrary_text` varchar(128) DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `approved` enum('Y','N','X') NOT NULL DEFAULT 'X'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `city`
--

CREATE TABLE IF NOT EXISTS `city` (
`ID` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '0',
  `city` varchar(11) NOT NULL DEFAULT '',
  `home` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `x_pos` smallint(6) unsigned NOT NULL DEFAULT '0',
  `y_pos` smallint(6) unsigned NOT NULL DEFAULT '0',
  `z_pos` smallint(6) unsigned NOT NULL DEFAULT '0',
  `city_name` varchar(20) NOT NULL DEFAULT 'Neue Stadt',
  `text` text NOT NULL,
  `pic` varchar(255) NOT NULL DEFAULT '',
  `alliance` varchar(255) NOT NULL DEFAULT '',
  `points` smallint(5) unsigned NOT NULL DEFAULT '5',
  `foundation` int(11) unsigned NOT NULL DEFAULT '0',
  `r_time` int(11) unsigned NOT NULL DEFAULT '0',
  `r_time_oxygen` int(10) unsigned NOT NULL DEFAULT '0',
  `r_iridium` double unsigned NOT NULL DEFAULT '10000',
  `r_iridium_add` double unsigned NOT NULL DEFAULT '0.555555555555556',
  `r_holzium` double unsigned NOT NULL DEFAULT '10000',
  `r_holzium_add` double unsigned NOT NULL DEFAULT '0.555555555555556',
  `r_water` double unsigned NOT NULL DEFAULT '10000',
  `r_water_add` double unsigned NOT NULL DEFAULT '0.00277777777777778',
  `r_oxygen` double unsigned NOT NULL DEFAULT '10000',
  `r_oxygen_add` double unsigned NOT NULL DEFAULT '0.0555555555555556',
  `t_mining` smallint(5) unsigned NOT NULL DEFAULT '0',
  `t_water_compression` smallint(5) unsigned NOT NULL DEFAULT '0',
  `t_depot_management` smallint(5) unsigned NOT NULL DEFAULT '0',
  `b_end_time` int(11) unsigned NOT NULL DEFAULT '0',
  `b_end_time_next` int(10) unsigned NOT NULL DEFAULT '0',
  `b_current_build` varchar(255) NOT NULL DEFAULT '0',
  `b_next_build` varchar(255) NOT NULL DEFAULT '',
  `b_iridium_mine` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_holzium_plantage` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_water_derrick` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_oxygen_reactor` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_depot` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_oxygen_depot` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_trade_center` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_hangar` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_airport` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_defense_center` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_shield` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_technologie_center` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_communication_center` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_work_board` smallint(6) unsigned NOT NULL DEFAULT '5',
  `d_electronwoofer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_protonwoofer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_neutronwoofer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_electronsequenzer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_protonsequenzer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_neutronsequenzer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_sparrow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_sparrow_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_blackbird` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_blackbird_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_raven` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_raven_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_eagle` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_eagle_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_falcon` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_falcon_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_nightingale` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_nightingale_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_ravager` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_ravager_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_destroyer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_destroyer_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_espionage_probe` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_espionage_probe_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_settler` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_settler_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_scarecrow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_scarecrow_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_bomber` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_bomber_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_small_transporter` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_small_transporter_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_medium_transporter` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_medium_transporter_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_big_transporter` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_big_transporter_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_gesamt_flugzeuge` int(11) unsigned NOT NULL DEFAULT '0',
  `c_shield_timer` int(10) unsigned NOT NULL DEFAULT '0',
  `c_active_shields` int(10) unsigned NOT NULL,
  `blubb` int(10) NOT NULL DEFAULT '0',
  `msg` varchar(255) NOT NULL DEFAULT '',
  `msg_next` varchar(255) NOT NULL DEFAULT '',
  `pos` tinyint(4) NOT NULL,
  `special` varchar(6) NOT NULL DEFAULT 'normal'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `city_history`
--

CREATE TABLE IF NOT EXISTS `city_history` (
  `city` varchar(20) NOT NULL,
  `owner` varchar(20) NOT NULL,
  `time` double NOT NULL,
  `user` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `city_tmp`
--

CREATE TABLE IF NOT EXISTS `city_tmp` (
  `user` varchar(20) NOT NULL DEFAULT '0',
  `city` varchar(11) NOT NULL DEFAULT '',
  `home` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `x_pos` smallint(6) unsigned NOT NULL DEFAULT '0',
  `y_pos` smallint(6) unsigned NOT NULL DEFAULT '0',
  `z_pos` smallint(6) unsigned NOT NULL DEFAULT '0',
  `city_name` varchar(20) NOT NULL DEFAULT 'Neue Stadt',
  `text` text NOT NULL,
  `pic` varchar(255) NOT NULL DEFAULT '',
  `alliance` varchar(255) NOT NULL DEFAULT '',
  `points` smallint(5) unsigned NOT NULL DEFAULT '5',
  `foundation` int(11) unsigned NOT NULL DEFAULT '0',
  `r_time` int(11) unsigned NOT NULL DEFAULT '0',
  `r_time_oxygen` int(10) unsigned NOT NULL DEFAULT '0',
  `r_iridium` double unsigned NOT NULL DEFAULT '10000',
  `r_iridium_add` double unsigned NOT NULL DEFAULT '0.555555555555556',
  `r_holzium` double unsigned NOT NULL DEFAULT '10000',
  `r_holzium_add` double unsigned NOT NULL DEFAULT '0.555555555555556',
  `r_water` double unsigned NOT NULL DEFAULT '10000',
  `r_water_add` double unsigned NOT NULL DEFAULT '0.00277777777777778',
  `r_oxygen` double unsigned NOT NULL DEFAULT '10000',
  `r_oxygen_add` double unsigned NOT NULL DEFAULT '0.0555555555555556',
  `t_mining` smallint(5) unsigned NOT NULL DEFAULT '0',
  `t_water_compression` smallint(5) unsigned NOT NULL DEFAULT '0',
  `t_depot_management` smallint(5) unsigned NOT NULL DEFAULT '0',
  `b_end_time` int(11) unsigned NOT NULL DEFAULT '0',
  `b_end_time_next` int(10) unsigned NOT NULL DEFAULT '0',
  `b_current_build` varchar(255) NOT NULL DEFAULT '0',
  `b_next_build` varchar(255) NOT NULL DEFAULT '',
  `b_iridium_mine` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_holzium_plantage` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_water_derrick` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_oxygen_reactor` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_depot` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_oxygen_depot` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_trade_center` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_hangar` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_airport` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_defense_center` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_shield` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_technologie_center` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_communication_center` smallint(6) unsigned NOT NULL DEFAULT '0',
  `b_work_board` smallint(6) unsigned NOT NULL DEFAULT '5',
  `d_electronwoofer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_protonwoofer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_neutronwoofer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_electronsequenzer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_protonsequenzer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `d_neutronsequenzer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_sparrow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_sparrow_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_blackbird` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_blackbird_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_raven` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_raven_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_eagle` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_eagle_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_falcon` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_falcon_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_nightingale` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_nightingale_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_ravager` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_ravager_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_destroyer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_destroyer_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_espionage_probe` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_espionage_probe_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_settler` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_settler_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_scarecrow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_scarecrow_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_bomber` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_bomber_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_small_transporter` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_small_transporter_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_medium_transporter` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_medium_transporter_gesamt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `p_big_transporter` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_big_transporter_gesamt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `p_gesamt_flugzeuge` int(11) unsigned NOT NULL DEFAULT '0',
  `c_shield_timer` int(10) unsigned NOT NULL DEFAULT '0',
  `c_active_shields` int(10) unsigned NOT NULL,
  `blubb` int(10) NOT NULL DEFAULT '0',
  `msg` varchar(255) NOT NULL DEFAULT '',
  `msg_next` varchar(255) NOT NULL DEFAULT '',
  `pos` tinyint(4) NOT NULL,
  `special` varchar(6) NOT NULL DEFAULT 'normal'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `delete_reason`
--

CREATE TABLE IF NOT EXISTS `delete_reason` (
`ID` smallint(6) NOT NULL,
  `user` varchar(255) NOT NULL,
  `alliance` varchar(255) NOT NULL,
  `reason1` enum('ETS benÃƒÂ¶tigt zuviel Zeit','ETS ist in letzter Zeit zu langweilig','ETS ist allgemein zu langweilig','Ich fÃƒÂ¼hle mich als AnfÃƒÂ¤nger nicht gut aufgenommen und unwohl','ETS ist nicht das Onlinespiel, das ich suche','Sonstige','Enthaltung') NOT NULL,
  `reason2` enum('Weniger als 4 Wochen','Dies war meine erste Runde','Ich habe letzte Runde begonnen','Seit mehr als 2 Runden','Seit Ewigkeiten','Enthaltung') NOT NULL,
  `reason3` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `donations`
--

CREATE TABLE IF NOT EXISTS `donations` (
`id` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL,
  `type` enum('a','u') NOT NULL DEFAULT 'u' COMMENT 'User- oder Allianzspende?',
  `ident` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(5,2) NOT NULL,
  `to_show` int(10) unsigned NOT NULL DEFAULT '0',
  `current` int(10) NOT NULL DEFAULT '0' COMMENT 'Zeitscheibe',
  `rip` enum('TRUE','FALSE') NOT NULL DEFAULT 'FALSE' COMMENT 'Account geloescht?'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `extern_voting`
--

CREATE TABLE IF NOT EXISTS `extern_voting` (
`ID` int(10) unsigned NOT NULL,
  `mmofacts_place` tinyint(3) unsigned NOT NULL,
  `gamesphere_place` tinyint(3) unsigned NOT NULL,
  `gametoplist_place` tinyint(3) unsigned NOT NULL,
  `mmofacts_votes` smallint(5) unsigned NOT NULL,
  `gamesphere_votes` smallint(5) unsigned NOT NULL,
  `gametoplist_votes` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `flightstats`
--

CREATE TABLE IF NOT EXISTS `flightstats` (
  `user` varchar(255) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `ad` varchar(30) NOT NULL,
  `1` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Angriff zerstÃƒÂ¶rt',
  `2` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Angriff verloren',
  `3` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Verteidigung zerstÃƒÂ¶rt',
  `4` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Verteidigung verloren',
  `5` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Handel Eingang',
  `6` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Handel Ausgang'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `flugtimer`
--

CREATE TABLE IF NOT EXISTS `flugtimer` (
  `ID` int(10) unsigned NOT NULL,
  `user` varchar(30) CHARACTER SET utf8 NOT NULL,
  `town` varchar(9) CHARACTER SET utf8 NOT NULL,
  `art` tinyint(1) unsigned NOT NULL,
  `ankunftszeit` varchar(8) CHARACTER SET utf8 NOT NULL,
  `flugzeit` varchar(8) CHARACTER SET utf8 NOT NULL,
  `rueckzeit` varchar(8) CHARACTER SET utf8 NOT NULL,
  `notizen` varchar(100) CHARACTER SET utf8 NOT NULL,
  `warning` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `global`
--

CREATE TABLE IF NOT EXISTS `global` (
`id` tinyint(3) unsigned NOT NULL,
  `iridium` bigint(20) unsigned NOT NULL DEFAULT '0',
  `holzium` bigint(20) unsigned NOT NULL DEFAULT '0',
  `water` bigint(20) unsigned NOT NULL DEFAULT '0',
  `oxygen` bigint(20) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `global`
--

INSERT INTO `global` (`id`, `iridium`, `holzium`, `water`, `oxygen`) VALUES
(1, 20000000000, 10000000000, 15000000000, 2500000000);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `global_logs`
--

CREATE TABLE IF NOT EXISTS `global_logs` (
`ID` int(10) unsigned NOT NULL,
  `seite` varchar(255) NOT NULL,
  `inhalt` varchar(10000) NOT NULL,
  `datum` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `holiday`
--

CREATE TABLE IF NOT EXISTS `holiday` (
  `user` varchar(20) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `art` char(1) NOT NULL COMMENT '1=normal;2=lÃƒÂ¤ngereAbwesenheit'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `html_meta`
--

CREATE TABLE IF NOT EXISTS `html_meta` (
  `page` varchar(60) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `jobs_build`
--

CREATE TABLE IF NOT EXISTS `jobs_build` (
  `city` varchar(11) NOT NULL,
  `current_build` varchar(255) NOT NULL,
  `end_time` int(11) NOT NULL,
  `level` smallint(6) NOT NULL,
  `msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `jobs_defense`
--

CREATE TABLE IF NOT EXISTS `jobs_defense` (
`id` int(11) NOT NULL,
  `city` varchar(11) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT '',
  `current_build` varchar(255) NOT NULL DEFAULT '',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0',
  `msg` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `jobs_planes`
--

CREATE TABLE IF NOT EXISTS `jobs_planes` (
`id` int(11) NOT NULL,
  `city` varchar(11) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT '',
  `current_build` varchar(255) NOT NULL DEFAULT '',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0',
  `msg` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `jobs_tech`
--

CREATE TABLE IF NOT EXISTS `jobs_tech` (
  `user` varchar(255) NOT NULL,
  `start_city` varchar(11) NOT NULL,
  `current_build` varchar(255) NOT NULL,
  `end_time` int(11) NOT NULL,
  `level` smallint(6) NOT NULL,
  `msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `logs_login`
--

CREATE TABLE IF NOT EXISTS `logs_login` (
`id` int(11) NOT NULL,
  `identity` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  `response_code` varchar(10) NOT NULL,
  `response_debug_data` varchar(255) NOT NULL,
  `captcha_success` enum('yes','no') NOT NULL,
  `captcha_type` enum('handy','normal') NOT NULL DEFAULT 'normal',
  `captcha_need_value` varchar(255) NOT NULL,
  `captcha_post_value` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `logs_support`
--

CREATE TABLE IF NOT EXISTS `logs_support` (
`id` int(11) NOT NULL,
  `supporter` varchar(20) DEFAULT NULL,
  `action` varchar(20) DEFAULT NULL,
  `action_value` text,
  `target_user` varchar(50) DEFAULT NULL,
  `target_city` varchar(50) DEFAULT NULL,
  `r_water_compression` int(11) DEFAULT NULL,
  `r_mining` int(11) DEFAULT NULL,
  `r_oxidationsdrive` int(11) DEFAULT NULL,
  `r_hoverdrive` int(11) DEFAULT NULL,
  `r_antigravitydrive` int(11) DEFAULT NULL,
  `r_electronsequenzweapons` int(11) DEFAULT NULL,
  `r_protonsequenzweapons` int(11) DEFAULT NULL,
  `r_neutronsequenzweapons` int(11) DEFAULT NULL,
  `r_account_prozent` int(11) DEFAULT NULL,
  `r_res_buildings` int(11) DEFAULT NULL,
  `r_work_board` int(11) DEFAULT NULL,
  `r_iridium` int(11) DEFAULT NULL,
  `r_holzium` int(11) DEFAULT NULL,
  `r_water` int(11) DEFAULT NULL,
  `r_oxygen` int(11) DEFAULT NULL,
  `timestamp` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `long_term_flights`
--

CREATE TABLE IF NOT EXISTS `long_term_flights` (
`id` int(11) NOT NULL,
  `city` varchar(11) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT '',
  `f_id` int(11) unsigned NOT NULL DEFAULT '0',
  `attack_deny_id` int(10) unsigned NOT NULL DEFAULT '0',
  `f_action` varchar(255) NOT NULL DEFAULT '',
  `f_plunder` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_iridium` varchar(20) NOT NULL DEFAULT '0',
  `f_holzium` varchar(20) NOT NULL DEFAULT '0',
  `f_water` varchar(20) NOT NULL DEFAULT '0',
  `f_oxygen` varchar(20) NOT NULL DEFAULT '0',
  `f_spy` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize_jobs` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize_fleets` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize_hangar` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_colonize_nobonus` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `f_give` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `f_start` double NOT NULL DEFAULT '0',
  `f_arrival` double NOT NULL DEFAULT '0',
  `f_target` varchar(11) NOT NULL DEFAULT '',
  `f_target_user` varchar(20) NOT NULL DEFAULT '',
  `f_name` varchar(255) DEFAULT NULL,
  `f_name_show` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `f_sparrow` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_blackbird` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_raven` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_eagle` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_falcon` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_nightingale` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_ravager` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_destroyer` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_espionage_probe` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_settler` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_scarecrow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_bomber` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_small_transporter` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_medium_transporter` smallint(6) unsigned NOT NULL DEFAULT '0',
  `f_big_transporter` smallint(5) unsigned NOT NULL DEFAULT '0',
  `f_flugzeuge_anzahl` int(11) unsigned NOT NULL DEFAULT '0',
  `msg` varchar(255) NOT NULL DEFAULT '',
  `msg_text` text NOT NULL,
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `f_volume` varchar(20) NOT NULL DEFAULT '0',
  `code` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `medals`
--

CREATE TABLE IF NOT EXISTS `medals` (
  `user` int(16) NOT NULL,
  `vote` int(5) NOT NULL DEFAULT '0',
  `m_login` int(5) NOT NULL DEFAULT '0',
  `d_login` double NOT NULL,
  `m_tech` int(5) NOT NULL DEFAULT '0',
  `d_tech` double NOT NULL,
  `m_points` int(5) NOT NULL DEFAULT '0',
  `d_points` double NOT NULL,
  `m_kolo` int(5) NOT NULL DEFAULT '0',
  `n_kolo` int(5) NOT NULL DEFAULT '0',
  `d_kolo` double NOT NULL,
  `m_production` int(5) NOT NULL DEFAULT '0',
  `d_production` double NOT NULL,
  `m_bbt` int(5) NOT NULL DEFAULT '0',
  `d_bbt` double NOT NULL,
  `m_wk` int(5) NOT NULL DEFAULT '0',
  `d_wk` double NOT NULL,
  `m_bz` int(5) NOT NULL DEFAULT '0',
  `d_bz` double NOT NULL,
  `m_defence` int(5) NOT NULL DEFAULT '0',
  `d_defence` double NOT NULL,
  `m_trade` int(5) NOT NULL DEFAULT '0',
  `d_trade` double NOT NULL,
  `m_fleet` int(5) NOT NULL DEFAULT '0',
  `d_fleet` double NOT NULL,
  `m_defence2` int(5) NOT NULL DEFAULT '0',
  `d_defence2` double NOT NULL,
  `m_attack` int(5) NOT NULL DEFAULT '0',
  `d_attack` double NOT NULL,
  `m_plunder` int(8) NOT NULL DEFAULT '0',
  `d_plunder` double NOT NULL,
  `m_weapon` int(5) NOT NULL,
  `d_weapon` double NOT NULL,
  `m_gear` int(5) NOT NULL,
  `d_gear` double NOT NULL,
  `m_scare` int(5) NOT NULL DEFAULT '0',
  `n_scare` int(10) NOT NULL DEFAULT '0',
  `d_scare` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Uebersicht ueber alle Medallien';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `multi_angemeldete`
--

CREATE TABLE IF NOT EXISTS `multi_angemeldete` (
  `user` varchar(20) NOT NULL DEFAULT '',
  `vorname` varchar(25) NOT NULL DEFAULT '',
  `name` varchar(25) NOT NULL DEFAULT '',
  `strasse` varchar(50) NOT NULL DEFAULT '',
  `plz` varchar(5) NOT NULL DEFAULT '',
  `ort` varchar(25) NOT NULL DEFAULT '',
  `land` varchar(20) NOT NULL DEFAULT '',
  `tel` varchar(15) NOT NULL DEFAULT '',
  `kommentar` text NOT NULL,
  `no_double_ip` enum('YES','NO') NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `multi_angemeldete_doppel_ip`
--

CREATE TABLE IF NOT EXISTS `multi_angemeldete_doppel_ip` (
  `user` varchar(20) NOT NULL DEFAULT '',
  `doppel_ip_user` varchar(20) NOT NULL DEFAULT '',
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `multi_check`
--

CREATE TABLE IF NOT EXISTS `multi_check` (
`id` int(11) NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `get` text NOT NULL,
  `post` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `multi_iphash`
--

CREATE TABLE IF NOT EXISTS `multi_iphash` (
  `iphash` varchar(32) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `provider` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `multi_sessions`
--

CREATE TABLE IF NOT EXISTS `multi_sessions` (
`id` bigint(20) unsigned NOT NULL,
  `id_hash` varchar(32) NOT NULL DEFAULT '',
  `pc_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `sess_id` varchar(32) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT '',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `logout_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL,
  `client` varchar(255) NOT NULL DEFAULT '',
  `last_id` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter`
--

CREATE TABLE IF NOT EXISTS `newsletter` (
  `email` varchar(60) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_ber`
--

CREATE TABLE IF NOT EXISTS `news_ber` (
`id` int(11) NOT NULL,
  `attack_user` varchar(20) NOT NULL DEFAULT '',
  `defense_user` varchar(20) NOT NULL DEFAULT '',
  `attack_bid` varchar(32) NOT NULL DEFAULT '',
  `defense_bid` varchar(32) NOT NULL DEFAULT '',
  `attack_city` varchar(11) NOT NULL DEFAULT '',
  `defense_city` varchar(11) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `attack_seen` enum('N','Y') NOT NULL DEFAULT 'N',
  `defense_seen` enum('N','Y') NOT NULL DEFAULT 'N',
  `attack_seen_sitter` enum('Y','N') NOT NULL DEFAULT 'N',
  `defense_seen_sitter` enum('N','Y') NOT NULL DEFAULT 'N',
  `attack_delete` enum('N','Y') NOT NULL DEFAULT 'N',
  `defense_delete` enum('N','Y') NOT NULL DEFAULT 'N',
  `attack_delete_sitter` enum('N','Y') NOT NULL DEFAULT 'N',
  `defense_delete_sitter` enum('N','Y') NOT NULL DEFAULT 'N',
  `attackers_alliance` varchar(25) DEFAULT NULL,
  `defenders_alliance` varchar(25) DEFAULT NULL,
  `attack_xmlid` varchar(32) DEFAULT NULL,
  `defense_xmlid` varchar(32) NOT NULL DEFAULT '',
  `f_name_show` enum('N','Y') NOT NULL DEFAULT 'N',
  `f_name` varchar(255) NOT NULL DEFAULT '',
  `iridium` int(10) unsigned DEFAULT NULL,
  `holzium` int(10) unsigned DEFAULT NULL,
  `water` int(10) unsigned DEFAULT NULL,
  `oxygen` int(10) unsigned DEFAULT NULL,
  `lost` char(1) NOT NULL,
  `error` enum('empty','Die Kolonie konnte nicht erobert werden, da dein Kommunikationszentrum zu klein ist.','Hangar','Settler') NOT NULL DEFAULT 'empty',
  `points` smallint(5) unsigned NOT NULL,
  `shield` smallint(5) unsigned NOT NULL,
  `art` enum('attack','attack_back','sell_to_depot','sell_from_depot','transport','transport_back','plane_sell','plane_buy','scan') NOT NULL,
  `plunder` enum('N','Y') NOT NULL DEFAULT 'N',
  `colonize` enum('N','Y') NOT NULL DEFAULT 'N',
  `userprotection` enum('N','Y') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_ber_`
--

CREATE TABLE IF NOT EXISTS `news_ber_` (
  `ID` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `ad` enum('attack','defense') NOT NULL,
  `before` varchar(5) NOT NULL,
  `after` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_directories`
--

CREATE TABLE IF NOT EXISTS `news_directories` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_er`
--

CREATE TABLE IF NOT EXISTS `news_er` (
`id` int(11) NOT NULL,
  `city` varchar(11) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `seen` enum('N','Y') NOT NULL DEFAULT 'N',
  `seen_sitter` enum('Y','N') NOT NULL DEFAULT 'N',
  `topic` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_igm_umid`
--

CREATE TABLE IF NOT EXISTS `news_igm_umid` (
`id` int(11) NOT NULL,
  `dir` int(10) unsigned NOT NULL DEFAULT '0',
  `owner` varchar(20) NOT NULL,
  `sender` text NOT NULL,
  `recipient` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `seen` enum('N','Y') NOT NULL DEFAULT 'N',
  `confirm` enum('N','Y','S') NOT NULL DEFAULT 'N',
  `topic` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_msg`
--

CREATE TABLE IF NOT EXISTS `news_msg` (
`id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '',
  `topic` varchar(255) NOT NULL DEFAULT '',
  `msg` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_support`
--

CREATE TABLE IF NOT EXISTS `news_support` (
`id` int(11) NOT NULL,
  `author` varchar(50) DEFAULT NULL,
  `subject` text,
  `text` text,
  `urgency` int(1) DEFAULT NULL,
  `timestamp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `new_city_history`
--

CREATE TABLE IF NOT EXISTS `new_city_history` (
  `city` varchar(20) NOT NULL,
  `owner` varchar(20) NOT NULL,
  `time` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `new_tutorial`
--

CREATE TABLE IF NOT EXISTS `new_tutorial` (
  `user` int(11) NOT NULL,
  `tutorial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `new_user`
--

CREATE TABLE IF NOT EXISTS `new_user` (
  `user` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `paypal_cart_info`
--

CREATE TABLE IF NOT EXISTS `paypal_cart_info` (
  `txnid` varchar(30) NOT NULL DEFAULT '',
  `itemname` varchar(255) NOT NULL DEFAULT '',
  `itemnumber` varchar(50) DEFAULT NULL,
  `os0` varchar(20) DEFAULT NULL,
  `on0` varchar(50) DEFAULT NULL,
  `os1` varchar(20) DEFAULT NULL,
  `on1` varchar(50) DEFAULT NULL,
  `quantity` char(3) NOT NULL DEFAULT '',
  `invoice` varchar(255) NOT NULL DEFAULT '',
  `custom` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `paypal_payment_info`
--

CREATE TABLE IF NOT EXISTS `paypal_payment_info` (
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `buyer_email` varchar(100) NOT NULL DEFAULT '',
  `street` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `state` char(3) NOT NULL DEFAULT '',
  `zipcode` varchar(11) NOT NULL DEFAULT '',
  `memo` varchar(255) DEFAULT NULL,
  `itemname` varchar(255) DEFAULT NULL,
  `itemnumber` varchar(50) DEFAULT NULL,
  `os0` varchar(20) DEFAULT NULL,
  `on0` varchar(50) DEFAULT NULL,
  `os1` varchar(20) DEFAULT NULL,
  `on1` varchar(50) DEFAULT NULL,
  `quantity` char(3) DEFAULT NULL,
  `paymentdate` varchar(50) NOT NULL DEFAULT '',
  `paymenttype` varchar(10) NOT NULL DEFAULT '',
  `txnid` varchar(30) NOT NULL DEFAULT '',
  `mc_gross` varchar(6) NOT NULL DEFAULT '',
  `mc_fee` varchar(5) NOT NULL DEFAULT '',
  `paymentstatus` varchar(15) NOT NULL DEFAULT '',
  `pendingreason` varchar(10) DEFAULT NULL,
  `txntype` varchar(10) NOT NULL DEFAULT '',
  `tax` varchar(10) DEFAULT NULL,
  `mc_currency` varchar(5) NOT NULL DEFAULT '',
  `reasoncode` varchar(20) NOT NULL DEFAULT '',
  `custom` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `datecreation` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `paypal_subscription_info`
--

CREATE TABLE IF NOT EXISTS `paypal_subscription_info` (
  `subscr_id` varchar(255) NOT NULL DEFAULT '',
  `sub_event` varchar(50) NOT NULL DEFAULT '',
  `subscr_date` varchar(255) NOT NULL DEFAULT '',
  `subscr_effective` varchar(255) NOT NULL DEFAULT '',
  `period1` varchar(255) NOT NULL DEFAULT '',
  `period2` varchar(255) NOT NULL DEFAULT '',
  `period3` varchar(255) NOT NULL DEFAULT '',
  `amount1` varchar(255) NOT NULL DEFAULT '',
  `amount2` varchar(255) NOT NULL DEFAULT '',
  `amount3` varchar(255) NOT NULL DEFAULT '',
  `mc_amount1` varchar(255) NOT NULL DEFAULT '',
  `mc_amount2` varchar(255) NOT NULL DEFAULT '',
  `mc_amount3` varchar(255) NOT NULL DEFAULT '',
  `recurring` varchar(255) NOT NULL DEFAULT '',
  `reattempt` varchar(255) NOT NULL DEFAULT '',
  `retry_at` varchar(255) NOT NULL DEFAULT '',
  `recur_times` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) DEFAULT NULL,
  `payment_txn_id` varchar(50) NOT NULL DEFAULT '',
  `subscriber_emailaddress` varchar(255) NOT NULL DEFAULT '',
  `datecreation` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plane_trade`
--

CREATE TABLE IF NOT EXISTS `plane_trade` (
`id` tinyint(3) unsigned NOT NULL,
  `plane_type` tinyint(3) unsigned NOT NULL,
  `start_phase` enum('yes','no') NOT NULL DEFAULT 'yes',
  `stock` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `stock_target` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT 'Sollbestand',
  `avarage_stock` double unsigned NOT NULL DEFAULT '0',
  `acquisitions` bigint(20) unsigned NOT NULL DEFAULT '0',
  `avarage_acquisitions` double unsigned NOT NULL DEFAULT '0',
  `sales` bigint(20) unsigned NOT NULL DEFAULT '0',
  `avarage_sales` double unsigned NOT NULL DEFAULT '0',
  `cost_factor` double unsigned NOT NULL DEFAULT '1.485',
  `gain_factor` double unsigned NOT NULL DEFAULT '1.35'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Daten für Tabelle `plane_trade`
--

INSERT INTO `plane_trade` (`id`, `plane_type`, `start_phase`, `stock`, `stock_target`, `avarage_stock`, `acquisitions`, `avarage_acquisitions`, `sales`, `avarage_sales`, `cost_factor`, `gain_factor`) VALUES
(1, 0, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(2, 1, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(3, 2, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(4, 3, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(5, 4, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(6, 5, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(7, 6, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(8, 7, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(9, 8, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(10, 9, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(11, 10, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(12, 11, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(13, 12, 'yes', 0, 16777215, 0, 0, 0, 8, 0, 1.2, 0.8),
(14, 13, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8),
(15, 14, 'yes', 0, 16777215, 0, 0, 0, 0, 0, 1.2, 0.8);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plane_transactions`
--

CREATE TABLE IF NOT EXISTS `plane_transactions` (
`id` bigint(20) unsigned NOT NULL,
  `plane` tinyint(3) unsigned NOT NULL,
  `number` mediumint(8) unsigned NOT NULL,
  `user` varchar(20) NOT NULL,
  `type` enum('sell','buy') NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `query_error_log`
--

CREATE TABLE IF NOT EXISTS `query_error_log` (
`id` mediumint(8) unsigned NOT NULL,
  `query` text NOT NULL,
  `ort` varchar(255) DEFAULT NULL,
  `error` text NOT NULL,
  `ip` varchar(20) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT '',
  `referer` varchar(255) DEFAULT NULL,
  `gpc` text,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `query_log`
--

CREATE TABLE IF NOT EXISTS `query_log` (
`id` mediumint(8) unsigned NOT NULL,
  `query` text NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ort` varchar(60) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ranks`
--

CREATE TABLE IF NOT EXISTS `ranks` (
`id` int(10) unsigned NOT NULL,
  `tag` varchar(25) NOT NULL DEFAULT '',
  `rank` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sperrliste_email`
--

CREATE TABLE IF NOT EXISTS `sperrliste_email` (
  `email` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sperrliste_email_domain`
--

CREATE TABLE IF NOT EXISTS `sperrliste_email_domain` (
  `domain` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sperrliste_igm`
--

CREATE TABLE IF NOT EXISTS `sperrliste_igm` (
  `user` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sperrliste_username`
--

CREATE TABLE IF NOT EXISTS `sperrliste_username` (
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `supporterdata`
--

CREATE TABLE IF NOT EXISTS `supporterdata` (
  `supporter` varchar(20) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '0',
  `access` decimal(2,0) DEFAULT NULL,
  `lastlogin` varchar(20) DEFAULT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `toplist_alliances`
--

CREATE TABLE IF NOT EXISTS `toplist_alliances` (
`pos` int(10) unsigned NOT NULL,
  `tag` varchar(25) NOT NULL DEFAULT '',
  `members` int(10) unsigned NOT NULL DEFAULT '0',
  `points` int(10) unsigned NOT NULL DEFAULT '0',
  `average` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `toplist_city`
--

CREATE TABLE IF NOT EXISTS `toplist_city` (
`pos` int(10) unsigned NOT NULL,
  `city` varchar(11) NOT NULL DEFAULT '',
  `city_name` varchar(20) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT '',
  `points` int(10) unsigned NOT NULL DEFAULT '0',
  `alliance` varchar(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `toplist_user`
--

CREATE TABLE IF NOT EXISTS `toplist_user` (
`pos` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `points` int(10) unsigned NOT NULL DEFAULT '0',
  `alliance` varchar(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `toplist_user_power`
--

CREATE TABLE IF NOT EXISTS `toplist_user_power` (
`pos` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `power` int(10) unsigned NOT NULL DEFAULT '0',
  `alliance` varchar(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tutorial`
--

CREATE TABLE IF NOT EXISTS `tutorial` (
  `user` varchar(20) CHARACTER SET utf8 NOT NULL,
  `page` varchar(20) CHARACTER SET utf8 NOT NULL,
  `number` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type_alliance`
--

CREATE TABLE IF NOT EXISTS `type_alliance` (
`type` tinyint(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `bauzeit` int(10) unsigned NOT NULL,
  `costs` int(10) unsigned NOT NULL,
  `max_stufe` tinyint(3) unsigned NOT NULL,
  `sorting` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `type_alliance`
--

INSERT INTO `type_alliance` (`type`, `name`, `bauzeit`, `costs`, `max_stufe`, `sorting`) VALUES
(1, 'Hauptquartier', 129600, 30000, 10, 1),
(2, 'Informationszentrum', 129600, 4000, 10, 2),
(3, 'Produktionshalle', 86400, 15000, 10, 3),
(4, 'Allianzhangar', 172800, 40000, 10, 4),
(5, 'Silo', 21600, 200, 0, 5),
(6, 'Abwehrzentrum', 36000, 8000, 100, 6),
(7, 'Startrampe', 72000, 5000, 10, 7);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type_building`
--

CREATE TABLE IF NOT EXISTS `type_building` (
`type` tinyint(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `cost1` int(10) unsigned NOT NULL,
  `cost2` int(10) unsigned NOT NULL,
  `cost3` int(10) unsigned NOT NULL,
  `cost4` int(10) unsigned NOT NULL,
  `production` varchar(256) NOT NULL,
  `base_duration` int(10) unsigned NOT NULL,
  `sorting` tinyint(3) unsigned NOT NULL,
  `types` enum('production','space','time_reduction','fleet','tech','comm','trade') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type_plane`
--

CREATE TABLE IF NOT EXISTS `type_plane` (
`type` tinyint(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `costs1` int(10) unsigned NOT NULL,
  `costs2` int(10) unsigned NOT NULL,
  `kw` int(10) unsigned NOT NULL,
  `speed` int(10) unsigned NOT NULL,
  `capacity` int(10) unsigned NOT NULL,
  `consumption` int(10) unsigned NOT NULL,
  `weapon_research_type` tinyint(3) unsigned NOT NULL,
  `engine_research_type` tinyint(3) unsigned NOT NULL,
  `base_duration` int(10) unsigned NOT NULL,
  `sorting` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Daten für Tabelle `type_plane`
--

INSERT INTO `type_plane` (`type`, `name`, `description`, `costs1`, `costs2`, `kw`, `speed`, `capacity`, `consumption`, `weapon_research_type`, `engine_research_type`, `base_duration`, `sorting`) VALUES
(1, 'Sparrow', 'Allgemeines:\r\nDer Sparrow gehÃ¶rt zur Klasse der leichten Kampfflugzeuge, die mit einem Oxidationsantrieb sowie Elektronensequenzwaffen ausgerÃ¼stet sind.\r\n\r\nEmpfehlung:\r\nDieses schnelle Kampfflugzeug hat die niedrigsten Produktionskosten, den geringsten Treibstoffverbrauch und die kÃ¼rzeste Bauzeit. Auf der anderen Seite ist der "Spatz" allerdings auch das schwÃ¤chste Kampfflugzeug auf Erde II, besitzt aber zumindest einen kleinen Laderaum. Der Sparrow ist Geschwindigkeitsfanatikern und Liebhabern der Waffengattung "ESW" zu empfehlen. Vor allem im Krieg kann die geringe Bauzeit von Bedeutung sein. Wer mit dem Sparrow grÃ¶ÃŸere Angriffe fliegen mÃ¶chte, sollte einigen Aufwand in die Weiterentwicklung aller verwendeten Technologien stecken.\r\n\r\nAnekdote:\r\nWÃ¤hrend meiner Recherchen Ã¼ber die Eigenschaften des Sparrows, sagte ein Techniker mit vÃ¶llig ernster Miene zu mir: "Wenn eine meiner StÃ¤dte angegriffen wird und ich die Wahl habe, mich mit einem Sparrow oder dem Modellflugzeug meines Sohnes zu verteidigen, glauben Sie mir, ich wÃ¼rde ohne zu zÃ¶gern das Modellflugzeug wÃ¤hlen!"', 500, 500, 100, 2000, 500, 70, 1, 4, 0, 1),
(2, 'Blackbird', 'Allgemeines:\r\nDer Typ Blackbird besitzt einen Oxidationsantrieb und ist mit Elektronensequenzwaffen ausgestattet. Er ist das Nachfolgemodell des Spatzen, in dem die Kampfkraft zu Lasten anderer Eigenschaften voll ausgereizt wurde.\r\n\r\nEmpfehlung:\r\nDie "Amseln" sind in erster Linie Kampfflugzeuge zur UnterstÃ¼tzung der Heimatverteidigung. fÃ¼r den Einsatz in FeldzÃ¼gen machen sie schwache Motoren und fehlende FrachtrÃ¤ume so gut wie ungeeignet.\r\n\r\nAnekdote:\r\nEin alter Abenteurer erzÃ¤hlte mir folgende Geschichte Ã¼ber seine erste Begegnung mit den Blackbirds: "Beim Anflug auf eine RohstofflagerstÃ¤tte in der NÃ¤he schaute ich gerade Alfred Hitchcocks "Die VÃ¶gel", wÃ¤hrend mein Zweiter die Maschine ans Ziel brachte, als sich der Himmel verdunkelte. Neugierig starrte ich durch die Frontscheibe, um zu sehen, was da vor sich ging. Ich erspÃ¤hte einen groÃŸen Schwarm von schwarzen VÃ¶geln, der sich plÃ¶tzlich Ã¼ber den angepeilten LÃ¤ndereien erhob und uns die Sicht nahm. Es war, als wÃ¤re der Film Wirklichkeit geworden! Da hat mein Zweiter gekniffen. Ich hÃ¤tte trotzdem angegriffen, wÃ¤re ich nicht in Ohnmacht gefallen."', 4000, 5000, 1000, 100, 0, 100, 0, 0, 0, 2),
(3, 'Raven', 'Allgemeines:\r\nDer Raven ist das tragstÃ¤rkste Kampfflugzeug der ESW/OXI-Gattung.\r\n\r\nEmpfehlung:\r\nDie "Raben" sind hervorragend fÃ¼r das PlÃ¼ndern geeignet und lÃ¶sen die Sparrows in diesem Punkt relativ bald ab, sobald sich die hÃ¶heren Forschungskosten rentieren. In Kriegszeiten ist die lÃ¤ngere Bauzeit im Vergleich zur Basisversion allerdings von Nachteil. Zu empfehlen ist der Raven daher als Farmflugzeug nach dem Sparrow und spÃ¤ter als Hauptbestandteil der ESW-Angriffsflotten.\r\n\r\n\r\n\r\nAnekdote:\r\nIch sprach mit dem Entwickler des Ravens und befragte ihn bezÃ¶glich der Namensgebung: "Eigentlich wollte ich das Flugzeug "Diebische Elster" nennen, aufgrund seiner guten Dienste fÃ¼r Farmer. Meinem Vorgesetzten ging das jedoch zu weit. Er sagte, man mÃ¼sse an das Image der Firma denken und einen neutralen Namen wÃ¤hlen. Was fÃ¼r ein SpieÃŸer! Ich konnte es mir jedoch nicht verkneifen, den Namen "Diebische Elster" auf die Fallschirme, mit denen die Raven ausgerÃ¼stet werden, nÃ¤hen zu lassen, hihi. Naja, ich wurde gefeuert und arbeite jetzt bei der Konkurrenz, bei PSW."', 1500, 1500, 350, 2300, 1500, 50, 0, 0, 0, 3),
(4, 'Eagle', 'Allgemeines:\r\nMit dem Eagle gelang es den Wissenschaftlern von Erde II, einen JÃ¤ger mit bis dato ungeahnter Kombination aus Kampfkraft, TragfÃ¤higkeit und Geschwindigkeit zu konstruieren: Es ist das erste Flugzeug der neuen Protonensequenzwaffengattung (PSW) und mit dem leistungsstarken Hoverantrieb ausgestattet.\r\n\r\nEmpfehlung:\r\nDank der neuartigen Technologien verfÃ¼gt der "Adler" Ã¼ber eine bessere Kombination aller Eigenschaften als alle Kampfflugzuge der ESW/OXI-Sparte. Trotz dieser Verbesserungen liegt der Treibstoffverbrauch nur geringfÃ¼gig Ã¼ber dem des Ravens, wobei aber auch der Laderaum erheblich grÃ¶ÃŸer ist. Die einzigen Nachteile gegenÃ¼ber den ESW-Flugzeugen sind die hÃ¶here Bauzeit und die gestiegenen Produktions- sowie die notwendigen Forschungskosten. Aufgrund der oben genannten Eigenschaften ist der Eagle ein gutes Beuteflugzeug, mit dem PSWler kostengÃ¼nstige, aber trotzdem schlagkrÃ¤ftige Angriffe fliegen kÃ¶nnen.\r\n\r\nAnekdote:\r\nMein Bekannter, ein ehemaliger ESW-Techniker, hat an der Entwicklung des Eagles mitgearbeitet: "Mit meinem neuen Chef hab ich echt das groÃŸe Los gezogen. Er nahm meinen Namensvorschlag "Eagle" begeistert an, als ich meinte, dass mich dieses Flugzeug an einen Adler erinnere, der sich mit messerscharfen Klauen erbarmungslos auf seine Beute niederstÃ¼rzt. Vielleicht sollte ich ins Marketing gehen..."', 2500, 2500, 500, 1600, 2500, 75, 0, 0, 0, 4),
(5, 'Falcon', 'Allgemeines:\r\nDer Falcon ist eine AufrÃ¼stung des Eagles und verfÃ¼gt ebenfalls Ã¼ber Protonensequenzwaffen und einen Hoverantrieb.\r\n\r\nEmpfehlung:\r\nDie Wissenschafter konzentrierten sich bei der Entwicklung des "Falken" besonders auf die Verbesserung der Kampfkraft. So ist es kein Wunder, dass der Falcon weitaus ehrfurchtgebietender als der Eagle zuschlÃ¤gt, jedoch ging dies vollkommen zu Lasten von Antrieb und Transportraum. Somit dient der Falke zumeist ausschlieÃŸlich der Verteidigung und ist bei Tage wÃ¤hrenden Terrorangriffen beliebt, wo es ausnahmsweise nicht auf Geschwindigkeit ankommt.\r\n\r\nAnekdote:\r\nIch sprach wiederum mit meinem Bekannten dem ehemaligen ESW-Techniker: "Pah, ich hÃ¤tte wirklich ins Marketing gehen sollen. Die Typen haben doch tatsÃ¤chlich meine Idee geklaut und den Falcon nach einem Greifvogel benannt. Dabei ist der Falc gar kein Jagdflugzeug! Ich hÃ¤tte diesen Schlachtenflieger viel eher "Dreadnought" genannt. Aber diese ideenklauenden Streber haben ja keine Ahnung von Kampfflugzeugen."', 6000, 7500, 2000, 50, 0, 150, 0, 0, 0, 5),
(6, 'Nightingale', 'Allgemeines:\r\nDer Typ Nightingale ist das KÃ¶nigsflugzeug der PSW-Klasse und besitzt ebenfalls einen Hoverantrieb.\r\n\r\nEmpfehlung:\r\nDiese Neuentwicklung ist ein erstaunlich ausgewogenes Kampfflugzeug. Es bietet eine gute Feuerkraft, aber ist trotzdem nicht viel langsamer als der Eagle! Bei diesen Attributen ist es Ã¼beraus erstaunlich, dass die "Nachtigall" zudem Ã¼ber einen fÃ¼r ein Kampfflugzeug riesigen Laderaum verfÃ¼gt, was sich aber auch in erhÃ¶htem Treibstoffverbrauch niederschlÃ¤gt. Die Nachteile bestehen in hÃ¶heren Produktionskosten und Bauzeiten. Die Nachtigall ist daher uneingeschrÃ¤nkt dem PSW-Liebhaber in allen denkbaren Angriffssituationen zu empfehlen.\r\n\r\nAnekdote:\r\nDer Besitzer einer mÃ¤chtigen Nightingale-Flotte meinte begeistert zu mir: "Nichts ist schÃ¶ner als die "lieblichen" TÃ¶ne ihrer feuernden GeschÃ¼tze, die meine Gegner kopflos in die Flucht schlagen. Die Feuerrate ist wirklich enorm und die Ironie der Namensgebung ein herrlicher Witz, der meine Feinde zusÃ¤tzlich verhÃ¶hnt. Meine Frau ist der Meinung, ich sei von ihnen besessen, nur weil ich ihr zum Hochzeitstag eine echte Nachtigall samt KÃ¤fig schenkte. Dabei sind diese VÃ¶gel verdammt selten und schweineteuer. Tja, das nÃ¤chste Mal gibt es nur Blumen, sie ist selbst schuld."', 7500, 12500, 1500, 2000, 10000, 200, 0, 0, 0, 6),
(7, 'Ravager', 'Allgemeines:\r\nDer Ravager ist das Ergebnis langer und ruinÃ¶s teurer Forschungsarbeit. Die neu entwickelten Neutronensequenzwaffen und der Antigravitationsantrieb waren nun soweit fortgeschritten, dass sie auch fÃ¼r Kampfflugzeuge verwendet werden konnten.\r\n\r\nEmpfehlung:\r\nDer "VerwÃ¼ster" macht seinem Namen alle Ehre. Durch seine beachtliche Feuerkraft ist dieses Flugzeug das RÃ¼ckgrat der meisten modernen StreitkrÃ¤fte. Im groÃŸen Verbund durchdringt er fast jede Verteidigungsstellung und kann auch weiter entfernte Ziele noch in annehmbarer Zeit erreichen. Sobald man sich eine Ravager-Flotte leisten kann, sollte der enorme Treibstoffverbrauch kaum noch stÃ¶ren. Die grÃ¶ÃŸten Nachteile sind die hohen Produktionskosten und die immense Bauzeit die benÃ¶tigt wird, um dieses hoch komplexe FluggerÃ¤t zu bauen. Aus diesem Grund sollte man sich ganz genau Ã¼berlegen, wann und gegen wen man sie einsetzt.\r\n\r\nAnekdote:\r\nNach einer Legende stammt der Name des Ravagers, der unter der Bezeichnung N-946 entwickelt wurde, aus einer der ersten Schlachten mit dessen Beteiligung. Eine kleine Flotte verwandelte eine blÃ¼hende Metropole in wenigen Minuten in einen Steinhaufen. Nachdem die Bilder den Generalstab erreichten, rief einer der GenerÃ¤le unwillig auf: "Da ist ja nur WÃ¼ste! Legt mal den richtigen Film ein!" Doch bei WÃ¼ste blieb es und das neue Flugzeug nannte man VerwÃ¼ster.', 15000, 20000, 2500, 1600, 7500, 350, 0, 0, 0, 7),
(8, 'Destroyer', 'Allgemeines:\r\nDer Destroyer ist die KrÃ¶nung der Kampfflugzeuge. Ebenso wie beim Ravager basieren seine Waffen auf der Neutronensequenzwaffentechnologie, sein Antrieb auf Antigravitation.\r\n\r\nEmpfehlung:\r\nDer "ZerstÃ¶rer" markiert den Endpunkt der Flugzeugentwicklung. Er ist eine grÃ¶ÃŸere Version des Ravagers. Dadurch kann er mit mehr Waffen bestÃ¼ckt werden, was seinen Kampfwert nochmal um einiges steigert. Auch dem Laderaum kommt diese Tatsache zu Gute , jedoch zu Lasten der Geschwindigkeit. Bedingt durch seine GrÃ¶ÃŸe steigen auch der Treibstoffverbrauch und die Produktionskosten noch einmal deutlich an, bleiben aber annÃ¤hernd gleich, wenn man sie ins VerhÃ¤ltnis zum Kampfwert setzt. Die fast doppelt so lange Bauzeit fÃ¼r einen Destroyer trÃ¤gt das ÃƒÆ’Ã†â€™Ãƒâ€¦Ã¢â‚¬Å“brige dazu bei, dass dieser Flugzeugtyp noch seltener und damit wertvoller fÃ¼r seinen Besitzer wird.\r\n\r\nAnekdote:\r\nIch befragte einen MilitÃ¤rhistoriker Ã¼ber die Rolle des Destroyers in der Geschichte der KriegsfÃ¼hrung: "Schon alleine die Anwesenheit dieser mÃ¶chtigen Kampfmaschine lÃ¤sst die Feinde vor Furcht erzittern. Vor einiger Zeit gab es einen Konflikt, da kapitulierte die eine Kriegspartei, noch bevor der erste Schuss fiel, weil sie das GerÃ¼cht vernahm, dass der Gegner Ã¼ber Destroyer verfÃ¼ge."', 35000, 30000, 4000, 1200, 12500, 450, 0, 0, 0, 8),
(9, 'Spionagesonde', 'Allgemeines:\r\nDie Sonde gehÃ¶rt zu der Klasse der UnterstÃ¼tzungsflugzeuge. Ein unbemanntes FluggerÃ¤t voll gestopft mit modernster Spionagetechnik. Auf Grund des geringen Gewichtes und eines speziell fÃ¼r die Sonde entwickelten Antriebs kann sie enorme Geschwindigkeiten erreichen.\r\n\r\nEmpfehlung:\r\nSonden sind ideal geeignet, viele Informationen Ã¼ber feindliche StÃ¤dte zu sammeln. RÃ¤uberische StÃ¤dteherren nutzen sie oftmals zur vorhergehenden Kontrolle, ob sich denn ein Ã¼berfall lohnt. Auch im Vorfeld eines Kolotakes, zur AbschÃ¤tzung der mÃ¶glichen Gegenwehr, werden sie gerne eingesetzt. Zwar sind diese wieselflinken Flugmaschinen in Minutenschnelle beim anvisierten Ziel und sammeln Unmengen an Informationen, doch werden sie nach der ÃƒÆ’Ã†â€™Ãƒâ€¦Ã¢â‚¬Å“bertragung der Informationen mit annÃ¤hernd hundertprozentiger Wahrscheinlichkeit von der gegnerischen Flugabwehr zerstÃ¶rt. Aus diesem Grund sollte man immer genug Sonden auf Lager haben.\r\n\r\nAnekdote:\r\nEin guter Freund, der jahrelang als Radaroffizier den Luftraum Ã¼berwachte, sagte einmal: "Sonden sind wie MÃ¼cken - Extrem lÃ¤stig, aber mehr als ein Jucken richten sie nicht an."', 125, 75, 1, 20000, 0, 8, 0, 0, 0, 9),
(10, 'Settler', 'Allgemeines:\r\nDer Settler gehÃ¶rt zur Klasse der Spezialflugzeuge und besitzt einen Oxidationsantrieb. Der Settler verfÃ¼gt heute nicht mehr Ã¼ber Bordwaffen, denn er ist nicht dem Kampf, sondern der friedlichen Kolonisierung gewidmet worden.\r\n\r\nEmpfehlung:\r\nNach beharrlicher Forschung auf dem Gebiet der Lagerverwaltung war es nun mÃ¶glich, ein Flugzeug zu bauen, das ausreichend Platz bot, um genug Material fÃ¼r die NeugrÃ¼ndung einer Siedlung mitzufÃ¼hren. Der "Siedler" ist aufgrund seiner GrÃ¶ÃŸe ein langsames Oxi-Flugzeug. Nach einer erfolgreichen Landung auf unbewohntem Gebiet wird in Windeseile die Grundlage einer neuen Stadt erschaffen, wobei auch das Flugzeug als Baumaterial aufgebraucht wird. Dieses Sonderflugzeug ist zwar eines der teuersten auf Erde II, jedoch fÃ¼r Kommandanten die einzige MÃ¶glichkeit, neue Kolonien zu grÃ¼nden.\r\n\r\nAnekdote:\r\nEin Historiker erzÃ¤hlte mir folgende Geschichte: "Vor einigen Jahren war es bei der Wirtschaftselite Mode, zum Zeitvertreib verschwenderische Schlachten mit gigantischen Settler-Flotten zu fÃ¼hren. Diese Dekadenz diente jedoch nicht nur dem SpaÃŸ, sondern war gleichzeitig auch eine Demonstration der StÃ¤rke und eine Zweckentfremdung, die auch heute noch ihresgleichen sucht."', 60000, 120000, 1000, 1000, 0, 300, 0, 0, 0, 10),
(11, 'Scarecrow', 'Allgemeines:\r\nDie Scarecrow bildet die nÃ¤chste Generation der Sonderflugzeuge und ist mit einem Oxidationsantrieb, aber nur einfachen Bordwaffen ausgestattet, die sich nicht verbessern lassen. Sie ermÃ¶glicht es kriegerischen StÃ¤dteherren, fremde Kolonien zu erobern.\r\n\r\nEmpfehlung:\r\nDie "Vogelscheuche" ist das am meisten gefÃ¼rchtete Flugzeug auf Erde II. Sie stellt den Destroyer jedoch nicht aufgrund ihrer Kampfkraft in den Schatten, es ist vielmehr die einzigartige FÃ¤higkeit der Scarecrow, fremde Kolonien zu erobern. Die Scarecrow ist damit sozusagen das kriegerische Pendant zum Settler. Es ist jedem potenziellen Eroberer anzuraten, stets mindestens zwei Scarecrows in seiner Eroberungsflotte mitzufÃ¼hren, falls eine beim Angriff zerstÃ¶rt wird. Weiterhin ist es wichtig zu wissen, dass die Scarecrow eine Kolonie nur erobern kann, wenn alle Verteidigungsanlagen, ausgenommen des Schutzschildes, zerstÃ¶rt wurden.\r\n\r\nAnekdote:\r\nBei seinem Auftritt in einer schÃ¤bigen Kneipe sagte ein Comedian: "Was ist der Unterschied zwischen Scarecrows und Ex-Frauen? Na ist doch klar, es gibt gar keinen. Beide sind hÃ¤ssliche Vogelscheuchen und nehmen einem die HÃ¤user weg!"', 200000, 300000, 1200, 1500, 0, 160, 0, 0, 0, 11),
(12, 'Bomber', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 12),
(13, 'Elektronenwoofer', 'Basierend auf den technologischen GrundsÃ¤tzen der ehemaligen Erde, sind diese schwachen Defensivanlagen nur mit Treibladungen versehen, wodurch nur ein geringer Schaden verursacht werden kann.', 300, 100, 150, 0, 0, 0, 0, 0, 0, 1),
(14, 'Protonenwoofer', 'Ein auf dem Beschuss mittels niederenergetischer Protonen basierende Turm, der jedoch nur geringe SchÃ¤den anrichtet. Trotz seiner eher mittelmÃ¤ÃŸigen Werte kann er, wenn in groÃŸen Mengen errichtet, schlachtentscheidend sein, da sein KostenverhÃ¤ltnis sehr gÃ¼nstig ist.', 600, 400, 250, 0, 0, 0, 0, 0, 0, 2),
(15, 'Neutronenwoofer', 'Letzter Turmneubau basierend auf den KonstruktionsplÃ¤nen von Alt-Erdbewohnern. Diese TÃ¼rme basieren noch auf niederfrequenten Neutronen, welche unter Dauerbeschuss eine atomare Kettenreaktion im gegnerischen Objekt hervorrufen und somit fast doppelt so leistungsstark wie Protonenwoofer sind.', 1000, 600, 400, 0, 0, 0, 0, 0, 0, 0),
(16, 'Elektronensequenzer', 'Dieser erste auf der neuen Elektronensequenzertechnologie basierende Verteidigungsturm kann durch seine neue Technologie und seine weit ausgereifte ZielfÃ¼hrung schon im Anfangsstadium die Werte eines voll entwickelten Kampflugzeuges der selben Technologieart erreichen. Dadurch wird er besonders am Anfang unersetzlich fÃ¼r eine jede StÃ¤dtische Verteidigungsanlage.', 2000, 800, 500, 0, 0, 0, 0, 0, 0, 0),
(17, 'Protonensequenzer', 'Dieser Turm ist das RÃ¼ckgrat einer jeden Verteidigung. Denn dieser Sequenzer, der unter Mithilfe eines vÃ¶llig neuen Technologiezentrums entwickelt wurde, besitzt einen minimalen Energieverbrauch, da er teilweise die Energie selbst dem Angreifer entzieht und sie in ihm zur Detonation bringt.', 3500, 2000, 750, 0, 0, 0, 0, 0, 0, 0),
(18, 'Neutronensequenzer', 'StÃ¤rkster jemals errichteter Turm, der neben einer groÃŸen Biopositronie auch Ã¼ber ein unerschÃ¶pfliches Energiereservoir verfÃ¼gt, da er seine Energie aus der heimatlichen Sonne bezieht, die gleichzeitig einen GroÃŸteil der Antineutrinos erzeugt.', 5000, 3500, 1000, 0, 0, 0, 0, 0, 0, 0),
(19, 'Kleines Transportflugzeug', 'Allgemein\r\nDer kleine Transporter ist, neben der Spionagesonde, das einzige Flugzeug, fÃ¼r das keine Technologie erforscht werden muss.\r\n\r\nEmpfehlung\r\nDie "kleine Transe", wie sie liebevoll genannt wird, ist in den ersten Wochen und Monaten die wichtigste und praktisch einzige MÃ¶glichkeit Ressourcen effektiv zu transportieren. Vor allem der geringe Verbrauch von wertvollem Sauerstoff, aber auch der zu diesem Zeitpunkt einmalig groÃŸe Laderaum machen diesen Flieger fÃ¼r alle HÃ¤ndler unentbehrlich. Aufgrund der sehr simplen und einfachen Technik, werden keine teuren Forschungsarbeiten benÃ¶tigt um dieses Flugzeug zu bauen, allerdings lÃ¤sst sich der Oxidationsantrieb des kleinen Transportflugzeug auch nicht weiter entwickeln und wer ihn einsetzen will, sollte genug Zeit und Geduld mit bringen.\r\n\r\nAnekdote\r\nMein Schwager, ein bekannter Spediteur, wollte vor einiger Zeit Weintrauben aus einer entfernten Kolonie zu seiner Heimatstadt transportieren, um sie dort zu verkaufen. Ihm stand aber nur ein kleiner Transporter zur VerfÃ¼gung. Ohne Alternative packte er die Trauben in HolzfÃ¤sser und schickte sie los. Als der Transporter ankam, war aus den Trauben der wohl kÃ¶stlichste Wein geworden, den Erde II je gesehen hatte und mein Schwager verdiente sich eine goldene Nase.', 1700, 700, 0, 700, 2500, 30, 0, 0, 0, 13),
(20, 'Mittleres Transportflugzeug', 'Allgemein\r\nDer mittlere Transporter ist mit einem teuren, aber recht schnellen Hoverantrieb ausgestattet.\r\n\r\nEmpfehlung\r\nDie "mittlere Transe", wie sie im Fachjargon bezeichnet wird, ist der Kleinen in allen Belangen Ã¼berlegen. Dank des Hoverantriebs, kann sie auch StÃ¤dte auf anderen Kontinenten in einer akzeptablen Zeit erreichen und dabei auch noch ein Vielfaches der Fracht bequem transportieren. Allerdings sind dementsprechend auch die technischen Anforderungen um Einiges hÃ¶her. Es wird nicht nur ein neuer Antrieb benÃ¶tigt, sondern es muss auch die Lagerverwaltung weiter entwickelt werden.\r\n\r\nAnekdote\r\nWenn ich eine mittlere Transe sehe, muss ich immer an meine Schulzeit zurÃ¼ckdenken. Jeder sollte ein Referat Ã¼ber einen Flugzeugtypen von Erde II halten. Ich bekam die mittlere Transe vom Lehrer zugeteilt. Das Referat war schnell gehalten. "Im Vergleich zu den anderen Transportern ist die mittlere Transe, mittel schnell, mittel teuer, kann mittel viel transportieren und verbraucht mittel viel o2." Leider bekam ich dafÃ¼r jedoch keine mittelgute Note!', 5000, 4000, 0, 1300, 10000, 70, 0, 0, 0, 14),
(21, 'GroÃŸes Transportflugzeug', 'Allgemein\r\nDer groÃŸe Transporter, ein wahres UngetÃ¼m der LÃ¼fte, besitzt einen Ã¤uÃŸerst leistungsstarken Antigravitationsantrieb\r\n\r\nEmpfehlung\r\nDie "groÃŸe Transe" ist ein Meisterwerk der Technik und nach dem Settler das zweitgrÃ¶ÃŸte Flugzeug auf Erde II. Die GrÃ¶ÃŸe ist durch ihren beeindruckend groÃŸen Laderaum bedingt, der einem unerfahrenen BÃ¼rger schon mal die Sprache verschlagen kann. Ihrem Antigravitationsantrieb ist es zu verdanken, dass die groÃŸe Transe trotz ihrer GrÃ¶ÃŸe schneller als ihre VorgÃ¤ngerin ist. Dementsprechend sind auch die Forschungskosten fÃ¼r diesen Transporter immens. Die Kosten rentieren sich jedoch allemal.\r\n\r\nAnekdote\r\nAls ich meine Oma im Altenheim besuchte, um mein Erbe ... Ã¤h, mich nach ihrem werten Befinden zu erkundigen, packte mich plÃ¶tzlich ein dÃ¼nner Arm von der Seite am Kragen und zog mich in eine Abstellkammer. Vor mir stand ein alter Mann mit wirrem Haarschopf, der meinte: "Hol mich hier raus, dann sage ich dir, wie man groÃŸe Transen mit Neutronensequenzwaffen bestÃ¼ckt!" Ich war noch vÃ¶llig verdattert, da sagte er plÃ¶tzlich: "Huch, wer sind sie denn? Warum haben sie mich in diese Kammer verschleppt? Es gibt doch Kuchen in der Cafeteria!" Vorsichtshalber ging ich am nÃ¤chsten Tag ins Patentamt, doch der Beamte kam aus dem Lachen gar nicht mehr heraus.', 30000, 25000, 0, 2000, 100000, 200, 0, 0, 0, 15);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `usarios`
--

CREATE TABLE IF NOT EXISTS `usarios` (
  `ID` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `sitter` varchar(20) NOT NULL DEFAULT '',
  `sitter_confirmation` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `sitter_time` int(10) unsigned NOT NULL DEFAULT '0',
  `sitting_time` int(10) unsigned NOT NULL DEFAULT '0',
  `login` int(11) NOT NULL DEFAULT '0',
  `logged_in` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `last_action` int(10) unsigned NOT NULL DEFAULT '0',
  `last_views` int(10) unsigned NOT NULL,
  `following_logins` tinyint(3) unsigned NOT NULL,
  `text` text NOT NULL,
  `signature` text NOT NULL,
  `alliance` varchar(25) NOT NULL DEFAULT '',
  `alliance_status` enum('member','admin','founder') NOT NULL DEFAULT 'member',
  `alliance_rank` varchar(255) NOT NULL DEFAULT '',
  `alliance_join` int(10) unsigned NOT NULL COMMENT 'Beitrittsdatum fÃƒÂ¼r Allianzstadt',
  `voting` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `votes` varchar(1) NOT NULL DEFAULT '0',
  `points` int(11) unsigned NOT NULL DEFAULT '5',
  `tech_points` int(11) unsigned NOT NULL DEFAULT '0',
  `t_end_time` int(11) unsigned NOT NULL DEFAULT '0',
  `t_current_build` varchar(255) NOT NULL DEFAULT '',
  `t_start_city` varchar(11) NOT NULL DEFAULT '',
  `msg` varchar(255) NOT NULL DEFAULT '',
  `t_next_build` varchar(255) DEFAULT NULL,
  `t_end_time_next` int(11) DEFAULT NULL,
  `t_start_city_next` varchar(11) DEFAULT NULL,
  `msg_next` varchar(255) DEFAULT NULL,
  `t_oxidationsdrive` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_hoverdrive` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_antigravitydrive` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_electronsequenzweapons` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_protonsequenzweapons` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_neutronsequenzweapons` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_consumption_reduction` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_plane_size` smallint(5) unsigned NOT NULL DEFAULT '0',
  `t_computer_management` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_depot_management` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_water_compression` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_mining` smallint(6) unsigned NOT NULL DEFAULT '0',
  `t_shield_tech` smallint(5) unsigned NOT NULL DEFAULT '0',
  `power` int(11) unsigned NOT NULL DEFAULT '0',
  `toplist_update` int(11) NOT NULL DEFAULT '0',
  `fame_own` int(11) unsigned NOT NULL DEFAULT '0',
  `fame` int(11) unsigned NOT NULL DEFAULT '0',
  `alliance_seen` int(11) NOT NULL,
  `flightstats` varchar(1) NOT NULL DEFAULT '0',
  `medals` varchar(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userdata`
--

CREATE TABLE IF NOT EXISTS `userdata` (
`ID` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `nick_change` enum('N','Y') NOT NULL DEFAULT 'N',
  `user_path` varchar(255) NOT NULL DEFAULT 'http://www.escape-to-space.de',
  `user_path_css` varchar(255) NOT NULL DEFAULT 'http://www.escape-to-space.de/css/new.css',
  `email` varchar(255) NOT NULL DEFAULT '',
  `email_new` varchar(255) NOT NULL DEFAULT '',
  `email_confirm` enum('N','Y') NOT NULL DEFAULT 'N',
  `password` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `zip` varchar(5) NOT NULL DEFAULT '',
  `location` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `birthday` varchar(12) NOT NULL DEFAULT '',
  `sex` enum('m','w') NOT NULL DEFAULT 'm',
  `plunder_iridium` varchar(1) NOT NULL,
  `plunder_holzium` varchar(1) NOT NULL,
  `plunder_water` varchar(1) NOT NULL,
  `plunder_oxygen` varchar(1) NOT NULL,
  `register` int(11) NOT NULL DEFAULT '0',
  `holiday` int(11) NOT NULL DEFAULT '0',
  `holiday2` tinyint(1) NOT NULL COMMENT '1=normal;2=lÃƒÂ¤ngereAbwesenheit',
  `time_block` int(11) NOT NULL DEFAULT '0',
  `delacc` int(11) NOT NULL DEFAULT '0',
  `delacc2` enum('N','K','A') NOT NULL DEFAULT 'A',
  `confirmation` enum('N','Y') NOT NULL DEFAULT 'N',
  `multi` enum('N','Y') NOT NULL DEFAULT 'N',
  `noipchk` enum('N','Y') NOT NULL DEFAULT 'N',
  `ip` varchar(32) NOT NULL,
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `protocol_level` enum('kein','hoch') NOT NULL DEFAULT 'kein',
  `show_attacks` tinyint(4) NOT NULL DEFAULT '1',
  `confirm_code` varchar(32) DEFAULT NULL,
  `name_affix` varchar(64) DEFAULT NULL,
  `ad_mode` enum('A','I','N') NOT NULL DEFAULT 'A',
  `acl` enum('NONE','SUPPORT','ADMIN','CENSOR') NOT NULL DEFAULT 'NONE',
  `abo_news` enum('N','Y') NOT NULL DEFAULT 'N',
  `user_captcha_blocked` enum('yes','no') NOT NULL DEFAULT 'no',
  `user_captcha_last_try` int(11) NOT NULL,
  `user_captcha_wrong_counter` int(11) NOT NULL,
  `user_captcha_free` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `user` varchar(20) NOT NULL,
  `vote1` int(11) NOT NULL,
  `vote2` int(11) NOT NULL,
  `vote3` int(11) NOT NULL,
  `vote4` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `voting`
--

CREATE TABLE IF NOT EXISTS `voting` (
  `tag` varchar(25) NOT NULL DEFAULT '',
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer1` varchar(255) NOT NULL DEFAULT '',
  `answer1_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer2` varchar(255) NOT NULL DEFAULT '',
  `answer2_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer3` varchar(255) NOT NULL DEFAULT '',
  `answer3_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer4` varchar(255) NOT NULL DEFAULT '',
  `answer4_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer5` varchar(255) NOT NULL DEFAULT '',
  `answer5_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer6` varchar(255) NOT NULL DEFAULT '',
  `answer6_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer7` varchar(255) NOT NULL DEFAULT '',
  `answer7_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer8` varchar(255) NOT NULL DEFAULT '',
  `answer8_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer9` varchar(255) NOT NULL DEFAULT '',
  `answer9_count` tinyint(4) NOT NULL DEFAULT '0',
  `answer10` varchar(255) NOT NULL DEFAULT '',
  `answer10_count` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `voting_extern`
--

CREATE TABLE IF NOT EXISTS `voting_extern` (
  `user` varchar(20) NOT NULL,
  `zeit` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wars`
--

CREATE TABLE IF NOT EXISTS `wars` (
`id` int(10) unsigned NOT NULL,
  `approved` enum('Y','N') NOT NULL DEFAULT 'N',
  `denied` enum('Y','N') NOT NULL DEFAULT 'N',
  `cancelled` enum('Y','N') NOT NULL DEFAULT 'N',
  `open` enum('Y','N') NOT NULL DEFAULT 'N',
  `config` text NOT NULL,
  `winner` varchar(25) DEFAULT NULL,
  `config_version` tinyint(4) NOT NULL DEFAULT '1',
  `begin` int(11) DEFAULT NULL,
  `end` int(11) DEFAULT NULL,
  `fame_A` double NOT NULL DEFAULT '0',
  `fame_B` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `war_party`
--

CREATE TABLE IF NOT EXISTS `war_party` (
`id` int(11) NOT NULL,
  `tag` varchar(25) NOT NULL,
  `begin_state` text NOT NULL,
  `end_state` text NOT NULL,
  `war_id` int(11) NOT NULL,
  `side` enum('A','B') NOT NULL DEFAULT 'A',
  `accepted` enum('Y','N') NOT NULL DEFAULT 'N',
  `accepted_version` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_attack`
--

CREATE TABLE IF NOT EXISTS `_attack` (
`id` int(10) unsigned NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `user` varchar(20) NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_bot_user`
--

CREATE TABLE IF NOT EXISTS `_bot_user` (
`id` int(10) unsigned NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_cron`
--

CREATE TABLE IF NOT EXISTS `_cron` (
  `work` char(1) NOT NULL DEFAULT 'N',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `file` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `_cron`
--

INSERT INTO `_cron` (`work`, `time`, `file`) VALUES
('N', 1408903861, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_manipulaion`
--

CREATE TABLE IF NOT EXISTS `_manipulaion` (
`id` int(10) unsigned NOT NULL,
  `request` text NOT NULL,
  `zeit` int(10) unsigned NOT NULL DEFAULT '0',
  `ort` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_matrix_igm`
--

CREATE TABLE IF NOT EXISTS `_matrix_igm` (
`id` int(11) NOT NULL,
  `dir` int(10) unsigned NOT NULL DEFAULT '0',
  `sender` varchar(20) NOT NULL DEFAULT '',
  `recipient` varchar(20) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `seen` enum('N','Y') NOT NULL DEFAULT 'N',
  `confirm` enum('N','Y','S') NOT NULL DEFAULT 'N',
  `topic` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_multi`
--

CREATE TABLE IF NOT EXISTS `_multi` (
  `user` varchar(50) NOT NULL,
  `same_pcid` int(11) NOT NULL,
  `same_ip` int(11) NOT NULL,
  `same_time` int(11) NOT NULL,
  `same_ally` int(11) NOT NULL,
  `same_useragent` int(11) NOT NULL,
  `interaction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_multi_seen`
--

CREATE TABLE IF NOT EXISTS `_multi_seen` (
  `user` varchar(50) NOT NULL,
  `supporter` varchar(50) NOT NULL,
  `seen` varchar(1) NOT NULL,
  `bearbeitet` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_tech`
--

CREATE TABLE IF NOT EXISTS `_tech` (
`id` int(10) unsigned NOT NULL,
  `query` text NOT NULL,
  `user` varchar(20) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ort` varchar(60) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `_temp_multi`
--

CREATE TABLE IF NOT EXISTS `_temp_multi` (
  `user` varchar(50) NOT NULL,
  `same_pcid` int(11) NOT NULL,
  `same_ip` int(11) NOT NULL,
  `same_time` int(11) NOT NULL,
  `same_ally` int(11) NOT NULL,
  `same_useragent` int(11) NOT NULL,
  `interaction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actions`
--
ALTER TABLE `actions`
 ADD PRIMARY KEY (`id`), ADD KEY `f_arrival` (`f_arrival`), ADD KEY `city` (`city`), ADD KEY `user` (`user`), ADD KEY `f_target` (`f_target`), ADD KEY `f_id` (`f_id`), ADD KEY `session_id` (`session_id`), ADD KEY `f_target_user` (`f_target_user`), ADD KEY `attack_deny_id` (`attack_deny_id`);

--
-- Indexes for table `activity_stats`
--
ALTER TABLE `activity_stats`
 ADD PRIMARY KEY (`time`);

--
-- Indexes for table `admin_agb_delict`
--
ALTER TABLE `admin_agb_delict`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_faq`
--
ALTER TABLE `admin_faq`
 ADD PRIMARY KEY (`id`), ADD KEY `cat` (`cat`);

--
-- Indexes for table `admin_faq_cat`
--
ALTER TABLE `admin_faq_cat`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_login_msgs`
--
ALTER TABLE `admin_login_msgs`
 ADD PRIMARY KEY (`id`), ADD KEY `time` (`time`);

--
-- Indexes for table `adressbook`
--
ALTER TABLE `adressbook`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`), ADD KEY `group` (`gid`);

--
-- Indexes for table `adressbook_groups`
--
ALTER TABLE `adressbook_groups`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alliances`
--
ALTER TABLE `alliances`
 ADD PRIMARY KEY (`ID`), ADD KEY `points` (`points`), ADD KEY `members` (`members`);

--
-- Indexes for table `alliances_building`
--
ALTER TABLE `alliances_building`
 ADD PRIMARY KEY (`tag`,`build_id`);

--
-- Indexes for table `alliance_ads`
--
ALTER TABLE `alliance_ads`
 ADD PRIMARY KEY (`id`), ADD KEY `tagapprovedcredit` (`tag`,`approved`,`credit`), ADD KEY `credit_approved_idx` (`credit`,`approved`);

--
-- Indexes for table `alliance_applications`
--
ALTER TABLE `alliance_applications`
 ADD PRIMARY KEY (`user`), ADD KEY `tag` (`tag`);

--
-- Indexes for table `artefakte`
--
ALTER TABLE `artefakte`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `artefakte_`
--
ALTER TABLE `artefakte_`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `asteroids`
--
ALTER TABLE `asteroids`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attack_denies`
--
ALTER TABLE `attack_denies`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`), ADD KEY `city` (`city`);

--
-- Indexes for table `chronicle`
--
ALTER TABLE `chronicle`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
 ADD PRIMARY KEY (`ID`), ADD KEY `user` (`user`), ADD KEY `b_current_build` (`b_current_build`), ADD KEY `b_end_time` (`b_end_time`), ADD KEY `b_next_build` (`b_next_build`), ADD KEY `ID` (`ID`,`user`,`city`,`alliance`);

--
-- Indexes for table `city_history`
--
ALTER TABLE `city_history`
 ADD PRIMARY KEY (`city`,`time`);

--
-- Indexes for table `city_tmp`
--
ALTER TABLE `city_tmp`
 ADD PRIMARY KEY (`city`), ADD KEY `user` (`user`), ADD KEY `b_current_build` (`b_current_build`), ADD KEY `b_end_time` (`b_end_time`), ADD KEY `b_next_build` (`b_next_build`);

--
-- Indexes for table `delete_reason`
--
ALTER TABLE `delete_reason`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`), ADD KEY `toshow_date_idx` (`date`,`to_show`);

--
-- Indexes for table `extern_voting`
--
ALTER TABLE `extern_voting`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `flightstats`
--
ALTER TABLE `flightstats`
 ADD PRIMARY KEY (`user`,`type`,`ad`);

--
-- Indexes for table `flugtimer`
--
ALTER TABLE `flugtimer`
 ADD PRIMARY KEY (`ID`,`user`,`town`,`art`);

--
-- Indexes for table `global`
--
ALTER TABLE `global`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_logs`
--
ALTER TABLE `global_logs`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `html_meta`
--
ALTER TABLE `html_meta`
 ADD PRIMARY KEY (`page`);

--
-- Indexes for table `jobs_build`
--
ALTER TABLE `jobs_build`
 ADD PRIMARY KEY (`city`,`end_time`);

--
-- Indexes for table `jobs_defense`
--
ALTER TABLE `jobs_defense`
 ADD PRIMARY KEY (`id`), ADD KEY `city` (`city`), ADD KEY `end_time` (`end_time`), ADD KEY `user` (`user`);

--
-- Indexes for table `jobs_planes`
--
ALTER TABLE `jobs_planes`
 ADD PRIMARY KEY (`id`), ADD KEY `city` (`city`), ADD KEY `end_time` (`end_time`), ADD KEY `user` (`user`);

--
-- Indexes for table `logs_login`
--
ALTER TABLE `logs_login`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs_support`
--
ALTER TABLE `logs_support`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `long_term_flights`
--
ALTER TABLE `long_term_flights`
 ADD PRIMARY KEY (`id`), ADD KEY `f_arrival` (`f_arrival`), ADD KEY `city` (`city`), ADD KEY `user` (`user`), ADD KEY `f_target` (`f_target`), ADD KEY `f_id` (`f_id`), ADD KEY `session_id` (`session_id`), ADD KEY `f_target_user` (`f_target_user`), ADD KEY `attack_deny_id` (`attack_deny_id`);

--
-- Indexes for table `medals`
--
ALTER TABLE `medals`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `multi_angemeldete`
--
ALTER TABLE `multi_angemeldete`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `multi_angemeldete_doppel_ip`
--
ALTER TABLE `multi_angemeldete_doppel_ip`
 ADD PRIMARY KEY (`user`,`doppel_ip_user`);

--
-- Indexes for table `multi_check`
--
ALTER TABLE `multi_check`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `multi_iphash`
--
ALTER TABLE `multi_iphash`
 ADD PRIMARY KEY (`iphash`);

--
-- Indexes for table `multi_sessions`
--
ALTER TABLE `multi_sessions`
 ADD PRIMARY KEY (`id`), ADD KEY `id_hash` (`id_hash`), ADD KEY `sess_id` (`sess_id`), ADD KEY `support` (`user`,`login_time`,`ip`), ADD KEY `pc_id` (`pc_id`), ADD KEY `user` (`user`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
 ADD PRIMARY KEY (`email`);

--
-- Indexes for table `news_ber`
--
ALTER TABLE `news_ber`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `bid` (`attack_bid`), ADD KEY `user` (`attack_user`), ADD KEY `origin` (`attack_city`), ADD KEY `seen` (`attack_seen`), ADD KEY `seen_sitter` (`attack_seen_sitter`), ADD KEY `nearly_all` (`attack_user`,`attack_city`,`attack_seen`,`time`);

--
-- Indexes for table `news_ber_`
--
ALTER TABLE `news_ber_`
 ADD PRIMARY KEY (`ID`,`type`,`ad`);

--
-- Indexes for table `news_directories`
--
ALTER TABLE `news_directories`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`), ADD KEY `user_name_idx` (`user`,`name`);

--
-- Indexes for table `news_er`
--
ALTER TABLE `news_er`
 ADD PRIMARY KEY (`id`), ADD KEY `city` (`city`), ADD KEY `seen` (`seen`), ADD KEY `seen_sitter` (`seen_sitter`);

--
-- Indexes for table `news_igm_umid`
--
ALTER TABLE `news_igm_umid`
 ADD PRIMARY KEY (`id`), ADD KEY `owner_id_dir_idx` (`owner`,`dir`,`seen`), ADD KEY `rec_dir_seen_idx` (`recipient`(15),`dir`,`seen`), ADD KEY `dir_seen_idx` (`dir`,`seen`);

--
-- Indexes for table `news_msg`
--
ALTER TABLE `news_msg`
 ADD PRIMARY KEY (`id`), ADD KEY `tag` (`tag`);

--
-- Indexes for table `news_support`
--
ALTER TABLE `news_support`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_city_history`
--
ALTER TABLE `new_city_history`
 ADD PRIMARY KEY (`city`,`time`);

--
-- Indexes for table `new_tutorial`
--
ALTER TABLE `new_tutorial`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `new_user`
--
ALTER TABLE `new_user`
 ADD PRIMARY KEY (`user`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `paypal_payment_info`
--
ALTER TABLE `paypal_payment_info`
 ADD PRIMARY KEY (`txnid`);

--
-- Indexes for table `plane_trade`
--
ALTER TABLE `plane_trade`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plane_transactions`
--
ALTER TABLE `plane_transactions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `query_error_log`
--
ALTER TABLE `query_error_log`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `query_log`
--
ALTER TABLE `query_log`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
 ADD PRIMARY KEY (`id`), ADD KEY `tag` (`tag`);

--
-- Indexes for table `sperrliste_email`
--
ALTER TABLE `sperrliste_email`
 ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sperrliste_email_domain`
--
ALTER TABLE `sperrliste_email_domain`
 ADD PRIMARY KEY (`domain`);

--
-- Indexes for table `sperrliste_igm`
--
ALTER TABLE `sperrliste_igm`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `sperrliste_username`
--
ALTER TABLE `sperrliste_username`
 ADD PRIMARY KEY (`username`);

--
-- Indexes for table `supporterdata`
--
ALTER TABLE `supporterdata`
 ADD UNIQUE KEY `supportername` (`supporter`);

--
-- Indexes for table `toplist_alliances`
--
ALTER TABLE `toplist_alliances`
 ADD PRIMARY KEY (`pos`), ADD UNIQUE KEY `tag` (`tag`), ADD KEY `points` (`points`), ADD KEY `average` (`average`);

--
-- Indexes for table `toplist_city`
--
ALTER TABLE `toplist_city`
 ADD PRIMARY KEY (`pos`), ADD UNIQUE KEY `city` (`city`), ADD KEY `user` (`user`), ADD KEY `points` (`points`);

--
-- Indexes for table `toplist_user`
--
ALTER TABLE `toplist_user`
 ADD PRIMARY KEY (`pos`), ADD UNIQUE KEY `user` (`user`), ADD KEY `points` (`points`);

--
-- Indexes for table `toplist_user_power`
--
ALTER TABLE `toplist_user_power`
 ADD PRIMARY KEY (`pos`), ADD UNIQUE KEY `user` (`user`), ADD KEY `power` (`power`);

--
-- Indexes for table `tutorial`
--
ALTER TABLE `tutorial`
 ADD PRIMARY KEY (`user`,`page`);

--
-- Indexes for table `type_alliance`
--
ALTER TABLE `type_alliance`
 ADD PRIMARY KEY (`type`);

--
-- Indexes for table `type_building`
--
ALTER TABLE `type_building`
 ADD PRIMARY KEY (`type`);

--
-- Indexes for table `type_plane`
--
ALTER TABLE `type_plane`
 ADD PRIMARY KEY (`type`);

--
-- Indexes for table `usarios`
--
ALTER TABLE `usarios`
 ADD PRIMARY KEY (`user`), ADD KEY `alliance` (`alliance`), ADD KEY `sitter` (`sitter`), ADD KEY `t_end_time` (`t_end_time`), ADD KEY `last_action_idx` (`last_action`,`logged_in`), ADD KEY `ID` (`ID`,`user`,`sitter`);

--
-- Indexes for table `userdata`
--
ALTER TABLE `userdata`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `email` (`email`), ADD UNIQUE KEY `ID` (`ID`,`user`), ADD KEY `ID_2` (`ID`,`user`,`email`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `voting`
--
ALTER TABLE `voting`
 ADD PRIMARY KEY (`tag`);

--
-- Indexes for table `voting_extern`
--
ALTER TABLE `voting_extern`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `wars`
--
ALTER TABLE `wars`
 ADD PRIMARY KEY (`id`), ADD KEY `challenger` (`approved`), ADD KEY `winner` (`winner`);

--
-- Indexes for table `war_party`
--
ALTER TABLE `war_party`
 ADD PRIMARY KEY (`id`), ADD KEY `war_id` (`war_id`);

--
-- Indexes for table `_attack`
--
ALTER TABLE `_attack`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_bot_user`
--
ALTER TABLE `_bot_user`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`);

--
-- Indexes for table `_cron`
--
ALTER TABLE `_cron`
 ADD PRIMARY KEY (`work`);

--
-- Indexes for table `_manipulaion`
--
ALTER TABLE `_manipulaion`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_matrix_igm`
--
ALTER TABLE `_matrix_igm`
 ADD PRIMARY KEY (`id`), ADD KEY `recipient` (`recipient`), ADD KEY `dir` (`dir`);

--
-- Indexes for table `_multi`
--
ALTER TABLE `_multi`
 ADD PRIMARY KEY (`user`);

--
-- Indexes for table `_tech`
--
ALTER TABLE `_tech`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_temp_multi`
--
ALTER TABLE `_temp_multi`
 ADD PRIMARY KEY (`user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actions`
--
ALTER TABLE `actions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_agb_delict`
--
ALTER TABLE `admin_agb_delict`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_faq`
--
ALTER TABLE `admin_faq`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_faq_cat`
--
ALTER TABLE `admin_faq_cat`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_login_msgs`
--
ALTER TABLE `admin_login_msgs`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `adressbook`
--
ALTER TABLE `adressbook`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `adressbook_groups`
--
ALTER TABLE `adressbook_groups`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alliances`
--
ALTER TABLE `alliances`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alliance_ads`
--
ALTER TABLE `alliance_ads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `artefakte`
--
ALTER TABLE `artefakte`
MODIFY `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `asteroids`
--
ALTER TABLE `asteroids`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attack_denies`
--
ALTER TABLE `attack_denies`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chronicle`
--
ALTER TABLE `chronicle`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delete_reason`
--
ALTER TABLE `delete_reason`
MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `extern_voting`
--
ALTER TABLE `extern_voting`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `global`
--
ALTER TABLE `global`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `global_logs`
--
ALTER TABLE `global_logs`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jobs_defense`
--
ALTER TABLE `jobs_defense`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jobs_planes`
--
ALTER TABLE `jobs_planes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logs_login`
--
ALTER TABLE `logs_login`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logs_support`
--
ALTER TABLE `logs_support`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `long_term_flights`
--
ALTER TABLE `long_term_flights`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `multi_check`
--
ALTER TABLE `multi_check`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `multi_sessions`
--
ALTER TABLE `multi_sessions`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news_ber`
--
ALTER TABLE `news_ber`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news_directories`
--
ALTER TABLE `news_directories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news_er`
--
ALTER TABLE `news_er`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news_igm_umid`
--
ALTER TABLE `news_igm_umid`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news_msg`
--
ALTER TABLE `news_msg`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news_support`
--
ALTER TABLE `news_support`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `plane_trade`
--
ALTER TABLE `plane_trade`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `plane_transactions`
--
ALTER TABLE `plane_transactions`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `query_error_log`
--
ALTER TABLE `query_error_log`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `query_log`
--
ALTER TABLE `query_log`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `toplist_alliances`
--
ALTER TABLE `toplist_alliances`
MODIFY `pos` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `toplist_city`
--
ALTER TABLE `toplist_city`
MODIFY `pos` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `toplist_user`
--
ALTER TABLE `toplist_user`
MODIFY `pos` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `toplist_user_power`
--
ALTER TABLE `toplist_user_power`
MODIFY `pos` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `type_alliance`
--
ALTER TABLE `type_alliance`
MODIFY `type` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `type_building`
--
ALTER TABLE `type_building`
MODIFY `type` tinyint(3) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `type_plane`
--
ALTER TABLE `type_plane`
MODIFY `type` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `userdata`
--
ALTER TABLE `userdata`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wars`
--
ALTER TABLE `wars`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `war_party`
--
ALTER TABLE `war_party`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_attack`
--
ALTER TABLE `_attack`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_bot_user`
--
ALTER TABLE `_bot_user`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_manipulaion`
--
ALTER TABLE `_manipulaion`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_matrix_igm`
--
ALTER TABLE `_matrix_igm`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_tech`
--
ALTER TABLE `_tech`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
